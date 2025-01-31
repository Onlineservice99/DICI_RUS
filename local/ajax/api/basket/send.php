<?php

use Bitrix\Sale;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
\Bitrix\Main\Loader::includeModule('sale');
$email = $request->get('email');

$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());

if (!CSalePdf::isPdfAvailable() || $basket->count() == 0) {
    die;
}

$elementsIds = [];
foreach ($basket as $basketItem) {
    $elementsIds[] = $basketItem->getField('PRODUCT_ID');
}

$elements = [];
$res = \CIBlockElement::GetList(
    [],
    [
        'IBLOCK_ID' => \Bitrix\Main\Config\Option::get('meven.info', 'iblock_catalog'),
        'ID' => $elementsIds
    ],
    false,
    false,
    ['ID', 'IBLOCK_ID', 'PREVIEW_PICTURE', 'PROPERTY_ARTICLE']
);
while ($element = $res->Fetch()) {
    $elements[$element['ID']] = $element;
}

$arCurFormat = CCurrencyLang::GetCurrencyFormat(\Bitrix\Currency\CurrencyManager::getBaseCurrency());
$currency = trim(str_replace('#', '', strip_tags($arCurFormat['FORMAT_STRING'])));


$pdf = new CSalePdf('P', 'pt', 'A4');
$pageWidth  = $pdf->GetPageWidth();
$pageHeight = $pdf->GetPageHeight();

$pdf->AddFont('Font', '', 'pt_sans-regular.ttf', true);
$pdf->AddFont('Font', 'B', 'pt_sans-bold.ttf', true);

$pdf->SetDisplayMode(100, 'continuous');
$pdf->SetMargins(30, 40, 30);
$pdf->SetAutoPageBreak(true, 40);

$pdf->AddPage();

$pdf->SetFont('Font', 'B', 18);
$pdf->Write(15, CSalePdf::prepareToPdf('Ваша корзина'));
$pdf->Ln();
$pdf->Ln();

$x1 = 30;
$x2 = 80;
$x3 = 340;
$x4 = 420;
$x5 = 500;

// TH
$pdf->SetFont('Font', '', 12);
$pdf->SetX($x1);
$pdf->Write(20, CSalePdf::prepareToPdf('Код'));
$pdf->SetX($x2);
$pdf->Write(20, CSalePdf::prepareToPdf('Бренд и наименование'));
$pdf->SetX($x3);
$pdf->Write(20, CSalePdf::prepareToPdf('Цена'));
$pdf->SetX($x4);
$pdf->Write(20, CSalePdf::prepareToPdf('Количество'));
$pdf->SetX($x5);
$pdf->Write(20, CSalePdf::prepareToPdf('Стоимость'));
$pdf->Ln();
$pdf->Line(30, $pdf->getY(), $pageWidth - 30, $pdf->getY());


//TR
foreach ($basket as $basketItem) {
    $element = $elements[$basketItem->getField('PRODUCT_ID')] ?? false;
    if (!$element) {
        continue;
    }

    $pdf->SetX($x1);
    $pdf->Write(20, CSalePdf::prepareToPdf($element['PROPERTY_ARTICLE_VALUE']));

    $pdf->SetX($x2);
    $y = $pdf->GetY();
    $name = $basketItem->getField('NAME');
    while ($pdf->GetStringWidth($name) > 0) {
        list($string, $name) = $pdf->splitString($name, $x3 - $x2 - 10);
        
        $pdf->Write(20, CSalePdf::prepareToPdf($string));

        if ($name) {
            $pdf->Ln();
            $pdf->SetX($x2);
        }
    }
    $y2 = $pdf->GetY();
    $pdf->SetY($y);

    $pdf->SetX($x3);
    $pdf->Write(20, CSalePdf::prepareToPdf($basketItem->getField('PRICE') . ' ' . $currency));

    $pdf->SetX($x4);
    $pdf->Write(20, CSalePdf::prepareToPdf(round($basketItem->getField('QUANTITY'), 2)));

    $pdf->SetX($x5);
    $pdf->Write(20, CSalePdf::prepareToPdf(($basketItem->getField('PRICE') * $basketItem->getField('QUANTITY')) . ' ' . $currency));

    $pdf->SetY($y2);
    $pdf->Ln();
    $pdf->Line(30, $pdf->getY(), $pageWidth - 30, $pdf->getY());
}

$pdf->Ln();
$pdf->SetFont('Font', 'B', 18);
$pdf->Write(15, CSalePdf::prepareToPdf('Итого ' . $basket->getPrice() . ' ' . $currency));
$pdf->Ln();

$filename = 'basket_'.md5($email.time()).'.pdf';
$data = $pdf->Output($filename, 'S');

if ($data) {
    $filenameSrc = $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $filename;
    file_put_contents($filenameSrc, $data);
    \Bitrix\Main\Mail\Event::send([
        'EVENT_NAME' => 'SEND_BASKET',
        'LID' => SITE_ID,
        'C_FIELDS' => [
            'EMAIL' => $email,
        ],
        'FILE' => [$filenameSrc],
    ]);
    unlink($filenameSrc);
}

\Meven\Helper\Json::dumpSuccess(['success' => true]);