<?php

require_once('includes/authorization.php'); // подключаем файл с авторизацией (здесь объявляется $user_id)
require_once('includes/helpers.php'); // подключаем файл с функциями
require_once('includes/mysqli_connect.php');

$title = 'readme: личные сообщения';

$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? null; // если $_GET['id'] есть, то чистим

// обрабатываем сообщение

$post_message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS) ?? false;
$errors = 0;
if ($post_message !== false and $get_id !== $user_id) {
    $post_message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS) ?? null; // чистим
    $post_message = trim($post_message);                                // убираем пробелы

    if ($post_message === '') {
        $errors = 1;
    }

    if ($errors === 0) {
        $sql = '
            INSERT INTO messages SET
            message = "' . $post_message . '",
            sender_id = "' . $user_id . '",
            recepient_id = "' . $get_id . '"
        ';
        $result = mysqli_query($con, $sql);
        if (!$result) {
            $error = mysqli_error($con);
            print("Ошибка MySQL: " . $error);
        }
    }
}

// собираем пользователей, с которыми есть переписка

$sql = '
    SELECT
        IF(users.id = "' . $user_id . '", messages.recepient_id, users.id) as id,
        IF(
            users.id = "' . $user_id . '",
                (SELECT username
                FROM users
                WHERE id = messages.recepient_id), users.username)
        as name,
        IF(
            users.id = "' . $user_id . '", (SELECT avatar FROM users WHERE id = messages.recepient_id), users.avatar)
        as avatar,

        messages.dt_add as dt_add,
        messages.message as message,
        messages.sender_id as sender,
        messages.recepient_id as recepient
    FROM users
        LEFT JOIN messages ON messages.sender_id = users.id
    WHERE
        messages.recepient_id = "' . $user_id . '" OR
        messages.sender_id = "' . $user_id . '"
    ORDER BY messages.dt_add DESC
';
$users = con_sql($con, $sql, 'all'); // получаем ассоциативный массив с юзерами, с которыми была переписка
$users = array_unique_key($users, 'id'); // убираем повторяющихся юзеров с помощью функции из helpers.php

// показываем последнюю переписку
$last_id = null;
$users_0 = (isset($users[0])) ? $users[0] : null;
$users_0_id = (isset($users[0]['id'])) ? $users[0]['id'] : null;
if (array_key_exists('0', $users)) {
    if (array_key_exists('id', $users[0])) {
        $last_id = $users_0_id;
    }
}
if ($get_id === null) { // редиректим на юзера, с которым была последняя переписка, если нет ?id=
    header('Location: /messages.php?id=' . $last_id);
} else { // или добавляем нового пользователя в меню слева, если его ещё нет
    $users_ids = array_column($users, 'id');
    if (in_array($get_id, $users_ids) === false) {
        $sql = '
            SELECT
                users.id as id,
                users.username as name,
                users.avatar as avatar
            FROM users
            WHERE users.id = "' . $get_id . '"
        ';
        $new_user = con_sql($con, $sql, 'all'); // получаем ассоциативный массив с пользователем
        $users = array_merge($new_user, $users);
    }
}

// получаем список сообщений

$sql = '
    SELECT
        messages.dt_add,
        messages.message,
        messages.sender_id,
        (SELECT username FROM users WHERE id = messages.sender_id) as username,
        (SELECT avatar FROM users WHERE id = messages.sender_id) as avatar
    FROM messages
    WHERE
        (messages.recepient_id = "' . $user_id . '" AND messages.sender_id = "' . $get_id . '") OR
        (messages.recepient_id = "' . $get_id . '" AND messages.sender_id = "' . $user_id . '")
    ORDER BY messages.dt_add
';
$messages = con_sql($con, $sql, 'all'); // получаем ассоциативный массив с сообщениями

// БЛОК ГЕНЕРАЦИИ ШАБЛОНА
$page_content = include_template('messages_template.php', [
    'user_id' => $user_id,
    'get_id' => $get_id,
    'users' => $users,
    'messages' => $messages,
    'errors' => $errors
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title]);
print($layout_content);
