<?php
namespace Sotbit\Seometa\Url;
use Sotbit\Seometa\ConditionTable;
use Sotbit\Seometa\Generator\AbstractGenerator;
use Sotbit\Seometa\Property\PropertySet;
use Sotbit\Seometa\SeoMeta;
use Bitrix\Main\Loader;

class ChpuUrl extends AbstractUrl
{
    protected $propertyTemplate;

    public function __construct($template = true, $spaceReplacement = false) {
        preg_match('/{([^:]+)(:.*)+?}/i', $template, $match);
        $this->propertyTemplate = $match[1];

        preg_match_all('/#PROPERTY_([a-z_]+)#/i', $template,$match);
        $this->propertyFields = array_combine($match[0], $match[1]);

        if(is_string($template))
            $this->mask = $this->templateWithSection = $this->template = preg_replace('/{.+}/', '#PROPERTIES#', $template);

        if($spaceReplacement && !empty($spaceReplacement)) {
            parent::setSpaceReplacement($spaceReplacement);
        }
    }

    public function setDelimiter($str) {
        if(is_string($str))
            $this->delimiter = $str;
    }

    public function getLinkGlue(\Sotbit\Seometa\Generater\Common $Generator) {
        if(mb_strpos('\Sotbit\Seometa\Generater\ComboxGenerator', get_class($Generator)) !== false)
            return '&';

        return '';
    }

    protected function replacePropertiesFromSet(array &$filteredProps, AbstractGenerator $generator) {
        if((!isset($filteredProps['PROPERTY']) || empty($filteredProps['PROPERTY'])) || !preg_match_all('/#PROPERTY_[a-z]+#/i', $this->propertyTemplate)) {
            $this->mask = str_replace('#PROPERTIES#', '', $this->mask);
            return;
        }

        $result = [];

        foreach($filteredProps['PROPERTY'] as $propertySetEntity) {
            $result[] = $generator->generate($this, $propertySetEntity);
        }

        $this->mask = str_replace('#PROPERTIES#', implode('/', $result), $this->mask);
    }

    protected function replacePriceFromSet(array &$filteredProps, AbstractGenerator $generator) {
        if(mb_strpos($this->mask, '#PRICES#') === false)
            return;

        if(!isset($filteredProps['PRICE']) || empty($filteredProps['PRICE'])) {
            $this->mask = str_replace('/#PRICES#', '', $this->mask);
            return;
        }

        $result = [];

        foreach($filteredProps['PRICE'] as $propertySetEntity) {
            $result[] = $generator->generate($this, $propertySetEntity);
        }

        $this->mask = str_replace('#PRICES#', implode('/', $result), $this->mask);
    }

    protected function replaceFilterFromSet(array &$filteredProps, AbstractGenerator $generator) {
        if(mb_strpos($this->mask, '#FILTER#') === false)
            return;

        if(!isset($filteredProps['FILTER']) || empty($filteredProps['FILTER'])) {
            $this->mask = str_replace('/#FILTER#', '', $this->mask);
            return;
        }

        $result = [];

        foreach($filteredProps['FILTER'] as $propertySetEntity) {
            $result[] = $generator->generate($this, $propertySetEntity);
        }

        $this->mask = str_replace('#FILTER#', implode('/', $result), $this->mask);
    }
}
?>