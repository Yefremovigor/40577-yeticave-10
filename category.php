<?php
require_once('init.php');

// Собираем запрос для получения саиска категорий.
$categories_sql = 'SELECT * FROM categories';

// Выполняем запрос и конвертируем данные в двумерный массив.
$categories = get_data_from_db($categories_sql, $db_connect);

// Определяем переменную для хранения категории.
$category_id = intval($_GET['category_id']) ?? '';

// Формируем запрос на имя категории.
$category_page_name_sql = 'SELECT name FROM categories WHERE id = "' . $category_id . '"';

// Выполняем запрос.
$category_page_name = get_data_from_db($category_page_name_sql, $db_connect, FALSE);

if (empty($category_page_name['name'])) {
    // Формируем 404 заголовок.
    header("HTTP/1.x 404 Not Found");

    // Окончаем выволнение сценария.
    die();
}

// Формируем запрос на проверку сколько товаров в БД.
$category_lots_count_sql = 'SELECT id FROM lots'
. ' WHERE category_id = "' . $category_id . '"'
. ' AND lots.finish_date > CURDATE()';

// Выполняем запрос.
$category_lots_count = count_rows_in_db($category_lots_count_sql, $db_connect);

// Проверяем есть ли результаты.
if ($category_lots_count) {
    // Задаем количество лотов на странице.
    $lots_on_page = 9;
    // Проверяем сколько страниц нцжно в пагинации.
    $pagination_size = ceil($category_lots_count / $lots_on_page);

    // Генерируем масив номеров страниц для пагинации.
    $pagination_pages = range(1, $pagination_size);

    // Определяем страницу поисковых резкльтатов
    $search_page = intval($_GET['page'] ?? 1);

    // Определяем смещение.
    $offset = ($search_page - 1) * $lots_on_page;

    // Формируем запрос.
    $category_lots_sql = 'SELECT lots.id, lots.title, categories.name AS category, lots.start_price AS price,'
    . ' lots.img, lots.finish_date AS auction_end_date FROM lots'
    . ' JOIN categories ON lots.category_id = categories.id'
    . ' WHERE category_id = "' . $category_id . '"'
    . ' AND lots.finish_date > CURDATE()'
    . ' ORDER BY lots.create_date DESC'
    . ' LIMIT ' . $lots_on_page
    . ' OFFSET ' . $offset;

    // Выполняем запрос.
    $category_lots = get_data_from_db($category_lots_sql, $db_connect);

    // Формируем заголовок.
    $category_page_title = 'Все лоты в категории «' . $category_page_name['name'] . '»';

    $content = include_template('category-template.php', [
        'categories' => $categories,
        'category_lots' => $category_lots,
        'category_page_title' => $category_page_title,
        'pagination_pages' => $pagination_pages
    ]);

} else {
    $category_page_title = 'В категории «' . $category_page_name['name'] . '» нет открытых лотов';

    $content = include_template('category-template.php', [
        'categories' => $categories,
        'category_page_title' => $category_page_title,
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
