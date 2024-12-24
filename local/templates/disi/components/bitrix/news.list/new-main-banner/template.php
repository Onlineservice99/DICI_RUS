<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="banner-wrapper">
    <?php foreach ($arResult['ITEMS'] as $item): ?>
        <!-- PC -->
            <?php if(!empty($item["PROPERTIES"]["IMAGE_BANNER_PC"]["~VALUE"])):?>
                <div class="promos_banner d-none d-xl-flex" style="background-image:url(<?=CFile::GetPath($item["PROPERTIES"]["IMAGE_BANNER_PC"]["~VALUE"]) ?>)">
            <? else: ?>
                <div class="promos_banner d-none d-xl-flex" style="background-image:url(<?= $item['PREVIEW_PICTURE']['SRC'] ?>)">
            <?php endif; ?>

            <?php if(!empty($item["PROPERTIES"]["SIZE_TEXT_PC"]["VALUE"]) || !empty($item["PROPERTIES"]["COLOR_TEXT_PC"]["VALUE"])):?>
                    <span style="font-size:<?=($item["PROPERTIES"]["SIZE_TEXT_PC"]["VALUE"] ?: 40)?>px; color:#<?=($item["PROPERTIES"]["COLOR_TEXT_PC"]["VALUE"] ?: '414042') ?>;"><?= $item['PREVIEW_TEXT'] ?></span>
            <? else: ?>
                    <span style="font-size: 40px; color: #414042;"><?= $item['PREVIEW_TEXT'] ?></span>
            <?php endif; ?>
            
            <? if (!empty($item["PROPERTIES"]["BUTTON_BANNER_PC"]["VALUE"])): ?>
                <?php if(!empty($item["PROPERTIES"]["TEXT_BANNER_PC"]["VALUE"]) || !empty($item['PROPERTIES']['LINK']['VALUE'])):?>
                    <a href="<?=($item['PROPERTIES']['LINK']['VALUE'] ?: '#')?>" class="btn btn--red"><?=($item["PROPERTIES"]["TEXT_BANNER_PC"]["VALUE"] ?: 'Перейти в раздел')?></a>
                <? else: ?>
                    <a href="#" class="btn btn--red">Перейти в раздел</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    
        <!-- Mobile -->
            <?php if(!empty($item["PROPERTIES"]["IMAGE_BANNER_M"]["~VALUE"])):?>
                <div class="promos_banner d-xl-none" style="background-image:url(<?=CFile::GetPath($item["PROPERTIES"]["IMAGE_BANNER_M"]["~VALUE"]) ?>)">
            <? else: ?>
                <div class="promos_banner d-xl-none" style="background-image:url(<?= $item['PREVIEW_PICTURE']['SRC'] ?>)">
            <?php endif; ?>

            <?php if(!empty($item["PROPERTIES"]["SIZE_TEXT_M"]["VALUE"]) || !empty($item["PROPERTIES"]["COLOR_TEXT_M"]["VALUE"])):?>
                <span style="font-size: <?=($item["PROPERTIES"]["SIZE_TEXT_M"]["VALUE"] ?: 28)?>px; color:#<?=($item["PROPERTIES"]["COLOR_TEXT_M"]["VALUE"] ?: '414042') ?>;"><?= $item['PREVIEW_TEXT'] ?></span>
            <? else: ?>
                <span style="font-size: 28px; color: #414042;"><?= $item['PREVIEW_TEXT'] ?></span>
            <?php endif; ?>

            <? if (!empty($item["PROPERTIES"]["BUTTON_BANNER_M"]["VALUE"])): ?>
                <?php if(!empty($item["PROPERTIES"]["TEXT_BANNER_M"]["VALUE"]) || !empty($item['PROPERTIES']['LINK']['VALUE'])):?>
                    <a href="<?=($item['PROPERTIES']['LINK']['VALUE'] ?: '#')?>" class="btn btn--red"><?=($item["PROPERTIES"]["TEXT_BANNER_M"]["VALUE"] ?: 'Перейти в раздел')?></a>
                <? else: ?>
                    <a href='#' class="btn btn--red">Перейти в раздел</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

