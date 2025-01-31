<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateBannerIblock20210407202313426806 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $type = new \Arrilot\BitrixMigrations\Constructors\IBlockType();
        $type->setId('catalog');
        $type->setLang('ru', 'Каталог');
        $type->setSections();
        $type->add();

        $type = new \Arrilot\BitrixMigrations\Constructors\IBlockType();
        $type->setId('forms');
        $type->setLang('ru', 'Формы');
        $type->add();

        $type = new \Arrilot\BitrixMigrations\Constructors\IBlockType();
        $type->setId('adds');
        $type->setLang('ru', 'Дополнительные');
        $type->setSections();
        $type->add();

        $iblock = new \Arrilot\BitrixMigrations\Constructors\IBlock();
        $iblock->constructDefault('Баннеры', 'banners', 'adds');
        $iblockId = $iblock->add();

        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_banners', $iblockId);


        /*$prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('NAME', 'Имя', $iblockId);
        $prop->add();*/
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $id = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_banners');
        \Arrilot\BitrixMigrations\Constructors\IBlock::delete($id);
    }
}
