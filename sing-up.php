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
    $new_user = $_POST;

    // Создаем пустой массив для записи ошибок в заполнении формы.
    $errors = [];

    // Проверяем поле email.
    if (empty($new_user['email'])) {
        $errors['email'] = 'Введите e-mail';
    } elseif (!filter_var($new_user['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'В введенном e-mail ошибка';
    } else {
        // Собираем запрос на проверку почты в базе.
        $email_check_sql = 'SELECT email FROM users WHERE email = "'
        . mysqli_real_escape_string($db_connect, $new_user['email']) . '"';

        // Выполняем запрос.
        $email_check = count_rows_in_db($email_check_sql, $db_connect);

        // Запишем ошибку в $errors если email есть в БД.
        if ($email_check) {
            $errors['email'] = 'Пользователь с таким e-mail уже зарегистрирован';
        }
    }

    // Проверям пароль.
    if (empty($new_user['password'])) {
        $errors['password'] = 'Введите пароль';
    }

    // Проверям имя.
    if (empty($new_user['name'])) {
        $errors['name'] = 'Введите имя';
    }

    // Проверям имя.
    if (empty($new_user['message'])) {
        $errors['message'] = 'Введите контактные двнные';
    }

    // Проверяем есть ли ошибки в массиве $errors.
    if (count($errors)) {
        // Если есть подключаем габлон и передаем туда список ошибок и меню.
        $content = include_template('sing-up-template.php', [
            'categories' => $categories,
            'errors' => $errors
        ]);
    } else {
        // Формируем запрос на добавление нового пользователя.
        $new_user_sql = 'INSERT INTO users SET'
        . ' email = "' . mysqli_real_escape_string($db_connect, $new_user['email']) . '"'
        . ', name = "' . mysqli_real_escape_string($db_connect, $new_user['name']) . '"'
        . ', password = "' . password_hash($new_user['password'], PASSWORD_DEFAULT) . '"'
        . ', contacts = "' . mysqli_real_escape_string($db_connect, $new_user['message']) . '"';

        // Выполняем запрос на добавление.
        $add_new_user = mysqli_query($db_connect, $new_user_sql);
        if (!$add_new_user) {
            die('Ошибка в sql запросе: ' . mysqli_error($db_connect));
        }

        // Перенаправляем человека на страницу входа.
        header('Location: login.php');
        exit();
    }
} else {
    // Если форма не отправлена показываем пустую форму.
    $content = include_template('sing-up-template.php', [
        'categories' => $categories
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
