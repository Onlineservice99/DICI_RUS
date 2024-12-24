<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div
        id="<?=$arResult['AREA_ID']?>"
        class="card card--row card--favorite <?=(!$arResult['ITEM']['CAN_BUY'] ? 'card--favorite-null' : '');?>"
        data-element-id="<?= $arResult['ITEM']['ID'] ?>"
>
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
            <img src="<?= $arResult['ITEM']['PREVIEW_PICTURE']['SRC'] ?>" alt="">
        </div>
        <div class="card__info">
            <div class="card__brand"><?= current($arResult['ITEM']['DISPLAY_PROPERTIES']['BRAND']['LINK_ELEMENT_VALUE'])['NAME'] ?></div>
            <div class="card__art">Код <span><?= $arResult['ITEM']['ID'] ?></span></div>
        </div>
        <a class="card__title" href="<?= $arResult['ITEM']['DETAIL_PAGE_URL'] ?>"><?= $arResult['ITEM']['NAME'] ?></a>
    </div>
    <div class="card__bottom">
        <div class="card__price-wrap">
            <div class="h3 mr-auto"><?= $arResult['ITEM']['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] ?></div>
            <?php if ($arResult['ITEM']['MIN_PRICE']['PRINT_DISCOUNT_DIFF'] > 0): ?>
                <s><?= $arResult['ITEM']['MIN_PRICE']['PRINT_VALUE'] ?></s>
            <?php endif; ?>
        </div>
        <div class="card__controls">
            <? if ($arResult['ITEM']['PRODUCT']['AVAILABLE'] == 'Y') : ?>
                <a class="card__add" href="#" onclick="event.preventDefault()">
                    <div class="spin spin--card">
                        <input class="js-spin-count" name="quantity" type="number" value="1" data-min="0" data-max="<?= $arResult['ITEM']['CATALOG_QUANTITY'] ?>" data-step="1" readonly>
                        <div class="spin__add-text js-add-basket" data-add-basket="">В корзину</div>
                    </div>
                </a>
            <? else : ?>
                <a href="/local/ajax/popups/onRequest.php?id=<?= $arResult['ITEM']['ID'] ?>" class="btn-add-basket" data-touch="false"
                   data-type="ajax" data-fancybox><?= ($btnSubmitText = COption::GetOptionString('cosmos.settings', 'onRequestBtnName')) ? $btnSubmitText : 'Запрос наличия' ?></a>
            <? endif; ?>
            <div class="card__icons">
                <a class="link link--chart" href="#">
                    <svg class="icon icon-chart-bar">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chart-bar"></use>
                    </svg>
                    <svg class="icon icon-chart-bar-full">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chart-bar-full"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="card__counts"><span><?= $arResult['ITEM']['CATALOG_QUANTITY'] ?></span> <?= $arResult['ITEM']['CATALOG_MEASURE_NAME'] ?> на складе</div>
    <div class="card__check">
        <div class="form-block">
            <label class="form-block__checkbox form-block__checkbox--dot">
                <input type="checkbox" name="favorites[]" value="<?=$arResult['ITEM']['ID']?>"><span></span>
            </label>
        </div>
    </div>
</div>