<?php

session_start();

unset($_SESSION['user']);
unset($_SESSION['username']);
header('Location: /index.php');

?>
