<?php

namespace Meven\Exchange;

use mysql_xdevapi\Exception;
use \Russvet\Api\Services;

/**
 * Class PropertiesNew
 * @package Meven\Exchange
 */
class Properties
{
    /**
     *
     */
    const FIELDS = [
        'TIP_KRYSHKI',
        'S_SOEDINITELEM',
        'ZASHCHITNAYA_PLENKA',
        'S_KABELNYM_ZAZHIMOM',
        'ISPOLNENIE_KRYSHKI',
        'PROZRACHNYY',
        'SOOTVETSTVUET_FUNKTS',
        'TSVET',
        'VID_MATERIALA',
        'POLEZNOE_POPERECHNOE',
        'TIP_MONTAZHA',
        'KOLICHESTVO_VSTRAIVA',
        'KOLICHESTVO_POSTOYAN',
        'DLINA',
        'SHIRINA',
        'VYSOTA'
    ];
    
    private $measureData = [];
    
    /**
     * Properties constructor.
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        \Bitrix\Main\Loader::includeModule('catalog');
        
		$this->logger = new Logger();
        $this->iblockId = \Bitrix\Main\Config\Option::get("meven.info", "iblock_catalog");
        $this->checkHash = \Bitrix\Main\Config\Option::get("meven.info", "russvet_check_hash");
        $this->brands = $this->getBrands();
        $res = \Bitrix\Catalog\MeasureTable::getList([
            'select' => ['ID', 'MEASURE_TITLE'],
        ])->fetchAll();
        foreach ($res as $measure) {
            $this->measureData[$measure['MEASURE_TITLE']] = $measure['ID'];
        }
    }
    
    public function getAllElements(): array
    {
        $currentElements = [];
        $dbList = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => $this->iblockId],
            false,
            false,
            ['ID', 'NAME', "IBLOCK_SECTION_ID", "PROPERTY_ARTICLE", "PROPERTY_HASH", "PROPERTY_API"]
        );
        
        while ($arElem = $dbList->GetNext()) {
            if (!$arElem['PROPERTY_ARTICLE_VALUE']) {
                continue;
            }
            
            $currentElements[] = [
                "IBLOCK_SECTION_ID" => $arElem['IBLOCK_SECTION_ID'],
                "CODE" => $arElem['PROPERTY_ARTICLE_VALUE'],
                "HASH" => $arElem['PROPERTY_HASH_VALUE'],
                "API" => $arElem['PROPERTY_API_VALUE'],
                "ID" => $arElem['ID']
            ];
        }
        
        return $currentElements;
    }
    
    /**
     *
     */
    public function getPageElements($page = 1): array
    {
        $elems = [];
        $dbList = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => $this->iblockId,
            ],
            false,
            ['nPageSize' => 100, 'iNumPage' => $page, 'checkOutOfRange' => true],
            ['ID', 'NAME', "IBLOCK_SECTION_ID", "PROPERTY_ARTICLE", "PROPERTY_HASH", "PROPERTY_API"]
        );
        
        while ($arElem = $dbList->GetNext()) {
            $elems[$arElem['PROPERTY_ARTICLE_VALUE']] = [
                "IBLOCK_SECTION_ID" => $arElem['IBLOCK_SECTION_ID'],
                "CODE" => $arElem['PROPERTY_ARTICLE_VALUE'],
                "HASH" => $arElem['PROPERTY_HASH_VALUE'],
                "API" => $arElem['PROPERTY_API_VALUE'],
                "ID" => $arElem['ID']
            ];
        }
        
        return $elems;
    }
    
    /**
     * @param string $code
     * @return array|null
     */
    public function getPropFromCode(string $code): ?array
    {
        $residue = new Services\Specs();
        $residue->setCodePosition($code);
        $responseResidue = json_decode($residue->send(), true);
        
        if (empty($responseResidue)) {
            return null;
        }
        
        return $responseResidue;
    }
    
    public function getSection($sectionName, $sectionCode, $sectionData = [])
    {
        $sections = \Bitrix\Iblock\SectionTable::getList([
            'filter' => [
                '=IBLOCK_ID' => \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog')
            ],
            'select' => ['ID', 'XML_ID', 'NAME', 'DEPTH_LEVEL'],
        ])->fetchAll();
        
        $maxDepth = 1 + count($sectionData) / 2;
        $code = \CUtil::translit($sectionName, 'ru');
        $sec = new \CIBlockSection;
        
        foreach ($sections as $section) {
            if (/*$section['DEPTH_LEVEL'] == $maxDepth && */$section['XML_ID'] == $sectionCode) {
                $sectionId = $section['ID'];
                break;
            }
        }
        
        if (!$sectionId) {
            if ($sectionData) {
                $xml = array_shift($sectionData);
                $name = array_shift($sectionData);
                $parentId = $this->getSection($name, $xml, $sectionData);
            } else {
                $parentId = false;
            }
            
            $sectionId = $sec->Add([
                'IBLOCK_ID' => \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog'),
                'NAME' => $sectionName,
                'CODE' => $code,
                'XML_ID' => $sectionCode,
                'IBLOCK_SECTION_ID' => $parentId,
                'SORT' => 400
            ]);
            
            if (!$sectionId) {
                $sectionId = $sec->Add([
                    'IBLOCK_ID' => \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog'),
                    'NAME' => $sectionName,
                    'CODE' => $code . '_' . $sectionCode,
                    'XML_ID' => $sectionCode,
                    'IBLOCK_SECTION_ID' => $parentId,
                    'SORT' => 400
                ]);
            }
            
            if (!$sectionId) {
                $this->logger->error('Create section ' . $sectionName .'(' . $sectionCode . ') error: ' . $sec->LAST_ERROR . print_r($sectionData, true), 'props');
            }
        }
        
        return $sectionId;
    }
    
    /**
     * @param $elems
     */
    public function updateProps($elem): void
    {
        try {
            if ($elem['CODE']) {
                $resultProps = $this->getPropFromCode($elem['CODE']);
                $_hash = self::createHash($resultProps);

                if ($this->checkHash && $elem['HASH'] == $_hash) {
                    return;
                }
        
                /*
                $sectionId = $this->getSection(
                    $resultProps['INFO'][0]['ETIM_CLASS_NAME'],
                    $resultProps['INFO'][0]['ETIM_CLASS'],
                    $resultProps['INFO'][0]['RS_CATALOG'][0]
                );
                */
                if ($resultProps) {
                    $resultProps = $this->getPropElement($resultProps);
                    if ($resultProps === null) {
                        return;
                    }
                
                    \CIBlockElement::SetPropertyValuesEx($elem['ID'], $this->iblockId, $resultProps['PROPS']);
                    
                    $el = new \CIBlockElement();
                    $el->Update(
                        $elem['ID'],
                        [
                            //'IBLOCK_SECTION_ID' => $sectionId,
                            'DETAIL_TEXT' => $resultProps['FIELDS']['DETAIL_TEXT'],
                            'DETAIL_TEXT_TYPE' => $resultProps['FIELDS']['DETAIL_TEXT_TYPE'],
                            'PREVIEW_PICTURE' => $resultProps['PICTURE'],
                            'DETAIL_PICTURE' => $resultProps['PICTURE'],
                        ]
                    );

                    if ($resultProps['SIZES']) {
                        $prod = new \CCatalogProduct();
                        $prod->Update(
                            $elem['ID'],
                            [
                                'WEIGHT' => $resultProps['SIZES']['WEIGHT'],
                                'WIDTH' => $resultProps['SIZES']['WIDTH'],
                                'LENGTH' => $resultProps['SIZES']['LENGTH'],
                                'HEIGHT' => $resultProps['SIZES']['HEIGHT'],
                                'MEASURE' => $resultProps['SIZES']['MEASURE'],
                            ]
                        );
                    }
                }
            }
        } catch (Exception $e) {
			$this->logger->error('Update props for product '. $elem['CODE'] . ' error: ' . $e->getMessage(), 'props');
            return;
        }
    }
    
    /**
     * @param array $props
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getPropElement(array $props): array
    {
		$fields = [
            'DETAIL_TEXT' => $props['INFO'][0]['LONG_DESCRIPTION'],
            'DETAIL_TEXT_TYPE' => 'html',
        ];
		
        $properties = [
			'HASH' => self::createHash($props),
            'API'  => json_encode($props, JSON_UNESCAPED_UNICODE),
		];
		
        if ($props['IMG'][0]['URL']) {
            try {
                $file = \CFile::MakeFileArray($props['IMG'][0]['URL']);
            } catch (\Bitrix\Main\IO\FileOpenException $e) {
                
            }
            
            if ($file) {
                $picture = $file;
                unset($props['IMG'][0]);
            }
        }
		
        if (count($props['IMG']) > 0) {
            foreach ($props['IMG'] as $i) {
                if ($i) {
                    try {
                        $file = \CFile::MakeFileArray($i['URL']);
                    } catch (\Bitrix\Main\IO\FileOpenException $e) {
                        continue;
                    }
                    
                    if ($file) {
                        $properties['PICTURES'][] = $file;
                    }
                }
            }
        }
        
        $properties['VIDEO'] = false;
        if (count($props['VIDEO']) > 0) {
            foreach ($props['VIDEO'] as $i) {
                if ($i['URL']) {
                    try {
                        $file = \CFile::MakeFileArray($i['URL']);
                    } catch (\Bitrix\Main\IO\FileOpenException $e) {
                        continue;
                    }
                    
                    if ($file) {
                        $properties['VIDEO'][] = $file;
                    }
                }
            }
        }
        
        $properties['CERTIFICATE'] = false;
        if (count($props['CERTIFICATE']) > 0) {
            foreach ($props['CERTIFICATE'] as $i) {
                if ($i['URL']) {
                    try {
                        $file = \CFile::MakeFileArray($i['URL']);
                    } catch (\Bitrix\Main\IO\FileOpenException $e) {
                        continue;
                    }
                    
                    if ($file) {
                        $properties['CERTIFICATE'][] = [
                            'VALUE' => $file,
                            'DESCRIPTION' => $i['CERT_NUM'],
                        ];
                    }
                }
            }
        }
        
        $properties['COUNTRY'] = $props['INFO'][0]['ORIGIN_COUNTRY'];
        $properties['WARRANTY'] = $props['INFO'][0]['WARRANTY'];
        $properties['BARCODE'] = $props['BARCODE'][0]['EAN'];
        
        $convertSizeValue = 1;
        if ($props['INFO'][0]['LOGISTIC_DETAILS'][0]['DIMENSION_UOM'] == 'м') {
            $convertSizeValue = 1000;
        } elseif ($props['INFO'][0]['LOGISTIC_DETAILS'][0]['DIMENSION_UOM'] == 'см') {
            $convertSizeValue = 100;
        }
        
        $convertWeightValue = 1;
        if ($props['INFO'][0]['LOGISTIC_DETAILS'][0]['WEIGHT_UOM'] == 'кг') {
            $convertWeightValue = 1000;
        }
        
        $measureId = $this->measureData[$props['INFO'][0]['PRIMARY_UOM']];
        if (!$measureId) {
            $res = \Bitrix\Catalog\MeasureTable::add([
                'MEASURE_TITLE' => $props['INFO'][0]['PRIMARY_UOM'],
                'CODE' => randString(6, ['0123456789']),
            ]);
            if ($res->isSuccess()) {
                $measureId = $res->getId();
                $this->measureData[$props['INFO'][0]['PRIMARY_UOM']] = $measureId;
            } else {
                $measureId = 5;
            }
        }
        
        $sizes = [
            'HEIGHT' => $props['INFO'][0]['LOGISTIC_DETAILS'][0]['HEIGH'] * $convertSizeValue,
            'WIDTH' => $props['INFO'][0]['LOGISTIC_DETAILS'][0]['WIDTH'] * $convertSizeValue,
            'LENGTH' => $props['INFO'][0]['LOGISTIC_DETAILS'][0]['LENGTH'] * $convertSizeValue,
            'WEIGHT' => $props['INFO'][0]['LOGISTIC_DETAILS'][0]['WEIGHT'] * $convertWeightValue,
            'MEASURE' => $measureId,
        ];
        
        return [
            'FIELDS' => $fields,
            'SIZES' => $sizes,
            'PROPS' => $properties,
            'PICTURE' => $picture,
        ];
    }
	
	public function getBrands()
    {
        $brands = [];
        $resBlock = \CIBlockElement::GetList(
            [],
            ['IBLOCK_ID' => \Bitrix\Main\Config\Option::get("meven.info","iblock_brands")],
            false,
            false,
            ['ID', 'NAME']
        );
        while ($arFieldsBlock = $resBlock->GetNext()) {
            $brands[$arFieldsBlock['NAME']] = $arFieldsBlock['ID'];
        }

        return $brands;
    }

    public function createBrand($name)
    {
        $el = new \CIBlockElement;
        $id = $el->Add([
             'IBLOCK_ID' => \Bitrix\Main\Config\Option::get("meven.info","iblock_brands"),
             'NAME' => $name,
             'CODE' => \Cutil::translit($name, "ru", ["replace_space" => "-", "replace_other" => "-"]),
        ]);
		
		if ($id) {
			$this->brands[$name] = $id;
		}
		
		return $id;
    }
	
	public function getBrand($name)
	{	
		if (isset($this->brands[$name])) {
			return $this->brands[$name];
		}
		
		return $this->createBrand($name);
	}
	
	public static function createHash($value)
	{
		return md5(json_encode($value));
	}
}