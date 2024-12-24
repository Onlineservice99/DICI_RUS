<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult)):?>
    <ul class="nav nav-pills nav-pills--white">
        <li class="nav-item">
            <a class="nav-link <?=(CSite::InDir(SITE_DIR.'news/index.php') ? 'active' : '')?>" href="/news/">Все</a>
        </li>
        <?php
        foreach($arResult as $arItem):
            ?>
             <?php if($arItem["SELECTED"]):?>
                <li class="nav-item">
                    <a class="nav-link active" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
                </li>
            <?php else:?>
                <li class="nav-item">
                    <a class="nav-link" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
                </li>
            <?php endif?>
        <?php endforeach?>
    </ul>
<?php endif?>