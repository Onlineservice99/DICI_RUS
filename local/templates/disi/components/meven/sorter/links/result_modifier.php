<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['SORTING_TYPES'] = [];
$arSorts = [
    1 => "ACTIVE_FROM",
    2 => "ACTIVE_TO",
    //3 => "NAME",
    //4 => "PRICE"
];

if (in_array("ACTIVE_FROM", $arSorts)) {
    $arResult['SORTING_TYPES']["ACTIVE_FROM"] = ["ACTIVE_FROM", "desc"];
}

if (in_array("ACTIVE_TO", $arSorts)) {
    $arResult['SORTING_TYPES']["ACTIVE_TO"] = ["ACTIVE_TO", "asc"];
}

if (in_array("NAME", $arSorts)) {
    $arResult['SORTING_TYPES']["NAME"] = ["NAME", "asc"];
}

if (in_array("PRICE", $arSorts)) {
    $arSortPrices = "BASE";
    $price = CCatalogGroup::GetList([], ["NAME" => $arSortPrices], false, false, ["ID", "NAME"])->GetNext();
    $arResult['SORTING_TYPES']["PRICE"] = ["CATALOG_PRICE_" . $price["ID"], "desc"];
}

$arResult['SORT'] = "SORT";
if ((array_key_exists("sort", $_REQUEST) && array_key_exists(ToUpper($_REQUEST["sort"]), $arResult['SORTING_TYPES'])) || (array_key_exists("sort", $_SESSION) && array_key_exists(ToUpper($_SESSION["sort"]), $arResult['SORTING_TYPES'])) || $arParams["ELEMENT_SORT_FIELD"]) {
    if ($_REQUEST["sort"]) {
        $arResult['SORT'] = ToUpper($_REQUEST["sort"]);
        $_SESSION["sort"] = ToUpper($_REQUEST["sort"]);
    } elseif ($_SESSION["sort"]) {
        $arResult['SORT'] = ToUpper($_SESSION["sort"]);
    } else {
        $arResult['SORT'] = ToUpper($arParams["ELEMENT_SORT_FIELD"]);
    }
}

$_SESSION['SORT_FIELD'] = $arResult['SORT'];

$arResult['SORT_ORDER'] = $arResult['SORTING_TYPES'][$arResult['SORT']][1];
if ((array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), ["asc", "desc"])) || (array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), ["asc", "desc"])) || $arParams["ELEMENT_SORT_ORDER"]) {
    if ($_REQUEST["order"]) {
        $arResult['SORT_ORDER'] = $_REQUEST["order"];
        $_SESSION["order"] = $_REQUEST["order"];
    } elseif ($_SESSION["order"]) {
        $arResult['SORT_ORDER'] = $_SESSION["order"];
    } else {
        $arResult['SORT_ORDER'] = ToLower($arParams["ELEMENT_SORT_ORDER"]);
    }
}

$_SESSION['SORT_ORDER'] = $arResult['SORT_ORDER'];