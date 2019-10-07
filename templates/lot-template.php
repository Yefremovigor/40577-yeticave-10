<?php require_once('menu.php') ?>
<section class="lot-item container">
    <h2><?=htmlspecialchars($lot['title'], ENT_QUOTES) ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="../<?=$lot['img'] ?>" width="730" height="548" alt="<?=$lot['title'] ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?=$lot['category'] ?></span></p>
            <p class="lot-item__description">
                <?=htmlspecialchars($lot['description'], ENT_QUOTES) ?>
            </p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <?php $lot_timer = get_dt_range($lot['finish_date']) ?>
                <div class="lot-item__timer timer <?=is_ad_finishing($lot_timer['hour']) ?>">
                    <?=$lot_timer['hour'].':'.$lot_timer['minute'] ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?=set_price_format($lot['price'], FALSE) ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?=set_price_format($lot['next_bet']) ?></span>
                    </div>
                </div>
                <?php if ($bit_form_toggle): ?>
                    <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="<?=set_price_format($lot['next_bet'], FALSE) ?>">
                            <span class="form__error">Введите наименование лота</span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                <?php endif ?>
            </div>
            <div class="history">
                <?php if(!empty($bets)): ?>
                    <h3>История ставок (<?=count($bets) ?>)</h3>
                    <?php foreach ($bets as $bet):?>
                    <table class="history__list">
                        <tr class="history__item">
                            <td class="history__name"><?=$bet['name'] ?></td>
                            <td class="history__price"><?=$bet['bet'] ?></td>
                            <td class="history__time"><?=show_when_was($bet['data'], FALSE) ?></td>
                        </tr>
                    </table>
                    <?php endforeach ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>
