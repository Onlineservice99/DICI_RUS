<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult['PICTURES'] = [];
$arResult['SMALL_PICTURES'] = [];

if (!empty($arResult['DETAIL_PICTURE']['SRC'])) {
    $arResult['PICTURES'][] = $arResult['DETAIL_PICTURE']['SRC'];
    $arResult['SMALL_PICTURES'][] = CFile::ResizeImageGet(
        $arResult['DETAIL_PICTURE'],
        ['width' => 100, 'height' => 100]
    );
}
if (!empty($arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'])) {
    if (count($arResult['DISPLAY_PROPERTIES']['PICTURES']['VALUE']) > 1) {
        foreach ($arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'] as $val) {
            if (!empty($val['SRC']) && $val['FILE_SIZE'] > 0) {
                $arResult['PICTURES'][] = $val['SRC'];
            }
            if (!empty($val) && $val['FILE_SIZE'] > 0) {
                $arResult['SMALL_PICTURES'][] = CFile::ResizeImageGet(
                    $val,
                    ['width' => 100, 'height' => 100],
                );
            }
        }
    } else {
        if (!empty($arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE']['SRC']) && $arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE']['FILE_SIZE'] > 0) {
            $arResult['PICTURES'][] = $arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE']['SRC'];
        }
        if (!empty($arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE']) && $arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE']['FILE_SIZE'] > 0) {
            $arResult['SMALL_PICTURES'][] = CFile::ResizeImageGet(
                $arResult['DISPLAY_PROPERTIES']['PICTURES']['FILE_VALUE'],
                ['width' => 100, 'height' => 100],
            );
        }
    }
    
} else {
    if (is_array($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
        if (count($arResult['PROPERTIES']['MORE_PHOTO']['VALUE']) > 1) {
            foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $val) {
                if (!empty($val)) {
                    $arResult['PICTURES'][] = CFile::GetPath($val);
                    $arResult['SMALL_PICTURES'][] = CFile::ResizeImageGet(
                        $val,
                        ['width' => 100, 'height' => 100],
                    );
                }
            }
        } else {
            if (!empty($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
                $arResult['PICTURES'][] = CFile::GetPath($arResult['PROPERTIES']['MORE_PHOTO']['VALUE']);
                $arResult['SMALL_PICTURES'][] = CFile::ResizeImageGet(
                    $arResult['PROPERTIES']['MORE_PHOTO']['VALUE'],
                    ['width' => 100, 'height' => 100],
                );
    
            }
        }
    } else {
        if (!empty($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
            $arResult['PICTURES'][] = CFile::GetPath($arResult['PROPERTIES']['MORE_PHOTO']['VALUE']);
            $arResult['SMALL_PICTURES'][] = CFile::ResizeImageGet(
                $arResult['PROPERTIES']['MORE_PHOTO']['VALUE'],
                ['width' => 100, 'height' => 100],
            );

        }
    }

}


$iblockReview = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_review');
$dbResult = CIBlockElement::GetList(
    [],
    ['IBLOCK_ID' => $iblockReview, 'PROPERTY_ELEMENT' => $arResult['ID'], 'ACTIVE' => 'Y'],
    false,
    false,
    ['IBLOCK_ID', 'ID', 'DATE_CREATE']
);

while ($review = $dbResult->GetNextElement()) {
    $fields = $review->GetFields();
    $fields['PROPS'] = $review->GetProperties();

    $arResult['REVIEWS'][] = $fields;
}


$arResult['ACTIONS'] = [];
$iblockPromotions = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_promotions');
$dbResult = CIBlockElement::GetList(
    [],
    ['IBLOCK_ID' => $iblockPromotions, 'PROPERTY_ITEMS' => $arResult['ID'], 'ACTIVE' => 'Y'],
    false,
    false,
    ['IBLOCK_ID', 'ID', 'NAME', "DETAIL_TEXT"]
);

if ($action = $dbResult->GetNextElement()) {
    $fields = $action->GetFields();
    $arResult['ACTIONS'] = $fields;
}
try {
    $properties = \Meven\Helper\Helper::getRussvetProperties($arResult['ID']);
    if (count($properties) > 0) {
        $arResult['DISPLAY_PROPERTIES'] = array_merge($arResult['DISPLAY_PROPERTIES'], $properties);
    }
} catch (\Throwable $th) {
    //throw $th;
}
$arResult['PRICE_DISCOUNT'] = Meven\Sale\BasketDiscount::getDiscountPriceByProduct($arParams['ELEMENT_ID']);

$cp = $this->__component; // объект компонента
if (is_object($cp))
{
    $this->__component->SetResultCacheKeys(['REVIEWS', 'DETAIL_PICTURE', 'MIN_PRICE', 'PRICE_DISCOUNT']);
}

$resStore = \Bitrix\Catalog\StoreProductTable::getlist(array(
    'filter' => array("=PRODUCT_ID"=> $arResult['ID'],'=STORE.ACTIVE'=>'Y'),
    'select' => array('AMOUNT','STORE_ID','STORE_TITLE' => 'STORE.TITLE'),
 ));
$arResult['STORE'] = [];
while($arStoreProduct = $resStore->fetch())
{
    $arResult['STORE'][$arStoreProduct['STORE_ID']] = $arStoreProduct;
}