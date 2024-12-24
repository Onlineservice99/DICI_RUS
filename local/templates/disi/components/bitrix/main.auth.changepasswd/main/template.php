<?php
defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED === true || die;

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if ($arResult['SUCCESS']) {
    LocalRedirect('/');
}

if ($arResult['AUTHORIZED'])
{
	echo Loc::getMessage('MAIN_AUTH_CHD_SUCCESS');
	return;
}

$fields = $arResult['FIELDS'];
?>

<form method="post" action="<?= POST_FORM_ACTION_URI ?>" name="bform1" class="form1 js-form-validation1">
    <input type="hidden" name="backurl" value="/" />
    <input type="hidden" name="<?= $arResult['FIELDS']['login'];?>" value="<?= $arResult["LAST_LOGIN"] ?>">
    <input type="hidden" name="<?= $arResult['FIELDS']['checkword'];?>" value="<?= $arResult[$fields['checkword']] ?>">

    <div class="col-lg-5">
        <?if ($arResult['ERRORS']):?>
            <div class="alert alert-danger">
                <? foreach ($arResult['ERRORS'] as $error){
                    echo $error;
                }
                ?>
            </div>
            <?elseif ($arResult['SUCCESS']):?>
                <div class="alert alert-success">
                    <?= $arResult['SUCCESS'];?>
                </div>
            <?endif;?>
    
        <div class="form-block mb-4">
            <input type="password" class="form-block__input js-validation" name="<?= $fields['password'];?>" required value="<?= $arResult[$fields['password']] ?>">
            <label class="form-block__label"><?= GetMessage("MAIN_AUTH_CHD_FIELD_PASS") ?></label>
        </div>
        
        <div class="form-block mb-4">
            <input type="password" class="form-block__input js-validation" name="<?= $fields['confirm_password'];?>" required value="<?= $arResult[$fields['confirm_password']] ?>">
            <label class="form-block__label"><?= GetMessage("MAIN_AUTH_CHD_FIELD_PASS2") ?></label>
        </div>

        <div class="pt-lg-16">
            <button type="submit" class="btn btn--red px-80" type="submit" name="<?= $fields['action'];?>" value="<?= GetMessage("MAIN_AUTH_CHD_FIELD_SUBMIT") ?>"><?= GetMessage("MAIN_AUTH_CHD_FIELD_SUBMIT") ?></button>
        </div>

        <div class="pt-lg-16">
            <?= $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?>
        </div>
    </div>
</form>