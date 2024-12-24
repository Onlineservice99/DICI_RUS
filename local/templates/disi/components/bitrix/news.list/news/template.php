<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="row">
    <?php
    foreach ($arResult['ITEMS'] as $arItem):?>
        <?php
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="col-sm-6 col-xl-4 d-flex pb-32" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="card-news">
                <div class="card-news__head p-md text-gray d-flex mb-24">
                    <div class="card-news__date mr-auto"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
                    <div class="card-news__type text-uppercase after_news <?=($arItem["SECTION_CODE"] == "posts") ? 'text-blue' : 'text-violet'?>"></div>
                </div>
                <a class="card-news__title link link--stretch h4" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
                <div class="card-news__text"><?=$arItem['PREVIEW_TEXT']?></div>
                <div class="card-news__img-wrap">
                    <img src="<?=(!empty($arItem["PREVIEW_PICTURE"]["SRC"])) ? $arItem["PREVIEW_PICTURE"]["SRC"] : SITE_TEMPLATE_PATH.'/assets/img/no_photo.png'?>" alt="<?=$arItem['NAME']?>">
                </div>
            </div>
        </div>
    <?php endforeach;?>
</div>
<?=$arResult['NAV_STRING']?>