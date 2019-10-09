<?php
function set_price_format($input, $show_currency = TRUE) {
    $price = ceil($input);
    $price = number_format($price, 0, '.', ' ');
    if ($show_currency) {
        $price .= ' ₽';
    }
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

function get_post_val($name) {
    return $_POST[$name] ?? '';
}

function is_length_invalid($value, $min, $max) {
    $len = strlen($value);
    $result = FALSE;
    if ($len < $min or $len > $max) {
        $result = TRUE;
    }

    return $result;
}

function count_rows_in_db($query, $connect) {
    $response = mysqli_query($connect, $query);
    if (!$response) {
        die('Ошибка в sql запросе: ' . mysqli_error($connect));
    }
    $count_rows = mysqli_num_rows($response);

    return $count_rows;
}

function show_when_was($date, $is_date_in_unix = TRUE) {
    if (!$is_date_in_unix) {
        $date = strtotime($date);
    }

    $diff = time() - $date;

    if ($diff > 86400) {
        return date('d.m.y в H:i', $date);
    } elseif ($diff > 7200) {
        $hour = intdiv($diff, 3600);
        return $hour . ' ' . get_noun_plural_form($hour, 'час', 'часа', 'часов') . ' назад';
    } elseif ($diff > 3600) {
        return 'Час назад';
    } elseif ($diff > 60) {
        $minute = intdiv($diff, 60);
        return $minute . ' ' . get_noun_plural_form($minute, 'минута', 'минуты', 'минут') . ' назад';
    } else {
        return 'Только что';
    }

    // $human_date
    return $diff;
}
