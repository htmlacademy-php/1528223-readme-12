<?php

session_start();
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}
if (isset($_SESSION['username'])) {
    unset($_SESSION['username']);
}
header('Location: /index.php');
