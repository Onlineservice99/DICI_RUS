<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Page\Asset;

if ($arParams['GUEST_MODE'] !== 'Y')
{
	Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js");
	Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css");
}

CJSCore::Init(array('clipboard', 'fx'));

$APPLICATION->SetTitle("");
?>
<?php if (!empty($arResult['ERRORS']['FATAL']))
{
    foreach ($arResult['ERRORS']['FATAL'] as $error)
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
        foreach ($arResult['ERRORS']['NONFATAL'] as $error)
        {
            ShowError($error);
        }
    }
    ?>
    <div class="basket__main basket__main--list">
        <div class="basket-item basket-item--head d-none d-xl-flex">
            <div class="basket-item__num">Код</div>
            <div class="basket-item__name-wrap">Бренд и наименование</div>
            <div class="basket-item__price-wrap mr-0">
                <div class="basket-item__price text-xl-right">Цена</div>
                <div class="basket-item__count">Количество</div>
                <div class="basket-item__price basket-item__price--total">Стоимость</div>
            </div>
        </div>
        <?php foreach ($arResult['BASKET'] as $basketItem):?>
            <div class="basket-item">
                <div class="basket-item__num"><span>1</span><?=$basketItem['PROPS']['ARTICLE']?></div>
                <a class="basket-item__img-wrap" href="<?=htmlspecialcharsbx($basketItem['DETAIL_PAGE_URL'])?>">
                    <?php
                    if($basketItem['PICTURE']['SRC'] <> '')
                    {
                        $imageSrc = htmlspecialcharsbx($basketItem['PICTURE']['SRC']);
                    }
                    else
                    {
                        $imageSrc = $this->GetFolder().SITE_TEMPLATE_PATH.'/images/no_photo.png';
                    }
                    ?>
                    <img src="<?=$imageSrc?>" alt="<?=$basketItem['NAME']?>">
                </a>
                <div class="basket-item__name-wrap">
                    <div class="basket-item__brand p-xs text-gray mb-16"><?=$arResult['BRANDS'][$basketItem['PROPS']['BRAND']]?></div>
                    <a class="basket-item__title link" href="<?=htmlspecialcharsbx($basketItem['DETAIL_PAGE_URL'])?>"><?=$basketItem['NAME']?></a>
                </div>
                <div class="basket-item__price-wrap">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="basket-item__price text-xl-right">
                           <?php if (!empty($basketItem["PRICE_FORMATED"])):?>
                                <s><?=$basketItem['BASE_PRICE_FORMATED']?></s>
                           <?php endif;?>
                            <?=$basketItem['PRICE_FORMATED']?>
                        </div>
                        <div class="basket-item__count">x <?=$basketItem['QUANTITY']?></div>
                        <div class="basket-item__price basket-item__price--total"><span class="fw-600"><?=$basketItem['FORMATED_SUM']?></div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
        <div class="pt-32">
            <h4 class="mb-20">Комментарий к заказу</h4>
            <div class="p-md">
                <?=nl2br(htmlspecialcharsbx($arResult["USER_DESCRIPTION"]))?>
            </div>
        </div>
    </div>
    <div class="basket__aside">
        <a class="btn btn--border btn--sm mb-32" href="<?=$arResult["URL_TO_COPY"]?>">
            <svg class="icon icon-refresh icon-16 text-red mr-8">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-refresh"></use>
            </svg>Повторить
        </a>
        <h4 class="mb-16">Статус заказа</h4>
        <table class="p-md mb-32">
            <tr>
                <td class="text-gray">Статус заказа: </td>
                <td class="pl-8 text-green"><?=htmlspecialcharsbx($arResult["STATUS"]["NAME"]);?></td>
            </tr>
            <tr>
                <td class="text-gray">Статус доставки:</td>
                <?php foreach ($arResult['SHIPMENT'] as $shipment):?>
                    <td class="pl-8 text-green"><?=htmlspecialcharsbx($shipment['STATUS_NAME'])?></td>
                <?php endforeach;?>
            </tr>
            <tr>
                <td class="text-gray">Статус оплаты:</td>
                <?php foreach ($arResult['PAYMENT'] as $payment):?>
                    <?php
                    if ($payment['PAID'] === 'Y')
                    {
                        ?>
                        <td class="pl-8 text-green">
                            <?=Loc::getMessage('SPOD_PAYMENT_PAID')?>
                        </td>
                    <?php
                    }
                    elseif ($arResult['IS_ALLOW_PAY'] == 'N')
                    {
                    ?>
                        <td class="pl-8 text-green text-orange">
                            <?=Loc::getMessage('SPOD_TPL_RESTRICTED_PAID')?>
                        </td>
                    <?php
                    }
                    else
                    {
                    ?>
                        <td class="pl-8 text-red">
                            <?=Loc::getMessage('SPOD_PAYMENT_UNPAID')?>
                        </td>
                    <?php
                    }
                    ?>
                <?php endforeach;?>
            </tr>
        </table>
        <div class="basket__total-block mb-32">
            <?//print_r($basketItem)?>
            <h4 class="mb-24 text-uppercase">В КОРЗИНЕ</h4>
            <div class="mb-8"><b><?=$basketItem['QUANTITY']?></b>&nbsp;товаров <span class="text-gray text-nowrap">на общую сумму</span></div>
            <div class="basket__total-price h3 mb-24">
                <?=$arResult['PRODUCT_SUM_FORMATED']?>
            </div>
            <h5 class="mb-12">В сумму включено:</h5>
            <div class="text-gray font-weight-normal p-xs pb-20">
                <div class="mb-8 d-flex">Скидка по акциям
                    <div class="text-green ml-auto">- <?=$arResult["DISCOUNT_VALUE"]?> руб.</div>
                </div>
                <div class="mb-8 d-flex">Скидка за онлайн-оплату
                    <div class="text-green ml-auto">- 25 руб.</div>
                </div>
            </div>
            <div class="text-gray font-weight-normal p-xs">
                <div class="mb-8 d-flex">Стоимость доставки
                    <div class="text-red ml-auto">+ <?=$arResult["PRICE_DELIVERY_FORMATED"]?></div>
                </div>
            </div>
        </div>
        <h4 class="mb-20">Адрес доставки</h4>
        <div class="p-md">
            <?php foreach ($arResult["ORDER_PROPS"] as $property):?>
                <?php
                if ($property["IS_ADDRESS"] !== "Y") continue;
                    echo htmlspecialcharsbx($property["VALUE"]);
                ?>
            <?php endforeach;?>
        </div>
    </div>

	<?
	$javascriptParams = array(
		"url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
		"templateFolder" => CUtil::JSEscape($templateFolder),
		"templateName" => $this->__component->GetTemplateName(),
		"paymentList" => $paymentData,
		"returnUrl" => $arResult['RETURN_URL'],
	);
	$javascriptParams = CUtil::PhpToJSObject($javascriptParams);
	?>
	<script>
		BX.Sale.PersonalOrderComponent.PersonalOrderDetail.init(<?=$javascriptParams?>);
	</script>
<?
}
?>

