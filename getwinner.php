<?php

// Формируем запрос к БД на получение лотов по которым закончились торги и нет подедителя.
$no_winer_lots_sql = 'SELECT lots.id, lots.title, lots.finish_date'
. ' FROM lots'
. ' WHERE lots.finish_date <= CURDATE() AND lots.winner_id IS NULL';

// Выполняем запрос к БД.
$no_winer_lots = get_data_from_db($no_winer_lots_sql, $db_connect);

// Проверяем есть ли такие лоты.
if (!empty($no_winer_lots)) {
    $i = 0;
    foreach ($no_winer_lots as $value) {
        // Формируем запрос на получение последний ставки по лоту.
        $new_winner_sql = 'SELECT bets.user_id , users.name, users.email'
        . ' FROM bets'
        . ' JOIN users ON bets.user_id = users.id'
        . ' WHERE bets.lot_id = "' . $value['id'] . '"'
        . ' ORDER BY bets.create_date DESC'
        . ' LIMIT 1';

        // Выполняем запрос.
        $new_winner = get_data_from_db($new_winner_sql, $db_connect, FALSE);
        // Проверяем есть ли результат.
        if (!empty($new_winner)) {
            // Собираем запрос на добавление победителя в таблицу.
            $new_winner_set_sql = 'UPDATE lots SET'
            . ' lots.winner_id = "' . $new_winner['user_id'] . '"'
            . ' WHERE lots.id = "' . $value['id'] . '"';

            // Выполняем запрос.
            $new_winner_set = mysqli_query($db_connect, $new_winner_set_sql);

            // Проверяем на ошибку.
            if (!$new_winner_set) {
                print('Ошибка в запросе : ' . mysqli_error($db_connect));
                die();
            }

            // Записываем победителя в список получателей.
            $need_letter[$i] = $new_winner;
            $need_letter[$i]['lot_id'] = $value['id'];
            $need_letter[$i]['title'] = $value['title'];
            $i++;
        }
    }

    // Удаляем i.
    unset($i);
}

// Проверяем нужно ли отправлять письма.
if (!empty($need_letter)) {
    $transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
        ->setUsername('keks@phpdemo.ru')
        ->setPassword('htmlacademy');

    $mailer = new Swift_Mailer($transport);

    foreach ($need_letter as $value) {
        $email = include_template('email.php', ['mail_info'=> $value]);

        $message = (new Swift_Message('Ваша ставка победила'))
            ->setFrom(['keks@phpdemo.ru' => 'YetiCave'])
            ->setTo([$value['email']])
            ->setBody($email, 'text/html');

        $mailer->send($message);
    }
}
