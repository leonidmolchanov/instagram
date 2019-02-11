<?
$accountsArr = json_decode($_REQUEST['accountsArr']);
$PROPERTY_VALUE = 0 ;  // значение свойства
$PROPERTY_CODE = "MAIN";  // код свойства
foreach ($accountsArr as &$value) {
    CIBlockElement::SetPropertyValuesEx((int)$value, false, array($PROPERTY_CODE => $PROPERTY_VALUE));
}
$PROPERTY_VALUE = 1 ;  // значение свойства

$ELEMENT_ID = (int)$_REQUEST['blockId'];
CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($PROPERTY_CODE => $PROPERTY_VALUE));
?>