<div class="right-banner-wrapper">
    <div class="promos_slider js-carousel-front-right">
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <?php $arFile = CFile::GetFileArray($item['PROPERTIES']['PHOTO']['VALUE']); ?>
                <div>
                    <!-- PC -->
                        <?php if(!empty($item["PROPERTIES"]["IMAGE_PC"]["~VALUE"])):?>
                            <div class="promos_slide d-none d-lg-flex" style="background-image:url(<?=CFile::GetPath($item["PROPERTIES"]["IMAGE_PC"]["~VALUE"]) ?>)">
                        <? else: ?>
                            <div class="promos_slide d-none d-lg-flex" style="background-image:url(<?=$arFile['SRC'] ?>)">
                        <?php endif; ?>

                        <?php if(!empty($item["PROPERTIES"]["SIZE_TEXT_PC"]["VALUE"]) || !empty($item["PROPERTIES"]["COLOR_TEXT_PC"]["VALUE"])):?>
                            <span style="font-size:<?=($item["PROPERTIES"]["SIZE_TEXT_PC"]["VALUE"] ?: 20)?>px; color:#<?=($item["PROPERTIES"]["COLOR_TEXT_PC"]["VALUE"] ?: '#414042') ?>;"><?= $item['PROPERTIES']['HEADER']['VALUE'] ?></span>
                            <b style="font-size:<?=($item["PROPERTIES"]["SIZE_TEXT_PC"]["VALUE"] ?: 40)?>px; color:#<?=($item["PROPERTIES"]["COLOR_TEXT_PC"]["VALUE"] ?: '#414042') ?>;"><?= $item['PROPERTIES']['DISCOUNT']['VALUE'] ?></b>
                         <? else: ?>
                            <span style="font-size: 20px; color: #414042;"><?= $item['PROPERTIES']['HEADER']['VALUE'] ?></span>
                            <b style="font-size: 40px; color: #414042;"><?= $item['PROPERTIES']['DISCOUNT']['VALUE'] ?></b>
                        <?php endif; ?>

                        <? if (!empty($item["PROPERTIES"]["BUTTON_BANNER_PC"]["VALUE"])): ?>
                            <?php if(!empty($item["PROPERTIES"]["TEXT_BANNER_PC"]["VALUE"]) || !empty($item['PROPERTIES']['LINK']['VALUE'] )):?>
                                <a href="<?= $item['PROPERTIES']['LINK']['VALUE'] ?? '#' ?>" class="btn <?if($item['PROPERTIES']["BTN_LOCATION"]["VALUE"] == "Слева") echo 'left'?> btn--<?=$item["PROPERTIES"]["BTN_COLOR"]["VALUE_XML_ID"]?>"><?=($item["PROPERTIES"]["TEXT_BANNER_PC"]["VALUE"] ?: 'Перейти в раздел')?></a>
                            <? else: ?>
                                <a href="#" class="btn btn--red">Перейти в раздел</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <!-- Mobile -->
                        <?php if(!empty($item["PROPERTIES"]["IMAGE_M"]["~VALUE"])):?>
                            <div class="promos_slide d-lg-none" style="background-image:url(<?=CFile::GetPath($item["PROPERTIES"]["IMAGE_M"]["~VALUE"]) ?>)">
                        <? else: ?>
                            <div class="promos_slide d-lg-none" style="background-image:url(<?=$arFile['SRC'] ?>)">
                        <?php endif; ?>

                        <?php if(!empty($item["PROPERTIES"]["SIZE_TEXT_M"]["VALUE"]) || !empty($item["PROPERTIES"]["COLOR_TEXT_M"]["VALUE"])):?>
                            <span style="font-size:<?=($item["PROPERTIES"]["SIZE_TEXT_M"]["VALUE"] ?: 20)?>px; color:#<?=($item["PROPERTIES"]["COLOR_TEXT_M"]["VALUE"] ?: '414042') ?>;"><?= $item['PROPERTIES']['HEADER']['VALUE'] ?></span>
                            <b style="font-size:<?=($item["PROPERTIES"]["SIZE_TEXT_M"]["VALUE"] ?: 40)?>px; color:#<?=($item["PROPERTIES"]["COLOR_TEXT_M"]["VALUE"] ?: '414042') ?>;"><?= $item['PROPERTIES']['DISCOUNT']['VALUE'] ?></b>
                        <? else: ?>
                            <span style="font-size: 20px; color: #414042;"><?= $item['PROPERTIES']['HEADER']['VALUE'] ?></span>
                            <b style="font-size: 40px; color: #414042;"><?= $item['PROPERTIES']['DISCOUNT']['VALUE'] ?></b>
                        <?php endif; ?>

                        <? if (!empty($item["PROPERTIES"]["BUTTON_BANNER_M"]["VALUE"])): ?>
                            <?php if(!empty($item["PROPERTIES"]["TEXT_BANNER_M"]["VALUE"]) || !empty($item['PROPERTIES']['LINK']['VALUE'])):?>
								<a href="<?= $item['PROPERTIES']['LINK']['VALUE'] ?? '#' ?>" class="btn <?if($item['PROPERTIES']["BTN_LOCATION"]["VALUE"] == "Слева") echo 'left'?> btn--<?=$item["PROPERTIES"]["BTN_COLOR"]["VALUE_XML_ID"]?>"><?=($item["PROPERTIES"]["TEXT_BANNER_M"]["VALUE"] ?: 'Перейти в раздел')?></a>
                            <? else: ?>
                                <a href="#" class="btn btn--red">Перейти в раздел</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
    </div>
</div>