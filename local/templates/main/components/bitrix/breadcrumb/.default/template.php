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

$strReturn .= '<nav class="breadcrumbs">';
$itemSize = count($arResult);
if (stripos($APPLICATION->GetCurDir(), '/personal/') !== false) {
    $itemSize = 0;
    $strReturn .= '
					<a href="/" title="Эстетика-PRO" itemprop="url"><span itemprop="title">Эстетика-PRO</span></a>
					<span class="separet"> / </span>  
					<span>Личный кабинет</span>
			';
}
for($index = 0; $index < $itemSize; $index++){
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);

	$nextRef = ($index < $itemSize-2 && $arResult[$index+1]["LINK"] <> ""? ' itemref="bx_breadcrumb_'.($index+1).'"' : '');
	$child = ($index > 0? ' itemprop="child"' : '');
		$arrow = ($index > 0? '<span class="separet"> / </span>  ' : '');
	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1){
		$strReturn .= ''.$arrow.'
				<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" itemprop="url">
					<span itemprop="title">'.$title.'</span>
				</a>';
	}else{
		$strReturn .= ''.$arrow.'
				<span>'.$title.'</span>
			';
	}
}

$strReturn .= '</nav>';

return $strReturn;
