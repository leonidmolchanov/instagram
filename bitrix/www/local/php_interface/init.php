<?


function checkAccountDemon(){

CEventLog::Add(array(
         "SEVERITY" => "SECURITY",
         "AUDIT_TYPE_ID" => "MY_OWN_TYPE",
         "MODULE_ID" => "main",
         "ITEM_ID" => 123,
         "DESCRIPTION" => "Какое-то описание",
      ));

}
?>