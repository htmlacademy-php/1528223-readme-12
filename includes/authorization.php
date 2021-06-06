<?php

session_start(); // открываем сессию
if (!isset($_SESSION['user'])) { // если в сессии нет переменной user, значит надо сначала авторизоваться
    header('Location: /index.php'); // поэтому редиректим на index.php
} else {
    $user_id = filter_var($_SESSION['user'], FILTER_SANITIZE_SPECIAL_CHARS);
}
