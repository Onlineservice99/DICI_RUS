<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$count = 0;
$result = [];
foreach ($arResult['SECTIONS'] as $section) {
    if ($section['DEPTH_LEVEL'] == 1) {
        $count += $section['ELEMENT_CNT'];
        $result[$section['ID']] = $section;
        continue;
    }
    $result[$section['IBLOCK_SECTION_ID']]["ELEMS"][] = $section;
}

$arResult['COUNT'] = $count;

$arResult['SECTIONS'] = $result;