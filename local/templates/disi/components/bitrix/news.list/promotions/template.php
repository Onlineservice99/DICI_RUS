<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="row">
    <?php foreach ($arResult['ITEMS'] as $arItem):?>
        <?php
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="col-sm-6 col-xl-4 d-flex pb-32" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="card-news pt-24">
                <div class="card-news__head p-md d-flex mb-24">
                    <?php if(!empty($arItem["FIELDS"]["DATE_ACTIVE_FROM"])):?>
                        <div class="mr-auto">
                            <div class="p-xs text-gray">Начало</div><?=$arItem["DATE_START"]?>
                        </div>
                    <?php endif?>
                    <?php if(!empty($arItem["FIELDS"]["DATE_ACTIVE_TO"])):?>
                        <div class="text-right">
                            <div class="p-xs text-gray">Конец</div><?=$arItem["DATE_END"]?>
                        </div>
                    <?php endif?>
                </div>
                <a class="card-news__title link link--stretch h4" href="#sale-<?=$arItem['ID']?>" data-fancybox data-touch="false"><?=$arItem['NAME']?></a>
                <div class="card-news__text"><?=$arItem['PREVIEW_TEXT']?></div>
                <div class="card-news__img-wrap">
                    <img src="<?=(!empty($arItem["PREVIEW_PICTURE"]["SRC"])) ? $arItem["PREVIEW_PICTURE"]["SRC"] : SITE_TEMPLATE_PATH.'/assets/img/no_photo.png'?>" alt="<?=$arItem['NAME']?>">
                </div>
                <div class="d-none">
                    <div class="popup popup--sale" id="sale-<?=$arItem['ID']?>">
                        <img class="w-100 mb-32 mb-lg-56" src="<?=$arItem['DETAIL_PICTURE']['SRC']?>">
                        <div class="popup__text-scroll article-content" data-scrollbar>
                            <h3><?=$arItem['NAME']?></h3>
                            <br>
                            <?=$arItem['DETAIL_TEXT']?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>
</div>
<?=$arResult['NAV_STRING']?>