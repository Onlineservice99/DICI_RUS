<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddOrderProps20210512173229680733 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        \Bitrix\Main\Loader::includeModule('sale');
        $result = \Bitrix\Sale\Internals\OrderPropsTable::add([
            'PERSON_TYPE_ID' => 1,
            'NAME' => 'ФИО',
            'TYPE' => 'STRING',
            'REQUIRED' => 'N',
            'SORT' => 100,
            'USER_PROPS' => 'Y',
            'IS_LOCATION' => 'N',
            'PROPS_GROUP_ID' => 1,
            'IS_EMAIL' => 'N',
            'IS_PROFILE_NAME' => 'Y',
            'IS_PAYER' => 'N',
            'IS_LOCATION4TAX' => 'N',
            'IS_FILTERED' => 'N',
            'CODE' => 'NAME',
            'IS_ZIP' => 'N',
            'IS_PHONE' => 'N',
            'ACTIVE' => 'Y',
            'UTIL' => 'N',
            'INPUT_FIELD_LOCATION' => 0,
            'MULTIPLE' => 'N',
            'IS_ADDRESS' => 'N',
            'SETTINGS' => "a:0:{}",
            'ENTITY_REGISTRY_TYPE' => "ORDER"
        ]);

        $result = \Bitrix\Sale\Internals\OrderPropsTable::add([
            'PERSON_TYPE_ID' => 1,
            'NAME' => 'Телефон',
            'TYPE' => 'STRING',
            'REQUIRED' => 'N',
            'SORT' => 100,
            'USER_PROPS' => 'Y',
            'IS_LOCATION' => 'N',
            'PROPS_GROUP_ID' => 1,
            'IS_EMAIL' => 'N',
            'IS_PROFILE_NAME' => 'N',
            'IS_PAYER' => 'N',
            'IS_LOCATION4TAX' => 'N',
            'IS_FILTERED' => 'N',
            'CODE' => 'PHONE',
            'IS_ZIP' => 'N',
            'IS_PHONE' => 'Y',
            'ACTIVE' => 'Y',
            'UTIL' => 'N',
            'INPUT_FIELD_LOCATION' => 0,
            'MULTIPLE' => 'N',
            'IS_ADDRESS' => 'N',
            'SETTINGS' => "a:0:{}",
            'ENTITY_REGISTRY_TYPE' => "ORDER"
        ]);

        $result = \Bitrix\Sale\Internals\OrderPropsTable::add([
            'PERSON_TYPE_ID' => 1,
            'NAME' => 'E-mail',
            'TYPE' => 'STRING',
            'REQUIRED' => 'N',
            'SORT' => 100,
            'USER_PROPS' => 'Y',
            'IS_LOCATION' => 'N',
            'PROPS_GROUP_ID' => 1,
            'IS_EMAIL' => 'Y',
            'IS_PROFILE_NAME' => 'N',
            'IS_PAYER' => 'N',
            'IS_LOCATION4TAX' => 'N',
            'IS_FILTERED' => 'N',
            'CODE' => 'EMAIL',
            'IS_ZIP' => 'N',
            'IS_PHONE' => 'N',
            'ACTIVE' => 'Y',
            'UTIL' => 'N',
            'INPUT_FIELD_LOCATION' => 0,
            'MULTIPLE' => 'N',
            'IS_ADDRESS' => 'N',
            'SETTINGS' => "a:0:{}",
            'ENTITY_REGISTRY_TYPE' => "ORDER"
        ]);


        $result = \Bitrix\Sale\Internals\OrderPropsTable::add([
            'PERSON_TYPE_ID' => 2,
            'NAME' => 'Наименование организации',
            'TYPE' => 'STRING',
            'REQUIRED' => 'N',
            'SORT' => 100,
            'USER_PROPS' => 'Y',
            'IS_LOCATION' => 'N',
            'PROPS_GROUP_ID' => 2,
            'IS_EMAIL' => 'N',
            'IS_PROFILE_NAME' => 'N',
            'IS_PAYER' => 'N',
            'IS_LOCATION4TAX' => 'N',
            'IS_FILTERED' => 'N',
            'CODE' => 'COMPANY',
            'IS_ZIP' => 'N',
            'IS_PHONE' => 'N',
            'ACTIVE' => 'Y',
            'UTIL' => 'N',
            'INPUT_FIELD_LOCATION' => 0,
            'MULTIPLE' => 'N',
            'IS_ADDRESS' => 'N',
            'SETTINGS' => "a:0:{}",
            'ENTITY_REGISTRY_TYPE' => "ORDER"
        ]);

        $result = \Bitrix\Sale\Internals\OrderPropsTable::add([
            'PERSON_TYPE_ID' => 2,
            'NAME' => 'Юр. адрес',
            'TYPE' => 'STRING',
            'REQUIRED' => 'N',
            'SORT' => 100,
            'USER_PROPS' => 'Y',
            'IS_LOCATION' => 'N',
            'PROPS_GROUP_ID' => 2,
            'IS_EMAIL' => 'N',
            'IS_PROFILE_NAME' => 'N',
            'IS_PAYER' => 'N',
            'IS_LOCATION4TAX' => 'N',
            'IS_FILTERED' => 'N',
            'CODE' => 'LEGAL_ADDRESS',
            'IS_ZIP' => 'N',
            'IS_PHONE' => 'N',
            'ACTIVE' => 'Y',
            'UTIL' => 'N',
            'INPUT_FIELD_LOCATION' => 0,
            'MULTIPLE' => 'N',
            'IS_ADDRESS' => 'N',
            'SETTINGS' => "a:0:{}",
            'ENTITY_REGISTRY_TYPE' => "ORDER"
        ]);

        $result = \Bitrix\Sale\Internals\OrderPropsTable::add([
            'PERSON_TYPE_ID' => 2,
            'NAME' => 'ИНН',
            'TYPE' => 'STRING',
            'REQUIRED' => 'N',
            'SORT' => 100,
            'USER_PROPS' => 'Y',
            'IS_LOCATION' => 'N',
            'PROPS_GROUP_ID' => 2,
            'IS_EMAIL' => 'N',
            'IS_PROFILE_NAME' => 'N',
            'IS_PAYER' => 'N',
            'IS_LOCATION4TAX' => 'N',
            'IS_FILTERED' => 'N',
            'CODE' => 'INN',
            'IS_ZIP' => 'N',
            'IS_PHONE' => 'N',
            'ACTIVE' => 'Y',
            'UTIL' => 'N',
            'INPUT_FIELD_LOCATION' => 0,
            'MULTIPLE' => 'N',
            'IS_ADDRESS' => 'N',
            'SETTINGS' => "a:0:{}",
            'ENTITY_REGISTRY_TYPE' => "ORDER"
        ]);

        $result = \Bitrix\Sale\Internals\OrderPropsTable::add([
            'PERSON_TYPE_ID' => 2,
            'NAME' => 'КПП',
            'TYPE' => 'STRING',
            'REQUIRED' => 'N',
            'SORT' => 100,
            'USER_PROPS' => 'Y',
            'IS_LOCATION' => 'N',
            'PROPS_GROUP_ID' => 2,
            'IS_EMAIL' => 'N',
            'IS_PROFILE_NAME' => 'N',
            'IS_PAYER' => 'N',
            'IS_LOCATION4TAX' => 'N',
            'IS_FILTERED' => 'N',
            'CODE' => 'KPP',
            'IS_ZIP' => 'N',
            'IS_PHONE' => 'N',
            'ACTIVE' => 'Y',
            'UTIL' => 'N',
            'INPUT_FIELD_LOCATION' => 0,
            'MULTIPLE' => 'N',
            'IS_ADDRESS' => 'N',
            'SETTINGS' => "a:0:{}",
            'ENTITY_REGISTRY_TYPE' => "ORDER"
        ]);

        $result = \Bitrix\Sale\Internals\OrderPropsTable::add([
            'PERSON_TYPE_ID' => 2,
            'NAME' => 'Р/c',
            'TYPE' => 'STRING',
            'REQUIRED' => 'N',
            'SORT' => 100,
            'USER_PROPS' => 'Y',
            'IS_LOCATION' => 'N',
            'PROPS_GROUP_ID' => 2,
            'IS_EMAIL' => 'N',
            'IS_PROFILE_NAME' => 'N',
            'IS_PAYER' => 'N',
            'IS_LOCATION4TAX' => 'N',
            'IS_FILTERED' => 'N',
            'CODE' => 'CHECKING_ACC',
            'IS_ZIP' => 'N',
            'IS_PHONE' => 'N',
            'ACTIVE' => 'Y',
            'UTIL' => 'N',
            'INPUT_FIELD_LOCATION' => 0,
            'MULTIPLE' => 'N',
            'IS_ADDRESS' => 'N',
            'SETTINGS' => "a:0:{}",
            'ENTITY_REGISTRY_TYPE' => "ORDER"
        ]);

        $result = \Bitrix\Sale\Internals\OrderPropsTable::add([
            'PERSON_TYPE_ID' => 2,
            'NAME' => 'K/c',
            'TYPE' => 'STRING',
            'REQUIRED' => 'N',
            'SORT' => 100,
            'USER_PROPS' => 'Y',
            'IS_LOCATION' => 'N',
            'PROPS_GROUP_ID' => 2,
            'IS_EMAIL' => 'N',
            'IS_PROFILE_NAME' => 'N',
            'IS_PAYER' => 'N',
            'IS_LOCATION4TAX' => 'N',
            'IS_FILTERED' => 'N',
            'CODE' => 'COR_ACC',
            'IS_ZIP' => 'N',
            'IS_PHONE' => 'N',
            'ACTIVE' => 'Y',
            'UTIL' => 'N',
            'INPUT_FIELD_LOCATION' => 0,
            'MULTIPLE' => 'N',
            'IS_ADDRESS' => 'N',
            'SETTINGS' => "a:0:{}",
            'ENTITY_REGISTRY_TYPE' => "ORDER"
        ]);

        $result = \Bitrix\Sale\Internals\OrderPropsTable::add([
            'PERSON_TYPE_ID' => 2,
            'NAME' => 'В банке...',
            'TYPE' => 'STRING',
            'REQUIRED' => 'N',
            'SORT' => 100,
            'USER_PROPS' => 'Y',
            'IS_LOCATION' => 'N',
            'PROPS_GROUP_ID' => 2,
            'IS_EMAIL' => 'N',
            'IS_PROFILE_NAME' => 'N',
            'IS_PAYER' => 'N',
            'IS_LOCATION4TAX' => 'N',
            'IS_FILTERED' => 'N',
            'CODE' => 'BANK',
            'IS_ZIP' => 'N',
            'IS_PHONE' => 'N',
            'ACTIVE' => 'Y',
            'UTIL' => 'N',
            'INPUT_FIELD_LOCATION' => 0,
            'MULTIPLE' => 'N',
            'IS_ADDRESS' => 'N',
            'SETTINGS' => "a:0:{}",
            'ENTITY_REGISTRY_TYPE' => "ORDER"
        ]);

        if (!$result->isSuccess()) {
            var_dump($result->getErrors());
            throw new MigrationException('Ошибка при удалении свойства инфоблока '.$ibp->LAST_ERROR);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        //
    }
}
