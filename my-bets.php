<?php
require_once('init.php');

// Собираем запрос для получения саиска категорий.
$categories_sql = 'SELECT * FROM categories';

// Выполняем запрос и конвертируем данные в двумерный массив.
$categories = get_data_from_db($categories_sql, $db_connect);

// Собираем запро для получения списка ставок.
$my_bets_sql = 'SELECT lots.id, lots.title, lots.img, lots.finish_date,'
. ' lots.winner_id, categories.name AS category, users.contacts AS contact,'
. ' bets.bet, bets.create_date'
. ' FROM bets'
. ' JOIN lots ON bets.lot_id = lots.id'
. ' JOIN categories ON lots.category_id = categories.id'
. ' JOIN users ON lots.author_id = users.id'
. ' WHERE bets.user_id = ' . $_SESSION['user']['id']
. ' ORDER BY bets.create_date DESC';

// Выполняем запрос.
$my_bets = get_data_from_db($my_bets_sql, $db_connect);

// Вычисляем статус лота.
foreach ($my_bets as $key => $value) {
    $lot_finish_date = strtotime($value['finish_date']);
    if ($value['winner_id'] == $_SESSION['user']['id']) {
        $my_bets[$key]['lot_status'] = 'win';
    } elseif ($lot_finish_date < time()) {
        $my_bets[$key]['lot_status'] = 'finish';
    } elseif (3600 > $lot_finish_date - time() AND $lot_finish_date - time() > 0 ) {
        $my_bets[$key]['lot_status'] = 'is_finish';
    } else {
        $my_bets[$key]['lot_status'] = 'on_trades';
    }

}

$content = include_template('my-bets-template.php', [
    'categories' => $categories,
    'my_bets' => $my_bets
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
