<?php
require_once('includes/redirectfeed.php'); // редиректим авторизованных пользователей на feed.php
require_once('includes/helpers.php'); // подключаем файл с функциями
require_once('includes/mysqli_connect.php'); // соединяемся с БД

$email = clear_input('login'); // чистим содержимое полей с помощью функции из helpers.php
$password = clear_input('password');

$errors = []; // массив, в который записываются ошибки

// проверяем поля на заполненность

if (isset($_POST['submit'])) {
    $error_text = 'Это поле должно быть заполнено';
    if (!$email) { // проверяем, что поле логина заполнено
        $errors['email'] = $error_text;
    }
    if (!$password) { // проверяем, что поле пароля заполнено
        $errors['password'] = $error_text;
    }
}

// проверяем наличие логина в БД и если находим, то сравниваем пароль с хешом

$check_login = 0; // по умолчанию логин не найден
$check_password = false; // по умолчанию пароли не совпадают
if (isset($_POST['submit']) and count($errors) < 1) {
    $sql = '
        SELECT
            id,
            password,
            username,
            avatar
        FROM users
        WHERE email = "' . $email . '"
    '; // запрос: забираем данные по пользователю с таким логином

    $check_login = con_sql($con, $sql, 'num'); // получаем число строк с данным логином
    if ($check_login < 1) { // если логин не найден (количество строк равно нулю) и нет пустых полей, то:
        $errors['email'] = 'Неверный логин'; // записываем в массив ошибку логина
    } else { // если такой логин есть, то:
        $db_login = con_sql($con, $sql, 'assoc'); // переносим данные по этому логину в одномерный массив
        $db_hash = $db_login['password']; // забираем пароль из массива
        // сравниваем введённый пароль и хеш пароля
        $check_password = (array_key_exists('password', $db_login) ? password_verify($password, $db_hash) : false);
    }
}

// если логин найден и пароли совпадают, то записываем сессию и редиректим

if ($check_login > 0) {
    if ($check_password === true) { // если логин и пароль совпадают, то:
        $db_login_id = (isset($db_login['id'])) ? $db_login['id'] : false;
        $db_login_username = (isset($db_login['username'])) ? $db_login['username'] : false;
        $db_login_avatar = (isset($db_login['avatar'])) ? $db_login['avatar'] : false;
        session_start(); // открываем сессию
        $_SESSION['user'] = $db_login_id; // записываем в сессию id пользователя
        $_SESSION['username'] = $db_login_username; // записываем в сессию имя пользователя
        $_SESSION['avatar'] = $db_login_avatar; // записываем в сессию адрес аватара пользователя
        header('Location: /popular.php'); // редиректим на главную страницу авторизованного пользователя
    } else { // если сравнение не прошло, то:
        $errors['password'] = 'Пароли не совпадают'; // записываем в массив ошибку password
    }
}

// БЛОК ГЕНЕРАЦИИ ШАБЛОНА
$layout_content = include_template('index_template.php', [
    'errors' => $errors
]);
print($layout_content);
