<?php
namespace Sotbit\Seometa\Helper;

use Sotbit\Seometa\Condition\Rule;
use Sotbit\Seometa\Property\PropertySet;
use Sotbit\Seometa\Property\PropertySetCollection;
use Sotbit\Seometa\Property\PropertySetEntity;
use Sotbit\Seometa\Property\PropertySetIterator;
use Sotbit\Seometa\Section\SectionCollection;
use Sotbit\Seometa\Url\CatalogUrl;
use Sotbit\Seometa\Url\ChpuUrl;

class Linker {
    private static $instance = false;
    private $rule = false;
    private $propertySetIterator = false;

    private function __construct() {
        $this->rule = new Rule();
        $this->propertySetIterator = new PropertySetIterator();
    }

    public static function getInstance()
    {
        if(self::$instance == false)
            self::$instance = new Linker();

        return self::$instance;
    }

    public function getSectionList($id = false){
        if($id === false)
            return false;

        $arCondition = \Sotbit\Seometa\ConditionTable::getById($id)->fetch();

        $ConditionSections = unserialize($arCondition['SECTIONS']);

        if(!is_array($ConditionSections) || count($ConditionSections) < 1) // If dont check sections
        {
            $ConditionSections = array();
            $rsSections = \CIBlockSection::GetList(
                array(
                    'SORT' => 'ASC'
                ),
                array(
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $arCondition['INFOBLOCK']
                ),
                false,
                array(
                    'ID'
                )
            );
            while($arSection = $rsSections->GetNext())
            {
                $ConditionSections[] = $arSection['ID'];
            }
        }

        return $ConditionSections;
    }

    public function getConditionList($site_id) {
        $result['sections'] = $result['conditions'] =[];

        $res = \Sotbit\Seometa\ConditionTable::getList([
            'filter' => [
                'ACTIVE' => 'Y',
                '!=NO_INDEX' => 'Y'
            ],
            'select' => [
                'ID',
                'SITES',
                'SECTIONS'
            ]
        ]);

        while ($item = $res->fetch()) {
            if(in_array($site_id, unserialize($item['SITES']))) {
                $result['conditions'][] = $item['ID'];
                if(!array_intersect($result['sections'], unserialize($item['SECTIONS']))) {
                    $result['sections'] = array_merge($result['sections'], unserialize($item['SECTIONS']));
                }
            }
        }

        $result['sections'] = array_unique($result['sections']);

        return $result;
    }

    public function generate (\Sotbit\Seometa\Link\AbstractWriter $writer, $conditionId = false, $sectionList = array(), $countTagsPerCond = 0) {
//        $count = 0;
        if(!$conditionId) {
            return false;
        }
        $countGeneratedTags = 0;
        $result = [];

        $smartFilter = new \Sotbit\Seometa\Filter\SmartFilter($conditionId);

        if(unserialize($smartFilter->getCondition()->SITES)) {
            $siteID = unserialize($smartFilter->getCondition()->SITES)[0];
        }

        $generator = \Sotbit\Seometa\Generator\GeneratorFactory::create($smartFilter->getCondition()->FILTER_TYPE, $siteID);
        $chpuGenerator = \Sotbit\Seometa\Generator\GeneratorFactory::create('chpu', $siteID);

        $catalogUrl = new CatalogUrl($smartFilter->getCondition(), $siteID);
        $chpuUrl = new ChpuUrl($smartFilter->getCondition()->getMeta('TEMPLATE_NEW_URL'),
            $smartFilter->getCondition()->getMeta('SPACE_REPLACEMENT'));

        $propertyCollection = $smartFilter->getPropertyCollection();

        if($sectionList) {
            $conditionSections = $sectionList;
        } else {
            $conditionSections = $smartFilter->getCondition()->getSections();
        }

        $propertyManager = $smartFilter->getPropertyManager();
        $priceManager = $smartFilter->getPriceManager();
        $parsedRule = $this->rule->parse($smartFilter->getCondition());
        if(!$parsedRule || !$conditionSections) {
            return;
        }

//        $propertyManager->getSetedProperties($parsedRule);

        if($catalogUrl->hasSectionPlaceholders() || $chpuUrl->hasSectionPlaceholders()) {
            SectionCollection::getInstance()->setCollectionById($conditionSections);
        }

        foreach($conditionSections as $sectionId) {
            $chpuUrl->cleanTemplate(true);
            $catalogUrl->cleanTemplate(true);
            $propertyCollection->clearValue();
            if(is_array($propertyCollection->getData())) {
                $propertyManager->fillPropertyValues($propertyCollection,
                    $sectionId);
            }

            if($propertyCollection->haveEmpty()) {
                continue;
            }

            $sectionRule = $parsedRule->filter($propertyCollection);
            $urlName = SectionCollection::getInstance()->getSectionById($sectionId)->NAME.' ';
            $priceManager->fillPriceValues($sectionRule, $sectionId);

            foreach($sectionRule as $propertySet) {
                $this->propertySetIterator->setProperties($propertySet, $propertyCollection);

                $catalogUrl->setSectionPlaceholdersIfNeed($sectionId);
                $chpuUrl->setSectionPlaceholdersIfNeed($sectionId);
//                $this->propertySetIterator->rewind();
                while($this->propertySetIterator->getNext()) {
//$propertySet->show();
//$count++;
//continue;
                    if(!$propertySet->isValid()) {
                        continue;
                    }

                    $count = $propertySet->getCountProducts($sectionId);

                    if($count < 1)
                        continue;

                    $chpuUrl->replaceFromSet($propertySet, $chpuGenerator);
                    $catalogUrl->replaceFromSet($propertySet, $generator);
//                    $propertySet->show();
//                    continue;

                    $result['product_count'] = $count;
                    $result['real_url'] = $catalogUrl->getMask();
                    $result['name'] = $urlName . $propertySet->getPropertyNames();
                    $result['properties'] = $propertySet->getPropertyValues();
                    $result['new_url'] = mb_strtolower($chpuUrl->getMask());
                    $result['section_id'] = $sectionId;
                    $result['active'] = \Bitrix\Main\Config\Option::get("sotbit.seometa", "IS_SET_ACTIVE", 'N', $siteID);
                    $result['condition_id'] = $conditionId;
                    $result['iblock_id'] = $smartFilter->getCondition()->INFOBLOCK;
                    $result['strict_relinking'] = $smartFilter->getCondition()->STRICT_RELINKING;

                    $result['site_id'] = $arCondition['SITES'] = $smartFilter->getCondition()->SITES;

                    $arCondition['ID'] = $smartFilter->getCondition()->ID;
                    $arCondition['TAG'] = $smartFilter->getCondition()->TAG;
                    $arCondition['CHANGEFREQ'] = $smartFilter->getCondition()->CHANGEFREQ;
                    $arCondition['PRIORITY'] = $smartFilter->getCondition()->PRIORITY;
                    $arCondition['META'] = $smartFilter->getCondition()->META;
                    $arCondition['DATE_CHANGE'] = $smartFilter->getCondition()->DATE_CHANGE;
                    $arCondition['TYPE_OF_INFOBLOCK'] = $smartFilter->getCondition()->TYPE_OF_INFOBLOCK;
                    $arCondition['INFOBLOCK'] = $smartFilter->getCondition()->INFOBLOCK;

                    $writer->SetCondition($arCondition);
//                    echo '<pre>';
//                    print_r($result);

                    $chpuUrl->cleanTemplate();
                    $catalogUrl->cleanTemplate();

//                    $writer->Write($result);

                    if(
                        $writer->Write($result) ||
                        !empty($countTagsPerCond) &&
                        ($countTagsPerCond - 1 <= $countGeneratedTags)
                    ) {
                        return;
                    } else {
                        $countGeneratedTags++;
                    }
                }
//                echo 'count -- ' . $count . PHP_EOL;
            }

            $sectionRule->remove();
        }
    }
}
