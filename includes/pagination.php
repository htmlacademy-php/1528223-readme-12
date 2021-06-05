<?php

$sql .= ' LIMIT 9';

$sql_num = 'SELECT COUNT(id) FROM posts';                                // общее количество постов

$sql_check = mysqli_query($con, $sql_num);
$num = mysqli_fetch_row($sql_check);
$num = array_shift($num);
$total_pages = ceil($num / 9);

// на какой странице сейчас (если $_GET['page'] нет, то на первой)
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?? 1;
if ($page >= 1 and $page <= $total_pages) {
    $sql .= ' OFFSET ' . ($page - 1) * 9;
} else {
    $page = 1;
    $sql .= ' OFFSET ' . 0;
}

$prev_link = null; // cразу формируем ссылки для кнопок "предыдущая.." и "следующая.."
$next_link = null;
if ($page > 1) {
    $prev_link = $page - 1;
}
if ($page < $total_pages) {
    $next_link = $page + 1;
}
