<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateSectionNewElems20210712132906851947 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $bs = new \CIBlockSection;
        $arFields = Array(
            "ACTIVE" => "N",
            "IBLOCK_ID" => \Bitrix\Main\Config\Option::get("meven.info","iblock_catalog"),
            "NAME" => "Новый раздел",
            "CODE" => 'new_section_test',
            "SORT" => 100
        );

        $id = $bs->Add($arFields);

        \Bitrix\Main\Config\Option::set("meven.info","section_new_elements", $id);
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
