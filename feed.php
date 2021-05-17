<?php

session_start();														// открываем сессию
if (!isset($_SESSION['user'])) {										// если в сессии нет переменной user, значит надо сначала авторизоваться
	header('Location: /index.php');										// поэтому редиректим на index.php
} else {
	$is_auth = 1;
	$user_id = $_SESSION['user'];
}

include ('helpers.php');												// подключаем файл с функциями
include ('mysqli_connect.php');											// соединяемся с БД

$title = 'readme: моя лента';

// собираем всех пользователей, на которых подписан данный юзер
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
		
		(SELECT content_type.class FROM content_type WHERE content_type.id = posts.content_type_id) as type,
		(SELECT COUNT(likes.post_id) FROM likes WHERE likes.post_id = posts.id) as count_likes,
		(SELECT COUNT(comments.id) FROM comments WHERE comments.post_id = posts.id) as count_comments
		
	FROM subscribes
		LEFT JOIN posts ON posts.user_id = subscribes.sourcer_id
		LEFT JOIN users ON users.id = subscribes.sourcer_id
	WHERE subscriber_id = ' . $user_id . ' AND posts.dt_add IS NOT NULL
	ORDER BY posts.dt_add DESC
	';
$sql_posts = mysqli_query($con, $sql);
if ($sql_posts) {														// если данные из БД получены, то получаем массив с данными
	$posts = mysqli_fetch_all($sql_posts, MYSQLI_ASSOC);
} else {
	include ('goto_404.php');											// если данные из БД не получены, то 404
}

// содержание страницы
$page_content = include_template('start_feed.php', ['posts' => $posts]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => $title, 'is_auth' => $is_auth]);

print($layout_content);

?>
