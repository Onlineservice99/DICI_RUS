<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php /* 
if (count($arResult['ITEMS']) > 0) : ?>
    <div class="js-carousel-cards">
        <? $itemCount = 1 ?>
        <?php foreach ($arResult["ITEMS"] as $item) : ?>
            <?
            $strJsonItems .= '{
                      "@type": "ListItem",
                      "position": ' . $itemCount . ',
                      "item":
                       {
                         "@id": "' . $_SERVER['SERVER_NAME'] . $item['DETAIL_PAGE_URL'] . '",
                         "name": "' . $item['NAME'] . '"
                       }
                   }';
            if ($itemCount != count($arResult['ITEMS'])) {
                $strJsonItems .= ',';
            }
            $itemCount++;
            ?>
            <div>
                <?php $APPLICATION->IncludeComponent(
                    "bitrix:catalog.item",
                    "element",
                    array(
                        "RESULT" => [
                            "ITEM" => $item,
                            'AREA_ID' => $this->GetEditAreaId($item['ID']),
                        ]
                    )
                ); ?>
            </div>
        <?php endforeach; ?>
    </div>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [<?= $strJsonItems ?>]
        }
    </script>
<?php endif; */?>

<?php  

/*

$cacheTime = 3600; // время кеширования в секундах
$cacheId = 'carousel_' . md5(serialize($arParams)) . SITE_ID; // уникальный идентификатор кеша
$cacheDir = '/carousel_cards'; // директория кеша

$cache = Bitrix\Main\Data\Cache::createInstance(); // создание экземпляра класса кеша

if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
    $vars = $cache->getVars(); // получение переменных из кеша
    $strJsonItems = $vars['strJsonItems'];
    $arResult = $vars['arResult'];
} elseif ($cache->startDataCache()) {
    if (count($arResult['ITEMS']) > 0) {
        $strJsonItems = '';
        $itemCount = 1;
        foreach ($arResult["ITEMS"] as $item) {
            $strJsonItems .= '{
                  "@type": "ListItem",
                  "position": ' . $itemCount . ',
                  "item": {
                     "@id": "' . $_SERVER['SERVER_NAME'] . $item['DETAIL_PAGE_URL'] . '",
                     "name": "' . $item['NAME'] . '"
                   }
               }';
            if ($itemCount != count($arResult['ITEMS'])) {
                $strJsonItems .= ',';
            }
            $itemCount++;
        }

        // Сохраняем данные в кеш
        $cache->endDataCache(['strJsonItems' => $strJsonItems, 'arResult' => $arResult]);
    } else {
        $cache->abortDataCache(); // Отмена кеширования, если нет элементов
    }
}

// Проверка наличия данных для вывода
if (!empty($strJsonItems)) {
?>
    <div class="js-carousel-cards">
        <? foreach ($arResult["ITEMS"] as $item) : ?>
            <div>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:catalog.item",
                    "element",
                    array(
                        "RESULT" => [
                            "ITEM" => $item,
                            'AREA_ID' => $this->GetEditAreaId($item['ID']),
                        ]
                    )
                ); ?>
            </div>
        <? endforeach; ?>
    </div>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [<?= $strJsonItems ?>]
        }
    </script>
<?php
}

*/





$cacheTime = 86400; // время кеширования в секундах (1 день)
$cacheId = 'carousel_' . md5(serialize($arParams)) . SITE_ID; // уникальный идентификатор кеша
$cacheDir = '/carousel_cards'; // директория кеша

$cache = Bitrix\Main\Data\Cache::createInstance(); // создание экземпляра класса кеша

if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
    $vars = $cache->getVars(); // получение переменных из кеша
    $strJsonItems = $vars['strJsonItems'];
    $arResult = $vars['arResult'];
} elseif ($cache->startDataCache()) {
    $strJsonItems = '';
    if (!empty($arResult['ITEMS'])) {
        $jsonItems = [];
        foreach ($arResult["ITEMS"] as $index => $item) {
            $jsonItems[] = [
                "@type" => "ListItem",
                "position" => $index + 1,
                "item" => [
                    "@id" => $_SERVER['SERVER_NAME'] . $item['DETAIL_PAGE_URL'],
                    "name" => $item['NAME']
                ]
            ];
        }
        $strJsonItems = json_encode($jsonItems);

        // Сохраняем данные в кеш
        $cache->endDataCache(['strJsonItems' => $strJsonItems, 'arResult' => $arResult]);
    } else {
        $cache->abortDataCache(); // Отмена кеширования, если нет элементов
    }
}

// Проверка наличия данных для вывода
if (!empty($strJsonItems)) {
?>
    <div class="js-carousel-cards">
        <? foreach ($arResult["ITEMS"] as $item) : ?>
            <div>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:catalog.item",
                    "element",
                    array(
                        "RESULT" => [
                            "ITEM" => $item,
                            'AREA_ID' => $this->GetEditAreaId($item['ID']),
                        ]
                    )
                ); ?>
            </div>
        <? endforeach; ?>
    </div>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": <?= $strJsonItems ?>
        }
    </script>
<?php
}

?>