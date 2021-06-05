<?php

require_once('includes/authorization.php'); // подключаем файл с авторизацией (здесь объявляется $user_id)
require_once('includes/helpers.php'); // подключаем файл с функциями
require_once('includes/mysqli_connect.php'); // подключаем файл с подключением к БД
require_once('includes/content_types.php'); // подключаем файл с типами контента (здесь объявляется $content_types)
require_once('includes/dictionary_titles.php'); // подключаем файл с типами контента (здесь объявляется $content_types)

// название страницы
$title = isset($titles['add.php']) ? $titles['add.php'] : 'readmi';

// выбираем, какой таб зажигать (по умолчанию text)
$post_type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS) ?? false;
$active_tab = !empty($post_type) ? $post_type : 'text';

// подключаем файл с названиями полей и текстами ошибок
require_once('includes/names.php');

// БЛОК ВАЛИДАЦИИ ДАННЫХ

// формируем массив ошибок
$errors = [];

// проверяем поля на заполненность

// заголовок
$post_header = filter_input(INPUT_POST, 'header', FILTER_SANITIZE_SPECIAL_CHARS) ?? false;
$errors['header'] = ($post_header !== false && empty($post_header)) ? $errors_header : null;
// теги
$post_tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_SPECIAL_CHARS) ?? false;
$errors['tags'] = ($post_tags !== false && empty($post_tags)) ? $errors_tags : null;
// автор
$post_author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_SPECIAL_CHARS) ?? false;
$errors['author'] = ($post_author !== false && empty($post_author)) ? $errors_author : null; // переписать в функцию
// контент
$post_content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS) ?? false;
$post_file = (isset($_FILES['file'])) ? $_FILES['file'] : false;
$post_file_type = (isset($post_file['type'])) ? $post_file['type'] : null;
// если поле пустое для всех кроме фото, а для фото -- если и поле картинки пустое и поле файла пустое
if (($post_content !== false && empty($post_content)) &&
        (($post_type !== 'photo') || ($post_type === 'photo' && empty($post_file_type)))) {
    $content_subhead = (isset($content_names[$post_type]['input_name'])) ? $content_names[$post_type]['input_name'] : null;
    $errors['content'] = $errors_content;
    $errors['content']['subhead'] = $content_subhead;
}

// проверяем поля на остальные ошибки

// правильность ссылок
if (!empty($post_content) && ($post_type === 'photo' || $post_type === 'link' || $post_type === 'video')) {
    if (!filter_var($post_content, FILTER_VALIDATE_URL)) { // проверяем формат ссылки
        $content_subhead = (isset($content_names[$post_type]['input_name'])) ? $content_names[$post_type]['input_name'] : null;
        $errors['content'] = $errors_content_link;
        $errors['content']['subhead'] = $content_subhead;
    } else {
        $check_link = true;
    }
}
// для фото: есть ли файл по ссылке
if (!empty($post_content) && $post_type === 'photo' && isset($check_link)) {
    $check_file = file_get_contents($post_content);
    if (!$check_file) {
        $errors['content'] = $errors_photo_file;
    } else {
        $check_file = true;
    }
}
// для видео: есть ли файл по ссылке
if (!empty($post_content) && ($post_type === 'video' && isset($check_file))) {
    $check_file = check_youtube_url($post_content);
    if (!$check_file) {
        $errors['content'] = $errors_video_file;
    }
}
// правильность файла
if (!empty($post_file_type)) {
    if ($post_file_type !== 'image/jpeg' && $post_file_type !== 'image/gif' && $post_file_type !== 'image/png') {
        $errors['content'] = $errors_file_file;
    }
}
// правильность тегов
if ($post_tags !== false && $post_type === 'tags') {
    $check_tags = !preg_match('/[^а-яА-Яa-zA-Z0-9 ]+/msiu', $post_tags);
    if ($check_tags === '') {
        $errors['tags'] = $errors_tags_noone;
    }
}

// убираем пустые поля из получившегося массива ошибок
$errors = array_filter($errors);

// БЛОК ДОБАВЛЕНИЯ ПОСТА

if ($post_type !== false && count($errors) < 1) {
    $text = ($post_type === 'text' || $post_type === 'quote') ? $post_content : '';
    $video_url = ($post_type === 'video') ? $post_content : '';
    $site_url = ($post_type === 'link') ? $post_content : '';

    // обрабатываем файл изображения
    $image_url = '';
    if ($post_type === 'photo') {
        if (isset($post_file)) {
            $file_link = (isset($post_file['tmp_name'])) ? $post_file['tmp_name'] : false;
            $file_name = (isset($post_file['name'])) ? $post_file['name'] : false;
        } else {
            $file_link = $content;
            $file_name = explode('/', $content);
            $file_name = end($file_name);
        }
        $image_url = 'uploads/' . $file_name;
        file_put_contents($image_url, file_get_contents($file_link));
    }

    // забираем id типа контента из таблицы content_type
    $sql = 'SELECT id FROM content_type WHERE class = "' . $post_type . '"';
    $ctid = con_sql($con, $sql);

    // заливаем данные в таблицу posts
    $sql = '
        INSERT INTO posts SET
        header = "' . $post_header . '",
        text = "' . $text . '",
        author = "' . $post_author . '",
        image_url = "' . $image_url . '",
        video_url = "' . $video_url . '",
        site_url = "' . $site_url . '",
        num_views = "0",
        user_id = "' . $user_id . '",
        content_type_id = "' . $ctid['id'] . '"
        ';
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }
    $id = mysqli_insert_id($con);

// БЛОК ДОБАВЛЕНИЯ ТЕГОВ

    $tags_array = explode(' ', $post_tags); // делаем массив с тегами

    // теги в таблицу hashtags (перечень тегов)
    $sql = 'INSERT IGNORE INTO hashtags (hashtag) VALUES';
    $count_tags = count($tags_array) - 1;
    foreach ($tags_array as $tags_key => $tag) {
        $sql .= ' ("' . $tag . '")';
        $sql .= ($tags_key !== $count_tags) ? ',' : '';
    }
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }

    // добавляем теги в таблицу post_hashtag (связь постов и тегов)
    foreach ($tags_array as $hashtag) {
        $sql = 'SELECT id FROM hashtags WHERE hashtag = "' . $hashtag . '"'; // забираем id добавленного тега
        $sql_hashtag = mysqli_query($con, $sql);
        $tag_id = mysqli_fetch_assoc($sql_hashtag); // получаем id в читаемом виде
        $sql = 'INSERT IGNORE INTO post_hashtag SET
            post_id = "' . $id . '",
            hashtag_id = "' . $tag_id['id'] . '"
        ';
        $result = mysqli_query($con, $sql);
        if (!$result) {
            $error = mysqli_error($con);
            print("Ошибка MySQL: " . $error);
        }
    }

// БЛОК ОТПРАВЛЕНИЯ ПИСЕМ ПОДПИСЧИКАМ

    // собираем список адресов, на которые будем отправлять уведомления
    $sql = '
        SELECT
            users.username as name,
            users.email as email
        FROM subscribes
            LEFT JOIN users ON users.id = subscribes.subscriber_id
        WHERE subscribes.sourcer_id = "' . $user_id . '"
    ';
    $subscribers = con_sql($con, $sql, 'all'); // ассоциативный массив с подписчиками (имя, email)

    // id автора поста
    $aurhor_id = (isset($_SESSION['user'])) ? filter_var($_SESSION['user'], FILTER_SANITIZE_NUMBER_INT) : false;
    // username автора поста
    $aurhor_username = (isset($_SESSION['username'])) ? filter_var($_SESSION['username'], FILTER_SANITIZE_SPECIAL_CHARS) : false;
    $header = $post_header; // заголовок поста

    // отправляем письма
    require_once('includes/email.php'); // подключаем файл, формирующий транспорт для писем
    foreach ($subscribers as $subscriber) {  // рассылаем письма в цикле
        // формирование сообщения
        $message = new Swift_Message('Новая публикация от пользователя ' . $aurhor_username);
        $message->setTo([$subscriber['email'] => $subscriber['name']]);
        $message->setBody(
            'Здравствуйте, ' .
            $subscriber['name'] . '. Пользователь ' .
            $aurhor_username . ' только что опубликовал новую запись „' .
            $header . '“. Посмотрите её на странице пользователя: <a href="profile.php?id= ' .
            $aurhor_id . '">страница пользователя</a>'
        );
        $message->setFrom('keks@phpdemo.ru', 'Кекс');

        /* закомментил, чтобы страница не падала, тк SMTP-сервер не работает
        // отправка сообщения
        $mailer = new Swift_Mailer($transport);
        $mailer->send($message);
        */
    }

// редирект на страницу поста после публикации
    header('Location: /post.php?id=' . $id);
}

// БЛОК ГЕНЕРАЦИИ ШАБЛОНА
$page_content = include_template('add_template.php', [
    'content_types' => $content_types,
    'active_tab' => $active_tab,
    'content_names' => $content_names,
    'errors' => $errors,
    'fields_names' => $fields_names
]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => $title]);
print($layout_content);
