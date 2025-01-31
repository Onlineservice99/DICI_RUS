<?php
namespace Meven\Events;

use Bitrix\Main,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Bitrix\Main\Config\Option,
    Bitrix\Main\IO\File,
    Bitrix\Main\Page\Asset,
    Bitrix\Main\Diag\Debug,
    Bitrix\Main\Type\Date,
    Bitrix\Catalog\Model\Product,
    Bitrix\Catalog\Model\Price;

if(!defined('MEVEN_MODULE_ID'))
    define('MEVEN_MODULE_ID', 'meven.info');

Loc::loadMessages(__FILE__);

class Form {

    // Сообщение после заполнения формы Получить скидку
    public static function SendDiscount(&$arFields)
    {
        $formId = \Bitrix\Main\Config\Option::get('meven.info', 'iblock_form_get_discount');
        \CEvent::Send("MIDOW_SEND_FORM_".$formId, SITE_ID, false, false);
    }

}