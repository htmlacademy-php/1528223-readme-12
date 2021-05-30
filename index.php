<?php

include ('all__redirectfeed.php');										// редиректим авторизованных пользователей на feed.php
include ('all__helpers.php');											// подключаем файл с функциями
include ('all__mysqli_connect.php');									// соединяемся с БД

$email = clear_input('login');											// чистим содержимое полей
$password = clear_input('password');

// БЛОК АВТОРИЗАЦИИ
$errors = [];
if ($email AND $password) {												// если и логин и пароль внесены
	$check = 0;
	if (!$con) {
		print('Ошибка подключения: ' . mysqli_connect_error());
	} else {
		$sql = 'SELECT * FROM users WHERE email = "' . $email . '"'; 	// готовим запрос email к таблице users
		$sql_row = mysqli_query($con, $sql);							// отправляем запрос
		$check = mysqli_num_rows($sql_row);								// смотрим число найденных строк с таким логином
	}
	
	if ($check === 0) {													// если логин не найден (количество строк равно нулю), то:
		$errors['email'] = 1;											// записываем в массив ошибку логина
	} else {															// если такой логин есть, то:
		$sql_row = mysqli_fetch_assoc($sql_row);						// переводим строку mysql в ассоциативный массив
		$hash = $sql_row['password'];									// забираем элемент хеш пароля из массива
		$check = password_verify($password ,$hash);						// сравниваем введённый пароль и хеш пароля с помощью встроенной функции
		if ($check == FALSE) {											// если сравнение не прошло, то:
			$errors['password'] = 1;									// записываем в массив ошибку password
		} else {														// если логин и пароль совпадают, то:
			session_start();											// открываем сессию
			$_SESSION['user'] = $sql_row['id'];							// создаём сессию с id этого пользователя
			$_SESSION['username'] = $sql_row['username'];				// создаём сессию с именем этого пользователя
			$_SESSION['avatar'] = $sql_row['avatar'];					// создаём сессию с аватаркой этого пользователя
			header('Location: /popular.php');							// редиректим на главную страницу авторизованного пользователя
		}
	}
}

// БЛОК ГЕНЕРАЦИИ ШАБЛОНА
$layout_content = include_template('start_template.php', [
	'errors' => $errors
]);
print($layout_content);

?>
