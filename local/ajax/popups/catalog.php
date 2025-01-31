<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "menu",
    array(
        "ALLOW_MULTI_SELECT" => "N",
        "CHILD_MENU_TYPE" => "catalog",
        "DELAY" => "N",
        "MAX_LEVEL" => "4",
        "MENU_CACHE_GET_VARS" => array(
        ),
        "MENU_CACHE_TIME" => "3600000",
        "MENU_CACHE_TYPE" => "Y",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "ROOT_MENU_TYPE" => "catalog",
        "USE_EXT" => "Y",
        "COMPONENT_TEMPLATE" => "main-catalog",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog'),
        "SECTION_ID" => "",
        "SECTION_CODE" => "",
        "COUNT_ELEMENTS" => "N",
        "COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
        "TOP_DEPTH" => "4",
        "SECTION_FIELDS" => array(
            0 => "",
            1 => "",
        ),
        "SECTION_USER_FIELDS" => array(
            0 => "",
            1 => "",
        ),
        "FILTER_NAME" => "",
        "SECTION_URL" => "",
        "CACHE_TYPE" => "Y",
        "CACHE_TIME" => "86400",
        "CACHE_GROUPS" => "Y",
        "CACHE_FILTER" => "Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "VISIBLE_SUBSECTIONS" => "5"
    )
);
