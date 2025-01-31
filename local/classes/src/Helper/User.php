<?php
namespace Meven\Helper;

class User
{

    public function __construct()
    {

    }

    // Получение id группы пользователя по коду
    public static function getUserGroupId(string $code)
    {
        $result = [];
        $resGroup = \Bitrix\Main\GroupTable::getList(
            [
                'select'  => ['ID'],
                'filter'  => ['STRING_ID' => $code]
            ]
        );

        if ($arGroup = $resGroup->fetch()) {
            $result = $arGroup['ID'];
        }

        return $result;
    }
}
