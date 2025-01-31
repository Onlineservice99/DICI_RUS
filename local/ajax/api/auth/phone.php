<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use \Bitrix\Main\Security\Random,
    \Bitrix\Main\Context,
    \electroset1\AuthorizationService,
    Bitrix\ImOpenLines\Tools\Phone;

$request = Context::getCurrent()->getRequest();

if (!empty($request->get('action'))){
    $result = [
        'success' => false,
        'message' => '',
    ];

    $oAuthService = new AuthorizationService();

    switch ($request->get('action')){
        case 'send':
            $result = $oAuthService->sendCode($request->get('phone'), $request->get('type'));
            break;

        case 'checkCode':
            $result = $oAuthService->checkCode($request->get('code'));
            break;

        default:
            $result['message'] = 'Invalid action';
            break;
    }


    die(json_encode($result));
}
