<?	$count = 0;
	
	foreach ($arResult['BASKET'] as $basketItem){$count++;}
	$titles = array(' товар', ' товара', ' товаров');
	/*function declOfNum($count, $titles){
		$cases = array (2, 0, 1, 1, 1, 2);
		$format = $titles[ ($count%100 > 4 && $count %100 < 20) ? 2 : $cases[min($count%10, 5)] ];
		return sprintf($format, $count);
	}*/
	$text = declOfNum($count, $titles);
	$count = $text;
	$title = ["TITLE_COUNT" => $count];
	$arResult = $arResult + $title;
?>