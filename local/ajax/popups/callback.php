<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
    "meven:form",
    "callback",
    array(
        "COMPONENT_TEMPLATE" => "callback",
        "IBLOCK_TYPE" => "forms",
        "IBLOCK_ID" => \Bitrix\Main\Config\Option::get('meven.info', 'iblock_form_callback'),
        "SUCCESS_MESSAGE" => "Ваше заявка успешно отправлена, дождитесь звонка менеджера",
        "SEND_BUTTON_NAME" => "Задать вопрос",
        "SEND_BUTTON_CLASS" => "btn btn-primary",
        "DISPLAY_CLOSE_BUTTON" => "Y",
        "SHOW_LICENCE" => "Y",
        "LICENCE_TEXT" => "btn btn-primary",
        "CLOSE_BUTTON_NAME" => "Закрыть",
        "CLOSE_BUTTON_CLASS" => "btn btn-primary",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_GROUPS" => "Y",
        "FORM_TITLE" => "",
        "HIDDEN_FIELDS" => ['SECTION'],
        "USE_CAPTCHA" => "Y",
    ),
    false
);
