<?php
$con = mysqli_connect('127.0.0.1:8889', 'root', 'root','yeticave');

if ($con == false) {
    print('Ошибка подключения: ' . mysqli_connect_error());
}

mysqli_set_charset($con, 'utf8');
