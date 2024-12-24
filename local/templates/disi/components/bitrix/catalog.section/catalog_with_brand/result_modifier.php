<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_banners_catalog');


global $USER;

$banners = [];

$res = CIBlockElement::GetList(['sort' => 'ASC'], ['IBLOCK_ID' => $iblockId], false, false, ["*"]);
while ($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();
    $arFields['PROPS'] = $ob->GetProperties();

    if ($arFields['PROPS']['NEED_AUTH']['VALUE'] == 'Y' && $USER->IsAuthorized()) {
        continue;
    }

    $banners[] = $arFields;
}

$arResult['BANNERS'] = $banners;
