<?php

$sql = 'SELECT id, name, class FROM content_type';
$sql_content_types = mysqli_query($con, $sql);
$content_types = [];
if ($sql_content_types) {												// если данные из БД получены, то получаем массив с данными
	$content_types = mysqli_fetch_all($sql_content_types, MYSQLI_ASSOC);
} else {
	include ('all__goto_404.php');										// если данные из БД не получены, то 404
}

?>
