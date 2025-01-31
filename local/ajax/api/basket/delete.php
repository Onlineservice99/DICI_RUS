<?php
use Bitrix\Sale;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::includeModule('sale');

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$id = (int) $request->get('id');

/*$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
$basket->getItemById($id)->delete();
$basket->save();*/

\CSaleBasket::Delete($id);