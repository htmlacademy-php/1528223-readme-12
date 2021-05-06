<?php

session_start();														// открываем сессию
if (isset($_SESSION['user'])) {											// если в сессии есть переменная user, значит пользователь уже авторизован
	header('Location: /feed.php');										// и регистрироваться ему не нужно, значит редиректим его на стартовую зареганных юзеров
}

$title = 'readmi: регистрация';

$is_auth = 0;

include ('helpers.php');												// подключаем файл с функциями
include ('mysqli_connect.php');											// соединяемся с БД

$required_fields = [];													// создаём массив с обязательными к заполнению полями
if (isset($_POST['email']) !== FALSE) {
	$required_fields[] = 'email';
}
if (isset($_POST['login']) !== FALSE) {
	$required_fields[] = 'login';
}
if (isset($_POST['password']) !== FALSE) {
	$required_fields[] = 'password';
}
if (isset($_POST['password-repeat']) !== FALSE) {
	$required_fields[] = 'password-repeat';
}

$errors = [];															// создаём массив с ошибками
foreach ($required_fields as $field) {
	if (empty($_POST[$field])) {
		if (isset($_POST['email']) AND $field == 'email') {
			$errors[$field]['head'] = 'Email';
		}
		if (isset($_POST['login']) AND $field == 'login') {
			$errors[$field]['head'] = 'Логин';
		}
		if (isset($_POST['password']) AND $field == 'password') {
			$errors[$field]['head'] = 'Пароль';
		}
		if (isset($_POST['password-repeat']) AND $field == 'password-repeat') {
			$errors[$field]['head'] = 'Повтор пароля';
		}
		$errors[$field]['message'] = 'Поле не заполнено';
		$errors[$field]['subhead'] = 'Пустое поле';
		$errors[$field]['submes'] = 'Это поле обязательно для заполнения!';
	}
}

// формируем переменные с содержанием полей и сразу чистим содержание полей от возможного вредоносного кода
$email = clear_input('email');
$login = clear_input('login');
$password = clear_input('password');
$password_repeat = clear_input('password-repeat');

// ВАЛИДАЦИЯ ПОЛЕЙ:

// валидация поля email
if (!empty($email)) {
	// проверяем валидность адреса
	if (filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE) {
		$errors['email']['head'] = 'Email';
		$errors['email']['message'] = 'Неверный формат адреса';
		$errors['email']['subhead'] = 'Неверный формат адреса';
		$errors['email']['submes'] = 'Укажите адрес электронной почты в правильном формате';
	}
	// проверяем уникальность email
	$sql_check = mysqli_query($con, 'SELECT id FROM users WHERE email = "' . $email . '"');
	$check = mysqli_num_rows($sql_check);
	if ($check > 0) {
		$errors['email']['head'] = 'Email';
		$errors['email']['message'] = 'Такой адрес уже есть';
		$errors['email']['subhead'] = 'Адрес есть';
		$errors['email']['submes'] = 'Восстановите пароль или укажите другой адрес эл. почты';
	}
}

// валидация поля login
if (!empty($login)) {
	// проверяем валидность логина
	$sql_check = mysqli_query($con, 'SELECT id FROM users WHERE username = "' . $login . '"');
	$check = mysqli_num_rows($sql_check);
	if ($check > 0) {
		$errors['login']['head'] = 'Логин';
		$errors['login']['message'] = 'Такой логин уже есть';
		$errors['login']['subhead'] = 'Логин есть';
		$errors['login']['submes'] = 'Укажите другой логин';
	}
}

// валидация полей паролей
if (!empty($password) AND !empty($password_repeat)) {
	if ($password !== $password_repeat) {
		$errors['password-repeat']['head'] = 'Повтор пароля';
		$errors['password-repeat']['message'] = 'Пароли не совпадают';
		$errors['password-repeat']['subhead'] = 'Пароли не совпадают';
		$errors['password-repeat']['submes'] = 'Повтор пароля не совпадает с паролем';
	}
}

// валидация аватарки
if (isset($_FILE['file']) AND $_FILE['file']['type'] !== 'image/jpeg' AND $_FILE['file']['type'] !== 'image/gif' AND $_FILE['file']['type'] !== 'image/png') {
	$errors['userpic-file']['head'] = 'Фото';
	$errors['userpic-file']['message'] = 'Неверный тип файла';
	$errors['userpic-file']['subhead'] = 'Неверный тип';
	$errors['userpic-file']['submes'] = 'Тип файла должен быть JPG, GIF или PNG';
}

// ЕСЛИ ОШИБОК В ЗАПОЛНЕНИИ НЕТ:

if (isset($email) AND count($errors) == 0) {
	// обрабатываем файл изображения
	if ($_FILES['userpic-file']['name'] !== '') {
		$file_link = $_FILES['userpic-file']['tmp_name'];
		$file_name = $_FILES['userpic-file']['name'];
		$image_url = 'uploads/' . $file_name;
		file_put_contents($image_url, file_get_contents($file_link));
		$avatar = $file_name;
	} else {
		$avatar = '';
	}
	
	// делаем хеш пароля
	$password_hash = password_hash($password, PASSWORD_DEFAULT);
	
	// заливаем данные в БД
	$sql = '
		INSERT INTO users SET
		email = "' . $email . '",
		username = "' . $login . '",
		password = "' . $password_hash . '",
		avatar = "' . $avatar . '"
		';
	$result = mysqli_query($con, $sql);
	if (!$result) { 
		$error = mysqli_error($con); 
		print("Ошибка MySQL: " . $error);
	}
	
	// редиректим на страницу popular.php
	header('Location: /feed.php');
}

// содержание страницы
$page_content = include_template('reg.php', ['errors' => $errors]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => $title, 'is_auth' => $is_auth]);

print($layout_content);

?>
