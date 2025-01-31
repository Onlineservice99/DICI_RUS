<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreatePropsCatalog20210408133720428931 extends BitrixMigration
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

        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog');
        $brandsIblock = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_brands');

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('BRAND', 'Бренд', $iblockId);
        $prop->setPropertyTypeIblock('E', $brandsIblock);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('COUNTRY', 'Страна изготовителя', $iblockId);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('ARTICLE', 'Код товара', $iblockId);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('PICTURES', 'Дополнительные картинки', $iblockId);
        $prop->setPropertyType('F');
        $prop->setMultiple(true);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('DETAIL_ISO', 'Знаки качества', $iblockId);
        $prop->setPropertyType('F');
        $prop->setMultiple(true);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('MAX_AMPERAGE', 'Максимальный ток нагрузки, А', $iblockId);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('MATERIAL', 'Материал изделия/изоляции', $iblockId);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('CONTACT_NUMBER', 'Количество контактов', $iblockId);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('CREPL', 'Крепление', $iblockId);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('DOCUMENT', 'Документация', $iblockId);
        $prop->setPropertyType('F');
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
