<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Application;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!Loader::includeModule('meven.info')) {
    throw new \Bitrix\Main\SystemException('Необходимо установить модуль');
}

global $USER;

$email = \Bitrix\Main\Config\Option::get("meven.info", "email");
$phone = \Bitrix\Main\Config\Option::get("meven.info", "phone");


$asset = Asset::getInstance();

$asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/fonts.css');
$asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/style.css');
$asset->addCss(SITE_TEMPLATE_PATH . '/assets/css/custom.css');
$asset->addCss('/local/front/css/main_style.css');
$asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/svgxuse.min.js');

$asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/plugins.js');
$asset->addJs('/local/components/meven/register/templates/.default/dist/app.bundle.js');
$asset->addJs('/local/components/meven/personal/templates/.default/dist/app.bundle.js');
$asset->addJs('/local/components/meven/order.status/templates/popup/dist/app.bundle.js');
$asset->addJs('/local/components/meven/sale.personal.profile.add/templates/jur/dist/app.bundle.js');
$asset->addJs(SITE_TEMPLATE_PATH . '/assets/js/script.js');

$page = $APPLICATION->GetCurPage(true);

// new
$isProduct = CSite::InDir(SITE_DIR . 'product/');
// 
$isCatalog = CSite::InDir(SITE_DIR . 'catalog/');
$isSearch = CSite::InDir(SITE_DIR . 'search/');
$isFavorite = CSite::InDir(SITE_DIR . 'favorite/');
$isContacts = CSite::InDir(SITE_DIR . 'contacts/');
$isPromotions = CSite::InDir(SITE_DIR . 'promotions/');
$isFaq = CSite::InDir(SITE_DIR . 'faq/');
$isBrands = CSite::InDir(SITE_DIR . 'brands/');
$isNews = CSite::InDir(SITE_DIR . 'news/');
$isMain = $APPLICATION->GetCurPage() === "/";

$textPages = ['/privacy-policy/', '/exchange-return/', '/payment-delivery/', '/wholesale/', '/about/', '/faq/'];
$isTextPages = in_array($APPLICATION->GetCurPage(), $textPages);
$arExclude = ['/faq/', '/contacts/'];
$excludePages = in_array($APPLICATION->GetCurPage(), $arExclude);
$city = \Meven\Helper\City::getInstance()->getNameCity();
$httpStatusCode = http_response_code();

function convertUrlToCategory($sElementUrl, $iBlockID) {
    $aSectionsCodes = explode('/', $sElementUrl);
    array_splice($aSectionsCodes, 0, 2);
    array_pop($aSectionsCodes);
    array_pop($aSectionsCodes);

    $aCategories = array();

    foreach ($aSectionsCodes as $code) {
        $rsSections = CIBlockSection::GetList(array(),array('IBLOCK_ID' => $iBlockID, '=CODE' => $code));
        if ($arSection = $rsSections->Fetch())
        {
            $aCategories[] = $arSection['NAME'];
        }
    }
    return implode('/', $aCategories);
}
?>

<!DOCTYPE html>
<html>
<head>
    
    <meta name="viewport" content="width=device-width">

    <script src='<?=SITE_TEMPLATE_PATH;?>/assets/js/jquery.min.js'></script>
    <script src="https://enterprise.api-maps.yandex.ru/2.1/?load=package.full&mode=release&lang=ru-RU&wizard=bitrix&apikey=7244e2c4-7c06-49ab-babc-8d2aeba09830"></script>
    <?php $APPLICATION->ShowHead() ?> 
    <title><?php $APPLICATION->ShowTitle() ?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#fc152e">
    <meta name="msapplication-TileColor" content="#dc1d31">
    <meta name="theme-color" content="#ffffff">


    <meta property="og:title" content="<?= $APPLICATION->ShowTitle() ?>" />
    <meta property="og:description" content="<?= $APPLICATION->ShowProperty('description') ?>" />
    <meta property="og:url" content="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>" />
    <meta property="og:site_name" content="<?= $APPLICATION->ShowTitle() ?>" />
    <meta property="og:locale" content="ru_RU"/>
    <meta property="og:type" content="website" />
    <meta property="og:image" content="<?=SITE_TEMPLATE_PATH?>/assets/img/logo-disi.svg" />

    <? if ($APPLICATION->GetCurPage() == '/contacts/'): ?>
        <meta hhtp-equiv="Content-type" content="text/html; charset=UTF-8">
    <? endif; ?>

    <?php $APPLICATION->IncludeComponent(
        "bitrix:breadcrumb",
        "seo",
        array(
            "COMPONENT_TEMPLATE" => "seo",
            "START_FROM" => "0",
            "PATH" => "",
            "SITE_ID" => "s1"
        )
    );?>

    <style>

        @font-face {
            font-display: swap;
            font-family: Montserratб, Helvetica, Arial, sans-serif;
            font-style: normal;
            font-weight: 400;
            src: local(""), url(/local/templates/disi/assets/fonts/montserrat-v15-latin_cyrillic-regular.woff2) format("woff2"), url(/local/templates/disi/assets/fonts/montserrat-v15-latin_cyrillic-regular.woff) format("woff"), font-display
        }

        @font-face {
            font-display: swap;
            font-family: Montserrat, Helvetica, Arial, sans-serif;
            font-style: normal;
            font-weight: 500;
            src: local(""), url(/local/templates/disi/assets/fonts/montserrat-v15-latin_cyrillic-500.woff2) format("woff2"), url(/local/templates/disi/assets/fonts/montserrat-v15-latin_cyrillic-500.woff) format("woff")
        }

        @font-face {
            font-display: swap;
            font-family: Montserrat, Helvetica, Arial, sans-serif;
            font-style: normal;
            font-weight: 600;
            src: local(""), url(/local/templates/disi/assets/fonts/montserrat-v15-latin_cyrillic-600.woff2) format("woff2"), url(/local/templates/disi/assets/fonts/montserrat-v15-latin_cyrillic-600.woff) format("woff")
        }

        body {
            font-family: Montserrat, Helvetica, Arial, sans-serif;
        }

    </style>

    <!-- Google Tag Manager -->
    <script data-skip-moving="true">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TWL6BP9');
    </script>
    <!-- End Google Tag Manager -->
    <script>
        //var quantity;
        //var name;

        function categoryFromBreadcrumbs() {
            var breadcrumbs = $('.breadcrumbs');
            if (typeof $('.breadcrumbs')[0] !== 'undefined') {
                breadcrumbs = breadcrumbs[0].textContent.replace(/\t/g, '').split(/\n/g);
                breadcrumbs = breadcrumbs.filter(function(item) {
                    return item !== '';
                });
                if (breadcrumbs.length > 2) {
                    breadcrumbs = breadcrumbs.filter(function(item) {
                        return item !== 'Главная' && item !== 'Каталог';
                    });
                }
                category = breadcrumbs.join('/');
                list = breadcrumbs[breadcrumbs.length - 1];

                return category;
            }
            return '';
        }

        function getListName() {
            var breadcrumbs = $('.breadcrumbs');
            if (typeof $('.breadcrumbs')[0] !== 'undefined') {
                breadcrumbs = breadcrumbs[0].textContent.replace(/\t/g, '').split(/\n/g);
                breadcrumbs = breadcrumbs.filter(function(item) {
                    return item !== '';
                });
                if (breadcrumbs.length > 2) {
                    breadcrumbs = breadcrumbs.filter(function(item) {
                        return item !== 'Главная' && item !== 'Каталог';
                    });
                }
                list = breadcrumbs[breadcrumbs.length - 1];

                return list;
            }
            return $( '.nav-link.active' ).text();
        }
    </script>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TWL6BP9"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <?php $APPLICATION->ShowPanel();?>
    <!--[if IE < 10]>
    <p class="browsehappy">Unfortunately, you are using an outdated browser. Please <a href="http://browsehappy.com/" target="_blank"> update your browser </a> to improve performance, quality of the displayed material, and improve security.</p>
    <![endif]-->
<header class="header" id="header">
        <div class="header__block">
            <div class="container">
                <div class="header__top d-none d-xl-flex">
                    <div class="header__city">
                        <svg class="icon icon-map-full">
                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-map-full"></use>
                        </svg>
                        <div class="link link--dashed">Абакан</div>
                    </div>
                    <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top-top", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "top",
		"DELAY" => "N",
		"MAX_LEVEL" => "4",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "top",
		"USE_EXT" => "Y",
		"COMPONENT_TEMPLATE" => "top-top",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);

?>
<nav style='display: none;'>
    <?
        /*
        $iblockId = 4;  // ID вашего инфоблока
        $cacheTime = 3600;  // Время кеширования в секундах
        $cacheId = 'catalog_structure_' . $iblockId;  // Уникальный ID кеша
        $cacheDir = '/catalog_structure_cache/' . $iblockId;  // Директория для кеша

        $cache = Bitrix\Main\Data\Cache::createInstance();  // Создаем экземпляр класса кеша

        if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
            // Если кеш валиден, извлекаем данные из кеша
            $result = $cache->getVars();
            
        } else {
            // Если данные не кешированы, начинаем буферизацию вывода
            ob_start();

            if (CModule::IncludeModule('iblock')) {
                echo "<ul>";
                $arFilter = array('IBLOCK_ID' => $iblockId, 'SECTION_ID' => 0, 'ACTIVE' => 'Y');
                $arSelect = array('ID', 'NAME', 'DEPTH_LEVEL', 'CODE');
                $rsSections = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter, false, $arSelect);

                while ($arSection = $rsSections->Fetch()) {
                    echo "<li><a href='/catalog/{$arSection['CODE']}/'>{$arSection['NAME']}</a>";
                    printSubCategories($iblockId, $arSection['ID']);
                    echo "</li>";
                }
                echo "</ul>";
            }

            // Получаем содержимое буфера и очищаем его
            $result = ob_get_clean();
            // Сохраняем данные в кеш
            $cache->startDataCache($cacheTime, $cacheId, $cacheDir);
            $cache->endDataCache($result);
        }

        echo $result;

        function printSubCategories($iblockId, $parentId) {
            $arFilter = array('IBLOCK_ID' => $iblockId, 'SECTION_ID' => $parentId, 'ACTIVE' => 'Y');
            $arSelect = array('ID', 'NAME', 'DEPTH_LEVEL', 'CODE');
            $rsSubSections = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter, false, $arSelect);

            if ($rsSubSections->SelectedRowsCount() > 0) {
                echo "<ul>";
                while ($arSubSection = $rsSubSections->Fetch()) {
                    echo "<li><a href='/catalog/{$arSubSection['CODE']}/'>{$arSubSection['NAME']}</a>";
                    printSubCategories($iblockId, $arSubSection['ID']);
                    echo "</li>";
                }
                echo "</ul>";
            }
        }

        */

        $iblockId = 4;  // ID вашего инфоблока
        $cacheTime = 86400;  // Увеличено время кеширования (1 день)
        $cacheId = 'catalog_structure_' . $iblockId;  // Уникальный ID кеша
        $cacheDir = '/catalog_structure_cache/' . $iblockId;  // Директория для кеша
        
        $cache = Bitrix\Main\Data\Cache::createInstance();  // Создаем экземпляр класса кеша
        
        if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
            // Если кеш валиден, извлекаем данные из кеша
            $result = $cache->getVars();
        } else {
            if (CModule::IncludeModule('iblock')) {
                $arFilter = array('IBLOCK_ID' => $iblockId, 'SECTION_ID' => 0, 'ACTIVE' => 'Y');
                $arSelect = array('ID', 'NAME', 'DEPTH_LEVEL', 'CODE');
                $rsSections = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter, false, $arSelect);
        
                $sections = array();
                while ($arSection = $rsSections->Fetch()) {
                    $sections[] = array(
                        'ID' => $arSection['ID'],
                        'NAME' => $arSection['NAME'],
                        'CODE' => $arSection['CODE'],
                        'SUBSECTIONS' => getSubCategories($iblockId, $arSection['ID'])
                    );
                }
        
                // Сохраняем данные в кеш
                $cache->startDataCache($cacheTime, $cacheId, $cacheDir);
                $cache->endDataCache($sections);
            }
        }
        
        if (isset($sections)) {
            echo "<ul>";
            foreach ($sections as $section) {
                echo "<li><a href='/catalog/{$section['CODE']}/'>{$section['NAME']}</a>";
                printSubCategories($section['SUBSECTIONS']);
                echo "</li>";
            }
            echo "</ul>";
        }
        
        function getSubCategories($iblockId, $parentId) {
            $arFilter = array('IBLOCK_ID' => $iblockId, 'SECTION_ID' => $parentId, 'ACTIVE' => 'Y');
            $arSelect = array('ID', 'NAME', 'DEPTH_LEVEL', 'CODE');
            $rsSubSections = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter, false, $arSelect);
        
            $subSections = array();
            while ($arSubSection = $rsSubSections->Fetch()) {
                $subSections[] = array(
                    'ID' => $arSubSection['ID'],
                    'NAME' => $arSubSection['NAME'],
                    'CODE' => $arSubSection['CODE'],
                    'SUBSECTIONS' => getSubCategories($iblockId, $arSubSection['ID'])
                );
            }
            return $subSections;
        }
        
        function printSubCategories($subSections) {
            if (!empty($subSections)) {
                echo "<ul>";
                foreach ($subSections as $subSection) {
                    echo "<li><a href='/catalog/{$subSection['CODE']}/'>{$subSection['NAME']}</a>";
                    printSubCategories($subSection['SUBSECTIONS']);
                    echo "</li>";
                }
                echo "</ul>";
            }
        }
    ?>
</nav>

                </div>
                <div class="header__row">
                    <a class="btn btn--menu mr-md-16 d-xl-none" href="#mob-menu" data-fancybox data-touch="false" data-base-class="fancybox-mob-menu" data-close-existing="true">
                        <svg class="icon icon-menu">
                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-menu"></use>
                        </svg>
                    </a>
                    <div itemscope itemtype="http://schema.org/Store" class="header__logo-wrap">
                        <a itemprop="url" class="header__logo" href="/">
                            <img itemprop="logo" src="<?=SITE_TEMPLATE_PATH?>/assets/img/logo-disi.svg" alt="">
                        </a>
                        <meta itemprop="name" content="<?= $APPLICATION->ShowTitle() ?>">
                        <meta itemprop="telephone" content="<?= \Bitrix\Main\Config\Option::get("meven.info", "phone") ?>">
                        <meta itemprop="address" content="<?= \Bitrix\Main\Config\Option::get("meven.info", "address") ?>">
                        <meta itemprop="email" content="<?= \Bitrix\Main\Config\Option::get("meven.info", "email") ?>">
                        <a id="catalogMenu" class="btn btn--black btn--catalog mr-md-32" href="#catalog" data-fancybox data-touch="false" data-base-class="fancybox-catalog" data-close-existing="true">Каталог</a>
                    </div>
					<?if(CModule::IncludeModule("arturgolubev.smartsearch")):?>
						<?$APPLICATION->IncludeComponent(
							"arturgolubev:search.title", 
							"top-top", 
							array(
								"COMPONENT_TEMPLATE" => "top-top",
								"NUM_CATEGORIES" => "1",
								"TOP_COUNT" => "5",
								"ORDER" => "rank",
								"USE_LANGUAGE_GUESS" => "Y",
								"CHECK_DATES" => "N",
								"SHOW_OTHERS" => "N",
								"INPUT_ID" => "title-search-input",
								"PAGE" => "/search/",
								"CATEGORY_0_TITLE" => "",
								"CATEGORY_0" => array(
									0 => "no",
								)
							),
							false
						);?>
					<?else:?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:search.title", 
							"top-top", 
							array(
								"COMPONENT_TEMPLATE" => "top-top",
								"NUM_CATEGORIES" => "1",
								"TOP_COUNT" => "5",
								"ORDER" => "date",
								"USE_LANGUAGE_GUESS" => "Y",
								"CHECK_DATES" => "N",
								"SHOW_OTHERS" => "N",
								"INPUT_ID" => "title-search-input",
								"PAGE" => "#SITE_DIR#search/index.php",
								"CATEGORY_0_TITLE" => "",
								"CATEGORY_0" => array(
									0 => "no",
								)
							),
							false
						);?>
					<?endif;?>
					
                    
                    
                    <div class="header__tel d-none d-xl-flex">
						<?/*<div class="p-xs text-gray">Бесплатный звонок по России</div>*/?>
                        <a class="link link--tel" href="tel:<?=\Meven\Info\Metods::forcall($phone)?>"><?=$phone?></a>
                        <a class="link link--dashed" href="/local/ajax/popups/callback.php" data-fancybox
                           data-touch="false"
                           data-type="ajax">Заказать звонок</a>
                    </div>
                    <div class="header__user-panel">
                        <a class="icon-link d-lg-none order-1 order-md-0" href="/search/">
                            <svg class="icon icon-search">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-search"></use>
                            </svg>
                        </a>
                        <a class="icon-link icon-link--status" href="/local/ajax/popups/order_status.php" data-toggle="popover" data-placement="bottom" data-content="Статус заказа" data-trigger="hover" data-fancybox data-type="ajax" data-touch="false" data-close-existing="true">
                            <svg class="icon icon-info">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-info"></use>
                            </svg>
                        </a>
                        <a class="icon-link icon-link--chart" href="/catalog/?action=COMPARE" data-toggle="popover"
                           data-placement="bottom" data-content="Сравнение" data-trigger="hover">
                            <svg class="icon icon-chart-bar">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-chart-bar"></use>
                            </svg><span class="icon-link__count d-none">0</span>
                        </a>
                        <a class="icon-link icon-link--star" href="/personal/favorite/" data-toggle="popover"
                           data-placement="bottom" data-content="Отложенные товары" data-trigger="hover">
                            <svg class="icon icon-star-empty">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-star-empty"></use>
                            </svg><span class="icon-link__count d-none">0</span>
                        </a>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:sale.basket.basket.line",
                            "top",
                            Array(
                            ),
                            false
                        );?>

                        <?php if ($USER->IsAuthorized()):?>
                            <a class="icon-link icon-link--user d-none d-xl-flex" href="/personal/">
                                <svg class="icon icon-user">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-user"></use>
                                </svg>
                            </a>
                        <?php else:?>
                            <a class="icon-link icon-link--user d-none d-xl-flex" href="/local/ajax/popups/auth.php"
                               data-fancybox data-touch="false" data-type="ajax" data-close-existing="true">
                                <svg class="icon icon-user">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-user"></use>
                                </svg>
                            </a>
                        <?php endif;?>
                    </div>
                    <a class="header__icon-tel d-xl-none" href="tel:<?=$phone?>">
                        <svg class="icon icon-phone">
                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-phone"></use>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="d-none">
                <div class="catalog __loading" id="catalog">
                    <svg class="spinner animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <div class="d-none">
                <div class="mob-menu" id="mob-menu">
                    <div class="header d-md-none px-20 mb-0">
                        <div class="header__row">
                            <a class="btn btn--menu mr-md-16 d-xl-none" href="#mob-menu" data-fancybox-close>
                                <svg class="icon icon-close">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                                </svg>
                            </a>
                            <div class="header__logo-wrap">
                                <a class="header__logo" href="#">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/logo-disi.svg" alt="">
                                </a>
                                <a id="catalogMenu" class="btn btn--black btn--catalog mr-md-32" href="#catalog" data-fancybox data-touch="false" data-base-class="fancybox-catalog" data-close-existing="true">Каталог</a>
                            </div>
                            <a class="header__icon-tel d-xl-none" href="#">
                                <svg class="icon icon-phone">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-phone"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="mob-menu__scroll px-20" data-scrollbar>
                        <button class="mob-menu__close link d-none d-md-block" type="button" data-fancybox-close>
                            <svg class="icon icon-close">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                            </svg>
                        </button>
                        <div class="mob-menu__head">
                            <svg class="icon icon-map-full mr-8">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-map-full"></use>
                            </svg>
                            <a class="link link--dashed mr-auto" >Абакан</a>
                            <?php if ($USER->IsAuthorized()):?>
                                <a class="icon-link icon-link--user" href="/personal/">
                                    <svg class="icon icon-user">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-user"></use>
                                    </svg>
                                </a>
                            <?php else:?>
                                <a class="icon-link icon-link--user" href="/local/ajax/popups/auth.php" data-fancybox data-touch="false"
                                   data-type="ajax" data-close-existing="true">
                                    <svg class="icon icon-user">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-user"></use>
                                    </svg>
                                </a>
                            <?php endif;?>
                        </div>
                        <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top-mobile", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "top",
		"DELAY" => "N",
		"MAX_LEVEL" => "4",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "top",
		"USE_EXT" => "Y",
		"COMPONENT_TEMPLATE" => "top-mobile",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>

                    </div>
                </div>
            </div>
        </div>
    </header>
    <?php if ($page !== '/index.php'):?>
    <main <? $APPLICATION->ShowProperty('MAIN_SCHEMA') ?>>
        <?php
        //  if ($APPLICATION->GetProperty("typeheader") == 'bg'):
        // new
        if ($APPLICATION->GetProperty("typeheader") == 'bg' && !$isProduct):?>
            <section class="section <?=($httpStatusCode !== 404) ? $APPLICATION->GetProperty("ADDCLASS") : 'pt-20'?>">
                <div class="container">
                    <?php $APPLICATION->IncludeComponent(
                        "bitrix:breadcrumb",
                        "main",
                        array(
                            "COMPONENT_TEMPLATE" => "main",
                            "START_FROM" => "0",
                            "PATH" => "",
                            "SITE_ID" => "s1"
                        ),
                        false
                    );?>
                </div>
                <article>
                    <?php if (!$isBrands):?>
                        <?php if($httpStatusCode !== 404):?>
                            <div class="container">
                                <h1 class="mb-16 section__title mr-40"><?$APPLICATION->ShowTitle()?></h1>
                                <?=$APPLICATION->ShowViewContent('countelems')?>
                            </div>
                        <?php endif;?>
                    <?php endif;?>
                    <?php if (!$excludePages):?>
                    <?php if (!$isBrands):?>
                            <div class="section__overlay <?=($httpStatusCode !== 404) ? 'mb-32 mb-lg-80 pt-lg-56' : 'section__overlay--bottom-bg'?>">
                        <?php endif;?>
                        <?php if ($isTextPages):?>
                        <div class="container">
                            <div class="section__article-text">
                        <?php endif;?>
                    <?php endif;?>
        <?php else:?>
            <!--if(!$isNews && !$isCatalog && !$isSearch):  -->
            <!-- new -->
        <?php if(!$isNews && !$isCatalog && !$isSearch && !$isProduct):?>
            <section class="section <?=($httpStatusCode !== 404) ? $APPLICATION->GetProperty("ADDCLASS") : 'pt-20'?>">
                <div class="container">
                    <?php $APPLICATION->IncludeComponent(
                        "bitrix:breadcrumb",
                        "main",
                        array(
                            "COMPONENT_TEMPLATE" => "main",
                            "START_FROM" => "0",
                            "PATH" => "",
                            "SITE_ID" => "s1"
                        ),
                        false
                    );?>
                        <h1 class="mb-32 mb-lg-44"><?$APPLICATION->ShowTitle(false)?></h1>
                    <?php endif;?>
        <?php endif;?>
    <?php endif;?>
