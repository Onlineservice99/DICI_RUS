<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Location;

Loc::loadMessages(__FILE__);

$asset = \Bitrix\Main\Page\Asset::getInstance();
$asset->addJs($templateFolder.'/dist/script.bundle.js');
if ($arResult['LOCATION']['NAME'] != '') {
    $city = $arResult['LOCATION']['NAME'];
} else {
    $city = \Meven\Helper\City::getInstance()->getNameCity();
}
?>
<div class="form-block">
    <input class="form-block__input" id="location" name="<?=$arParams['INPUT_NAME']?>" value="<?=$city?>">
    <input type="hidden" class="js-refresh-elem location_hidden_field" value="<?=$arResult['LOCATION']['CODE']?>"
           name="ORDER_PROP_<?=$arParams['ID_PROP']?>">
    <div id="js-city-ul" class="d-none" style="position: absolute; top: 83px; background-color: #fff; width: 100%;z-index: 3;">
        <ul></ul>
    </div>
    <label class="form-block__label">Населенный пункт</label>
    <div class="form-block__label form-block__label--error">ошибка</div>
</div>

<script>
    new Meven.Components.SaleSearchCity();
</script>