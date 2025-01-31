<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
?>
<div class="popup">
    <div class="h2 text-center mb-28">Отправить корзину</div>
    <form
        class="popup__form js-form-validation js-send-basket js-ajax-form"
        action="/local/ajax/api/basket/send.php"
        method="POST"
        autocomplete="off"
        novalidate="novalidate"
        data-success="Корзина сформирована и отправлена на Ваш email"
    >
        <div class="form-block form-block--subs mb-32">
            <input
                class="form-block__input js-validation"
                name="email"
                type="email"
                placeholder=""
                required="required"
            />
            <button class="btn btn--black form-block__btn-focus d-none d-lg-inline-block" type="submit"><span class="d-none d-xl-inline-block">Отправить</span>
                <svg class="icon icon-arrow-right d-xl-none">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/local/templates/assets/icons/symbol-defs.svg#icon-arrow-right"></use>
                </svg>
            </button>
        </div>
        <div class="form-block">
            <label class="form-block__checkbox">
                <input class="js-validation" type="checkbox" name="cond" checked="checked" required="required" /><span class="p-md">Я соглашаюсь на обработку моих персональных данных в соответствии с <a href="/politika.pdf">Политикой конфиденциальности</a></span>
            </label>
        </div>
        <input type="hidden" name="form_submit" value="Y" />
        <textarea class="d-none" name="basket-items"></textarea>
        <button class="btn btn--black w-100 mt-32 d-lg-none" type="submit">Отправить</button>
    </form>
    <script>
        initMask();
        formValidation();
    </script>
</div>

