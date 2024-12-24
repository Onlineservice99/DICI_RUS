<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$result = [];
foreach ($arResult['SECTIONS'] as $section) {
    if ($section['DEPTH_LEVEL'] == 1) {
        $result[$section['ID']] = $section;
        continue;
    }
    $result[$section['IBLOCK_SECTION_ID']]["ELEMS"][] = $section;
}

$arResult['SECTIONS'] = $result;