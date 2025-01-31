<?php
//------------- AMOCRM ----------------
use Bitrix\Main;
use Bitrix\Sale;
//------------- AMOCRM ----------------

const PERSON_TYPE_FIZ = 1;
const PERSON_TYPE_JUR = 2;
define("APPLICATION_PATH", dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR);
include_once(APPLICATION_PATH . join(DIRECTORY_SEPARATOR, array("local", "php_interface", "")) . "autoload.php");


require_once(__DIR__ . '/../classes/vendor/autoload.php');

// Получение id группы Почтовые пользователи
$userGroup = new \Meven\Helper\User();
$mailUsersGroupId = $userGroup->getUserGroupId('MAIL_INVITED');
define('MAIL_USERS_GROUP_ID', $mailUsersGroupId);

AddEventHandler("main", "OnFileSave", "OnFileSaveCustom");

function OnFileSaveCustom($arFile, $fileName, $module, $bForceMD5, $bSkipExt, $dirAdd)
{
    \Bitrix\Main\Diag\Debug::writeToFile($arFile);
}

//обработчик на событие создания списка пользовательских свойств инфоблока
AddEventHandler('iblock', 'OnIBlockPropertyBuildList', ['electroset1\UserType\CUserTypeColor', 'GetUserTypeDescription']);

AddEventHandler('catalog', 'OnSuccessCatalogImport1C', ['electroset1\EventHandlers\CatalogImport1C', 'OnSuccessCatalogImport1C']);

AddEventHandler("iblock", "OnAfterIBlockElementAdd", "SetInactive");
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "SetInactive");
AddEventHandler("main", "OnProlog", function () {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/agent_category_visibility.php");

// Регистрация агента
    if (!CAgent::GetList([], ['NAME' => 'CategoryVisibilityManager::updateCategoryVisibility();'])->Fetch()) {
        CAgent::AddAgent(
            "CategoryVisibilityManager::updateCategoryVisibility();", // имя функции
            "main", // идентификатор модуля
            "N", // выполнять только один раз (Y/N)
            86400, // интервал между запусками в секундах (24 часа = 86400 секунд)
            "", // дата первой проверки
            "Y", // активен
            "", // дата первого запуска
            100 // приоритет
        );
    }
});
function SetInactive($arFields)
{
    $iblock = new CIBlockElement();
    $arCatalogID = array(4); //каталоги товаров
// AddMessage2Log($arFields["IBLOCK_SECTION"]);
    if (in_array($arFields['IBLOCK_ID'], $arCatalogID)) {
        $arFilter = array('IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID']);
        $arSelect = array('ID', 'PROPERTY_INACTIVE');
        $arItem = CIBlockElement::GetList(false, $arFilter, false, false, $arSelect)->fetch();
        if ($arItem["PROPERTY_INACTIVE_VALUE"] == "Y" && $arFields["ACTIVE"] == 'Y') {
            $iblock->Update($arFields["ID"], array("ACTIVE" => "N"));
        }
    }
}

//------------- AMOCRM ----------------
//---------------------------------------------------------------
// Обработка фром для amocrm
//---------------------------------------------------------------

function toAmoRequest($dataReq, $url = 'https://amo.electro.dev/LeadAdd/')
{
    $name = $phone = $email = $comment = '';

    // // имя
    if (isset($dataReq['ORDER_PROP_1']))
        $name = $dataReq['ORDER_PROP_1'];
    if (isset($dataReq['ORDER_PROP_41'])) // появляется поле при выборе юр лица
        $name = $dataReq['ORDER_PROP_41'];

    // // // номер телефона
    if (isset($dataReq['PHONE']))
        $phone = $dataReq['PHONE'];
    if (isset($dataReq['ORDER_PROP_2']))
        $phone = $dataReq['ORDER_PROP_2'];
    if (isset($dataReq['ORDER_PROP_42'])) // появляется поле при выборе юр лица
        $phone = $dataReq['ORDER_PROP_42'];

    // // email
    if (isset($dataReq['ORDER_PROP_3']))
        $email = $dataReq['ORDER_PROP_3'];
    if (isset($dataReq['ORDER_PROP_43'])) // появляется поле при выборе юр лица
        $email = $dataReq['ORDER_PROP_43'];

    // // вопрос
    // if (isset($dataReq['QUESTION']))
    //     $question = $dataReq['QUESTION'];

    // // // комментарий
    // // if (isset($dataReq['PROPERTY']['108']))
    // //     $comment = $dataReq['PROPERTY']['108'][0];
    // // if (isset($dataReq['PROPERTY']['112']))
    // //     $comment = $dataReq['PROPERTY']['112'][0];

    // // товары
    // if (isset($dataReq['fields']['25']))
    //     $goods = $dataReq['fields']['25'];
    //     if (isset($dataReq['fields']['21']))
    //     $goods = $dataReq['fields']['21'];

    $data= [];
    if ($name!=="")
        $data['name'] = $name; // имя
    $data['phone'] = $phone; // телефон
    // $data['comment'] = $question; // комментарии
    if ($email!=="")
        $data['email'] = $email; // email
    if (isset($dataReq['goods']))
        $data['goods'] = $dataReq['goods']; // товары
    // if (isset($dataReq['ORDER_PROP_21']))
    //     $data['id_order'] = $dataReq['ORDER_PROP_21']; // id заказа в интернет-магазине
    if (isset($dataReq['id_order']))
        $data['id_order'] = $dataReq['id_order']; // id заказа в интернет-магазине
    if (isset($dataReq['addr']))
        $data['addr_dostavki'] = $dataReq['addr']; // адрес доставки
    if (isset($dataReq['sposob_dostavki']))
        $data['sposob_dostavki'] = $dataReq['sposob_dostavki']; // адрес доставки
    if (isset($dataReq['comments']))
        $data['comments'] = $dataReq['comments']; // комментарии к заказу
    if (isset($dataReq['summa_zakaza']))
        $data['summa_zakaza'] = $dataReq['summa_zakaza']; // сумма заказа
    if (isset($dataReq['summa_dostavki']))
        $data['summa_dostavki'] = $dataReq['summa_dostavki']; // сумма доставки

    // организация
    if (isset($dataReq['org_name']))
        $data['org_name'] = $dataReq['org_name']; // название организации
    if (isset($dataReq['inn']))
        $data['inn'] = $dataReq['inn']; // ИНН
    if (isset($dataReq['kpp']))
        $data['kpp'] = $dataReq['kpp']; // КПП
    if (isset($dataReq['ORDER_PROP_40']))
        $data['org_addr'] = $dataReq['ORDER_PROP_40']; // адрес организации

    // $data['code'] = 'GAXCxHVWQKMVy7ex';
    // $data['siteName'] = 'makedon.pro';
    // $data['goods'] = $goods;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Указываем, что у нас POST запрос
    curl_setopt($ch, CURLOPT_POST, 1);
    // Добавляем переменные
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $output = curl_exec($ch);
    // sleep(2000); // пауза в 5 сек, чтобы другой срипт прокинул раньше информации о клиенте на сервер (utm метки и так далее)
    curl_close($ch);

    $testUrl = 'https://eo60aqt5v2hcx1x.m.pipedream.net';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $testUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Указываем, что у нас POST запрос
    curl_setopt($ch, CURLOPT_POST, 1);
    // Добавляем переменные
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataReq));
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataReq);
    $output = curl_exec($ch);
    // sleep(2000); // пауза в 5 сек, чтобы другой срипт прокинул раньше информации о клиенте на сервер (utm метки и так далее)
    curl_close($ch);

    // $testUrl = 'https://nfdgjh457t4.free.beeceptor.com';
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $testUrl);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // // Указываем, что у нас POST запрос
    // curl_setopt($ch, CURLOPT_POST, 1);
    // // Добавляем переменные
    // // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataReq));
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $output);
    // $output = curl_exec($ch);
    // // sleep(2000); // пауза в 5 сек, чтобы другой срипт прокинул раньше информации о клиенте на сервер (utm метки и так далее)
    // curl_close($ch);
}

if (isset($_REQUEST['PHONE'])) // если есть телефон
     toAmoRequest($_REQUEST); // отправляем данные

// if (isset($_REQUEST['ORDER_PROP_2'])) // если есть телефон
//      toAmoRequest($_REQUEST); // отправляем данные

/* создаём обработчик событий создания заказа */
Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderSaved', /* указываем нашу функцию */
    'myFunction'
);

Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSalePaymentPaid', /* указываем нашу функцию */
    'myFunctionPayed'
);

// Автоматическое изменения статуса заказа при полной оплате
// Main\EventManager::getInstance()->addEventHandler(
//     'sale', 'OnSalePaymentPaid', function(Main\Event $event) {
//         // $payment = $event->getParameter("ENTITY");
//         // $order = $payment->getOrder();
//         // if ($order->isPaid()) {
//         //     $order->setField('STATUS_ID', 'P'); // Меняем статус на «Оплачен»
//         //     $order->save();
//         // }

//         $testUrl = 'https://eo60aqt5v2hcx1x.m.pipedream.net';
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, $testUrl);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         // Указываем, что у нас POST запрос
//         curl_setopt($ch, CURLOPT_POST, 1);
//         // Добавляем переменные
//         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($_REQUEST));
//         // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataReq);
//         $output = curl_exec($ch);
//         // sleep(2000); // пауза в 5 сек, чтобы другой срипт прокинул раньше информации о клиенте на сервер (utm метки и так далее)
//         curl_close($ch);
//     }
// )

function myFunctionPayed(Main\Event $event)
{
    /* здесь мы будем прописывать основные моменты */
    $order = $event->getParameter("ENTITY");

    /* получаем id созданного заказа */
    $idOrder = $order->getId();
    $data=[];
    $data['id_order']= (string) $idOrder;

     $url = 'https://amo.electro.dev/LeadUpdate/';
    // $testUrl = 'https://eo60aqt5v2hcx1x.m.pipedream.net';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Указываем, что у нас POST запрос
        curl_setopt($ch, CURLOPT_POST, 1);
        // Добавляем переменные
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataReq);
        $output = curl_exec($ch);
        // sleep(2000); // пауза в 5 сек, чтобы другой срипт прокинул раньше информации о клиенте на сервер (utm метки и так далее)
        curl_close($ch);
}


/* функция которая будет запускаться после создания заказа */
function myFunction(Main\Event $event)
{

    $order = $event->getParameter("ENTITY");

    /* цена */
    $price = $order->getPrice();
    /* дата создания */
    $dateOrder = $order->getDateInsert();
    $idOrder = $order->getId();

    /* делаем запрос и вытаскиваем информацию по id заказа */
    /* $idOrder - ранее полученный идентификатор заказа */
    $order = \Bitrix\Sale\Order::load($idOrder);
    $summa_zakaza= $order->getPrice();
    $summa_dostavki= $order->getDeliveryPrice();  // стоимость доставки

    /* проверяем есть ли такой заказ */
    if (!empty($order)) {
        /* получаем информацию о полях "Свойства заказа" */
        $tradeBindingCollection = $order->getTradeBindingCollection();


        /* получаем значение свойства "Источник заказа" */
        //    foreach ($tradeBindingCollection as $item) {

        //        /* записываем id источника заказа в переменную $tpId */
        //     //    $tpId = $item->getField('TRADING_PLATFORM_ID');
        //    }

        /* проверяем "это турбо-заказ"? */
        //    if($tpId == 2) {
        //    ...
        //    }

        $basket = $order->getBasket();
        $basketItems = $basket->getBasketItems();
        $basketStr = '';
        $eol = `
        `;

        $data = $_REQUEST;

        /* пробегаемся по значениям корзины и записываем информацию о товарах */
        $i = 0;
        $basket= []; // корзина
        foreach ($basketItems as $basketItem) {
            // $basketPropertyCollection = $basketItem->getPropertyCollection();

            if (strlen($basketStr) > 0) $basketStr .= ', ';
            $basketStr .= $basketItem->getField('NAME') . " " . $basketItem->getQuantity() . "шт. " . $basketItem->getPrice() . ' р.' . $eol;

            $basket[]=[
                'name'=>$basketItem->getField('NAME') ,
                'price'=>(int) $basketItem->getPrice(),
                'quantity'=>(int) $basketItem->getQuantity(),
                'summ'=>$basketItem->getPrice() * $basketItem->getQuantity()
                // 'props'=>$basketPropertyCollection,
            ];

            // $arrInfoBasket[$i]["NAME"] = $basketItem->getField('NAME') . " " . $basketItem->getQuantity() . "шт.";
            // $arrInfoBasket[$i]["PRICE"] = $basketItem->getPrice();

            //foreach ($basketPropertyCollection->getPropertyValues() as $key => $prop) {
            $propertyCollection = $order->getPropertyCollection();
            foreach ($propertyCollection as $propertyItem){
                // $arrInfoBasket[$i]["PROP"][$key]["NAME"] = $prop["NAME"];
                // $arrInfoBasket[$i]["PROP"][$key]["VALUE"] = $prop["VALUE"];

                switch($propertyItem->getField("CODE")){
                    case "STREET":
                        $data["addr"] = $propertyItem->getValue();
                        break;
                    case "ORGNAME":
                        $data["org_name"] = (string)$propertyItem->getValue();
                        break;
                    case "INN":
                        $data["inn"] = (string)$propertyItem->getValue();
                        break;
                    case "KPP":
                        $data["kpp"] = (string)$propertyItem->getValue();
                        break;
                    default:
                };

            }
            $i++;
        }

        // sendOrderRedAmoTest([
        //     'strOrder' => $basketStr
        // ]);

        $val = $_REQUEST['DELIVERY_ID'];
        $data["sposob_dostavki"] = (string)$val;
        // доставка физ лица
        if ($val==6 )
            $data["sposob_dostavki"] = "Почта России Доставка курьером";
        if ($val==43 )
            $data["sposob_dostavki"] = "Доставка курьером (СДЭК)";
        if ($val==48 )
            $data["sposob_dostavki"] = "Доставка до двери (DPD)";
        // доставка юр лица
        if ($val==41 )
            $data["sposob_dostavki"] = "Доставка транспортной компанией";
        // варианты самовывоза
        if ($val==5 )
            $data["sposob_dostavki"] = "Из отделения Почты России";
        if ($val==44 )
            $data["sposob_dostavki"] = "Самовывоз из пункта выдачи СДЭК";
        if ($val==47 )
            $data["sposob_dostavki"] = "Доставка до пункта выдачи (DPD)";

        if (isset($_REQUEST["ORDER_DESCRIPTION"]))
            $data["comments"] = $_REQUEST["ORDER_DESCRIPTION"]; // комментарии к заказу

        $data['goods'] = $basket;
        $data['basketStr'] = $basketStr;
        $data['id_order'] = (string) $idOrder;
        $data['summa_zakaza'] = (int) $summa_zakaza;
        $data['summa_dostavki'] = (int) $summa_dostavki;
        toAmoRequest($data);
        // sendOrderRedAmoTest($data);

        /* создаём массив названий свойств которые нам НЕ НУЖНЫ*/
        // $arrException = [
        //     "CATALOG.XML_ID",
        //     "PRODUCT.XML_ID"
        // ];

        // /* создаём строку с перечисленными товара которые участвуют в заказе */
        // $descProduct .= "ОПИСАНИЕ ЗАКАЗА \n";

        // foreach ($arrInfoBasket as $product) {
        //     $descProduct .= $product["NAME"] . "\n";
        //     $descProduct .= "Цена - " . $product["PRICE"] . "р \n";
        //     foreach ($product["PROP"] as $key => $prop) {
        //         /* проверяем не находится ли это свойство в исключение */
        //         if (!in_array($key, $arrException)) {
        //             $descProduct .= $prop["NAME"] . " - " . $prop["VALUE"] . "\n";
        //         }
        //     }
        //     $descProduct .= "\n";
        // }

        /* ПОЛУЧАЕМ ИНФОРМАЦИЮ ИЗ ПОЛЕЙ ЗАЯВКИ */
        // $propertyCollection = $order->getPropertyCollection();
        // $arrFields = $propertyCollection->getArray();

        // foreach ($arrFields["properties"] as $field) {
        //     if ($field["VALUE"][0]) {
        //         $strInfoFields .= $field["NAME"] . " - " . $field["VALUE"][0] . "\n";
        //     }
        // }
    } // order
}

// Заказв  1 клик
if (isset($_REQUEST['ONE_CLICK_BUY'])) // если есть номер телеофна
    toAmoRequest($_REQUEST);

//---------------------------------------------------------------
// amocrm
//---------------------------------------------------------------
//------------- AMOCRM ----------------