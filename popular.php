<?php

include ('all__authorization.php');										// подключаем файл с авторизацией (здесь объявляется $user_id)
include ('all__helpers.php');											// подключаем файл с функциями
include ('all__mysqli_connect.php');									// подключаем файл с подключением к БД

$title = 'readme: популярное';

$get_type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT) ?? 0;

// БЛОК ЛАЙКОВ
include ('all__likes.php');												// подключаем файл для добавления лайка

// БЛОК ПОЛУЧЕНИЯ ДАННЫХ ПОСТА
include ('all__content_types.php');										// подключаем файл для сбора массива с типами контента (здесь объявляется $content_types)

$sql = '
	SELECT
		posts.num_views as views,
		posts.dt_add as datetime,
		posts.header as header,
		posts.user_id as user_id,
		posts.id as id,
		posts.author as author,
		posts.site_url as url,
		CONCAT (posts.text, posts.image_url, posts.video_url) as content,

		content_type.class as type,
		
		users.username as username,
		users.avatar as avatar,
		
		COUNT(DISTINCT likes.user_id) as likes_count,
		COUNT(DISTINCT comments.id) as comments_count		
		
	FROM posts
		LEFT JOIN users ON posts.user_id = users.id
		LEFT JOIN content_type ON posts.content_type_id = content_type.id
		LEFT JOIN likes ON likes.post_id = posts.id
		LEFT JOIN comments ON comments.post_id = posts.id
	
';
if ($get_type >= 1 AND $get_type <= 5) {
	$sql .= ' WHERE posts.content_type_id = ' . $get_type;
}
$sql .= '
	GROUP BY
		posts.id
	ORDER BY num_views DESC
';

include ('all__pagination.php');										// добавляем в запрос параметры пагинации (здесь объявляется $prev_link и $next_link)
$popular_posts = con_sql($con, $sql);									// получаем ассоциативный массив с постами через функцию проверки

// БЛОК ГЕНЕРАЦИИ ШАБЛОНА
$page_content = include_template('popular_template.php', [
	'content_types' => $content_types,
	'popular_posts' => $popular_posts,
	'get_type' => $get_type,
	'prev_link' => $prev_link,
	'next_link' => $next_link
]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => $title]);
print($layout_content);

?>
