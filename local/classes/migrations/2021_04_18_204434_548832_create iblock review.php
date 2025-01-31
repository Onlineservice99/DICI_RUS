<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateIblockReview20210418204434548832 extends BitrixMigration
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
        $iblock->constructDefault('Отзывы', 'reviews', 'forms');
        $iblockId = $iblock->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('FIO', 'ФИО', $iblockId);
        $prop->setIsRequired(true);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('PHONE', 'Телефон', $iblockId);
        $prop->setIsRequired(true);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('EMAIL', 'E-mail', $iblockId);
        $prop->setIsRequired(true);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('REVIEW', 'Ваш отзыв', $iblockId);
        $prop->setIsRequired(true);
        $prop->setUserType('HTML');
        $prop->setRowCount(5);
        $prop->add();

        $catalogId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog');
        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('ELEMENT', 'Элемент', $iblockId);
        $prop->setIsRequired(true);
        $prop->setPropertyType('E');
        $prop->setLinkIblockId($catalogId);
        $prop->add();

        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_review', $iblockId);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_review');
        \Arrilot\BitrixMigrations\Constructors\IBlock::delete($iblockId);
    }
}
