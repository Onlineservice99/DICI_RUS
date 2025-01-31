<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateElementsBrands20210408105716871510 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_brands');

        global $USER;
        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "1",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/brand-1.png')
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "2",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/brand-2.png')
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "3",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/brand-3.png')
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "4",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/brand-4.png')
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "5",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/brand-5.png')
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "6",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/brand-6.png')
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "7",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/brand-1.png')
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "8",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/brand-2.png')
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "9",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/brand-3.png')
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
