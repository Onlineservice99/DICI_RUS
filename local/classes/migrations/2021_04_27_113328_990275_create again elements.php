<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateAgainElements20210427113328990275 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        \Bitrix\Main\Loader::includeModule('catalog');
        $pictures = [
            'https://realize-project-as.gitlab.io/disi/img/card-6.png',
            'https://realize-project-as.gitlab.io/disi/img/card-7.png',
            'https://realize-project-as.gitlab.io/disi/img/card-8.png',
            'https://realize-project-as.gitlab.io/disi/img/card-9.png',
            'https://realize-project-as.gitlab.io/disi/img/card-1.png',
            'https://realize-project-as.gitlab.io/disi/img/card-2.png',
            'https://realize-project-as.gitlab.io/disi/img/card-3.png',
            'https://realize-project-as.gitlab.io/disi/img/card-4.png',
            'https://realize-project-as.gitlab.io/disi/img/card-5.png',
        ];

        $names = [
            'Зажим анкерный 4x(16-35) мм² SO158.1' . rand(1, 999),
            'Кабель ВВГнг(A)-П 2*1,5 (N) -0,66' . rand(1, 999),
            'Витая пара U/UTP кат.5E 4х2х24AWG PVC серый (305м) (LC1-C5E04-111)' . rand(1, 999),
            'DIN-рейка оцинкованная 140 мм' . rand(1, 999),
            'Автоматический выключатель ЕКF PROxima ВА 7-63 1P 5А "C"' . rand(1, 999),
            'Система кабельная двужильная Grandeks G2-05.0 /100' . rand(1, 999),
            '07-PG IP54 Ввод кабельный (сальник) (20шт)' . rand(1, 999),
            'Выключатель концевой Электротехник ВК-300 БР11-67У2-21' . rand(1, 999),
            'Наконечник медный луженый ТМЛ 16-8-6' . rand(1, 999),
            'Кабель ВВГнг(A)-П 3*1,5ок (N,PE) -0,66' . rand(1, 999),
            'Зажим ответвительный изолированный ЗОИ 16-70/1,5-10 (P6, P616, EP95-13, SLIW11.1) IEK (UZA-11-D01-D10)' . rand(
                1,
                999
            ),
            'Зажим анкерный 4x(16-35) мм² SO158.1' . rand(1, 999)
        ];

        foreach ($names as $name) {
            $this->createElement($name, $pictures[rand(0, count($pictures) - 1)]);
        }
    }

    function createElement($name, $picture)
    {
        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog');
        global $USER;
        $el = new \CIBlockElement;
        $paramLabelHit = [0, 7];
        $paramLabelRecomend = [0, 8];
        $paramLabelActions = [0, 9];
        $paramLabelRaspr = [0, 10];
        $arLoadProductArray = array(
            "MODIFIED_BY" => 1, // элемент изменен текущим пользователем
            "IBLOCK_ID" => $iblockId,
            "IBLOCK_SECTION_ID" => 3,
            "CODE" => Cutil::translit($name, "ru", ["replace_space" => "-", "replace_other" => "-"]),
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
                'MAX_AMPERAGE' => rand(10, 40),
                'MATERIAL' => "Полистирол",
                'CONTACT_NUMBER' => rand(1, 5),
                'CREPL' => "Защёлка",
                'LABEL_HIT' => $paramLabelHit[rand(0,1)],
                'LABEL_RECOMEND' => $paramLabelRecomend[rand(0,1)],
                'LABEL_ACTIONS' => $paramLabelActions[rand(0,1)],
                'LABEL_SALES' => $paramLabelRaspr[rand(0,1)],
                'DOCUMENT' => [
                    CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-1c.png'),
                    CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/pr-2c.png')
                ]
            ],
            "NAME" => $name,
            "PREVIEW_TEXT" => "Система молниезащиты требует расчета и составления проекта, так как переменных довольно много. Для разных зданий нужен свой подход.",
            "PREVIEW_PICTURE" => CFile::MakeFileArray($picture)
        );
        $id = $el->Add($arLoadProductArray);

        $result = \Bitrix\Catalog\Model\Product::add(
            array(
                "ID" => $id,
                "QUANTITY" => rand(0, 9999)
            )
        );
        \Bitrix\Catalog\Model\Price::add(
            [
                "PRODUCT_ID" => $result->getId(),
                "PRICE" => rand(100, 99999),
                "CURRENCY" => "RUB",

                "CATALOG_GROUP_ID" => 1
            ]
        );
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
