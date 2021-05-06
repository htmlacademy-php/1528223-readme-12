<?php

session_start();														// открываем сессию
if (isset($_SESSION['user'])) {											// если в сессии есть переменная user, значит пользователь уже авторизован
	header('Location: /feed.php');										// и регистрироваться ему не нужно, значит редиректим его на стартовую зареганных юзеров
} else {
	$is_auth = 0;
}

include ('helpers.php');												// подключаем файл с функциями
include ('mysqli_connect.php');											// соединяемся с БД

$email = clear_input('login');											// чистим содержимое полей
$password = clear_input('password');

$errors = [																// создаём массив с ошибками (по умолчанию ошибок нет)
	'email' => 0,
	'password' => 0
];

if ($email AND $password) {												// если и логин и пароль внесены
	if (!$con) {
		print('Ошибка подключения: ' . mysqli_connect_error());
	} else {
		$sql = 'SELECT * FROM users WHERE email = "' . $email . '"'; 	// готовим запрос email к таблице users
		$sql_row = mysqli_query($con, $sql);							// отправляем запрос
		$check = mysqli_num_rows($sql_row);								// смотрим число найденных строк с таким логином
	}
	
	if ($check == 0) {													// если логин не найден (количество строк равно нулю), то:
		$errors['email'] = 1;											// записываем в массив ошибку логина
	} else {															// если такой логин есть, то:
		$sql_row = mysqli_fetch_assoc($sql_row);						// переводим строку mysql в ассоциативный массив
		$hash = $sql_row['password'];									// забираем элемент хеш пароля из массива
		$check = password_verify($password ,$hash);						// сравниваем введённый пароль и хеш пароля с помощью встроенной функции
		if ($check == FALSE) {											// если сравнение не прошло, то:
			$errors['password'] = 1;									// записываем в массив ошибку password
		} else {														// если логин и пароль совпадают, то:
			$id = $sql_row['id'];										// сохраняем id пользователя
			$username = $sql_row['username'];							// сохраняем имя пользователя
			session_start();											// открываем сессию
			$_SESSION['user'] = $id;									// создаём сессию с id этого пользователя
			$_SESSION['username'] = $username;							// создаём сессию с именем этого пользователя
			header('Location: /feed.php');								// редиректим на главную страницу авторизованного пользователя
		}
	}
}

$layout_content = include_template('start_layout.php', ['errors' => $errors]);

print($layout_content);

?>
