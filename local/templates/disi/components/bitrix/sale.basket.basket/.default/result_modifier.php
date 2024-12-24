<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Grid\Declension;

$iblockBrand = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_brands');

$arResult['HAS_ZERO_AVAILIBLE'] = false;

if (count($arResult['ITEMS']) > 0) {
    foreach ($arResult['ITEMS']['AnDelCanBuy'] as $key=>$item) {
        $ids[] = $item['PRODUCT_ID'];
        $arResult['ITEMS'][$key]['NOT_AVAILABLE'] = false;

        if ($item['QUANTITY'] > $item['AVAILABLE_QUANTITY']) {
            $arResult['ITEMS']['AnDelCanBuy'][$key]['NOT_AVAILABLE'] = true;
            $arResult['HAS_ZERO_AVAILIBLE'] = true;
        }
    }

    $iblock = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog');
    $elems = [];
    $brandsIds = [];

    $dbElems = CIBlockElement::GetList(
        [],
        ['IBLOCK_ID' => $iblock, 'ID' => $ids],
        false,
        false,
        ['IBLOCK_ID', 'ID', 'PREVIEW_PICTURE', 'DETAIL_PAGE_URL', 'PROPERTY_ARTICLE', 'PROPERTY_BRAND']
    );
    while ($el = $dbElems->GetNextElement()) {
        $fields = $el->GetFields();
        if ($fields['PROPERTY_BRAND_VALUE'] > 0) {
            $brandsIds[] = $fields['PROPERTY_BRAND_VALUE'];
        }
        $elems[$fields['ID']] = $fields;
    }

    $brands = [];
    $dbElems = CIBlockElement::GetList(
        [],
        ['IBLOCK_ID' => $iblockBrand, 'ID' => $brandsIds],
        false,
        false,
        ['IBLOCK_ID', 'ID', 'NAME']
    );
    while ($el = $dbElems->GetNextElement()) {
        $fields = $el->GetFields();
        $brands[$fields['ID']] = $fields['NAME'];
    }

    $arResult['BRANDS'] = $brands;
    $arResult['ELEMS'] = $elems;
}


$wordItems = new Declension('товар', 'товара', 'товаров');
$arResult['WORD_ITEMS'] = $wordItems->get(count($arResult['ITEMS']['AnDelCanBuy']));

