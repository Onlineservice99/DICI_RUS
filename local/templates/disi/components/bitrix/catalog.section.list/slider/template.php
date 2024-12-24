<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
    $templateData = $arResult;
?>
<?php if (!empty($arResult['SECTIONS'])):?>
    <div class="carousel carousel--catalog mb-56">
        <div class="js-carousel-catalog">
            <?php foreach ($arResult['SECTIONS'] as $section):?>
            <div>
                <div class="catalog-card bg-light">
                    <img class="catalog-card__ic" src="<?=$section['PICTURE']['SRC']?>" alt="">
                    <div class="pl-16">
                        <a class="catalog-card__title link h4" href="<?=$section['SECTION_PAGE_URL']?>"><?=$section['NAME']?>&nbsp;&nbsp;
                            <span>
                                (<?=$section['ELEMENT_CNT']?>)
                            </span></a>
                        <ul class="catalog-card__list">
                            <?php foreach ($section['ELEMS'] as $elem):?>
                            <li>
                                <a class="link" href="<?=$elem['SECTION_PAGE_URL']?>"><?=$elem['NAME']?>&nbsp;
                                    &nbsp;
                                    <span>(<?=$elem['ELEMENT_CNT']?>)</span></a>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
<?php endif;?>