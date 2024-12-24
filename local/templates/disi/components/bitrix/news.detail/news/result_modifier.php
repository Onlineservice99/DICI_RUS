<?php
$picture = (!empty($arResult["DETAIL_PICTURE"]["SRC"])) ? $arResult["DETAIL_PICTURE"]["SRC"] : SITE_TEMPLATE_PATH.'/assets/img/no_photo.png';
$logo = SITE_TEMPLATE_PATH.'/assets/img/logo-disi.svg';
$strMetaJson = ' 
 <script type="application/ld+json">
 {
 "@context": "https://schema.org",
 "@type": "NewsArticle",
 "mainEntityOfPage": {
 "@type": "WebPage",
 "@id": "https://google.com/article"
 },
 "headline": "'.$arResult['NAME'].'",
 "image": [
 "'.$picture.'"
 ],
 "datePublished": "'.$arResult['DATE_FORM'].'",
 "dateModified": "'.$arResult['DATE_FORM'].'",
 "publisher": {
 "@type": "Organization",
 "name": "Диси",
 "logo": {
 "@type": "ImageObject",
 "url": "'.$logo.'"
 }
 },
 "description": "'.str_replace( array("\r\n", "\r", "\n"), " ", HTMLToTxt($arResult["DETAIL_TEXT"])).'"
 }
 </script>'
;

echo $strMetaJson;