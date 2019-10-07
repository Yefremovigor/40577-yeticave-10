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



// Проверяем показывать или нет форму добавления ставки.
// По умолчанию форма скрыта.
$bit_form_toggle = FALSE;

// Проверяем что лот еще на торгах.
$is_lot_alive = strtotime($lot['finish_date']) > time();

// Проверяем что пользователь не автор.
$is_user_lot_author = $lot['author_id'] == $_SESSION['user']['id'];

// Проверяем что последняя ставка не от пользователя.
$is_ussers_bet_last = FALSE;
if (!empty($bets)) {
    $is_ussers_bet_last = ($bets[0]['user_id']) == $_SESSION['user']['id'];
}

if ($is_auth AND $is_lot_alive AND !$is_user_lot_author AND !$is_ussers_bet_last) {
    $bit_form_toggle = TRUE;
}

// Проверяем отправлена ли форма.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Сохраняем переданные данные.
    $new_bet = $_POST;

    // Создаем масив для накопление ошибок из формы.
    $errors = [];

    // Проверяем поле ставки.
    if (empty($new_bet['cost'])) {
        $errors['cost'] = 'Укажите вашу ставку';
      } else if (!filter_var($new_bet['cost'], FILTER_VALIDATE_INT) OR $new_bet['cost'] < $lot['next_bet']) {
        $errors['cost'] = 'Cтавка должна быть целым числом, которо больше или равно минимальной ставки';
    }

    // Проверяем есть ли ошибки
    if (count($errors)) {
        // Выводим ошибки в шаблон.
        $content = include_template('lot-template.php', [
            'categories' => $categories,
            'lot' => $lot,
            'bit_form_toggle' => $bit_form_toggle,
            'errors' => $errors,
            'bets' => $bets
        ]);
    } else {
        // Формируем запрос для добавления ставки.
        $add_new_bet_sql = 'INSERT INTO bets'
        . ' SET'
        . ' bet = ' . intval($new_bet['cost']) . ','
        . ' user_id = ' . $_SESSION['user']['id'] . ','
        . ' lot_id = ' . $lot_id;

        // Выполняем запрос на добавление.
        $add_new_bet_lot = mysqli_query($db_connect, $add_new_bet_sql);

        header('Location: lot.php?lot_id='.$lot_id);
        exit();
    }
} else {
    $content = include_template('lot-template.php', [
        'categories' => $categories,
        'lot' => $lot,
        'bit_form_toggle' => $bit_form_toggle,
        'bets' => $bets
    ]);
}

$layout = include_template('layout.php', [
    'title' => 'Yeti Cave | Название_товара',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]);

print($layout);
