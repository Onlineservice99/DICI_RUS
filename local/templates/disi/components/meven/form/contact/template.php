<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div class="h2 mb-32 mb-lg-44">Написать нам</div>
<form class="form form--contacts js-form-validation row no-gutters js-ajax-form"
      action="/local/ajax/popups/contact.php"
      method="POST"
      autocomplete="off"
      novalidate="novalidate"
      data-layer="ask">
    <div class="col-lg-5">
        <div class="form-block w-100">
            <input class="form-block__input js-validation" name="<?=$arResult["QUESTIONS"]["FIO"]["CODE"]?>" required>
            <label class="form-block__label"><?=$arResult["QUESTIONS"]["FIO"]["NAME"]?></label>
            <div class="form-block__label form-block__label--error">Введите ФИО</div>
        </div>
        <div class="form-block w-100">
            <input class="form-block__input js-validation js-mask-tel" name="<?=$arResult["QUESTIONS"]["PHONE"]["CODE"]?>" type="tel" required placeholder="+7(9__)-___-__-__">
            <label class="form-block__label"><?=$arResult["QUESTIONS"]["PHONE"]["NAME"]?></label>
            <div class="form-block__label form-block__label--error">Номер введен не корректно</div>
        </div>
        <div class="form-block w-100">
            <input class="form-block__input js-validation" name="<?=$arResult["QUESTIONS"]["EMAIL"]["CODE"]?>" type="email" required>
            <label class="form-block__label"><?=$arResult["QUESTIONS"]["EMAIL"]["NAME"]?></label>
            <div class="form-block__label form-block__label--error">E-mail введен не корректно</div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="form-block w-100">
            <textarea class="form-block__input js-validation" name="<?=$arResult["QUESTIONS"]["MESSAGES"]["CODE"]?>" required></textarea>
            <label class="form-block__label"><?=$arResult["QUESTIONS"]["MESSAGES"]["NAME"]?></label>
            <div class="form-block__label form-block__label--error">Задайте ваш вопрос</div>
        </div>
    </div>
    <div class="col-12 pt-lg-16">
        <input type="hidden" name="form_submit" value="Y" />
        <?if ($arParams['USE_CAPTCHA'] == 'Y'):?>
            <script src="https://www.google.com/recaptcha/api.js"></script>
            <div class="g-recaptcha" data-sitekey="<?=$arResult['RECAPTCHA_KEY']?>"></div><br>
        <?endif;?>
        <button class="btn btn--red px-80" type="submit">Отправить</button>
    </div>
</form>