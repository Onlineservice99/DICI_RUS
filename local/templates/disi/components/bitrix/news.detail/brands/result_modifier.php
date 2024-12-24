<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arFilter = [
    "IBLOCK_ID" => \Bitrix\Main\Config\Option::get("meven.info", "iblock_catalog"),
    "PROPERTY_BRAND" => $arResult["ID"],
    "SECTION_GLOBAL_ACTIVE" => 'Y'
];
$rsBanners = CIBlockElement::GetList([], $arFilter, false, false, false);
$arResult["PRODUCTS_COUNT"] = $rsBanners->SelectedRowsCount();