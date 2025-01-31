<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateOrdersData20210422171012463311 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        \Bitrix\Main\Loader::includeModule('sale');

        $types[0] = \Bitrix\Sale\Internals\PersonTypeTable::add(
            [
                'LID' => 's1',
                'NAME' => 'Физическое лицо',
                'SORT' => 100,
                'ACTIVE' => 'Y',
                'ENTITY_REGISTRY_TYPE' => 'ORDER'
            ]
        );

        \Bitrix\Sale\Internals\PersonTypeSiteTable::add(
            [
                'PERSON_TYPE_ID' => $types[0]->getId(),
                'SITE_ID' => 's1'
            ]
        );

        $types[1] = \Bitrix\Sale\Internals\PersonTypeTable::add(
            [
                'LID' => 's1',
                'NAME' => 'Юридическое лицо',
                'SORT' => 150,
                'ACTIVE' => 'Y',
                'ENTITY_REGISTRY_TYPE' => 'ORDER'
            ]
        );

        \Bitrix\Sale\Internals\PersonTypeSiteTable::add(
            [
                'PERSON_TYPE_ID' => $types[1]->getId(),
                'SITE_ID' => 's1'
            ]
        );

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
