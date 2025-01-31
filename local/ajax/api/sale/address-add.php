<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$profile = \Meven\Sale\BuyerProfile::getInstance();
$profile->addProfile();

$APPLICATION->IncludeComponent(
    "bitrix:sale.personal.profile.list",
    "main",
    Array(
        "PATH_TO_DETAIL" => "",
        "PER_PAGE" => "20",
        "SET_TITLE" => "N"
    )
);