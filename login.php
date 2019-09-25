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
    $user_login = $_POST;

    // Создаем пустой массив для записи ошибок в заполнении формы.
    $errors = [];

    // Проверяем поле email.
    if (empty($user_login['email'])) {
        $errors['email'] = 'Введите e-mail';
    } elseif (!filter_var($user_login['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'В введенном e-mail ошибка';
    } else {
        // Собираем запрос на проверку почты в базе.
        $email_check_sql = 'SELECT * FROM users WHERE email = "'
        . mysqli_real_escape_string($db_connect, $user_login['email']) . '"';

        // Выполняем запрос.
        $user = get_data_from_db($email_check_sql, $db_connect, FALSE);

        // Запишем ошибку в $errors если email есть в БД.
        if (empty($user['id'])) {
            $errors['email'] = 'Пользователь с таким e-mail не зарегистрирован';
        }
    }

    // Проверям указан ли пароль.
    if (empty($user_login['password'])) {
        $errors['password'] = 'Введите пароль';
    }

    if (isset($user['id']) AND !empty($user_login['password'])) {
        if (password_verify($user_login['password'], $user['password'])) {
            exit('Все ок!');
        } else {
            $errors['password'] = 'Вы ввели неверный пароль';
        }
    }

    if (count($errors)) {
        // Если есть подключаем габлон и передаем туда список ошибок и меню.
        $content = include_template('login-template.php', [
            'categories' => $categories,
            'errors' => $errors
        ]);
    }

} else {
    // Если форма не отправлена показываем пустую форму.
    $content = include_template('login-template.php', [
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
