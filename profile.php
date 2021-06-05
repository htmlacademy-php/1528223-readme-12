<?php

require_once('includes/authorization.php'); // подключаем файл с авторизацией (здесь объявляется $user_id)
require_once('includes/helpers.php'); // подключаем файл с функциями

$title = 'readme: профиль';

// обрабатываем get-запрос

$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? false; // если $_GET['id'] есть, то чистим

if ($get_id) { // если ?id= есть, то соединяемся с БД
    require_once('includes/mysqli_connect.php');
} else {
    require_once('includes/goto_404.php'); // если ?id= нет, то 404
}

// проверяем, существует ли пользователь с таким id
$sql = '
    SELECT id
    FROM users
    WHERE id = "' . $get_id . '"
';
$check_user = con_sql($con, $sql, 'num'); // получаем количество пользователей с таким get_id
if ($check_user === 0) { // если пользователя с таким id в БД нет, то 404
    require_once('includes/goto_404.php');
}

require_once('includes/likes.php'); // подключаем файл для добавления лайка
require_once('includes/repost.php'); // подключаем файл для репоста

// собираем данные по просматриваемому профилю

$profile = [];
$sql = '
    SELECT
        users.id as id,
        users.dt_add as datetime,
        users.avatar as avatar,
        users.username as name,
        users.email as email,

        COUNT(DISTINCT posts.id) as posts_count,
        COUNT(DISTINCT subscribes.subscriber_id) as subscribers_count

    FROM users
        LEFT JOIN posts ON posts.user_id = users.id
        LEFT JOIN subscribes ON subscribes.sourcer_id = users.id
    WHERE users.id = ' . $get_id . '
    GROUP BY
        users.id
';
$profile = con_sql($con, $sql); // получаем массив с данными пользователя с $get_id

// подлючаем подписку на пользователя

$sourcer_id = $get_id; // id пользователя, на которого подписываемся/отписываемся
$sourcer_name = (isset($profile['name'])) ? $profile['name'] : null; // имя пользователя, на которого подписываемся
$sourcer_email = (isset($profile['email'])) ? $profile['email'] : null; // email пользователя, на которого подписываемся
require_once('includes/subscribe.php'); // подключаем файл для подписки/отписки и отправки уведомления

// собираем посты для вкладки "посты"

$sql = '
    SELECT
        posts.id as post_id,
        posts.dt_add as dt_add,
        posts.header as header,
        posts.text as text,
        posts.author as author,
        posts.image_url as image_url,
        posts.video_url as video_url,
        posts.site_url as site_url,

        content_type.class as type,
        COUNT(DISTINCT likes.user_id) as likes_count,
        COUNT(DISTINCT p.id) as reposts_count,

        (SELECT GROUP_CONCAT(hashtags.hashtag)
            FROM post_hashtag
                LEFT JOIN hashtags ON post_hashtag.hashtag_id = hashtags.id
            WHERE post_hashtag.post_id = posts.id
        ) as hashtags

    FROM posts
        LEFT JOIN content_type ON content_type.id = posts.content_type_id
        LEFT JOIN likes ON posts.id = likes.post_id
        LEFT JOIN posts as p ON posts.id = p.repost
    WHERE posts.user_id = ' . $get_id . '
    GROUP BY
        posts.id
    ORDER BY dt_add DESC
';
$posts = con_sql($con, $sql, 'all'); // получаем ассоциативный массив с постами через функцию проверки

require_once('includes/hashtags.php'); // подключаем файл массива хештегов для постов (здесь объявляется $hashtags)

// собираем лайки для вкладки "лайки"

$sql = '
    SELECT
        likes.user_id as liker_post_id,
        likes.post_id as post_id,
        posts.user_id as author_post_id,
        users.username as name,
        users.avatar as avatar,
        content_type.class as type
    FROM likes
        LEFT JOIN posts ON posts.id = likes.post_id
        LEFT JOIN users ON users.id = likes.user_id
        LEFT JOIN content_type ON content_type.id = posts.content_type_id
    WHERE posts.user_id = "' . $get_id . '"
';
$likes = con_sql($con, $sql, 'all'); // получаем массив с лайками через функцию из helpers.php

// собираем подписки для вкладки "подписки"

$sql = '
    SELECT
        subscribes.subscriber_id as subscriber_id,
        users.dt_add as dt_add,
        users.email as email,
        users.avatar as avatar,
        users.username as username,

        COUNT(DISTINCT posts.id) as count_posts,
        COUNT(DISTINCT s.subscriber_id) as count_subscribes,

        (SELECT GROUP_CONCAT(subscribes.subscriber_id)
            FROM subscribes
            WHERE subscribes.sourcer_id = users.id
        ) as subscribers

    FROM subscribes
        LEFT JOIN users ON users.id = subscribes.subscriber_id
        LEFT JOIN posts ON posts.user_id = subscribes.subscriber_id
        LEFT JOIN subscribes as s ON s.sourcer_id = subscribes.subscriber_id
    WHERE subscribes.sourcer_id = "' . $get_id . '"
    GROUP BY
        users.id
';
$subscribes = con_sql($con, $sql, 'all'); // получаем массив с подписчиками через функцию из helpers.php

// БЛОК ГЕНЕРАЦИИ ШАБЛОНА
$page_content = include_template('profile_template.php', [
    'get_id' => $get_id,
    'user_id' => $user_id,
    'profile' => $profile,
    'subscribe' => $subscribe,
    'posts' => $posts,
    'hashtags' => $hashtags,
    'likes' => $likes,
    'subscribes' => $subscribes
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title]);
print($layout_content);
