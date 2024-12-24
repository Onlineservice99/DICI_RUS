<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="container mb-52 mb-lg-100">
    <div class="row">
        <?php foreach ($arResult['ITEMS'] as $item):?>
        <div class="col-xl-4 d-flex pb-16">
            <div class="privilege privilege--gray">
                <img class="privilege__ic" src="<?=CFile::GetPath($item['PROPERTIES']['FILE']['VALUE'])?>" alt="">
                <div class="pl-16 pl-sm-24">
                    <div class="h4 mb-8"><?=$item['NAME']?></div>
                    <p class="p-md mb-0"><?=$item['PREVIEW_TEXT']?></p>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>