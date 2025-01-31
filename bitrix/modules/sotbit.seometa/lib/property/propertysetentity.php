<?
namespace Sotbit\Seometa\Property;

use Sotbit\Seometa\Price\PriceManager;

class PropertySetEntity {
    private $data = [];
    private $meta = [];
    private $property = false;
    private $price = false;
    private $isCompressed = false;

    public function __construct(array &$dataEntity)  {
        $this->data = $dataEntity;

        if(mb_stripos($dataEntity['CLASS_ID'], 'CondIBProp') !== false || mb_stripos($dataEntity['CLASS_ID'], 'property') !== false)
            $this->constructProperty($dataEntity);
        else if(mb_stripos($dataEntity['CLASS_ID'], 'price') !== false)
            $this->constructPrice($dataEntity);
        else {
            throw new \Exception("Undefined type property for making [".print_r($dataEntity, true)."]");
        }
    }

    private function constructProperty(array &$dataEntity) {
        $this->meta = explode(':', $dataEntity['CLASS_ID']);
        if(!empty($dataEntity['DATA']['value'])) {
            $this->meta[3] = $dataEntity['DATA']['value'];
        }
        $this->property = PropertyCollection::getInstance()->getProperty($this->meta[2]);
    }

    private function constructPrice(array &$dataEntity) {
        $meta = str_replace('CondIB', '', $dataEntity['CLASS_ID']);
        $this->meta = explode('Price', $meta);
        $this->price = PriceManager::getInstance()->getPriceByCode($this->meta[1]);
    }

    public function getProperty() {
        return $this->property;
    }

    public function getPrice() {
        return $this->price;
    }

    public function compare(PropertySetEntity $propertySetEntity) {
        if($this->property->SORT == $propertySetEntity->property->SORT) {
            if($this->property->ID == $propertySetEntity->property->ID)
                return 0;

            if($this->property->ID > $propertySetEntity->property->ID)
                return -1;
            else
                return 1;
        }

        if($this->property->SORT > $propertySetEntity->property->SORT)
            return -1;
        else
            return 1;
    }

    public function __get($name) {
        return (isset($this->data[$name])) ? $this->data[$name] : null;
    }

    public function getMetaValue() {
        return (isset($this->meta[3]) && !empty($this->meta[3])) ? $this->meta[3] : null;
    }

    public function setValue($value) {
        $this->data['DATA']['value'] = $value;
    }

    public function isProperty() {
        return strcmp($this->meta[0], 'CondIBProp') == 0 || mb_stripos($this->meta[0], 'property') !== false;
    }

    public function isPrice() {
        return mb_stripos($this->data['CLASS_ID'], 'price') !== false;
    }

    public function isEmptyValue() {
        if(is_array($this->data['DATA']['value'])) {
            return empty(array_filter($this->data['DATA']['value'], function ($v) {
                return !empty($v);
            }));
        } else if (!empty($this->data['DATA']['value'])) {
            return false;
        }

        return true;
    }

    public function getIblockId() {
        return isset($this->meta[1]) ? $this->meta[1] : false;
    }

    public function getPropertyId() {
        return isset($this->meta[2]) ? $this->meta[2] : false;
    }

    public function getMeta() {
        return $this->meta ? $this->meta : false;
    }

    public function remove() {
        unset($this->data, $this->meta);
    }

    public function show() {
        if($this->isPrice()) {
            echo $this->data['CLASS_ID'].' - '.$this->data['DATA']['value'].';';
        } else {
            $value = [];

            foreach($this->data['DATA']['value'] as $propertyValue) {
                $value[] = $propertyValue->VALUE;
            }

            echo $this->data['CLASS_ID'] . ' - ' . implode(' | ', $value) . ';';
        }
    }

    public function compareValue(PropertySetEntity $setEntity) {
        return $this->data == $setEntity->data;
    }

    public function exchangeValue(PropertyCollection $propertyCollection) {
        if($this->isProperty())
            $this->exchangePropertyValue($propertyCollection);
        else if ($this->isPrice())
            $this->exchangePriceValue($propertyCollection);
        else
            throw new \Exception('Unknown type property set entity');
    }

    private function exchangePriceValue(PropertyCollection $propertyCollection) {
        $this->data['DATA'][mb_strtoupper($this->meta[0])] = $this->data['DATA']['value'];
    }

    private function exchangePropertyValue(PropertyCollection $propertyCollection) {
        $propertyId = $this->getPropertyId();
        $property = $propertyCollection->getProperty($propertyId);
        $propertyValue = $property->getValueBy($this->data['DATA']['value']);

        if(!$propertyValue && $property->getType() == 'N') {
            $htmlKey = PropertyManager::makeHtmlKey($this->data['DATA']['value']);
            $crcKey = PropertyManager::makeCrcKey($htmlKey);
            $value = number_format($this->data['DATA']['value'], 4, '.', '');
            $newProp = current($property->getData()['VALUES']);
            $newProp->CONTROL_ID = $newProp->CONTROL_NAME_ALT . '_' . $crcKey;
            $newProp->CONTROL_NAME = $newProp->CONTROL_NAME_ALT . '_' . $crcKey;
            $newProp->HTML_VALUE_ALT = $crcKey;
            $newProp->VALUE = $newProp->UPPER = $newProp->URL_ID = $value;
            $propertyValue = $newProp;
        }

        $this->data['DATA']['value'] = $propertyValue;
    }

    public function getEntityType() {
        if(mb_strpos( $this->data['CLASS_ID'], 'FilterProperty' ) !== false) {
            return 'FILTER';
        } elseif(mb_strpos( $this->data['CLASS_ID'], 'Price' ) !== false) {
            return 'PRICE';
        } else {
            return 'PROPERTY';
        }
    }

    public function getField($key) {
        if($this->isProperty())
            return ($key != 'VALUE' && ($key == 'CODE' || $key == 'NAME')) ? mb_strtolower($this->property->$key) : $this->getValues($key);
        else if($this->isPrice())
            return (isset($this->price[$key])) ? $this->price[$key] : false;
        else
            return false;
    }

    public function getData() {
        return (isset($this->data)) ? $this->data : false;
    }

    public function getDataField($key) {
        return (isset($this->data['DATA'][$key])) ? $this->data['DATA'][$key] : false;
    }

    private function getValues($key) {
        $result = [];

        if($this->isProperty()) {
            foreach ($this->data['DATA']['value'] as $propertyValue) {
                $result[] = $propertyValue->$key;
            }
        } else if($this->isPrice()) {
            $result = $this->data['DATA']['value'];
        }

        return $result;
    }

    public function setCompress($compressValue) {
        $this->isCompressed = $compressValue;
    }

    public function isCompressed() {
        return $this->isCompressed;
    }

    public function mergeValue(PropertySetEntity $propertySetEntity) {
        if($this->isProperty()) {
            $this->mergePropertyValue($propertySetEntity);
        } else if($this->isPrice()) {
            $this->mergePriceValue($propertySetEntity);
        }
    }

    public function resetValue() {
        if(is_array($this->data['DATA']['value']) && count($this->data['DATA']['value']) < 2)
            $this->data['DATA']['value'] = $this->data['DATA']['value'][0];
    }

    public function wrapValue() {
        if(!is_array($this->data['DATA']['value']))
            $this->data['DATA']['value'] = [$this->data['DATA']['value']];
    }

    public function compareProperty(PropertySetEntity $propertySetEntity) {
        return strcmp($this->data['CLASS_ID'], $propertySetEntity->data['CLASS_ID']) == 0 ||
            strcmp($this->meta[2], $propertySetEntity->meta[2]) == 0;
    }

    public function comparePrice(PropertySetEntity $propertySetEntity) {
        return strcmp($this->meta[1], $propertySetEntity->meta[1]) == 0;
    }

    private function mergePropertyValue(PropertySetEntity $propertySetEntity) {
        if(!is_array($this->data['DATA']['value'])) {
            $this->data['DATA']['value'] = [$this->data['DATA']['value']];
        }

        $this->data['DATA']['value'][] = $propertySetEntity->data['DATA']['value'];
    }

    private function mergePriceValue(PropertySetEntity $propertySetEntity) {
        $tmp['value'] = array_merge([$this->data['DATA']], [$propertySetEntity->data['DATA']]);
        $this->data['DATA'] = $tmp;
    }

    public function getFilterItem() {
        if($this->isProperty()) {
            return ['PROPERTY_'.$this->property->ID => $this->data['DATA']['value'][0]->URL_ID];
        } else if($this->isPrice()) {
            return ['catalog_PRICE_'.$this->price['ID'] => $this->data['DATA']['value']];
        }
    }

    public function getConditionArrayItem() {
        if($this->isPrice()) {
            $value = $this->data['DATA']['value'];
        } else {
            foreach ($this->data['DATA']['value'] as $item) {
                if ($item) {
                    $value[] = $item->getValueByType($this->property->getType());
                }
            }
//            $value = $this->data['DATA']['value'][0]->getValueByType($this->property->getType());
        }

        return [
            'CLASS_ID' => $this->data['CLASS_ID'],
            'DATA' => [
                'logic' => $this->data['DATA']['logic'],
                'value' => $value
                ]
        ];
    }
}

