<?php

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
	// если такой пост есть, то добавляем лайк в таблицу лайков
	if ($check > 0) {
		$sql = '
			INSERT IGNORE INTO likes
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

?>
