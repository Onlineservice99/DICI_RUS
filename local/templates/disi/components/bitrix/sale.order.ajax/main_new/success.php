<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Sale;
use electroset1\SaleOrder;

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

$basketItems = json_encode(SaleOrder::getOrderProducts($request->get('ORDER_ID')), JSON_UNESCAPED_UNICODE);

$paymentMethodId = array_values($arResult['PAYMENT'])[0]['ID'];
$paymentMethodName = $arResult['PAY_SYSTEM_LIST_BY_PAYMENT_ID'][$paymentMethodId]['NAME'];
?>
<script>
    $('div.rbs__content').click(function(){
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'ecommerce': {
                'checkout': {
                    'actionField': {
                        'step': 3, // НОМЕР ШАГА
                        'option': "<?= $paymentMethodName ?>"
                    },
                    'products': <?= $basketItems ?>
                }
            },
            'event': 'ecommerceCheckout'
        });
        }
    );
</script>
<div class="section__overlay mb-32 mb-lg-80 pt-lg-48">
    <div class="container">
        <div class="basket basket--order mb-40 mb-lg-80">
            <h3>Ваш заказ №<?= $request->get('ORDER_ID') ?> успешно оформлен</h3>
        </div>

        <? if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y') {
            if (!empty($arResult["PAYMENT"])) {
                foreach ($arResult["PAYMENT"] as $payment) {
                    if ($payment["PAID"] != 'Y') {
                        if (
                            !empty($arResult['PAY_SYSTEM_LIST'])
                            && array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
                        ) {
                            $arPaySystem = $arResult['PAY_SYSTEM_LIST_BY_PAYMENT_ID'][$payment["ID"]];
                            if (empty($arPaySystem["ERROR"])) : ?>
                                <div class="row mb-2">
                                    <div class="col">
                                        <h3 class="pay_name"><?= Loc::getMessage("SOA_PAY") ?></h3>
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-auto"><strong><?= $arPaySystem["NAME"] ?></strong></div>
                                    <div class="col"><?= CFile::ShowImage($arPaySystem["LOGOTIP"], 100, 100, "border=0\" style=\"width:100px\"", "", false) ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col">
                                        <? if ($arPaySystem["ACTION_FILE"] <> '' && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y") :
                                            $orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
                                            $paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
                                        ?>
                                            <script>
                                                window.open('<?= $arParams["PATH_TO_PAYMENT"] ?>?ORDER_ID=<?= $orderAccountNumber ?>&PAYMENT_ID=<?= $paymentAccountNumber ?>');
                                            </script>
                                            <?= Loc::getMessage("SOA_PAY_LINK", array("#LINK#" => $arParams["PATH_TO_PAYMENT"] . "?ORDER_ID=" . $orderAccountNumber . "&PAYMENT_ID=" . $paymentAccountNumber)) ?>
                                            <? if (CSalePdf::isPdfAvailable() && $arPaySystem['IS_AFFORD_PDF']) : ?>
                                                <br />
                                                <?= Loc::getMessage("SOA_PAY_PDF", array("#LINK#" => $arParams["PATH_TO_PAYMENT"] . "?ORDER_ID=" . $orderAccountNumber . "&pdf=1&DOWNLOAD=Y")) ?>
                                            <? endif ?>
                                        <? else : ?>
                                            <?= $arPaySystem["BUFFERED_OUTPUT"] ?>
                                        <? endif ?>
                                    </div>
                                </div>
                            <? else : ?>
                                <div class="alert alert-danger" role="alert"><?= Loc::getMessage("SOA_ORDER_PS_ERROR") ?></div>
                            <? endif;
                        } else { ?>
                            <div class="alert alert-danger" role="alert"><?= Loc::getMessage("SOA_ORDER_PS_ERROR") ?></div>
            <? }
                    } else {
                        $items = SaleOrder::getPaidOrderProducts($request->get('ORDER_ID'));
                        $shipping = $arResult['ORDER']['PRICE_DELIVERY'];
                        $tax = $arResult['ORDER']['TAX_VALUE'];
                        $transactionId = $arResult['ORDER']["PAY_VOUCHER_NUM"];
                        $revenue = $arResult['ORDER']['PRICE'];
                        $coupon = SaleOrder::getCoupon($request->get('ORDER_ID'));

                        ?>
                            <script>
                                window.dataLayer = window.dataLayer || []; dataLayer.push({
                                    'ecommerce' : {
                                        'purchase' : {
                                            'actionField' : {
                                                'id' : '<?= $transactionId ?>', // Идентификатор транзакции обязательное
                                                'revenue' : <?= $revenue ?>, // сумма заказа
                                                'tax' : <?= $tax ?>, // налог
                                                'shipping' : <?= $shipping ?>, //доставка
                                                'coupon': '<?= $coupon ?>' // купон
                                            },
                                            'products' : <?= json_encode($items, JSON_UNESCAPED_UNICODE) ?>
                                        }
                                    },
                                    'event' : 'ecommercePurchase'
                                });
                            </script>
                        <?php
                    }
                }
            }
        } else { ?>
            <div class="alert alert-danger" role="alert"><?= $arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'] ?></div>
        <? } ?>
    </div>
</div>

<?if($payment["PAY_SYSTEM_ID"] == 5 && $USER->IsAdmin()) {
    $event = new CEvent;
    $order = \Bitrix\Sale\Order::load($request->get('ORDER_ID'));
    ob_start();
    $paymentCollection = $order->getPaymentCollection();
    $paymentEntity = $paymentCollection[0];
    $service = Sale\PaySystem\Manager::getObjectById($payment["PAY_SYSTEM_ID"]);
    $context = \Bitrix\Main\Application::getInstance()->getContext();
    $service->initiatePay($paymentEntity, $context->getRequest());
    $pdf_content=ob_get_contents();
    ob_end_clean();
    
    $fid = CFile::SaveFile(array(
            'name' => 'bill_'.$request->get('ORDER_ID') . '-' . generateRandomString() . '.pdf',
            'size' => strlen($pdf_content),
            'type' => 'application/pdf',
            'MODULE_ID' => 'sale',
            'content' => $pdf_content,
        ),
        'bills'
    );

    $propertyCollection = $order->getPropertyCollection();
    
    $arMailFields = array(
        "ORDER_ID" => $request->get('ORDER_ID'),
        "EMAIL" => $propertyCollection->getUserEmail()->GetValue(),
    );

    $event->Send("SEND_BILL", SITE_ID, $arMailFields, "N", "", array($fid));
}?>
