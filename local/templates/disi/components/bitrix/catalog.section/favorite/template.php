<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

\Bitrix\Main\Page\Asset::getInstance()->addJs($templateFolder . '/dist/app.bundle.js');
//\Bitrix\Main\Page\Asset::getInstance()->addJs($templateFolder.'/dist/app.bundle.css');
global $USER;
$templateData['ALL_CAN_BUY'] = true;
?>


    <div class="product-row mb-56 mb-lg-80" id="favorite-inner-elements">
        <?php
        if ($arParams['IS_AJAX'] == 'Y') {
            ob_start();
        }
        ?>
        <?php foreach ($arResult["ITEMS"] as $key => $item): ?>
            <?php $APPLICATION->IncludeComponent(
                "bitrix:catalog.item",
                "favorite",
                array(
                    "RESULT" => [
                        "ITEM" => $item,
                        'AREA_ID' => $this->GetEditAreaId($item['ID']),
                    ]
                )
            ); ?>
            <?php

                if ($item['CAN_BUY'] === false) {
                    $templateData['ALL_CAN_BUY'] = false;
                }
            ?>
        <?php endforeach; ?>
        <?php
        if ($arParams['IS_AJAX'] == 'Y') {
            $templateData['CONTENT'] = ob_get_contents();
            ob_end_clean();
        }
        ?>
    </div>

    <div class="d-flex justify-content-center" id="favorite-inner-pages">
        <?php
        if ($arParams['IS_AJAX'] == 'Y') {
            ob_start();
        }
        ?>
        <?=$arResult['NAV_STRING']?>
        <?php
        if ($arParams['IS_AJAX'] == 'Y') {
            $templateData['PAGINATION'] = ob_get_contents();
            ob_end_clean();
        }
        ?>
    </div>
