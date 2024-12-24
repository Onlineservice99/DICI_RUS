<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js");
Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css");
$this->addExternalCss("/bitrix/css/main/bootstrap.css");
CJSCore::Init(array('clipboard', 'fx'));

Loc::loadMessages(__FILE__);?>
<div class="lk__nav">
    <ul class="nav nav-pills">
        <li class="nav-item col-auto col-md-12 px-0">
            <a class="nav-link active" href="?year=all&clear_cache=Y">Все</a>
        </li>
        <?php foreach ($arResult['YEARS'] as $year):?>
            <li class="nav-item col-auto col-md-12 px-0">
                <a class="nav-link js-order-year" href="?year=<?=$year?>&clear_cache=Y"><?=$year?> год</a>
            </li>
        <?php endforeach?>
    </ul>
</div>
<div class="lk__story pb-40">
    <?php
    if (!empty($arResult['ERRORS']['FATAL']))
    {
        foreach($arResult['ERRORS']['FATAL'] as $error)
        {
            ShowError($error);
        }
        $component = $this->__component;
        if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]))
        {
            $APPLICATION->AuthForm('', false, false, 'N', false);
        }

    }
    else
    {
        if (!empty($arResult['ERRORS']['NONFATAL']))
        {
            foreach($arResult['ERRORS']['NONFATAL'] as $error)
            {
                ShowError($error);
            }
        }
        if (!count($arResult['ORDERS']))
        {
            if ($_REQUEST["filter_history"] == 'Y')
            {
                if ($_REQUEST["show_canceled"] == 'Y')
                {
                    ?>
                    <h3><?= Loc::getMessage('SPOL_TPL_EMPTY_CANCELED_ORDER')?></h3>
                    <?php
                }
                else
                {
                    ?>
                    <h3><?= Loc::getMessage('SPOL_TPL_EMPTY_HISTORY_ORDER_LIST')?></h3>
                    <?php
                }
            }
            else
            {
                ?>
                <h3><?= Loc::getMessage('SPOL_TPL_EMPTY_ORDER_LIST')?></h3>
                <?php
            }
        }
        ?>
        <div class="row col-md-12 col-sm-12">
            <?php
            $nothing = !isset($_REQUEST["filter_history"]) && !isset($_REQUEST["show_all"]);
            $clearFromLink = array("filter_history","filter_status","show_all", "show_canceled");

            if ($nothing || $_REQUEST["filter_history"] == 'N')
            {
                ?>
                <a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>">
                    <?=Loc::getMessage("SPOL_TPL_VIEW_ORDERS_HISTORY")?>
                </a>
                <?php
            }
            if ($_REQUEST["filter_history"] == 'Y')
            {
                ?>
                <a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("", $clearFromLink, false)?>">
                    <?=Loc::getMessage("SPOL_TPL_CUR_ORDERS")?>
                </a>
                <?php
                if ($_REQUEST["show_canceled"] == 'Y')
                {
                    ?>
                    <a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>">
                        <?=Loc::getMessage("SPOL_TPL_VIEW_ORDERS_HISTORY")?>
                    </a>
                    <?php
                }
                else
                {
                    ?>
                    <a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y&show_canceled=Y", $clearFromLink, false)?>">
                        <?=Loc::getMessage("SPOL_TPL_VIEW_ORDERS_CANCELED")?>
                    </a>
                    <?php
                }
            }
            ?>
        </div>

        <?php
        if (!count($arResult['ORDERS']))
        {
            ?>
            <div class="row col-md-12 col-sm-12">
                <a href="<?=htmlspecialcharsbx($arParams['PATH_TO_CATALOG'])?>" class="sale-order-history-link">
                    <?=Loc::getMessage('SPOL_TPL_LINK_TO_CATALOG')?>
                </a>
            </div>
            <?php
        }

        $paymentChangeData = array();
        $orderHeaderStatus = null;

        foreach ($arResult['ORDERS'] as $key => $order)
        {
            if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $arResult['SORT_TYPE'] == 'STATUS')
            {
                $orderHeaderStatus = $order['ORDER']['STATUS_ID'];
                if ($orderHeaderStatus == 'N'){
                ?>
                    <h4 class="mb-12">Текущие заказы</h4>
                <?php
                } elseif($orderHeaderStatus == 'F'){?>
                    <h4 class="mb-12 mt-32">Выполненные заказы</h4>
                <?php
                }
            }

            $showDelimeter = false;
            foreach ($order['PAYMENT'] as $payment)
            {
                if ($order['ORDER']['LOCK_CHANGE_PAYSYSTEM'] !== 'Y')
                {
                    $paymentChangeData[$payment['ACCOUNT_NUMBER']] = array(
                        "order" => htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']),
                        "payment" => htmlspecialcharsbx($payment['ACCOUNT_NUMBER']),
                        "allow_inner" => $arParams['ALLOW_INNER'],
                        "refresh_prices" => $arParams['REFRESH_PRICES'],
                        "path_to_payment" => $arParams['PATH_TO_PAYMENT'],
                        "only_inner_full" => $arParams['ONLY_INNER_FULL'],
                        "return_url" => $arResult['RETURN_URL'],
                    );
                }
                ?>
                <div class="py-16 border-bottom js-order-item" data-order-id="<?=$order["ORDER"]["ID"]?>" data-order-year="<?=$order["ORDER"]['YEAR']?>">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a class="link fw-600" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"])?>"><?=Loc::getMessage('SPOL_TPL_NUMBER_SIGN').$order['ORDER']['ACCOUNT_NUMBER']?></a><span class="text-gray p-md ml-8">от <?=$order['ORDER']['DATE_INSERT_FORMATED']?></span>
                            <div class="p-md"><?=$order["ADDRESS"]?></div>
                            <table class="p-md">
                                <tr>
                                    <td class="text-gray">Статус заказа: </td>
                                    <td class="pl-8 <?=($orderHeaderStatus == 'F') ? 'text-green' : 'text-violet'?>"><?=htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME'])?></td>
                                </tr>
                                <tr>
                                    <td class="text-gray">Статус доставки:</td>
                                    <td class="pl-8 text-green">
                                        <?php foreach ($order['SHIPMENT'] as $shipment){
                                            if (empty($shipment))
                                            {
                                                continue;
                                            }?>
                                            <?=$arResult['INFO']['DELIVERY'][$shipment['DELIVERY_ID']]['NAME']?>
                                        <?php }?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-gray">Статус оплаты:</td>
                                    <?php
                                    if ($payment['PAID'] === 'Y')
                                    {
                                        ?>
                                        <td class="pl-8 text-green"><?=Loc::getMessage('SPOL_TPL_PAID')?></td>
                                        <?php
                                    }
                                    elseif ($order['ORDER']['IS_ALLOW_PAY'] == 'N')
                                    {
                                        ?>
                                        <td class="pl-8 text-red"><?=Loc::getMessage('SPOL_TPL_RESTRICTED_PAID')?></td>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <td class="pl-8 text-red"><?=Loc::getMessage('SPOL_TPL_NOTPAID')?></td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                            </table>
                        </div>
                        <div class="col-auto ml-auto text-right">
                            <div class="h4 text-green mb-12 text-nowrap"><?=$payment['FORMATED_SUM']?></div>
                            <a class="btn btn--border btn--sm"
                               href="/personal/orders/?COPY_ORDER=Y&ID=<?=$order['ORDER']['ID']?>">
                                <svg class="icon icon-refresh icon-16 text-red mr-8">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-refresh"></use>
                                </svg>Повторить
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>

        <?php
        echo $arResult["NAV_STRING"];

        if ($_REQUEST["filter_history"] !== 'Y')
        {
            $javascriptParams = array(
                "url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
                "templateFolder" => CUtil::JSEscape($templateFolder),
                "templateName" => $this->__component->GetTemplateName(),
                "paymentList" => $paymentChangeData,
                "returnUrl" => CUtil::JSEscape($arResult["RETURN_URL"]),
            );
            $javascriptParams = CUtil::PhpToJSObject($javascriptParams);
            ?>
            <script>
                BX.Sale.PersonalOrderComponent.PersonalOrderList.init(<?=$javascriptParams?>);
            </script>
            <?php
        }
    }
?>
</div>
