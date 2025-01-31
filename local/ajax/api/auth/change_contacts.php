<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

global $USER;
$userId = $USER->GetID();
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$name = $request->getPost("NAME");
$phone = $request->getPost("PHONE_NUMBER");
$email = $request->getPost("EMAIL");

$USER->Update($userId, ["NAME" => $name, "PHONE_NUMBER" => $phone, "EMAIL" => $email]);