<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
foreach ($arResult['ITEMS'] as $key => $arItem){
    $resSection = CIBlockSection::GetByID($arItem["IBLOCK_SECTION_ID"]);
    if($arSection = $resSection->GetNext()) {
        $arResult['ITEMS'][$key]["SECTION_NAME"] = $arSection['NAME'];
        $arResult['ITEMS'][$key]["SECTION_CODE"] = $arSection['CODE'];
    }
}