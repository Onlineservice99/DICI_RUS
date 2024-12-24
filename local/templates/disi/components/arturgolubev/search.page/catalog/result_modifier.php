<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult["SEARCH_HISTORY"] = array();
if($arParams["SHOW_HISTORY"] == 'Y'){
	$arResult["SEARCH_HISTORY"] = \Arturgolubev\Smartsearch\Tools::getSearchHistory(10);
}

if (!empty($arResult["arReturn"])){
    $arElements = $arResult["arReturn"];

    foreach ($arElements as $k=>$v) {
        if (substr($v, 0, 1) == 'S')
            unset($arElements[$k]);
    }

    $arElements = array_values($arElements);

    $arFilter = Array(
        "IBLOCK_ID"=>$arParams['IBLOCK_ID'],
        "ID" =>$arElements,
        "ACTIVE"=>"Y",
        "CATALOG_AVAILABLE"=>"Y"
    );
    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter);
    $arElementRes = array();
    while ($ar_fields = $res->GetNext()) {
        $arElementRes[] = $ar_fields["ID"];
    }

    $arResult['FOUND_AVAILABLE_CNT'] = count($arElementRes);
}