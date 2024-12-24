<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div class="popup">
    <div class="h2 text-center mb-28">Заказать звонок</div>
    <form
        class="popup__form js-form-validation js-ajax-form"
        action="/local/ajax/popups/callback.php"
        method="POST"
        autocomplete="off"
        novalidate="novalidate"
        data-layer="order_call"
    >
        <div class="form-block form-block--subs mb-32">
            <input
                    class="form-block__input js-validation js-mask-tel"
                    name="PHONE"
                    type="tel"
                    placeholder="+7(9__)-___-__-__"
                    required="required"
            />
            <label class="form-block__label">Ваш номер</label>
            <div class="form-block__label form-block__label--error">Номер введен не корректно</div>
            <button class="btn btn--black form-block__btn-focus d-none d-lg-inline-block" type="submit"><span class="d-none d-xl-inline-block">Заказать звонок</span>
                <svg class="icon icon-arrow-right d-xl-none">
                    <use xlink:href="./icons/symbol-defs.svg#icon-arrow-right"></use>
                </svg>
            </button>
        </div>
		<div class="form-block">
			<?if ($arParams['USE_CAPTCHA'] == 'Y'):?>
				<script src="https://www.google.com/recaptcha/api.js"></script>
				<div class="g-recaptcha" data-sitekey="<?=$arResult['RECAPTCHA_KEY']?>"></div><br>
			<?endif;?>
		</div>
        <div class="form-block">
            <label class="form-block__checkbox">
                <input class="js-validation" type="checkbox" name="cond" checked="checked" required="required" /><span class="p-md">Я соглашаюсь на обработку моих персональных данных в соответствии с <a href="/politika.php">Политикой конфиденциальности</a></span>
            </label>
        </div>
        <input type="hidden" name="form_submit" value="Y" />
        <button class="btn btn--black w-100 mt-32 d-lg-none" type="submit">Заказать звонок</button>
    </form>
    <script>
        initMask();
        formValidation();
    </script>
</div>
