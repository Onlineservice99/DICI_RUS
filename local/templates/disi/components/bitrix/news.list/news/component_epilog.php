<?php

$arItems = [];

$listItemType = '';
$curPage = $APPLICATION->GetCurPage();
if ($curPage == '/news/publications/'){
    $listItemType = "BlogPosting";
}else{
    $listItemType = "NewsArticle";
}

foreach ($arResult['ITEMS'] as $item) {

    $arItems[] = [
        "@type" => $listItemType,
        "name"  => $item['NAME'],
        "image" => $item['PREVIEW_PICTURE']['SRC']
    ];

}
$arJson = [
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'url'             => $_SERVER['SERVER_NAME'] . $APPLICATION->GetCurPage(),
    'numberOfItems'   => count($arItems),
    'itemListElement' => $arItems
];
?>

<script type="application/ld+json">
 <?= json_encode($arJson, JSON_UNESCAPED_UNICODE) ?>
</script>
