<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateCatalogIblock20210407202347384637 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblock = new \Arrilot\BitrixMigrations\Constructors\IBlock();
        $iblock->constructDefault('Каталог', 'catalog', 'catalog');
        $iblock->setDefaultUrls(true);
        $iblockId = $iblock->add();

        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_catalog', $iblockId);


    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $id = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog');
        \Arrilot\BitrixMigrations\Constructors\IBlock::delete($id);
    }
}
