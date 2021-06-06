<?php

// обрабатываем репост, если он нажат
if (isset($_GET['repost']) !== FALSE) {
	$repost_id = filter_input(INPUT_GET, 'repost', FILTER_SANITIZE_NUMBER_INT) ?? FALSE; // если $_GET['repost'] есть, то очищаем
	// проверяем, есть ли такой пост в БД
	$sql = '
		SELECT
			header,
			text,
			author,
			image_url,
			video_url,
			site_url,
			user_id,
			content_type_id,
			original_author
		FROM posts
		WHERE id = ' . $repost_id;
	$sql_repost = mysqli_query($con, $sql);
	$repost = mysqli_fetch_array($sql_repost);
	// если такой пост есть, то добавляем репост в таблицу постов:
	if (count($repost) > 0) {
		// если это репоста репоста, то в original_author вставляем самого первого автора поста, а если нет, то автора поста
		$original_author = ($repost['original_author'] == NULL ? $repost['user_id'] : $repost['original_author']);
		
		// затем вставляем их в ту же таблицу, но с другим id автора, временем, признаком репоста и автором оригинального поста
		$sql = '
			INSERT IGNORE INTO posts
			SET
				header = "' . $repost['header'] . '",
				text = "' . $repost['text'] . '",
				author = "' . $repost['author'] . '",
				image_url = "' . $repost['image_url'] . '",
				video_url = "' . $repost['video_url'] . '",
				site_url = "' . $repost['site_url'] . '",
				num_views = 0,
				user_id = "' . $user_id . '",
				content_type_id = "' . $repost['content_type_id'] . '",
				repost = "' . $repost_id . '",
				original_author = "' . $original_author . '"
		';
		$result = mysqli_query($con, $sql);
		if (!$result) { 												// если не добавилось, то показываем ошибку
			$error = mysqli_error($con); 
			print("Ошибка MySQL: " . $error);
		} else {														// а если нормально добавилось, то редиректим на страницу текущего пользователя
			header('Location:profile.php?id=' . $user_id);
		}
	}
}

?>
