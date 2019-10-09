<?php require_once('menu.php') ?>
<div class="container">
    <section class="lots">
        <h2><?=$category_page_title ?></h2>
        <?php if (isset($category_lots)): ?>
            <ul class="lots__list">
                <?php foreach ($category_lots as $item): ?>
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
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    </section>
    <?php if (isset($pagination_pages) && count($pagination_pages) > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a href="/category.php?category_id=<?=intval($_GET['category_id']) ?>&page=<?=($search_page > 1) ? $search_page -1 : $search_page ?>">Назад</a></li>
            <?php foreach ($pagination_pages as $pages): ?>
            <li class="pagination-item <?=($pages == $search_page) ? 'pagination-item-active' : '' ?>"><a href="/category.php?category_id=<?=intval($_GET['category_id']) ?>&page=<?=$pages ?>"><?=$pages ?></a></li>
            <?php endforeach ?>
            <li class="pagination-item pagination-item-next"><a href="/category.php?category_id=<?=intval($_GET['category_id']) ?>&page=<?=($search_page < count($pagination_pages)) ? $search_page +1 : $search_page ?>">Вперед</a></li>
        </ul>
    <?php endif ?>
</div>
