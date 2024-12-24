<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

use electroset1\Content;

$this->setFrameMode(true);

if (!isset($arParams['FILTER_VIEW_MODE']) || (string)$arParams['FILTER_VIEW_MODE'] == '')
    $arParams['FILTER_VIEW_MODE'] = 'VERTICAL';
$arParams['USE_FILTER'] = (isset($arParams['USE_FILTER']) && $arParams['USE_FILTER'] == 'Y' ? 'Y' : 'N');

$isVerticalFilter = ('Y' == $arParams['USE_FILTER'] && $arParams["FILTER_VIEW_MODE"] == "VERTICAL");
$isSidebar = ($arParams["SIDEBAR_SECTION_SHOW"] == "Y" && isset($arParams["SIDEBAR_PATH"]) && !empty($arParams["SIDEBAR_PATH"]));
$isFilter = ($arParams['USE_FILTER'] == 'Y');

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if ($isFilter)
{
    $arFilter = array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ACTIVE" => "Y",
        "GLOBAL_ACTIVE" => "Y",
    );
    if (0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
        $arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
    elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"])
        $arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];

    $obCache = new CPHPCache();
    if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
    {
        $arCurSection = $obCache->GetVars();
    }
    elseif ($obCache->StartDataCache())
    {
        $arCurSection = array();
        if (Loader::includeModule("iblock"))
        {
            $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));

            if(defined("BX_COMP_MANAGED_CACHE"))
            {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache("/iblock/catalog");

                if ($arCurSection = $dbRes->Fetch())
                    $CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);

                $CACHE_MANAGER->EndTagCache();
            }
            else
            {
                if(!$arCurSection = $dbRes->Fetch())
                    $arCurSection = array();
            }
        }
        $obCache->EndDataCache($arCurSection);
    }
    if (!isset($arCurSection))
        $arCurSection = array();
}
?>
    <script>
        var quantity;
        var name;
        var id;
        var price;

        function setPrice(elemPrice) {
            price = elemPrice;
        }

        function getAllCatalogElements() {
            var aElements = new Array();
            var id;

            myElement = document.querySelectorAll('.card');

            for (var i = 0; i < myElement.length; i++) {
                id = myElement[i].getAttribute('data-element-id');
                name = $( myElement[i] ).find('.card__title')[0].textContent;
                aElements.push({
                    'name': name,
                    'id': id,
                    'category': categoryFromBreadcrumbs(),
                    'list': getListName(),
                    'position': i + 1,
                });
            }
            return aElements;
        }

        $('div.btn-add-basket').click(function(){
                quantity = $( this ).parent().find('.js-spin-count').val(); // Этот атрибут прописан в html
                name = $( this ).closest('.card').find('.card__title')[0].textContent;
                id = $( this ).closest('.card').attr('data-element-id');

                ecommClickToBasket();
        }
        );

        function ecommClick(id) {
            aElements = getAllCatalogElements();
            let result = aElements.filter(function(el){
                return el.id.indexOf(id) > -1;//fieldName - поле по которому нужно фильтровать
            });
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                'ecommerce' : {
                    'click' : {
                        'actionField' : {
                            'list' : result[0]['list']
                        },
                        'products' : [{
                            'name' : result[0]['name'], // обязательное
                            'id' : result[0]['id'], // обязательное
                            'category' : result[0]['category'],
                            'position' : result[0]['position'] // Позиция в списке (целое число)), обязательное
                        }]
                    }
                },
                'event' : 'ecommerceClick'
            });
        }
        function ecommClickToBasket() {
            window.dataLayer = window.dataLayer || []; window.dataLayer.push({
                'ecommerce' : {
                    'add' : {
                        'products' : [{
                            'name' : name, // обязательное
                            'id' : id, // обязательное
                            'price' : price,
                            'category' : categoryFromBreadcrumbs(),
                            'quantity' : quantity // количество
                        }]
                    }
                },
                'event' : 'ecommerceAdd'
            });
        }

        let aEcommElements = getAllCatalogElements();
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'ecommerce' : {
                'impressions' :
                aEcommElements,
            }, 'event' : 'ecommerceImpressions'
        });
    </script>
    <div class="text-center text-sm-left d-lg-none mb-16 mb-sm-40">
        <a class="btn btn--sm btn--border" href="#catalog-categories" data-toggle="collapse">
            <svg class="icon icon-apps text-red mr-8">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-apps"></use>
            </svg>Подкатегории раздела
        </a>
    </div>
    <div class="collapse d-lg-block" id="catalog-categories">
        <?php
        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section.list",
            "slider",
            [
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : '')
            ],
            $component,
            ($arParams["SHOW_TOP_ELEMENTS"] !== "N" ? array("HIDE_ICONS" => "Y") : array())
        );
        ?>
    </div>
    </div>
    <div class="section__overlay" id="category_content">
        <div class="container">
            <?php $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "catalog-feature",
                array(
                    "COMPONENT_TEMPLATE" => "main-brands",
                    "IBLOCK_TYPE" => "adds",
                    "IBLOCK_ID" => \Bitrix\Main\Config\Option::get("meven.info","iblock_features_catalog"),
                    "NEWS_COUNT" => "3",
                    "SORT_BY1" => "SORT",
                    "SORT_ORDER1" => "ASC",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER2" => "ASC",
                    "FILTER_NAME" => "",
                    "FIELD_CODE" => array(
                        0 => "",
                        1 => "",
                    ),
                    "PROPERTY_CODE" => array(
                        0 => "PICTURE",
                        1 => "",
                    ),
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "N",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "36000000",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "N",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "SET_TITLE" => "N",
                    "SET_BROWSER_TITLE" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "INCLUDE_SUBSECTIONS" => "N",
                    "STRICT_SECTION_CHECK" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "PAGER_TITLE" => "Новости",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "SET_STATUS_404" => "N",
                    "SHOW_404" => "N",
                    "MESSAGE_404" => ""
                ),
                false
            );?>

            <div class="row no-gutters flex-nowrap">
								<?
								$filterComponentTemplate = 'main_new';
								/*if($_REQUEST['appsweb_development'] == 'Y') {
									$filterComponentTemplate = 'main';
								}*/
								?>
                <?php
                $APPLICATION->IncludeComponent(
                    "bitrix:catalog.smart.filter",
	                  $filterComponentTemplate,
                    [
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "SECTION_ID" => $arCurSection['ID'],
                        "FILTER_NAME" => $arParams["FILTER_NAME"],
                        "PRICE_CODE" => $arParams["~PRICE_CODE"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "SAVE_IN_SESSION" => "N",
                        "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                        "XML_EXPORT" => "N",
                        "SECTION_TITLE" => "NAME",
                        "SECTION_DESCRIPTION" => "DESCRIPTION",
                        'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                        "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                        "SEF_MODE" => $arParams["SEF_MODE"],
                        "SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
                        "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                        "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                        "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                    ],
                    $component,
                    ($arParams["SHOW_TOP_ELEMENTS"] !== "N" ? array("HIDE_ICONS" => "Y") : array())
                );
                ?>
                <div class="section__product-list">
		                <?
		                $sortComponent = "vogood:sort";
		                /*if($_REQUEST['appsweb_development'] == 'Y') {
			                $sortComponent = "vogood:sort";
		                }*/
		                ?>
                    <?php
                    $sortResult = $APPLICATION->IncludeComponent(
	                     $sortComponent,
                        "",
                        [
	                         "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
	                         "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"]
                        ],
                        $component,
                        false
                    );
                    /*if($_REQUEST['appsweb_development'] == 'Y') {
	                    //echo '<pre>' . print_r($sortResult, 1) . '</pre>' . __FILE__ . ' # ' . __LINE__;
	                    //echo '<pre>' . print_r($_COOKIE['sort_type'], 1) . '</pre>' . __FILE__ . ' # ' . __LINE__;
                    }*/

                    ?>
                    <?php
                    /*switch ($request->get('sort')):
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
                    endswitch;*/

                    global $USER;
                    $auth = 'N';
                    if ($USER->IsAuthorized()) {
                        $auth = 'Y';
                    }
                    ?>
                    <div class="ajax-container">
                        <?php $intSectionID = $APPLICATION->IncludeComponent(
                            "bitrix:catalog.section",
                            "catalog",
                            array(
								"ADDITIONAL_ITEM_CLASSES" => "card--row",
                                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
//                                "ELEMENT_SORT_FIELD" => $sortField,
//                                "ELEMENT_SORT_ORDER" => $sortOrder,
                                "ELEMENT_SORT_FIELD" => $sortResult['sortField'],
                                "ELEMENT_SORT_ORDER" => $sortResult['sortOrder'],
                                "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                                "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                                "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                                "PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
                                "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
                                "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
                                "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
                                "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                                "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
                                "BASKET_URL" => $arParams["BASKET_URL"],
                                "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                                "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                                "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                                "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                                "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                                "FILTER_NAME" => $arParams["FILTER_NAME"],
                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                                "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                "SET_TITLE" => $arParams["SET_TITLE"],
                                "MESSAGE_404" => $arParams["~MESSAGE_404"],
                                "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                                "SHOW_404" => $arParams["SHOW_404"],
                                "FILE_404" => $arParams["FILE_404"],
                                "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                                "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
                                "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                                "PRICE_CODE" => $arParams["~PRICE_CODE"],
                                "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                                "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

                                "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                                "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                                "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                                "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                                "PRODUCT_PROPERTIES" => (isset($arParams["PRODUCT_PROPERTIES"]) ? $arParams["PRODUCT_PROPERTIES"] : []),

                                "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                                "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                                "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                                "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                                "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                                "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                                "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                                "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
                                "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
                                "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                                "LAZY_LOAD" => $arParams["LAZY_LOAD"],
                                "MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
                                "LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],

                                "OFFERS_CART_PROPERTIES" => (isset($arParams["OFFERS_CART_PROPERTIES"]) ? $arParams["OFFERS_CART_PROPERTIES"] : []),
                                "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                                "OFFERS_PROPERTY_CODE" => (isset($arParams["LIST_OFFERS_PROPERTY_CODE"]) ? $arParams["LIST_OFFERS_PROPERTY_CODE"] : []),
                                "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                                "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                                "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                                "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                                "OFFERS_LIMIT" => (isset($arParams["LIST_OFFERS_LIMIT"]) ? $arParams["LIST_OFFERS_LIMIT"] : 0),

                                "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                                "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                                "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                                "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                                "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
                                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                                'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                                'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

                                'LABEL_PROP' => $arParams['LABEL_PROP'],
                                'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
                                'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
                                'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                                'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                                'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
                                'PRODUCT_ROW_VARIANTS' => $arParams['LIST_PRODUCT_ROW_VARIANTS'],
                                'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
                                'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
                                'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
                                'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
                                'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

                                'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                                'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : []),
                                'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                                'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                                'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
                                'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                                'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                                'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
                                'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
                                'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
                                'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
                                'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
                                'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
                                'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
                                'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
                                'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
                                'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

                                'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
                                'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
                                'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

                                'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                                "ADD_SECTIONS_CHAIN" => "N",
                                'ADD_TO_BASKET_ACTION' => $basketAction,
                                'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                                'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
                                'COMPARE_NAME' => $arParams['COMPARE_NAME'],
                                'USE_COMPARE_LIST' => 'Y',
                                'AUTH' => $auth,
                                'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
                                'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
                                'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : '')
                            ),
                            $component
                        );?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$resSection = CIBlockSection::GetByID($arCurSection['ID']);
if($arSection = $resSection->GetNext()) {
    $sectionDetailImg = CFile::GetPath($arSection["DETAIL_PICTURE"]);
}
?>
<?php if(!empty($arSection["DESCRIPTION"])):?>
    <section class="section bg-light pt-48">
        <div class="container">
            <div class="seo-block">
                <?php if(!empty($sectionDetailImg)):?>
                    <img class="d-none d-sm-block" src="<?=$sectionDetailImg?>" alt="<?=$arSection["NAME"]?>">
                    <img class="d-sm-none" src="<?=$sectionDetailImg?>" alt="<?=$arSection["NAME"]?>">
                <?php endif;?>
                <div class="seo-block__text">
                    <?=$arSection["DESCRIPTION"]?>
                </div>
            </div>
        </div>
    </section>
<?php endif;?>

<?php

$iblock = \Bitrix\Main\Config\Option::get("meven.info", "iblock_catalog");
$price = Content::getMinMaxPriceCatalog($iblock, [
  'SORT' => 'ASC',
  'BRAND_ID' => false,
  'SECTION_ID' => $arCurSection['ID'],
  'PRODUCT_ID' => false
])['MIN']['PRICE'];

$iPropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams['IBLOCK_ID'], $arCurSection['ID']);
$title = $iPropValues->getValues()['SECTION_META_TITLE'];
$desc = $iPropValues->getValues()['SECTION_META_DESCRIPTION'];

$title = str_replace('{_min_price_}', ceil($price), $title);
$desc = str_replace('{_min_price_}', ceil($price), $desc);

$APPLICATION->SetPageProperty("title", $title);
$APPLICATION->SetPageProperty("description", $desc);

