<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use \Bitrix\Main\Config\Option;

class InsertDefaultPropInModule20210408175308866235 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        Option::set('meven.info', 'phone', '8 800 123 4567');
        Option::set('meven.info', 'email', 'disimarket@yandex.ru');
        Option::set('meven.info', 'address', 'г. Абакан, ул. Складская, 9 «У»');
        Option::set('meven.info', 'workpnpt-start', '9:00');
        Option::set('meven.info', 'workpnpt-end', '18:00');
        Option::set('meven.info', 'worksbvs-start', '9:00');
        Option::set('meven.info', 'worksbvs-end', '18:00');
        Option::set('meven.info', 'address_hall', 'г. Абакан, ул. Складская, 9 «У»');
        Option::set('meven.info', 'phone_hall', '8 (3902) 24-89-82');
        Option::set('meven.info', 'email_hall', 'admin@dicimarket.ru');
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
