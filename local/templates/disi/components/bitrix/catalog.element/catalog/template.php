<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use electroset1\Content;
$APPLICATION->SetPageProperty("canonical", $arResult["CANONICAL_PAGE_URL"]);
?>
<script>
    let categoryFrom = document.referrer;
    categoryFrom = categoryFrom.split('/');
    categoryFrom = categoryFrom[categoryFrom.length-2];
</script>
<?php
$res = 0;

$aSectionsCodesList = explode('/', $arResult['SECTION']['SECTION_PAGE_URL']);
$aSectionsList = array();

for ($i = 2; $i < count($aSectionsCodesList) - 1; $i++) {
    $rsSections = CIBlockSection::GetList(array(),array('IBLOCK_ID' => $arResult['ORIGINAL_PARAMETERS']['IBLOCK_ID'],
                  '=CODE' => $aSectionsCodesList[$i]));
    $sSection = $rsSections->Fetch();
    ?>
    <script>
        var sectionFromName;

        if (categoryFrom == "<?= $aSectionsCodesList[$i] ?>") {
            sectionFromName = "<?= $sSection['NAME'] ?>";
        }
    </script>
    <?php
}
?>
    <script>
        name = $( 'h1.mb-16.mr-40' ).text();
        var quantity = $( '.js-spin-count' ).val();
        var price = $( '.product-block__price' ).text().replace(" руб.", "").replace(" ", "").trim();

        function ecommClickToBasketDetail(id) {
            window.dataLayer = window.dataLayer || []; window.dataLayer.push({
                'ecommerce' : {
                    'add' : {
                        'products' : [{
                            'name' : name, // обязательное
                            'id' : id, // обязательное
                            'price' : price,
                            'category' : "<?= $arResult['CATEGORY_PATH'] ?>",
                            'quantity' : quantity // количество
                        }]
                    }
                },
                'event' : 'ecommerceAdd'
            });
        }

        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'ecommerce' : {
                'detail' : {
                    'actionField' : {
                        'list' : sectionFromName
                    },
                    'products' : [ {
                        'name' : "<?= $arResult['NAME'] ?>", // обязательное
                        'id' : <?= $arResult['ID'] ?>, // обязательное
                        'category' : "<?= $arResult['CATEGORY_PATH'] ?>"
                    }]
                }
            },
            'event' : 'ecommerceDetail'
        });
    </script>
<div class="product-block" data-element-id="<?= $arResult['ID'] ?>" id="<?=$this->GetEditAreaId($arResult['ID'])?>">
    <div class="product-block__room">
        <div class="carousel carousel--product">
            <div id="carousel-product-block">
                <?php foreach ($arResult['PICTURES'] as $pic): ?>
                    <div>
                        
                        <div class="product-block__img-wrap">
                            <img class="tns-lazy" src="" data-src="<?= $pic ?>" alt="">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="carousel carousel--thumbs">
            <div id="carousel-product-block-nav">
                <?php foreach ($arResult['SMALL_PICTURES'] as $pic): ?>
                    <div>
                        <div class="product-block__thumb">
                            <img src="<?= $pic["src"] ?>">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="product-block__info">
        <div class="product-block__block product-block__block--top">
            <div class="product-block__head mb-24">
                <div class="col-xl-auto pb-12 px-12">
                    <div class="p-md text-gray">Бренд</div>
                    <?= ( !is_null($arResult['DISPLAY_PROPERTIES']['BRAND']['LINK_ELEMENT_VALUE']) && !empty(current($arResult['DISPLAY_PROPERTIES']['BRAND']['LINK_ELEMENT_VALUE'])['NAME']) &&
                        !is_null(current($arResult['DISPLAY_PROPERTIES']['BRAND']['LINK_ELEMENT_VALUE'])['NAME']))
                        ? current($arResult['DISPLAY_PROPERTIES']['BRAND']['LINK_ELEMENT_VALUE'])['NAME']
                        : $arResult['PROPERTIES']['CML2_MANUFACTURER']['VALUE']
                    ?>
                </div>
                <div class="col-xl-auto pb-12 px-12">
                    <div class="p-md text-gray">Страна изготовителя</div>
                    <?= (!empty($arResult['DISPLAY_PROPERTIES']['COUNTRY']['VALUE'])) ? $arResult['DISPLAY_PROPERTIES']['COUNTRY']['VALUE'] : $arResult['PROPERTIES']['STRANA']['VALUE'] ?>
                </div>
                <div class="col-xl-auto pb-12 px-12">
                    <div class="p-md text-gray">Код товара</div>
                    <?= $arResult['ID'] ?>
                </div>
                <div class="col-auto pb-12 px-12 d-none d-xl-block">
                    <a class="link link--print" href="#" onClick="window.print()">
                        <svg class="icon icon-print">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-print"></use>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="product-block__block product-block__block--icons">
            <div class="product-block__icons-row mb-24 mb-lg-40 mx-n12">
                <?php foreach ($arResult['DISPLAY_PROPERTIES']['DETAIL_ISO']['FILE_VALUE'] as $val): ?>
                    <div class="col-auto pb-8 px-12">
                        <div class="product-block__icon">
                            <img src="<?= $val['SRC'] ?>" alt="">
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
        <div class="w-100">
            <div class="row align-items-center mb-24 mb-lg-44 mx-n8">
                <?php if ($arResult['ACTIONS']['ID'] > 0):?>
                    <div class="col-auto text-gray pb-12 px-8 mr-auto">Товар учавствует в акции
                        «<?=$arResult['ACTIONS']['NAME']?>»&nbsp;<a class="link link--underline" href="#sale-1" data-fancybox="" data-touch="false">Подробнее</a>
                    </div>
                    <div class="d-none">
                        <div id="sale-1"><div class="container"><?=$arResult['ACTIONS']['DETAIL_TEXT']?></div></div>
                    </div>
                <?php endif;?>
                <div class="col-auto p-md pb-12 px-8"><span class="text-gray">Единица измерения:&nbsp&nbsp;</span><span
                            class="fw-600"><?= $arResult['ITEM_MEASURE']['TITLE'] ?></span></div>
            </div>
        </div>
        <div class="d-xl-flex w-100">
            <div class="product-block__price-wrap mb-12">
                <div class="d-flex mb-16">
                    <div class="product-block__price h3 text-green"><?= $arResult['PRICE_DISCOUNT']['PRINT_PRICE'] ?>
                    </div>
                    <?php if ($arResult['PRICE_DISCOUNT']['DISCOUNT'] > 0): ?>
                        <s class="product-block__price-old ml-auto align-self-end"><?= $arResult['PRICE_DISCOUNT']['PRINT_BASE_PRICE'] ?></s>
                    <?php endif; ?>
                </div>
                <div class="product-block__add mb-8 js-add-basket-wrap <?= Content::checkProductInBasket($arResult['ID']) == true ? ' in_basket' : '' ?>">
                    <?if ($arResult['PRODUCT']['AVAILABLE'] == 'Y'):?>
                        <div class="spin spin--card spin--product">
                            <input class="js-spin-count" name="quantity" type="number" value="1" data-min="1"
                                data-max="<?= $arResult['CATALOG_QUANTITY'] ?>"
                                data-step="1">
                        </div>
                        <div class="btn-add-basket js-add-basket" onClick="ecommClickToBasketDetail(<?=$arResult['ID']?>)">В&nbsp;корзину</div>

                        <a href="/personal/cart/" class="btn-go-basket">В&nbsp;корзине <span>Перейти</span></a>
                    <?else:?>
                        <a href="/local/ajax/popups/onRequest.php?id=<?= $arResult['ITEM']['ID'] ?>" class="btn-add-basket" data-touch="false"
                           data-type="ajax" data-fancybox><?= ($btnSubmitText = COption::GetOptionString('cosmos.settings', 'onRequestBtnName')) ? $btnSubmitText : 'Запрос наличия' ?></a>
                    <?endif;?>
                    <div class="product-block__icons">
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
                </div>
                <div class="product-block__count p-md"><span class="text-gray mr-auto">На складе:</span>
                    <div class="fw-600"><?= $arResult['CATALOG_QUANTITY'] ?> <?= $arResult['ITEM_MEASURE']['TITLE'] ?></div>
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
            <?php
            $countOpt = \Bitrix\Main\Config\Option::get('meven.info', 'count_items_from_discount_opt', 0);
            $percentOpt = \Bitrix\Main\Config\Option::get('meven.info', 'percent_from_discount_opt', 0);
            if ($countOpt > 0 && $percentOpt > 0): ?>
                <div class="product-block__opt-wrap mb-12"><span class="text-gray">Оптовая цена* &nbsp;&nbsp;</span>
                    <span class="fw-600"><?=\Meven\Helper\Helper::formatNumber($arResult['MIN_PRICE']['VALUE'] * ((100-$percentOpt)/100), 2)?></span>&nbsp;руб.
                    <p class="mb-0 pt-8">
                        *Оптовая цена действует при заказе одного вида товара от <?=$countOpt?> единиц.
                        <br>Добавьте товар в корзину и скидка будет применена автоматически, при выполнении условия.
                    </p>
                </div>
            <?php endif;?>
        </div>
        <div class="w-100 d-xl-none">
            <a class="btn btn--border btn--sm w-100" href="javascript:;">
                <svg class="icon icon-shuffle mr-8 text-red">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-shuffle"></use>
                </svg>
                Посмотрите аналоги этого товара
            </a>
        </div>
    </div>
</div>
<div class="d-lg-flex mb-32 mb-lg-48">
    <ul class="nav nav-pills">
        <?php if (!empty($arResult['DETAIL_TEXT'])):?>
            <li class="nav-item">
                <a class="nav-link active" href="#tab1" data-toggle="tab">Описание</a>
            </li>
        <?php endif;?>
        <li class="nav-item">
            <a class="nav-link <?=(empty($arResult['DETAIL_TEXT']) ? 'active' : '')?>" href="#tab2" data-toggle="tab">Характеристики</a>
        </li>
        <?php if (!empty($arResult['PROPERTIES']['CERTIFICATE']['VALUE'])):?>
        <li class="nav-item">
            <a class="nav-link" href="#tab3" data-toggle="tab">Сертификаты</a>
        </li>
        <?php endif;?>
        <?php if (!empty($arResult['PROPERTIES']['DOCUMENT']['FILE_VALUE'])):?>
        <li class="nav-item">
            <a class="nav-link" href="#tab4" data-toggle="tab">Документация</a>
        </li>
        <?php endif;?>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-center" href="#tab5"
               data-toggle="tab">Отзывы<span class="nav-link__count">
                <? if (is_array($arResult['REVIEWS'])) {
                    echo count($arResult['REVIEWS']);
                } ?>
            </span></a>
        </li>
    </ul>
</div>
<div class="tab-content pb-40 pb-lg-80">
    <?php if (!empty($arResult['DETAIL_TEXT'])):?>
        <div class="tab-pane fade active show" id="tab1">
            <h2 class="h4 mb-16">Описание</h2>
            <div class="section__article-text pb-0">
                <p><?= $arResult['DETAIL_TEXT'] ?></p>
            </div>
        </div>
    <?php endif;?>
    <div class="tab-pane fade <?=(empty($arResult['DETAIL_TEXT']) ? 'active show' : '')?>" id="tab2">
        <h2 class="h3 mb-24">Технические характеристики</h2>
        <div class="row">
            <div class="col-auto col-xl-5 mb-32">
                <h3 class="h4 mb-16">Основные</h3>
                <table class="product-set props-table">
                    <?php foreach ($arResult["DISPLAY_PROPERTIES"] as $prop):
                        if ($prop['CODE'] == 'PICTURES') {
                            continue;   
                        }?>
                        <tr>
                            <td>
                                <div class="text-gray d-flex"><?=$prop['NAME']?></div>
                            </td>
                            <td><?=($prop['DISPLAY_VALUE'] ?: $prop['VALUE'])?></td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </div>
    </div>
    <?if (!empty($arResult['PROPERTIES']['CERTIFICATE']['VALUE'])):?>
        <div class="tab-pane fade" id="tab3">
            <h2 class="h4 mb-16">Сертификаты</h2>
            <?php foreach ($arResult['PROPERTIES']['CERTIFICATE']['VALUE'] as $i => $sid): ?>
                <a class="link link--file" href="<?= \CFile::GetPath($sid) ?>" download>
                    <svg class="icon icon-file mr-16">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-file"></use>
                    </svg>
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="pr-16"><?= $arResult['PROPERTIES']['CERTIFICATE']['DESCRIPTION'][$i] ?></div>
                    </div>
                </a>
                <br>
            <?php endforeach; ?>
        </div>
    <?php endif;?>

    <?php
    if ($arResult['DISPLAY_PROPERTIES']['DOCUMENT']['FILE_VALUE']['ID'] > 0) {
        $docFiles[] = $arResult['DISPLAY_PROPERTIES']['DOCUMENT']['FILE_VALUE'];
    } else {
        $docFiles = $arResult['DISPLAY_PROPERTIES']['DOCUMENT']['FILE_VALUE'];
    }
    if (!empty($docFiles)):?>
        <div class="tab-pane fade" id="tab4">
            <h4 class="mb-16">Документация</h4>
            <?php foreach ($docFiles as $doc): ?>
                <a class="link link--file" href="<?= $doc['SRC'] ?>" download>
                    <svg class="icon icon-file mr-16">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-file"></use>
                    </svg>
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="pr-16"><?= $doc['ORIGINAL_NAME'] ?></div>
                        <?php if ($doc['TIMESTAMP_X']):?>
                            <div class="text-gray p-md"><?= $doc['DESCRIPTION'] // $doc['TIMESTAMP_X']->format('d.m.Y') ?></div>
                        <?php endif;?>
                    </div>
                </a>
                <br>
            <?php endforeach; ?>
        </div>
    <?php endif;?>
    <div class="tab-pane fade" id="tab5">

        <?php if (is_array($arResult['REVIEWS'])) { 
            if (count($arResult['REVIEWS']) > 0) { ?>
                <h4 class="mb-16">Отзывы</h4>
                <div class="review border-0">
                    <div class="review__head">
                        <div class="fw-600 mb-4"><?= $arResult['REVIEWS'][0]['PROPS']['FIO']['VALUE'] ?></div>
                        <div class="p-md text-gray"><?= FormatDate("d.m.Y", MakeTimeStamp($arResult['REVIEWS'][0]['DATE_CREATE']) + CTimeZone::GetOffset()) ?></div>
                    </div>
                    <p class="review__text"><?=htmlspecialcharsBack($arResult["REVIEWS"][0]["PROPS"]["REVIEW"]["~VALUE"]["TEXT"])?></p>
                </div>
                <?php if (count($arResult['REVIEWS']) > 1): ?>
                    <div class="collapse" id="reviews-collapse">
                        <?php foreach ($arResult['REVIEWS'] as $key=>$review):?>
                            <?php if ($key == 0) {
                                continue;
                            }?>
                            <div class="review">
                                <div class="review__head">
                                    <div class="fw-600 mb-4"><?= $review['PROPS']['FIO']['VALUE'] ?></div>
                                    <div class="p-md text-gray"><?= FormatDate("d.m.Y", MakeTimeStamp($review['DATE_CREATE']) + CTimeZone::GetOffset()) ?></div>
                                </div>
                                <p class="review__text"><?= $review["PROPS"]["REVIEW"]["~VALUE"]["TEXT"] ?></p>
                            </div>
                        <?php endforeach;?>
                    </div>
                <?php endif; ?>

            <?php } 
        } ?>

        <div class="review align-items-center border-0">
            <?php if (is_array($arResult['REVIEWS'])) { 
                if (count($arResult['REVIEWS']) > 0) { ?>
                    <div class="review__head">
                        <a class="btn btn--border btn--sm btn--toggle-plus" href="#reviews-collapse" data-toggle="collapse"
                        data-show="Показать все отзывы (<?= count($arResult['REVIEWS']) ?>)" data-close="Показать меньше
                        отзывов"></a>
                    </div>
                <?php } 
            } ?>
            <div class="review__text">
                <a class="btn btn--black px-80" href="/local/ajax/popups/review.php?id=<?= $arResult['ID'] ?>"
                   data-fancybox
                   data-type="ajax"
                   data-touch="false">Написать отзыв</a>
            </div>
        </div>
    </div>
</div>