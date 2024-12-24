<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Grid\Declension;
/**
 * @global array $arParams
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global string $cartId
 */
$compositeStub = (isset($arResult['COMPOSITE_STUB']) && $arResult['COMPOSITE_STUB'] == 'Y');

$wordItems = new Declension('товар', 'товара', 'товаров');
$wordItemsDecl = $wordItems->get($arResult['NUM_PRODUCTS']);
?>
<a
        class="icon-link icon-link--basket"
        href="/personal/cart/"
        data-toggle="popover"
        data-placement="bottom"
        data-content="В корзине &lt;b class='text-red text-nowrap'&gt;<?=$arResult['NUM_PRODUCTS']?>&lt;/b&gt; <?=$wordItemsDecl?> на
        сумму &lt;b
        class='text-red text-nowrap'&gt;<?=$arResult['TOTAL_PRICE']?>&lt;/b&gt;&amp;nbsp;"
        data-trigger="hover"
>
    <svg class="icon icon-shopping-cart">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/assets/icons/symbol-defs.svg#icon-shopping-cart"></use>
    </svg>
    <?php if ($arResult['NUM_PRODUCTS'] > 0):?>
        <span class="icon-link__count"><?=$arResult['NUM_PRODUCTS']?></span>
    <?php endif?>
</a>
