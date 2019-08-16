<?php
$is_auth = rand(0, 1);

include_once('data.php');
include_once('functions.php');

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
?>
