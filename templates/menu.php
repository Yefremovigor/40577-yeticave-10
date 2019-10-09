<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="category.php?category_id=<?=$category['id'] ?>"><?=$category['name'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
