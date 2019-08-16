<?php
function set_price_format($input) {
    $price = ceil($input);
    $price = number_format($price, 0, '.', ' ') . ' ₽';
    return $price;
}
