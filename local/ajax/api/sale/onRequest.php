<?php
use \Cosmos\Config,
    \Bitrix\Main\Mail\Event,
    \Bitrix\Main\Loader;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$httpApp = \Bitrix\Main\Application::getInstance();
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if($arFields = $request->getPost('fields')){
    if(!Loader::includeModule('sale')) { die(json_encode(['success' => false, 'message' => 'Не удалось подключить модуль sale'])); }
    $requiredFields = [
        'PRODUCT_ID',
        'NAME',
        'PHONE',
        'COUNT',
        'cond',
    ];
    foreach ($requiredFields as $code){
        if ($arFields[$code]){
            continue;
        }
        die(json_encode(['success' => false, 'message' => 'Не заполнено обязательное поле']));
    }
    Config::getInstance()->init();
    $aCosmosConfigIblock = Config::getInstance()->getParam("IBLOCK");
    $oCIBlockElement = new \CIBlockElement;

    $arProduct = CIBlockElement::GetByID($arFields['PRODUCT_ID'])->Fetch();
    if(is_array($arProduct) && !empty($arProduct)){
        $arFields['PRODUCT_NAME'] = $arProduct['NAME'];
    }

    $iblockFields['IBLOCK_ID'] = $aCosmosConfigIblock["fast_order"]["ID"];

    $res = $oCIBlockElement->Add([
        'NAME' => 'Заявка с сайта',
        'IBLOCK_ID' => $aCosmosConfigIblock["fast_order"]["ID"],
        'PROPERTY_VALUES' => $arFields,
    ]);

    if ($res){
        $mail = Event::send([
            'EVENT_NAME' => 'FORM_FILLING_FAST_ORDER',
            'LID' => 's1',
            'C_FIELDS' => $arFields
            ]);


        die(json_encode(['success' => true, 'message' => 'Успешно']));
    }else{
        die(json_encode(['success' => false, 'message' => 'Не удалось сохранить заявку']));
    }
}


die();