<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$alphabet = [];
$alphabet = range('A', 'Z');

$numbers = false;
$itemsAbc = [];
foreach ($arResult['ITEMS'] as $key=>$item) {
    $letter = mb_substr($item['NAME'], 0, 1);

    if ((int)$letter > 0 && (int) $letter < 10) {
        $itemsAbc['123'][] = $item;
        $numbers = true;
        continue;
    }

    $itemsAbc[$letter][] = $item;
}

$alphabetRu = [];
foreach(range(chr(0xC0),chr(0xDF)) as $key => $letter){
    $letter = iconv('CP1251','UTF-8', $letter);
    if (!isset($itemsAbc[$letter])) {
        continue;
    }

    $alphabetRu[$key] = $letter;
}

foreach ($alphabet as $key=>$a) {
    if (!isset($itemsAbc[$a])) {
        unset($alphabet[$key]);
    }
}

$arResult['ALPHABET_NUM'] = [];
if ($numbers) {
    $arResult['ALPHABET_NUM'][] = "123";
}
$arResult["ALPHABET_RU"] = $alphabetRu;
$arResult["ALPHABET"] = $alphabet;
$arResult["ITEMS_ABC"] = $itemsAbc;

foreach (array_merge($arResult["ALPHABET_RU"], $arResult["ALPHABET"]) as $key => $letter) {
    $arResult["TAB_ID"][$letter] = $key;
}

