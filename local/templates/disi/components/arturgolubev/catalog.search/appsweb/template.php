<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 
$this->setFrameMode(true);


?>

	</div> <? /* end container */?>
<div class="container">
<?
CModule::IncludeModule("arturgolubev.smartsearch");
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();


$bx_search_limit = COption::GetOptionString('search','max_result_size',50);
$arElements = $APPLICATION->IncludeComponent(
	"arturgolubev:search.page",
	"catalog",
	Array(
		"RESTART" => $arParams["RESTART"],
		"NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"],
		"USE_LANGUAGE_GUESS" => $arParams["USE_LANGUAGE_GUESS"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
		"arrFILTER" => array("iblock_".$arParams["IBLOCK_TYPE"]),
		"arrFILTER_iblock_".$arParams["IBLOCK_TYPE"] => array($arParams["IBLOCK_ID"]),
		"USE_TITLE_RANK" => "Y",
		"DEFAULT_SORT" => "rank",
		"FILTER_NAME" => "",
		"SHOW_WHERE" => "N",
		"arrWHERE" => array(),
		"SHOW_WHEN" => "N",
		"PAGE_RESULT_COUNT" => $bx_search_limit,
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "N",
		"INPUT_PLACEHOLDER" => $arParams["INPUT_PLACEHOLDER"],
		"SHOW_HISTORY" => $arParams["SHOW_HISTORY"],
	),
	$component,
	array('HIDE_ICONS' => 'Y')
);

?>
</div>
<?
if (!empty($arElements) && is_array($arElements)) {?>
	<div class="section__overlay">
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
	foreach($arElements as $k=>$v){
		if(substr($v, 0, 1) == 'S')
			unset($arElements[$k]);
	}
	
	$arElements = array_values($arElements);


	
	if($arParams["ELEMENT_SORT_FIELD"] == 'rank'){
		$arParams["ELEMENT_SORT_FIELD"] = "ID";
		$arParams["ELEMENT_SORT_ORDER"] = $arElements;
	}
	
	global $searchFilter;
	$searchFilter = array(
		"ID" => $arElements,
	);
if(\Arturgolubev\Smartsearch\Unitools::checkModuleVersion('iblock', '18.6.200')){
		$APPLICATION->IncludeComponent(
			"bitrix:catalog.smart.filter",
			"main_new",
			array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				//"SECTION_ID" => $arCurSection['ID'],
				"PREFILTER_NAME" => 'searchFilter',
				"FILTER_NAME" => 'searchFilter',
				"PRICE_CODE" => $arParams["~PRICE_CODE"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SAVE_IN_SESSION" => "N",
				"FILTER_VIEW_MODE" => "VERTICAL",
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
				"SHOW_ALL_WO_SECTION" => 'Y',
			),
			$component,
			array('HIDE_ICONS' => 'Y')
		);
	}?>

	<div class="section__product-list">
		<?
		//echo '<pre>' . print_r($arCurSection, 1) . '</pre>' . __FILE__ . ' # ' . __LINE__;
		//echo '<pre>' . print_r($searchFilter, 1) . '</pre>' . __FILE__ . ' # ' . __LINE__;
		?>
		<?php
		$sortResult = $APPLICATION->IncludeComponent(
			 "vogood:sort",
			 "",
			 [
				  "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
				  "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"]
			 ],
			 $component,
			 false
    );
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
<?
	if($sortResult["sortField"] == 'SORT'){
		$sortResult["sortField"] = 'ID';
		$sortResult["sortOrder"] = array_values($arElements);
	}
	
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"catalog",
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			
//			"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
//			"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],

			"ELEMENT_SORT_FIELD" => $sortResult['sortField'],
			"ELEMENT_SORT_ORDER" => $sortResult['sortOrder'],
			
			/* For search rank sort for bitrix 19.0.0+ */
			// "ELEMENT_SORT_FIELD" => "ID",
			// "ELEMENT_SORT_ORDER" => array_values($arElements),
			
			"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
			"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
			
			"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
			"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
			"PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
			"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
			"SECTION_URL" => $arParams["SECTION_URL"],
			"DETAIL_URL" => $arParams["DETAIL_URL"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
			"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
			"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
			"HIDE_NOT_AVAILABLE_OFFERS" => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
			"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE" => $arParams["PAGER_TITLE"],
			"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
			"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
			"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
			"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
			"FILTER_NAME" => "searchFilter",
			"SECTION_ID" => "",
			"SECTION_CODE" => "",
			"SECTION_USER_FIELDS" => array(),
			"INCLUDE_SUBSECTIONS" => "Y",
			"SHOW_ALL_WO_SECTION" => "Y",
			"META_KEYWORDS" => "",
			"META_DESCRIPTION" => "",
			"BROWSER_TITLE" => "",
			"ADD_SECTIONS_CHAIN" => "N",
			"SET_TITLE" => "N",
			"SET_STATUS_404" => "N",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "N",
			'AUTH' => $auth,
		),
		 $arResult["THEME_COMPONENT"],
		 array('HIDE_ICONS' => 'N')
	);
?>
	</div>
	</div>
</div>
</div>
</div>
<? }elseif (is_array($arElements)) { ?>
    <div class="container not-found"><?= GetMessage("CT_BCSE_NOT_FOUND"); ?></div>
<? } ?>