<?php include_once $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php';

/**
 *
 * Добавить
 * ?action=ADD_TO_COMPARE_LIST&id=96
 *
 * Удалить
 * ?action=DELETE_FROM_COMPARE_LIST&id=96
 *
 */

/** Чтобы по умолчанию режим ajax был включен **/
$_REQUEST["ajax_action"] = "Y";

$APPLICATION->IncludeComponent(
    "bitrix:catalog.compare.list",
    "",
    Array(
        "ACTION_VARIABLE" => "action",
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "COMPARE_URL" => "/catalog/compare/",
        "COMPONENT_TEMPLATE" => ".default",
        "DETAIL_URL" => "",
        "IBLOCK_ID" => \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog'), // iblock_id
        "IBLOCK_TYPE" => "catalog", //iblock_type
        "NAME" => "COMPARE_LIST",
        "POSITION" => "top left",
        "POSITION_FIXED" => "Y",
        "PRODUCT_ID_VARIABLE" => "id"
    )
);

$items = reset($_SESSION['COMPARE_LIST']);

\Meven\Helper\Json::dumpSuccess(['count' => count($items['ITEMS'])]);