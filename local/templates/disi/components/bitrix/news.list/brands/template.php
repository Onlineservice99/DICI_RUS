<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$x = 0;?>
<div class="carousel carousel--arrows-card carousel--brands">
    <div class="carousel__nav-brands mb-24" id="nav-brands">
        <div class="carousel__nav-group">
            <?php foreach ($arResult["ALPHABET_NUM"] as $key => $letter):?>
                <button class="<?=($key == 0) ? 'is-active' : ''?>" data-slide="<?=$x?>"><?=$letter?></button>
                <?$x++;?>
            <?php endforeach;?>
        </div>
        <div class="carousel__nav-group">
            <?php foreach ($arResult["ALPHABET_RU"] as $key => $letter):?>
                <button class="<?=($key == 0) ? 'is-active' : ''?>" data-slide="<?=$x?>"><?=$letter?></button>
                <?$x++;?>
            <?php endforeach;?>
        </div>
        <div class="carousel__nav-group">
            <?php foreach ($arResult["ALPHABET"] as $key => $letter):?>
                <button class="<?=($key == 0) ? 'is-active' : ''?>" data-slide="<?=$x?>"><?=$letter?></button>
                <?$x++;?>
            <?php endforeach;?>
        </div>
    </div>
    <div class="px-32 px-lg-60">
        <div class="js-carousel-abc" id="carousel-abc">
            <?php foreach (array_merge($arResult["ALPHABET_NUM"], $arResult["ALPHABET_RU"], $arResult["ALPHABET"]) as $key => $letter):?>
                <div class="<?=($key == 0) ? 'is-active' : ''?>">
                    <div class="carousel__item">
                        <div class="h1 mb-8"><?=$letter?></div>
                        <ul>
                            <?php foreach ($arResult['ITEMS_ABC'][$letter] as $arItem):?>
                                <?php
                                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                ?>
                                <li>
                                    <a class="link" href="<?=$arItem['DETAIL_PAGE_URL']?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                                        <?=$arItem['NAME']?>
                                    </a>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>