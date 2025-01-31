<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

if (!$request->isAjaxRequest() || !$request->isPost()) {
    \Meven\Helper\Json::dumpErrors(["Не корректный запрос"]);
}

$result = CAllUser::SendPassword($request->getPost('email'), $request->getPost('email'));

if ($result['TYPE'] == 'ERROR') {
    \Meven\Helper\Json::dumpErrors([$result['MESSAGE']]);
}

\Meven\Helper\Json::dumpSuccess();