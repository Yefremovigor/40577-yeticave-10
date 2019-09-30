<?php
require_once('init.php');

// Собираем запрос для получения саиска категорий.
$categories_sql = 'SELECT * FROM categories';

// Выполняем запрос и конвертируем данные в двумерный массив.
$categories = get_data_from_db($categories_sql, $db_connect);

// Определяем переменную для хранения запроса и записываем его, если он есть.
$search = $_GET['search'] ?? '';

// Проверяем запрошана ли страница через GET и переданны данные для поиска
if ($_SERVER['REQUEST_METHOD'] == 'GET' AND !empty($search)) {
    // Формируем запрос на колличество совпадений в БД.
    $search_count_sql = 'SELECT id FROM lots WHERE MATCH(title,description) AGAINST("'
    . mysqli_real_escape_string($db_connect, trim($search)) . '")';

    // Выполняем запрос на подсчет.
    $search_count = count_rows_in_db($search_count_sql, $db_connect);

    // Проверяем есть ли результаты.
    if ($search_count) {
        // Задаем количество лотов на странице.
        $lots_on_page = 9;
        // Проверяем сколько страниц нцжно в пагинации.
        $pagination_size = ceil($search_count / $lots_on_page);

        // Генерируем масив номеров страниц для пагинации.
        $pagination_pages = range(1, $pagination_size);

        // Определяем страницу поисковых резкльтатов
        $search_page = intval($_GET['page'] ?? 1);

        // Определяем смещение.
        $offset = ($search_page - 1) * $lots_on_page;

        // Формируем запрос.
        $search_sql = 'SELECT lots.id, lots.title, categories.name AS category, lots.start_price AS price,'
        . ' lots.img, lots.finish_date AS auction_end_date FROM lots'
        . ' JOIN categories ON lots.category_id = categories.id'
        . ' WHERE MATCH(lots.title, lots.description)'
        . ' AGAINST("' . mysqli_real_escape_string($db_connect, trim($search)) . '")'
        . ' AND lots.finish_date > CURDATE()'
        . ' ORDER BY lots.create_date DESC'
        . ' LIMIT ' . $lots_on_page
        . ' OFFSET ' . $offset;

        // Выполняем запрос.
        $search_result = get_data_from_db($search_sql, $db_connect);

        $search = 'Результаты поиска по запросу «' . htmlspecialchars($search, ENT_QUOTES) . '»';

        $content = include_template('search-template.php', [
            'categories' => $categories,
            'search' => $search,
            'lots' => $search_result,
            'pagination_pages' => $pagination_pages,
            'search_page' => $search_page
        ]);

    } else {
        $content = include_template('search-template.php', [
            'categories' => $categories,
            'search' => 'Ничего не найдено по вашему запросу'
        ]);
    }

} else {
    $content = include_template('search-template.php', [
        'categories' => $categories,
        'search' => 'Введите запрос с строку поиска'
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
