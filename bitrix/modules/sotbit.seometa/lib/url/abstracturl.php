<?php
namespace Sotbit\Seometa\Url;

use Sotbit\Seometa\Generator\AbstractGenerator;
use Sotbit\Seometa\Property\PropertySet;
use Sotbit\Seometa\Section\Section;
use Sotbit\Seometa\Section\SectionCollection;

abstract class AbstractUrl {
    protected $mask = false;
    protected $template = false;
    protected $templateWithSection = false;
    protected $propertyTemplate = '';
    protected $delimiter = '-or-';
    protected $propertyFields = [];
    protected $spaceReplacement = '-';

    public function hasSectionPlaceholders() {
        return preg_match('/\#(ID|SECTION_ID|CODE|SECTION_CODE|SECTION_CODE_PATH|EXTERNAL_ID)\#/', $this->mask);
    }

    public function setSectionPlaceholders(Section $section) {
        if(!($section instanceof Section))
            return;

        preg_match_all('/\#(ID|SECTION_ID|CODE|SECTION_CODE|SECTION_CODE_PATH|EXTERNAL_ID)\#/', $this->mask, $match);
        if(isset($match[0]) && !empty($match[0])) {
            $this->replaceSectionHolders($match[0], $section);
        }
    }

    private function replaceSectionHolders(array $keys, Section $section) {
        $result = [];
        $keys[] = '#ID#';
        $keys[] = '#SECTION_CODE#';

        foreach ($keys as $key) {
            $clearKey = preg_replace('/#(SECTION_)?([a-z_]+)#/i', '$2', $key);
            $result[$key] = $section->$clearKey;
        }

        if(in_array('#SECTION_CODE_PATH#', $keys)) {
            $result['#SECTION_CODE_PATH#'] = $section->getSectionPath();
        }

        if(in_array('#EXTERNAL_ID#', $keys)) {
            $result['#EXTERNAL_ID#'] = $section->XML_ID;
        }

        $this->replaceHolders($result);
    }

    public function isEmpty() {
        return empty($this->mask);
    }

    public function getMask() {
        return $this->mask;
    }

    public function setSpaceReplacement(
        $spaceReplacement
    ) {
        $this->spaceReplacement = $spaceReplacement;
    }

    public function getSpaceReplacement()
    {
        return $this->spaceReplacement;
    }

    public function cleanTemplate($full = false) {
        if($full)
            $this->mask = $this->template;
        else
            $this->mask = $this->templateWithSection;
    }

    public function replaceHolders($arHolderValues = array()) {
        $arHolderValues = $this->prepareFields($arHolderValues);
        $this->mask = str_replace(array_keys($arHolderValues), $arHolderValues, $this->mask);
    }

    protected function prepareFields($arFields) {
        if(is_array($arFields))
            foreach ($arFields as &$arField)
                if(is_array($arField)) {
                    $arField = implode($this->delimiter, $arField);
                }

        return is_array($arFields) ? $arFields : array();
    }

    public function setSectionPlaceholdersIfNeed($sectionId) {
        if($this->hasSectionPlaceholders()) {
            $this->setSectionPlaceholders(SectionCollection::getInstance()->getSectionById($sectionId));
            $this->templateWithSection = $this->mask;
        }
    }

    public function replaceFromSet(PropertySet $propertySet, AbstractGenerator $generator) {
        $filteredProps = [];

        foreach ($propertySet->getData() as $propertySetEntity) {
            if($propertySetEntity->isCompressed())
                continue;

            $typeEntity = $propertySetEntity->getEntityType();

            $filteredProps[$typeEntity][] = $propertySetEntity;
        }

        $this->replacePropertiesFromSet($filteredProps, $generator);
        $this->replacePriceFromSet($filteredProps, $generator);
        $this->replaceFilterFromSet($filteredProps, $generator);

        $this->mask = str_replace('//', '/', $this->mask);
    }

    abstract protected function replacePropertiesFromSet(array &$filteredProps, AbstractGenerator $generator);

    abstract protected function replacePriceFromSet(array &$filteredProps, AbstractGenerator $generator);

    abstract protected function replaceFilterFromSet(array &$filteredProps, AbstractGenerator $generator);

    public function hasPropertyFields() {
        return !empty($this->propertyFields);
    }

    public function getPropertyFields() {
        return $this->propertyFields;
    }

    public function getPropertyTemplate() {
        return $this->propertyTemplate;
    }

    public function resetMask() {
        $this->mask = $this->template;
    }
}
?>