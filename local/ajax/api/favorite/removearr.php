<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('sale');
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$fuser = \Bitrix\Sale\Fuser::getId();


foreach ($request->getPost('ids') as $id) {
    $exist = \Meven\Models\FavoriteTable::getList(
        [
            'filter' => [
                'USER_ID' => $fuser,
                'ITEM_ID' => $id
            ]
        ]
    )->fetch();

    if (!empty($exist)) {
        \Meven\Models\FavoriteTable::delete($exist['ID']);
    }
}

$count = \Meven\Models\FavoriteTable::getList(
    [
        'filter' => [
            '=USER_ID' => $fuser
        ]
    ]
)->fetchAll();
\Meven\Helper\Json::dumpSuccess(['count' => count($count)]);