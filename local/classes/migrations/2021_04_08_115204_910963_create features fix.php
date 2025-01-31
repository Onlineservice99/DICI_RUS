<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateFeaturesFix20210408115204910963 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_features');

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('FILE', 'Файл', $iblockId);
        $prop->setPropertyType('F');
        $prop->add();

        global $USER;
        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "Огромный ассортимент",
            "PREVIEW_TEXT"   => "Более 1,5 млн товаров на собственном складе в наличии",
            "PROPERTY_VALUES"=> [
                "FILE" => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/ic-list.svg')
            ]
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "Профессиональные консультации",
            "PREVIEW_TEXT"   => "Звоните - мы поможем с выбором, найдем и подберем совместимое, прокнсультируем по любым вопросам",
            "PROPERTY_VALUES"=> [
                "FILE" => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/ic-smart.svg')
            ]
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "Бесплатная доставка",
            "PREVIEW_TEXT"   => "Доставка до двери или в 150 пунктах выдачи, беслатно при заказе от 1000 рублей",
            "PROPERTY_VALUES"=> [
                "FILE" => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/ic-delivery.svg')
            ]
        );
        $el->Add($arLoadProductArray);
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
