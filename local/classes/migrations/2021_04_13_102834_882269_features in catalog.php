<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class FeaturesInCatalog20210413102834882269 extends BitrixMigration
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

        $iblock = new \Arrilot\BitrixMigrations\Constructors\IBlock();
        $iblock->constructDefault('Фичи в каталоге', 'features_catalog', 'adds');
        $iblockId = $iblock->add();

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('PICTURE', 'Ссылка на картинку svg', $iblockId);
        $prop->add();

        \Bitrix\Main\Config\Option::set('meven.info', 'iblock_features_catalog', $iblockId);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"         => $iblockId,
            "PROPERTY_VALUES"   => [
                "PICTURE" => "/local/templates/disi/assets/icons/symbol-defs.svg#icon-rotate"
            ],
            "NAME"              => "Гарантируем качество - обмен и возврат в течение 14 дней",
        );
        $id = $el->Add($arLoadProductArray);


        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"         => $iblockId,
            "PROPERTY_VALUES"   => [
                "PICTURE" => "/local/templates/disi/assets/icons/symbol-defs.svg#icon-car"
            ],
            "NAME"              => "Останьтесь дома - мы все привезем!",
        );
        $id = $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"       => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"         => $iblockId,
            "PROPERTY_VALUES"   => [
                "PICTURE" => "/local/templates/disi/assets/icons/symbol-defs.svg#icon-tag"
            ],
            "NAME"              => "Постоянным покупателям - еще дешевле!",
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
