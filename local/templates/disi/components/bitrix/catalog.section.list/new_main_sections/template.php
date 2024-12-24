<section class="section">
    <div class="container">
        <div class="categories-block bg-light">
            <div class="categories_toper row align-items-center pb-12">
                <h2 class="h2 col mr-auto mb-28">Категории</h2>
                <div class="col-xl-auto mb-28">
                    <div class="row">
                        <div class="col-sm-auto">
                            <a class="btn btn--border btn--sm w-100" href="/catalog">
                                <svg class="icon icon-plus mr-8">
                                    <use xlink:href="/local/templates/disi/assets/icons/symbol-defs.svg#icon-plus"></use>
                                </svg>
                                Все категории
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <?/*<div class="categories_items">
                <?php $revert = 0 ?>
                <?php for ($i = 0; $i < count($arResult['SECTIONS']); $i++): ?>
                    <?php $arFile = CFile::GetFileArray($arResult['SECTIONS'][$i]['PICTURE']['SRC']); ?>
                    <?php if (!$revert): ?>
                        <?php
                        if ($i > 8) {
                            break;
                        }
                        ?>
                        <div class="categories_row">
                            <div>
                                <a href="<?= $arResult['SECTIONS'][$i]['SECTION_PAGE_URL'] ?? '#' ?>"
                                   class="categories_item categories_item__big a">
                                    <img src="<?= $arResult['SECTIONS'][$i]['PICTURE']['SRC'] ?>"
                                         alt="">
                                    <span>
                                            <span class="categories_item-txt"><?= $arResult['SECTIONS'][$i]['NAME'] ?></span>
                                            <span class="categories_item-num"><?= $arResult['SECTIONS'][$i]['ELEMENT_CNT'] ?></span>
                                        </span>
                                </a>
                            </div>
                            <div>
                                <?php $i++ ?>
                                <div class="categories_row">
                                    <div>
                                        <a href="<?= $arResult['SECTIONS'][$i]['SECTION_PAGE_URL'] ?? '#' ?>"
                                           class="categories_item a">
                                            <img src="<?= $arResult['SECTIONS'][$i]['PICTURE']['SRC'] ?? "/categories/2.svg" ?>"
                                                 alt="">
                                            <span>
                                                    <span class="categories_item-txt"><?= $arResult['SECTIONS'][$i]['NAME'] ?></span>
                                                    <span class="categories_item-num"><?= $arResult['SECTIONS'][$i]['ELEMENT_CNT'] ?></span>
                                                </span>
                                        </a>
                                    </div>
                                    <?php $i++ ?>
                                    <div>
                                        <a href="<?= $arResult['SECTIONS'][$i]['SECTION_PAGE_URL'] ?? '#' ?>"
                                           class="categories_item a">
                                            <img src="<?= $arResult['SECTIONS'][$i]['PICTURE']['SRC'] ?>"
                                                 alt="">
                                            <span>
                                                    <span class="categories_item-txt"><?= $arResult['SECTIONS'][$i]['NAME'] ?></span>
                                                    <span class="categories_item-num"><?= $arResult['SECTIONS'][$i]['ELEMENT_CNT'] ?></span>
                                                </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="categories_row">
                            <div>
                                <div class="categories_row">
                                    <div>
                                        <a href="<?= $arResult['SECTIONS'][$i]['SECTION_PAGE_URL'] ?? '#' ?>"
                                           class="categories_item a">
                                            <img src="<?= $arResult['SECTIONS'][$i]['PICTURE']['SRC'] ?>"
                                                 alt="">
                                            <span>
                                                    <span class="categories_item-txt"><?= $arResult['SECTIONS'][$i]['NAME'] ?></span>
                                                    <span class="categories_item-num"><?= $arResult['SECTIONS'][$i]['ELEMENT_CNT'] ?></span>
                                                </span>
                                        </a>
                                    </div>
                                    <?php $i++ ?>
                                    <div>
                                        <a href="<?= $arResult['SECTIONS'][$i]['SECTION_PAGE_URL'] ?? '#' ?>"
                                           class="categories_item a">
                                            <img src="<?= $arResult['SECTIONS'][$i]['PICTURE']['SRC'] ?>"
                                                 alt="">
                                            <span>
                                                    <span class="categories_item-txt"><?= $arResult['SECTIONS'][$i]['NAME'] ?></span>
                                                    <span class="categories_item-num"><?= $arResult['SECTIONS'][$i]['ELEMENT_CNT'] ?></span>
                                                </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php $i++ ?>
                            <div>
                                <a href="<?= $arResult['SECTIONS'][$i]['SECTION_PAGE_URL'] ?? '#' ?>"
                                   class="categories_item categories_item__big a">
                                    <img src="<?= $arResult['SECTIONS'][$i]['PICTURE']['SRC'] ?>"
                                         alt="">
                                    <span>

                                            <span class="categories_item-txt"><?= $arResult['SECTIONS'][$i]['NAME'] ?></span>
                                            <span class="categories_item-num"><?= $arResult['SECTIONS'][$i]['ELEMENT_CNT'] ?></span>
                                        </span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php
                    $revert = !$revert;
                    ?>
                <?php endfor; ?>
            </div>*/ ?>
            <?
            $cacheTime = 3600; // Время кеширования в секундах
            $cacheId = 'categories_items_' . md5(serialize($arResult['SECTIONS'])); // Уникальный идентификатор кеша
            $cacheDir = '/categories_items'; // Директория кеша

            $cache = Bitrix\Main\Data\Cache::createInstance(); // Создание экземпляра кеша

            if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
                $vars = $cache->getVars(); // Получение переменных из кеша
                $strOutput = $vars['strOutput'];
            } elseif ($cache->startDataCache()) {
                ob_start(); // Начинаем буферизацию вывода

                if (count($arResult['SECTIONS']) > 0) :
            ?>
                    <div class="categories_items">
                        <?php $revert = 0 ?>
                        <?php for ($i = 0; $i < count($arResult['SECTIONS']); $i++) : ?>
                            <?php $arFile = CFile::GetFileArray($arResult['SECTIONS'][$i]['PICTURE']['SRC']); ?>
                            <?php if (!$revert) : ?>
            <?php
            if ($i > 8) {
                break;
            }
            ?>
            <div class="categories_row">
                <div>
                    <a href="<?= $arResult['SECTIONS'][$i]['SECTION_PAGE_URL'] ?? '#' ?>" class="categories_item categories_item__big a">
                        <img src="<?= $arResult['SECTIONS'][$i]['PICTURE']['SRC'] ?>" alt="">
                        <span>
                            <span class="categories_item-txt"><?= $arResult['SECTIONS'][$i]['NAME'] ?></span>
                            <span class="categories_item-num"><?= $arResult['SECTIONS'][$i]['ELEMENT_CNT'] ?></span>
                        </span>
                    </a>
                </div>
                <div>
                    <?php $i++ ?>
                    <div class="categories_row">
                        <div>
                            <a href="<?= $arResult['SECTIONS'][$i]['SECTION_PAGE_URL'] ?? '#' ?>" class="categories_item a">
                                <img src="<?= $arResult['SECTIONS'][$i]['PICTURE']['SRC'] ?? "/categories/2.svg" ?>" alt="">
                                <span>
                                    <span class="categories_item-txt"><?= $arResult['SECTIONS'][$i]['NAME'] ?></span>
                                    <span class="categories_item-num"><?= $arResult['SECTIONS'][$i]['ELEMENT_CNT'] ?></span>
                                </span>
                            </a>
                        </div>
                        <?php $i++ ?>
                        <div>
                            <a href="<?= $arResult['SECTIONS'][$i]['SECTION_PAGE_URL'] ?? '#' ?>" class="categories_item a">
                                <img src="<?= $arResult['SECTIONS'][$i]['PICTURE']['SRC'] ?>" alt="">
                                <span>
                                    <span class="categories_item-txt"><?= $arResult['SECTIONS'][$i]['NAME'] ?></span>
                                    <span class="categories_item-num"><?= $arResult['SECTIONS'][$i]['ELEMENT_CNT'] ?></span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="categories_row">
                <div>
                    <div class="categories_row">
                        <div>
                            <a href="<?= $arResult['SECTIONS'][$i]['SECTION_PAGE_URL'] ?? '#' ?>" class="categories_item a">
                                <img src="<?= $arResult['SECTIONS'][$i]['PICTURE']['SRC'] ?>" alt="">
                                <span>
                                    <span class="categories_item-txt"><?= $arResult['SECTIONS'][$i]['NAME'] ?></span>
                                    <span class="categories_item-num"><?= $arResult['SECTIONS'][$i]['ELEMENT_CNT'] ?></span>
                                </span>
                            </a>
                        </div>
                        <?php $i++ ?>
                        <div>
                            <a href="<?= $arResult['SECTIONS'][$i]['SECTION_PAGE_URL'] ?? '#' ?>" class="categories_item a">
                                <img src="<?= $arResult['SECTIONS'][$i]['PICTURE']['SRC'] ?>" alt="">
                                <span>
                                    <span class="categories_item-txt"><?= $arResult['SECTIONS'][$i]['NAME'] ?></span>
                                    <span class="categories_item-num"><?= $arResult['SECTIONS'][$i]['ELEMENT_CNT'] ?></span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <?php $i++ ?>
                <div>
                    <a href="<?= $arResult['SECTIONS'][$i]['SECTION_PAGE_URL'] ?? '#' ?>" class="categories_item categories_item__big a">
                        <img src="<?= $arResult['SECTIONS'][$i]['PICTURE']['SRC'] ?>" alt="">
                        <span>

                            <span class="categories_item-txt"><?= $arResult['SECTIONS'][$i]['NAME'] ?></span>
                            <span class="categories_item-num"><?= $arResult['SECTIONS'][$i]['ELEMENT_CNT'] ?></span>
                        </span>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        <?php
        $revert = !$revert;
                            ?>
                        <?php endfor; ?>
                    </div>
            <?php
                endif;
                $strOutput = ob_get_clean(); // Получаем текущий буфер и очищаем его
                $cache->endDataCache(['strOutput' => $strOutput]); // Сохраняем данные в кеш
            }

            echo $strOutput; // Выводим результат
            ?>
</section>