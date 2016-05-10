<?php
$Vtiger_Utils_Log = true;

include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');

$module = Vtiger_Module::getInstance('CallHistory');
if($module) {
$blockInstance = Vtiger_Block::getInstance('Callhistory_INFORMATION', $module);

    if($blockInstance) 
	{

    $fieldInstance = Vtiger_Field::getInstance('accountid', $module);

       if(!$fieldInstance) {
           $fieldInstance = new Vtiger_Field();
           $fieldInstance->name = 'accountid';
           $fieldInstance->label = 'Organization Name';
           $fieldInstance->uitype = 10;
           $fieldInstance->typeofdata = 'V~O';
           $blockInstance->addField($fieldInstance);

           $fieldInstance->setRelatedModules(Array('Accounts'));
       }
  }
}
?>