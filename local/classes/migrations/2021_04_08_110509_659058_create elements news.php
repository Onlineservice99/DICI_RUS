<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateElementsNews20210408110509659058 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_news');

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('TYPE', 'Тип новости', $iblockId);
        $prop->setPropertyType('L');
        $prop->setPropertyTypeList(['Публикации', "Новости"]);
        $prop->setWithDescription(true);
        $prop->add();

        $props = [];
        $propertyEnums = CIBlockPropertyEnum::GetList(
            [],
            [
                'IBLOCK_ID' => $iblockId,
                'CODE' => 'TYPE'
            ]
        );
        while ($enumFields = $propertyEnums->GetNext()) {
            $props[$enumFields['VALUE']] = $enumFields['ID'];
        }

        global $USER;
        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "PROPERTY_VALUE" => [
                'TYPE' => $props['Новости']
            ],
            "NAME"           => "Молниезащита: назначение, устройство, расчет",
            "PREVIEW_TEXT"   => "Система молниезащиты требует расчета и составления проекта, так как переменных довольно много. Для разных зданий нужен свой подход.",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/news-1.jpg')
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "PROPERTY_VALUE" => [
                'TYPE' => $props['Публикации']
            ],
            "NAME"           => "Контроль качества продукции",
            "PREVIEW_TEXT"   => "В рамках системы менеджмента качества, компания Минимакс внедрила опцию контроля за качеством поставляемой продукции.",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/news-2.jpg')
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "PROPERTY_VALUE" => [
                'TYPE' => $props['Публикации']
            ],
            "NAME"           => "6 аргументов в пользу импортозамещения от компании OSRAM",
            "PREVIEW_TEXT"   => "Тема импортозамещения имеет высокую актуальность. Мы приводим 6 весомых аргументов в пользу люминесцентных ламп смоленского производства компании OSRAM.",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/news-3.jpg')
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
