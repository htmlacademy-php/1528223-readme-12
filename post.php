<?php

session_start();														// открываем сессию
if (!isset($_SESSION['user'])) {										// если в сессии нет переменной user, значит надо сначала авторизоваться
	header('Location: /index.php');										// поэтому редиректим на index.php
} else {
	$is_auth = 1;
	$user_id = $_SESSION['user'];
}

include ('helpers.php');												// подключаем файл с функциями

$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? FALSE; // если $_GET['id'] есть, то чистим

if ($get_id) {															// если ?id= есть, то соединяемся с БД
	include ('mysqli_connect.php');
} else {
	include ('goto_404.php');											// если ?id= нет, то 404
}

if (!$con) {
   print('Ошибка подключения: ' . mysqli_connect_error());
} else {																// если удалось соединиться с БД, то формируем запрос в БД
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
			
			posts.content_type_id as content_id,
			content_type.class as content_type,
			
			COUNT(DISTINCT likes.user_id) as likes_count,
			COUNT(DISTINCT comments.id) as comments_count,
			COUNT(DISTINCT subscribes.subscriber_id) as subscribers_count,
			COUNT(DISTINCT p.id) as posts_count
			
		FROM posts
			LEFT JOIN users ON posts.user_id = users.id
			LEFT JOIN content_type ON posts.content_type_id = content_type.id
			LEFT JOIN likes ON posts.id = likes.post_id
			LEFT JOIN comments ON posts.id = comments.post_id
			LEFT JOIN subscribes ON posts.user_id = subscribes.sourcer_id
			LEFT JOIN posts as p ON posts.user_id = p.user_id
		WHERE posts.id = ' . $get_id . '
		GROUP BY
			posts.id
		';
	$sql_post = mysqli_query($con, $sql);
}
	
if ($sql_post) {														// если данные из БД получены, то получаем массив с данными
	$post = mysqli_fetch_assoc($sql_post);
} else {
	include ('goto_404.php');											// если данные из БД не получены, то 404
}

if ($post !== null) {													// если пост найден, то действуем дальше
	
	$errors = '';
	if (isset($_POST['comment'])) {
		$user_comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS) ?? FALSE;
		if ($user_comment == '') {
			$errors = 'Это поле обязательно к заполнению';
		} elseif (mb_strlen($user_comment,'UTF-8') < 4) {
			$errors = 'Комментарий должен содержать не менее 4 символов';
		} else {
			$sql = '
				INSERT INTO comments SET
					user_id = "' . $user_id . '",
					post_id = "' . $post['id'] . '",
					content = "' . $user_comment . '"
			';
			$result = mysqli_query($con, $sql);
			if (!$result) { 
				$error = mysqli_error($con); 
				print("Ошибка MySQL: " . $error);
			}
		}
	}
	
	
	
	$comments = 0;
	if ($post['comments_count'] > 0) {									// если есть комментарии к посту
		$sql = '
		SELECT
			comments.dt_add as dt_add,
			comments.content as content,
			users.avatar as avatar,
			users.username as name
		FROM comments
			LEFT JOIN users ON comments.user_id = users.id
		WHERE comments.post_id = ' . $post['id'] . '
		ORDER BY comments.dt_add DESC
		';
		$sql_comments = mysqli_query($con, $sql);
		if ($sql_comments) {
			$comments = mysqli_fetch_all($sql_comments, MYSQLI_ASSOC);
		}
	}
	// вносим полученные данные в сценарий поста
	$page_content = include_template('post-details.php', ['post' => $post, 'errors' => $errors, 'comments' => $comments]);
} else {
	include ('goto_404.php');
}

// окончательный HTML-код
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: ' . $post['header'], 'is_auth' => $is_auth]);

print($layout_content);

?>
