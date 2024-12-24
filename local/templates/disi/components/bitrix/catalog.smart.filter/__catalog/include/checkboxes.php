<div class="filters__block">
    <a class="filters__title link" href="#ff2" data-toggle="collapse" aria-expanded="true"><?= $item['NAME'] ?>
        <svg class="icon icon-chevron-up">
            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
        </svg>
    </a>
    <div class="collapse show" id="ff2">
        <? $num = 0; ?>
        <?php foreach ($item['VALUES'] as $ar) : ?>
            <? $num++; ?>
            <?php
            if ($num > 6) {
                break;
            }
            ?>
            <div class="form-block mb-16">
                <label class="form-block__checkbox form-block__checkbox--dot">
                    <input type="checkbox" value="<?= $ar["HTML_VALUE"] ?>" name="<?= $ar["CONTROL_NAME"] ?>" id="<?= $ar["CONTROL_ID"] ?>" <?= $ar["CHECKED"] ? 'checked="checked"' : '' ?> onclick="smartFilter.click(this)" />
                    <span>
                        <?= $ar['VALUE'] ?>
                        <?php if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])) : ?>
                            <span data-role="count_<?= $ar["CONTROL_ID"] ?>">(<?= $ar["ELEMENT_COUNT"] ?>)</span>
                        <?php endif; ?>
                    </span>

                </label>
            </div>
        <?php endforeach; ?>
        <? $num = 0; ?>
        <?php if (count($item['VALUES']) > 6) : ?>
            <?php foreach ($item['VALUES'] as $ar) : ?>
                <? $num++; ?>
                <?php
                if ($num < 6) {
                    continue;
                }
                ?>
                <div class="collapse" id="ff2-h">
                    <div class="form-block mb-16">
                        <label class="form-block__checkbox form-block__checkbox--dot">
                            <input type="checkbox" value="<?= $ar["HTML_VALUE"] ?>" name="<?= $ar["CONTROL_NAME"] ?>" id="<?= $ar["CONTROL_ID"] ?>" <?= $ar["CHECKED"] ? 'checked="checked"' : '' ?> onclick="smartFilter.click(this)" />
                            <span>
                                <?= $ar['VALUE'] ?>
                                <?php if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])) : ?>
                                    <span data-role="count_<?= $ar["CONTROL_ID"] ?>">(<?= $ar["ELEMENT_COUNT"] ?>)</span>
                                <?php endif; ?>
                            </span>

                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
            <a class="link link--underline link--more" href="#ff2-h" data-toggle="collapse" data-show="Показать все" data-close="Скрыть"></a>
        <?php endif; ?>

        <div class="text-right pb-16">
            <a class="filters__clear" href="#">Очистить
                <svg class="icon icon-close icon-16 ml-4">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-close"></use>
                </svg>
            </a>
        </div>
    </div>
</div>