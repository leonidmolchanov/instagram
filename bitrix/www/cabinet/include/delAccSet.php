<?
if(!CIBlockElement::Delete((int)$_REQUEST['blockId']))
{
    header('x', true, 404);
}
else{
}
?>