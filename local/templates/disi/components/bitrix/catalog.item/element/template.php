<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use electroset1\Content;



$resizeImg = CFile::ResizeImageGet($arResult['ITEM']['PREVIEW_PICTURE']['ID'], Array("width" => 250, "height" => 265), BX_RESIZE_IMAGE_PROPORTIONAL )['src'];
$imgSEO = ($resizeImg) ? true : false;
$resStore = \Bitrix\Catalog\StoreProductTable::getlist(array(
    'filter' => array("=PRODUCT_ID"=> $arResult['ITEM']['ID'],'=STORE.ACTIVE'=>'Y'),
    'select' => array('AMOUNT','STORE_ID','STORE_TITLE' => 'STORE.TITLE'),
 ));
$arResult['STORE'] = [];
while($arStoreProduct = $resStore->fetch())
{
    $arResult['STORE'][$arStoreProduct['STORE_ID']] = $arStoreProduct;
}
?>

<div class="card <?=$arResult["ADDITIONAL_CLASSES"]?>" <?= $imgSEO ? 'itemscope itemtype="http://schema.org/ImageObject"' : '' ?>  data-element-id="<?= $arResult['ITEM']['ID'] ?>">
    <div class="card__link-area">
        <div class="card__labels">
            <?php if ($arResult['ITEM']['PROPERTIES']['LABEL_HIT']['VALUE'] == 'Да'):?>
                <div class="label <?= $arResult['ITEM']['PROPERTIES']['LABEL_HIT']['HINT'] ?>">
                    <?= $arResult['ITEM']['PROPERTIES']['LABEL_HIT']['NAME'] ?>
                </div>
            <?php endif;?>
            <?php if ($arResult['ITEM']['PROPERTIES']['LABEL_RECOMEND']['VALUE'] == 'Да'):?>
                <div class="label <?= $arResult['ITEM']['PROPERTIES']['LABEL_RECOMEND']['HINT'] ?>">
                    <?= $arResult['ITEM']['PROPERTIES']['LABEL_RECOMEND']['NAME'] ?>
                </div>
            <?php endif;?>
            <?php if ($arResult['ITEM']['PROPERTIES']['LABEL_ACTIONS']['VALUE'] == 'Да'):?>
                <div class="label <?= $arResult['ITEM']['PROPERTIES']['LABEL_ACTIONS']['HINT'] ?>">
                    <?= $arResult['ITEM']['PROPERTIES']['LABEL_ACTIONS']['NAME'] ?>
                </div>
            <?php endif;?>
            <?php if ($arResult['ITEM']['PROPERTIES']['LABEL_SALES']['VALUE'] == 'Да'):?>
                <div class="label <?= $arResult['ITEM']['PROPERTIES']['LABEL_SALES']['HINT'] ?>">
                    <?= $arResult['ITEM']['PROPERTIES']['LABEL_SALES']['NAME'] ?>
                </div>
            <?php endif;?>
        </div>
        <div class="card__img-wrap">
            <img loading="lazy" <?= $imgSEO ? 'itemprop="contentUrl"' : '' ?>  src="<?= $resizeImg ?>" alt="">
        </div>
        <div class="card__info">
            <div class="card__brand"><? /* current($arResult['ITEM']['DISPLAY_PROPERTIES']['BRAND']['LINK_ELEMENT_VALUE'])['NAME'] */ ?></div>
            <div class="card__art">Код <span><?= $arResult['ITEM']['ID'] ?></span></div>
        </div>
        
        <a class="card__title" onclick="ecommClick(<?= $arResult['ITEM']['ID'] ?>)" <?= $imgSEO ? 'itemprop="name"' : '' ?>  href="<?= $arResult['ITEM']['DETAIL_PAGE_URL'] ?>"><?= $arResult['ITEM']['NAME'] ?></a>
        
    </div>
    <div class="card__bottom">
        <div class="card__price-wrap">
            <div class="h3 mr-auto"><?= $arResult['PRICE_DISCOUNT']['PRINT_PRICE'] ?> </div>
            <?php if ($arResult['PRICE_DISCOUNT']['DISCOUNT'] > 0): ?>
                <s><?= $arResult['PRICE_DISCOUNT']['PRINT_BASE_PRICE'] ?></s>
            <?php endif; ?>
        </div>
        <div class="card__controls js-add-basket-wrap <?= Content::checkProductInBasket($arResult['ITEM']['ID']) == true ? ' in_basket' : '' ?>">
            <? if ($arResult['ITEM']['PRODUCT']['AVAILABLE'] == 'Y') : ?>
                <a class="card__add" href="#" onclick="event.preventDefault()">
                    <div class="spin spin--card">
                        <input class="js-spin-count" name="quantity" type="number" value="1" data-min="0" data-max="<?= $arResult['ITEM']['CATALOG_QUANTITY'] ?>" data-step="1">
                    </div>
                    <div onclick="setPrice(<?= $arResult['PRICE_DISCOUNT']['PRICE'] ?>)" class="btn-add-basket js-add-basket">В&nbsp;корзину</div>
                </a>
                <a href="/personal/cart/" class="btn-go-basket">В&nbsp;корзине <span>Перейти</span></a>
            <? else : ?>
                <a href="/local/ajax/popups/onRequest.php?id=<?= $arResult['ITEM']['ID'] ?>" class="btn-add-basket" data-touch="false"
                   data-type="ajax" data-fancybox><?= ($btnSubmitText = COption::GetOptionString('cosmos.settings', 'onRequestBtnName')) ? $btnSubmitText : 'Запрос наличия' ?></a>
            <? endif; ?>
            <div class="card__icons">
                <a class="link link--favorite" href="#">
                    <svg class="icon icon-star-empty">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-star-empty"></use>
                    </svg>
                    <svg class="icon icon-star">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-star"></use>
                    </svg>
                </a>
                <a class="link link--chart" href="#">
                    <svg class="icon icon-chart-bar">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chart-bar"></use>
                    </svg>
                    <svg class="icon icon-chart-bar-full">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chart-bar-full"></use>
                    </svg>
                </a>
            </div>
            <? if (!empty($arResult['STORE'])) {?>
                    <? $idRussvetStore = !empty(\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet")) ? (int)\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet") : 1;?>
                    <? if(isset($arResult['STORE'][$idRussvetStore]) && !empty($arResult['STORE'][$idRussvetStore])) {?>
                        <? if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "russvet_count_shipment"))) {?>
                            <div class="card__shipment card__shipment--row ">
                                <span class="before_otgr"> <span><?= \Bitrix\Main\Config\Option::get("meven.info", "russvet_count_shipment")?></span></span>
                            </div>
                            <? } ?>
                    <? } else {?>
                        <? if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "other_count_shipment"))) {?>
                            <div class="card__shipment card__shipment--row ">
                                <span class="before_otgr"> <span><?= \Bitrix\Main\Config\Option::get("meven.info", "other_count_shipment")?></span></span>
                            </div>
                        <? } ?>
                    <?} ?>
                <? } ?>
        </div>
        <? if (!empty($arResult['STORE'])) {?>
            <? $idRussvetStore = !empty(\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet")) ? (int)\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet") : 1;?>
            <? if(isset($arResult['STORE'][$idRussvetStore]) && !empty($arResult['STORE'][$idRussvetStore])) {?>
                <? if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "russvet_count_shipment"))) {?>
                    <div class="card__shipment _mobile">
                        <span class="before_otgr"> <span><?= \Bitrix\Main\Config\Option::get("meven.info", "russvet_count_shipment")?></span></span>
                    </div>
                    <? } ?>
            <? } else {?>
                <? if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "other_count_shipment"))) {?>
                    <div class="card__shipment _mobile">
                        <span class="before_otgr"> <span><?= \Bitrix\Main\Config\Option::get("meven.info", "other_count_shipment")?></span></span>
                    </div>
                <? } ?>
            <?} ?>
        <? } ?>
    </div>
    <div class="card__counts">
        <span><?= $arResult['ITEM']['CATALOG_QUANTITY'] ?></span> <?= $arResult['ITEM']['CATALOG_MEASURE_NAME'] ?> на
        складе
    </div>
    <? if (!empty($arResult['STORE'])) {?>
        <? $idRussvetStore = !empty(\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet")) ? (int)\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet") : 1;?>
        <? if(isset($arResult['STORE'][$idRussvetStore]) && !empty($arResult['STORE'][$idRussvetStore])) {?>
            <? if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "russvet_count_shipment"))) {?>
                <div class="card__shipment">
                    <span class="before_otgr"> <span><?= \Bitrix\Main\Config\Option::get("meven.info", "russvet_count_shipment")?></span></span>
                </div>
                <? } ?>
        <? } else {?>
            <? if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "other_count_shipment"))) {?>
                <div class="card__shipment">
                    <span class="before_otgr"> <span><?= \Bitrix\Main\Config\Option::get("meven.info", "other_count_shipment")?></span></span>
                </div>
            <? } ?>
        <?} ?>
    <? } ?>
</div>


