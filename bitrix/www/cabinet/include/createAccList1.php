<?php
/**
 * Created by PhpStorm.
 * User: leonidmolcanov
 * Date: 18/02/2019
 * Time: 02:31
 */

echo json_encode($_REQUEST['data']);

$arr=json_decode($_REQUEST['data']);

foreach ($arr as $item){
$el = new CIBlockElement;

$PROP = array();
$PROP['STATUS'] = 0;  // свойству с кодом 12 присваиваем значение "Белый"
$PROP['PROFILE'] = $_REQUEST['iblockid'];        // свойству с кодом 3 присваиваем значение 38

$arLoadProductArray = Array(
    "MODIFIED_BY"    =>1, // элемент изменен текущим пользователем
    "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
    "IBLOCK_ID"      => 7,
    "PROPERTY_VALUES"=> $PROP,
    "NAME"           => $item,
    "ACTIVE"         => "Y"
);

if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
}
}
?>
