<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
class SalesOrder_GetRewardPoints_Action extends Vtiger_Save_Action {
           
        var $db;

        public function process(Vtiger_Request $request) {
         $customer_id=$request->get('customerId');
         $contact_id=$request->get('contactId');
         if($customer_id!=0 && $customer_id!=''){
         	$query= 'SELECT count(accountid) as sale_count FROM vtiger_salesorder where accountid=?';
         	$this->db = PearDatabase::getInstance();
         	$adb=$this->db;
         	$result=$adb->pquery($query, array($contact_id));
         	$required_array=$result->fields;
          
        $client = new SoapClient('https://tendercuts.in/api/soap/?wsdl');
	$session = $client->login('admin','Tendercuts123!');
	$email= $required_array['email'];
     //   $cashback=$client->call($session,'rewardpoints_customer.getbalancebyid',$customer_id);
        $cashback=0; 
         /************** Reward Points Calculation ********************/



        	 if($required_array['sale_count']==0){
        	   $minPoint=0;
         	 }
        	 elseif($required_array['sale_count']==1){
	           $minPoint=$cashback/4;
     		 }	
                 elseif($required_array['sale_count']==2){
                    $minPoint=$cashback/3;
                  }   
                 elseif($required_array['sale_count']==3){
                    $minPoint=$cashback/2;
         	 }   
                elseif($required_array['sale_count']>=4){
	            $minPoint=$cashback/1;
                } 
             //    $minPoint=50;
               //  $cashback=50;
                 $resultarray=json_encode(array('minPoint'=>$minPoint,'pointAvailable'=>$cashback,'success'=>true));
                 print_r($resultarray);
       }
       else{
        
       $resultarray=json_encode(array('minPoint'=>0,'pointAvailable'=>0,'success'=>false));
                 print_r($resultarray);
 
       }


          
      }
}


