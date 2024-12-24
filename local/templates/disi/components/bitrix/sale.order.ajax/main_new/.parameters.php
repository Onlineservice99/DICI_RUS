<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("sale");
$delivery = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();
foreach($delivery as $arDelivery){
$resDelivery[$arDelivery["ID"]]=$arDelivery["NAME"];
}
$arTemplateParameters = array(
    "NEED_DELIVERY" => Array(
        "NAME" => "Выберите службы доставки для раздела 'Нужна доставка'",
        "TYPE" => "LIST",
        'VALUES' => $resDelivery,
        'MULTIPLE' => "Y",
    ),
    "SELF_DELIVERY" => Array(
        "NAME" => "Выберите службы доставки для раздела 'Самовывоз'",
        "TYPE" => "LIST",
        'VALUES' => $resDelivery,
        'MULTIPLE' => "Y",
    ),
);
?>