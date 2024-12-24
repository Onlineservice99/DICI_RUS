<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$result = [];
foreach ($arResult['SECTIONS'] as $section) {
    if ($section['DEPTH_LEVEL'] == 1) {
        $result[$section['ID']] = $section;
        continue;
    }
    if ($section['DEPTH_LEVEL'] == 2) {
        $result[$section['IBLOCK_SECTION_ID']]["LVL2"][] = $section;
    }elseif($section['DEPTH_LEVEL'] == 3){
        $result[$section['IBLOCK_SECTION_ID']]["LVL3"][] = $section;
    }elseif($section['DEPTH_LEVEL'] == 4){
        $result[$section['IBLOCK_SECTION_ID']]["LVL4"][] = $section;
    }
}

$arResult['SECTIONS'] = $result;