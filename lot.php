<?php
require_once('init.php');

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

// Извлекаем id лота из GET-параметра и приводим его к числу для защиты.
$lot_id = intval($_GET['lot_id']);

// Собираем запрос на получение лота по id.
$lot_sql = 'SELECT lots.id, lots.title, lots.start_price AS price, lots.img, '
    . 'categories.name AS category, lots.description, lots.finish_date, '
    . 'lots.bet_step, lots.author_id, MAX(bets.bet) AS bet '
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

// Создаем массив для ставок.
$bets = [];

// Проверяем есть ли стаки по лоту.
if (isset($lot['bet'])) {
    // Если есть ставки, то цена = макс ставка.
    $lot['price'] = $lot['bet'];

    // Формируем запрос на получение списка ставок
    $bets_sql = 'SELECT bets.user_id, users.name, bets.create_date AS data, bets.bet'
    . ' FROM bets'
    . ' JOIN users ON bets.user_id = users.id'
    . ' WHERE bets.lot_id = "' . $lot_id . '"'
    . ' ORDER BY bets.create_date DESC'
    . ' LIMIT 10';

    // Выполняем запрос.
    $bets = get_data_from_db($bets_sql, $db_connect);
}

// Считаем минимальную ставку.
$lot['next_bet'] = $lot['price'] + $lot['bet_step'];



// Проверяем показывать или нет форму добавления ставки
$bit_form_toggle = FALSE;
if (strtotime($lot['finish_date']) > time() AND $is_auth AND $lot['author_id'] != $_SESSION['user']['id']) {
    $bit_form_toggle = TRUE;
}


$content = include_template('lot-template.php', [
    'categories' => $categories,
    'lot' => $lot,
    'bit_form_toggle' => $bit_form_toggle,
    'bets' => $bets
]);

$layout = include_template('layout.php', [
    'title' => 'Yeti Cave | Название_товара',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]);

print($layout);
