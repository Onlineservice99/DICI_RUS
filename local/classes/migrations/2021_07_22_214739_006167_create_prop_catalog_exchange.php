<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreatePropCatalogExchange20210722214739006167 extends BitrixMigration
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
        $this->createProp("Тип крышки");
        $this->createProp("С соединителем", 'checkbox');
        $this->createProp("Защитная пленка", 'checkbox');
        $this->createProp("С кабельным зажимом", 'checkbox');
        $this->createProp("Исполнение крышки");
        $this->createProp("Прозрачный", 'checkbox');
        $this->createProp("Соответствует функциональной целостности", 'checkbox');
        $this->createProp("Цвет");
        $this->createProp("Вид материала");
        $this->createProp("Полезное поперечное сечение");
        $this->createProp("Тип монтажа");
        $this->createProp("Количество встраиваемых разделительных перегородок");
        $this->createProp("Количество постоянных перегородок");
        $this->createProp("Материал");
        $this->createProp("Длина");
        $this->createProp("Ширина");
        $this->createProp("Высота");
    }

    public function createProp($name, $type = 'string')
    {
        $this->iblockIdCatalog = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog');
        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('test', $name, $this->iblockIdCatalog);
        $code = strip_tags(\Cutil::translit(
            $name,
            "ru",
            [
                "replace_space" => "_",
                "replace_other" => "_",
                "max_len"=> 20,
                'change_case' => 'U'
            ]
            )
        );
        $prop->setCode($code);
        if ($type == 'checkbox') {
            $prop->setPropertyTypeList(['Да'], 'C');
        }
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
