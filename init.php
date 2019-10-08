<?php
// Проверямем авторизацию.
session_start();
$is_auth = (isset($_SESSION['user'])) ? 1 : 0;
$user_name = (isset($_SESSION['user'])) ? $_SESSION['user']['name'] : '';

// Подключаем пакеты из composer
require_once('vendor/autoload.php');

// Подключаем базу данных.
require_once('db.php');
// Поподключаем функции.
require_once('helpers.php');
require_once('functions.php');
// Задаем часовой пояс
date_default_timezone_set('Europe/Moscow');
