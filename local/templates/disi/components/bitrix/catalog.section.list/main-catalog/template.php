<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<h2 class="h1 text-center mb-32 mb-xl-68">Каталог продукции</h2>
<div class="row mx-n12 mx-xxl-n16 mb-40 mb-lg-128">
    <?php 
    $count = 0;
    foreach ($arResult['SECTIONS'] as $key=>$section):
    $count++;
    ?>
        <?php
        if ($count > 7) {
            continue;    
        }
        ?>
        <div class="col-md-6 col-xl-4 d-flex pb-32 px-12 px-xxl-16">
            <div class="catalog-card">
                <img class="catalog-card__ic" src="<?=$section['PICTURE']['SRC']?>" alt="">
                <div class="pl-16">
                    <a class="catalog-card__title link h4" href="<?=$section['SECTION_PAGE_URL']?>"><?=$section['NAME']?>&nbsp;&nbsp;<span>(<?=$section['ELEMENT_CNT']?>)</span></a>
                    <ul class="catalog-card__list">
                        <?php foreach ($section['ELEMS'] as $key=>$s):?>
                            <?php
                            if ($key > 4) {
                                break;    
                            }
                            ?>
                            <li>
                                <a class="link" href="<?=$s['SECTION_PAGE_URL']?>"><?=$s['NAME']?>&nbsp;&nbsp;<span>(<?=$s['ELEMENT_CNT']?>)</span></a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                    <?php if (count($section['ELEMS']) > 4):?>
                        <div class="catalog-card__hide-block">
                            <a class="catalog-card__link-all link link--dashed" href="#">
                                <svg class="icon icon-close icon-16">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                                </svg><span>Показать все</span>
                            </a>
                            <ul class="catalog-card__list">
                                <?php for ($key2 = 5; $key2 < count($section['ELEMS']); $key2++):?>
                                    <li>
                                        <a class="link" href="<?=$section['ELEMS'][$key2]['SECTION_PAGE_URL']?>"><?=$section['ELEMS'][$key2]['NAME']?>&nbsp;&nbsp;<span>(<?=$section['ELEMS'][$key2]['ELEMENT_CNT']?>)</span></a>
                                    </li>
                                <?php endfor;?>
                            </ul>
                        </div>
                    <?php endif?>
                </div>
            </div> 
        </div>
    <?php endforeach;?>
    <div class="col-md-6 col-xl-4 d-flex pb-md-32 px-12 px-xxl-16">
        <div class="catalog-card catalog-card--bg border-bottom">
            <img class="catalog-card__ic" src="<?=SITE_TEMPLATE_PATH?>/assets/img/cc-8.png" alt="">
            <div class="pl-16">
                <div class="catalog-card__title h4">Больше разделов каталога</div>
                <a class="link" href="/catalog/">Смотреть все разделы</a>
            </div>
        </div>
    </div>
    <? $APPLICATION->IncludeFile(SITE_DIR . 'include/pricelist.php') ?>
</div>
