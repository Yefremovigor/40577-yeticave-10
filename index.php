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

// Собираем запрос для получения саиска лотов для главной.
$ads_sql = 'SELECT lots.title, categories.name AS category, lots.start_price AS price,'
    . ' lots.img, lots.finish_date AS auction_end_date FROM lots '
    . 'JOIN categories ON lots.category_id = categories.id';

// Выполняем запрос и конвертируем данные в двумерный массив.
$ads = get_data_from_db($ads_sql, $db_connect);

// Собираем запрос для получения саиска категорий.
$categories_sql = 'SELECT * FROM categories';

// Выполняем запрос и конвертируем данные в двумерный массив.
$categories = get_data_from_db($categories_sql, $db_connect);;

$content = include_template('main.php', [
    'categories' => $categories,
    'ads' => $ads
]);

$layout = include_template('layout.php', [
    'title' => 'Yeti Cave | Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content,
    'main_class' => 'container'
]);

print($layout);
