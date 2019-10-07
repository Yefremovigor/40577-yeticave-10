<?php
require_once('init.php');

// Собираем запрос для получения саиска категорий.
$categories_sql = 'SELECT * FROM categories';

// Выполняем запрос и конвертируем данные в двумерный массив.
$categories = get_data_from_db($categories_sql, $db_connect);

$content = include_template('my-bets-template.php', [
    'categories' => $categories
]);

// Если пользователь неавторизован, то выдаем ему 403 код.
if (empty($_SESSION['user'])) {
    header("HTTP/1.x 403 Forbidden");
    exit();
}

$layout = include_template('layout.php', [
    'title' => 'Yeti Cave | Название_товара',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]);

print($layout);
