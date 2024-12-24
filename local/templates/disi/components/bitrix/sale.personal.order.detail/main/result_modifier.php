<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
\Bitrix\Main\Loader::includeModule('iblock');

$elementIds = [];
$basketItemIds = [];
foreach ($arResult['BASKET'] as $basketItem) {
    $elementIds[] = $basketItem['PRODUCT_ID'];
    $basketItemIds[$basketItem['PRODUCT_ID']] = $basketItem;
}

if (count($elementIds) > 0) {
    $brandIds = [];
    $brand = [];
    $element = CIBlockElement::GetList([], ['ID' => $elementIds, 'IBLOCK_ID' => \Bitrix\Main\Config\Option::get("meven.info", "iblock_catalog")], false, false, ['ID', 'IBLOCK_ID', 'PROPERTY_BRAND', 'PROPERTY_ARTICLE']);
    while ($arProps = $element->GetNext()) {
        $basketItemIds[$arProps['ID']]['PROPS']['ARTICLE'] = $arProps['PROPERTY_ARTICLE_VALUE'];
        $basketItemIds[$arProps['ID']]['PROPS']['BRAND'] = $arProps['PROPERTY_BRAND_VALUE'];
        $brandIds[] = $arProps['PROPERTY_BRAND_VALUE'];
    }
    if (count($brandIds) > 0) {
        $brandDb = CIBlockElement::GetList([], ['ID' => $brandIds, 'IBLOCK_ID' => \Bitrix\Main\Config\Option::get("meven.info", "iblock_brands")], false, false, ['ID', 'IBLOCK_ID', 'NAME']);
        while ($arBrand = $brandDb->GetNext()) {
            $brand[$arBrand['ID']] = $arBrand['NAME'];
        }
    }

    $arResult['BASKET'] = $basketItemIds;
    $arResult['BRANDS'] = $brand;
}
