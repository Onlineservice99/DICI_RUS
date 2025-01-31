<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('sale');
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$fuser = \Bitrix\Sale\Fuser::getId();

if ($fuser > 0 && $request->getPost('id') > 0) {
    $exist = \Meven\Models\FavoriteTable::getList(
        [
            'filter' => [
                '=USER_ID' => $fuser,
                '=ITEM_ID' => $request->getPost('id')
            ]
        ]
    )->fetch();

    if (!empty($exist)) {
        \Meven\Helper\Json::dumpSuccess();
    }

    $result = \Meven\Models\FavoriteTable::add(
        [
            'USER_ID' => $fuser,
            'ITEM_ID' => $request->getPost('id')
        ]
    );

    $count = \Meven\Models\FavoriteTable::getList(
        [
            'filter' => [
                '=USER_ID' => $fuser
            ]
        ]
    )->fetchAll();

    if ($result->isSuccess()) {
        \Meven\Helper\Json::dumpSuccess(['count' => count($count)]);
    }
}

\Meven\Helper\Json::dumpErrors(['произошла ошибка']);