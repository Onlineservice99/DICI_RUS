<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult['ITEMS'] as $key => $item) {
    if (array_search($item['CODE'], ['BASE', 'BRAND', 'ACTIONS', 'IN_STOCK']) === false) {
        unset($arResult['ITEMS'][$key]);
    }
}