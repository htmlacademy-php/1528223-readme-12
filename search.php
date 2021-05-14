<?php

session_start();														// открываем сессию
if (!isset($_SESSION['user'])) {										// если в сессии нет переменной user, значит надо сначала авторизоваться
	header('Location: /index.php');										// поэтому редиректим на index.php
} else {
	$is_auth = 1;
	$user_id = $_SESSION['user'];
}

include ('helpers.php');												// подключаем файл с функциями

$title = 'readme: страница результатов поиска';
$get_id = filter_input(INPUT_GET, 's', FILTER_SANITIZE_SPECIAL_CHARS) ?? FALSE; // если $_GET['id'] есть, то чистим

if ($get_id) {															// если ?s= есть, то соединяемся с БД
	include ('mysqli_connect.php');
} else {
	include ('goto_404.php');											// если ?s= нет, то 404
}

if (!$con) {
	print('Ошибка подключения: ' . mysqli_connect_error());
} else {																// если подключение к БД прошло успешно, то проверяем есть ли пользователь с таким ID
	$sql = '
		SELECT
			posts.id as id,
			posts.dt_add as dt,
			posts.header,
			posts.text,
			posts.author,
			posts.image_url,
			posts.site_url,
		
			users.id as user_id,
			users.avatar as avatar,
			users.username as name,
			content_type.class as type,
			
			COUNT(DISTINCT likes.user_id) as likes_count,
			COUNT(DISTINCT comments.id) as comments_count
			
		FROM posts
			LEFT JOIN users ON posts.user_id = users.id
			LEFT JOIN content_type ON content_type.id = posts.content_type_id
			LEFT JOIN likes ON posts.id = likes.post_id
			LEFT JOIN comments ON posts.id = comments.post_id
		WHERE MATCH(header, text) AGAINST("' . $get_id . '")
		GROUP BY
			posts.id
	';
	$sql_search = mysqli_query($con, $sql);
	$search = mysqli_fetch_all($sql_search, MYSQLI_ASSOC);
}

// вносим полученные данные в сценарий поста
$page_content = include_template('search_results.php', ['get_id' => $get_id, 'search' => $search]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => $title, 'is_auth' => $is_auth]);

print($layout_content);

?>
