<?php
defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED === true || die;

if (!$arParams['COLUMS']) {
    $arParams['COLUMS'] = 3;
}

$arResult['GROUPS'] = range(0, $arParams['COLUMS'] - 1);
for ($i = 0; $i < count($arResult['ITEMS']); $i += $arParams['COLUMS']) {
    $count = 0;
    foreach ($arResult['GROUPS'] as $j => $group) {
        if (!is_array($arResult['GROUPS'][$j])) {
            $arResult['GROUPS'][$j] = [];
        }

        if (array_key_exists($i + $count, $arResult['ITEMS'])) {
            $arResult['GROUPS'][$j][] = $arResult['ITEMS'][$i + $count];
        }

        $count++;
    }
}

$this->__component->SetResultCacheKeys(['GROUPS']);
