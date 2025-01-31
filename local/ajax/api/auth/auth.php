<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

if (!$request->isAjaxRequest() || !$request->isPost()) {
    \Meven\Helper\Json::dumpErrors(["Не корректный запрос"]);
}

$result = \Meven\User\Authorize::auth($request->getPost('login'), $request->getPost('password'));

if ($result === true) {
    \Meven\Helper\Json::dumpSuccess();
}

if ($result['TYPE'] == 'ERROR') {
    \Meven\Helper\Json::dumpErrors([$result['MESSAGE']]);
}
\Meven\Helper\Json::dumpErrors([$result]);


