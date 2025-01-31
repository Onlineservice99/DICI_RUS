<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");?>
<div class="popup popup--status">
    <div class="h2 text-center mb-28 mx-sm-n56">Проверить статус заказа</div>
    <?$APPLICATION->IncludeComponent(
        "meven:order.status",
        "popup",
        [
        ]
    );?>
</div>