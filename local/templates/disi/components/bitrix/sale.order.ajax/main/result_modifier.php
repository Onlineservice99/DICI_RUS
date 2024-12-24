<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$properties = [];
foreach ($arResult['JS_DATA']['ORDER_PROP']['properties'] as $prop) {
    $properties[$prop['PERSON_TYPE_ID']][$prop['CODE']] = $prop['ID'];
}

$arResult['PROPS'] = $properties;


