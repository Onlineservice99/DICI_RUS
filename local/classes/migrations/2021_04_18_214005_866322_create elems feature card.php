<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateElemsFeatureCard20210418214005866322 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        global $USER;
        $iblock = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_features_catalog');

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"         => $iblock,
            "SORT"         => 1000,
            "PROPERTY_VALUES"   => [
                "PICTURE" => "/local/templates/disi/assets/img/ic-list.svg"
            ],
            "NAME"              => "Огромный ассортимент",
            "PREVIEW_TEXT"      => "Более 1,5 млн товаров на собственном складе в наличии",
        );
        $id = $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"         => $iblock,
            "SORT"         => 1000,
            "PROPERTY_VALUES"   => [
                "PICTURE" => "/local/templates/disi/assets/img/ic-smart.svg"
            ],
            "NAME"              => "Профессиональные консультации",
            "PREVIEW_TEXT"      => "Звоните - мы поможем с выбором, найдем и подберем совместимое, прокнсультируем по любым вопросам",
        );
        $id = $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"         => $iblock,
            "SORT"         => 1000,
            "PROPERTY_VALUES"   => [
                "PICTURE" => "/local/templates/disi/assets/img/ic-delivery.svg"
            ],
            "NAME"              => "Бесплатная доставка",
            "PREVIEW_TEXT"      => "Доставка до двери или в 150 пунктах выдачи, беслатно при заказе от 1000 рублей",
        );
        $id = $el->Add($arLoadProductArray);
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
