<?php
namespace Meven\User;

use Bitrix\Main\UserTable;

class Authorize
{
    public function __construct()
    {

    }

    public static function auth(string $name, string $password)
    {
        global $USER;
        $tempPhone = str_replace(['(', ')', '-', ' '], '', $name);
        $tempPhone = \Bitrix\Main\UserPhoneAuthTable::normalizePhoneNumber($tempPhone);
        $userElemPhone = \Bitrix\Main\UserPhoneAuthTable::getList([
            'filter' => ['PHONE_NUMBER' => '+'.$tempPhone],
            'select' => ['USER_ID', 'PHONE_NUMBER']
        ])->fetch();

        if ((int) $userElemPhone['USER_ID'] > 0) {
            $userElem = UserTable::getList([
                'filter' => [
                    '=ID' => $userElemPhone['USER_ID']
                ],
                'select' => [
                    'LOGIN'
                ]
            ])->fetch();
        } elseif (filter_var($name, FILTER_VALIDATE_EMAIL)) {
            $userElem = UserTable::getList([
                'filter' => [
                   '=EMAIL' => $name
                ],
                'select' => [
                   'LOGIN'
                ]
            ])->fetch();
        } else {
            return "Ошибка в веденном телефоне или email";
        }


        if (!is_object($USER)) $USER = new \CUser;
        return $USER->Login($userElem['LOGIN'], $password, 'Y');
    }
}