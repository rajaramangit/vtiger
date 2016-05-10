<?php
class CallHistory extends CRMEntity {
 
    var $table_name = "vtiger_callhistory";
    var $table_index = 'callhistoryid';
 
    var $tab_name = Array(
        'vtiger_crmentity',
        'vtiger_callhistory',
        'vtiger_callhistorycf'
    );
 
    var $tab_name_index = Array(
        'vtiger_crmentity' => 'crmid',
        'vtiger_callhistory' => 'callhistoryid',
        'vtiger_callhistorycf' => 'callhistoryid'
    );
 
    var $customFieldTable = Array(
        'vtiger_callhistorycf',
        'callhistoryid'
    );
 
    var $entity_table = "vtiger_crmentity";
 
    //var $column_fields = Array();
 
    // This is the list of vtiger_fields that are in the lists.
    var $list_fields = Array(
      'Calling Time' => Array('vtiger_callhistory' => 'calltime'),
  
      'Call Type' => Array('vtiger_callhistory' => 'calltype'),
		'Call Sid' => Array('vtiger_callhistory' => 'callsid'),
     //   'Calling Time' => Array('vtiger_callhistory' => 'calltime'),
		'Recording URL' => Array('vtiger_callhistory' => 'recordurl'),
        'Lead' => Array('vtiger_callhistory' => 'leadid'),
        'Assigned To' => Array('vtiger_crmentity' => 'smownerid')
    );
 
    var $list_fields_name = Array(
        'Calling Time' => 'calltime',
        'Call Type' => 'calltype',
		'Call Sid' => 'callsid',
      //  'Calling Time' => 'calltime',
		'Recording URL' => 'recordurl',
        'Lead' => 'leadid',
        'Assigned To' => 'assigned_user_id'
    );
 
    var $list_link_field = 'category_name';
 
    var $search_fields = Array(
        'Calling Time' => Array('vtiger_callhistory' => 'calltime'),
        'Call Type' => Array('vtiger_callhistory' => 'calltype'),
		'Call Sid' => Array('vtiger_callhistory' => 'callsid'),
      //  'Calling Time' => Array('vtiger_callhistory' => 'calltime'),
		'Recording URL' => Array('vtiger_callhistory' => 'recordurl'),
        'Lead' => Array('vtiger_callhistory' => 'leadid'),
        'Assigned To' => Array('vtiger_crmentity' => 'smownerid')
    );
 
    var $search_fields_name = Array(
        'Calling Time' => 'calltime',
               'Call Type' => 'calltype',
	   'Call Sid' => 'callsid',
       //'Calling Time' => 'calltime',
		'Recording URL' => 'recordurl',
        'Lead' => 'leadid',
        'Assigned To' => 'assigned_user_id'
    );
	
	var $mandatory_fields = Array('calltime','calltype','callsid','recordurl','assigned_user_id');
	
	// This is the list of vtiger_fields that are required
	var $required_fields =  array('calltype');
	
	
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'calltime';
	var $default_sort_order = 'DESC';
	
	function CallHistory() {
		$this->log =LoggerManager::getLogger('CallHistory');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('CallHistory');
		
	}
 
	/** Function to handle module specific operations when saving a entity
	*/
	function save_module($module) {
 
	}
}
