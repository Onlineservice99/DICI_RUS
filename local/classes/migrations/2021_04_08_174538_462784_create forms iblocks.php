<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateFormsIblocks20210408174538462784 extends BitrixMigration
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
        $iblock->constructDefault('Обратный звонок', 'callback', 'forms');
        $iblockId = $iblock->add();

        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_callback', $iblockId);

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('PHONE', 'Телефон', $iblockId);
        $prop->setIsRequired(true);
        $prop->add();


        $iblock = new \Arrilot\BitrixMigrations\Constructors\IBlock();
        $iblock->constructDefault('Форма в контактах', 'form_contacts', 'forms');
        $iblockId = $iblock->add();
        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_form_contacts', $iblockId);

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('FIO', 'ФИО', $iblockId);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('MESSAGES', 'Ваше сообщение', $iblockId);
        $prop->setRowCount(3);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('EMAIL', 'e-mail', $iblockId);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('PHONE', 'Ваш телефон', $iblockId);
        $prop->setIsRequired(true);
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
        $id = \Bitrix\Main\Config\Option::get('meven.info', 'callback');
        \Arrilot\BitrixMigrations\Constructors\IBlock::delete($id);

        $id = \Bitrix\Main\Config\Option::get('meven.info', 'form_contacts');
        \Arrilot\BitrixMigrations\Constructors\IBlock::delete($id);
    }
}
