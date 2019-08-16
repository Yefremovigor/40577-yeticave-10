<?php
function set_price_format($input) {
    $price = ceil($input);
    $price = number_format($price, 0, '.', ' ') . ' ₽';
    return $price;
}

function get_dt_range($date) {
    $dt_interval = strtotime($date) - time();
    $hour_to_date = floor($dt_interval/3600);
    $minute_to_date = floor($dt_interval/60%60);
    $time_to_date = [
        'hour' => str_pad($hour_to_date, 2, "0", STR_PAD_LEFT),
        'minute' => str_pad($minute_to_date, 2, "0", STR_PAD_LEFT)
    ];
    return $time_to_date;
}

function is_ad_finishing($hours_to_finishing) {
    $set_class = '';
    if ($hours_to_finishing < 1) {
        $set_class = 'timer--finishing';
    }
    return $set_class;
}
