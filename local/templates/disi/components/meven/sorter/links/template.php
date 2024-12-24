<?php
use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
global $APPLICATION;
?>
<div class="dropdown d-inline-block mb-32">
    <a class="link link--sort" href="#" data-toggle="dropdown">
        <svg class="icon icon-star-empty icon-20 text-red mr-8">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-star-empty"></use>
        </svg><?=(!empty(Loc::getMessage('SORT_'.$_SESSION["SORT_FIELD"]))) ? Loc::getMessage('SORT_'.$_SESSION["SORT_FIELD"]) : Loc::getMessage('SORT_ACTIVE_TO')?>
    </a>
    <div class="dropdown-menu">
        <?php
        foreach($arResult['SORTING_TYPES'] as $key => $val):
            $newSort = $val[1];
            $current_url = $APPLICATION->GetCurPageParam('sort='.$key.'&order='.$val[1], ['sort', 'order']);
            $url = str_replace('+', '%2B', $current_url);
            ?>
            <a class="dropdown-item" href="<?=$url;?>" <?=$newSort?> <?=$key?>><?=Loc::getMessage('SORT_'.$key)?></a>
        <?php endforeach;?>
    </div>
</div>
<?php
if($arResult['SORT'] == "PRICE"){
    $_SESSION['SORT_FIELD'] = $arResult['SORTING_TYPES']["PRICE"][0];
}