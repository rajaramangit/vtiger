<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

/**
 * SalesOrder Edit View Record Structure Model
 */
class SalesOrder_EditRecordStructure_Model extends Inventory_EditRecordStructure_Model {

	
}
$delivery_area_store_ary = getDeliveryAreaStore();
global $marinate_vat_percentage;
if( isset($delivery_area_store_ary) && !empty($delivery_area_store_ary) ){
	echo '<script type="text/javascript">
			var area_store 	= '. $delivery_area_store_ary. ';
			var marinate_vat_percentage = '.$marinate_vat_percentage.';
		</script>';
}