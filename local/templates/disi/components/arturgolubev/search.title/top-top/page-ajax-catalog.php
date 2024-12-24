<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
	"arturgolubev:search.title", 
	"top-top", 
	array(
		"COMPONENT_TEMPLATE" => "top-top",
		"NUM_CATEGORIES" => "1",
		"TOP_COUNT" => "5",
		"ORDER" => "rank",
		"USE_LANGUAGE_GUESS" => "Y",
		"CHECK_DATES" => "N",
		"SHOW_OTHERS" => "N",
		"INPUT_ID" => "title-search-input2",
		"CONTAINER_ID" => "title-search2",
		"PAGE" => "/search/",
		"CATEGORY_0_TITLE" => "",
		"CATEGORY_0" => array(
			0 => "no",
		)
	),
	false
);