<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$date = new DateTime();
$arResult['MIN_YEAR'] = $date->format('Y');
$arResult['MAX_YEAR'] = $date->format('Y');

foreach ($arResult['ORDERS'] as $key => &$order) {
    $resOrder = \Bitrix\Sale\Order::load($order['ORDER']['ID']);
    $propertyCollection = $resOrder->getPropertyCollection();
    $address = $propertyCollection->getAddress();
    $order["ADDRESS"] = $address ? $address->getValue() : '';

    $yearDate = DateTime::createFromFormat("d.m.Y", $order["ORDER"]["DATE_INSERT_FORMATED"]);

    $order["ORDER"]["YEAR"] = $yearDate->format("Y");
    if ($arResult["MIN_YEAR"] > $order["ORDER"]["YEAR"]) {
        $arResult["MIN_YEAR"] = $order["ORDER"]["YEAR"];
    }

    if ($arResult["MAX_YEAR"] < $order["ORDER"]["YEAR"]) {
        $arResult["MAX_YEAR"] = $order["ORDER"]["YEAR"];
    }
}

for ($i = $arResult['MAX_YEAR']; $i >= $arResult['MIN_YEAR']; $i--) {
    $arResult['YEARS'][] = $i;
}