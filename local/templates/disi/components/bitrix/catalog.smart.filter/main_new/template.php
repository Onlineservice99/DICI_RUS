<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

$FILTER = $GLOBALS[$arParams['FILTER_NAME']];
$phone = \Bitrix\Main\Config\Option::get("meven.info", "phone");
?>
<aside class="section__filters-aside d-none d-xl-block">
    <div class="bx-filter">
        <form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter filters" id="filters" data-form="filters">
            <div class="container px-xl-0">
                <div class="d-xl-none mb-24">
                    <div class="header px-0 mb-0">
                        <div class="header__row">
                            <a class="btn btn--menu mr-md-16 d-xl-none" href="#mob-menu" data-fancybox-close>
                                <svg class="icon icon-close">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                                </svg>
                            </a>
                            <div class="header__logo-wrap">
                                <a class="header__logo" href="#">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/logo-disi.svg" alt="">
                                </a>
                                <a class="btn btn--black btn--catalog mr-md-32" href="#catalog" data-fancybox data-touch="false" data-base-class="fancybox-catalog" data-close-existing="true">Каталог</a>
                            </div>
                            <a class="header__icon-tel d-xl-none" href="tel:<?=$phone?>">
                                <svg class="icon icon-phone">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-phone"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <?php
                    $APPLICATION->IncludeComponent(
                        "meven:sort",
                        "filter_mobile",
                        [
                        ],
                        $component,
                        false
                    );
                    ?>
                </div>
                <div class="d-flex flex-column">
                    <?php foreach($arResult["HIDDEN"] as $arItem):?>
                        <input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
                    <?php endforeach;?>

                    <?php
                    foreach($arResult["ITEMS"] as $key=>$arItem) {
                        if (
                            empty($arItem["VALUES"])
                            || isset($arItem["PRICE"])
                        )
                            continue;

                        if (
                            $arItem["DISPLAY_TYPE"] == "A"
                            && (
                                $arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
                            )
                        )
                            continue;

                    }
                    //prices
                    foreach($arResult["ITEMS"] as $key=>$arItem) {
                        $key = $arItem["ENCODED_ID"];
                        if(isset($arItem["PRICE"])):
                            if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
                                continue;
                            $APPLICATION->SetPageProperty('minPrice', $arItem['VALUES']['MIN']['VALUE']);
                            $step_num = 1;
                            $step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / $step_num;
                            $prices = array();
                            if (Bitrix\Main\Loader::includeModule("currency"))
                            {
                                for ($i = 0; $i < $step_num; $i++)
                                {
                                    $prices[$i] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MIN"]["VALUE"] + $step*$i, $arItem["VALUES"]["MIN"]["CURRENCY"], false);
                                }
                                $prices[$step_num] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MAX"]["VALUE"], $arItem["VALUES"]["MAX"]["CURRENCY"], false);
                            }
                            else
                            {
                                $precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
                                for ($i = 0; $i < $step_num; $i++)
                                {
                                    $prices[$i] = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step*$i, $precision, ".", "");
                                }
                                $prices[$step_num] = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
                            }

                            $isFilter = isset($FILTER['><CATALOG_PRICE_' . $arItem['ID']]);
                            ?>

                            <div class="filters__block bx-filter-parameters-box">
		                            <span class="bx-filter-container-modef"></span>
                                <a class="filters__title link" href="#ff1" data-toggle="collapse" aria-expanded="true">
                                    Цена
                                    <svg class="icon icon-chevron-up">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
                                    </svg>
                                </a>
                                <div class="collapse show" id="ff<?=$arItem['ID']?>">
                                    <div class="form-block form-block--range">
                                        <input
                                            class="min-price"
                                            id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
                                            type="text"
                                            name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
                                            value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
                                            placeholder="<?echo $arItem["VALUES"]["MIN"]["VALUE"]?>"
                                            onkeyup="smartFilter.keyup(this)"
                                        />
                                        <input
                                            class="max-price"
                                            id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
                                            type="text"
                                            name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
                                            value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
                                            placeholder="<?echo $arItem["VALUES"]["MAX"]["VALUE"]?>"
                                            onkeyup="smartFilter.keyup(this)"
                                        />
                                    </div>
                                    <div class="range bx-ui-slider-track-container">
                                        <div class="bx-ui-slider-track" id="drag_track_<?=$key?>">
                                            <div class="bx-ui-slider-pricebar-vd" style="left: 0;right: 0;" id="colorUnavailableActive_<?=$key?>"></div>
                                            <div class="bx-ui-slider-pricebar-vn" style="left: 0;right: 0;" id="colorAvailableInactive_<?=$key?>"></div>
                                            <div class="bx-ui-slider-pricebar-v"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>
                                            <div class="bx-ui-slider-range" id="drag_tracker_<?=$key?>"  style="left: 0%; right: 0%;">
                                                <a class="bx-ui-slider-handle left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
                                                <a class="bx-ui-slider-handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right pb-16">
                                        <?/*<button <?if(!$isFilter):?>style="display:none"<?endif;?>
                                            class="filters__clear"
                                            id="clear_<?=$arItem['ID']?>"
                                            data-code="<?=$arItem['CODE']?>"
                                        >
                                            Очистить
                                            <svg class="icon icon-close icon-16 ml-4">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                                            </svg>
                                        </button>*/?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $arJsParams = array(
                                "leftSlider" => 'left_slider_'.$key,
                                "rightSlider" => 'right_slider_'.$key,
                                "tracker" => "drag_tracker_".$key,
                                "trackerWrap" => "drag_track_".$key,
                                "minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
                                "maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
                                "minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
                                "maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
                                "curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                                "curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                                "fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
                                "fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
                                "precision" => $precision,
                                "colorUnavailableActive" => 'colorUnavailableActive_'.$key,
                                "colorAvailableActive" => 'colorAvailableActive_'.$key,
                                "colorAvailableInactive" => 'colorAvailableInactive_'.$key,
                            );
                            ?>
                            <script type="text/javascript">
                                BX.ready(function(){
                                    window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
                                });
                            </script>
                        <?php endif;
                    }

                    //not prices
                    foreach($arResult["ITEMS"] as $key=>$arItem):
                        if(
                            empty($arItem["VALUES"])
                            || isset($arItem["PRICE"])
                        )
                            continue;

                        if (
                            $arItem["DISPLAY_TYPE"] == "A"
                            && (
                                $arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
                            )
                        )
                            continue;


                        $isFilter = isset($FILTER['=PROPERTY_' . $arItem['ID']]);
                        
                        ?>
                    <div class="bx-filter-parameters-box">
                        <span class="bx-filter-container-modef"></span>
                        <?php
                        $arCur = current($arItem["VALUES"]);
                        switch ($arItem["DISPLAY_TYPE"])
                        {
                            case "A"://NUMBERS_WITH_SLIDER
                                ?>
                            <div class="filters__block">
                                <a class="filters__title link" href="#ff<?=$arItem['ID']?>" data-toggle="collapse" aria-expanded="true">
                                    <?=$arItem['NAME']?>
                                    <svg class="icon icon-chevron-up">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
                                    </svg>
                                </a>
                                <div class="collapse show" id="ff<?=$arItem['ID']?>">
                                    <div class="form-block form-block--range">
                                        <input
                                            class="min-price filters__input mb-16"
                                            type="text"
                                            name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
                                            id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
                                            value="<?echo $arItem["VALUES"]["MIN"]["VALUE"]?>"
                                            size="5"
                                            onkeyup="smartFilter.keyup(this)"
                                        />
                                        <input
                                            class="max-price filters__input mb-16"
                                            type="text"
                                            name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
                                            id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
                                            value="<?echo $arItem["VALUES"]["MAX"]["VALUE"]?>"
                                            size="5"
                                            onkeyup="smartFilter.keyup(this)"
                                        />
                                    </div>

                                    <div class="range bx-ui-slider-track-container">
                                        <div class="bx-ui-slider-track" id="drag_track_<?=$key?>">
                                            <?php
                                            $precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
                                            $step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 4;
                                            $value1 = number_format($arItem["VALUES"]["MIN"]["VALUE"], $precision, ".", "");
                                            $value2 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step, $precision, ".", "");
                                            $value3 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 2, $precision, ".", "");
                                            $value4 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 3, $precision, ".", "");
                                            $value5 = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
                                            ?>

                                            <div class="bx-ui-slider-pricebar-vd" style="left: 0;right: 0;" id="colorUnavailableActive_<?=$key?>"></div>
                                            <div class="bx-ui-slider-pricebar-vn" style="left: 0;right: 0;" id="colorAvailableInactive_<?=$key?>"></div>
                                            <div class="bx-ui-slider-pricebar-v"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>
                                            <div class="bx-ui-slider-range" 	id="drag_tracker_<?=$key?>"  style="left: 0;right: 0;">
                                                <a class="bx-ui-slider-handle left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
                                                <a class="bx-ui-slider-handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right pb-16">
                                        <button <?if(!$isFilter):?>style="display:none"<?endif;?>
                                            class="filters__clear"
                                            id="clear_<?=$arItem['ID']?>"
                                            data-code="<?=$arItem['CODE']?>"
                                        >
                                            Очистить
                                            <svg class="icon icon-close icon-16 ml-4">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <?php
                                $arJsParams = array(
                                    "leftSlider" => 'left_slider_'.$key,
                                    "rightSlider" => 'right_slider_'.$key,
                                    "tracker" => "drag_tracker_".$key,
                                    "trackerWrap" => "drag_track_".$key,
                                    "minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
                                    "maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
                                    "minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
                                    "maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
                                    "curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                                    "curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                                    "fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
                                    "fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
                                    "precision" => $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0,
                                    "colorUnavailableActive" => 'colorUnavailableActive_'.$key,
                                    "colorAvailableActive" => 'colorAvailableActive_'.$key,
                                    "colorAvailableInactive" => 'colorAvailableInactive_'.$key,
                                );
                                ?>
                                <script type="text/javascript">
                                    BX.ready(function(){
                                        window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
                                    });
                                </script>
                                <?php
                                break;
                            case "B"://NUMBERS
                                ?>
                                <div class="col-xs-6 bx-filter-parameters-box-container-block bx-left">
                                    <i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_FROM")?></i>
                                    <div class="bx-filter-input-container">
                                        <input
                                            class="min-price"
                                            type="text"
                                            name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
                                            id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
                                            value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
                                            size="5"
                                            onkeyup="smartFilter.keyup(this)"
                                            />
                                    </div>
                                </div>
                                <div class="col-xs-6 bx-filter-parameters-box-container-block bx-right">
                                    <i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_TO")?></i>
                                    <div class="bx-filter-input-container">
                                        <input
                                            class="max-price"
                                            type="text"
                                            name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
                                            id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
                                            value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
                                            size="5"
                                            onkeyup="smartFilter.keyup(this)"
                                            />
                                    </div>
                                </div>
                                <?
                                break;
                            case "G"://CHECKBOXES_WITH_PICTURES
                                ?>
                                <div class="col-xs-12">
                                    <div class="bx-filter-param-btn-inline">
                                    <?foreach ($arItem["VALUES"] as $val => $ar):?>
                                        <input
                                            style="display: none"
                                            type="checkbox"
                                            name="<?=$ar["CONTROL_NAME"]?>"
                                            id="<?=$ar["CONTROL_ID"]?>"
                                            value="<?=$ar["HTML_VALUE"]?>"
                                            <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                        />
                                        <?
                                        $class = "";
                                        if ($ar["CHECKED"])
                                            $class.= " bx-active";
                                        if ($ar["DISABLED"])
                                            $class.= " disabled";
                                        ?>
                                        <label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label <?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
                                            <span class="bx-filter-param-btn bx-color-sl">
                                                <?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                <span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
                                                <?endif?>
                                            </span>
                                        </label>
                                    <?endforeach?>
                                    </div>
                                </div>
                                <?
                                break;
                            case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
                                ?>
                                <div class="col-xs-12">
                                    <div class="bx-filter-param-btn-block">
                                    <?foreach ($arItem["VALUES"] as $val => $ar):?>
                                        <input
                                            style="display: none"
                                            type="checkbox"
                                            name="<?=$ar["CONTROL_NAME"]?>"
                                            id="<?=$ar["CONTROL_ID"]?>"
                                            value="<?=$ar["HTML_VALUE"]?>"
                                            <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                        />
                                        <?
                                        $class = "";
                                        if ($ar["CHECKED"])
                                            $class.= " bx-active";
                                        if ($ar["DISABLED"])
                                            $class.= " disabled";
                                        ?>
                                        <label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
                                            <span class="bx-filter-param-btn bx-color-sl">
                                                <?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                    <span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
                                                <?endif?>
                                            </span>
                                            <span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
                                            if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                                ?> (<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                            endif;?></span>
                                        </label>
                                    <?endforeach?>
                                    </div>
                                </div>
                                <?
                                break;
                            case "P"://DROPDOWN
                                $checkedItemExist = false;
                                ?>
                                <div class="col-xs-12">
                                    <div class="bx-filter-select-container">
                                        <div class="bx-filter-select-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
                                            <div class="bx-filter-select-text" data-role="currentOption">
                                                <?
                                                foreach ($arItem["VALUES"] as $val => $ar)
                                                {
                                                    if ($ar["CHECKED"])
                                                    {
                                                        echo $ar["VALUE"];
                                                        $checkedItemExist = true;
                                                    }
                                                }
                                                if (!$checkedItemExist)
                                                {
                                                    echo GetMessage("CT_BCSF_FILTER_ALL");
                                                }
                                                ?>
                                            </div>
                                            <div class="bx-filter-select-arrow"></div>
                                            <input
                                                style="display: none"
                                                type="radio"
                                                name="<?=$arCur["CONTROL_NAME_ALT"]?>"
                                                id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
                                                value=""
                                            />
                                            <?foreach ($arItem["VALUES"] as $val => $ar):?>
                                                <input
                                                    style="display: none"
                                                    type="radio"
                                                    name="<?=$ar["CONTROL_NAME_ALT"]?>"
                                                    id="<?=$ar["CONTROL_ID"]?>"
                                                    value="<? echo $ar["HTML_VALUE_ALT"] ?>"
                                                    <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                                />
                                            <?endforeach?>
                                            <div class="bx-filter-select-popup" data-role="dropdownContent" style="display: none;">
                                                <ul>
                                                    <li>
                                                        <label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
                                                            <? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
                                                        </label>
                                                    </li>
                                                <?
                                                foreach ($arItem["VALUES"] as $val => $ar):
                                                    $class = "";
                                                    if ($ar["CHECKED"])
                                                        $class.= " selected";
                                                    if ($ar["DISABLED"])
                                                        $class.= " disabled";
                                                ?>
                                                    <li>
                                                        <label for="<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" data-role="label_<?=$ar["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')"><?=$ar["VALUE"]?></label>
                                                    </li>
                                                <?endforeach?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?
                                break;
                            case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
                                ?>
                                <div class="col-xs-12">
                                    <div class="bx-filter-select-container">
                                        <div class="bx-filter-select-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
                                            <div class="bx-filter-select-text fix" data-role="currentOption">
                                                <?
                                                $checkedItemExist = false;
                                                foreach ($arItem["VALUES"] as $val => $ar):
                                                    if ($ar["CHECKED"])
                                                    {
                                                    ?>
                                                        <?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                            <span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
                                                        <?endif?>
                                                        <span class="bx-filter-param-text">
                                                            <?=$ar["VALUE"]?>
                                                        </span>
                                                    <?
                                                        $checkedItemExist = true;
                                                    }
                                                endforeach;
                                                if (!$checkedItemExist)
                                                {
                                                    ?><span class="bx-filter-btn-color-icon all"></span> <?
                                                    echo GetMessage("CT_BCSF_FILTER_ALL");
                                                }
                                                ?>
                                            </div>
                                            <div class="bx-filter-select-arrow"></div>
                                            <input
                                                style="display: none"
                                                type="radio"
                                                name="<?=$arCur["CONTROL_NAME_ALT"]?>"
                                                id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
                                                value=""
                                            />
                                            <?foreach ($arItem["VALUES"] as $val => $ar):?>
                                                <input
                                                    style="display: none"
                                                    type="radio"
                                                    name="<?=$ar["CONTROL_NAME_ALT"]?>"
                                                    id="<?=$ar["CONTROL_ID"]?>"
                                                    value="<?=$ar["HTML_VALUE_ALT"]?>"
                                                    <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                                />
                                            <?endforeach?>
                                            <div class="bx-filter-select-popup" data-role="dropdownContent" style="display: none">
                                                <ul>
                                                    <li style="border-bottom: 1px solid #e5e5e5;padding-bottom: 5px;margin-bottom: 5px;">
                                                        <label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
                                                            <span class="bx-filter-btn-color-icon all"></span>
                                                            <? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
                                                        </label>
                                                    </li>
                                                <?
                                                foreach ($arItem["VALUES"] as $val => $ar):
                                                    $class = "";
                                                    if ($ar["CHECKED"])
                                                        $class.= " selected";
                                                    if ($ar["DISABLED"])
                                                        $class.= " disabled";
                                                ?>
                                                    <li>
                                                        <label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')">
                                                            <?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                                <span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
                                                            <?endif?>
                                                            <span class="bx-filter-param-text">
                                                                <?=$ar["VALUE"]?>
                                                            </span>
                                                        </label>
                                                    </li>
                                                <?endforeach?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?
                                break;
                            case "K"://RADIO_BUTTONS
                                ?>
                                <div class="col-xs-12">
                                    <div class="radio">
                                        <label class="bx-filter-param-label" for="<? echo "all_".$arCur["CONTROL_ID"] ?>">
                                            <span class="bx-filter-input-checkbox">
                                                <input
                                                    type="radio"
                                                    value=""
                                                    name="<? echo $arCur["CONTROL_NAME_ALT"] ?>"
                                                    id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
                                                    onclick="smartFilter.click(this)"
                                                />
                                                <span class="bx-filter-param-text"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
                                            </span>
                                        </label>
                                    </div>
                                    <?foreach($arItem["VALUES"] as $val => $ar):?>
                                        <div class="radio">
                                            <label data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label" for="<? echo $ar["CONTROL_ID"] ?>">
                                                <span class="bx-filter-input-checkbox <? echo $ar["DISABLED"] ? 'disabled': '' ?>">
                                                    <input
                                                        type="radio"
                                                        value="<? echo $ar["HTML_VALUE_ALT"] ?>"
                                                        name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
                                                        id="<? echo $ar["CONTROL_ID"] ?>"
                                                        <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                                        onclick="smartFilter.click(this)"
                                                    />
                                                    <span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?>
                                                    <?php
                                                    if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                                        ?>&nbsp;(<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)
                                                        <?endif;?>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    <?endforeach;?>
                                </div>
                                <?
                                break;
                            case "U"://CALENDAR
                                ?>
                                <div class="col-xs-12">
                                    <div class="bx-filter-parameters-box-container-block"><div class="bx-filter-input-container bx-filter-calendar-container">
                                        <?$APPLICATION->IncludeComponent(
                                            'bitrix:main.calendar',
                                            '',
                                            array(
                                                'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
                                                'SHOW_INPUT' => 'Y',
                                                'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                                'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
                                                'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                                                'SHOW_TIME' => 'N',
                                                'HIDE_TIMEBAR' => 'Y',
                                            ),
                                            null,
                                            array('HIDE_ICONS' => 'Y')
                                        );?>
                                    </div></div>
                                    <div class="bx-filter-parameters-box-container-block"><div class="bx-filter-input-container bx-filter-calendar-container">
                                        <?$APPLICATION->IncludeComponent(
                                            'bitrix:main.calendar',
                                            '',
                                            array(
                                                'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
                                                'SHOW_INPUT' => 'Y',
                                                'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                                'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
                                                'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                                                'SHOW_TIME' => 'N',
                                                'HIDE_TIMEBAR' => 'Y',
                                            ),
                                            null,
                                            array('HIDE_ICONS' => 'Y')
                                        );?>
                                    </div></div>
                                </div>
                                <?
                                break;
                            default://CHECKBOXES
                            ?>
                            <div class="filters__block">
                                <a class="filters__title link" href="#ff<?=$arItem["ID"]?>" data-toggle="collapse" aria-expanded="true"><?=$arItem['NAME']?>
                                    <svg class="icon icon-chevron-up">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
                                    </svg>
                                </a>
                                <div class="collapse show" id="ff<?=$arItem["ID"]?>">
                                    <?php
                                    $valuesCount = count($arItem["VALUES"]); // Количество значений свойства
                                    $valuesVisible = 6; // Количество видимых значений свойства
                                    ?>
                                    <?php foreach (array_slice($arItem["VALUES"], 0, $valuesVisible) as $ar):?>
                                        <div class="form-block mb-16">
                                            <label class="form-block__checkbox form-block__checkbox--dot">
                                                <input
                                                    type="checkbox"
                                                    value="<?=$ar["HTML_VALUE"]?>"
                                                    name="<?=$ar["CONTROL_NAME"]?>"
                                                    id="<?=$ar["CONTROL_ID"]?>"
                                                    <?=$ar["CHECKED"]? 'checked="checked"': ''?>
                                                    onclick="smartFilter.click(this)"
                                                />
                                                <span>
                                                    <?=$ar['VALUE']?>
                                                    <?php if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):?>
                                                        <span data-role="count_<?=$ar["CONTROL_ID"]?>">(<?=$ar["ELEMENT_COUNT"]?>)</span>
                                                    <?php endif;?>
                                                </span>

                                            </label>
                                        </div>
                                    <?php endforeach;?>

                                    <?php if($valuesCount > $valuesVisible):?>
                                        <?php foreach (array_slice($arItem["VALUES"], $valuesVisible, $valuesCount) as $ar):?>
                                            <div class="collapse" id="ff<?=$arItem["ID"]?>-h">
                                                <div class="form-block mb-16">
                                                    <label class="form-block__checkbox form-block__checkbox--dot">
                                                        <input
                                                            type="checkbox"
                                                            value="<?=$ar["HTML_VALUE"]?>"
                                                            name="<?=$ar["CONTROL_NAME"]?>"
                                                            id="<?=$ar["CONTROL_ID"]?>"
                                                            <?=$ar["CHECKED"]? 'checked="checked"': ''?>
                                                            onclick="smartFilter.click(this)"
                                                        />
                                                        <span>
                                                            <?=$ar['VALUE']?>
                                                            <?php if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):?>
                                                                (<span data-role="count_<?=$ar["CONTROL_ID"]?>"><?=$ar["ELEMENT_COUNT"]?></span>)
                                                            <?php endif;?>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach;?>
                                        <a class="link link--underline link--more" href="#ff<?=$arItem["ID"]?>-h" data-toggle="collapse" data-show="Показать все" data-close="Скрыть"></a>
                                    <?php endif;?>

                                    <div class="text-right pb-16">
                                        <button <?if(!$isFilter):?>style="display:none"<?endif;?>
                                            class="filters__clear"
                                            id="clear_<?=$arItem['ID']?>"
                                            data-code="<?=$arItem['CODE']?>"
                                        >
                                            Очистить
                                            <svg class="icon icon-close icon-16 ml-4">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        <?php }?>
                    </div>

                    <?php endforeach;?>

                    <div class="bx-filter-button-box">
                        <div class="bx-filter-block">
                            <div class="bx-filter-parameters-box-container">
                                <input
                                        class="button_blue set"
                                        type="submit"
                                        id="set_filter"
                                        name="set_filter"
                                        value="<?=GetMessage("CT_BCSF_SET_FILTER")?>"
                                />
                                <?/*<div class="bx-filter-popup-result <?if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL") echo $arParams["POPUP_POSITION"]?>" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?> style="display: inline-block;">
                                <div class="bx-filter-popup-result <?if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL") echo $arParams["POPUP_POSITION"]?>" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?> style="display: none;">
                                    <?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
                                    <span class="arrow"></span>
                                    <br/>
                                    <a href="<?echo $arResult["FILTER_URL"]?>" target=""><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
                                </div>*/?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bx-filter-popup-result <?if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL") echo $arParams["POPUP_POSITION"]?>" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?>>
	                <div class="bx-filter-popup-result__text">
		                Найдено товаров: <span id="modef_num"><?=intval($arResult["ELEMENT_COUNT"]);?></span>
	                </div>
	                <a href="<?echo $arResult["FILTER_URL"]?>" class="bx-filter-popup-result__link">
		                <?echo GetMessage("CT_BCSF_FILTER_SHOW")?>
	                </a>
                </div>
                <button <?if(!$arParams['SMART_FILTER_PATH'] || $arParams['SMART_FILTER_PATH'] == 'clear'):?>style="display:none"<?endif;?>
                    class="btn btn--border w-100 px-0 fw-500"
                    type="submit"
                    id="del_filter"
                    name="del_filter"
                    value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>"
                >
                    Очистить все фильтры
                </button>
            </div>
        </form>
    </div>
</aside>
<script type="text/javascript">
	let smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>
