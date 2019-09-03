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

// Извлекаем id лота из GET-параметра.
$lot_id = $_GET['lot_id'];

// Собираем запрос на получение лота по id.
$lot_sql = 'SELECT lots.id, lots.title, lots.start_price AS price, lots.img, '
    . 'categories.name AS category, lots.description, lots.finish_date, '
    . 'lots.bet_step, MAX(bets.bet) AS bet '
    . 'FROM lots '
    . 'JOIN categories ON lots.category_id = categories.id '
    . 'JOIN bets ON lots.id = bets.lot_id '
    . 'WHERE lots.id = ' . $lot_id;

// Выполняем запрос и конвертируем данные в двумерный массив.
$lot = get_data_from_db($lot_sql, $db_connect, FALSE);

// Проверяем что лот есть в БД
if (empty($lot['id'])) {
    // Формируем 404 заголовок.
    header("HTTP/1.x 404 Not Found");

    // Окончаем выволнение сценария.
    die();
}

// Если по лоту есть ставка цена = макс ставка.
if (isset($lot['bet'])) {
    $lot['price'] = $lot['bet'];
}

// Считаем минимальную ставку.
$lot['next_bet'] = $lot['price'] + $lot['bet_step'];

// Проверяем показывать или нет форму добавления ставки
$bit_form_toggle = FALSE;
if (strtotime($lot['finish_date']) > time() && $is_auth) {
    $bit_form_toggle = TRUE;
}


$content = include_template('lot-template.php', [
    'categories' => $categories,
    'lot' => $lot,
    'bit_form_toggle' => $bit_form_toggle
]);

$layout = include_template('layout.php', [
    'title' => 'Yeti Cave | Название_товара',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]);

print($layout);
