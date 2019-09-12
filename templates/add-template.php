<?php require_once('menu.php') ?>
<form class="form form--add-lot container <?=(isset($errors)) ? 'form--invalid' : '' ?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?=(isset($errors['lot-name'])) ? 'form__item--invalid' : '' ?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=getPostVal('lot-name') ?>" required>
            <span class="form__error">Введите наименование лота</span>
        </div>
        <div class="form__item <?=(isset($errors['category'])) ? 'form__item--invalid' : '' ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category" required>
                <?=(empty($_POST['category']) || isset($errors['category'])) ? '<option value="" selected>Выберите категорию</option>' : '' ?>
                <?php foreach ($categories as $category): ?>
                    <option value="<?=$category['id'] ?>" <?=(isset($_POST['category']) && $category['id'] == intval($_POST['category'])) ? 'selected' : '' ?> > <?=$category['name'] ?></option>
                <?php endforeach ?>
            </select>
            <span class="form__error">Выберите категорию</span>
        </div>
    </div>
    <div class="form__item form__item--wide <?=(isset($errors['message'])) ? 'form__item--invalid' : '' ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота" required><?=getPostVal('message') ?></textarea>
        <span class="form__error">Напишите описание лота</span>
    </div>
    <div class="form__item form__item--file <?=(isset($errors['lot-img'])) ? 'form__item--invalid' : '' ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="lot-img" id="lot-img" value="" required>
            <label for="lot-img">
                Добавить
            </label>
            <span class="form__error">Добасьте фотографию лота</span>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?=(isset($errors['lot-rate'])) ? 'form__item--invalid' : '' ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=getPostVal('lot-rate') ?>" required>
            <span class="form__error">Введите начальную цену</span>
        </div>
        <div class="form__item form__item--small <?=(isset($errors['lot-step'])) ? 'form__item--invalid' : '' ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=getPostVal('lot-step') ?>" required>
            <span class="form__error">Введите шаг ставки</span>
        </div>
        <div class="form__item <?=(isset($errors['lot-date'])) ? 'form__item--invalid' : '' ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=getPostVal('lot-date') ?>" required>
            <span class="form__error">Введите дату завершения торгов</span>
        </div>
    </div>
    <span class="form__error form__error--bottom">
        Пожалуйста, исправьте ошибки в форме:
        <?php if (isset($errors)): ?>
            <ul>
                <?php foreach ($errors as $error):?>
                    <li><?=$error?></li>
                <?php endforeach;?>
            </ul>
        <?php endif ?>
    </span>
    <button type="submit" class="button">Добавить лот</button>
</form>
