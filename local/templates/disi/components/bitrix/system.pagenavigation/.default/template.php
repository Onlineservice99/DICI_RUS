<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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
if (!$arResult["NavShowAlways"]) {
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false)) {
        return;
    }
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"] . "&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?" . $arResult["NavQueryString"] : "");
?>
<div class="pagination d-none d-sm-flex <?=(CSite::InDir(SITE_DIR.'news/') ? 'pagination--white mb-40 mb-lg-80' : '')?>">

    <? if ($arResult["NavPageNomer"] > 1): ?>

        <? if ($arResult["bSavePage"]): ?>
            <a
                    class="pagination__link pagination__link--btn"
                    href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>">
                <svg class="icon icon-chevron-up">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
                </svg>
            </a>
        <? else: ?>
            <? if ($arResult["NavPageNomer"] > 2): ?>
                <a
                        class="pagination__link pagination__link--btn"
                        href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>">
                    <svg class="icon icon-chevron-up">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
                    </svg>
                </a>
            <? else: ?>
                <a
                        class="pagination__link pagination__link--btn"
                        href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">
                    <svg class="icon icon-chevron-up">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
                    </svg>
                </a>
            <? endif ?>
        <? endif ?>
    <? endif ?>

    <? while ($arResult["nStartPage"] <= $arResult["nEndPage"]): ?>

        <? if ($arResult["nStartPage"] == $arResult["NavPageNomer"]): ?>
            <div class="pagination__link is-active">&nbsp;<?= $arResult["nStartPage"] ?>&nbsp;</div>&nbsp;
        <? elseif ($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false): ?>
            <a class="pagination__link"
               href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"><?= $arResult["nStartPage"] ?></a>&nbsp;
        <? else: ?>
            <a class="pagination__link"
               href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>"><?= $arResult["nStartPage"] ?></a>&nbsp;
        <? endif ?>
        <? $arResult["nStartPage"]++ ?>
    <? endwhile ?>


    <? if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]): ?>
        <a class="pagination__link pagination__link--btn"
           href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>"
        >
            <svg class="icon icon-chevron-up">
                <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
            </svg>
        </a>
    <? endif ?>

</div>


<div class="pagination d-sm-none">
    <? if ($arResult["NavPageNomer"] > 1): ?>

        <? if ($arResult["bSavePage"]): ?>
            <a
                    class="pagination__link pagination__link--btn"
                    href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>">
                <svg class="icon icon-chevron-up">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
                </svg>
            </a>
        <? else: ?>
            <? if ($arResult["NavPageNomer"] > 2): ?>
                <a
                        class="pagination__link pagination__link--btn"
                        href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>">
                    <svg class="icon icon-chevron-up">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
                    </svg>
                </a>
            <? else: ?>
                <a
                        class="pagination__link pagination__link--btn"
                        href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">
                    <svg class="icon icon-chevron-up">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
                    </svg>
                </a>
            <? endif ?>
        <? endif ?>
    <? endif ?>

    <? while ($arResult["nStartPage"] <= $arResult["nEndPage"]): ?>

        <? if ($arResult["nStartPage"] == $arResult["NavPageNomer"]): ?>
            <div class="pagination__link is-active">&nbsp;<?= $arResult["nStartPage"] ?>&nbsp;</div>&nbsp;
        <? elseif ($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false): ?>
            <a class="pagination__link"
               href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"><?= $arResult["nStartPage"] ?></a>&nbsp;
        <? else: ?>
            <a class="pagination__link"
               href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>"><?= $arResult["nStartPage"] ?></a>&nbsp;
        <? endif ?>
        <? $arResult["nStartPage"]++ ?>
    <? endwhile ?>


    <? if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]): ?>
    <a class="pagination__link pagination__link--btn"
       href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>"
    >
        <svg class="icon icon-chevron-up">
            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
        </svg>
    </a>
    <? endif ?>
</div>