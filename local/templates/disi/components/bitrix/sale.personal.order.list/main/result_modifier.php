<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
foreach ($arResult['ORDERS'] as $key => &$order) {
    $resOrder = \Bitrix\Sale\Order::load($order['ORDER']['ID']);
    $propertyCollection = $resOrder->getPropertyCollection();
    $address = $propertyCollection->getAddress();
    if ($address != null) {
        $order["ADDRESS"] = $address->getValue();
    }

    $userId = $order['ORDER']['USER_ID'];
}

if ($arResult['ORDERS'] && $userId) {
    $arResult['COUNT'] = \Bitrix\Sale\OrderTable::getList([
        'filter' => [
            '=USER_ID' => $userId
        ]
    ])->getSelectedRowsCount();
}