<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main;
use electroset1\SaleOrder;
use Bitrix\Main\Localization\Loc;

\Bitrix\Main\Page\Asset::getInstance()->addJs($templateFolder . '/dist/script.bundle.js');

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */

$documentRoot = Main\Application::getDocumentRoot();

$totalCount = 0;
foreach ($arResult['ITEMS']['AnDelCanBuy'] as $item) {
    $totalCount += $item['QUANTITY'];
}

$aCategories = array();

foreach ($arResult['GRID']['ROWS'] as $item) {
    $aCategories[$item["PRODUCT_ID"]] = SaleOrder::getCategoryFromUrl($item["PRODUCT_ID"]);
}

$aCategories = json_encode($aCategories, JSON_UNESCAPED_UNICODE);
?>
<?php if (count($arResult['ITEMS']['AnDelCanBuy']) > 0) : ?>

    <?php if ($arResult['HAS_ZERO_AVAILIBLE']) : ?>
        <div class="bg-light text-red p-12 px-sm-24 rounded-sm mb-40">Несколько товаров в Вашей корзине на данный
            момент
            отсутствуют в наличии
        </div>
    <?php endif; ?>
    <div class="basket mb-40 mb-lg-80">
        <div class="basket__main" id="basket-download">
            <div class="basket-item basket-item--head d-none d-xl-flex">
                <div class="basket-item__num">Код</div>
                <div class="basket-item__name-wrap">Бренд и наименование</div>
                <div class="basket-item__price-wrap">
                    <div class="basket-item__price text-xl-right">Цена</div>
                    <div class="basket-item__count">Количество</div>
                    <div class="basket-item__price basket-item__price--total">Стоимость</div>
                </div>
            </div>
            <script>
                let aBasketElements = [];
                function getAllBasketElements() {
                    var aElements = new Array();
                    var id;
                    var basketItemName;
                    var basketItemsQuantity;
                    var basketItemPrice;

                    myElement = document.querySelectorAll('.basket-item ');

                    for (var i = 1; i < myElement.length; i++) {
                        id = myElement[i].getAttribute('data-element-basket');
                        basketItemName = $( myElement[i] ).find('.basket-item__title')[0].textContent;
                        basketItemsQuantity = $( myElement[i] ).find('.js-spin-count').attr('value');
                        basketItemPrice = $( myElement[i] ).find('.basket-item__price')[0].textContent.trim().split(' ')[0];

                        aElements.push({
                            'name': basketItemName,
                            'id': id,
                            'price': basketItemPrice,
                            'category': <?= $aCategories ?>[id],
                            'quantity': basketItemsQuantity,
                        });
                    }
                    return aElements;
                }


                window.dataLayer = window.dataLayer || []; window.dataLayer.push({
                    'ecommerce' : {
                        'checkout' : {
                            'actionField' : {
                                'step' : 1 // НОМЕР ШАГА
                            },
                            'products' : [getAllBasketElements()]
                        }
                    },
                    'event' : 'ecommerceCheckout'
                });
            </script>
            <script>
                var price;
                var id;

                $('.js-del-basket').click(function(){
                        quantity = $( this ).closest('.basket-item').find('.js-spin-count').attr('value');
                        name = $( this ).closest('.basket-item').find('.basket-item__title')[0].textContent;
                        price = $( this ).closest('.basket-item').find('.basket-item__price')[0].textContent.trim().split(' ')[0];
                        id = $( this ).closest('.basket-item').attr('data-element-basket');
                        ecommDeleteFromBasket();
                    }
                );

                function ecommDeleteFromBasket() {
                    window.dataLayer = window.dataLayer || []; window.dataLayer.push({
                        'ecommerce' : {
                            'remove' : {
                                'products' : [{
                                    'name' : name, // обязательное
                                    'id' : id, // обязательное
                                    'price' : price,
                                    'category' : '',
                                    'quantity' : quantity // количество
                                }]
                            }
                        },
                        'event' : 'ecommerceRemove'
                    });
                }
            </script>
            <?php foreach ($arResult['ITEMS']['AnDelCanBuy'] as $key => $item) : ?>
                <?php
                    $category = explode('/', $item['DETAIL_PAGE_URL']);
                    array_shift($category);
                    array_shift($category);
                    array_pop($category);

                    $aCategories = [];

                    foreach ($category as $code) {
                        $rsSections = CIBlockSection::GetList(array(),array('IBLOCK_ID' => $arResult['ELEMS'][$item['PRODUCT_ID']]['IBLOCK_ID'], '=CODE' => $code));

                        $arSection = $rsSections->Fetch();

                        $aCategories[] = $arSection['NAME'];
                    }
                    $sCategories = join('/', $aCategories);
                    $resStore = \Bitrix\Catalog\StoreProductTable::getlist(array(
                        'filter' => array("=PRODUCT_ID"=> $item['PRODUCT_ID'],'=STORE.ACTIVE'=>'Y'),
                        'select' => array('AMOUNT','STORE_ID','STORE_TITLE' => 'STORE.TITLE'),
                     ));
                    $arResult['STORE'] = [];
                    while($arStoreProduct = $resStore->fetch())
                    {
                        $arResult['STORE'][$arStoreProduct['STORE_ID']] = $arStoreProduct;
                    }
                ?>
                <div class="basket-item <?= ($item['NOT_AVAILABLE'] ? 'basket-item--null' : '') ?>" data-element-id="<?= $item['ID'] ?>" data-element-basket="<?= $item['PRODUCT_ID'] ?>">
                    <div class="basket-item__num">
                        <span><?= ($key + 1) ?></span><?= $arResult['ELEMS'][$item['PRODUCT_ID']]['PROPERTY_ARTICLE_VALUE'] ?>
                    </div>
                    <a class="basket-item__img-wrap" href="<?= $arResult['ELEMS'][$item['PRODUCT_ID']]['DETAIL_PAGE_URL'] ?>">
                        <img src="<?= CFile::GetPath($arResult['ELEMS'][$item['PRODUCT_ID']]['PREVIEW_PICTURE']) ?>" alt="" />
                    </a>
                    <?php if ($item['NOT_AVAILABLE']) : ?>
                        <div class="basket-item__name-wrap">
                            <div class="basket-item__brand p-xs text-gray mb-16"><?= $arResult['BRANDS'][$arResult['ELEMS'][$item['PRODUCT_ID']]['PROPERTY_BRAND_VALUE']] ?></div>
                            <a class="basket-item__title link" href="<?= $arResult['ELEMS'][$item['PRODUCT_ID']]['DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?></a>
                        </div>
                        <div class="basket-item__price-wrap">
                            <div class="text-red rounded-sm bg-light p-12 px-md-24 text-center">Товара нет в наличии</div>
                        </div>
                    <?php else : ?>
                        <div class="basket-item__name-wrap">
                            <div class="basket-item__brand p-xs text-gray mb-16">
                                <?= $arResult['BRANDS'][$arResult['ELEMS'][$item['PRODUCT_ID']]['PROPERTY_BRAND_VALUE']] ?>
                            </div>
                            <a class="basket-item__title link" href="<?= $arResult['ELEMS'][$item['PRODUCT_ID']]['DETAIL_PAGE_URL'] ?>"><?= $item['NAME']
                                                                                                                                        ?></a>
                        </div>
                        <div class="basket-item__price-wrap">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="basket-item__price text-xl-right">
                                    <?php if ($item['SUM_DISCOUNT_PRICE'] > 0) : ?>
                                        <s><?= $item['FULL_PRICE_FORMATED'] ?></s>
                                    <?php endif; ?>
                                    <?= $item['PRICE_FORMATED'] ?>
                                </div>
                                <div class="spin spin--card spin--basket">
                                    <input class="js-spin-count" name="" type="number" value="<?= $item['QUANTITY'] ?>" data-min="0" data-max="1000" data-step="1">
                                    <? if (!empty($arResult['STORE'])) {?>
                                        <? $idRussvetStore = !empty(\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet")) ? (int)\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet") : 1;?>
                                        <? if(isset($arResult['STORE'][$idRussvetStore]) && !empty($arResult['STORE'][$idRussvetStore])) {?>
                                            <? if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "russvet_count_shipment"))) {?>
                                                <div class="card__shipment _pk">
                                                    Отгрузка: <span> <span><?= \Bitrix\Main\Config\Option::get("meven.info", "russvet_count_shipment")?></span></span>
                                                </div>
                                                <? } ?>
                                        <? } else {?>
                                            <? if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "other_count_shipment"))) {?>
                                                <div class="card__shipment _pk">
                                                    Отгрузка: <span> <span><?= \Bitrix\Main\Config\Option::get("meven.info", "other_count_shipment")?></span></span>
                                                </div>
                                            <? } ?>
                                        <?} ?>
                                    <? } ?>
                                </div>
                                <div class="basket-item__price basket-item__price--total"><span class="fw-600"><?= $item['SUM'] ?></span>
                                </div>
                            </div>
                        </div>
                        <? if (!empty($arResult['STORE'])) {?>
                            <? $idRussvetStore = !empty(\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet")) ? (int)\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet") : 1;?>
                            <? if(isset($arResult['STORE'][$idRussvetStore]) && !empty($arResult['STORE'][$idRussvetStore])) {?>
                                <? if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "russvet_count_shipment"))) {?>
                                    <div class="card__shipment _mobile">
                                        Отгрузка: <span> <span><?= \Bitrix\Main\Config\Option::get("meven.info", "russvet_count_shipment")?></span></span>
                                    </div>
                                    <? } ?>
                            <? } else {?>
                                <? if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "other_count_shipment"))) {?>
                                    <div class="card__shipment _mobile">
                                        Отгрузка: <span> <span><?= \Bitrix\Main\Config\Option::get("meven.info", "other_count_shipment")?></span></span>
                                    </div>
                                <? } ?>
                            <?} ?>
                        <? } ?>
                    <?php endif; ?>
                    <div class="basket-item__del">
                        <a class="link js-del-basket" href="javascript:;">
                            <svg class="icon icon-close icon-20">
                                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-close"></use>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
        <div class="basket__aside">
            <div class="basket__total-block mb-8">
                <h4 class="mb-24 text-uppercase">В КОРЗИНЕ</h4>
                <div class="mb-8"><b data-cart-count><?= $totalCount ?></b>&nbsp;
                    <?= $arResult['WORD_ITEMS'] ?> <span class="text-gray text-nowrap">на общую сумму</span>
                </div>
                <div class="basket__total-price h3 mb-24"><?= $arResult['allSum_FORMATED'] ?></div>
                <div class="form-block mb-28">
                    <input class="form-block__input" id="coupon_input" name="" <?php
                                                                                if ($arResult['COUPON_LIST'][count($arResult['COUPON_LIST']) - 1]['JS_STATUS'] == 'APPLYED') {
                                                                                    echo 'disabled="disabled"';
                                                                                }
                                                                                ?> value="<?= ($arResult['COUPON_LIST'][count($arResult['COUPON_LIST']) - 1]['COUPON']) ?>" placeholder="0000000000">
                    <label class="form-block__label">Промокод на скидку</label>
                    <div class="form-block__label form-block__label--error">Ошибка</div>
                </div>
                <h5 class="mb-12">В сумму включено:</h5>
                <div class="text-gray font-weight-normal p-xs pb-20 <?= ($arResult['DISCOUNT_PRICE_ALL'] < 1 ? 'd-none' : ''); ?>">
                    <div class="mb-8 d-flex">Скидка
                        <div class="text-green ml-auto">-
                            <span id="discount-price"><?= $arResult['DISCOUNT_PRICE_ALL_FORMATED'] ?></span>
                        </div>
                    </div>
                </div>
                <a class="btn btn--red w-100 px-0" href="/personal/order/" onclick="ym(88715282,'reachGoal','orderBtn')">Оформить заказ</a>
            </div>
            <a class="btn btn--border fw-500 w-100 mb-40" id="clear_basket" href="#">Очистить корзину</a>
            <div class="d-flex justify-content-around">
                <div class="d-none d-xl-block">
                    <a class="link link--circle-icon" href="#" onClick="window.print()">
                        <svg class="icon icon-print">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-print"></use>
                        </svg>
                    </a>
                    <a class="link link--circle-icon" href="#" onClick="cartPageS.generatePDF()">
                        <svg class="icon icon-download">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-download"></use>
                        </svg>
                    </a>
                    <a class="link link--circle-icon" href="/local/ajax/popups/sendbasket.php" data-fancybox data-type="ajax" data-touch="false" data-close-existing="true">
                        <svg class="icon icon-email">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-email"></use>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="basket mb-40 mb-lg-80 align-items-center">
        <div class="basket__main">
            <h3 class="mb-8">Ваша корзина пуста.</h3>
            <div class="mb-32">Вы можете загрузить товары из списка отложенных или добавить товары из каталога
            </div>
            <div class="row">
                <div class="col-xl-auto pb-16">
                    <a class="btn btn--border btn--sm w-100" href="/personal/favorite/">
                        <svg class="icon icon-star icon-16 text-red mr-8">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-star"></use>
                        </svg>
                        Заполнить корзину отложенными товарами
                    </a>
                </div>
                <div class="col-xl-auto pb-16">
                    <a class="btn btn--border btn--sm w-100" href="/catalog/">
                        <svg class="icon icon-apps icon-16 text-red mr-8">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-apps"></use>
                        </svg>
                        В каталог
                    </a>
                </div>
            </div>
        </div>
        <div class="basket__aside">
            <div class="basket__total-block">
                <h4 class="mb-24 text-uppercase">В КОРЗИНЕ</h4>
                <div class="mb-8"><b>0</b>&nbsp;товаров <span class="text-gray text-nowrap">на общую сумму</span></div>
                <div class="basket__total-price h3 mb-24">0 <small>руб.</small>
                </div>
                <a class="btn btn--red is-disabled w-100 px-0" href="#">Оформить заказ</a>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php

$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedTemplate = $signer->sign($templateName, 'sale.basket.basket');
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.basket.basket');
$options['siteId'] = $component->getSiteId();
$options['siteTemplateId'] = $component->getSiteTemplateId();
$options['templateFolder'] = $signedTemplate;
?>
<script>
    var cartPageS = new Meven.Components.CartPage('<?= $signedParams ?>', <?= CUtil::PhpToJSObject($options) ?>);
</script>

<style>
    @media print {
        .breadcrumbs,
        #bx-panel,
        #tns1-ow,
        .privilege,
        h2,
        header,
        footer {
            display: none !important;
        }
    }
</style>