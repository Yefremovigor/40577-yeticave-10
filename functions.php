<?php
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function set_price_format($input) {
    $price = ceil($input);
    $price = number_format($price, 0, '.', ' ') . ' ₽';
    return $price;
}

?>
