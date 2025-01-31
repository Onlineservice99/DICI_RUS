<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateNewsIblock20210407202358142717 extends BitrixMigration
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
        $iblock->constructDefault('Новости', 'news', 'adds');
        $iblockId = $iblock->add();

        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_news', $iblockId);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $id = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_news');
        \Arrilot\BitrixMigrations\Constructors\IBlock::delete($id);
    }
}
