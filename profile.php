<?php

session_start();														// открываем сессию
if (!isset($_SESSION['user'])) {										// если в сессии нет переменной user, значит надо сначала авторизоваться
	header('Location: /index.php');										// поэтому редиректим на index.php
} else {
	$is_auth = 1;
	$user_id = $_SESSION['user'];
}

include ('helpers.php');												// подключаем файл с функциями

$title = 'readme: профиль';
$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? FALSE; // если $_GET['id'] есть, то чистим

if ($get_id) {															// если ?id= есть, то соединяемся с БД
	include ('mysqli_connect.php');
} else {
	include ('goto_404.php');											// если ?id= нет, то 404
}

if (!$con) {
	print('Ошибка подключения: ' . mysqli_connect_error());
} else {																// если подключение к БД прошло успешно, то проверяем есть ли пользователь с таким ID
	$sql = '
		SELECT id
		FROM users
		WHERE id = "' . $get_id . '"
	';
	$sql_check = mysqli_query($con, $sql);
	$check = mysqli_num_rows($sql_check);
}
if ($check == 0) {														// если пользователя с таким ID в БД нет, то 404
	include ('goto_404.php');
}
	
// если нажата кнопка "Подписаться", то подписываем пользователя $user_id на пользователя $get_id, внося соответствующую запись в БД
if (isset($_POST['subscribe']) !== FALSE) {
	$sql = '
		INSERT INTO subscribes SET
		sourcer_id = "' . $get_id . '",
		subscriber_id = "' . $user_id . '"
	';
	$result = mysqli_query($con, $sql);
	if (!$result) { 
		$error = mysqli_error($con); 
		print("Ошибка MySQL: " . $error);
	}
}

// если нажата кнопка "Отписаться", то отписываем пользователя $user_id на пользователя $get_id, удаляя соответствующую запись БД
if (isset($_POST['unsubscribe']) !== FALSE) {
	$sql = '
		DELETE
		FROM subscribes
		WHERE sourcer_id = "' . $get_id . '" AND subscriber_id = "' . $user_id . '"
	';
	$result = mysqli_query($con, $sql);
	if (!$result) { 
		$error = mysqli_error($con); 
		print("Ошибка MySQL: " . $error);
	}
}

// проверяем подписан ли пользователь $user_id на пользователя $get_id, чтобы показать кнопку "подписаться"/"отписаться"
$sql = '
	SELECT sourcer_id, subscriber_id
	FROM subscribes
	WHERE sourcer_id = "' . $get_id . '" AND subscriber_id = "' . $user_id . '"
';
$sql_check = mysqli_query($con, $sql);
$check = mysqli_num_rows($sql_check);
if ($check > 0) {
	$subscribe = 1;
} else {
	$subscribe = 0;
}

// собираем остальные данные по авторизованному пользователю
$sql = '
	SELECT
		users.dt_add as datetime,
		users.avatar as avatar,
		users.username as name,
		
		COUNT(DISTINCT posts.id) as posts_count,
		COUNT(DISTINCT subscribes.subscriber_id) as subscribers_count
		
	FROM users
		LEFT JOIN posts ON posts.user_id = users.id
		LEFT JOIN subscribes ON subscribes.sourcer_id = users.id
	WHERE users.id = ' . $get_id . '
	GROUP BY
		users.id
';
$sql_profile = mysqli_query($con, $sql);
if ($sql_profile) {
	$profile = mysqli_fetch_assoc($sql_profile);						// получаем массив с нужными данными по пользователю с $get_id
}

// обрабатываем лайк, если он нажат
if (isset($_GET['likepost']) !== FALSE) {
	$likepost_id = filter_input(INPUT_GET, 'likepost', FILTER_SANITIZE_NUMBER_INT) ?? FALSE; // если $_GET['likepost'] есть, то очищаем
	// проверяем, есть ли такой пост в БД
	$sql = '
		SELECT id
		FROM posts
		WHERE id = ' . $likepost_id;
	$sql_check = mysqli_query($con, $sql);
	$check = mysqli_num_rows($sql_check);
	// если такой пост, то добавляем лайк в таблицу лайков
	if ($check > 0) {
		$sql = '
			INSERT INTO likes
			SET
				user_id = "' . $user_id . '",
				post_id = "' . $likepost_id . '"
		';
		$result = mysqli_query($con, $sql);
		if (!$result) { 												// если не добавилось, то показываем ошибку
			$error = mysqli_error($con); 
			print("Ошибка MySQL: " . $error);
		} else {														// а если нормально добавилось, то редиректим обратно
			header('Location:' . $_SERVER['HTTP_REFERER']);
			
		}
	}
}

// собираем список постов пользователя
$sql = '
	SELECT
		posts.*,
		content_type.class as type,
		COUNT(DISTINCT likes.user_id) as likes_count
	FROM posts
		LEFT JOIN content_type ON content_type.id = posts.content_type_id
		LEFT JOIN likes ON posts.id = likes.post_id
	WHERE posts.user_id = ' . $get_id . '
	GROUP BY
		posts.id
';
$sql_posts = mysqli_query($con, $sql);
if ($sql_posts) {
	$posts = mysqli_fetch_all($sql_posts, MYSQLI_ASSOC);				// получаем массив с нужными данными по пользователю с $get_id
}

// вносим полученные данные в сценарий поста
$page_content = include_template('profile_content.php', ['get_id' => $get_id, 'profile' => $profile, 'subscribe' => $subscribe, 'posts' => $posts]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => $title, 'is_auth' => $is_auth]);

print($layout_content);

?>
