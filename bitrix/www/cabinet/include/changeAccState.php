<?php
/**
 * Created by PhpStorm.
 * User: leonidmolcanov
 * Date: 19/02/2019
 * Time: 01:12
 */

$el = new CIBlockElement;

if($_REQUEST['proc']=='start'){
    $status=1;
}
else{
    $status=0;
}
$PROP = array();
$PROP["STATUS"] = $status;  // свойству с кодом 12 присваиваем значение "Белый"
        // свойству с кодом 3 присваиваем значение 38

$arLoadProductArray = Array(
    "MODIFIED_BY"    => 1, // элемент изменен текущим пользователем
    "IBLOCK_SECTION" => false,          // элемент лежит в корне раздела
    "PROPERTY_VALUES"=> $PROP,
    "ACTIVE"         => "Y"
);
$res = $el->Update($_REQUEST['iblockid'], $arLoadProductArray);