<?php
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'CallHistory';

$moduleInstance = Vtiger_Module::getInstance($MODULENAME);

if ($moduleInstance || file_exists('modules/' . $MODULENAME)) {
	echo "Module already present - choose a different name.";
} else {

	$moduleInstance = new Vtiger_Module();
	$moduleInstance -> name = $MODULENAME;
	$moduleInstance -> parent = 'Tools';
	$moduleInstance -> save();

	// Schema Setup
	$moduleInstance->initTables();

	// Field Setup
	$block = new Vtiger_Block();
	$block -> label = 'Callhistory_INFORMATION';
	$moduleInstance -> addBlock($block);

	/*$blockcf = new Vtiger_Block();
	$blockcf -> label = 'LBL_CUSTOM_INFORMATION';
	$moduleInstance -> addBlock($blockcf);*/

	$field1 = new Vtiger_Field();
	$field1 -> name = 'calltype';
	$field1 -> label = 'Call Type';
	$field1 -> summaryfield = '1';
	$field1 -> uitype = 2;
	$field1 -> column = $field1 -> name;
	$field1 -> columntype = 'VARCHAR(255)';
	$field1 -> typeofdata = 'V~M';
	$block -> addField($field1);

	$moduleInstance -> setEntityIdentifier($field1);
	
	$field5 = new Vtiger_Field();
	$field5 -> name = 'callsid';
	$field5 -> label = 'Call Sid';
	$field5 -> summaryfield = '1';
	$field5 -> uitype = 2;
	$field5 -> column = $field5 -> name;
	$field5 -> columntype = 'VARCHAR(255)';
	$field5 -> typeofdata = 'V~M';
	$block -> addField($field5);

	$moduleInstance -> setEntityIdentifier($field5);
	
	$field4 = new Vtiger_Field();
	$field4 -> name = 'calltime';
	$field4 -> label = 'Call Time';
	$field4 -> summaryfield = '1';
	$field4 -> uitype = 2;
	$field4 -> column = $field4 -> name;
	$field4 -> columntype = 'VARCHAR(255)';
	$field4 -> typeofdata = 'V~O';

	$block -> addField($field4);
	
	$moduleInstance -> setEntityIdentifier($field4);
	
	$field3 = new Vtiger_Field();
	$field3 -> name = 'recordurl';
	$field3 -> label = 'Recording URL';
	$field3 -> summaryfield = '1';
	$field3 -> uitype = 17;
	$field3 -> column = $field3 -> name;
	$field3 -> columntype = 'VARCHAR(255)';
	$field3 -> typeofdata = 'V~M';
	$block -> addField($field3);

	$moduleInstance -> setEntityIdentifier($field3);
	

	
	$field2 = new vtiger_field();
	$field2->name = 'contactid';
	$field2->label = 'Contact Name';
	$field2->table = 'vtiger_callhistory';
	$field2->column = 'contactid';
	$field2->columntype = 'VARCHAR(255)';
	$field2->uitype = 10;
	$field2->typeofdata = 'v~o';
	$block->addfield($field2);
	$field2->setrelatedmodules(array('Contacts'));
		
	
	
	// Recommended common fields every Entity module should have (linked to core table)
	$mfield1 = new Vtiger_Field();
	$mfield1 -> name = 'assigned_user_id';
	$mfield1 -> label = 'Assigned To';
	$mfield1 -> table = 'vtiger_crmentity';
	$mfield1 -> column = 'smownerid';
	$mfield1 -> uitype = 53;
	$mfield1 -> typeofdata = 'V~M';
	$block -> addField($mfield1);

	$mfield2 = new Vtiger_Field();
	$mfield2 -> name = 'CreatedTime';
	$mfield2 -> label = 'Created Time';
	$mfield2 -> table = 'vtiger_crmentity';
	$mfield2 -> column = 'createdtime';
	$mfield2 -> uitype = 70;
	$mfield2 -> typeofdata = 'T~O';
	$mfield2 -> displaytype = 2;
	$block -> addField($mfield2);

	$mfield3 = new Vtiger_Field();
	$mfield3 -> name = 'ModifiedTime';
	$mfield3 -> label = 'Modified Time';
	$mfield3 -> table = 'vtiger_crmentity';
	$mfield3 -> column = 'modifiedtime';
	$mfield3 -> uitype = 70;
	$mfield3 -> typeofdata = 'T~O';
	$mfield3 -> displaytype = 2;
	$block -> addField($mfield3);

	// Filter Setup
	$filter1 = new Vtiger_Filter();
	$filter1 -> name = 'All';
	$filter1 -> isdefault = true;
	$moduleInstance -> addFilter($filter1);
	$filter1 -> addField($field1) -> addField($field2, 1) -> addField($field3, 2) -> addField($field4, 3) ->addField($field5, 4) -> addField($mfield1, 5);

	// Sharing Access Setup
	$moduleInstance -> setDefaultSharing();

	// Webservice Setup
	$moduleInstance -> initWebservice();

	mkdir('modules/' . $MODULENAME);
	echo "OK\n";
}