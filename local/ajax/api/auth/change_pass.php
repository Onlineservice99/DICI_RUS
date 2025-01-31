<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

global $USER;
$userId = $USER->GetID();
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$pass = $request->getPost('USER_PASSWORD');

if (!empty($pass)) {
    $USER->Update($userId, ["PASSWORD"=> $pass]);
}