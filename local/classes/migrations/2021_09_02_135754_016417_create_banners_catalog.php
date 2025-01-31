<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateBannersCatalog20210902135754016417 extends BitrixMigration
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
        $iblock->constructDefault('Баннеры между товарами', 'banners_catalog', 'catalog');
        $iblockId = $iblock->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('ADD_CLASS', 'Дополнительные классы', $iblockId);
        $prop->add();

        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_banners_catalog', $iblockId);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $id = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_banners_catalog');
        \Arrilot\BitrixMigrations\Constructors\IBlock::delete($id);
    }
}
