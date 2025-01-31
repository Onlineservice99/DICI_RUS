<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateElementBanners20210408100347821084 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_banners');
        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('LINK', 'Ссылка', $iblockId);
        $prop->setWithDescription(true);
        $prop->add();

        global $USER;
        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "PROPERTY_VALUES"=> ['LINK' => ['VALUE' => "/catalog/", 'DESCRIPTION' => "Перейти в раздел"]],
            "NAME"           => "Нагревательные провода",
            "PREVIEW_TEXT"   => "Нагревательные провода",
            "DETAIL_TEXT"    => "Для теплых полов и стен",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/banner-2.jpg')
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "PROPERTY_VALUES"=> ['LINK' => ['VALUE' => "/catalog/", 'DESCRIPTION' => "Перейти в раздел"]],
            "NAME"           => "Онлайн-гипермаркет",
            "PREVIEW_TEXT"   => "Онлайн Гипермаркет электротехнических товаров по низким–низким ценам",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/banner-1.jpg')
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "PROPERTY_VALUES"=> ['LINK' => ['VALUE' => "/catalog/", 'DESCRIPTION' => "Перейти в раздел"]],
            "NAME"           => "Нагревательные провода",
            "PREVIEW_TEXT"   => "Нагревательные провода",
            "DETAIL_TEXT"    => "Для теплых полов и стен",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/banner-2.jpg')
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
