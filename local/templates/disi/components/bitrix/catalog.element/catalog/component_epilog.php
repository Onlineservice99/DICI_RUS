<?php
$reviewsJSON = [];


foreach ($arResult['REVIEWS'] as $key => $review) {
    $reviewsJSON [] = '{
          "@context": "https://schema.org/",
          "@type": "Review",
          "author": {
            "@type": "Person",
            "name": "'.$review["PROPS"]["FIO"]['VALUE'].'"
          },
          "datePublished": "'.$review["DATE_CREATE"].'",
          "reviewBody": "'.$review["PROPS"]["REVIEW"]['VALUE']["TEXT"].'",
          "itemReviewed": {
            "@type": "Product",
            "name": "'.$arResult['NAME'].'",
            "image": "'.$_SERVER['SERVER_NAME'].$arResult['DETAIL_PICTURE']['SRC'].'",
            "url": "'.$_SERVER['SERVER_NAME'].$APPLICATION->GetCurPage().'",
            "description": "'.$arResult['DESCRIPTION'].'",
            "offers": [
            {
                "@type": "Offer",
                "price": "'. $arResult['PRICE_DISCOUNT']['PRICE'].'",
                "priceCurrency": "RUB"
            }
            ]
          },
          "url": "'.$_SERVER['SERVER_NAME'].$APPLICATION->GetCurPage().'"
        }';
}

?>

<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "<?= $arResult['NAME'] ?>",
        "image": "<?= $_SERVER['SERVER_NAME'].$arResult['DETAIL_PICTURE']['SRC'] ?>",
        "url": "<?= $_SERVER['SERVER_NAME'].$APPLICATION->GetCurPage(); ?>",
        "description": "<?= $arResult['DESCRIPTION'] ?>",
        "offers": [
        {
            "@type": "Offer",
            "price": "<?= $arResult['PRICE_DISCOUNT']['PRICE'] ?>",
            "priceCurrency": "RUB"
        }
        ]
    }
</script>

<? foreach ($reviewsJSON as $item): ?>
<script type="application/ld+json">
    <?= $item ?>
</script>
<? endforeach; ?>
