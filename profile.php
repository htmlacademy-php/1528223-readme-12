<?php

include ('all__authorization.php');										// подключаем файл с авторизацией (здесь объявляется $user_id)
include ('all__helpers.php');											// подключаем файл с функциями

$title = 'readme: профиль';

// БЛОК ОБРАБОТКИ GET-ЗАПРОСА
$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? FALSE; // если $_GET['id'] есть, то чистим

if ($get_id) {															// если ?id= есть, то соединяемся с БД
	include ('all__mysqli_connect.php');
} else {
	include ('all__goto_404.php');										// если ?id= нет, то 404
}

// БЛОК ПРОВЕРКИ СУЩЕСТВОВАНИЯ ID ПРОФИЛЯ
$check = 0;
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
if ($check === 0) {														// если пользователя с таким ID в БД нет, то 404
	include ('all__goto_404.php');
}

// БЛОК ЛАЙКА
include ('all__likes.php');												// подключаем файл для добавления лайка

// БЛОК РЕПОСТА
include ('all__repost.php');											// подключаем файл для репоста

// БЛОК ДАННЫХ ПО ПРОФИЛЮ
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
$sql_profile = mysqli_query($con, $sql);
if ($sql_profile) {
	$profile = mysqli_fetch_assoc($sql_profile);						// получаем массив с данными пользователя с $get_id
}

// БЛОК ПОДПИСКИ
$sourcer_id = $get_id;													// id пользователя, на которого подписываемся/отписываемся
$sourcer_name = $profile['name'];										// имя пользователя, на которого подписываемся
$sourcer_email = $profile['email'];										// email пользователя, на которого подписываемся
include ('all__subscribe.php');											// подключаем файл для подписки/отписки и отправки уведомления

// БЛОК ВКЛАДКИ СО СПИСКОМ ПОСТОВ
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
$posts = con_sql($con, $sql);											// получаем ассоциативный массив с постами через функцию проверки

include ('all__hashtags.php');											// подключаем файл для формирования массива с хештегами для каждого поста (здесь объявляется $hashtags)

// БЛОК ВКЛАДКИ СО СПИСКОМ ЛАЙКОВ
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
$likes = con_sql($con, $sql);											// получаем ассоциативный массив с лайками через функцию проверки

// БЛОК ВКЛАДКИ СО СПИСКОМ ПОДПИСОК
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
$subscribes = con_sql($con, $sql);										// получаем ассоциативный массив с подписчиками через функцию проверки

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
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => $title]);
print($layout_content);

?>
