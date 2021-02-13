<?php

$is_auth = rand(0, 1);

$user_name = 'Максим'; // укажите здесь ваше имя

// подключаем файл с функциями
include ('helpers.php');

$con = mysqli_connect('localhost', 'root', '','readme');				// 1. В сценарии главной страницы выполните подключение к MySQL.

if ($con === FALSE) {
   print('Ошибка подключения: ' . mysqli_connect_error());
} 
else {
	mysqli_set_charset($con, "utf8");
	
	$sql = 'SELECT name, class FROM content_type';
	$sql_content_types = mysqli_query($con, $sql);						// 2. Отправьте SQL-запрос для получения типов контента.
	$content_types = mysqli_fetch_all($sql_content_types, MYSQLI_ASSOC);
	
	$sql = '
		SELECT
			posts.num_views as views,
			posts.dt_add as datetime,
			posts.header as header,
			
			content_type.class as type,
			
			users.username as username,
			users.avatar as avatar,
			
			CONCAT (posts.text, posts.image_url, posts.video_url) as content,
			
			posts.author as author,
			posts.site_url as url
			
		FROM posts
		LEFT JOIN users
			ON posts.user_id = users.id
		LEFT JOIN content_type
			ON posts.content_type_id = content_type.id
		ORDER BY num_views DESC
	';
	$sql_popular_posts = mysqli_query($con, $sql);						// 3. Отправьте SQL-запрос для получения списка постов, объединенных с пользователями и отсортированный по популярности.
	$popular_posts = mysqli_fetch_all($sql_popular_posts, MYSQLI_ASSOC);
	
}

// защита от XSS-атак
foreach ($popular_posts as $array_key => $array_value) {
	foreach ($array_value as $key => $value) {
		$popular_posts[$array_key][$key] = htmlspecialchars($value);
	}
}

// установка временной зоны по умолчанию
date_default_timezone_set('Europe/Moscow');

// добавляем случайные даты в двумерный массив списка постов с помощью функции generate_random_date
$index = 0;
foreach ($popular_posts as $array_key => $array_value) {
	$popular_posts[$array_key]['datetime'] = generate_random_date($index);
	$index += 1;
}

// HTML-код главной страницы
$page_content = include_template('main.php', ['content_types' => $content_types, 'popular_posts' => $popular_posts]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($layout_content);

?>
