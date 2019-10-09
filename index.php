<?php
require_once('init.php');
require_once('getwinner.php');

// Собираем запрос для получения саиска лотов для главной.
$ads_sql = 'SELECT lots.id, lots.title, categories.name AS category, lots.start_price AS price,'
    . ' lots.img, lots.finish_date AS auction_end_date FROM lots'
    . ' JOIN categories ON lots.category_id = categories.id'
    . ' WHERE lots.finish_date > CURDATE()'
    . ' ORDER BY lots.create_date DESC'
    . ' LIMIT 9';

// Выполняем запрос и конвертируем данные в двумерный массив.
$ads = get_data_from_db($ads_sql, $db_connect);

// Собираем запрос для получения саиска категорий.
$categories_sql = 'SELECT * FROM categories';

// Выполняем запрос и конвертируем данные в двумерный массив.
$categories = get_data_from_db($categories_sql, $db_connect);

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
