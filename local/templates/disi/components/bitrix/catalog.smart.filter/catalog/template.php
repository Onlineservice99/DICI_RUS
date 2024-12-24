<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<aside class="section__filters-aside d-none d-xl-block">
    <form class="filters d-xl-block" action="" method="" id="filters">
        <div class="container px-xl-0">
            <div class="d-xl-none mb-24">
                <div class="header px-0 mb-0">
                    <div class="header__row">
                        <a class="btn btn--menu mr-md-16 d-xl-none" href="#mob-menu" data-fancybox-close>
                            <svg class="icon icon-close">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                            </svg>
                        </a>
                        <div class="header__logo-wrap">
                            <a class="header__logo" href="#">
                                <img src="./img/logo-disi.svg" alt="">
                            </a>
                            <a class="btn btn--black btn--catalog mr-md-32" href="#catalog" data-fancybox data-touch="false" data-base-class="fancybox-catalog" data-close-existing="true">Каталог</a>
                        </div>
                        <a class="header__icon-tel d-xl-none" href="#">
                            <svg class="icon icon-phone">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-phone"></use>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="dropdown">
                    <a class="link link--sort" href="#" data-toggle="dropdown">
                        <svg class="icon icon-import-export icon-20 text-red mr-8">
                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-import-export"></use>
                        </svg>Сначала популярные
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Хит продаж</a>
                        <a class="dropdown-item" href="#">Распродажа</a>
                        <a class="dropdown-item" href="#">Рекомендуемые</a>
                        <a class="dropdown-item" href="#">Сначала дешевые</a>
                        <a class="dropdown-item" href="#">Сначала дорогие</a>
                    </div>
                </div>
            </div>
            <?php
                $priceKey = key($arResult['PRICES']);
                $item = $arResult['ITEMS'][$priceKey];
                $APPLICATION->SetPageProperty('minPrice', $item['VALUES']['MIN']['VALUE']);
                include \Bitrix\Main\Application::getDocumentRoot().$templateFolder.'/include/number.php';
            ?>
            <?php
            foreach ($arResult['ITEMS'] as $item):
                if ($item['PRICE'] || count($item['VALUES']) < 1) {
                    continue;
                }

                switch ($item['DISPLAY_TYPE']):
                    case 'A':
                        include \Bitrix\Main\Application::getDocumentRoot().$templateFolder.'/include/number.php';
                        break;

                    default:
                        include \Bitrix\Main\Application::getDocumentRoot().$templateFolder.'/include/checkboxes.php';
                endswitch;
                ?>

            <?php endforeach;?>

            <button class="btn btn--border w-100 px-0 fw-500" type="reset">Очистить все фильтры</button>
        </div>
    </form>
</aside>

<script type="text/javascript">
    var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>