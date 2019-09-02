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

function get_data_from_db($query, $connect, $is_multidimensional = TRUE) {
    $response = mysqli_query($connect, $query);
    if (!$response) {
        die('Ошибка в sql запросе: ' . mysqli_error($connect));
    }
    $converted_array = $is_multidimensional ? mysqli_fetch_all($response, MYSQLI_ASSOC) : mysqli_fetch_assoc($response);

    return $converted_array;
}
