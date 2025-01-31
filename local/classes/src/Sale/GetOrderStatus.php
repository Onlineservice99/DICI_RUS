<?php
namespace Meven\Sale;
use \Bitrix\Main\Service\GeoIp\Manager;
use \Bitrix\Main\Web\Cookie;
use \Bitrix\Main\Application;

class GetOrderStatus
{
    private static $instance = null;

    public function __construct()
    {

    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function getOrderStatus($orderId):array
    {
        \Bitrix\Main\Loader::includeModule('sale');

        $order = \Bitrix\Sale\Order::load($orderId);

        // Заказ
        $orderDate = $order->getField('DATE_INSERT');
        $orderStatusId = $order->getField('STATUS_ID');

        // Отгрузка
        $orderDeliveryAllow = $order->getField('ALLOW_DELIVERY');
        $orderDeliveryAllowDate = $order->getField('ALLOW_DELIVERY_DATE');

        // Оплата
        $orderPayed = $order->getField('PAYED');
        $orderPayedDate = $order->getField('PAYED_DATE');

        // Форматирование даты заказа
        if ($stmpOrder = MakeTimeStamp($orderDate, "DD.MM.YYYY HH:MI")) {
            $orderDateFormatted = date("d.m.Y H:i", $stmpOrder);
        }

        // Форматирование даты отгрузки
        if ($stmpDeliveryAllow = MakeTimeStamp($orderDeliveryAllowDate, "DD.MM.YYYY HH:MI")) {
            $orderDeliveryAllowDateFormatted = date("d.m.Y H:i", $stmpDeliveryAllow);
        }

        // Форматирование даты оплаты
        if ($stmpPayed = MakeTimeStamp($orderPayedDate, "DD.MM.YYYY HH:MI")) {
            $orderPayedDateFormatted = date("d.m.Y H:i", $stmpPayed);
        }

        // Получение названия статуса заказа по ID
        $statusResult = \Bitrix\Sale\Internals\StatusLangTable::getList([
            'order' => ['STATUS.SORT' => 'ASC'],
            'filter' => ['STATUS_ID' => $orderStatusId, 'LID' => LANGUAGE_ID],
            'select' => ['STATUS_ID', 'NAME', 'DESCRIPTION'],
        ]);

        if($arStatus = $statusResult->fetch()) {
            $orderStatusName = $arStatus['NAME'];
        }

        if($orderStatusId === 'F'){
            $orderStatusColorClass = 'green';
        }else{
            $orderStatusColorClass = 'violet';
        }

        if($orderDeliveryAllow == 'Y') {
            $orderDeliveryStatus = "Доставка разрешена";
            $orderDeliveryColorClass = "green";
        } else {
            $orderDeliveryStatus = "В обработке";
            $orderDeliveryColorClass = "yellow";
        }

        if($orderPayed == 'Y') {
            $orderPaymentStatus = "Заказ оплачен";
            $orderPaymentColorClass = "green";
        } else {
            $orderPaymentStatus = "Заказ не оплачен";
            $orderPaymentColorClass = "red";
        }

        // Сбор данных в массив
        $orderInfo = [
            'order_status' => $orderStatusName,
            'order_date' => $orderDateFormatted,
            'order_delivery_status' => $orderDeliveryStatus,
            'order_delivery_allow_date' => $orderDeliveryAllowDateFormatted,
            'order_payment_status' => $orderPaymentStatus,
            'order_payed_date' => $orderPayedDateFormatted,
            'order_status_color' => $orderStatusColorClass,
            'order_delivery_color' => $orderDeliveryColorClass,
            'order_payment_color' => $orderPaymentColorClass
        ];

        return $orderInfo;
    }
}