<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach($arResult["PROFILES"] as &$val) {
    // Получение свойств профиля
    $resProps = CSaleOrderUserPropsValue::GetList(
        ["ID" => "ASC"],
        [
            "USER_PROPS_ID" => $val['ID'],
            "PROP_CODE" => ["LEGAL_ADDRESS", "STREET", "HOUSE", "CORPUS", "ENTRANCE", "OFIS"],
        ]
    );

    while ($arProp = $resProps->Fetch()){
        $val[$arProp['PROP_CODE']] = $arProp["VALUE"];
        $val['PROP_CODE'] = $arProp["PROP_CODE"];
    }
}