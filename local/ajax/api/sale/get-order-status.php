<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$orderId = $request->getPost('order-id');
$order = \Meven\Sale\GetOrderStatus::getInstance();

if(!empty($orderId)){
    $orderInfo = $order->getOrderStatus($orderId);
    echo json_encode($orderInfo);
} else {
    \Meven\Helper\Json::dumpErrors(['Заказ не найден']);
}