<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::includeModule('sale');

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$fuser = \Bitrix\Sale\Fuser::getId();

if ($fuser > 0) {
    $elements = \Meven\Models\FavoriteTable::getList(
        [
            'filter' => [
                '=USER_ID' => $fuser,
            ]
        ]
    )->fetchAll();

    if (!empty($elements)) {
        \Meven\Helper\Json::dumpSuccess($elements);
    }
}

\Meven\Helper\Json::dumpErrors(['элементы не найдены']);