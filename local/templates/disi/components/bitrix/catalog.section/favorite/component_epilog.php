<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!empty($templateData['CONTENT'])) {
    global $APPLICATION;
    $APPLICATION->RestartBuffer();
    \Meven\Helper\Json::dumpSuccess($templateData);
}

?>
<?$this->__template->SetViewTarget("favorite_item_not_in_stock");?>
    <?php if ($templateData['ALL_CAN_BUY'] === false):?>
        <div class="bg-light text-red p-12 px-sm-24 rounded-sm mb-40">Несколько товаров в Вашей корзине на данный момент
            отсутствуют в наличии
        </div>
    <?php endif;?>
<?$this->__template->EndViewTarget();?>

<script>
    new Meven.Components.FavoritePage();
</script>