<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<ul class="footer__menu p-md">
    <?php foreach ($arResult as $item):?>
        <li>
            <a class="link" href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
        </li>
    <?php endforeach;?>
</ul>