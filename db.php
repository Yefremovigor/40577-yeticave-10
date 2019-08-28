<?php
$db_connect = mysqli_connect('127.0.0.1:8889', 'root', 'root','yeticave');

// Проверяем подключение к БД.
if ($db_connect) {
    mysqli_set_charset($db_connect, 'utf8');
} else {
    $errors[] = 'Ошибка подключения к БД: ' . mysqli_connect_error();
}
