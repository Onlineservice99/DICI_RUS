<?php

use electroset1\Content;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
?>
</article>
<div class="container bg-light">
    <?$ElementID = $APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "brands",
        Array(
            "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
            "DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
            "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
            "DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
            "PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
            "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
            "META_KEYWORDS" => $arParams["META_KEYWORDS"],
            "META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
            "BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
            "SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
            "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
            "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
            "SET_TITLE" => $arParams["SET_TITLE"],
            "MESSAGE_404" => $arParams["MESSAGE_404"],
            "SET_STATUS_404" => $arParams["SET_STATUS_404"],
            "SHOW_404" => $arParams["SHOW_404"],
            "FILE_404" => $arParams["FILE_404"],
            "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
            "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
            "ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
            "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
            "DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
            "DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
            "PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
            "PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
            "CHECK_DATES" => $arParams["CHECK_DATES"],
            "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
            "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
            "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
            "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
            "IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
            "USE_SHARE" => $arParams["USE_SHARE"],
            "SHARE_HIDE" => $arParams["SHARE_HIDE"],
            "SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
            "SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
            "SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
            "SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
            "ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
            'STRICT_SECTION_CHECK' => (isset($arParams['STRICT_SECTION_CHECK']) ? $arParams['STRICT_SECTION_CHECK'] : ''),
        ),
        $component
    );?>
</div>
<div class="section__overlay pt-20 mb-48 mb-md-72 mb-lg-128">
    <div class="container">
        <div class="row no-gutters flex-nowrap">
            <?
            $GLOBALS['brandFilter'] = ['=PROPERTY_BRAND' => $ElementID];
            $APPLICATION->IncludeComponent(
                "bitrix:catalog.smart.filter",
                "main",
                array(
                    "IS_BRAND_PAGE" => "Y",
                    "CACHE_GROUPS" => "N",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "A",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                    "CONVERT_CURRENCY" => "N",
                    "DISPLAY_ELEMENT_COUNT" => "Y",
                    "FILTER_NAME" => "arrFilter",
                    "FILTER_VIEW_MODE" => "vertical",
                    "HIDE_NOT_AVAILABLE" => "L",
                    "IBLOCK_ID" => \Bitrix\Main\Config\Option::get("meven.info", "iblock_catalog"),
                    "IBLOCK_TYPE" => "catalog",
                    "PAGER_PARAMS_NAME" => "arrPager",
                    "POPUP_POSITION" => "left",
                    "PREFILTER_HIDE" => "BRAND",
                    "PREFILTER_NAME" => "brandFilter",
                    "PRICE_CODE" => array(
                        0 => "BASE",
                    ),
                    "SAVE_IN_SESSION" => "N",
                    "SECTION_CODE" => "",
                    "SECTION_CODE_PATH" => "",
                    "SECTION_DESCRIPTION" => "-",
                    "SECTION_ID" => "",
                    "SECTION_TITLE" => "-",
                    "SEF_MODE" => "Y",
                    "SEF_RULE" => "",
                    "SMART_FILTER_PATH" => "",
                    "TEMPLATE_THEME" => "blue",
                    "XML_EXPORT" => "N",
                    "COMPONENT_TEMPLATE" => "catalog",
                    "INSTANT_RELOAD" => "Y",
                    "SHOW_ALL_WO_SECTION" => "Y",
                ),
                false,
                array('HIDE_ICONS' => 'Y')
            );?>

            <div class="section__product-list">
                <?php
                $APPLICATION->IncludeComponent(
                    "meven:sort",
                    "",
                    [
                    ],
                    $component,
                    false,
                );
                ?>
                <?php
                switch ($request->get('sort')):
                    case 'popular':
                        $sortField = "SORT";
                        $sortOrder = "ASC";
                        break;

                    case 'hit':
                        $sortField = "PROPERTY_LABEL_HIT";
                        $sortOrder = "DESC";
                        break;

                    case 'reccomend':
                        $sortField = "PROPERTY_LABEL_RECOMEND";
                        $sortOrder = "DESC";
                        break;

                    case 'sales':
                        $sortField = "PROPERTY_LABEL_ACTIONS";
                        $sortOrder = "DESC";
                        break;

                    case 'minprice':
                        $sortField = "catalog_PRICE_1";
                        $sortOrder = "ASC";
                        break;

                    case 'maxprice':
                        $sortField = "catalog_PRICE_1";
                        $sortOrder = "DESC";
                        break;

                    default:
                        $sortField = $arParams["ELEMENT_SORT_FIELD"];
                        $sortOrder = $arParams["ELEMENT_SORT_ORDER"];
                        break;
                endswitch;

                global $arrFilter;
                $arrFilter['SECTION_GLOBAL_ACTIVE'] = 'Y';

                global $USER;
                ?>
                <?
                $APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"catalog_with_brand",
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "/basket/",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPATIBLE_MODE" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_COMPARE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => $sortField,
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => $sortOrder,
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "arrFilter",
		"HIDE_NOT_AVAILABLE" => "L",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => \Bitrix\Main\Config\Option::get("meven.info","iblock_catalog"),
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_SUBSECTIONS" => "A",
		"LABEL_PROP" => array(
		),
		"LAZY_LOAD" => "N",
		"LINE_ELEMENT_COUNT" => "3",
		"LOAD_ON_SCROLL" => "N",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "",
		"META_KEYWORDS" => "",
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_LIMIT" => "5",
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "48",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE_MOBILE" => "",
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
		"RCM_TYPE" => "personal",
		"SECTION_CODE" => "",
		"SECTION_CODE_PATH" => "",
		"SECTION_ID" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SEF_MODE" => "N",
		"SEF_RULE" => "",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_FROM_SECTION" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "N",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "blue",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPONENT_TEMPLATE" => "catalog",
		"LABEL_PROP_MOBILE" => "",
		"LABEL_PROP_POSITION" => "top-left",
		"SHOW_ALL_WO_SECTION" => "Y",
		"AJAX_MODE" => "N",
		"AUTH" => $USER->IsAuthorized() ? 'Y' : 'N',
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);
                ?>
            </div>
        </div>
    </div>
</div>

<?php

$iblock = \Bitrix\Main\Config\Option::get("meven.info", "iblock_catalog");
$price = Content::getMinMaxPriceCatalog($iblock, [
    'SORT' => 'ASC',
    'BRAND_ID' => $ElementID,
    'SECTION_ID' => false,
    'PRODUCT_ID' => false
])['MIN']['PRICE'];

$iPropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arParams['IBLOCK_ID'], $ElementID);
$desc = $iPropValues->getValues()['ELEMENT_META_DESCRIPTION'];

$desc = str_replace('{_min_price_}', ceil($price), $desc);

$APPLICATION->SetPageProperty("description", $desc);
