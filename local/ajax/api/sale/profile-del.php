<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$profile = \Meven\Sale\BuyerProfile::getInstance();
$id = $request->get('id');
$profile->delProfile($id);