<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach ($arResult['ITEMS'] as &$items) {
	 $itemID = $items['PROPERTIES']['NAME_COURSE']['VALUE']; 
	 $arFilter = Array(
		"IBLOCK_ID" => 2,
		"ACTIVE_DATE"=>"Y",
		"ACTIVE"=>"Y",
		"ID" => $itemID
	);
	$resitem = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	$counter = 0;
	if($item = $resitem->GetNextElement()){
		$arFields = $item->GetFields();
		$arProperties = $item->GetProperties();
		$items['INSTR'] = $arProperties['INSTR'];
		$items['PROF_INSTR'] = $arProperties['PROF_INSTR'];
		$items['FOTO_INSTR'] = $arProperties['FOTO_INSTR'];
		$items['TYPE_COURSE'] = $arProperties['TYPE_COURSE'];
		$items['NAME_COURSE'] = $arFields['NAME'];
	}
	$res = CIBlockSection::GetByID($items['IBLOCK_SECTION_ID']);
	$ar_res = $res->GetNext();
}

$resLash = CIBlockSection::GetList(Array('SORT' => 'asc'), Array('IBLOCK_ID' =>'31' , 'ACTIVE' => 'Y','SECTION_ID' =>'1601' ));
while ($resultLash = $resLash->GetNext()){
	$elements = [];
	foreach($arResult['ITEMS'] as $item) {
		if ($item['IBLOCK_SECTION_ID'] == $resultLash['ID'])
			$elements[] = $item;
	}
	$resultLash['ELEMENTITEMS'] = $elements;
	$arResultLash[] =  $resultLash;
}
$arResult['SECTIONS1601'] = $arResultLash;

$resNail = CIBlockSection::GetList(Array('SORT' => 'asc'), Array('IBLOCK_ID' =>'31' , 'ACTIVE' => 'Y','SECTION_ID' =>'1602' ));
while ($resultNail = $resNail->GetNext()){
	$elements = [];
	foreach($arResult['ITEMS'] as $item) {
		if ($item['IBLOCK_SECTION_ID'] == $resultNail['ID'])
			$elements[] = $item;
	}
	$resultNail['ELEMENTITEMS'] = $elements;
	$arResultNail[] =  $resultNail;
}
$arResult['SECTIONS1602'] = $arResultNail;







