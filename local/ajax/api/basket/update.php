<?php
use Bitrix\Sale;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::includeModule('sale');

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$id = (int) $request->getPost('id');
$quantity = (int) $request->getPost('quantity');

$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
if ($item = $basket->getExistsItem('catalog', $id)) {
    $item->setField('QUANTITY', $quantity);
} else {
    $item = $basket->createItem('catalog', $id);
    $item->setFields(array(
         'QUANTITY' => $quantity,
         'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
         'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
         'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
    ));
}

$r = $basket->save();
if ($r->isSuccess()) {
    \Meven\Helper\Json::dumpSuccess(['price' => CurrencyFormat($item->getFinalPrice(), 'RUB')]);
} else {
    \Meven\Helper\Json::dumpErrors([$r->getErrorMessages()]);
}