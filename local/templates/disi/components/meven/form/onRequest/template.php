<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div class="popup">
    <div class="h2 text-center mb-28">Запрос наличия</div>
    <p>Заполните поля и отправьте форму, менеджер свяжется с вами и озвучит условия поставки товара</p>
    <form id="form-onRequest" class="popup__form js-form-validation js-ajax-form"
          action="/local/ajax/api/sale/onRequest.php"
          method="POST"
          autocomplete="off"
          novalidate="novalidate"
          data-success="<?=$arParams['SUCCESS_MESSAGE']?>">
        <input type="hidden" name="fields[PRODUCT_ID]" value="<?= htmlspecialchars($_REQUEST['id']) ?>">
        <div class="form-row form-row--no-radius">
            <div class="form-block w-100">
                <input class="form-block__input js-validation" name="fields[NAME]" required="required" />
                <label class="form-block__label">Имя<span>*</span></label>
                <div class="form-block__label form-block__label--error">Введите Имя</div>
            </div>
        </div>
        <div class="form-row form-row--no-radius">
            <div class="form-block w-100">
                <input
                        class="form-block__input js-validation js-mask-tel"
                        name="fields[PHONE]"
                        type="tel"
                        placeholder="+7(9__)-___-__-__"
                        required="required"
                />
                <label class="form-block__label">Телефон<span>*</span></label>
                <div class="form-block__label form-block__label--error">Номер введен некорректно</div>
            </div>
        </div>
        <br>
        <div class="form-row form-row--no-radius">
            <div class="form-block w-100">
                <p>Нужное количество:</p>
                <a class="compare-item__add" href="#" onclick="event.preventDefault()">
                    <div class="spin spin--card">
                        <input class="js-spin-count" name="fields[COUNT]" type="number" value="0" data-min="0" data-max="100" data-step="1">
                    </div>
                </a>
            </div>
        </div>
        <br>
        <div class="form-row form-row--no-radius">
            <div class="form-block w-100">
                <textarea class="form-block__input" name="fields[MESSAGE]"></textarea>
                <label class="form-block__label">Комментарий к заказу</label>
            </div>
        </div>
        <div class="form-block my-32">
            <label class="form-block__checkbox">
                <input class="js-validation" type="checkbox" name="fields[cond]" checked="checked" required="required" /><span class="p-md">Я соглашаюсь на обработку моих персональных данных в соответствии с <a href="/politika.php">Политикой конфиденциальности</a></span>
            </label>
        </div>
        <button class="btn btn--black btn--mw" type="submit"> <?= ($btnSubmitText = COption::GetOptionString('cosmos.settings', 'onRequestPopupBtnName')) ? $btnSubmitText : 'Запросить наличие' ?></button>
    </form>

    <script>
        initMask();
        formValidation();
        $(document).ready(function(){
            $('.js-spin-count').TouchSpin({
                buttondown_class: "spin__btn",
                buttondown_txt: '<svg class="icon icon-minus">\n' +
                    '                                                   <use xlink:href="/local/templates/disi/assets/icons/symbol-defs.svg#icon-minus"></use>\n' +
                    '                                                </svg>',
                buttonup_class: "spin__btn",
                buttonup_txt: '<svg class="icon icon-plus">\n' +
                    '                                                    <use xlink:href="/local/templates/disi/assets/icons/symbol-defs.svg#icon-plus"></use>\n' +
                    '                                                </svg>'
            });
        });

    </script>
</div>
