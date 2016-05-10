 <?php

// turn on debugging level
$vtiger_utils_log = true;
include_once 'vtlib/Vtiger/Module.php';

$moduleinstance = vtiger_module::getinstance('Contacts');
//$testmodule = vtiger_module::getinstance('CallHistory');
//$relationlabel  = 'CallHistory';
$moduleinstance->setrelatedlist(
      $testmodule, $relationlabel, null, get_dependents_list
);
