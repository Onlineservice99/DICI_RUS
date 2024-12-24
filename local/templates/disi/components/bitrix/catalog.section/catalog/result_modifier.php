<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_banners_catalog');


global $USER;

$banners = [];
$currentPage = $_GET["PAGEN_1"] ? $_GET["PAGEN_1"] : 0;
$counter = $currentPage % 2;

$res = CIBlockElement::GetList(['sort' => 'ASC'], ['IBLOCK_ID' => $iblockId], false, false, ["*"]);
while ($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();
    $arFields['PROPS'] = $ob->GetProperties();

	// Конструкция ниже, делает следующее:
	// 1) Всегда отображает баннер, если пользователь не зарегистрирован
	// 2) В зависимости от страницы отображает четные и нечетные баннера
    if ($arFields['PROPS']['NEED_AUTH']['VALUE'] == 'Y' && $USER->IsAuthorized()) {
        continue;
	} else {
		if($arFields['PROPS']['NEED_AUTH']['VALUE'] == 'Y') {
			$banners[] = $arFields;
		} else if($currentPage > 1) {
			if($counter % 2 == 0) {
				$banners[] = $arFields;
			}
			$counter++;
		}
	}
}

$arResult['BANNERS'] = $banners;