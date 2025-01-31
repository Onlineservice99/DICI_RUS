<?php include_once $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php';
$result = [];
$items = ( !is_null($_SESSION['COMPARE_LIST']) ) ? reset($_SESSION['COMPARE_LIST']) : [];

if (count($items['ITEMS']) > 0) {
    foreach ($items['ITEMS'] as $item) {
        $result[] = $item['ID'];
    }
}

if (!empty($result)) {
    \Meven\Helper\Json::dumpSuccess($result);
} else {
    \Meven\Helper\Json::dumpErrors(['error']);
}
