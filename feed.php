<?php

session_start();														// открываем сессию
if (!isset($_SESSION['user'])) {										// если в сессии нет переменной user, значит надо сначала авторизоваться
	header('Location: /index.php');										// поэтому редиректим на index.php
} else {
	$is_auth = 1;
}

include ('helpers.php');												// подключаем файл с функциями
include ('mysqli_connect.php');											// соединяемся с БД

$title = 'readme: моя лента';

// содержание страницы
$page_content = include_template('start_feed.php');

// окончательный HTML-код
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => $title, 'is_auth' => $is_auth]);

print($layout_content);

?>
