<?php

require_once('includes/authorization.php'); // подключаем файл с авторизацией (здесь объявляется $user_id)
require_once('includes/helpers.php'); // подключаем файл с функциями
require_once('includes/mysqli_connect.php'); // подключаем файл с подключением к БД

$title = 'readme: популярное';

$get_type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT) ?? 0; // обработка типа данных из GET

require_once('includes/likes.php'); // подключаем файл для обработки лайков
require_once('includes/content_types.php'); // подключаем файл массива с типами контента (здесь объявляется $content_types)

// формируем ленту постов

$sql = '
    SELECT
        posts.num_views as views,
        posts.dt_add as datetime,
        posts.header as header,
        posts.user_id as user_id,
        posts.id as id,
        posts.author as author,
        posts.site_url as url,
        CONCAT (posts.text, posts.image_url, posts.video_url) as content,

        content_type.class as type,

        users.username as username,
        users.avatar as avatar,

        COUNT(DISTINCT likes.user_id) as likes_count,
        COUNT(DISTINCT comments.id) as comments_count

    FROM posts
        LEFT JOIN users ON posts.user_id = users.id
        LEFT JOIN content_type ON posts.content_type_id = content_type.id
        LEFT JOIN likes ON likes.post_id = posts.id
        LEFT JOIN comments ON comments.post_id = posts.id
';
if ($get_type >= 1 and $get_type <= 5) {
    $sql .= ' WHERE posts.content_type_id = ' . $get_type;
}
$sql .= '
    GROUP BY
        posts.id
    ORDER BY num_views DESC
';

require_once('includes/pagination.php'); // добавляем в запрос параметры пагинации (объявляется $prev_link и $next_link)

$posts = con_sql($con, $sql, 'all'); // получаем ассоциативный массив с постами через функцию проверки

$active_all = '';
if ($get_type < 1 or $get_type > 5) { // зажигать вкладку "все" или нет
    $active_all = ' filters__button--active';
}

// БЛОК ГЕНЕРАЦИИ ШАБЛОНА
$page_content = include_template('popular_template.php', [
    'content_types' => $content_types,
    'posts' => $posts,
    'get_type' => $get_type,
    'active_all' => $active_all,
    'prev_link' => $prev_link,
    'next_link' => $next_link
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title]);
print($layout_content);
