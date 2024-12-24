<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
foreach ($arResult['ITEMS'] as $key => $arItem){
    $arResult['ITEMS'][$key]["DATE_START"] = FormatDate("d F Y", MakeTimeStamp($arItem["FIELDS"]["DATE_ACTIVE_FROM"]));
    $arResult['ITEMS'][$key]["DATE_END"] = FormatDate("d F Y", MakeTimeStamp($arItem["FIELDS"]["DATE_ACTIVE_TO"]));
}