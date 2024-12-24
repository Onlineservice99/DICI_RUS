<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '';

//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()

$strReturn .= '<div class="breadcrumbs">';

$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .=  $arrow.'
			<div class="breadcrumbs__item" id="bx_breadcrumb_'.$index.'">
				<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'">'.$title.'</a>
			</div>';
	}
	else
	{
		$strReturn .= $arrow.'
			<div class="breadcrumbs__item">'.$title.'</div>';
	}
}

$strReturn .= '</div>';

return $strReturn;
