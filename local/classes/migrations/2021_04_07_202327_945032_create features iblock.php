<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateFeaturesIblock20210407202327945032 extends BitrixMigration
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
        $iblock->constructDefault('Фичи под баннером', 'features', 'adds');
        $iblockId = $iblock->add();

        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_features', $iblockId);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $id = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_features');
        \Arrilot\BitrixMigrations\Constructors\IBlock::delete($id);
    }
}
