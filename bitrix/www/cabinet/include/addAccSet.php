<?
$el = new CIBlockElement;

$PROP = array();
$PROP['SSID'] = $_REQUEST['accSsid'];  // свойству с кодом 12 присваиваем значение "Белый"
$PROP['ID'] = $_REQUEST['accId'];  // свойству с кодом 12 присваиваем значение "Белый"
$PROP['CSRFTOKEN'] = $_REQUEST['accCsrftocken'];  //

$arLoadProductArray = Array(
    "MODIFIED_BY"    => 1, // элемент изменен текущим пользователем
    "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
    "IBLOCK_ID"      => 5,
    "PROPERTY_VALUES"=> $PROP,
    "NAME"           => $_REQUEST['accName'],
    "ACTIVE"         => "Y"
);

if($PRODUCT_ID = $el->Add($arLoadProductArray)):

else:
    header('x', true, 404);
endif;
?>