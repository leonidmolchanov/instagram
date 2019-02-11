<?
global $USER;
$iblockid = 0;
if (CModule::IncludeModule("iblock")) {

    $ib_list = CIBlock::GetList(
        Array(),
        Array(
            "CODE" => "PROFILE_ACC_SET",
            "CHECK_PERMISSIONS" => "N"
        )
    );
    while ($arIBlock = $ib_list->GetNext()) //цикл по всем блокам
    {
        $iblockid = $arIBlock["ID"];

    }
}
if($_REQUEST['accId']==0) {

    $el = new CIBlockElement;

    $PROP = array();
    $PROP["ACC_COUNT_MIN"] = $_REQUEST["accCountMin"];  // учитель для группы
    $PROP["ACC_POST_MIN"] = $_REQUEST["accPostMin"];
    $PROP["ACC_POST_MAX"] = $_REQUEST["accPostMax"];
    $PROP["ACC_POST_VIDEO_P"] = $_REQUEST["accPostVideoP"];
    $PROP["ACC_A"] = $_REQUEST['accA'];
    $PROP["ACC_B"] = $_REQUEST['accB'];
    $PROP["ACC_C"] = $_REQUEST['accC'];
    $PROP["ACC_X1"] = $_REQUEST['accX1'];
    $PROP["ACC_X2"] = $_REQUEST['accX2'];

    if ($_REQUEST['repeat'] == 'false') {
        $PROP["REPEAT"] = 0;
    } else {
        $PROP["REPEAT"] = 1;
    }
    $arLoadProductArray = Array(
        "MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
        "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
        "IBLOCK_ID" => $iblockid,
        "PROPERTY_VALUES" => $PROP,
        "NAME" => $_REQUEST["accName"],
        "ACTIVE" => "Y"
    );

    if ($PRODUCT_ID = $el->Add($arLoadProductArray))
        $request = 'Success';
    else
        $request = 'Error' . $_REQUEST["accName"];

    echo json_encode($request);
}

else{
    $el = new CIBlockElement;

    $PROP = array();
    $PROP["ACC_COUNT_MIN"] = $_REQUEST["accCountMin"];  // учитель для группы
    $PROP["ACC_POST_MIN"] = $_REQUEST["accPostMin"];
    $PROP["ACC_POST_MAX"] = $_REQUEST["accPostMax"];
    $PROP["ACC_POST_VIDEO_P"] = $_REQUEST["accPostVideoP"];
    $PROP["ACC_A"] = $_REQUEST['accA'];
    $PROP["ACC_B"] = $_REQUEST['accB'];
    $PROP["ACC_C"] = $_REQUEST['accC'];
    $PROP["ACC_X1"] = $_REQUEST['accX1'];
    $PROP["ACC_X2"] = $_REQUEST['accX2'];
    $arLoadProductArray = Array(
        "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
        "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
        "IBLOCK_ID"      => $iblockid,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => $_REQUEST["accName"],
        "ACTIVE"         => "Y"
    );

    if($el->Update($_REQUEST['accId'],$arLoadProductArray))
        $request = 'Success';
    else
        $request = 'Error'.$_REQUEST["accName"];

    echo json_encode($request);
}
?>