<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

if (!$request->isAjaxRequest() || !$request->isPost()) {
    \Meven\Helper\Json::dumpErrors(["Не корректный запрос"]);
}

$fields['LOGIN'] = $request->getPost('email');
$fields['EMAIL'] = $request->getPost('email');
list($fields['LAST_NAME'], $fields['NAME'], $fields['SECOND_NAME']) = explode(' ', $request->getPost('name'));
$fields['PHONE_NUMBER'] = $request->getPost('phone');
$fields['PERSONAL_PHONE'] = $request->getPost('phone');
$fields['PASSWORD'] = $request->getPost('password');
$fields['CONFIRM_PASSWORD'] = $request->getPost('confirm_password');

$user = new \CUser;
$id = $user->Add($fields);

if ($id < 1) {
    \Meven\Helper\Json::dumpErrors([$user->LAST_ERROR]);
} else {
    $user->Authorize($id);
}

\Meven\Helper\Json::dumpSuccess();