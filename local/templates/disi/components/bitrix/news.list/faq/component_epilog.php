<?php

$arItems = [];

foreach ($arResult['GROUPS'] as $GROUP) {
    foreach ($GROUP as $item) {
        $arItems[] = [
            "@type"          => "Question",
            "name"           => $item['PROPERTIES']["MESSAGE"]['VALUE']['TEXT'],
            "acceptedAnswer" => [
                [
                    "@type" => "Answer",
                    "text"  => $item["PREVIEW_TEXT"]
                ],
            ]
        ];
    }
}

$arJson = [
    "@context"   => "https://schema.org",
    "@type"      => "FAQPage",
    "mainEntity" => $arItems
];
?>

<script type="application/ld+json">
    <?= json_encode($arJson, JSON_UNESCAPED_UNICODE) ?>
</script>