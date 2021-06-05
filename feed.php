<?php

require_once('includes/authorization.php'); // подключаем файл с авторизацией (здесь объявляется $user_id)
require_once('includes/helpers.php'); // подключаем файл с функциями
require_once('includes/mysqli_connect.php'); // подключаем файл с подключением к БД

$title = 'readme: моя лента';

require_once('includes/likes.php'); // подключаем файл для добавления лайка
require_once('includes/repost.php'); // подключаем файл для репоста
require_once('includes/content_types.php'); // подключаем файл массива с типами контента (объявляется $content_types)

$get_type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT) ?? 0; // очищаем GET-запрос дя типов контента

// получаем список пользователей, на которых подписан текущий пользователь
$sql = '
    SELECT
        sourcer_id,

        users.id as user_id,
        users.username as username,
        users.avatar as avatar,

        posts.id as post_id,
        posts.dt_add as dt_add,
        posts.header as header,
        posts.text as text,
        posts.author,
        posts.image_url,
        posts.video_url,
        posts.site_url,
        posts.repost,
        posts.original_author,

        (SELECT content_type.class
            FROM content_type
            WHERE content_type.id = posts.content_type_id
        ) as type,
        (SELECT COUNT(likes.post_id)
            FROM likes
            WHERE likes.post_id = posts.id
        ) as count_likes,
        (SELECT
            COUNT(comments.id)
            FROM comments
            WHERE comments.post_id = posts.id
        ) as count_comments,
        (SELECT GROUP_CONCAT(hashtags.hashtag)
            FROM post_hashtag
            LEFT JOIN hashtags ON post_hashtag.hashtag_id = hashtags.id
            WHERE post_hashtag.post_id = posts.id
        ) as hashtags,

        COUNT(DISTINCT p2.id) as count_reposts,

        IF(posts.repost > 0,
            (SELECT users.username FROM users WHERE users.id = posts.original_author), NULL
        ) as author_name,

        IF(posts.repost > 0,
            (SELECT users.avatar FROM users WHERE users.id = posts.original_author), NULL
        ) as author_avatar

    FROM subscribes
        LEFT JOIN posts ON posts.user_id = subscribes.sourcer_id
        LEFT JOIN users ON users.id = subscribes.sourcer_id
        LEFT JOIN posts as p2 ON posts.id = p2.repost
    WHERE subscriber_id = ' . $user_id . ' AND posts.dt_add IS NOT NULL';

if ($get_type >= 1 and $get_type <= 5) {
    $sql .= ' AND posts.content_type_id = ' . $get_type;
}
$sql .= '
    GROUP BY
        posts.id
    ORDER BY posts.dt_add DESC
';
$posts = con_sql($con, $sql, 'all'); // получаем ассоциативный массив с постами через функцию проверки

require_once('includes/hashtags.php'); // подключаем файл массива с хештегами для постов (здесь объявляется $hashtags)

// БЛОК ГЕНЕРАЦИИ ШАБЛОНА
$page_content = include_template('feed_template.php', [
    'posts' => $posts,
    'content_types' => $content_types,
    'get_type' => $get_type,
    'hashtags' => $hashtags
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title
]);
print($layout_content);
