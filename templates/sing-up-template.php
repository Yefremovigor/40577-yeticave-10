<?php require_once('menu.php') ?>
<form class="form container <?=(isset($errors)) ? 'form--invalid' : '' ?>" action="sing-up.php" method="post" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?=(isset($errors['email'])) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=get_post_val('email') ?>">
        <span class="form__error">Введите e-mail</span>
    </div>
    <div class="form__item <?=(isset($errors['password'])) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error">Введите пароль</span>
    </div>
    <div class="form__item <?=(isset($errors['name'])) ? 'form__item--invalid' : '' ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=get_post_val('name') ?>">
        <span class="form__error">Введите имя</span>
    </div>
    <div class="form__item <?=(isset($errors['message'])) ? 'form__item--invalid' : '' ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?=get_post_val('message') ?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
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
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
