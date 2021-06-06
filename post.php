<?php

require_once('includes/authorization.php'); // подключаем файл с авторизацией (здесь объявляется $user_id)
require_once('includes/helpers.php'); // подключаем файл с функциями

$title = '';

// БЛОК ОБРАБОТКИ GET-ЗАПРОСА
$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? false; // если $_GET['id'] есть, то чистим

if ($get_id) { // если ?id= есть, то соединяемся с БД
    require_once('includes/mysqli_connect.php'); // подключаем файл с подключением к БД
} else {
    require_once('includes/goto_404.php'); // если ?id= нет, то 404
}

// БЛОК ЛАЙКА
require_once('includes/likes.php'); // подключаем файл для добавления лайка

// БЛОК ПОЛУЧЕНИЯ ДАННЫХ ПОСТА
$sql = '
    SELECT
        posts.id as id,
        posts.dt_add as dt,
        posts.header as header,
        CONCAT (posts.text, posts.image_url, posts.video_url) as post_content,
        posts.author as author,
        posts.site_url as site_url,
        posts.num_views as num_views,

        posts.user_id as user_id,
        users.avatar as avatar,
        users.username as username,
        users.dt_add as user_dt,
        users.email as email,

        posts.content_type_id as content_id,
        content_type.class as content_type,

        COUNT(DISTINCT likes.user_id) as likes_count,
        COUNT(DISTINCT comments.id) as comments_count,
        COUNT(DISTINCT subscribes.subscriber_id) as subscribers_count,
        COUNT(DISTINCT p.id) as posts_count,
        COUNT(DISTINCT p2.id) as reposts_count

    FROM posts
        LEFT JOIN users ON posts.user_id = users.id
        LEFT JOIN content_type ON posts.content_type_id = content_type.id
        LEFT JOIN likes ON posts.id = likes.post_id
        LEFT JOIN comments ON posts.id = comments.post_id
        LEFT JOIN subscribes ON posts.user_id = subscribes.sourcer_id
        LEFT JOIN posts as p ON posts.user_id = p.user_id
        LEFT JOIN posts as p2 ON posts.id = p2.repost
    WHERE posts.id = ' . $get_id . '
    GROUP BY
        posts.id
    ';
$post = con_sql($con, $sql); // получаем ассоциативный массив с содержимым поста

// БЛОК ПОДПИСКИ
$sourcer_id = (isset($post['user_id'])) ? $post['user_id'] : null; // id пользователя, на которого подписываемся
$sourcer_name = (isset($post['username'])) ? $post['username'] : null; // имя пользователя, на которого подписываемся
$sourcer_email = (isset($post['email'])) ? $post['email'] : null; // email пользователя, на которого подписываемся
require_once('includes/subscribe.php'); // подключаем файл для подписки/отписки и отправки уведомления

// БЛОК КОММЕНТАРИЕВ
$comments = [];
$post_id = (isset($post['id'])) ? $post['id'] : null;
$post_user_id = (isset($post['user_id'])) ? $post['user_id'] : null;
if (count($post) > 0) { // если пост найден, то показываем комментарии и можем добавить новый
    $errors = null;
    if (isset($_POST['submit_comment'])) { // если добавляется комментарий, то смотрим на ошибки
        $user_comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS) ?? false;
        if ($user_comment !== false && empty($user_comment)) {
            $errors = 'Это поле обязательно к заполнению';
        } elseif (mb_strlen($user_comment, 'UTF-8') < 4) {
            $errors = 'Комментарий должен содержать не менее 4 символов';
        }
    }
    if (isset($_POST['submit_comment']) && $errors === null) { // если ошибок нет, то формируем запрос на добавление и отправляем в БД
        $sql = '
            INSERT INTO comments SET
                user_id = "' . $user_id . '",
                post_id = "' . $post_id . '",
                content = "' . $user_comment . '"
        ';
        $result = mysqli_query($con, $sql);
        if (!$result) { // проверяем на успешное добавление в БД
            $error = mysqli_error($con);
            print("Ошибка MySQL: " . $error);
        } else {
            header('Location: /profile.php?id=' . $post_user_id); // если ошибок нет, то редиректим на страницу текущего юзера
        }
    }
    if (isset($post['comments_count']) && $post['comments_count'] > 0) { // если есть комментарии к посту, то выводим их
        $sql = '
        SELECT
            comments.dt_add as dt_add,
            comments.content as content,
            users.avatar as avatar,
            users.username as name
        FROM comments
            LEFT JOIN users ON comments.user_id = users.id
        WHERE comments.post_id = ' . $post_id . '
        ORDER BY comments.dt_add DESC
        ';
        $comments = con_sql($con, $sql, 'all');
    }
} else {
    require_once('includes/goto_404.php'); // если пост не найден, то редиректим на 404
}

// БЛОК ГЕНЕРАЦИИ ШАБЛОНА
$post_header = (isset($post['header'])) ? $post['header'] : null;
$page_content = include_template('post_template.php', [
    'post' => $post,
    'errors' => $errors,
    'subscribe' => $subscribe,
    'comments' => $comments,
    'get_id' => $get_id
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'readme: ' . $post_header]);
print($layout_content);
