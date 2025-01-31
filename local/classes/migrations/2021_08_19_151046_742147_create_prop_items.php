<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreatePropItems20210819151046742147 extends BitrixMigration
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
        $idActions = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_promotions');
        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('ITEMS', "Товары", $idActions);
        $prop->setPropertyTypeIblock("E", $idCatalog);
        $prop->setMultiple(true);
        $prop->add();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $idActions = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_promotions');
        $id = \Arrilot\BitrixMigrations\Constructors\IBlockProperty::getIdFromCode('ITEMS', $idActions);
        \Arrilot\BitrixMigrations\Constructors\IBlockProperty::delete($id);
    }
}
