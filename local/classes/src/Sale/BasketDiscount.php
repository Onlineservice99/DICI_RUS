<?php
namespace Meven\Sale;

use \Bitrix\Catalog;
use \Bitrix\Sale;
use \Bitrix\Main\Loader;
use \Bitrix\Sale\Discount\Context;

class BasketDiscount
{
    /**
     * Получает стоимость со скидкой по id товара
     * @param int $productId
     * @return array
     */
    public static function getDiscountPriceByProduct(int $productId): array
    {
        $calcResults = [];

        if (Loader::IncludeModule('sale') && Loader::includeModule('catalog')) {

            $cacheId = 'product_discount_' . $productId;
            $cache = \Bitrix\Main\Application::getInstance()->getManagedCache();

            if ($cache->read(36000, $cacheId)) {
                $calcResults = $cache->get($cacheId);
            } else {

                $product = [
                    'ID' => $productId,
                    'MODULE' => 'catalog',
                ];

                $registry = Sale\Registry::getInstance(Sale\Registry::REGISTRY_TYPE_ORDER);
                $basketClass = $registry->getBasketClassName();
                $basket = $basketClass::create(SITE_ID);
                $basketItem = $basket->createItem($product['MODULE'], $product['ID']);

                $priceRow = Catalog\Discount\DiscountManager::getPriceDataByProductId($product['ID'], 1);

                $fields = array(
                    'PRODUCT_ID' => $product['ID'],
                    'QUANTITY' => 1,
                    'LID' => SITE_ID,
                    'PRODUCT_PRICE_ID' => $priceRow['ID'],
                    'PRICE' => $priceRow['PRICE'],
                    'BASE_PRICE' => $priceRow['PRICE'],
                    'DISCOUNT_PRICE' => 0,
                    'CURRENCY' => $priceRow['CURRENCY'],
                    'CAN_BUY' => 'Y',
                    'DELAY' => 'N',
                    'PRICE_TYPE_ID' => (int)$priceRow['CATALOG_GROUP_ID']
                );

                $basketItem->setFieldsNoDemand($fields);
                $discount = Sale\Discount::buildFromBasket($basket, new Context\UserGroup([2]));
                $discount->setExecuteModuleFilter(array('all', 'catalog'));
                $discount->calculate();

                $calcResults = $discount->getApplyResult(true);

                if (isset($calcResults['PRICES']['BASKET']) && !empty($calcResults['PRICES']['BASKET'])) {
                    $calcResults = current($calcResults['PRICES']['BASKET']);
                    $calcResults['PRINT_PRICE'] = self::formatPrice($calcResults['PRICE']);
                    $calcResults['PRINT_BASE_PRICE'] = self::formatPrice($calcResults['BASE_PRICE']);
                } else {
                    $calcResults = [];
                }

                $cache->set($cacheId, $calcResults);
            }
        }

        return $calcResults;
    }


    public static function formatPrice($value, $unit = 'руб.')
    {
        if ($value > 0) {
            $value = number_format($value, 2, '.', ' ');
            $value = str_replace(',00', '', $value);

            if (!empty($unit)) {
                $value .= ' <small>' . $unit . '</small>';
            }
        }

        return $value;
    }

}