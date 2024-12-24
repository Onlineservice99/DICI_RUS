<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<form class="footer__form js-form-validation js-ajax-form"
      action="/local/ajax/popups/get_discount.php"
      method="POST"
      autocomplete="off"
      novalidate="novalidate"
>
    <div class="form-block form-block--subs">
        <input class="form-block__input js-validation" name="<?=$arResult["QUESTIONS"]["EMAIL"]["CODE"]?>" type="email" placeholder="example@mail.com" required/>
        <label class="form-block__label">e-mail*</label>
        <div class="form-block__label form-block__label--error">e-mail введен не корректно</div>
        <input type="hidden" name="form_submit" value="Y" />
        <button class="btn btn--black form-block__btn-focus" type="submit"> <span class="d-none d-xl-inline-block">Получить скидку</span>
            <svg class="icon icon-arrow-right d-xl-none">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-arrow-right"></use>
            </svg>
        </button>
    </div>
</form>