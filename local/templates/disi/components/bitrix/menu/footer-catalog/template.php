<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="footer__block">
    <ul class="footer__menu p-md">
        <?php foreach ($arResult as $key=>$item):?>
            <?php if ($key == ceil(count($arResult)/2)):?>
                </ul>
                </div>
            <div class="footer__block">
                <ul class="footer__menu p-md">
            <?php endif;?>
            <li>
                <a class="link" href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
            </li>
        <?php endforeach;?>
    </ul>
</div>