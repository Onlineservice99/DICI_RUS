<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateSectionsCatalog20210407220137303424 extends BitrixMigration
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

        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog');

        $bs = new CIBlockSection;
        $arFields = [
            "IBLOCK_SECTION_ID" => 0,
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Кабельно-проводниковая продукция",
            "CODE" => Cutil::translit("Кабельно-проводниковая продукция", "ru", ["replace_space"=>"-","replace_other"=>"-"]),
            "PICTURE" => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/cc-1.png'),
        ];
        $id1 = $bs->Add($arFields);

        $arFields = [
            "IBLOCK_SECTION_ID" => $id1,
            "IBLOCK_ID" => $iblockId,
            "CODE" => Cutil::translit("Провода", "ru", ["replace_space"=>"-","replace_other"=>"-"]),
            "NAME" => "Провода",
        ];
        $bs->Add($arFields);
        $arFields = [
            "IBLOCK_SECTION_ID" => $id1,
            "IBLOCK_ID" => $iblockId,
            "CODE" => Cutil::translit("Кабеля", "ru", ["replace_space"=>"-","replace_other"=>"-"]),
            "NAME" => "Кабеля",
        ];
        $bs->Add($arFields);
        $arFields = [
            "IBLOCK_SECTION_ID" => $id1,
            "IBLOCK_ID" => $iblockId,
            "CODE" => Cutil::translit("Аксессуары", "ru", ["replace_space"=>"-","replace_other"=>"-"]),
            "NAME" => "Аксессуары",
        ];
        $bs->Add($arFields);

        $arFields = [
            "IBLOCK_SECTION_ID" => 0,
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Системы прокладки кабеля",
            "CODE" => Cutil::translit("Системы прокладки кабеля", "ru", ["replace_space"=>"-","replace_other"=>"-"]),
            "PICTURE" => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/cc-2.png'),
        ];
        $id2 = $bs->Add($arFields);
        $arFields = [
            "IBLOCK_SECTION_ID" => $id2,
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Кабельные лотки",
            "CODE" => Cutil::translit("Кабельные лотки", "ru", ["replace_space"=>"-","replace_other"=>"-"]),
        ];
        $bs->Add($arFields);
        $arFields = [
            "IBLOCK_SECTION_ID" => $id2,
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Металлические трубы и трубы пнд (полиэтилен низкого давления)",
            "CODE" => Cutil::translit("Металлические трубы и трубы пнд (полиэтилен низкого давления)", "ru", ["replace_space"=>"-","replace_other"=>"-"]),
        ];
        $bs->Add($arFields);
        $arFields = [
            "IBLOCK_SECTION_ID" => $id2,
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Кабель-каналы",
        ];
        $bs->Add($arFields);
        $arFields = [
            "IBLOCK_SECTION_ID" => $id2,
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Крепежные детали",
        ];
        $bs->Add($arFields);
        $arFields = [
            "IBLOCK_SECTION_ID" => $id2,
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Перфорированные короба",
        ];
        $bs->Add($arFields);
        $arFields = [
            "IBLOCK_SECTION_ID" => $id2,
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Электророзетки",
        ];
        $bs->Add($arFields);
        $arFields = [
            "IBLOCK_SECTION_ID" => $id2,
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Выключатели",
        ];
        $bs->Add($arFields);
        $arFields = [
            "IBLOCK_SECTION_ID" => $id2,
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Рамки электроустановочные",
        ];
        $bs->Add($arFields);

        $arFields = [
            "IBLOCK_SECTION_ID" => 0,
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Арматура для СИП",
            "PICTURE" => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/cc-3.png'),
        ];
        $id3 = $bs->Add($arFields);

        $arFields = [
            "IBLOCK_SECTION_ID" => 0,
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Низковольтное оборудование",
            "PICTURE" => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/cc-4.png'),
        ];
        $id4 = $bs->Add($arFields);
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
