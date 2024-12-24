<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="row align-items-center pb-12">
    <h2 class="h1 col mr-auto mb-28 before_news"><?=$arParams["BLOCK_TITLE"]?></h2>
    <div class="col-xl-auto mb-28">
        <div class="row">
            <div class="col-sm-auto mb-12 mb-sm-0">
                <a class="btn btn--border btn--sm w-100 after_news" href="/news/posts/">
                    <svg class="icon icon-plus mr-8">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-plus"></use>
                    </svg>Все
                </a>
            </div>
            <div class="col-sm-auto">
                <a class="btn btn--border btn--sm w-100" href="/news/publications/">
                    <svg class="icon icon-plus mr-8">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-plus"></use>
                    </svg>Все публикации
                </a>
            </div>
        </div>
    </div>
</div>
<div class="carousel carousel--news carousel--arrows-card">
    <div class="js-carousel-news">
        <?php foreach ($arResult['ITEMS'] as $item):?>
        <div>
            <div class="card-news">
                <div class="card-news__head p-md text-gray d-flex mb-24">
                    <div class="card-news__date mr-auto"><?=$item["DISPLAY_ACTIVE_FROM"]?></div>
                    <div class="card-news__type text-uppercase after_news <?=($item["SECTION_CODE"] == "posts") ? 'text-blue' : 'text-violet'?>"></div>
                </div>
                <a class="card-news__title link link--stretch h4"
                   href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['NAME']?></a>
                <div class="card-news__text"><?=$item['PREVIEW_TEXT']?></div>
                <div class="card-news__img-wrap">
                    <img src="<?=(!empty($item["PREVIEW_PICTURE"]["SRC"])) ? $item["PREVIEW_PICTURE"]["SRC"] : SITE_TEMPLATE_PATH.'/assets/img/no_photo.png'?>" alt="<?=$item['NAME']?>">
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>