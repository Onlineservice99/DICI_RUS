<?php
use Bitrix\Main\Grid\Declension;
use Meven\Helper\Helper;
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$templateData['COUNT'] = $arResult['COUNT'];
?>
<?
$cacheTime = 3600; // Время кеширования в секундах
$cacheId = 'catalog_sections_' . md5(serialize($arResult['SECTIONS'])); // Уникальный идентификатор кеша
$cacheDir = '/catalog_sections'; // Директория кеша

$cache = Bitrix\Main\Data\Cache::createInstance(); // Создание экземпляра кеша

if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
    $vars = $cache->getVars(); // Получение переменных из кеша
    $strOutput = $vars['strOutput'];
} elseif ($cache->startDataCache()) {
    ob_start(); // Начинаем буферизацию вывода

    if (count($arResult['SECTIONS']) > 0):
?>
<?/*
<div class="row mx-n12 mx-xxl-n16">



    <?php foreach ($arResult['SECTIONS'] as $section):
        // Предварительно вычислим количество элементов, чтобы избежать повторных вызовов count()
        $elemsCount = count($section['ELEMS']);
        ?>
        <div class="col-md-6 col-xl-4 d-flex pb-32 px-12 px-xxl-16">
            <div class="catalog-card">
                <img class="catalog-card__ic" src="<?= $section['PICTURE']['SRC'] ?>" alt="">
                <div class="pl-16">
                    <a class="catalog-card__title link h4" href="<?= $section['SECTION_PAGE_URL'] ?>"><?= $section['NAME'] ?>&nbsp;&nbsp;<span>(<?= Helper::formatNumber($section['ELEMENT_CNT']) ?>)</span></a>
                    <ul class="catalog-card__list">
                        <?php
                        $limit = min($elemsCount, 5);
                        for ($key = 0; $key < $limit; $key++): 
                            $s = $section['ELEMS'][$key];
                            ?>
                            <li>
                                <a class="link" href="<?= $s['SECTION_PAGE_URL'] ?>"><?= $s['NAME'] ?>&nbsp;&nbsp;<span>(<?= Helper::formatNumber($s['ELEMENT_CNT']) ?>)</span></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                    <?php if ($elemsCount > 5): ?>
                        <div class="catalog-card__hide-block">
                            <a class="catalog-card__link-all link link--dashed" href="#">
                                <svg class="icon icon-close icon-16">
                                    <use xlink:href="./icons/symbol-defs.svg#icon-close"></use>
                                </svg><span>Показать все</span>
                            </a>
                            <ul class="catalog-card__list">
                                <?php
                                for ($key = 5; $key < $elemsCount; $key++):
                                    $elem = $section['ELEMS'][$key];
                                    ?>
                                    <li>
                                        <a class="link" href="<?= $elem['SECTION_PAGE_URL'] ?>"><?= $elem['NAME'] ?>&nbsp;&nbsp;<span>(<?= $elem['ELEMENT_CNT'] ?>)</span></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php $APPLICATION->IncludeFile(SITE_DIR . 'include/pricelist.php'); ?>
</div>*/?>

<div class="row mx-n12 mx-xxl-n16">
    <?php foreach ($arResult['SECTIONS'] as $section):
        $elemsCount = count($section['ELEMS']);
    ?>
    <div class="col-md-6 col-xl-4 d-flex pb-32 px-12 px-xxl-16">
        <div class="catalog-card">
            <img class="catalog-card__ic" src="<?= $section['PICTURE']['SRC'] ?>" alt="">
            <div class="pl-16">
                <a class="catalog-card__title link h4" href="<?= $section['SECTION_PAGE_URL'] ?>"><?= $section['NAME'] ?>&nbsp;&nbsp;<span>(<?= Helper::formatNumber($section['ELEMENT_CNT']) ?>)</span></a>
                <ul class="catalog-card__list">
                    <?php
                    $limit = min($elemsCount, 5);
                    for ($key = 0; $key < $limit; $key++):
                        $s = $section['ELEMS'][$key];
                    ?>
                        <li>
                            <a class="link" href="<?= $s['SECTION_PAGE_URL'] ?>"><?= $s['NAME'] ?>&nbsp;&nbsp;<span>(<?= Helper::formatNumber($s['ELEMENT_CNT']) ?>)</span></a>
                        </li>
                    <?php endfor; ?>
                </ul>
                <?php if ($elemsCount > 5): ?>
                    <div class="catalog-card__hide-block">
                        <a class="catalog-card__link-all link link--dashed" href="#">
                            <svg class="icon icon-close icon-16">
                                <use xlink:href="./icons/symbol-defs.svg#icon-close"></use>
                            </svg><span>Показать все</span>
                        </a>
                        <ul class="catalog-card__list">
                            <?php
                            for ($key = 5; $key < $elemsCount; $key++):
                                $elem = $section['ELEMS'][$key];
                            ?>
                                <li>
                                    <a class="link" href="<?= $elem['SECTION_PAGE_URL'] ?>"><?= $elem['NAME'] ?>&nbsp;&nbsp;<span>(<?= $elem['ELEMENT_CNT'] ?>)</span></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php $APPLICATION->IncludeFile(SITE_DIR . 'include/pricelist.php'); ?>
</div>

<?php
    endif;
    $strOutput = ob_get_clean(); // Получаем текущий буфер и очищаем его
    $cache->endDataCache(['strOutput' => $strOutput]); // Сохраняем данные в кеш
}

echo $strOutput; // Выводим результат
?>

<?php $this->SetViewTarget('countelems'); ?>
    <div class="p-xs bg-white px-16 py-8 rounded-lg mb-12"><?= Helper::formatNumber($templateData['COUNT']) . ' ' . (new Declension('товар', 'товара', 'товаров'))->get($templateData['COUNT']) ?></div>
<?php $this->EndViewTarget(); ?>