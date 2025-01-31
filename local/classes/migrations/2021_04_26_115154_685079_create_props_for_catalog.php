<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreatePropsForCatalog20210426115154685079 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog');

        \Bitrix\Main\Loader::includeModule('catalog');

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('LABEL_HIT', 'Хит продаж', $iblockId);
        $prop->setPropertyTypeList(['Да'],'C');
        $prop->setHint('bg-blue');
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('LABEL_RECOMEND', 'Рекомендуем', $iblockId);
        $prop->setPropertyTypeList(['Да'],'C');
        $prop->setHint('bg-violet');
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('LABEL_ACTIONS', 'Акции', $iblockId);
        $prop->setPropertyTypeList(['Да'],'C');
        $prop->setHint('bg-red');
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('LABEL_SALES', 'Распродажа', $iblockId);
        $prop->setPropertyTypeList(['Да'],'C');
        $prop->setHint('bg-yellow');
        $prop->add();

        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog');
        $id = \Arrilot\BitrixMigrations\Constructors\IBlockProperty::getIdFromCode('LABEL', $iblockId);
        \Arrilot\BitrixMigrations\Constructors\IBlockProperty::delete($id);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
    }
}
