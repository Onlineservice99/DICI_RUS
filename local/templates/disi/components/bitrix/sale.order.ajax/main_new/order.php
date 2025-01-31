<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
global $USER;

use electroset1\SaleOrder;

$asset = \Bitrix\Main\Page\Asset::getInstance(); ?>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js"></script>

<?
$asset->addJs($templateFolder . '/dist/script.bundle.js');
?>
<style>
    body > main > section > article > div.section__overlay.mb-32.mb-lg-80.pt-lg-56 > div > div{
        @media(min-width: 1200px){
            display: flex;
        }
    }
</style>

<?

$needDeliveryServices = implode(",", $arParams["NEED_DELIVERY"]);
$selfDeliveryServices = implode(",", $arParams["SELF_DELIVERY"]);

if (array_key_exists('DEFAULT_DELIVERY', $arParams) && is_array($arParams["DEFAULT_DELIVERY"])) {
    $defaultDelivery = implode(",", $arParams["DEFAULT_DELIVERY"]);
} else {
    $defaultDelivery = '';
}

?>
<div class="section__overlay mb-32 mb-lg-80 pt-lg-48">
    <div class="container">
        <div class="basket basket--order mb-40 mb-lg-80">
            <div class="basket__main mb-32 mb-lg-120">
                <?php if (!$USER->IsAuthorized()): ?>
                    <div class="bg-light rounded-sm px-16 pt-16 mb-32 mb-lg-56">
                        <div class="row align-items-center">
                            <div class="col-xl text-center pb-16">Я зарегистрированный пользователь</div>
                            <div class="col-xl-auto pb-16 mr-xl-88">
                                <a class="btn btn--black px-xl-112 w-100" href="/local/ajax/popups/auth.php"
                                   data-fancybox data-touch="false" data-type="ajax"
                                   data-close-existing="true">Войти</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <form class="form" id="soa-form" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <?= bitrix_sessid_post(); ?>
                    <input type="hidden" name="BUYER_STORE" value="0">
                    <input type="hidden" name="soa-action" value="saveOrderAjax">
                    <div class="d-lg-flex mb-32 mb-lg-40">
                        <ul class="nav nav-pills">
                            <input type="hidden" class="js-refresh-elem pt_id" name="PERSON_TYPE"
                                   value="<?= $arResult["USER_VALS"]["PERSON_TYPE_ID"] ?>">
                            <? foreach ($arResult["PERSON_TYPE"] as $arPT) { ?>

                                <li class="nav-item pt_tabs">
                                    <a class="nav-link<? if ($arResult["USER_VALS"]["PERSON_TYPE_ID"] > 0 && $arResult["USER_VALS"]["PERSON_TYPE_ID"] == $arPT["ID"]) { ?> active<? } ?>"
                                       href="#pttab-<?= $arPT["ID"] ?>" data-ptid="<?= $arPT["ID"] ?>"
                                       data-toggle="tab"><?= $arPT["NAME"] ?></a>
                                </li>
                            <? } ?>
                        </ul>
                    </div>
                    <h3 class="mb-32 mb-lg-48">Товары в заказе</h3>
                    <div class="basket__main--list">
                        <div class="basket-item basket-item--head d-none d-xl-flex">
                            <div class="basket-item__num">Код</div>
                            <div class="basket-item__name-wrap">Бренд и наименование</div>
                            <div class="basket-item__price-wrap mr-0">
                                <div class="basket-item__price text-xl-right">Цена</div>
                                <div class="basket-item__count">Количество</div>
                                <div class="basket-item__price basket-item__price--total">Стоимость</div>
                            </div>
                        </div>
                        <div data-items>
                        </div>
                        <? if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "order_text_stores"))) { ?>
                            <div class="backet__main--notice"><?= \Bitrix\Main\Config\Option::get("meven.info", "order_text_stores") ?></div>
                        <? } ?>
                    </div>
                    <?php
                    $aCategories = array();
                    $aPaySystems = array();

                    foreach ($arResult['BASKET_ITEMS'] as $item) {
                        $aCategories[$item["PRODUCT_ID"]] = SaleOrder::getCategoryFromUrl($item["PRODUCT_ID"]);
                    }

                    foreach ($arResult['PAY_SYSTEM'] as $paySystem) {
                        $aPaySystems[$paySystem['ID']] = $paySystem['NAME'];
                    }

                    $aPaySystems = json_encode($aPaySystems, JSON_UNESCAPED_UNICODE);
                    $aCategories = json_encode($aCategories, JSON_UNESCAPED_UNICODE);
                    ?>
                    <script>

                        function getAllOrderElements() {
                            var aOrderElements = new Array();
                            var id;
                            var orderItemName;
                            var orderItemsQuantity;
                            var paySystemId = $('[data-payments]').find('.js-refresh-elem:checked').val();

                            myElement = document.querySelectorAll('.basket-item ');

                            for (var i = 1; i < myElement.length; i++) {
                                id = myElement[i].getAttribute('data-element-basket');
                                orderItemName = $(myElement[i]).find('.basket-item__title')[0].textContent;
                                orderItemsQuantity = $(myElement[i]).find('.basket-item__count').text().split(' ')[1];
                                aOrderElements.push({
                                    'name': orderItemName,
                                    'id': id,
                                    'category': <?= $aCategories ?>[id],
                                    'quantity': orderItemsQuantity,
                                });
                            }

                            window.dataLayer = window.dataLayer || [];
                            window.dataLayer.push({
                                'ecommerce': {
                                    'checkout': {
                                        'actionField': {
                                            'step': 2, // НОМЕР ШАГА
                                            'option': <?= $aPaySystems ?>[paySystemId]
                                        },
                                        'products': [aOrderElements]
                                    }
                                },
                                'event': 'ecommerceCheckout'
                            });
                        }

                    </script>
                    <div class="border-top mb-40"></div>

                    <? /*?><h3 class="mb-32">Доставка</h3><?*/ ?>

                    <div class="form__block form__block--f1">


                        <?
                        $APPLICATION->IncludeComponent(
                            "bitrix:sale.location.selector.search",
                            "custom_new",
                            array(
                                "CODE" => $arResult["USER_VALS"]["ORDER_PROP"][$arResult["PROPS"][1]["LOCATION"]],
                                "ID_PROP" => $arResult["PROPS"][$arResult["USER_VALS"]["PERSON_TYPE_ID"]]["LOCATION"],
                                "COMPONENT_TEMPLATE" => "custom_new",
                                "ID" => "",
                                "INPUT_NAME" => "LOCATION",
                                "PROVIDE_LINK_BY" => "code",
                                "JS_CONTROL_GLOBAL_ID" => "",
                                "JS_CALLBACK" => "",
                                "FILTER_BY_SITE" => "N",
                                "SHOW_DEFAULT_LOCATIONS" => "N",
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "36000000"
                            ),
                            false
                        );
                        ?>
                        <input type="hidden" class="kladr_id" value=""/>
                    </div>
                    <div class="delivery_block">
                        <div class="d-lg-flex mb-32 mb-lg-40 delivery_tabs">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active deliveryTab" data-os_tab="1" href="#deliverytab-1">Нужна
                                        доставка</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link deliveryTab" href="#deliverytab-2" data-os_tab="2" >Самовывоз</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div class="form__block form__block--delivery" id="deliveryItems" data-delivery></div>
                    <?php if ( !is_null($arResult['ORDER_PROP']['USER_PROFILES']) && count($arResult['ORDER_PROP']['USER_PROFILES']) > 0 && 1 == 2): ?>
                        <div class="tab-pane fade show active" id="tab-delivery-1">
                            <h4 class="mb-20">Адрес доставки</h4>
                            <div class="select mb-16">
                                <div class="select__label">Ваши адреса</div>
                                <select class="js-select-default js-refresh-elem" name="PROFILE_ID">
                                    <?php foreach ($arResult['ORDER_PROP']['USER_PROFILES'] as $prof): ?>
                                        <option value="<?= $prof['ID'] ?>"><?= $prof['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <a class="btn btn--border btn--sm btn--toggle-plus mb-40" href="#add-address"
                               data-toggle="collapse">Добавить новый адрес</a>
                            <div class="collapse" id="add-add1ress">
                                <div class="form__block form__block--tmp mb-16">
                                    <div class="form-block">
                                        <input type="text" name="ORDER_PROP_<?= $arResult['PROPS'][1]['STREET'] ?>"
                                               value="" style="display: none"/>
                                        <input class="form-block__input" data-prop-street
                                               name="ORDER_PROP_<?= $arResult['PROPS'][1]['STREET'] ?>">
                                        <label class="form-block__label">Улица*</label>
                                        <div class="form-block__label form-block__label--error">ошибка</div>
                                    </div>
                                    <div class="form-block">
                                        <input class="form-block__input" data-prop-house
                                               id="input-house"
                                               name="ORDER_PROP_<?= $arResult['PROPS'][1]['INDEX'] ?>">
                                        <label class="form-block__label">Индекс</label>
                                        <div class="form-block__label form-block__label--error">ошибка</div>
                                    </div>
                                    <div class="form-block">
                                        <input class="form-block__input" data-prop-house
                                               autocomplete="house"
                                               name="ORDER_PROP_<?= $arResult['PROPS'][1]['HOUSE'] ?>">
                                        <label class="form-block__label">Дом</label>
                                        <div class="form-block__label form-block__label--error">ошибка</div>
                                    </div>
                                    <div class="form-block">
                                        <input class="form-block__input" data-prop-corp
                                               autocomplete="corpus"
                                               name="ORDER_PROP_<?= $arResult['PROPS'][1]['CORPUS'] ?>">
                                        <label class="form-block__label">Корпус</label>
                                        <div class="form-block__label form-block__label--error">ошибка</div>
                                    </div>
                                    <div class="form-block">
                                        <input class="form-block__input" data-prop-entrance
                                               autocomplete="off"
                                               name="ORDER_PROP_<?= $arResult['PROPS'][1]['ENTRANCE'] ?>">
                                        <label class="form-block__label">Подъезд</label>
                                        <div class="form-block__label form-block__label--error">ошибка</div>
                                    </div>
                                    <div class="form-block">
                                        <input class="form-block__input" data-prop-ofis
                                               autocomplete="off"
                                               name="ORDER_PROP_<?= $arResult['PROPS'][1]['OFIS'] ?>">
                                        <label class="form-block__label">Офис/квартира</label>
                                        <div class="form-block__label form-block__label--error">ошибка</div>
                                    </div>
                                </div>
                                <a class="btn btn--border btn--sm mb-40" href="#" data-toggle="collapse">
                                    <svg class="icon icon-success icon-16 text-red mr-8">
                                        <use xlink:href="./icons/symbol-defs.svg#icon-success"></use>
                                    </svg>
                                    Сохранить в мои адреса
                                </a>
                            </div>
                        </div>
                    <?php else: ?>

                    <?php endif; ?>

                    <?php /*foreach ($arResult['JS_DATA']['DELIVERY'] as $delivery):
                            echo "<pre>";
                                print_r($delivery);
                            echo "</pre>";
                            */ ?><!--
                            <div class="tab-pane mb-40 fade show" id="tab-delivery-<?php /*=$delivery['ID']*/ ?>">
                                <?php /*=$delivery['DESCRIPTION']*/ ?>
                            </div>
                        --><?php /*endforeach;*/ ?>
            </div>
            <div class="border-top mb-40"></div>
            <div>
                <h4 class="mb-40 group_name"></h4>
                <div class="form__block form__block--contacts" data-properies="7"></div>
            </div>
            <div>
                <h4 class="mb-40 group_name">Контактные данные</h4>

                <div class="form__block form__block--contacts" data-properies="4">
                    <div class="form-block">
                        <input class="form-block__input"
                               name="ORDER_PROP_<?= $arResult['PROPS'][1]['NAME'] ?>"
                               value="<?= $arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['NAME']] ?>"
                        >
                        <label class="form-block__label">ФИО<span>*</span></label>
                        <div class="form-block__label form-block__label--error">Введите ФИО</div>
                    </div>
                    <div class="form-block">
                        <input class="form-block__input js-mask-tel"
                               name="ORDER_PROP_<?= $arResult['PROPS'][1]['PHONE'] ?>"
                               type="tel"
                               placeholder="+7(9__)-___-__-__"
                               value="<?= $arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['PHONE']] ?>"
                        >
                        <label class="form-block__label">Ваш телефон<span>*</span></label>
                        <div class="form-block__label form-block__label--error">Номер введен не корректно</div>
                    </div>
                    <div class="form-block">
                        <input class="form-block__input"
                               name="ORDER_PROP_<?= $arResult['PROPS'][1]['EMAIL'] ?>"
                               value="<?= $arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['EMAIL']] ?>"
                               type="email">
                        <label class="form-block__label">e-mail<span>*</span></label>
                        <div class="form-block__label form-block__label--error">E-mail введен не корректно</div>
                    </div>
                </div>
            </div>
            <div class="show active">
                <h4 class="mb-20 group_name">Адреc доставки</h4>
                <div class="form__block form__block--address" data-properies="1">

                </div>
            </div>
            <? /*php
                        $val = $arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['UR']];
                        ?>
                        <div class="form-block mb-40">
                            <label class="form-block__checkbox form-block__checkbox--dot" data-toggle="collapse" data-target="#legal">
                                <input
                                    type="checkbox" <?=($val == 'Y' ? 'checked' : '')?>
                                    value="<?=$val?>"
                                    name="ORDER_PROP_<?=$arResult['PROPS'][1]['UR']?>"
                                >
                                <span>Я юридидическое лицо</span>
                            </label>
                        </div>
                        <div class="collapse <?=($val == 'Y' ? 'show' : '')?>" id="legal">
                            <h4 class="mb-24">Реквизиты Юридического лица</h4>
                            <div class="form__block form__block--legal">
                                <div class="form-block">
                                    <input class="form-block__input" name="ORDER_PROP_<?=$arResult['PROPS'][1]['COMPANY']?>" value="<?=$arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['COMPANY']]?>">
                                    <label class="form-block__label">Наименование организации</label>
                                </div>
                                <div class="form-block">
                                    <input class="form-block__input" name="ORDER_PROP_<?=$arResult['PROPS'][1]['LEGAL_ADDRESS']?>" value="<?=$arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['LEGAL_ADDRESS']]?>">
                                    <label class="form-block__label">Юр. адрес</label>
                                </div>
                                <div class="form-block">
                                    <input class="form-block__input" name="ORDER_PROP_<?=$arResult['PROPS'][1]['INN']?>" value="<?=$arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['INN']]?>">
                                    <label class="form-block__label">ИНН</label>
                                </div>
                                <div class="form-block">
                                    <input class="form-block__input" name="ORDER_PROP_<?=$arResult['PROPS'][1]['KPP']?>" value="<?=$arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['KPP']]?>">
                                    <label class="form-block__label">КПП</label>
                                </div>
                                <div class="form-block">
                                    <input class="form-block__input" name="ORDER_PROP_<?=$arResult['PROPS'][1]['CHECKING_ACC']?>" value="<?=$arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['CHECKING_ACC']]?>">
                                    <label class="form-block__label">Р/с</label>
                                </div>
                                <div class="form-block">
                                    <input class="form-block__input" name="ORDER_PROP_<?=$arResult['PROPS'][1]['COR_ACC']?>" value="<?=$arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['COR_ACC']]?>">
                                    <label class="form-block__label">К/с</label>
                                </div>
                                <div class="form-block">
                                    <input class="form-block__input" name="ORDER_PROP_<?=$arResult['PROPS'][1]['BANK']?>" value="<?=$arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['BANK']]?>">
                                    <label class="form-block__label">В банке...</label>
                                </div>
                            </div>
                            <div class="mb-32">Или загрузите файл с реквизитами. <span class="text-gray p-md">Поддерживаются форматы: .doc, .docx, .pdf</span></div>
                            <div class="form-block form-block--file mb-40">
                                <label class="mb-0 mr-40">
                                    <input type="file" name="ORDER_PROP_<?=$arResult['PROPS'][1]['REQUISITES']?>"
                                           accept=".doc, .docx, .pdf"
                                           onchange="fileSet(this)">
                                    <span class="btn btn--border btn--sm">
                                        <svg class="icon icon-attachment icon-16 text-red mr-8">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-attachment"></use>
                                        </svg>Загрузить файл
                                    </span>
                                </label>
                                <div class="form-block__file-name"> <span class="text-gray mr-16">Прикреплено:</span>
                                    <svg class="icon icon-file mr-8">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-file"></use>
                                    </svg>
                                    <span class="form-block__file-value"> </span>
                                </div>
                                <button class="form-block__file-del" type="button" onclick="resetFile(this)">Удалить
                                    <svg class="icon icon-close icon-16 ml-8">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <?*/ ?>
            <div class="border-top mb-40"></div>
            <h3 class="mb-40">Способ оплаты</h3>
            <div data-payments>
                <div class="form-block mb-16">
                    <label class="form-block__checkbox form-block__checkbox--radio">
                        <input type="radio" name="pays" checked><span class="p-md">Наличными при получении</span>
                    </label>
                </div>
            </div>
            <div class="border-top mb-40 mt-40"></div>
            <h3 class="mb-40">Дополнительная информация</h3>
            <div class="form-block w-100">
                <textarea class="form-block__input" name="ORDER_DESCRIPTION"></textarea>
                <label class="form-block__label">Комментарий к заказу</label>
            </div>
            </form>
        </div>
        <div class="basket__aside basket__aside--sticky">
            <div class="basket__total-block mb-8">
                <h4 class="mb-24 text-uppercase">В КОРЗИНЕ</h4>
                <div class="mb-8"><b data-all-quantity>7</b>&nbsp;товаров <span class="text-gray
                        text-nowrap">на общую сумму</span></div>
                <div class="basket__total-price h3 mb-24" data-all-price></div>
                <h5 class="mb-12">В сумму включено:</h5>
                <div class="text-gray font-weight-normal p-xs pb-20">
                    <div class="mb-8 d-flex">Скидка
                        <div class="text-green ml-auto" data-all-discount></div>
                    </div>
                    <div class="mb-8 d-flex delivery_summury">Стоимость доставки
                        <div class="text-red ml-auto" data-all-delivery></div>
                    </div>
                    <div class="mb-8 d-flex">Срок доставки
                        <div class="text-green ml-auto" data-period-text></div>
                        <div class="delivery_error">
                            Выберите другой способ доставки.
                        </div>
                    </div>
                    <label class="terms">
                        <input type="checkbox" checked="checked" class="personal terms_input">
                        <span>Я соглашаюсь с <a href="/politika.php" target="_blank">правилами обработки персональных данных</a></span>
                    </label>
                    <label class="terms">
                        <input type="checkbox" checked="checked" class="offerta terms_input">
                        <span>Я соглашаюсь с <a href="/contract-offer/" target="_blank">договором-оферты</a></span>
                    </label>
                    <span class="terms_error">Необходимо принять условия для оформления заказа</span>
                    <a class="btn btn--red w-100 px-0" onclick="getAllOrderElements()" id="soa-submit-button" href="#">Оформить
                        заказ</a>
                </div>
                <a class="btn btn--border fw-500 w-100 mb-40 mb-lg-0" href="/personal/cart/">Вернуться в корзину</a>
            </div>

        </div>
    </div>
</div>
<?php
$signer = new Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, 'sale.order.ajax');
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.order.ajax');
$options['siteId'] = $component->getSiteId();
$options['siteTemplateId'] = $component->getSiteTemplateId();
$options['templateFolder'] = $signedTemplate;
$options['courierDeliveryIds'] = $arParams['COURIER_DELIVERY_IDS'];
?>
<script>
    var orderAjaxComponent = new Meven.Components.SaleOrderAjax('<?=$signedParams?>', <?=CUtil::PhpToJSObject($options)?>);

    class OrderTabs{
        constructor(deliveryItems) {
            this.deliveryItems = $(deliveryItems);
            this.needDeliveryIds = "<?=$needDeliveryServices;?>";
            this.selfDeliveryIds = "<?=$selfDeliveryServices;?>";
            this.needDeliveryIds = this.needDeliveryIds.split(',');
            this.selfDeliveryIds = this.selfDeliveryIds.split(',');

            this.activeTab = 1;

            let _this = this;
            $('.deliveryTab').click(function (){
                _this.activeTab = $(this).data('os_tab');
                $('.deliveryTab').removeClass('active');
                $(this).addClass('active');
                _this.changeTab();
            });
            _this.loadObserver();
        }

        freshItemsEvent(el,id){
            if( this.activeTab == 1 ){
                if( !this.needDeliveryIds.includes(id) )
                    $(el).hide();
                else
                    $(el).show()
            }
            else if( this.activeTab == 2 ){
                if( !this.selfDeliveryIds.includes(id) )
                    $(el).hide();
                else
                    $(el).show()
            }
        }
        changeTab(){
            let _this = this;
            $(this.deliveryItems[0].children).map(function (i,el){
                let inputId = $($(el).find('input[name="DELIVERY_ID"]')).val();
                _this.freshItemsEvent(el,inputId);
            })
        }
        loadObserver(){
            let _this = this;
            const delivery_observer = new MutationObserver((record) => {
                let addedNodes = record[0]['addedNodes'];

                $(addedNodes).map(function (i,el){
                    let inputId = $($(el).find('input[name="DELIVERY_ID"]')).val();
                    _this.freshItemsEvent(el,inputId);
                });
            });

            // Настраиваем наблюдение за изменениями в тексте или содержимом `t`
            delivery_observer.observe(_this.deliveryItems[0], { childList: true, subtree: true });
        }
    }

    let orderTabs = new OrderTabs('#deliveryItems');
</script>