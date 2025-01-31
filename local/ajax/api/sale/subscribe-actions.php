<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

if (!check_email($request->get('email'))) {
    Meven\Helper\Json::dumpErrors(['Не корректный email']);
}

if($request->get('act') == "subscribe_user") {
    global $USER;
    $userId = false;
    if ($USER->IsAuthorized()) {
        $userId = $USER->GetID();
    }
    $request = \Bitrix\Main\Context::getCurrent()->getRequest();

    \Bitrix\Main\Loader::includeModule('subscribe');

    $todo = $request->get('todo');
    if (!empty($todo)) {

        $subscription = CSubscription::GetByEmail($request->get('email'));
        $id = $subscription->fetch();

        if ($id > 0 && $todo == 'unsubscribe') {
            $subscr = new CSubscription;
            $res = $subscr->Delete($id['ID']);
            Meven\Helper\Json::dumpSuccess();
            // echo 'Subscribed';
        } elseif ($todo == 'subscribe' && $id < 1) {

            $rubs = [];
            $rsRub = CRubric::GetList(
                [],
                ["ACTIVE"=>"Y", "LID"=>LANG]
            );
            while ($arRubric = $rsRub->GetNext()) {
                $rubs[] = $arRubric['ID'];
            }

            $arFields = Array(
                "USER_ID" => $userId,
                "FORMAT" => "html",
                "EMAIL" => $request->get('email'),
                "ACTIVE" => "Y",
                "SEND_CONFIRM" => 'N',
                "RUB_ID" => $rubs
            );
            $subscr = new CSubscription;
            $ID = $subscr->Add($arFields);
            if ($ID > 0) {
                CSubscription::Authorize($ID);
                Bitrix\Main\Mail\Event::send([
                    'EVENT_NAME' => "SEND_SUBSCRIBE_CODE",
                    'LID' => SITE_ID,
                    'C_FIELDS' => [
                        'EMAIL' => $request->get('email')
                    ]
                                             ]);
                Meven\Helper\Json::dumpSuccess(['id' => $ID]);
            }

            Meven\Helper\Json::dumpErrors(['error' => 'Ошибка']);
        }

    }

    Meven\Helper\Json::dumpErrors(['error']);
}
?>