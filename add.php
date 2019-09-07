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

// Собираем запрос для получения саиска категорий.
$categories_sql = 'SELECT * FROM categories';

// Выполняем запрос и конвертируем данные в двумерный массив.
$categories = get_data_from_db($categories_sql, $db_connect);

// Проверяем отправлена ли форма (запрошана ли страница через метот POST).
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Записываем переданные данные.
    $new_lot= $_POST;

    // Определяем список обязательных полей.
    $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];

    // Создаем пустой массив для записи ошибок в заполнении формы.
    $errors = [];

    // Проверяем заплнение названия нового лота.
    if (empty($new_lot['lot-name'])) {
        $errors['lot-name'] = 'Заполните название лота';
    } elseif (is_length_invalid($new_lot['lot-name'], 10, 64)) {
        $errors['lot-name'] = 'Название лота должно быть длинной от 10 до 64 символов';
    }
}

$content = include_template('add-template.php', [
    'categories' => $categories
]);

$layout = include_template('layout.php', [
    'title' => 'Yeti Cave | Название_товара',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $content
]);

print($layout);
