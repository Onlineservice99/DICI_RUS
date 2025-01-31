<?php
namespace Meven\Exchange;

use \Russvet\Api\Services;
use \Bitrix\Catalog\Model\Price;

class Product
{
    public function __construct()
    {
		\Bitrix\Main\Loader::includeModule('iblock');
		
        $this->iblockId = \Bitrix\Main\Config\Option::get("meven.info","iblock_catalog");
		$this->stockId = \Bitrix\Main\Config\Option::get("meven.info", "russvet_stock");        
        $this->markup = (\Bitrix\Main\Config\Option::get("meven.info","setting_price_plus_exchange", 0)/100) + 1;
        $this->logger = new Logger();
    }

    public function getAllElements()
    {
        $res = \CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => $this->iblockId
            ],
            false,
            false,
            ['ID', 'PROPERTY_ARTICLE']
        );
        while ($arFields = $res->GetNext()) {
            if (!$arFields['PROPERTY_ARTICLE_VALUE']) {
                continue;
            }

            $currentElements[] = [
                "CODE" => $arFields['PROPERTY_ARTICLE_VALUE'],
                "ID" => $arFields['ID']
            ];
        }

        return $currentElements;
    }

	/**
     *
     */
    public function getPageElements($page = 1): array
    {
        $currentElements = [];
        $res = \CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => $this->iblockId,
                '!PROPERTY_VYGRUZHENO_IZ_1S_VALUE' => 'Да'
            ],
            false,
            ['nPageSize' => 100, 'iNumPage' => $page, 'checkOutOfRange' => true],
            ['ID', 'PROPERTY_ARTICLE', 'PROPERTY_VYGRUZHENO_IZ_1S']
        );
        while ($arFields = $res->GetNext()) {
            if (!$arFields['PROPERTY_ARTICLE_VALUE']) {
                continue;
            }

            $currentElements[] = [
                "CODE" => $arFields['PROPERTY_ARTICLE_VALUE'],
                "ID" => $arFields['ID']
            ];
        }

        return $currentElements;
    }

    public function getInfo(string $code)
    {
        $quantity = $this->getQuantity($code);
        $price = $this->getPrice($code);
    }

    public function updatePriceQuantity(string $code, int $id)
    {
        $quantity = $this->getQuantity($code);
        $price = $this->getPrice($code);

        if ($price === null) {
            return false;
        }

        $price = $price['Price']['Personal_w_VAT']*$this->markup;
        $quantity = $quantity ?? 0;

        $existProduct = \Bitrix\Catalog\Model\Product::getCacheItem($id,true);

        if (!empty($existProduct)) {
            // Количество товара
            $res = \Bitrix\Catalog\Model\Product::update(
                $id,
                [
                    "QUANTITY" => $quantity['Residue'],
                ]
            );

            if (!$res->isSuccess()) {
                $this->logger->error('Product "'. $code . '" update error: ' . print_r($res->getErrorMessages(), true), 'stock');
            }
        } else {
            // Количество товара
            $res = \Bitrix\Catalog\Model\Product::add(
                [
                    "ID" => $id, // ID добавленного элемента инфоблока
                    "VAT_ID" => 1, // тип НДС (задается в админке)
                    "VAT_INCLUDED" => "Y", // НДС входит в стоимость
                    "TYPE " => \Bitrix\Catalog\ProductTable::TYPE_PRODUCT,
                    "QUANTITY" => $quantity['Residue']
                ]
            );

            if (!$res->isSuccess()) {
				$this->logger->error('Product "'. $code . '" add error: ' . print_r($res->getErrorMessages(), true), 'stock');
            }
        }
        //обновление на складе Руссвета
        \Bitrix\Main\Loader::includeModule('catalog');
        $arLoadStoreArray = array(                    
            "PRODUCT_ID" => $id,
            "AMOUNT" => $quantity['Residue'],
            "STORE_ID" => !empty(\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet")) ? (int)\Bitrix\Main\Config\Option::get("meven.info", "id_store_russvet") : 1,
        );
        \CCatalogStoreProduct::UpdateFromForm($arLoadStoreArray);

        $price = str_replace(',', '.', $price);
        // Элемент инфоблока превращен в товар
        $arFieldsPrice = array(
            "PRODUCT_ID" => $id, // ID добавленного товара
            "CATALOG_GROUP_ID" => 1, // ID типа цены
            "PRICE" => (float) $price, // Значение цены
            "CURRENCY" => "RUB", // Валюта
        );

        // Проверка установлена ли цена для данного товара
        $curPrice = Price::getList([
           "filter" => array(
               "PRODUCT_ID" => $id,
               "CATALOG_GROUP_ID" => 1
           )
       ])->fetch();

        if ($curPrice) {
            // Если цены нет, то добавляем
            $result = Price::update($curPrice['ID'], $arFieldsPrice);
        } else {
            // Если цены нет, то добавляем
            $result = Price::add($arFieldsPrice);
        }

        $el = new \CIBlockElement;
        $el->Update($id, [
            'ACTIVE' => $arFieldsPrice['PRICE'] > 0 ? 'Y' : 'N'
        ]);

        \CIBlockElement::SetPropertyValuesEx($id, $this->iblockId, [
            'IN_STOCK' => $quantity['Residue'] > 0 ? 1153 : 1154
        ]);
    }

    public function getPrice($code)
    {
        $service = new Services\Price();
        $service->setCodePosition($code);

        return json_decode($service->send(), true);
    }

    public function getQuantity($code)
    {
        $service = new Services\Residue();
        $service->setIdStock($this->stockId);
        $service->setCodePosition($code);

        return json_decode($service->send(), true);
    }
}
