<?php

include ('all__authorization.php');										// подключаем файл с авторизацией (здесь объявляется $user_id)
include ('all__helpers.php');											// подключаем файл с функциями

$title = 'readme: страница результатов поиска';

$get_search = filter_input(INPUT_GET, 's', FILTER_SANITIZE_SPECIAL_CHARS) ?? FALSE; // если $_GET['s'] есть, то чистим

if ($get_search) {														// если ?s= есть, то соединяемся с БД
	include ('all__mysqli_connect.php');
} else {
	include ('all__goto_404.php');										// если ?s= нет, то 404
}

// БЛОК РЕЗУЛЬТАТОВ ПОИСКА
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
';
if (substr($get_search, 0, 1) !== '#') {
	$sql .= '
		WHERE MATCH(header, text) AGAINST("' . $get_search . '")
	';
} else {
	$sql .= '
			LEFT JOIN post_hashtag ON post_hashtag.post_id = posts.id
			LEFT JOIN hashtags ON hashtags.id = post_hashtag.hashtag_id
		WHERE hashtags.hashtag = "' . substr($get_search, 1) . '"
	';
}
$sql .= '
	GROUP BY
		posts.id
';

$search = con_sql($con, $sql);											// получаем ассоциативный массив с результатами поиска через функцию проверки

// БЛОК ГЕНЕРАЦИИ ШАБЛОНА
$page_content = include_template('search_template.php', [
	'get_search' => $get_search,
	'search' => $search
]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => $title]);
print($layout_content);

?>
