<?php

use electroset1\Content;

$iblock = \Bitrix\Main\Config\Option::get("meven.info", "iblock_catalog");
$price = Content::getMinMaxPriceCatalog($iblock, ['SORT' => 'ASC', 'BRAND_ID' => false, 'SECTION_ID' => false, 'PRODUCT_ID' => false])['MIN']['PRICE'];

$desc = $APPLICATION->GetDirProperty("description");
$desc = str_replace('{_min_price_}', ceil($price), $desc);

$APPLICATION->SetPageProperty("description", $desc);
