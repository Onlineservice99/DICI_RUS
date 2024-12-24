<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
global $USER;
$x = 0;
?>
<div class="ajax-container">
    <div class="product-row mb-56 mb-lg-80">
            <?php foreach ($arResult["ITEMS"] as $key=>$item):?>
            <?php if ($key%8 == 0 && $key > 0):?>
                </div>
                <div
                        class="<?=$arResult['BANNERS'][$x]['PROPS']['ADD_CLASS']['VALUE']?>"
                    <?=($arResult['BANNERS'][$x]['PREVIEW_PICTURE'] > 0 ? 'style="background-image:url('.CFile::GetPath($arResult['BANNERS'][$x]['PREVIEW_PICTURE']).')"' : '')?>
                >
					<?if($arResult["BANNERS"][$x]["PREVIEW_PICTURE"]):?>
					<img src="<?=CFile::GetPath($arResult['BANNERS'][$x]['PREVIEW_PICTURE'])?>" class="banner__background banner__background_desktop">
					<img src="<?=CFile::GetPath($arResult['BANNERS'][$x]['DETAIL_PICTURE'])?>" class="banner__background banner__background_mobile">
					<?endif?>
                    <?=$arResult['BANNERS'][$x]['DETAIL_TEXT']?>
                </div>
                <?/*$APPLICATION->IncludeFile(SITE_DIR."include/catalog/banner-1.php");*/?>
                <?$x++;?>
                <div class="product-row mb-56 mb-lg-80">
            <?php endif;?>
            <?php $APPLICATION->IncludeComponent(
                "bitrix:catalog.item",
                "element",
                array(
                    "RESULT" => [
                            "ITEM" => $item,
                            'AREA_ID' => $this->GetEditAreaId($item['ID']),
							'ADDITIONAL_CLASSES' => $arParams["ADDITIONAL_ITEM_CLASSES"]
                    ]
                )
            );?>
            <?php endforeach;?>
    </div>
</div>
<div data-pagination-num="<?=$navParams['NavNum']?>">
    <!-- pagination-container -->
    <?=$arResult['NAV_STRING']?>
    <!-- pagination-container -->
</div>