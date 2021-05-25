<?php

include ('all__authorization.php');										// подключаем файл с авторизацией (здесь объявляется $user_id)
include ('all__helpers.php');											// подключаем файл с функциями

$title = '';

// БЛОК ОБРАБОТКИ GET-ЗАПРОСА
$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? FALSE; // если $_GET['id'] есть, то чистим

if ($get_id) {															// если ?id= есть, то соединяемся с БД
	include ('all__mysqli_connect.php');								// подключаем файл с подключением к БД
} else {
	include ('all__goto_404.php');										// если ?id= нет, то 404
}

// БЛОК ЛАЙКА
include ('all__likes.php');												// подключаем файл для добавления лайка

// БЛОК ПОЛУЧЕНИЯ ДАННЫХ ПОСТА
$post = [];
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
$post = con_sql_assoc($con, $sql);										// получаем ассоциативный массив с содержимым поста через функцию проверки

// БЛОК ПОДПИСКИ
$sourcer_id = $post['user_id']; 										// id пользователя, на которого подписываемся
$sourcer_name = $post['username'];										// имя пользователя, на которого подписываемся
$sourcer_email = $post['email'];										// email пользователя, на которого подписываемся
include ('all__subscribe.php');											// подключаем файл для подписки/отписки и отправки уведомления

// БЛОК КОММЕНТАРИЕВ
$comments = [];
if (count($post) !== 0) {												// если пост найден, то показываем комментарии и можем добавить новый
	
	if ($post['comments_count'] > 0) {									// если есть комментарии к посту, то выводим их
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
	
	$errors = NULL;
	if (isset($_POST['comment']) !== FALSE) {							// если добавляется комментарий, то смотрим на ошибки
		$user_comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS) ?? FALSE;
		if ($user_comment == '') {
			$errors = 'Это поле обязательно к заполнению';
		} elseif (mb_strlen($user_comment,'UTF-8') < 4) {
			$errors = 'Комментарий должен содержать не менее 4 символов';
		}
	}
	if (isset($_POST['comment']) !== FALSE
		AND $errors == NULL) {											// если ошибок нет, то формируем запрос на добавление и отправляем в БД
		$sql = '
			INSERT INTO comments SET
				user_id = "' . $user_id . '",
				post_id = "' . $post['id'] . '",
				content = "' . $user_comment . '"
		';
		$result = mysqli_query($con, $sql);
		if (!$result) { 												// проверяем на успешное добавление в БД
			$error = mysqli_error($con);
			print("Ошибка MySQL: " . $error);
		} else {
			header('Location: /profile.php?id=' . $post['user_id']);	// если ошибок нет, то редиректим на страницу поста
		}
	}
} else {
	include ('all__goto_404.php');										// если пост не найден, то редиректим на 404
}

// БЛОК ГЕНЕРАЦИИ ШАБЛОНА
$page_content = include_template('post_template.php', [
	'post' => $post,
	'errors' => $errors,
	'subscribe' => $subscribe,
	'comments' => $comments,
	'get_id' => $get_id
]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: ' . $post['header']]);
print($layout_content);

?>
