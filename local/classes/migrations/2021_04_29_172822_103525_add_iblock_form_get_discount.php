<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddIblockFormGetDiscount20210429172822103525 extends BitrixMigration
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
        $iblock->constructDefault('Получите персональную скидку', 'form_get_discount', 'forms');
        $iblockId = $iblock->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('EMAIL', 'E-mail', $iblockId);
        $prop->setIsRequired(true);
        $prop->add();

        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_form_get_discount', $iblockId);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $iblock = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_form_get_discount');
        \Arrilot\BitrixMigrations\Constructors\IBlock::delete($iblock);
    }
}
