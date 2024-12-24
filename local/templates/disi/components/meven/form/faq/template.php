<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
?>

<div class="popup">
    <div class="h2 text-center mb-28 mx-sm-n56">Задать вопрос</div>
    <form
        class="popup__form js-form-validation js-ajax-form"
        action="/local/ajax/popups/faq.php"
        method="POST"
        autocomplete="off"
        novalidate="novalidate"
        data-success="<?=$arParams['SUCCESS_MESSAGE']?>"
    >
        <div class="form-row form-row--radius-top">
            <div class="form-block w-100">
                <input class="form-block__input js-validation" name="NAME" required="required" />
                <label class="form-block__label">ФИО<span>*</span></label>
                <div class="form-block__label form-block__label--error">Введите ФИО</div>
            </div>
        </div>
        <div class="form-row form-row--no-radius-2">
            <div class="form-block">
                <input class="form-block__input js-validation js-mask-tel" name="PHONE" type="tel" required="required"
                       placeholder="+7(9__)-___-__-__" />
                <label class="form-block__label">Ваш телефон<span>*</span></label>
                <div class="form-block__label form-block__label--error">Номер введен не корректно</div>
            </div>
            <div class="form-block">
                <input class="form-block__input js-validation" name="EMAIL" type="email" required="required" />
                <label class="form-block__label">e-mail<span>*</span></label>
                <div class="form-block__label form-block__label--error">E-mail введен не корректно</div>
            </div>
        </div>
        <div class="form-row form-row--radius-bottom">
            <div class="form-block w-100">
                <textarea class="form-block__input js-validation" name="MESSAGE" required="required"></textarea>
                <label class="form-block__label">Ваш вопрос<span>*</span></label>
                <div class="form-block__label form-block__label--error">Задайте ваш вопрос</div>
            </div>
        </div>
        <div class="form-block my-32">
            <label class="form-block__checkbox">
                <input class="js-validation" type="checkbox" name="cond" checked="checked" required="required" /><span class="p-md">Я соглашаюсь на обработку моих персональных данных в соответствии с <a href="/politika.php">Политикой конфиденциальности</a></span>
            </label>
        </div>
        <?if ($arParams['USE_CAPTCHA'] == 'Y'):?>
            <script src="https://www.google.com/recaptcha/api.js"></script>
            <div class="g-recaptcha" data-sitekey="<?=$arResult['RECAPTCHA_KEY']?>"></div><br>
        <?endif;?>
        <button class="btn btn--black btn--mw" type="submit">Отправить</button>
        <input type="hidden" name="form_submit" value="Y" />
        <input type="hidden" name="ELEMENT" value="<?=(int) $request->get('id')?>" />
    </form>
    <script>
        initMask();
        formValidation();
    </script>
</div>