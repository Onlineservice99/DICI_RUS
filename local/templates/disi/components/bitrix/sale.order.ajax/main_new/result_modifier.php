<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\Page\Asset;
$properties = [];
foreach ($arResult['JS_DATA']['ORDER_PROP']['properties'] as $prop) {
    $properties[$prop['PERSON_TYPE_ID']][$prop['CODE']] = $prop['ID'];
}
$idRussvetStore = !empty(\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet")) ? (int)\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet") : 1;
$showPopupArray = [];
$arResult['PROPS'] = $properties;
$arResult['STORE'] = $arResult['STORE_DAY'] = [];
foreach ($arResult['JS_DATA']['GRID']["ROWS"] as $key => $arBasket) {
    $resStore = \Bitrix\Catalog\StoreProductTable::getlist(array(
        'filter' => array("=PRODUCT_ID"=> $arBasket["data"]["PRODUCT_ID"],'=STORE.ACTIVE'=>'Y'),
        'select' => array('AMOUNT','STORE_ID','STORE_TITLE' => 'STORE.TITLE'),
     ));
    while($arStoreProduct = $resStore->fetch())
    {
        if(isset($arStoreProduct['STORE_ID']) && $arStoreProduct['STORE_ID'] == $idRussvetStore) {
            if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "russvet_count_shipment"))) {
                    $arResult['STORE'][(int)$arBasket["data"]["PRODUCT_ID"]]['text'] = \Bitrix\Main\Config\Option::get("meven.info", "russvet_count_shipment");
                    $arResult['STORE_DAY'][$arStoreProduct['STORE_ID']] = true;
                    $showPopupArray[0] = true;
                 } 
         } else {
            if (!empty(\Bitrix\Main\Config\Option::get("meven.info", "other_count_shipment"))) {
                $arResult['STORE'][(int)$arBasket["data"]["PRODUCT_ID"]]['text'] = \Bitrix\Main\Config\Option::get("meven.info", "other_count_shipment");
                $arResult['STORE_DAY'][$arStoreProduct['STORE_ID']] = true;
                $showPopupArray[1] = true;
            }
        }        
    }
}

$arResult['STORE_RESULT_MODAL'] = count($showPopupArray) >= 2 ? true : false;
Asset::getInstance()->addString('<script>let orderInfoStores = ' . CUtil::PhpToJSObject($arResult['STORE']) . ';
let storeResultModal = ' . CUtil::PhpToJSObject($arResult['STORE_RESULT_MODAL']) . ';
let orderModal = ' . CUtil::PhpToJSObject(\Bitrix\Main\Config\Option::get("meven.info", "order_text_stores_modal")) . ';</script>');

?>



