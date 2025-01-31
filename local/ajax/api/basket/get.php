<?php
use Bitrix\Sale;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$id = (int) $request->get('id');

$price = '';
$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
if ($item = $basket->getExistsItem('catalog', $id)) {
    $price = $item->getFinalPrice();
}

if ($price != '') {
    \Meven\Helper\Json::dumpSuccess([$price]);
} else {
    \Meven\Helper\Json::dumpErrors(['error get price']);
}