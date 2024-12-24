<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
global $USER;

$asset = \Bitrix\Main\Page\Asset::getInstance();
$asset->addJs($templateFolder.'/dist/script.bundle.js');
?>
<div class="section__overlay mb-32 mb-lg-80 pt-lg-48">
    <div class="container">
        <div class="basket basket--order mb-40 mb-lg-80">
            <div class="basket__main mb-32 mb-lg-120">
                <?php if (!$USER->IsAuthorized()):?>
                    <div class="bg-light rounded-sm px-16 pt-16 mb-32 mb-lg-56">
                        <div class="row align-items-center">
                            <div class="col-xl text-center pb-16">Я зарегистрированный пользователь</div>
                            <div class="col-xl-auto pb-16 mr-xl-88">
                                <a class="btn btn--black px-xl-112 w-100" href="/local/ajax/popups/auth.php" data-fancybox data-touch="false" data-type="ajax" data-close-existing="true">Войти</a>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
                <form class="form" id="soa-form" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <?=bitrix_sessid_post();?>
                    <input type="hidden" name="BUYER_STORE" value="0">
                    <input type="hidden" name="RECENT_DELIVERY_VALUE" value="0000697597">
                    <input type="hidden" name="soa-action" value="saveOrderAjax">
                    <input type="hidden" name="location_type" value="code">
                    <input type="hidden" name="PERSON_TYPE" value="1">
                    <input type="hidden" name="PERSON_TYPE_OLD" value="1">
                    <h3 class="mb-32">Доставка</h3>
                    <div class="form__block form__block--f1">
                        <?
                        $APPLICATION->IncludeComponent(
                            'bitrix:sale.location.selector.search',
                            'custom',
                            array(
                                'CODE' => $arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['LOCATION']],
                                'ID_PROP' => $arResult['PROPS'][1]['LOCATION']
                            ),
                            false
                        );
                        ?>

                    </div>
                    <div class="form__block form__block--delivery" data-delivery></div>
                    <div class="tab-content">
                        <?/*<div class="d-none">// блок чтоб работали табы бутстрапа если убрать табы н ебудут работать правильно
                            <ul class="nav">
                                <?php foreach ($arResult['JS_DATA']['DELIVERY'] as $delivery):?>
                                    <div
                                        class="tab-pane mb-40 fade"
                                        href="#tab-delivery-<?=$delivery['ID']?>"
                                        id="delivery-link<?=$delivery['ID']?>"
                                    />
                                        <?=$delivery['OWN_NAME']?>
                                    </div>
                                <?php endforeach;?>
                            </ul>
                        </div>*/?>
                        <?php /*if (count($arResult['ORDER_PROP']['USER_PROFILES']) > 0 && 1 == 2):?>
                            <div class="tab-pane fade show active" id="tab-delivery-1">
                                <h4 class="mb-20">Адрес доставки</h4>
                                <div class="select mb-16">
                                    <div class="select__label">Ваши адреса</div>
                                    <select class="js-select-default js-refresh-elem" name="PROFILE_ID">
                                        <?php foreach ($arResult['ORDER_PROP']['USER_PROFILES'] as $prof):?>
                                            <option value="<?=$prof['ID']?>"><?=$prof['NAME']?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <a class="btn btn--border btn--sm btn--toggle-plus mb-40" href="#add-address" data-toggle="collapse">Добавить новый адрес</a>
                                <div class="collapse" id="add-add1ress">
                                    <div class="form__block form__block--tmp mb-16">
                                        <div class="form-block">
                                            <input type="text" name="ORDER_PROP_<?=$arResult['PROPS'][1]['STREET']?>" value="" style="display: none" /> 
                                            <input class="form-block__input" data-prop-street
                                                name="ORDER_PROP_<?=$arResult['PROPS'][1]['STREET']?>">
                                            <label class="form-block__label">Улица*</label>
                                            <div class="form-block__label form-block__label--error">ошибка</div>
                                        </div>
                                        <div class="form-block">
                                            <input class="form-block__input" data-prop-house
                                            id="input-house"
                                                name="ORDER_PROP_<?=$arResult['PROPS'][1]['INDEX']?>">
                                            <label class="form-block__label">Индекс</label>
                                            <div class="form-block__label form-block__label--error">ошибка</div>
                                        </div>
                                        <div class="form-block">
                                            <input class="form-block__input" data-prop-house
                                            autocomplete="house"
                                                name="ORDER_PROP_<?=$arResult['PROPS'][1]['HOUSE']?>">
                                            <label class="form-block__label">Дом</label>
                                            <div class="form-block__label form-block__label--error">ошибка</div>
                                        </div>
                                        <div class="form-block">
                                            <input class="form-block__input" data-prop-corp
                                            autocomplete="corpus"
                                                name="ORDER_PROP_<?=$arResult['PROPS'][1]['CORPUS']?>">
                                            <label class="form-block__label">Корпус</label>
                                            <div class="form-block__label form-block__label--error">ошибка</div>
                                        </div>
                                        <div class="form-block">
                                            <input class="form-block__input" data-prop-entrance
                                            autocomplete="off"
                                                name="ORDER_PROP_<?=$arResult['PROPS'][1]['ENTRANCE']?>">
                                            <label class="form-block__label">Подъезд</label>
                                            <div class="form-block__label form-block__label--error">ошибка</div>
                                        </div>
                                        <div class="form-block">
                                            <input class="form-block__input" data-prop-ofis
                                            autocomplete="off"
                                                name="ORDER_PROP_<?=$arResult['PROPS'][1]['OFIS']?>">
                                            <label class="form-block__label">Офис/квартира</label>
                                            <div class="form-block__label form-block__label--error">ошибка</div>
                                        </div>
                                    </div>
                                    <a class="btn btn--border btn--sm mb-40" href="#" data-toggle="collapse">
                                        <svg class="icon icon-success icon-16 text-red mr-8">
                                            <use xlink:href="./icons/symbol-defs.svg#icon-success"></use>
                                        </svg>Сохранить в мои адреса
                                    </a>
                                </div>
                            </div>
                        <?php else:*/?>
                            <div class="show active">
                                <h4 class="mb-20">Адреc доставки</h4>
                                
                                <div class="form__block form__block--address" data-properies="1"></div>
                            </div>
                        <?php /*endif;*/?>
                            <?php /*foreach ($arResult['JS_DATA']['DELIVERY'] as $delivery):?>
                                <div class="tab-pane mb-40 fade" id="tab-delivery-<?=$delivery['ID']?>">
                                    <?=$delivery['DESCRIPTION']?>
                                </div>
                            <?php endforeach;*/?>
                        </div>
                        <div class="border-top mb-40"></div>
                        <h3 class="mb-40">Контактные данные</h3>
                        <div class="form__block form__block--contacts">
                            <div class="form-block">
                                <input class="form-block__input"
                                       name="ORDER_PROP_<?=$arResult['PROPS'][1]['NAME']?>"
                                       value="<?=$arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['NAME']]?>"
                                >
                                <label class="form-block__label">ФИО<span>*</span></label>
                                <div class="form-block__label form-block__label--error">Введите ФИО</div>
                            </div>
                            <div class="form-block">
                                <input class="form-block__input js-mask-tel"
                                       name="ORDER_PROP_<?=$arResult['PROPS'][1]['PHONE']?>"
                                       type="tel"
                                       placeholder="+7(9__)-___-__-__"
                                       value="<?=$arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['PHONE']]?>"
                                >
                                <label class="form-block__label">Ваш телефон<span>*</span></label>
                                <div class="form-block__label form-block__label--error">Номер введен не корректно</div>
                            </div>
                            <div class="form-block">
                                <input class="form-block__input"
                                       name="ORDER_PROP_<?=$arResult['PROPS'][1]['EMAIL']?>"
                                       value="<?=$arResult['USER_VALS']['ORDER_PROP'][$arResult['PROPS'][1]['EMAIL']]?>"
                                       type="email">
                                <label class="form-block__label">e-mail<span>*</span></label>
                                <div class="form-block__label form-block__label--error">E-mail введен не корректно</div>
                            </div>
                        </div>
                        <?php
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
                        <div class="mb-8 d-flex">Стоимость доставки
                            <div class="text-red ml-auto" data-all-delivery></div>
                        </div>
                    </div>
                    <a class="btn btn--red w-100 px-0" id="soa-submit-button" href="#">Оформить заказ</a>
                </div>
                <a class="btn btn--border fw-500 w-100 mb-40 mb-lg-0" href="/personal/cart/">Вернуться в корзину</a>
            </div>
            <div class="basket__main basket__main--list">
                <h2 class="mb-32 mb-lg-48">Ваш заказ</h2>
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

    // для совместимости
    BX.Sale.OrderAjaxComponent = {
        sendRequest: function() {
            $('[name=ORDER_PROP_30]').val($('#russianpost_result_address').val());
            //orderAjaxComponent.sendRequest();
        }
    };
</script>