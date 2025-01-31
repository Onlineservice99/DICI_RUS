<?php
namespace Meven\Exchange;

use \Russvet\Api\Services;

class Items
{
    public function __construct()
    {
        $this->iblockId = \Bitrix\Main\Config\Option::get("meven.info", "iblock_catalog");
        $this->stockId = \Bitrix\Main\Config\Option::get("meven.info", "russvet_stock");
		$this->propertiesService = new Properties();		
		$this->productService = new Product();
		$this->logger = new Logger();
    }

    public function getCountPage(): int
    {
        $service = new Services\Position();
        $service->setCategory("instock");
        $service->setIdStock($this->stockId);
        $service->setPage(1);
        $positions = $service->send();
        
        $positions = json_decode($positions, true);

        return $positions['meta']['last_page'];
    }

    public function getItems($page = 1): array
    {
        $service = new Services\Position();
        $service->setCategory("instock");
        $service->setIdStock($this->stockId);
        $service->setPage($page);
        $positions = $service->send();
        
        return json_decode($positions, true);
    }

    public function updateElements(array $elems)
    {
        \Bitrix\Main\Loader::includeModule('iblock');

        // Фильтр для поиска разделов, где пользовательское поле "Участвует в интеграции" установлено в "Да"
        $sectionsFilter = [
            'IBLOCK_ID' => $this->iblockId,
            'UF_MEVEN_EXCHANGE' => false, // категория участвует в обмене
        ];

        $sectionsList = \CIBlockSection::GetList([], $sectionsFilter, false, ['ID']);

        $sectionsIds = [];
        while ($section = $sectionsList->GetNext()) {
            $sectionsIds[] = $section['ID'];
        }

        // AddMessage2Log('Категории, не участвующие в обмене: '.json_encode($sectionsIds, JSON_UNESCAPED_UNICODE));


        $getCodes = [];

        foreach ($elems as $key => $e) {
            $getCodes[] = $e['CODE'];
        }
        
        $currentElements = [];

        if (!empty($sectionsIds)) {
            $elementsFilter = [
                'IBLOCK_ID' => $this->iblockId,
                'SECTION_ID' => $sectionsIds, // Фильтр по ID разделов
                'INCLUDE_SUBSECTIONS' => 'Y', // Включить поиск по подразделам
                'PROPERTY_ARTICLE' => $getCodes,
            ];
        
            $res = \CIBlockElement::GetList(
                [],
                $elementsFilter,
                false,
                false,
                ['ID', 'PROPERTY_ARTICLE', 'PROPERTY_BRAND']
            );
            while ($arFields = $res->GetNext()) {
                $currentElements[$arFields['ID']] = $arFields['PROPERTY_ARTICLE_VALUE'];
            }

            $diffElems = array_diff($getCodes, $currentElements);
            foreach ($diffElems as $key => $e) {
                try {
                    $id = $this->createElement($elems[$key]);
                    $currentElements[$id] = $elems[$key]['CODE'];
                } catch (Exception $e) {
                    $this->logger->error('Create error: ' . $e->getMessage(), 'items');
                }
                
            }
        }
    }

    public function createElement(array $element): ?int
    {		
		$resultProps = $this->propertiesService->getPropFromCode($element['CODE']);	
        // AddMessage2Log("resultProps: ".json_encode($resultProps, JSON_UNESCAPED_UNICODE));
		$sectionId = $this->propertiesService->getSection(
			$resultProps['INFO'][0]['ETIM_CLASS_NAME'],
			$resultProps['INFO'][0]['ETIM_CLASS'],
			$resultProps['INFO'][0]['RS_CATALOG'][0]
		);		
		$resultProps = $this->propertiesService->getPropElement($resultProps);
		$resultProps['PROPS']['ARTICLE'] = $element['CODE'];
		$resultProps['PROPS']['BRAND'] = $this->propertiesService->getBrand($element['BRAND']);
		
		$rs = \CIBlockElement::GetList(
			[],
			[
				'IBLOCK_ID' => $this->iblockId,
				'NAME' => $element['NAME'],
				'PROPERTY_ARTICLE' => ['', false],
			],
			false,
			false,
			['ID']
		);
		if ($existElem = $rs->Fetch()) {
			//\CIBlockElement::SetPropertyValuesEx($existElem['ID'], $this->iblockId, $resultProps);
			$this->logger->error($element['CODE'] . ' '. $element['NAME'] . ': Товар с таким названием уже есть, но пустой код товара.', 'items');
		}
		
		$el = new \CIBlockElement;
        $aElemFields = [
			'IBLOCK_ID' => $this->iblockId,
			'IBLOCK_SECTION_ID' => $sectionId,
			"ACTIVE" => "Y",
			'NAME' => $element['NAME'],
			'CODE' => \Cutil::translit($element['CODE'] . ' ' .$element['NAME'], "ru", ["replace_space" => "-", "replace_other" => "-"]),
			'DETAIL_TEXT' => $resultProps['FIELDS']['DETAIL_TEXT'],
			'DETAIL_TEXT_TYPE' => $resultProps['FIELDS']['DETAIL_TEXT_TYPE'],
			'PREVIEW_PICTURE' => $resultProps['PICTURE'],
			'DETAIL_PICTURE' => $resultProps['PICTURE'],
			'PROPERTY_VALUES' => $resultProps['PROPS'],
        ];
        $id = $el->Add($aElemFields);
		
		if ($id) {
			if ($resultProps['SIZES']) {
				$prod = new \CCatalogProduct();
				$prod->Update(
					$id,
					[
						'WEIGHT' => $resultProps['SIZES']['WEIGHT'],
						'WIDTH' => $resultProps['SIZES']['WIDTH'],
						'LENGTH' => $resultProps['SIZES']['LENGTH'],
						'HEIGHT' => $resultProps['SIZES']['HEIGHT'],
						'MEASURE' => $resultProps['SIZES']['MEASURE'],
					]
				);
			}
			$this->productService->updatePriceQuantity($element['CODE'], $id);
		} else if ($el->LAST_ERROR) {
			$this->logger->error($element['CODE'] . ' '. $element['NAME'] . ': ' . $el->LAST_ERROR, 'items');
        }

        return $id;
    }
}
