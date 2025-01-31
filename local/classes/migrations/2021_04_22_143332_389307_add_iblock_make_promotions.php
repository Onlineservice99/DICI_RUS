<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddIblockMakePromotions20210422143332389307 extends BitrixMigration
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
        $iblock->constructDefault('Акции', 'promotions', 'adds');
        $iblockId = $iblock->add();

        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_promotions', $iblockId);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $id = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_promotions');
        \Arrilot\BitrixMigrations\Constructors\IBlock::delete($id);
    }
}
