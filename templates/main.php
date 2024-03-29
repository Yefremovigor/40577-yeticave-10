<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?=$category['alias'] ?>">
                <a class="promo__link" href="pages/all-lots.html"><?=$category['name'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($ads as $item): ?>
            <?php $lot_timer = get_dt_range($item['auction_end_date']) ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=$item['img'] ?>" width="350" height="260" alt="<?=htmlspecialchars($item['title'], ENT_QUOTES) ?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=$item['category'] ?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?=$item['id'] ?>"><?=htmlspecialchars($item['title'], ENT_QUOTES) ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=set_price_format($item['price']) ?></span>
                        </div>
                        <div class="lot__timer timer <?=is_ad_finishing($lot_timer['hour']) ?>">
                            <?=$lot_timer['hour'].':'.$lot_timer['minute'] ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
