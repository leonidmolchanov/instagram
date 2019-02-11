<?php
/**
 * Created by PhpStorm.
 * User: leonidmolcanov
 * Date: 10/02/2019
 * Time: 21:41
 */
$DB->StartTransaction();
if(!CIBlockElement::Delete($_REQUEST["id"]))
{
    $request='error';
    $strWarning .= 'Error!';
    $DB->Rollback();
}
else
    $DB->Commit();
$request='Success';
//}
echo json_encode($request);
?>