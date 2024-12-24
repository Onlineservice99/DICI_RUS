<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

$isAjax = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$isAjax = (
		(isset($_POST['ajax_action']) && $_POST['ajax_action'] == 'Y')
		|| (isset($_POST['compare_result_reload']) && $_POST['compare_result_reload'] == 'Y')
	);
}

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());
$itemSectionFilter = (int) $request->get('section') > 0 ? (int) $request->get('section') : 0;

if ($itemSectionFilter > 0) {
    foreach ($arResult['ITEMS'] as $item => $i) {
        if ($i['IBLOCK_SECTION_ID'] != $itemSectionFilter) {
            unset($arResult['ITEMS'][$item]);
        }
    }
}
?>




<?php
if ($isAjax)
{
	$APPLICATION->RestartBuffer();
}
?>
<p style="display: none"><font class="notetext">Список сравниваемых элементов пуст.</font></p>
    <div class="row align-items-center">
        <div class="col-lg-5 col-xl-4 col-xxl-3 mb-24 mb-lg-12">
            <div class="select">
                <div class="select__label">Подкатегория сравнения</div>
                <select class="js-select-default" id="select-category-compare">
                    <?php
                    $uri->deleteParams(['section']);
                    ?>
                    <option
                        <?=($itemSectionFilter === 0 ? 'selected="selected"' : '')?>
                            value=""
                            data-url="<?=$uri->getUri()?>">Категория не выбрана</option>
                    <?php foreach ($arResult['SECTIONS'] as $key=>$s):?>
                        <?php
                            $uri->deleteParams(['section']);
                            $uri->addParams(['section' => $key]);
                        ?>
                        <option
                            <?=($itemSectionFilter == $key ? 'selected="selected"' : '')?>
                                value="<?=$key?>"
                                data-url="<?=$uri->getUri()?>">
                            <?=$s?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>

        <?php
        $uri->deleteParams(['section']);
        $uri->addParams(['section' => $itemSectionFilter]);
        ?>

        <div class="col-auto mb-12">
            <div class="form-block mb-16">
                <label class="form-block__checkbox form-block__checkbox--radio">
                    <?php
                    $uri->deleteParams(['DIFFERENT']);
                    $uri->addParams(['DIFFERENT' => "N"]);
                    ?>
                    <input class="js-validation" type="radio" name="diff_n" value="diff_n" <?=($arResult['DIFFERENT'] != 'Y') ? 'checked' : ''?> onclick="window.location='<?=$uri->getUri()?>';">
                    <span class="p-md">
                        <?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?>
                    </span>
                </label>
            </div>
            <div class="form-block">
                <label class="form-block__checkbox form-block__checkbox--radio">
                    <?php
                    $uri->deleteParams(['DIFFERENT']);
                    $uri->addParams(['DIFFERENT' => "Y"]);
                    ?>
                    <input class="js-validation" type="radio" name="diff_y" value="diff_y" <?=($arResult['DIFFERENT'] == 'Y') ? 'checked' : ''?> onclick="window.location='<?=$uri->getUri()?>';">
                    <span class="p-md">
                        <?=GetMessage("CATALOG_ONLY_DIFFERENT")?>
                    </span>
                </label>
            </div>
        </div>
    </div>

    <div class="carousel carousel--compare">
        <div class="compare-item compare-item--set ml-0">
            <?php
            foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty):
                $showRow = true;
                if ($arResult['DIFFERENT'])
                {
                    $arCompare = array();
                    foreach($arResult["ITEMS"] as $arElement)
                    {
                        $arPropertyValue = $arElement["PROPERTIES"][$code]["VALUE"];
                        if (is_array($arPropertyValue))
                        {
                            sort($arPropertyValue);
                            $arPropertyValue = implode(" / ", $arPropertyValue);
                        }
                        $arCompare[] = $arPropertyValue;
                    }
                    unset($arElement);
                    $showRow = (count(array_unique($arCompare)) > 1);
                }?>

                <?php if ($showRow):?>
                    <div class="compare-item__cell compare-item__cell--title"><?=$arProperty["NAME"]?></div>
                <?php endif?>
            <?php endforeach;?>
        </div>
        <div class="carousel__scroll overflow-hidden" data-scrollbar data-continuous-scrolling="true">
            <div class="d-flex compare-items-row">
                <?php foreach ($arResult["ITEMS"] as $key => $arElement):?>
                    <div class="compare-item">
                        <button class="compare-item__del js-delete" type="button" onclick="CatalogCompareObj.delete('<?=CUtil::JSEscape($arElement['~DELETE_URL'])?>', this);">
                            <svg class="icon icon-delete icon-20">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-delete"></use>
                            </svg>
                        </button>
                        <div class="compare-item__border">
                            <a class="link" href="<?=$arElement["DETAIL_PAGE_URL"]?>">
                                <div class="compare-item__img-wrap">
                                    <img src="<?=$arElement["FIELDS"]["PREVIEW_PICTURE"]["SRC"]?>" alt="">
                                </div>
                                <div class="compare-item__title"><?=$arElement["NAME"]?></div>
                            </a>
                            <div class="compare-item__price"><?=$arElement['MIN_PRICE']['PRINT_DISCOUNT_VALUE']?></div>
                            <a class="compare-item__add" href="#" onclick="event.preventDefault()">
                                <div class="card__controls compare <?= \electroset1\Content::checkProductInBasket($arElement['ID']) ? 'in_basket' : '' ?>" data-element-id="<?= $arElement['ID'] ?>">

                                    <?php if($arElement["CAN_BUY"] || \electroset1\Content::checkProductInBasket($arElement['ID'])):?>
                                        <a class="card__add" href="#" onclick="event.preventDefault()">
                                            <div class="spin spin--card">
                                                                                        <input class="js-spin-count" name="quantity" type="number" value="<?= \electroset1\Content::checkProductInBasket($arElement['ID'], true) ?>" data-min="0"
                                                                                               data-max="<?=$arElement['CATALOG_QUANTITY'] ?>" data-step="1" readonly>
                                            </div>
                                            <div class="btn-add-basket js-add-basket">В&nbsp;корзину</div>
                                        </a>
                                        <a href="/personal/cart/" class="btn-go-basket">В&nbsp;корзине <span>Перейти</span></a>
                                    <?php else:?>
                                        <a href="/local/ajax/popups/onRequest.php?id=<?= $arResult['ITEM']['ID'] ?>" class="btn-add-basket" data-touch="false"
                                           data-type="ajax" data-fancybox>Запрос наличия</a>
                                    <?php endif;?>
                                </div>
                            </a>
                        </div>
                        <?
                        if (!empty($arResult["SHOW_PROPERTIES"])){
                            foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty)
                            {
                                $showRow = true;
                                if ($arResult['DIFFERENT'])
                                {
                                    $arCompare = array();
                                    foreach($arResult["ITEMS"] as $arEl)
                                    {
                                        $arPropertyValue = $arEl["DISPLAY_PROPERTIES"][$code]["VALUE"];
                                        if (is_array($arPropertyValue))
                                        {
                                            sort($arPropertyValue);
                                            $arPropertyValue = implode(" / ", $arPropertyValue);
                                        }
                                        $arCompare[] = $arPropertyValue;
                                    }
                                    unset($arEl);
                                    $showRow = (count(array_unique($arCompare)) > 1);
                                }

                                if ($showRow)
                                {
                                    ?>

                                    <div class="compare-item__cel">
                                        <?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
                                    </div>

                                    <?
                                }
                            }
                        }
                        ?>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
<?php
if ($isAjax)
{
	die();
}
?>
<script type="text/javascript">
    // $('.scroll-content').scroll(function() {
    //     $(this).find('.sticky').css('left', $(this).scrollLeft());
    // });
	var CatalogCompareObj = new BX.Iblock.Catalog.CompareClass("bx_catalog_compare_block", '<?=CUtil::JSEscape($arResult['~COMPARE_URL_TEMPLATE']); ?>');
</script>