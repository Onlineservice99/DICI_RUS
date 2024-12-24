<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="row">
    <?php foreach ($arResult['GROUPS'] as $group) : ?>
        <div class="col-xxl-4">
            <?php foreach ($group as $item) : ?>
                <div class="bg-light rounded-sm mb-32">
                    <a class="link link--faq" href="#q<?= $item['ID'] ?>" data-toggle="collapse">
                        <svg class="icon icon-chevron-up flex-shrink-0 mr-8 mr-sm-16">
                            <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/assets/icons/symbol-defs.svg#icon-chevron-up"></use>
                        </svg>
                        <?= $item['PROPERTIES']['MESSAGE']['~VALUE']['TEXT'] ?>
                    </a>
                    <div class="collapse" id="q<?= $item['ID'] ?>">
                        <div class="pl-16 pl-sm-56 pr-16 pb-20">
                            <?= $item['PREVIEW_TEXT'] ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>