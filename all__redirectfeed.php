<?php

session_start();														// открываем сессию
if (isset($_SESSION['user'])) {											// если в сессии есть переменная user, значит пользователь уже авторизован
	header('Location: /feed.php');										// и регистрироваться ему не нужно, значит редиректим его на стартовую зареганных юзеров
} else {
	$is_auth = 0;
}

?>