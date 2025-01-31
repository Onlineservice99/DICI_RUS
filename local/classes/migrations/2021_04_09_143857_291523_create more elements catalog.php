<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateMoreElementsCatalog20210409143857291523 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog');

        \Bitrix\Main\Loader::includeModule('catalog');

        $props = [];
        $propertyEnums = CIBlockPropertyEnum::GetList([],['IBLOCK_ID' => $iblockId,'CODE' => 'LABEL']);
        while ($enumFields = $propertyEnums->GetNext()) {
            $props[$enumFields['VALUE']] = $enumFields['ID'];
        }

        global $USER;
        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "IBLOCK_SECTION_ID"      => 3,
            "CODE" => Cutil::translit("Автоматический выключатель ЕКF PROxima ВА 47-63 1P 5А C", "ru", ["replace_space"=>"-","replace_other"=>"-"]),
            "PROPERTY_VALUES" => [
                'COUNTRY' => "Россия",
                'ARTICLE' => rand(10000, 99999),
                'PICTURES' => [
                    0 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    1 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                    2 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    3 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                    4 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    5 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                ],
                'DETAIL_ISO' => [
                    0 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-1c.png'),
                    1 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-2c.png'),
                    2 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-3c.png'),
                ],
                'MAX_AMPERAGE' => '32',
                'MATERIAL' => "Полистирол",
                'CONTACT_NUMBER' => "3",
                'CREPL' => "Защёлка",
                'LABEL' => [
                    $props['Рекомендуем'],
                    $props['Хит продаж'],
                    $props['Акции'],
                    $props['Распродажа'],
                ],
                'DOCUMENT' => [
                    CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-1c.png'),
                    CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-2c.png')
                ]
            ],
            "NAME"           => "Автоматический выключатель ЕКF PROxima ВА 47-63 1P 5А \"C\"",
            "PREVIEW_TEXT"   => "Система молниезащиты требует расчета и составления проекта, так как переменных довольно много. Для разных зданий нужен свой подход.",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/card-1.png')
        );
        $id = $el->Add($arLoadProductArray);

        $result = \Bitrix\Catalog\Model\Product::add(["ID" => $id, "AVAILABLE" => 'Y']);
        \Bitrix\Catalog\Model\Price::add(["PRODUCT_ID" => $id, "PRICE" => rand(100, 99999), "CURRENCY" => "RUB", "CATALOG_GROUP_ID" => 1]);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "IBLOCK_SECTION_ID"      => 3,
            "PROPERTY_VALUES" => [
                'COUNTRY' => "Россия",
                'ARTICLE' => rand(10000, 99999),
                'PICTURES' => [
                    0 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    1 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                    2 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    3 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                    4 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    5 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                ],
                'DETAIL_ISO' => [
                    0 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-1c.png'),
                    1 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-2c.png'),
                    2 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-3c.png'),
                ],
                'MAX_AMPERAGE' => '32',
                'MATERIAL' => "Полистирол",
                'CONTACT_NUMBER' => "3",
                'CREPL' => "Защёлка",
                'LABEL' => [
                    $props['Рекомендуем'],
                    $props['Хит продаж'],
                    $props['Акции'],
                    $props['Распродажа'],
                ],
                'DOCUMENT' => [
                    CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-1c.png'),
                    CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-2c.png')
                ]
            ],
            "NAME"           => "Система кабельная двужильная Grandeks G2-005.0 /100",
            "CODE" => Cutil::translit("Система кабельная двужильная Grandeks G2-005.0 /100", "ru", ["replace_space"=>"-","replace_other"=>"-"]),
            "PREVIEW_TEXT"   => "Система молниезащиты требует расчета и составления проекта, так как переменных довольно много. Для разных зданий нужен свой подход.",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/local/templates/disi/assets/img/card-2.png')
        );
        $id = $el->Add($arLoadProductArray);

        \Bitrix\Main\Loader::includeModule('catalog');

        $result = \Bitrix\Catalog\Model\Product::add(["ID" => $id, "AVAILABLE" => 'Y']);
        \Bitrix\Catalog\Model\Price::add(["PRODUCT_ID" => $id, "PRICE" => rand(100, 99999), "CURRENCY" => "RUB", "CATALOG_GROUP_ID" => 1]);
        \CCatalogProduct::Update($id, ['QUANTITY' => rand(100, 999)]);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "IBLOCK_SECTION_ID"      => 3,
            "PROPERTY_VALUES" => [
                'COUNTRY' => "Россия",
                'ARTICLE' => rand(10000, 99999),
                'PICTURES' => [
                    0 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    1 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                    2 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    3 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                    4 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    5 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                ],
                'DETAIL_ISO' => [
                    0 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-1c.png'),
                    1 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-2c.png'),
                    2 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-3c.png'),
                ],
                'MAX_AMPERAGE' => '32',
                'MATERIAL' => "Полистирол",
                'CONTACT_NUMBER' => "3",
                'CREPL' => "Защёлка",
                'LABEL' => [
                    $props['Рекомендуем'],
                    $props['Хит продаж'],
                    $props['Акции'],
                    $props['Распродажа'],
                ],
                'DOCUMENT' => [
                    CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-1c.png'),
                    CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-2c.png')
                ]
            ],
            "NAME"           => "07-PG IP54 Ввод кабельный (сальник) (20шт)",
            "CODE" => Cutil::translit("07-PG IP54 Ввод кабельный (сальник) (20шт)", "ru", ["replace_space"=>"-","replace_other"=>"-"]),
            "PREVIEW_TEXT"   => "Система молниезащиты требует расчета и составления проекта, так как переменных довольно много. Для разных зданий нужен свой подход.",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/card-1.png')
        );
        $id = $el->Add($arLoadProductArray);

        \Bitrix\Main\Loader::includeModule('catalog');

        $result = \Bitrix\Catalog\Model\Product::add(["ID" => $id, "AVAILABLE" => 'Y']);
        \Bitrix\Catalog\Model\Price::add(["PRODUCT_ID" => $id, "PRICE" => rand(100, 99999), "CURRENCY" => "RUB", "CATALOG_GROUP_ID" => 1]);
        \CCatalogProduct::Update($id, ['QUANTITY' => rand(100, 999)]);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "IBLOCK_SECTION_ID"      => 3,
            "PROPERTY_VALUES" => [
                'COUNTRY' => "Россия",
                'ARTICLE' => rand(10000, 99999),
                'PICTURES' => [
                    0 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    1 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                    2 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    3 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                    4 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    5 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                ],
                'DETAIL_ISO' => [
                    0 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-1c.png'),
                    1 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-2c.png'),
                    2 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-3c.png'),
                ],
                'MAX_AMPERAGE' => '32',
                'MATERIAL' => "Полистирол",
                'CONTACT_NUMBER' => "3",
                'CREPL' => "Защёлка",
                'LABEL' => [
                    $props['Рекомендуем'],
                    $props['Хит продаж'],
                    $props['Акции'],
                    $props['Распродажа'],
                ],
                'DOCUMENT' => [
                    CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-1c.png'),
                    CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-2c.png')
                ]
            ],
            "NAME"           => "Выключатель концевой Электротехник ВК-300 БР11-67У2-21",
            "CODE" => Cutil::translit("Выключатель концевой Электротехник ВК-300 БР11-67У2-21", "ru", ["replace_space"=>"-","replace_other"=>"-"]),
            "PREVIEW_TEXT"   => "Система молниезащиты требует расчета и составления проекта, так как переменных довольно много. Для разных зданий нужен свой подход.",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/card-1.png')
        );
        $id = $el->Add($arLoadProductArray);

        $result = \Bitrix\Catalog\Model\Product::add(["ID" => $id, "AVAILABLE" => 'Y']);
        \Bitrix\Catalog\Model\Price::add(["PRODUCT_ID" => $id, "PRICE" => rand(100, 99999), "CURRENCY" => "RUB", "CATALOG_GROUP_ID" => 1]);
        \CCatalogProduct::Update($id, ['QUANTITY' => rand(100, 999)]);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "IBLOCK_SECTION_ID"      => 3,
            "PROPERTY_VALUES" => [
                'COUNTRY' => "Россия",
                'ARTICLE' => rand(10000, 99999),
                'PICTURES' => [
                    0 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    1 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                    2 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    3 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                    4 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block.png'),
                    5 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/product-block2.png'),
                ],
                'DETAIL_ISO' => [
                    0 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-1c.png'),
                    1 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-2c.png'),
                    2 => CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-3c.png'),
                ],
                'MAX_AMPERAGE' => '32',
                'MATERIAL' => "Полистирол",
                'CONTACT_NUMBER' => "3",
                'CREPL' => "Защёлка",
                'LABEL' => [
                    $props['Рекомендуем'],
                    $props['Хит продаж'],
                    $props['Акции'],
                    $props['Распродажа'],
                ],
                'DOCUMENT' => [
                    CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-1c.png'),
                    CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-2c.png')
                ]
            ],
            "NAME"           => "DIN-рейка оцинкованная 1400&nbsp;мм",
            "CODE" => Cutil::translit("DIN-рейка оцинкованная 1400&nbsp;мм", "ru", ["replace_space"=>"-","replace_other"=>"-"]),
            "PREVIEW_TEXT"   => "Система молниезащиты требует расчета и составления проекта, так как переменных довольно много. Для разных зданий нужен свой подход.",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/card-1.png')
        );
        $id = $el->Add($arLoadProductArray);

        \Bitrix\Main\Loader::includeModule('catalog');

        $result = \Bitrix\Catalog\Model\Product::add(["ID" => $id, "AVAILABLE" => 'Y']);
        \Bitrix\Catalog\Model\Price::add(["PRODUCT_ID" => $id, "PRICE" => rand(100, 99999), "CURRENCY" => "RUB", "CATALOG_GROUP_ID" => 1]);
        \CCatalogProduct::Update($id, ['QUANTITY' => rand(100, 999)]);
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
