<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$strJsonItems = '';
?>
<div class="container pb-80 mb-xl-80">
    <h2 class="text-center mb-32 mb-lg-44">Бренды, представленные в нашем каталоге</h2>
    <div class="carousel carousel--arrows-card px-32 px-md-80">
        <div class="js-carousel-logo">
            <? $itemCount = 1 ?>
            <?php foreach ($arResult['ITEMS'] as $item):?>
            <?
                $strJsonItems .= '{
                          "@type": "ListItem",
                          "position": '.$itemCount.',
                          "item":
                           {
                             "@id": "'.$item['DETAIL_PAGE_URL'].'",
                             "name": "'.$item['NAME'].'"
                           }
                       }';
                if ($itemCount != count($arResult['ITEMS'])){
                    $strJsonItems .= ',';
                }
                $itemCount++;
                ?>
            <div>
                <a href="<?=$item['DETAIL_PAGE_URL']?>" class="logo-brand">
                    <img loading="lazy" src="<?= CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], Array("width" => 200, "height" => 200), BX_RESIZE_IMAGE_PROPORTIONAL )['src']?>" alt="">
                </a>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>

<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [<?= $strJsonItems ?>]
    }
</script>