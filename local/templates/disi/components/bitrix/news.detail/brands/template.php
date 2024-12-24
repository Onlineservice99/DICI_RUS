<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$APPLICATION->SetPageProperty("brandName", $arResult['NAME']);
?>
<div class="row no-gutters align-items-center pb-16 pb-lg-28">
    <h1 class="mb-16 mr-40"><?=$arResult["NAME"]?></h1>
    <div class="p-xs bg-white px-16 py-8 rounded-lg mb-12">Товаров: <?=$arResult["PRODUCTS_COUNT"]?></div>
</div>
<div class="row mb-32 mb-lg-56">
    <div class="col-xl-9 mb-32 mb-xl-0">
        <?=$arResult["DETAIL_TEXT"]?>
    </div>
    <div class="col-xl-3 align-self-end">
        <div class="logo-brand logo-brand--border">
            <img src="<?=(!empty($arResult["PREVIEW_PICTURE"]["SRC"])) ? $arResult["PREVIEW_PICTURE"]["SRC"] : SITE_TEMPLATE_PATH.'/assets/img/no_photo.png'?>" alt="<?=$arResult["NAME"]?>">
        </div>
    </div>
</div>