<?php
// Создаем массив для хранения возможных ошибок.
$errors = [];

// Объявляем все переменные необходимые для отображения layout.
$user_name = 'Игорь';
$categories = [];
$content = '';

// Проверямем авторизацию.
$is_auth = rand(0, 1);

// Подключаем базу данных.
include_once('db.php');
// Поподключаем функции.
include_once('helpers.php');
include_once('functions.php');

// Задаем часовой пояс
date_default_timezone_set('Europe/Moscow');

// Собираем запрос для получения саиска лотов для главной.
$ads_sql = 'SELECT lots.title, categories.name AS category, lots.start_price AS price,'
    . ' lots.img, lots.finish_date AS auction_end_date FROM lots '
    . 'JOIN categories ON lots.category_id = categories.id';

// Выаолняем запрос.
$ads_result = mysqli_query($db_connect, $ads_sql);
if (!$ads_result) {
    print('Ошибка в запросе');
}

// Конвертируем данные в массив.
$ads = mysqli_fetch_all($ads_result, MYSQLI_ASSOC);

// Собираем запрос для получения саиска категорий.
$categories_sql = 'SELECT * FROM categories';

// Выполняем запрос.
$categories_result = mysqli_query($db_connect, $categories_sql);
if (!$categories_sql) {
    print('Ошибка в запросе');
}

// Конвертируем данные в массив.
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);

$content = include_template('main.php', [
    'categories' => $categories,
    'ads' => $ads
]);

$layout = include_template('layout.php', [
    'title' => 'Yeti Cave | Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]);
print($layout);
