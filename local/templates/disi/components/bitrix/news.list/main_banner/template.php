<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="carousel carousel--front mb-40 mb-md-56">
    <div class="container">
        <div class="js-carousel-front">
            <?php foreach ($arResult['ITEMS'] as $item):?>
                <div>
                    <div class="banner banner--front" style="background-image: url(<?=$item['PREVIEW_PICTURE']['SRC']?>)">
                        <div class="banner__left">
                            <div class="h2 mb-16 mb-md-24"><?=$item['PREVIEW_TEXT']?></div>
                            <div class="h3 mb-48 mb-lg-60"><?=$item['DETAIL_TEXT']?></div>
                            <?php if ($item['PROPERTIES']['LINK']['VALUE']):?>
                                <a class="btn btn--red px-44 col-12 col-sm-auto"
                                   href="<?=$item['PROPERTIES']['LINK']['VALUE']?>">
                                    <?=$item['PROPERTIES']['LINK']['DESCRIPTION']?>
                                </a>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>