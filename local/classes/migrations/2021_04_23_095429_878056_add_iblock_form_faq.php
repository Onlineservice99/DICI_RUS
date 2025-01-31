<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddIblockFormFaq20210423095429878056 extends BitrixMigration
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
        $iblock->constructDefault('Вопрос-ответ', 'form_faq', 'forms');
        $iblockId = $iblock->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('NAME', 'ФИО', $iblockId);
        $prop->setIsRequired(true);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('PHONE', 'Ваш телефон', $iblockId);
        $prop->setIsRequired(true);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('EMAIL', 'E-mail', $iblockId);
        $prop->setIsRequired(true);
        $prop->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('MESSAGE', 'Ваш вопрос', $iblockId);
        $prop->setIsRequired(true);
        $prop->setUserType('HTML');
        $prop->setRowCount(5);
        $prop->add();

        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_form_faq', $iblockId);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $iblock = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_form_faq');
        \Arrilot\BitrixMigrations\Constructors\IBlock::delete($iblock);
    }
}
