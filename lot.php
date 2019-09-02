<?php
// Проверямем авторизацию.
$is_auth = rand(0, 1);
$user_name = 'Игорь';

// Подключаем базу данных.
require_once('db.php');
// Поподключаем функции.
require_once('helpers.php');
require_once('functions.php');
// Задаем часовой пояс
date_default_timezone_set('Europe/Moscow');

// Проверяем GET параметр
if (empty($_GET['lot_id'])) {
    // Формируем 404 заголовок.
    header("HTTP/1.x 404 Not Found");

    // Окончаем выволнение сценария.
    die();
}

// Собираем запрос для получения саиска категорий.
$categories_sql = 'SELECT * FROM categories';

// Выполняем запрос и конвертируем данные в двумерный массив.
$categories = get_data_from_db($categories_sql, $db_connect);

// Собираем запрос на получение лота по id.
$lot_sql = 'SELECT * FROM lots JOIN categories ON lots.category_id = categories.id WHERE lots.id = 2';

// Выполняем запрос и конвертируем данные в двумерный массив.
$lot = get_data_from_db($lot_sql, $db_connect, FALSE);

$content = include_template('lot-template.php', [
    'categories' => $categories,
    'lot' => $lot
]);

$layout = include_template('layout.php', [
    'title' => 'Yeti Cave | Название_товара',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]);

print($layout);
