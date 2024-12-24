<?php
use Bitrix\Main\Application;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$context = Application::getInstance()->getContext();
$request = $context->getRequest();

if (!empty(htmlspecialchars($request->getQuery('del_filter')))) {
    header('Location: '.$arResult["FORM_ACTION"]);
}

if ($arParams['SECTION_ID']) {
    $section = \Bitrix\Iblock\SectionTable::getList([
        'filter' => [
            '=IBLOCK_ID' => $arParams['IBLOCK_ID'],
            '=ID' => $arParams['SECTION_ID'],
        ],
        'select' => ['IBLOCK_SECTION_ID'],
    ])->fetch();

    if (!$section['IBLOCK_SECTION_ID']) {
        foreach ($arResult['ITEMS'] as $key => $item) {
            if (array_search($item['CODE'], ['BASE', 'BRAND', 'ACTIONS', 'IN_STOCK']) === false) {
                unset($arResult['ITEMS'][$key]);
            }
        }
    }
}

if ($arParams['IS_BRAND_PAGE']) {
    foreach ($arResult['ITEMS'] as $key => $item) {
        if (array_search($item['CODE'], ['BASE', 'ACTIONS', 'IN_STOCK']) === false) {
            unset($arResult['ITEMS'][$key]);
        }
    }
}