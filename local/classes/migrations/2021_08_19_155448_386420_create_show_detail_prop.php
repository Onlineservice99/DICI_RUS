<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateShowDetailProp20210819155448386420 extends BitrixMigration
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
        $idCatalog = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog');
        $properties = CIBlockProperty::GetList(
            ["sort" => "asc", "name" => "asc"],
            ["ACTIVE" => "Y", "IBLOCK_ID" => $idCatalog]
        );

        while ($prop_fields = $properties->GetNext()) {
            \Bitrix\Iblock\Model\PropertyFeature::setFeatures(
                $prop_fields["ID"],[[
                    "MODULE_ID"=>"iblock",
                    "IS_ENABLED"=>"Y",
                    "FEATURE_ID" => "DETAIL_PAGE_SHOW"
                ]]
            );
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
