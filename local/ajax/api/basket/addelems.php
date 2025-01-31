<?php
use Bitrix\Sale;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::includeModule('sale');

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$quantity = 1;

foreach ($request->getPost('ids') as $id) {
    $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
    if ($item = $basket->getExistsItem('catalog', $id)) {
        $item->setField('QUANTITY', $item->getQuantity() + $quantity);
    } else {
        $item = $basket->createItem('catalog', $id);
        $item->setFields(array(
             'QUANTITY' => $quantity,
             'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
             'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
             'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
         ));
    }

    $basket->save();
}

\Meven\Helper\Json::dumpSuccess(['count' => count($basket->getQuantityList())]);