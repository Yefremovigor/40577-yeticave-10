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

    // Проверяем заплнение категориии нового лота.
    if (empty($new_lot['category'])) {
        $errors['category'] = 'Выберите категорию лота';
    } else {
        // Приваодим переданый id категории к числу.
        $selected_category = intval($new_lot['category']);
        // Создаем запрос пдля проверки наличия выбранного id категории в БД.
        $category_check_sql = 'SELECT id FROM categories WHERE id = ' . $selected_category;
        // Выполняем запрос.
        $category_check = count_rows_in_db($category_check_sql, $db_connect);
        // Записываем ошибку в массив, если category_check содержит 0.
        if (!$category_check) {
            $errors['category'] = 'Выберите категорию из списка';
        }
    }

    // Проверяем заплнение описания нового лота.
    if (empty($new_lot['message'])) {
        $errors['message'] = 'Заполните описание лота';
    }

    // проверяем загруженый файл.
    if (isset($_FILES['lot-img'])) {
        // Сохраняем временное имя файла.
        $file_name = $_FILES['lot-img']['tmp_name'];

        // Получаем информацию о типе файла.
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $file_name);

        // Сравниваем тип файла с допустимым.
        if ($file_type !== 'image/jpeg' AND $file_type !== 'image/png') {
            $errors['lot-img'] = 'Фотография лота должна быть в формате .jpg, .jpeg или .png';
        }

        // Проверяем размер файла.
        $file_size = $_FILES['lot-img']['size'];
        if ($file_size > 2000000) {
            $errors['lot-img'] = 'Фотография должна весить не больше 2Мб.';
        }
    } else {
        $errors['lot-img'] = 'Загрузите фотографию лота в формате .jpg, .jpeg или .png';
    }

    // Проверяем заплнение стартовой цены нового лота.
    if (empty($new_lot['lot-rate'])) {
        $errors['lot-rate'] = 'Укажите начвльную цену лота';
    } elseif (intval($lot['lot-rate']) <= 0) {
        $errors['lot-rate'] = 'Цену лота должна быть целым числом больше нуля';
    }

    // Проверяем заплнение шага ставки нового лота.
    if (empty($new_lot['lot-step'])) {
        $errors['lot-step'] = 'Укажите начвльную цену лота';
    } elseif (intval($lot['lot-step']) <= 0) {
        $errors['lot-step'] = 'Шаг ставки быть целым числом больше нуля';
    }

    // Проверяем дату окончания торгов.
    if (empty($new_lot['lot-date'])) {
        $errors['lot-date'] = 'Укажите дату окончания торгов';
    } elseif (!is_date_valid($new_lot['lot-date'])) {
        $errors['lot-date'] = 'Введите дату в формате ГГГГ-ММ-ДД';
    } elseif (strtotime($new_lot['lot-date']) < strtotime('tomorrow')) {
        $errors['lot-date'] = 'Дата окончания торгов не может раньше чем '
        . date('Y-m-d', time('tomorrow') + 86400);
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
