<?php

$is_auth = rand(0, 1);

include ('helpers.php');												// подключаем файл с функциями

$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? FALSE; // если $_GET['id'] есть, то чистим
if ($get_id) {															// если ?id= есть, то:
	$con = mysqli_connect('localhost', 'root', '','readme');			// соединяемся с БД
	if ($con) {															// если удалось соединиться с БД, то формируем запрос
		mysqli_set_charset($con, "utf8");
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
				
				(SELECT COUNT(*) FROM likes WHERE posts.id = likes.post_id) as likes_count,
				(SELECT COUNT(*) FROM comments WHERE posts.id = comments.post_id) as comments_count,
				(SELECT COUNT(*) FROM subscribes WHERE posts.user_id = sourcer_id) as subscribers_count,
				(SELECT COUNT(*) FROM posts WHERE posts.user_id = users.id) as posts_count
				
			FROM posts
				LEFT JOIN users ON posts.user_id = users.id
				LEFT JOIN content_type ON posts.content_type_id = content_type.id
			WHERE posts.id = ' . $get_id;
		$sql_post = mysqli_query($con, $sql);
		if ($sql_post) {												// если данные из БД получены, то получаем массив с данными
			$post = mysqli_fetch_assoc($sql_post);
			if ($post['id']) {											// если пост найден, то формируем массив с данными
				
				// если есть комментарии к посту, то выводим последние 2 в отдельный массив (если все комментарии выводятся не на отдельной странице, то убрать лимит)
				$comments = 0;
				if ($post['comments_count'] > 0) {
					mysqli_set_charset($con, "utf8");
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
					LIMIT 2
					';
					$sql_comments = mysqli_query($con, $sql);
					if ($sql_comments) {
						$comments = mysqli_fetch_all($sql_comments, MYSQLI_ASSOC);
					}
				}
				// вносим полученные данные в сценарий поста
				$page_content = include_template('post-details.php', ['post' => $post, 'comments' => $comments]);
			} else {
				goto mark;
			}
		} else {
			goto mark;
		}
	}
} else {
	mark:
	$page_content = 'Отправляем на 404 3';
}

// окончательный HTML-код
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: ' . $post['header'], 'is_auth' => $is_auth]);

print($layout_content);

?>
