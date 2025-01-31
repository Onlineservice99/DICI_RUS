<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateElementsCatalog20210409131141845574 extends BitrixMigration
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
        $prop->constructDefault('LABEL', 'Ярлык', $iblockId);
        $prop->setPropertyTypeList(['Рекомендуем', 'Хит продаж', 'Акции', 'Распродажа'], 'L');
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
        //
    }
}
