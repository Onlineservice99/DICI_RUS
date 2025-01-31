<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddOrderProps20210714174044738728 extends BitrixMigration
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

        $arFields = [
            "PERSON_TYPE_ID" => PERSON_TYPE_FIZ,
            "NAME" => "ФИО",
            "TYPE" => "STRING",
            "REQUIRED" => "N",
            "DEFAULT_VALUE" => "",
            "SORT" => 100,
            "CODE" => "NAME",
            "USER_PROPS" => "Y",
            "IS_LOCATION" => "N",
            "IS_LOCATION4TAX" => "N",
            "PROPS_GROUP_ID" => 1,
            "SIZE1" => 0,
            "SIZE2" => 0,
            "DESCRIPTION" => "",
            "IS_EMAIL" => "N",
            "IS_PROFILE_NAME" => "Y",
            "IS_PAYER" => "N",
            "IS_PHONE" => "N"
        ];

        CSaleOrderProps::Add($arFields);

        $arFields = [
            "PERSON_TYPE_ID" => PERSON_TYPE_FIZ,
            "NAME" => "Телефон",
            "TYPE" => "STRING",
            "REQUIRED" => "N",
            "DEFAULT_VALUE" => "",
            "SORT" => 100,
            "CODE" => "PHONE",
            "USER_PROPS" => "Y",
            "IS_LOCATION" => "N",
            "IS_LOCATION4TAX" => "N",
            "PROPS_GROUP_ID" => 1,
            "SIZE1" => 0,
            "SIZE2" => 0,
            "DESCRIPTION" => "",
            "IS_EMAIL" => "N",
            "IS_PROFILE_NAME" => "N",
            "IS_PAYER" => "N",
            "IS_PHONE" => "Y"
        ];

        CSaleOrderProps::Add($arFields);


        $arFields = [
            "PERSON_TYPE_ID" => PERSON_TYPE_FIZ,
            "NAME" => "E-mail",
            "TYPE" => "STRING",
            "REQUIRED" => "N",
            "DEFAULT_VALUE" => "",
            "SORT" => 100,
            "CODE" => "EMAIL",
            "USER_PROPS" => "Y",
            "IS_LOCATION" => "N",
            "IS_LOCATION4TAX" => "N",
            "PROPS_GROUP_ID" => 1,
            "SIZE1" => 0,
            "SIZE2" => 0,
            "DESCRIPTION" => "",
            "IS_EMAIL" => "Y",
            "IS_PROFILE_NAME" => "N",
            "IS_PAYER" => "N",
            "IS_PHONE" => "N"
        ];

        CSaleOrderProps::Add($arFields);

        $arFields = [
            "PERSON_TYPE_ID" => PERSON_TYPE_FIZ,
            "NAME" => "Наименование организации",
            "TYPE" => "STRING",
            "REQUIRED" => "N",
            "DEFAULT_VALUE" => "",
            "SORT" => 100,
            "CODE" => "COMPANY",
            "USER_PROPS" => "Y",
            "IS_LOCATION" => "N",
            "IS_LOCATION4TAX" => "N",
            "PROPS_GROUP_ID" => 1,
            "SIZE1" => 0,
            "SIZE2" => 0,
            "DESCRIPTION" => "",
            "IS_EMAIL" => "N",
            "IS_PROFILE_NAME" => "N",
            "IS_PAYER" => "N",
            "IS_PHONE" => "N"
        ];

        $ID = CSaleOrderProps::Add($arFields);


        $arFields = [
            "PERSON_TYPE_ID" => PERSON_TYPE_FIZ,
            "NAME" => "Юр. адрес",
            "TYPE" => "STRING",
            "REQUIRED" => "N",
            "DEFAULT_VALUE" => "",
            "SORT" => 100,
            "CODE" => "LEGAL_ADDRESS",
            "USER_PROPS" => "Y",
            "IS_LOCATION" => "N",
            "IS_LOCATION4TAX" => "N",
            "PROPS_GROUP_ID" => 1,
            "SIZE1" => 0,
            "SIZE2" => 0,
            "DESCRIPTION" => "",
            "IS_EMAIL" => "N",
            "IS_PROFILE_NAME" => "N",
            "IS_PAYER" => "N",
            "IS_PHONE" => "N",
        ];

        $ID = CSaleOrderProps::Add($arFields);

        $arFields = [
            "PERSON_TYPE_ID" => PERSON_TYPE_FIZ,
            "NAME" => "ИНН",
            "TYPE" => "STRING",
            "REQUIRED" => "N",
            "DEFAULT_VALUE" => "",
            "SORT" => 100,
            "CODE" => "INN",
            "USER_PROPS" => "Y",
            "IS_LOCATION" => "N",
            "IS_LOCATION4TAX" => "N",
            "PROPS_GROUP_ID" => 1,
            "SIZE1" => 0,
            "SIZE2" => 0,
            "DESCRIPTION" => "",
            "IS_EMAIL" => "N",
            "IS_PROFILE_NAME" => "N",
            "IS_PAYER" => "N",
            "IS_PHONE" => "N"
        ];

        CSaleOrderProps::Add($arFields);


        $arFields = [
            "PERSON_TYPE_ID" => PERSON_TYPE_FIZ,
            "NAME" => "КПП",
            "TYPE" => "STRING",
            "REQUIRED" => "N",
            "DEFAULT_VALUE" => "",
            "SORT" => 100,
            "CODE" => "KPP",
            "USER_PROPS" => "Y",
            "IS_LOCATION" => "N",
            "IS_LOCATION4TAX" => "N",
            "PROPS_GROUP_ID" => 1,
            "SIZE1" => 0,
            "SIZE2" => 0,
            "DESCRIPTION" => "",
            "IS_EMAIL" => "N",
            "IS_PROFILE_NAME" => "N",
            "IS_PAYER" => "N",
            "IS_PHONE" => "N"
        ];

        CSaleOrderProps::Add($arFields);

        $arFields = [
            "PERSON_TYPE_ID" => PERSON_TYPE_FIZ,
            "NAME" => "Р/c",
            "TYPE" => "STRING",
            "REQUIRED" => "N",
            "DEFAULT_VALUE" => "",
            "SORT" => 100,
            "CODE" => "CHECKING_ACC",
            "USER_PROPS" => "Y",
            "IS_LOCATION" => "N",
            "IS_LOCATION4TAX" => "N",
            "PROPS_GROUP_ID" => 1,
            "SIZE1" => 0,
            "SIZE2" => 0,
            "DESCRIPTION" => "",
            "IS_EMAIL" => "N",
            "IS_PROFILE_NAME" => "N",
            "IS_PAYER" => "N",
            "IS_PHONE" => "N"
        ];

        CSaleOrderProps::Add($arFields);

        $arFields = [
            "PERSON_TYPE_ID" => PERSON_TYPE_FIZ,
            "NAME" => "K/c",
            "TYPE" => "STRING",
            "REQUIRED" => "N",
            "DEFAULT_VALUE" => "",
            "SORT" => 100,
            "CODE" => "COR_ACC",
            "USER_PROPS" => "Y",
            "IS_LOCATION" => "N",
            "IS_LOCATION4TAX" => "N",
            "PROPS_GROUP_ID" => 1,
            "SIZE1" => 0,
            "SIZE2" => 0,
            "DESCRIPTION" => "",
            "IS_EMAIL" => "N",
            "IS_PROFILE_NAME" => "N",
            "IS_PAYER" => "N",
            "IS_PHONE" => "N"
        ];

        CSaleOrderProps::Add($arFields);

        $arFields = [
            "PERSON_TYPE_ID" => PERSON_TYPE_FIZ,
            "NAME" => "Банк",
            "TYPE" => "STRING",
            "REQUIRED" => "N",
            "DEFAULT_VALUE" => "",
            "SORT" => 100,
            "CODE" => "BANK",
            "USER_PROPS" => "Y",
            "IS_LOCATION" => "N",
            "IS_LOCATION4TAX" => "N",
            "PROPS_GROUP_ID" => 1,
            "SIZE1" => 0,
            "SIZE2" => 0,
            "DESCRIPTION" => "",
            "IS_EMAIL" => "N",
            "IS_PROFILE_NAME" => "N",
            "IS_PAYER" => "N",
            "IS_PHONE" => "N"
        ];

        CSaleOrderProps::Add($arFields);

        $arFields = [
            "PERSON_TYPE_ID" => PERSON_TYPE_FIZ,
            "NAME" => "Адрес",
            "TYPE" => "STRING",
            "REQUIRED" => "N",
            "DEFAULT_VALUE" => "",
            "SORT" => 100,
            "CODE" => "ADDRESS",
            "USER_PROPS" => "N",
            "IS_LOCATION" => "N",
            "IS_LOCATION4TAX" => "Y",
            "PROPS_GROUP_ID" => 1,
            "SIZE1" => 0,
            "SIZE2" => 0,
            "DESCRIPTION" => "",
            "IS_EMAIL" => "N",
            "IS_PROFILE_NAME" => "N",
            "IS_PAYER" => "N",
            "IS_PHONE" => "N",
            "IS_ADDRESS" => "Y"
        ];

        CSaleOrderProps::Add($arFields);

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
