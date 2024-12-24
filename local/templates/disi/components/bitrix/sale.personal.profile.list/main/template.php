<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if($arResult["ERROR_MESSAGE"] <> '')
{
	ShowError($arResult["ERROR_MESSAGE"]);
}

$includedFields = array("NAME", "EMAIL", "STREET", "HOUSE", "CORPUS", "ENTRANCE", "OFIS");
?>

<?if (is_array($arResult["PROFILES"]) && !empty($arResult["PROFILES"])):
    ?>
    <div class="profile__addresses js-profiles-list">
		<?php foreach($arResult["PROFILES"] as $val):?>
            <?php //if(empty($val["ADDRESS"]["VALUE"])) continue
            if(!in_array($val['PROP_CODE'], $includedFields)) continue;
            ?>
            <div class="address js-address-item" data-profile-item-id="<?=$val["ID"]?>">
                <?/*php if(!empty($val["LEGAL_ADDRESS"]["VALUE"])):?>
                    <?=$val["LEGAL_ADDRESS"]["VALUE"]?>
                <?php else:?>
                    <?=$val["ADDRESS"]["VALUE"]?>
                <?php endif;*/?>
                <?=$val["STREET"].' '.$val["HOUSE"].' '.$val["CORPUS"].' '.$val["ENTRANCE"].' '.$val["OFIS"]?>
                <?/*<a href="javascript:if(confirm('<?=Loc::getMessage("STPPL_DELETE_CONFIRM") ?>')) window.location='<?=$val["URL_TO_DETELE"] ?>'">*/?>
                <a class="profile__delete-btn" href="javascript:;" data-profile-id="<?=$val["ID"]?>">
                    Удалить
                    <svg class="icon icon-close icon-16 ml-8">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-close"></use>
                    </svg>
                </a>
            </div>
        <?php endforeach;?>
        <?php
        if($arResult["NAV_STRING"] <> '')
        {
            echo $arResult["NAV_STRING"];
        }?>
    </div>
<?php elseif ($arResult['USER_IS_NOT_AUTHORIZED'] !== 'Y'):?>
	<p><?=Loc::getMessage("STPPL_EMPTY_PROFILE_LIST") ?></p>
<?php endif;?>