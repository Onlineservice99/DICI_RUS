<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$flag = $request->isPost();
$profile = \Meven\Sale\BuyerProfile::getInstance();
$profile->addProfile();

$APPLICATION->IncludeComponent(
    "meven:sale.personal.profile.add",
    "jur",
    [
    ],
    false
);
?>
<script>

    console.log('privet')

</script>
<script src="/local/components/meven/sale.personal.profile.add/templates/jur/dist/app.bundle.js"></script>
<script src="/local/components/meven/sale.personal.profile.add/templates/jur/src/script.js"></script>