<?php require_once('menu.php') ?>
<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php if (!empty($my_bets)): ?>
            <?php foreach ($my_bets as $my_bet): ?>
            <tr class="rates__item
                <?=($my_bet['lot_status'] == 'win') ? 'rates__item--win' : '' ?>
                <?=($my_bet['lot_status'] == 'finish') ? 'rates__item--end' : '' ?>
                ">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?=$my_bet['img'] ?>" width="54" height="40" alt="<?=htmlspecialchars($my_bet['title'], ENT_QUOTES) ?>">
                    </div>
                    <div>
                    <h3 class="rates__title"><a href="/lot.php?lot_id=<?=$my_bet['id'] ?>"><?=htmlspecialchars($my_bet['title'], ENT_QUOTES) ?></a></h3>
                    <?php if ($my_bet['lot_status'] == 'win'): ?>
                        <p><?=$my_bet['contact'] ?></p>
                    <?php endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?=htmlspecialchars($my_bet['category'], ENT_QUOTES) ?>
                </td>
                <?php $lot_timer = get_dt_range($my_bet['finish_date']) ?>
                <td class="rates__timer">
                    <div class="timer
                        <?=($my_bet['lot_status'] == 'win') ? 'timer--win' : '' ?>
                        <?=($my_bet['lot_status'] == 'finish') ? 'timer--end' : '' ?>
                        <?=($my_bet['lot_status'] == 'is_finish') ? 'timer--finishing' : '' ?>
                        ">
                        <?php if ($my_bet['lot_status'] == 'win'): ?>
                            Ставка выиграла
                        <?php elseif ($my_bet['lot_status'] == 'finish'): ?>
                            Торги окончены
                        <?php else: ?>
                            <?=$lot_timer['hour'].':'.$lot_timer['minute'] ?>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__price">
                    <?=set_price_format($my_bet['bet']) ?>
                </td>
                <td class="rates__time">
                    <?=show_when_was($my_bet['create_date'], FALSE) ?>
                </td>
            </tr>
            <?php endforeach ?>
        <?php endif ?>
    </table>
</section>
