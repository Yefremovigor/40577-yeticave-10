<?php
$db_connect = mysqli_connect('127.0.0.1:8889', 'root', 'root','yeticave');

if ($db_connect == false) {
    print('Ошибка подключения: ' . mysqli_connect_error());
}

mysqli_set_charset($db_connect, 'utf8');

if ($errors !== []) {
    print('есть ошибка');
}
