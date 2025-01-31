<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateElementBannersCatalog20210902140550235932 extends BitrixMigration
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
        $iblockId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_banners_catalog');
        $arParams = array("replace_space"=>"-","replace_other"=>"-");

        $prop = new \Arrilot\BitrixMigrations\Constructors\IBlockProperty();
        $prop->constructDefault('NEED_AUTH', 'Для не авторизованных', $iblockId);
        $prop->setPropertyTypeList(['Y'], 'C');
        $idProp = $prop->add();

        $valueY = $this->getPropertiesEnum($idProp);

        $el = new \CIBlockElement;
        $name = "Зарегистрируйтесь, чтобы видеть оптовые цены";
        $arLoadProductArray = Array(
            "MODIFIED_BY"   => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"     => $iblockId,
            "SORT" => "100",
            "NAME" => $name,
            "CODE" => \Cutil::translit($name,"ru", $arParams),
            "PREVIEW_TEXT"  => "",
            "DETAIL_TEXT"   => '<div class="banner__left">
                                    <h3 class="mb-16">Зарегистрируйтесь, чтобы видеть оптовые цены</h3>
                                    <div class="banner__text mb-24 mb-lg-32">Получите больше функционала интернет-магазина, например список отложенных товаров.</div>
                                    <a class="btn btn--red px-44 col-12 col-sm-auto" href="/local/ajax/popups/auth.php" data-fancybox="" data-touch="false" data-type="ajax" data-close-existing="true">Зарегистрироваться</a>
                                </div>',
            "DETAIL_TEXT_TYPE"   => "html",
            "PREVIEW_PICTURE"  => CFile::MakeFileArray("https://realize-project-as.gitlab.io/disi/img/banner-reg.jpg"),
            "DETAIL_PICTURE"   => "",
            "PROPERTY_VALUES" => [
                'NEED_AUTH' => $valueY,
                'ADD_CLASS' => 'banner banner--reg',
            ]
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $name = "Поиск";
        $arLoadProductArray = Array(
            "MODIFIED_BY"   => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"     => $iblockId,
            "SORT" => "200",
            "NAME" => $name,
            "CODE" => \Cutil::translit($name,"ru", $arParams),
            "PREVIEW_TEXT"  => "",
            "DETAIL_TEXT"   => '<h3 class="mb-16">Заблудились в каталоге?</h3>
                                <div class="mb-32">Воспользуйтесь нашей уникальной системой поиска - это очень удобно!</div>
                                <div class="form-block">
                                    <input class="form-block__input form-block__input--search" name="q">
                                </div>',
            "DETAIL_TEXT_TYPE"   => "html",
            "PREVIEW_PICTURE"  => CFile::MakeFileArray("https://realize-project-as.gitlab.io/disi/img/banner-reg.jpg"),
            "DETAIL_PICTURE"   => "",
        );
        $el->Add($arLoadProductArray);

        $el = new \CIBlockElement;
        $name = "Не нашли, что хотели?";
        $arLoadProductArray = Array(
            "MODIFIED_BY"   => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_ID"     => $iblockId,
            "SORT" => "300",
            "NAME" => $name,
            "CODE" => \Cutil::translit($name,"ru", $arParams),
            "PREVIEW_TEXT"  => "",
            "DETAIL_TEXT"   => '<div class="banner__content">
                                    <h2 class="mb-20">Не нашли, что хотели?</h2>
                                    <div class="h3">Спросите у консультанта</div>
                                    <div class="h4 mb-32">Мы скоро перезвоним</div>
                                    <a class="btn btn--red"  href="/local/ajax/popups/callback.php" data-fancybox="" data-touch="false" data-type="ajax">Заказать звонок</a>
                                </div>',
            "DETAIL_TEXT_TYPE"   => "html",
            "PREVIEW_PICTURE"  => CFile::MakeFileArray("https://realize-project-as.gitlab.io/disi/img/banner-reg.jpg"),
            "DETAIL_PICTURE"   => "",
            "PROPERTY_VALUES" => [
                'ADD_CLASS' => 'banner banner--call',
            ]
        );
        $el->Add($arLoadProductArray);
    }

    public function getPropertiesEnum($id)
    {
        $result = [];
        $resultDb = \Bitrix\Iblock\PropertyEnumerationTable::getList(
            [
                'filter' => ['=PROPERTY_ID' => $id],
                'cache' => [
                    'ttl' => 726000,
                    'cache_joins' => true,
                ],
                'select' => [
                    'VALUE',
                    'ID'
                ]
            ]
        );

        while ($value = $resultDb->fetch()) {
            $result[$value['VALUE']] = $value['ID'];
        }

        return $result['Y'];
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
