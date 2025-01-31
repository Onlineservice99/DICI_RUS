<?php


namespace Meven\Helper;


class Helper
{
    public static function formatNumber($num, $decimals = 0): string
    {
        return number_format($num, $decimals, '.', ' ');
    }

    public static function getRussvetProperties($elementId)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $result = [];

        $element = \Bitrix\Iblock\Elements\ElementCatalogTable::getList([
            'filter' => ['=ID' => $elementId],
            'select' => ['API_' => 'API']
        ])->fetchObject();
        if ($data = $element->getApi())
            $data = $element->getApi()->getValue();
        if (!$data) {
            return $result;
        }

        $data = unserialize($data);
        if (!$data || !$data['TEXT']) {
            return $result;
        }

        $data = str_replace('&quot', '"', $data['TEXT']);
        $data = json_decode($data, true);

        if (!$data || !$data['SPECS']) {
            return $result;
        }
        
        if ($data['INFO'][0]['VENDOR_CODE']) {
            $result[] = [
                'NAME' => 'Артикул',
                'VALUE' => $data['INFO'][0]['VENDOR_CODE'],
            ];
        }

        if ($data['INFO'][0]['SERIES']) {
            $result[] = [
                'NAME' => 'Серия',
                'VALUE' => $data['INFO'][0]['SERIES'],
            ];
        }

        foreach ($data['SPECS'] as $property) {
            $value = preg_replace('/^\./', '0.', $property['VALUE']);
            if ($property['UOM']) {
                $value .= (' ' . $property['UOM']);
            }
            $result[] = [
                'NAME' => $property['NAME'],
                'VALUE' => $value,
            ];
        }

        return $result;
    }

    public static function getJsonValue($content): string
    {
        $content = str_replace('&quot;', '"', $content);
        return json_decode($content, true);
    }
}