<?php
use Bitrix\Sale;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::includeModule('sale');

\CSaleBasket::DeleteAll(\Bitrix\Sale\Fuser::getId());