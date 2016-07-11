<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Inventory_ProductsPopup_View extends Vtiger_Popup_View {

	/**
	 * Function returns module name for which Popup will be initialized
	 * @param type $request
	 */
	function getModule($request) {
		return 'Products';
	}
	
	function process (Vtiger_Request $request) {
		$viewer = $this->getViewer ($request);
		$companyDetails = Vtiger_CompanyDetails_Model::getInstanceById();
		$companyLogo = $companyDetails->getLogo();

		$this->initializeListViewContents($request, $viewer);

		$viewer->assign('COMPANY_LOGO',$companyLogo);
		$moduleName = 'Inventory';
		$viewer->assign('MODULE_NAME',$moduleName);
		$viewer->view('Popup.tpl', $moduleName);
	}

	/*
	 * Function to initialize the required data in smarty to display the List View Contents
	 */
	public function initializeListViewContents(Vtiger_Request $request, Vtiger_Viewer $viewer) {
		//src_module value is added to just to stop showing inactive products
		$request->set('src_module', $request->getModule());

		$moduleName = $this->getModule($request);
		$cvId = $request->get('cvid');
		$pageNumber = $request->get('page');
		$orderBy = $request->get('orderby');
		$sortOrder = $request->get('sortorder');
		$sourceModule = $request->get('src_module');
		$sourceField = $request->get('src_field');
		$sourceRecord = $request->get('src_record');
		$searchKey = $request->get('search_key');
        $searchValue = $request->get('search_value');
		$currencyId = $request->get('currency_id');
		$storeId = $request->get('store_id');
//		if($sourceModule=="SalesOrder"){
//                 $searchKey ="productcategory";
//                $searchValue = "All";
//
//                }
//                else{
//                $searchKey = $request->get('search_key');
//                $searchValue = $request->get('search_value');
//                 }
//                $currencyId = $request->get('currency_id');

		//To handle special operation when selecting record from Popup
		$getUrl = $request->get('get_url');

		//Check whether the request is in multi select mode
		$multiSelectMode = $request->get('multi_select');
		if(empty($multiSelectMode)) {
			$multiSelectMode = false;
		}

		if(empty($cvId)) {
			$cvId = '0';
		}
		if(empty ($pageNumber)) {
			$pageNumber = '1';
		}

		$pagingModel = new Vtiger_Paging_Model();
		$pagingModel->set('page', $pageNumber);

		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$listViewModel = Vtiger_ListView_Model::getInstanceForPopup($moduleName);
		$recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceForModule($moduleModel);

		if(!empty($orderBy)) {
			$listViewModel->set('orderby', $orderBy);
			$listViewModel->set('sortorder', $sortOrder);
		}
		if(!empty($sourceModule)) {
			$listViewModel->set('src_module', $sourceModule);
			$listViewModel->set('src_field', $sourceField);
			$listViewModel->set('src_record', $sourceRecord);
		}
		if((!empty($searchKey)) && (!empty($searchValue))) {
			$listViewModel->set('search_key', $searchKey);
			$listViewModel->set('search_value', $searchValue);
		}

		if(!$this->listViewHeaders) {
			$this->listViewHeaders = $listViewModel->getListViewHeaders();
		}
		if(!$this->listViewEntries) {
			$this->listViewEntries = $listViewModel->getListViewEntries($pagingModel);
		}

		//foreach ($this->listViewEntries as $key => $listViewEntry) {
		//	$productId = $listViewEntry->getId();
		//	$subProducts = $listViewModel->getSubProducts($productId);
		//	if($subProducts) {
		//		$listViewEntry->set('subProducts', $subProducts);
		//	}
		//}
		$prod_img_details = array();
		foreach ($this->listViewEntries as $key => $listViewEntry) {			
			$productId = $listViewEntry->getId();
			$recordModel = Vtiger_Record_Model::getInstanceById($productId, $productModel);
			$prod_img_details[][$productId] = $recordModel->getImageDetails();			
			$subProducts = $listViewModel->getSubProducts($productId);
			if($subProducts) {
				$listViewEntry->set('subProducts', $subProducts);
			}
		}
		$noOfEntries = count($this->listViewEntries);

		if(empty($sortOrder)) {
			$sortOrder = "ASC";
		}
		if($sortOrder == "ASC") {
			$nextSortOrder = "DESC";
			$sortImage = "downArrowSmall.png";
		}else {
			$nextSortOrder = "ASC";
			$sortImage = "upArrowSmall.png";
		}
		$viewer->assign('MODULE', $moduleName);

		$viewer->assign('SOURCE_MODULE', $sourceModule);
		$viewer->assign('SOURCE_FIELD', $sourceField);
		$viewer->assign('SOURCE_RECORD', $sourceRecord);

		$viewer->assign('SEARCH_KEY', $searchKey);
		$viewer->assign('SEARCH_VALUE', $searchValue);

		$viewer->assign('ORDER_BY',$orderBy);
		$viewer->assign('SORT_ORDER',$sortOrder);
		$viewer->assign('NEXT_SORT_ORDER',$nextSortOrder);
		$viewer->assign('SORT_IMAGE',$sortImage);
		$viewer->assign('GETURL', $getUrl);
		$viewer->assign('CURRENCY_ID', $currencyId);

		$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
		$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());

		$viewer->assign('PAGING_MODEL', $pagingModel);
		$viewer->assign('PAGE_NUMBER',$pageNumber);

		$viewer->assign('LISTVIEW_ENTIRES_COUNT',$noOfEntries);
		$viewer->assign('LISTVIEW_HEADERS', $this->listViewHeaders);
		$viewer->assign('LISTVIEW_ENTRIES', $this->listViewEntries);
		$viewer->assign('IMAGE_DETAILS', $prod_img_details);
		
		if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false)) {
			if(!$this->listViewCount){
				$this->listViewCount = $listViewModel->getListViewCount();
			}
			$totalCount = $this->listViewCount;
			$pageLimit = $pagingModel->getPageLimit();
			$pageCount = ceil((int) $totalCount / (int) $pageLimit);

			if($pageCount == 0){
				$pageCount = 1;
			}
			$viewer->assign('PAGE_COUNT', $pageCount);
			$viewer->assign('LISTVIEW_COUNT', $totalCount);
		}

		$viewer->assign('MULTI_SELECT', $multiSelectMode);
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());

		$trigger_event_name = $_REQUEST['triggerEventName'];
		$c_trigger_event_name = ($_REQUEST['custom_triggerEventName']) ? $_REQUEST['custom_triggerEventName'] : '';
		
		$viewer->assign('TRIGGER_EVENT_NAME', $trigger_event_name);
		if( !isset($_SESSION['trigger_event_name']) || $_SESSION['trigger_event_name'] != $c_trigger_event_name){
			$_SESSION['trigger_event_name'] = $trigger_event_name;
			$_SESSION['product_inventory_avail_data'] = getQtyByStoreId($storeId);
		}
		
		$viewer->assign('PRODUCTS_QTY_OF_STORE', $_SESSION['product_inventory_avail_data']);

		$viewer->assign('MODULE', $request->getModule());
		$viewer->assign('GETURL', 'getTaxesURL');
		$viewer->assign('VIEW', 'ProductsPopup');
	}

	 /**
	 * Function to get the list of Script models to be included
	 * @param Vtiger_Request $request
	 * @return <Array> - List of Vtiger_JsScript_Model instances
	 */
	function getHeaderScripts(Vtiger_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);

		$moduleName = $request->getModule();
		$modulePopUpFile = 'modules.'.$moduleName.'.resources.Popup';
		unset($headerScriptInstances[$modulePopUpFile]);

		$jsFileNames = array('modules.Inventory.resources.Popup');
		$jsFileNames[] = $modulePopUpFile;
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);

		return $headerScriptInstances;
	}

}
