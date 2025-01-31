<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddElementsPromotions20210422143741006572 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_promotions');

        global $USER;
        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "К конвекторам ENSTO - ножки в подарок!",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/sale-1.jpg'),
            "PREVIEW_TEXT" => "Приятная новость для покупателей: приобретая сейчас конвекторы Ensto, вы можете получить в подарок ультрапрочные ножки – удобный аксессуар для мобильности обогревателя*. Конвекторы «Энсто» выпускаются в нескольких вариантах – выбирайте модель в зависимости от мощности и площади комнаты."
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "Скидка на розетки и выключатели Avanti",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/sale-2.jpg'),
            "PREVIEW_TEXT" => "С 15 декабря 2020 г. по 15 января 2021 г. на сайте «Минимакс» и в магазинах «Электрик» проводится акция для розничных покупателей. На самые востребованные розетки, выключатели, диммеры серии Avanti, а также на многие сопутствующие товары этой марки мы СНИЗИЛИ ЦЕНЫ НА 15 ПРОЦЕНТОВ!"
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "«Тройные бонусы» от Legrand",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/sale-3.jpg'),
            "PREVIEW_TEXT" => "А вы любите делать выгодные покупки? Воспользуйтесь специальным предложением: ПОЛУЧАЙТЕ 15% БОНУСАМИ на вашу карту лояльности «Минимакс» при покупке товаров Legrand. Это тройная выгода, ведь обычный бонус постоянного клиента составляет 5%."
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "Покупайте в «ДиСи» по карте Халва",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/sale-4.jpg'),
            "PREVIEW_TEXT" => "С Халвой можно приобретать товары в рассрочку на 3 месяца, причем по нулевой процентной ставке. Компания «ДиСи электротехника» стала участников партнерской программы Халва и теперь наши покупатели могут также воспользоваться ее преимуществами."
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "Акция на светотехнику и лампы LEDVANCE и OSRAM",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/sale-5.jpg'),
            "PREVIEW_TEXT" => "Уважаемые покупатели! Совместно с производителем электротоваров LEDVANCE мы проводим акцию: при покупке светодиодных светильников LEDVANCE и любых ламп OSRAM участникам программы лояльности* начисляются увеличенные бонусы..."
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"      => $iblockId,
            "NAME"           => "Дарим 5-ю светодиодную лампу Philips всем, кто купит 4",
            "PREVIEW_PICTURE"=> CFile::MakeFileArray('https://realize-project-as.gitlab.io/disi/img/sale-6.jpg'),
            "PREVIEW_TEXT" => "Темнеет намного раньше, и мы точно знаем, что это надолго. Переживите «темные времена» время с ярким и экономичным освещением от Philips: сейчас можно купить светодиодные лампы по выгодной цене 99 руб. и с дополнительным подарком!"
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
