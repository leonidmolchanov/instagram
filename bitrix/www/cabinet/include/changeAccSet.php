<?
$el = new CIBlockElement;

$PROP = array();
$PROP['SSID'] = $_REQUEST['accSsid'];  // свойству с кодом 12 присваиваем значение "Белый"
$PROP['ID'] = $_REQUEST['accId'];  // свойству с кодом 12 присваиваем значение "Белый"
$PROP['CSRFTOKEN'] = $_REQUEST['accCsrftocken'];  // свойству с кодом 12 присваиваем значение "Белый"
$arLoadProductArray = Array(
    "MODIFIED_BY"    => 1, // элемент изменен текущим пользователем
    "IBLOCK_SECTION" => false,          // элемент лежит в корне раздела
    "PROPERTY_VALUES"=> $PROP,
    "NAME"           => $_REQUEST['accName'],
    "ACTIVE"         => "Y"            // активен
);

$PRODUCT_ID = $_REQUEST['blockId'];  // изменяем элемент с кодом (ID) 2
if($res = $el->Update($PRODUCT_ID, $arLoadProductArray)):

else:
    header('x', true, 404);

endif;
?>