<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="<?=($arParams['CLASS_ROW'] != '' ? $arParams['CLASS_ROW'] : 'row mb-4 mb-md-24')?>">
    <?php foreach ($arResult['ITEMS'] as $item):?>
    <div class="col-lg-4 d-flex pb-16">
        <div class="bg-light d-flex align-items-center bglight rounded px-16 px-sm-24 py-16 w-100">
            <svg class="icon icon-rotate text-red mr-16 flex-shrink-0">
                <use xlink:href="<?=$item['PROPERTIES']['PICTURE']['VALUE']?>"></use>
            </svg>
            <div class="p-md"><?=$item['NAME']?></div>
        </div>
    </div>
    <?php endforeach;?>
</div>