<?php

include ('all__authorization.php');										// подключаем файл с авторизацией (здесь объявляется $user_id)
include ('all__helpers.php');											// подключаем файл с функциями
include ('all__mysqli_connect.php');									// подключаем файл с подключением к БД
include ('all__content_types.php');										// подключаем файл с типами контента (здесь объявляется $content_types)

$title = 'readme: добавление поста';

// какой таб зажигать (по умолчанию text)
$active = (isset($_POST['type']) ? $_POST['type'] : 'text');

// массив для блока content с названиями полей и плейсхолдерами
$field_names = [
	[
		'type' => 'text',
		'input_name' => 'Текст поста',
		'placeholder' => 'Введите текст публикации',
		'required' => 'yes'
	],
	[
		'type' => 'photo',
		'input_name' => 'Ссылка из интернета',
		'placeholder' => 'Введите ссылку',
		'required' => ''
	],
	[
		'type' => 'video',
		'input_name' => 'Ссылка YouTube',
		'placeholder' => 'Введите ссылку',
		'required' => 'yes'
	],
	[
		'type' => 'quote',
		'input_name' => 'Текст цитаты',
		'placeholder' => 'Текст цитаты',
		'required' => 'yes'
	],
	[
		'type' => 'link',
		'input_name' => 'Ссылка',
		'placeholder' => 'Введите ссылку',
		'required' => 'yes'
	]
];

// массив с обязательными к заполнению полями
$required_fields = [];
if (isset($_POST['heading']) !== FALSE) {
	$required_fields[] = 'heading';
}
if (isset($_POST['tags']) !== FALSE) {
	$required_fields[] = 'tags';
}
if (isset($_POST['type'])
	AND $_POST['type'] !== 'photo') {
	$required_fields[] = 'content';
} else {
	if (isset($_POST['file'])
		AND $_POST['file'] == '') {
		$required_fields[] = 'content';
	}
}
if (isset($_POST['type'])
	AND $_POST['type'] == 'quote') {
	$required_fields[] = 'author';
}
// если поле обязательно к заполнению
foreach ($field_names as $key => $val) {								// узнаём имя поля content для формирования сообщения о пустом поле
	if (isset($_POST['type'])
		AND $_POST['type'] == $val['type']) {
		$input_name = $val['input_name'];
	}
}

$errors = [];															// создаём массив с ошибками
foreach ($required_fields as $field) {									// формируем сообщения о пустых полях
    if (empty($_POST[$field])) {
		if (isset($_POST['heading'])
			AND $field == 'heading') {
			$errors[$field]['head'] = 'Заголовок';
		}
		if (isset($_POST['content'])
			AND $field == 'content') {
			$errors[$field]['head'] = $input_name;
		}
		if (isset($_POST['author'])
			AND $field == 'author') {
			$errors[$field]['head'] = 'Автор';
		}
		if (isset($_POST['tags'])
			AND $field == 'tags') {
			$errors[$field]['head'] = 'Теги';
		}
		$errors[$field]['message'] = 'Поле не заполнено';
		$errors[$field]['subhead'] = 'Пустое поле';
		$errors[$field]['submes'] = 'Гражданин, это поле должно быть честно, открыто и старательно заполнено!';
		if ($field == 'content'
			AND $_POST['type'] == 'photo') {
			$errors[$field]['head'] = 'Ссылка из интернета / Загрузка файла';
			$errors[$field]['message'] = 'Одно из полей не заполнено';
			$errors[$field]['subhead'] = 'Одно из полей пустое';
			$errors[$field]['submes'] = 'Необходимо заполнить либо поле Ссылка из интернета, либо поле Загрузка файла!';
		}
    }
}

// формируем переменные с содержанием полей и сразу чистим содержание полей от возможного вредоносного кода
$heading = clear_input('heading');
$content = clear_input('content');
$author = clear_input('author');
$tags = clear_input('tags');

// БЛОК ВАЛИДАЦИИ ПОЛЕЙ

// валидация правильности ссылки тип photo
if (isset($_POST['type'])
	AND $_POST['type'] == 'photo') {
	$check_file = NULL;
	if (filter_var($content, FILTER_VALIDATE_URL) == FALSE) {			// проверяем правильность ссылки в поле content
		$errors['content']['head'] = 'Ссылка из интернета';
		$errors['content']['message'] = 'Неверный формат адреса';
		$errors['content']['subhead'] = 'Неверный адрес';
		$errors['content']['submes'] = 'Ссылка имеет неверный формат';
	} else {
		$check_file = file_get_contents($content);
	}
	if ($check_file == NULL) {
		$errors['content']['head'] = 'Ссылка из интернета';
		$errors['content']['message'] = 'Не удалось загрузить файл';
		$errors['content']['subhead'] = 'Файл на загружен';
		$errors['content']['submes'] = 'По указанному адресу файла не обнаружено';
	}
}
// валидация правильности ссылки тип video
if (isset($_POST['type'])
	AND $_POST['type'] == 'video') {
	if (filter_var($content, FILTER_VALIDATE_URL) == FALSE) {			// проверяем правильность ссылки в поле content
		$errors['content']['head'] = 'Ссылка YouTube';
		$errors['content']['message'] = 'Неверный формат адреса';
		$errors['content']['subhead'] = 'Неверный адрес';
		$errors['content']['submes'] = 'Ссылка имеет неверный формат адреса';
	}
}
// валидация правильности ссылки тип link
if (isset($_POST['type'])
	AND $_POST['type'] == 'link') {
	if (filter_var($content, FILTER_VALIDATE_URL) == FALSE) {			// проверяем правильность ссылки в поле content
		$errors['content']['head'] = 'Ссылка';
		$errors['content']['message'] = 'Неверный формат адреса';
		$errors['content']['subhead'] = 'Неверный адрес';
		$errors['content']['submes'] = 'Ссылка имеет неверный формат адреса';
	}
}
// валидация наличия видео по ссылке youtube
if (isset($_POST['type'])
	AND $_POST['type'] == 'video') {
	$check_link = FALSE;
	if (filter_var($content, FILTER_VALIDATE_URL) == FALSE) {			// проверяем правильность ссылки в поле content
		$errors['content']['head'] = 'Ссылка';
		$errors['content']['message'] = 'Неверный формат адреса';
		$errors['content']['subhead'] = 'Неверный адрес';
		$errors['content']['submes'] = 'Ссылка имеет неверный формат адреса';
	} else {
		$check_link = TRUE;
	}
	if ($check_link == TRUE
		AND check_youtube_url($content) == FALSE) {
		$errors['content']['head'] = 'Ссылка';
		$errors['content']['message'] = 'Видео не найдено';
		$errors['content']['subhead'] = 'Видео не найдено';
		$errors['content']['submes'] = 'Видео по такой ссылке не найдено. Проверьте ссылку на видео';
	}
}
// валидация поля tags
if (!empty($tags)
	AND !preg_match('/[^а-яА-Яa-zA-Z0-9 ]+/msiu', $tags) == '') {
	$errors['tags']['head'] = 'Теги';
	$errors['tags']['message'] = 'Неверный формат записи';
	$errors['tags']['subhead'] = 'Неверный формат';
	$errors['tags']['submes'] = 'Теги должны разделяться пробелами';
}
// валидация файла
if (isset($_FILE['file'])
	AND $_FILE['file']['type'] !== 'image/jpeg'
	AND $_FILE['file']['type'] !== 'image/gif'
	AND $_FILE['file']['type'] !== 'image/png') {
	$errors['tags']['head'] = 'Фото';
	$errors['tags']['message'] = 'Неверный тип файла';
	$errors['tags']['subhead'] = 'Неверный тип';
	$errors['tags']['submes'] = 'Тип файла должен быть JPG, GIF или PNG';
}

// БЛОК ДОБАВЛЕНИЯ ПОСТА

if (isset($_POST['type'])
	AND count($errors) === 0) {											// $_POST['type'] -- это скрытое поле, поэтому валидацию не проводим
	$text = ($_POST['type'] == 'text' OR $_POST['type'] == 'quote') ? $content : '';
	$video_url = ($_POST['type'] == 'video') ? $content : '';
	$site_url = ($_POST['type'] == 'link') ? $content : '';
	
	// обрабатываем файл изображения
	$image_url = '';
	if ($_POST['type'] == 'photo') {
		if (isset($_FILE['file'])) {
			$file_link = $_FILES['file']['tmp_name'];
			$file_name = $_FILES['file']['name'];
		} else {
			$file_link = $content;
			$file_name = explode('/', $content);
			$file_name = end($file_name);
		}
		$image_url = 'uploads/' . $file_name;
		file_put_contents($image_url, file_get_contents($file_link));
	}
	
	// забираем id типа контента из таблицы content_type
	$sql = 'SELECT id FROM content_type WHERE class = "' . $_POST['type'] . '"';
	$sql_cont_type = mysqli_query($con, $sql);
	$ctid = mysqli_fetch_assoc($sql_cont_type);

	// заливаем данные в таблицу posts
	$sql = '
		INSERT INTO posts SET
		header = "' . $heading . '",
		text = "' . $text . '",
		author = "' . $author . '",
		image_url = "' . $image_url . '",
		video_url = "' . $video_url . '",
		site_url = "' . $site_url . '",
		num_views = "0",
		user_id = "' . $user_id . '",
		content_type_id = "' . $ctid['id'] . '"
		';
	$result = mysqli_query($con, $sql);
	if (!$result) { 
		$error = mysqli_error($con); 
		print("Ошибка MySQL: " . $error);
	}
	$id = mysqli_insert_id($con);
	
// БЛОК ДОБАВЛЕНИЯ ТЕГОВ
	$tags_array = explode(' ', $tags);									// делаем массив с тегами
	
	// теги в таблицу hashtags (перечень тегов)
	$sql = 'INSERT IGNORE INTO hashtags (hashtag) VALUES';
	$count_tags = count($tags_array) - 1;
	foreach($tags_array as $key => $val) {
		$sql .= ' ("' . $val . '")';
		$sql .= ($key !== $count_tags) ? ',' : '';
	}
	$result = mysqli_query($con, $sql);
	if (!$result) { 
		$error = mysqli_error($con); 
		print("Ошибка MySQL: " . $error);
	}
	
	// теги в таблицу post_hashtag (связь постов и тегов)
	foreach($tags_array as $key => $val) {
		$sql = 'SELECT id FROM hashtags WHERE hashtag = "' . $val . '"'; // забираем id добавленного тега
		$sql_hashtag = mysqli_query($con, $sql);
		$tag_id = mysqli_fetch_assoc($sql_hashtag);						// получаем id в читаемом виде
		$sql = 'INSERT IGNORE INTO post_hashtag SET
			post_id = "' . $id . '",
			hashtag_id = "' . $tag_id['id'] . '"
		';
		$result = mysqli_query($con, $sql);
		if (!$result) { 
			$error = mysqli_error($con); 
			print("Ошибка MySQL: " . $error);
		}
	}
	
// БЛОК ОТПРАВЛЕНИЯ ПИСЕМ ПОДПИСЧИКАМ
	// собираем список адресов, на которые будем отправлять уведомления
	$sql = '
		SELECT
			users.username as name,
			users.email as email
		FROM subscribes
			LEFT JOIN users ON users.id = subscribes.subscriber_id
		WHERE subscribes.sourcer_id = "' . $user_id . '"
	';
	$subscribers = con_sql($con, $sql);									// ассоциативный массив с подписчиками (имя, email)
	$aurhor_id = $_SESSION['user'];										// id автора поста
	$aurhor_username = $_SESSION['username'];							// username автора поста
	$header = $heading;													// заголовок поста
	// отправляем письма
	include ('all__email.php');											// подключаем файл, формирующий транспорт для писем
	foreach ($subscribers as $key => $val) {							// рассылаем письма в цикле
		// формирование сообщения
		$message = new Swift_Message('Новая публикация от пользователя ' . $aurhor_username);
		$message->setTo([$val['email'] => $val['name']]);
		$message->setBody('Здравствуйте, ' . $val['name'] . '. Пользователь ' . $aurhor_username . ' только что опубликовал новую запись „' . $header . '“. Посмотрите её на странице пользователя: <a href="profile.php?id= ' . $aurhor_id . '">страница пользователя</a>');
		$message->setFrom('keks@phpdemo.ru', 'Кекс');
		/* закомментил, чтобы страница не падала, тк SMTP-сервер не работает
		// отправка сообщения
		$mailer = new Swift_Mailer($transport);
		$mailer->send($message);
		*/
	}
	
// РЕДИРЕКТ НА СТРАНИЦУ ПОСТА ПОСЛЕ ПУБЛИКАЦИИ
	header('Location: /post.php?id=' . $id);
}

// БЛОК ГЕНЕРАЦИИ ШАБЛОНА
$page_content = include_template('add_template.php', [
	'content_types' => $content_types,
	'active' => $active,
	'field_names' => $field_names,
	'errors' => $errors,
	'required_fields' => $required_fields
]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => $title]);
print($layout_content);

?>
