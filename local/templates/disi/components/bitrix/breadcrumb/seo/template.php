<?php

global $APPLICATION;

if(empty($arResult))
    return "";

$strReturn = '';

$strReturn .= '<script type="application/ld+json">{
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);
    $link = $arResult[$index]["LINK"];
    $position = $index + 1;
    $strReturn .= '
    {
        "@type": "ListItem",
        "position": '.intval($position).',
        "name": "'.$title.'",
        "item": "'.$_SERVER['SERVER_NAME'].$link.'"
      }
    ';

    if ($position != $itemSize){
        $strReturn .=',';
    }


}

$strReturn .= ']}</script>';

if ($itemSize > 0){
    return $strReturn;
}else{
    return '';
}
