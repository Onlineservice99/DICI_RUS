<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Config\Option;

$email = Option::get("meven.info", "email");
$phone = Option::get("meven.info", "phone");
?>
<ul class="mob-menu__list">
    <?php foreach ($arResult as $item):?>
        <li>
            <a class="link" href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
        </li>
    <?php endforeach;?>
</ul>
<div class="h4 h4--line mb-20">Контакты</div>
<div class="mob-menu__contact row no-gutters mb-24">
    <svg class="icon icon-email text-blue">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-email"></use>
    </svg>
    <div class="pl-24">
        <div class="p-xs text-gray mb-4">e-mail</div>
        <a class="link p-md" href="mailto:<?=$email?>"><?=$email?></a>
    </div>
</div>
<div class="mob-menu__contact row no-gutters mb-24">
    <svg class="icon icon-phone text-yellow">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-phone"></use>
    </svg>
    <div class="pl-24">
        <div class="p-xs text-gray mb-4">телефон</div>
        <a class="link link--tel" href="tel:<?=\Meven\Info\Metods::forcall($phone)?>"><?=$phone?></a>
        <div class="p-xs text-gray">Бесплатный звонок по России</div>
    </div>
</div>
<div class="h4 h4--line mb-20 pt-12">Время работы</div>
<div class="mob-menu__contact row no-gutters mb-24">
    <svg class="icon icon-alarm text-violet">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-alarm"></use>
    </svg>
    <div class="pl-24">
        <div class="p-xs text-gray mb-4">Время указано по Абакану</div>
        <div class="p-md fw-600">Пн-Пт - <?=Option::get('meven.info', 'workpnpt-start')?> - <?=Option::get('meven.info', 'workpnpt-end')?></div>
        <div class="p-md fw-600">Сб-Вс - <?=Option::get('meven.info', 'worksbvs-start')?> - <?=Option::get('meven.info', 'worksbvs-end')?></div>
    </div>
</div>