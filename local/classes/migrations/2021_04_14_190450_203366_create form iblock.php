<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateFormIblock20210414190450203366 extends BitrixMigration
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
        $iblock->constructDefault('Обратная связь', 'callback', 'forms');
        $iblockId = $iblock->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('PHONE', 'Телефон', $iblockId);
        $prop->add();

        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_form_callback', $iblockId);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $iblock = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_form_callback');
        \Arrilot\BitrixMigrations\Constructors\IBlock::delete($iblock);
    }
}
