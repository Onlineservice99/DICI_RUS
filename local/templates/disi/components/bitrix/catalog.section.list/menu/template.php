<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<div class="h-100 d-flex flex-column w-100">
    <div class="catalog__head">
        <div class="container">
            <div class="d-flex align-items-center">
                <h3>Каталог продукции</h3>
                <button class="catalog__close link ml-auto" type="button" data-fancybox-close>
                    <svg class="icon icon-close">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div class="catalog__scroll overflow-hidden flex-grow-1" data-scrollbar>
        <div class="container">
            <div class="row mx-n12 mx-xxl-n16 mb-40 mb-lg-128">
                <?php foreach ($arResult['SECTIONS'] as $key => $section):?>
                    <?php if ($section['RELATIVE_DEPTH_LEVEL'] == '1'):?>
                        <div class="col-md-6 col-xl-4 d-flex pb-32 px-12 px-xxl-16">
                            <div class="catalog-card">
                                <?php $catalogCardImg = CFile::ResizeImageGet($section['PICTURE']['ID'], Array("width" => 200, "height" => 200), BX_RESIZE_IMAGE_PROPORTIONAL ) ?>
                                <img class="catalog-card__ic" loading="lazy" src="<?=(!empty($catalogCardImg['src'])) ? $catalogCardImg['src'] : SITE_TEMPLATE_PATH.'/assets/img/no_photo.png'?>" alt="<?=$section["NAME"]?>">
                                <div class="pl-16">
                                    <?if ( !is_null($section['LVL2']) && count($section['LVL2'])):?>
                                        <a class="catalog-card__title link h4" href="#catalog-lvl<?=$section['ID']?>" data-fancybox data-touch="false" data-base-class="fancybox-catalog">
                                            <?=$section['NAME']?></span>
                                        </a>
                                    <?else:?>
                                        <a class="catalog-card__title link h4" href="<?=$section['SECTION_PAGE_URL']?>">
                                            <?=$section['NAME']?></span>
                                        </a>
                                    <?endif;?>
                                    <ul class="catalog-card__list">
                                        <?php
                                        $valuesCount = !is_null($section['LVL2'])  ? count($section['LVL2']) : 0; // Количество значений свойства
                                        $valuesVisible = $arParams["VISIBLE_SUBSECTIONS"]; // Количество видимых значений свойства
                                        if( !is_null($section['LVL2']) ): ?>

                                            <?
                                            foreach (array_slice($section['LVL2'], 0, $valuesVisible) as $s):?>
                                                <li>
                                                    <a class="link" href="<?=$s['SECTION_PAGE_URL']?>"><?=$s['NAME']?></span></a>
                                                </li>
                                            <?php endforeach;
                                        endif;
                                        ?>
                                    </ul>
                                    <?php if($valuesCount > $valuesVisible):?>
                                        <div class="catalog-card__hide-block">
                                            <a class="catalog-card__link-all link link--dashed" href="#">
                                                <svg class="icon icon-close icon-16">
                                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                                                </svg><span>Показать все</span>
                                            </a>
                                            <ul class="catalog-card__list">
                                                <?php foreach (array_slice($section['LVL2'], $valuesVisible, $valuesCount) as $s):?>
                                                    <li>
                                                        <a class="link" href="<?=$s['SECTION_PAGE_URL']?>"><?=$s['NAME']?></span></a>
                                                    </li>
                                                <?php endforeach;?>
                                            </ul>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>

                        </div>
                    <?php endif?>
                <?php endforeach;?>
                <?php foreach ($arResult['SECTIONS'] as $key => $section):?>
                    <div class="d-none">
                        <div class="catalog" id="catalog-lvl<?=$section['ID']?>">
                            <div class="h-100 d-flex flex-column w-100">
                                <div class="catalog__head">
                                    <div class="container">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-16 d-none d-sm-block text-nowrap">Каталог
                                                <svg class="icon icon-arrow-right ml-16">
                                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-arrow-right"></use>
                                                </svg>

                                            </div>
                                            <h3><?=$section['NAME']?></h3>
                                            <button class="catalog__close link ml-auto" type="button" data-fancybox-close onclick="$.fancybox.close(true)">
                                                <svg class="icon icon-close">
                                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                                                </svg>
                                            </button>
                                        </div>
                                        <button class="btn btn--border btn--sm mt-20" type="button" data-fancybox-close>
                                            <svg class="icon icon-horizontal-align-left mr-8 text-green">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-horizontal-align-left"></use>
                                            </svg>Назад
                                        </button>
                                    </div>

                                </div>
                                <div class="catalog__scroll overflow-hidden flex-grow-1" data-scrollbar>
                                    <div class="container">
                                        <div class="row mx-n12 mx-xxl-n16 mb-40 mb-lg-128">
                                            <?php foreach ($section['LVL2'] as $key2=>$subSection):?>
                                                <div class="col-md-6 col-xl-4 d-flex pb-32 px-12 px-xxl-16">
                                                    <div class="catalog-card">
                                                        <?php $catalogCardImgLvl2 = CFile::ResizeImageGet($subSection['PICTURE']['ID'], Array("width" => 200, "height" => 200), BX_RESIZE_IMAGE_PROPORTIONAL ) ?>
                                                        <img class="catalog-card__ic" loading="lazy" src="<?=(!empty($catalogCardImgLvl2['src'])) ? $catalogCardImgLvl2['src'] : SITE_TEMPLATE_PATH.'/assets/img/no_photo.png'?>" alt="<?=$subSection["NAME"]?>">
                                                        <div class="pl-16">
                                                            <a class="catalog-card__title link h4" href="#catalog-lvl<?=$subSection["ID"]?>" data-fancybox data-touch="false" data-base-class="fancybox-catalog">
                                                                <?=$subSection['NAME']?></span>
                                                            </a>
                                                            <div class="d-none">
                                                                <div class="catalog" id="catalog-lvl<?=$subSection["ID"]?>">
                                                                    <div class="h-100 d-flex flex-column w-100">
                                                                        <div class="catalog__head">
                                                                            <div class="container">
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="mr-16 d-none d-sm-block text-nowrap">Каталог
                                                                                        <svg class="icon icon-arrow-right ml-16">
                                                                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-arrow-right"></use>
                                                                                        </svg>

                                                                                    </div>
                                                                                    <h3><?=$subSection['NAME']?></h3>
                                                                                    <button class="catalog__close link ml-auto" type="button" data-fancybox-close onclick="$.fancybox.close(true)">
                                                                                        <svg class="icon icon-close">
                                                                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                                                                                        </svg>
                                                                                    </button>
                                                                                </div>
                                                                                <button class="btn btn--border btn--sm mt-20" type="button" data-fancybox-close>
                                                                                    <svg class="icon icon-horizontal-align-left mr-8 text-green">
                                                                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-horizontal-align-left"></use>
                                                                                    </svg>Назад
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="catalog__scroll overflow-hidden flex-grow-1" data-scrollbar>
                                                                            <div class="container">
                                                                                <div class="row mx-n12 mx-xxl-n16 mb-40 mb-lg-128">
                                                                                    <?php foreach ($arResult['SECTIONS'][$subSection["ID"]]['LVL3'] as $key3=>$subSection3):?>
                                                                                        <div class="col-md-6 col-xl-4 d-flex pb-32 px-12 px-xxl-16">
                                                                                            <div class="catalog-card">
                                                                                                <?php $catalogCardImgLvl3 = CFile::ResizeImageGet($section['PICTURE']['ID'], Array("width" => 200, "height" => 200), BX_RESIZE_IMAGE_PROPORTIONAL ) ?>
                                                                                                <img class="catalog-card__ic" loading="lazy" src="<?=(!empty($catalogCardImgLvl3['src'])) ? $catalogCardImgLvl3['src'] : SITE_TEMPLATE_PATH.'/assets/img/no_photo.png'?>" alt="<?=$subSection3["NAME"]?>">
                                                                                                <div class="pl-16">
                                                                                                    <a class="catalog-card__title link h4" href="<?=$subSection3["SECTION_PAGE_URL"]?>"
                                                                                                        <?php /* if (!empty($arResult['SECTIONS'][$subSection3["ID"]]['LVL4'])):?>
                                                                                                            href="#catalog-lvl<?=$subSection3["ID"]?>"
                                                                                                            data-fancybox
                                                                                                            data-touch="false"
                                                                                                            data-base-class="fancybox-catalog"
                                                                                                        <?php else:?>
                                                                                                            href="<?=$subSection3["SECTION_PAGE_URL"]?>"
                                                                                                        <?php endif; */ ?>
                                                                                                    >
                                                                                                        <?=$subSection3['NAME']?></span>
                                                                                                    </a>
                                                                                                    <ul class="catalog-card__list">
                                                                                                        <?php
                                                                                                        $valuesCount = ( !is_null($arResult['SECTIONS'][$subSection3["ID"]]['LVL4']) ) ? count($arResult['SECTIONS'][$subSection3["ID"]]['LVL4']) : 0; // Количество значений свойства
                                                                                                        $valuesVisible = $arParams["VISIBLE_SUBSECTIONS"]; // Количество видимых значений свойства

                                                                                                        if( !is_null($arResult['SECTIONS'][$subSection3["ID"]]['LVL4']) ):
                                                                                                        foreach (array_slice($arResult['SECTIONS'][$subSection3["ID"]]['LVL4'], 0, $valuesVisible) as $key4 => $s4):
                                                                                                            ?>
                                                                                                            <li>
                                                                                                                <a class="link" href="<?=$s4['SECTION_PAGE_URL']?>"><?=$s4['NAME']?></span></a>
                                                                                                            </li>
                                                                                                        <?php endforeach;
                                                                                                        endif;
                                                                                                        ?>
                                                                                                    </ul>
                                                                                                    <?php if($valuesCount > $valuesVisible):?>
                                                                                                        <div class="catalog-card__hide-block">
                                                                                                            <a class="catalog-card__link-all link link--dashed" href="#">
                                                                                                                <svg class="icon icon-close icon-16">
                                                                                                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                                                                                                                </svg><span>Показать все</span>
                                                                                                            </a>
                                                                                                            <ul class="catalog-card__list">
                                                                                                                <?php foreach (array_slice($arResult['SECTIONS'][$subSection3["ID"]]['LVL4'], $valuesVisible, $valuesCount) as $s):?>
                                                                                                                    <li>
                                                                                                                        <a class="link" href="<?=$s['SECTION_PAGE_URL']?>"><?=$s['NAME']?></span></a>
                                                                                                                    </li>
                                                                                                                <?php endforeach;?>
                                                                                                            </ul>
                                                                                                        </div>
                                                                                                    <?php endif;?>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endforeach;?>
                                                                                    <?php /*?>
                                                                                    <div class="col-md-6 col-xl-4 d-flex pb-32 px-12 px-xxl-16">
                                                                                        <div class="catalog-card catalog-card--bg">
                                                                                            <img class="catalog-card__ic" src="<?=SITE_TEMPLATE_PATH?>/assets/img/cc-9.png" alt="">
                                                                                            <div class="pl-16">
                                                                                                <div class="catalog-card__title h4">Скачайте прайс –
                                                                                                    <br>сравните цены!
                                                                                                </div>
                                                                                                <a class="link" href="#">Полный прайс-лист ДиСи </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php */?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <ul class="catalog-card__list">
                                                                <?php
                                                                $valuesCount = ( !is_null($arResult['SECTIONS'][$subSection["ID"]]['LVL3']) ) ? count($arResult['SECTIONS'][$subSection["ID"]]['LVL3']) : 0; // Количество значений свойства
                                                                $valuesVisible = $arParams["VISIBLE_SUBSECTIONS"]; // Количество видимых значений свойства

                                                                if( !is_null($arResult['SECTIONS'][$subSection["ID"]]['LVL3']) ):
                                                                foreach (array_slice($arResult['SECTIONS'][$subSection["ID"]]['LVL3'], 0, $valuesVisible) as $key3 => $s):
                                                                    ?>
                                                                    <li>
                                                                        <a class="link" href="<?=$s['SECTION_PAGE_URL']?>"><?=$s['NAME']?></span></a>
                                                                    </li>
                                                                <?php endforeach;
                                                                endif;
                                                                ?>
                                                            </ul>
                                                            <?php if($valuesCount > $valuesVisible):?>
                                                                <div class="catalog-card__hide-block">
                                                                    <a class="catalog-card__link-all link link--dashed" href="#">
                                                                        <svg class="icon icon-close icon-16">
                                                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                                                                        </svg><span>Показать все</span>
                                                                    </a>
                                                                    <ul class="catalog-card__list">
                                                                        <?php foreach (array_slice($arResult['SECTIONS'][$subSection["ID"]]['LVL3'], $valuesVisible, $valuesCount) as $s):?>
                                                                            <li>
                                                                                <a class="link" href="<?=$s['SECTION_PAGE_URL']?>"><?=$s['NAME']?></span></a>
                                                                            </li>
                                                                        <?php endforeach;?>
                                                                    </ul>
                                                                </div>
                                                            <?php endif;?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
