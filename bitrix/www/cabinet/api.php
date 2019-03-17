<?
require_once ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
require("include/settings.php");
if($_REQUEST['sessid']==bitrix_sessid() || $_REQUEST['token']==$NODE_TOKEN):
    CModule::IncludeModule('iblock');
    if($_REQUEST['type']=='add'):
        require("include/addAccSet.php");
    elseif($_REQUEST['type']=='delete'):
        require("include/delAccSet.php");
    elseif($_REQUEST['type']=='createAccList1'):
        require("include/createAccList1.php");
    elseif($_REQUEST['type']=='setDefaultAcc'):
        require("include/setDefaultAcc.php");
    elseif($_REQUEST['type']=='changeAccState'):
        require("include/changeAccState.php");
    elseif($_REQUEST['type']=='addProfileAcc'):
        require("include/addProfileAcc.php");
    elseif($_REQUEST['type']=='delProfileAcc'):
        require("include/delProfileAcc.php");
    elseif($_REQUEST['type']=='node'):
echo "active";
    elseif($_REQUEST['type']=='change'):
        require("include/changeAccSet.php");
else:
header('x', true, 404);
    endif;
else:
    header('x', true, 404);
endif;
?>