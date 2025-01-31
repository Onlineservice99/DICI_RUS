<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);
global $USER;

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

if ($request->get('ORDER_ID') && $request->get('ORDER_ID') > 0) {
    include __DIR__.'/success.php';
} else {
    include __DIR__.'/order.php';
}
