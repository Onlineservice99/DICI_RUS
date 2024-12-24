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
?>
<div class="hero">
    <img src="<?=(!empty($arResult["DETAIL_PICTURE"]["SRC"])) ? $arResult["DETAIL_PICTURE"]["SRC"] : SITE_TEMPLATE_PATH.'/assets/img/no_photo.png'?>" alt="<?=$arResult["NAME"]?>">
    <h1 class="h2 hero__title"><?=$arResult["NAME"]?></h1>
</div>
<div class="section__article-text">
    <?=$arResult["DETAIL_TEXT"]?>
</div>