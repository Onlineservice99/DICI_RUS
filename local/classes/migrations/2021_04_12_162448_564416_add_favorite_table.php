<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Application;
use \Meven\Models;


class AddFavoriteTable20210412162448564416 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $connection = Application::getInstance()->getConnection();

        if (!$connection->isTableExists(Models\FavoriteTable::getEntity()->getDBTableName())) {
            Models\FavoriteTable::getEntity()->createDbTable();
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $connection = Application::getInstance()->getConnection();

        if ($connection->isTableExists(Models\FavoriteTable::getEntity()->getDBTableName())) {
            $connection->dropTable(Models\FavoriteTable::getEntity()->getDBTableName());
        }
    }
}
