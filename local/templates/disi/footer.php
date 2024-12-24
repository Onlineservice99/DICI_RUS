<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Config\Option;
$page = $APPLICATION->GetCurPage(true);
?>

<?php if ($page !== '/index.php'): ?>
    <?php if ($APPLICATION->GetProperty("typeheader") == 'bg'): ?>
        <?php if (!$isBrands): ?>
                </div>
        <?php endif; ?>
            <?php if ($isTextPages): ?>
                </div>
            </div>
            <?php endif; ?>
            <?php if (!$excludePages && !CSite::InDir(SITE_DIR . 'brands/index.php') && $httpStatusCode !== 404 && $APPLICATION->GetProperty('showfeature') != 'N'): ?>
                <div class="container mb-52 mb-lg-100">
                    <?php $APPLICATION->IncludeFile(SITE_DIR . "include/teasers.php"); ?>
                </div>
            <?php endif; ?>
        </article>
    <?php else: ?>
            </div>
        </div>
    <?php endif; ?>

    </section>
    <?php if ($APPLICATION->GetProperty('showfeature') == 'Y'): ?>
            <?php $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "features",
                array(
                    "COMPONENT_TEMPLATE" => "features",
                    "IBLOCK_TYPE" => "content",
                    "IBLOCK_ID" => Option::get("meven.info", "iblock_features"),
                    "NEWS_COUNT" => "20",
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_ORDER1" => "DESC",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER2" => "ASC",
                    "FILTER_NAME" => "",
                    "LINK_REGISTER" => "/auth/reg/",
                    "FIELD_CODE" => array(
                        0 => "",
                        1 => "",
                    ),
                    "PROPERTY_CODE" => array(
                        0 => "REGISTR",
                        1 => "LINK",
                        2 => "",
                    ),
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "36000000",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "SET_TITLE" => "N",
                    "SET_BROWSER_TITLE" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "STRICT_SECTION_CHECK" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "Y",
                    "PAGER_TITLE" => "Новости",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "SET_STATUS_404" => "N",
                    "SHOW_404" => "N",
                    "MESSAGE_404" => "",
                ),
                false
            ); ?>
    <?php endif; ?>

    <?php if ($APPLICATION->GetProperty('shownews') == 'Y'): ?>
        <section class="section mb-lg-72">
            <a name="shownews"></a>
            <div class="container">
                <?php $APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    "main-news",
                    array(
                        "COMPONENT_TEMPLATE" => "main-news",
                        "IBLOCK_TYPE" => "-",
                        "IBLOCK_ID" => Option::get("meven.info", "iblock_news"),
                        "NEWS_COUNT" => "20",
                        "SORT_BY1" => "ACTIVE_FROM",
                        "SORT_ORDER1" => "DESC",
                        "SORT_BY2" => "SORT",
                        "SORT_ORDER2" => "ASC",
                        "FILTER_NAME" => "",
                        "LINK_REGISTER" => "/auth/reg/",
                        "FIELD_CODE" => array(
                            0 => "",
                            1 => "",
                        ),
                        "PROPERTY_CODE" => array(
                            0 => "",
                            1 => "REGISTR",
                            2 => "LINK",
                            3 => "",
                        ),
                        "CHECK_DATES" => "Y",
                        "DETAIL_URL" => "",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "ACTIVE_DATE_FORMAT" => "j F Y",
                        "SET_TITLE" => "N",
                        "SET_BROWSER_TITLE" => "N",
                        "SET_META_KEYWORDS" => "N",
                        "SET_META_DESCRIPTION" => "N",
                        "SET_LAST_MODIFIED" => "N",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "STRICT_SECTION_CHECK" => "N",
                        "PAGER_TEMPLATE" => ".default",
                        "DISPLAY_TOP_PAGER" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        "PAGER_TITLE" => "Новости",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "SET_STATUS_404" => "N",
                        "SHOW_404" => "N",
                        "MESSAGE_404" => "",
                        "BLOCK_TITLE" => " и публикации"
                    ),
                    false
                ); ?>
            </div>
        </section>
    <?php endif; ?>
    <?php if ($APPLICATION->GetProperty('showseo') == 'Y'): ?>
        <section class="section bg-light pt-48">
            <div class="container">
                <div class="seo-block">
                    <img class="d-none d-sm-block" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/seo-img.png" alt="">
                    <img class="d-sm-none" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/seo-text-mob.png" alt="">
                    <div class="seo-block__text">
                        <h2><? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => "/include/main/seo-title.php"
                                )
                            ); ?></h2>
                        <br>
                        <p class="p-md">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                [
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => "/include/main/seo-description.php"
                                ]
                            ); ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php endif; ?>
<footer class="footer">
    <div class="footer__fixed-link d-none d-xl-flex">
        <svg class="icon icon-calc">
            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-calc"></use>
        </svg>
        <a href="#">Помощь в подборе оборудования</a>
    </div>
    <button class="footer__to-top" type="button" onclick="scrollAnimateToBlock(event)">
        <svg class="icon icon-chevron-top-lg">
            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chevron-top-lg"></use>
        </svg>
    </button>
	<?php
	/*
    // Блок "ПОЛУЧАЙТЕ САМЫЕ ИНТЕРЕСНЫЕ ПРЕДЛОЖЕНИЯ ПЕРВЫМИ!" с подпиской
    $APPLICATION->IncludeComponent(
        'app:form.subscribe',
        '.default',
        [],
        false,
        ['HIDE_ICONS' => 'Y']
    );
    */
    ?>
    <div class="container">
        <div class="footer__row">
            <div class="footer__block">
                <div class="h4 h4--line mb-20 js-toggle">Компания</div>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "footer-company",
                    array(
                        "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
                        "CHILD_MENU_TYPE" => "bottom",	// Тип меню для остальных уровней
                        "DELAY" => "N",	// Откладывать выполнение шаблона меню
                        "MAX_LEVEL" => "4",	// Уровень вложенности меню
                        "MENU_CACHE_GET_VARS" => array(	// Значимые переменные запроса
                            0 => "",
                        ),
                        "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
                        "MENU_CACHE_TYPE" => "N",	// Тип кеширования
                        "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
                        "ROOT_MENU_TYPE" => "bottom",	// Тип меню для первого уровня
                        "USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
                    ),
                    false
                ); ?>

            </div>
            <div class="footer__block footer__block--center">
                <div class="h4 h4--line mb-20 js-toggle">Каталог</div>
                <div class="row justify-content-between flex-xl-nowrap mx-n12">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "footer-catalog",
                        array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "bottomcatalog",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "4",
                            "MENU_CACHE_GET_VARS" => array(),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "bottomcatalog",
                            "USE_EXT" => "Y",
                            "COMPONENT_TEMPLATE" => "footer-catalog"
                        ),
                        false
                    ); ?>
                </div>
            </div>
            <div class="footer__block">
                <div class="h4 h4--line mb-20">Контакты</div>
                <div class="footer__contact row no-gutters mb-24">
                    <svg class="icon icon-email text-blue">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-email"></use>
                    </svg>
                    <div class="pl-24">
                        <div class="p-xs text-gray mb-4">e-mail</div>
                        <a class="link p-md" href="mailto:<?= Option::get('meven.info', 'email') ?>"><?= Option::get('meven.info', 'email') ?></a>
                    </div>
                </div>
                <div class="footer__contact row no-gutters mb-24">
                    <svg class="icon icon-phone text-yellow">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-phone"></use>
                    </svg>
                    <div class="pl-24">
                        <div class="p-xs text-gray mb-4">телефон</div>
                        <a class="link link--tel" href="tel:<?= \Meven\Info\Metods::forcall(Option::get('meven.info', 'phone')) ?>">
                            <?= Option::get('meven.info', 'phone') ?>
                        </a>
                        <div class="p-xs text-gray">Бесплатный звонок по России</div>
                    </div>
                </div>
                <div class="h4 h4--line mb-20 pt-12">Время работы</div>
                <div class="footer__contact row no-gutters mb-24">
                    <svg class="icon icon-alarm text-violet">
                        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-alarm"></use>
                    </svg>
                    <div class="pl-24">
                        <div class="p-xs text-gray mb-4">Время указано по Абакану</div>
                        <? if (($workpnptStart = Option::get('meven.info', 'workpnpt-start')) && ($workpnptEnd = Option::get('meven.info', 'workpnpt-end'))): ?>
                            <div class="p-md fw-600">Пн-Пт - <?= $workpnptStart ?> - <?= $workpnptEnd ?></div>
                        <? endif; ?>
                        <? if (($worksbvsStart = Option::get('meven.info', 'worksbvs-start')) && ($worksbvsEnd = Option::get('meven.info', 'worksbvs-end'))): ?>
                            <div class="p-md fw-600">Сб-Вс - <?= $worksbvsStart ?> - <?= $worksbvsEnd ?></div>
                        <? endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer__border">
            <div class="footer__copyright p-md col-12 col-xl-auto px-0 pb-16 pb-xl-0"><? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            [
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => "/include/footer/copyright.php"
                            ]
                        ); ?></div>
            <div class="footer__pay-logo col-sm col-xl-auto pt-12 pb-24 py-sm-0 px-0">
                <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/paykeeper.png" alt="">
                <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/visa.png" alt="">
                <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/mastercard.png" alt="">
                <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/mir.png" alt="">
            </div>
        </div>
        <div class="footer__bottom text-sm-center p-xs py-20">Информация, размещенная на сайте не является публичной офертой и носит ознакомительный характер. Описание и фотографии некоторых товаров могут отличаться.</div>
    </div>
</footer>

<?php
\Bitrix\Main\UI\Extension::load("meven.favorite");
\Bitrix\Main\UI\Extension::load("meven.basket");
\Bitrix\Main\UI\Extension::load("meven.form");
\Bitrix\Main\UI\Extension::load("meven.compare");
?>
<script>
    var favorite = new Meven.Components.Favorite();
    favorite.getList();

    var basket = new Meven.Components.Basket();
    var form = new Meven.Components.Form();
    var compare = new Meven.Components.Compare();
    compare.getList();
</script>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
  (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
  m[i].l=1*new Date();
  for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
  k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
  (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

  ym(97206590, "init", {
       clickmap:true,
       trackLinks:true,
       accurateTrackBounce:true,
       webvisor:true
  });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/97206590" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script>
    jQuery('.js-toggle').click(function(){
        jQuery(this).toggleClass('active');
    });
</script>
<?/*<script src="//cdn.callibri.ru/callibri.js" type="text/javascript" charset="utf-8"></script>*/?>
</body>
</html>
