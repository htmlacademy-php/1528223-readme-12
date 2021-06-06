<?php

$con = mysqli_connect('localhost', 'root', '','readme');
mysqli_set_charset($con, "utf8");

if (!$con) {
   include ('all__goto_404.php');										// если данные из БД не получены, то 404
}

// установка временной зоны по умолчанию
date_default_timezone_set('Europe/Moscow');

?>
