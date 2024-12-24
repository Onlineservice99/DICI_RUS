<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<nav class="header__top-nav">
    <?php foreach ($arResult as $item):?>
        <a class="link" href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
    <?php endforeach;?>
</nav>
