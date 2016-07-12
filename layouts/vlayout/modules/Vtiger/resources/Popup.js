/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
jQuery.Class("Vtiger_Popup_Js",{

    getInstance: function(){
	    var module = app.getModuleName();
		var className = jQuery('#popUpClassName').val();
		if(typeof className != 'undefined'){
			var moduleClassName = className;
		}else{
			var moduleClassName = module+"_Popup_Js";
		}
		var fallbackClassName = Vtiger_Popup_Js;
	    if(typeof window[moduleClassName] != 'undefined'){
			var instance = new window[moduleClassName]();
		}else{
			var instance = new fallbackClassName();
		}
	    return instance;
	}

},{

	//holds the event name that child window need to trigger
	eventName : '',
	popupPageContentsContainer : false,
	sourceModule : false,
	sourceRecord : false,
	sourceField : false,
	multiSelect : false,
	relatedParentModule : false,
	relatedParentRecord : false,

	/**
	 * Function to get source module
	 */
	getSourceModule : function(){
		if(this.sourceModule == false){
			this.sourceModule = jQuery('#parentModule').val();
		}
		return this.sourceModule;
	},

	/**
	 * Function to get source record
	 */
	getSourceRecord : function(){
		if(this.sourceRecord == false){
			this.sourceRecord = jQuery('#sourceRecord').val();
		}
		return this.sourceRecord;
	},

	/**
	 * Function to get source field
	 */
	getSourceField : function(){
		if(this.sourceField == false){
			this.sourceField = jQuery('#sourceField').val();
		}
		return this.sourceField;
	},

	/**
	 * Function to get related parent module
	 */
	getRelatedParentModule : function(){
		if(this.relatedParentModule == false){
			this.relatedParentModule = jQuery('#relatedParentModule').val();
		}
		return this.relatedParentModule;
	},
	/**
	 * Function to get related parent id
	 */
	getRelatedParentRecord : function(){
		if(this.relatedParentRecord == false){
			this.relatedParentRecord = jQuery('#relatedParentId').val();
		}
		return this.relatedParentRecord;
	},

	/**
	 * Function to get Search key
	 */
	getSearchKey : function(){
		return jQuery('#searchableColumnsList').val();
	},

	/**
	 * Function to get Search value
	 */
	getSearchValue : function(){
		return jQuery('#searchvalue').val();
	},

	/**
	 * Function to get Order by
	 */
	getOrderBy : function(){
		return jQuery('#orderBy').val();
	},

	/**
	 * Function to get Sort Order
	 */
	getSortOrder : function(){
			return jQuery("#sortOrder").val();
	},

	/**
	 * Function to get Page Number
	 */
	getPageNumber : function(){
		return jQuery('#pageNumber').val();
	},

	getPopupPageContainer : function(){
		if(this.popupPageContentsContainer == false) {
			this.popupPageContentsContainer = jQuery('#popupPageContainer');
		}
		return this.popupPageContentsContainer;

	},

	show : function(urlOrParams, cb, windowName, eventName, onLoadCb){
		if(typeof urlOrParams == 'undefined'){
			urlOrParams = {};
		}
		if (typeof urlOrParams == 'object' && (typeof urlOrParams['view'] == "undefined")) {
			urlOrParams['view'] = 'Popup';
		}

		// Target eventName to be trigger post data selection.
		if(typeof eventName == 'undefined') {
			eventName = 'postSelection'+ Math.floor(Math.random() * 10000);
		}
		if(typeof windowName == 'undefined' ){
			windowName = 'test';
		}
		if (typeof urlOrParams == 'object') {
			urlOrParams['triggerEventName'] = eventName;
		} else {
			urlOrParams += '&triggerEventName=' + eventName;
		}

		var urlString = (typeof urlOrParams == 'string')? urlOrParams : jQuery.param(urlOrParams);
		var url = 'index.php?'+urlString;
		var popupWinRef =  window.open(url, windowName ,'width=800,height=650,resizable=0,scrollbars=1').focus();
		
		if (typeof this.destroy == 'function') {
			// To remove form elements that have created earlier
			this.destroy();
		}
		jQuery.initWindowMsg();

		if(typeof cb != 'undefined') {
			this.retrieveSelectedRecords(cb, eventName);
		}

		 if(typeof onLoadCb == 'function') {
			jQuery.windowMsg('Vtiger.OnPopupWindowLoad.Event', function(data){
				onLoadCb(data);
            })
        }

		// http://stackoverflow.com/questions/13953321/how-can-i-call-a-window-child-function-in-javascript
		// This could be useful for the caller to invoke child window methods post load.
		return popupWinRef;
	},

	retrieveSelectedRecords : function(cb, eventName) {
		if(typeof eventName == 'undefined') {
			eventName = 'postSelection';
		}

		jQuery.windowMsg(eventName, function(data) {
			cb(data);
		});
	},

	/**
	 * Function which removes the elements that are added by the plugin
	 *
	 */
	destroy : function(){
		jQuery('form[name="windowComm"]').remove();
	},

	done : function(result, eventToTrigger, window) {

		if(typeof eventToTrigger == 'undefined' || eventToTrigger.length <=0 ) {
			eventToTrigger = 'postSelection'
		}

		if(typeof window == 'undefined'){
			window = self;
		}
		var hid_area_store_sel_ids = window.opener.$("#hid_area_store_sel_ids").val().split('###');				
		window.opener.$('#'+hid_area_store_sel_ids[1]).removeAttr('disabled');
		window.opener.$("#"+hid_area_store_sel_ids[1]).trigger("liszt:updated");				

		window.close();

		var len = Object.keys(result).length;		
		var pop_qty = '1';
		var pop_cmnt = '';
		if(len == 1){//assign to array
			result = [result];
		}
		for(var i=0;i<len;i++){									
			$.each(result[i], function(key,val) {
				if(val.id != 'undefined'){
					pop_qty = jQuery('#popup_qty_' + val.id).val();
					pop_cmnt = '';
					var popup_prod_desc = (jQuery('#popup_prod_desc_'+val.id).val()) ? '||'+jQuery('#popup_prod_desc_'+val.id).val() : '';
					if(typeof jQuery('#sel_opt_' + val.id).val() !== 'undefined' && jQuery('#sel_opt_' + val.id).val() != ''){						
						pop_cmnt = jQuery('#sel_opt_' + val.id + ' option:selected').text()+popup_prod_desc+'###'+jQuery('#sel_opt_' + val.id).val();
					}
					if(pop_cmnt == ''){
						pop_cmnt = popup_prod_desc;
					}
				}				
				if(pop_qty == ''){
					pop_qty = '1';
				}				
				result[i][key]['popup_qty'] = pop_qty;
				result[i][key]['popup_cmnt'] = pop_cmnt;
			  });			
		}
		if(len == 1){//array to object
			result = result[0];
		}
        var data = JSON.stringify(result);
        // Because if we have two dollars like this "$$" it's not working because it'll be like escape char(Email Templates)
        data = data.replace(/\$\$/g,"$ $");

		
		jQuery.triggerParentEvent(eventToTrigger, JSON.stringify(result));

	},

	getView : function(){
	    var view = jQuery('#view').val();
	    if(view == '') {
		    view = 'PopupAjax';
	    } else {
		    view = view+'Ajax';
	    }
	    return view;
	},

	setEventName : function(eventName) {
		this.eventName = eventName;
	},

	getEventName : function() {
		return this.eventName;
	},

	isMultiSelectMode : function() {
		if(this.multiSelect == false){
			this.multiSelect = jQuery('#multi_select');
		}
		var value = this.multiSelect.val();
		if(value) {
			return value;
		}
		return false;
	},

	getListViewEntries: function(e){
		var thisInstance = this;
		var row  = jQuery(e.currentTarget);
		var dataUrl = row.data('url');
		if(typeof dataUrl != 'undefined'){
			dataUrl = dataUrl+'&currency_id='+jQuery('#currencyId').val();
		    AppConnector.request(dataUrl).then(
			function(data){
				for(var id in data){
				    if(typeof data[id] == "object"){
					var recordData = data[id];
				    }
				}
				thisInstance.done(recordData, thisInstance.getEventName());
				e.preventDefault();
			},
			function(error,err){

			}
		    );
		} else {
		    var id = row.data('id');
		    var recordName = row.data('name');
			var recordInfo = row.data('info');
		    var response ={};
		    response[id] = {'name' : recordName,'info' : recordInfo} ;
			thisInstance.done(response, thisInstance.getEventName());
		    e.preventDefault();
		}

	},

	registerSelectButton : function(){
		var popupPageContentsContainer = this.getPopupPageContainer();
		var thisInstance = this;
		popupPageContentsContainer.on('click','button.select', function(e){
			var tableEntriesElement = popupPageContentsContainer.find('table');
			var selectedRecordDetails = {};
			var recordIds = new Array();
			var dataUrl;
			jQuery('input.entryCheckBox', tableEntriesElement).each(function(index, checkBoxElement){
				var checkBoxJqueryObject = jQuery(checkBoxElement)
				if(! checkBoxJqueryObject.is(":checked")){
					return true;
				}
				var row = checkBoxJqueryObject.closest('tr');
				var id = row.data('id');
				recordIds.push(id);
				var name = row.data('name');
				dataUrl = row.data('url');
				selectedRecordDetails[id] = {'name' : name};
			});
			var jsonRecorIds = JSON.stringify(recordIds);
			if(Object.keys(selectedRecordDetails).length <= 0) {
				alert(app.vtranslate('JS_PLEASE_SELECT_ONE_RECORD'));
			}else{
				
				//is quantity valid
					var is_valid_qty = is_valid_opt = true;	
		 			jQuery('input[type="checkbox"]').each(function() {
				        if ($(this).is(":checked")) {
								var ch_pid = jQuery(this).data("chk_pid");
								if(jQuery('#sel_opt_'+ch_pid).length && jQuery('#sel_opt_'+ch_pid).val() == ''){
									jQuery('#sel_opt_'+ch_pid).addClass('qty-empty-highlight');
									is_valid_opt = false;
								}
								var isvalid_qty = jQuery.isNumeric(jQuery('#popup_qty_'+ch_pid).val());															
								if(!isvalid_qty && isvalid_qty > 0){
									jQuery('#popup_qty_'+ch_pid).addClass('qty-empty-highlight');
									is_valid_qty = false;
								}
				        }
				    });
				    if(!is_valid_qty && !is_valid_opt){
				    	alert('Please enter a valid quantity and optoin');
				    	return false;
				    }
				    else if(!is_valid_qty){
				    	alert('Please enter a valid quantity');
				    	return false;
				    }
				    else if(!is_valid_opt){
				    	alert('Please select a valid option');
				    	return false;
				    }				    
//		
				
				if(typeof dataUrl != 'undefined'){
				    dataUrl = dataUrl+'&idlist='+jsonRecorIds+'&currency_id='+jQuery('#currencyId').val();
				    AppConnector.request(dataUrl).then(
					function(data){
						for(var id in data){
						    if(typeof data[id] == "object"){
							var recordData = data[id];
						    }
						}
						var recordDataLength = Object.keys(recordData).length;
						if(recordDataLength == 1){
							recordData = recordData[0];
						}
						thisInstance.done(recordData, thisInstance.getEventName());
						e.preventDefault();
					},
					function(error,err){

					}
				);
				}else{
				    thisInstance.done(selectedRecordDetails, thisInstance.getEventName());
				}
			}
		});
	},

	selectAllHandler : function(e){
		var currentElement = jQuery(e.currentTarget);
		var isMainCheckBoxChecked = currentElement.is(':checked');
		var tableElement = currentElement.closest('table');
		if(isMainCheckBoxChecked) {
			jQuery('input.entryCheckBox', tableElement).attr('checked','checked').closest('tr').addClass('highlightBackgroundColor');
		}else {
			jQuery('input.entryCheckBox', tableElement).removeAttr('checked').closest('tr').removeClass('highlightBackgroundColor');
		}
	},

	registerEventForSelectAllInCurrentPage : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('change','input.selectAllInCurrentPage',function(e){
			thisInstance.selectAllHandler(e);
		});
	},

	checkBoxChangeHandler : function(e){
		var elem = jQuery(e.currentTarget);
		var parentElem = elem.closest('tr');
		if(elem.is(':checked')){
			parentElem.addClass('highlightBackgroundColor');

		}else{
			parentElem.removeClass('highlightBackgroundColor');
		}
	},

	/**
	 * Function to register event for entry checkbox change
	 */
	registerEventForCheckboxChange : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('click','input.entryCheckBox',function(e){
			e.stopPropagation();
			thisInstance.checkBoxChangeHandler(e);
		});
		popupPageContentsContainer.on('click','input.popup_qty',function(e){
			e.stopPropagation();
		});	
	},
	/**
	 * Function to get complete params
	 */
	
	getCompleteParams : function(){
		var params = {};
		params['view'] = this.getView();
		params['src_module'] = this.getSourceModule();
		params['src_record'] = this.getSourceRecord();
		params['src_field'] = this.getSourceField();
		params['search_key'] =  this.getSearchKey();
		params['search_value'] =  this.getSearchValue();
		params['orderby'] =  this.getOrderBy();
		params['sortorder'] =  this.getSortOrder();
		params['page'] = this.getPageNumber();
		params['related_parent_module'] = this.getRelatedParentModule();
		params['related_parent_id'] = this.getRelatedParentRecord();
		params['module'] = app.getModuleName();
		if(jQuery('#triggerEventName').val().length){
			var cus_tri_name = jQuery('#triggerEventName').val();
			params['custom_triggerEventName'] = cus_tri_name;	
		}
		if(this.isMultiSelectMode()) {
			params['multi_select'] = true;
		}
		return params;
	},
	
//	getCompleteParams : function(){
//		var params = {};
//		params['view'] = this.getView();
//		params['src_module'] = this.getSourceModule();
//                 if(this.getSourceModule()=='SalesOrder'){
//
//                params['search_key'] =  "productcategory";
//                params['search_value'] =  "All";
//
//                }
//                else{
//                params['search_key'] =  this.getSearchKey();
//                params['search_value'] =  this.getSearchValue();
//               }
//
//		params['src_record'] = this.getSourceRecord();
//		params['src_field'] = this.getSourceField();
//		params['orderby'] =  this.getOrderBy();
//		params['sortorder'] =  this.getSortOrder();
//		params['page'] = this.getPageNumber();
//		params['related_parent_module'] = this.getRelatedParentModule();
//		params['related_parent_id'] = this.getRelatedParentRecord();
//		params['module'] = app.getModuleName();
//
//		if(this.isMultiSelectMode()) {
//			params['multi_select'] = true;
//		}
//		return params;
//	},

	/**
	 * Function to get Page Records
	 */
	getPageRecords : function(params){
		var thisInstance = this;
		var aDeferred = jQuery.Deferred();
		var progressIndicatorElement = jQuery.progressIndicator({
			'position' : 'html',
			'blockInfo' : {
				'enabled' : true
			}
		});
		Vtiger_BaseList_Js.getPageRecords(params).then(
				function(data){
					jQuery('#popupContents').html(data);
					progressIndicatorElement.progressIndicator({
						'mode' : 'hide'
					})
					thisInstance.calculatePages().then(function(data){
						aDeferred.resolve(data);
					});
				},

				function(textStatus, errorThrown){
					aDeferred.reject(textStatus, errorThrown);
				}
			);
		return aDeferred.promise();
	},
	
		/**
	 * Function to calculate number of pages
	 */
	calculatePages : function() {
		var aDeferred = jQuery.Deferred();
		var element = jQuery('#totalPageCount');
		var totalPageNumber = element.text();
		if(totalPageNumber == ""){
			var totalRecordCount = jQuery('#totalCount').val();
			if(totalRecordCount != '') {
				var recordPerPage = jQuery('#noOfEntries').val();
				if(recordPerPage == '0') recordPerPage = 1;
				pageCount = Math.ceil(totalRecordCount/recordPerPage);
				if(pageCount == 0){
					pageCount = 1;
				}
				element.text(pageCount);
				aDeferred.resolve();
				return aDeferred.promise();
			}
			this.getPageCount().then(function(data){
				var pageCount = data['result']['page'];
				if(pageCount == 0){
					pageCount = 1;
				}
				element.text(pageCount);
				aDeferred.resolve();
			});
		} else{
			aDeferred.resolve();
		}
		return aDeferred.promise();
	},
	
	/**
	 * Function to handle search event
	 */

	searchHandler : function(){
		var aDeferred = jQuery.Deferred();
		var completeParams = this.getCompleteParams();
		var cus_srch = jQuery('#custom_search_val').val();

		if(cus_srch != ''){
			completeParams['search_key'] =  'productcategory';
			completeParams['search_value'] =  cus_srch;
		}
		completeParams['page'] = 1;
		return this.getPageRecords(completeParams).then(
			function(data){
				aDeferred.resolve(data);
			},

			function(textStatus, errorThrown){
				aDeferred.reject(textStatus, errorThrown);
		});
		return aDeferred.promise();
	},

	/**
	 * Function to register event for Search
	 */
	registerEventForSearch : function(){
		var thisInstance = this;
		jQuery('#popupSearchButton, .cus_srch').on('click',function(e){
			var cls = jQuery(this).attr('class').split(' ')[0];
			jQuery('#custom_search_val').val('');
			if(cls == 'cus_srch'){
				jQuery(".cus_srch").removeClass('active');
				jQuery(this).addClass('active');
				jQuery('#custom_search_val').val(jQuery(this).data("cus_srch"));
			}
			jQuery('#totalPageCount').text("");
		
			var cus_srch = jQuery('#custom_search_val').val();
			if(cus_srch != ''){
				var anyBoxesChecked = false;
			    jQuery('input[type="checkbox"]').each(function() {
			        if ($(this).is(":checked")) {
			            anyBoxesChecked = true;
			        }
			    });
			    if(anyBoxesChecked){
			    	alert('Please submit changes before change category.');
					return false
			    }
			}

			thisInstance.searchHandler().then(function(data){
				jQuery('#pageNumber').val(1);
				jQuery('#pageToJump').val(1);
				thisInstance.updatePagination();
			});
		});
	},
	
	
	/**
	 * Function to register event for Searching on click of enter
	 */
	registerEventForEnter : function(){
		var thisInstance = this;
		jQuery('#searchvalue').keyup(function (e) {
            if (e.keyCode == 13) {
            jQuery('#popupSearchButton').trigger('click');
            }
		});
	},
	/**
	 * Function to register event for Searching on click of enter
	 */
	registerEventQtyEnter : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('keyup','.popup_qty',function(e){
			this.value = this.value.replace(/[^0-9\.]/g,'');
			if(this.value > 0){
				var avail_qty = parseInt($('#avail_qty_hid_'+jQuery(this).attr('id').split('popup_qty_')[1]).val());
				if( this.value <= avail_qty ){
					$(this).closest('td').find('input[type=checkbox]').prop('checked', true);			    	
				}else{
					this.value = '';
					alert('Quantity must be less than or equal to available quantity');
				}
			}else{
				$(this).closest('td').find('input[type=checkbox]').prop('checked', false);
			}
		});
	},

	/**
	 * Function to handle Sort
	 */
	sortHandler : function(headerElement){
		var aDeferred = jQuery.Deferred();
		var fieldName = headerElement.data('columnname');
		var sortOrderVal = headerElement.data('nextsortorderval');
		var sortingParams = {
			"orderby" : fieldName,
			"sortorder" : sortOrderVal
		}
		var completeParams = this.getCompleteParams();
		jQuery.extend(completeParams,sortingParams);
		return this.getPageRecords(completeParams).then(
			function(data){
				aDeferred.resolve(data);
			},

			function(textStatus, errorThrown){
				aDeferred.reject(textStatus, errorThrown);
			}
		);
		return aDeferred.promise();
	},

	/**
	 * Function to register Event for Sorting
	 */
	registerEventForSort : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('click','.listViewHeaderValues',function(e){
			var element = jQuery(e.currentTarget);
			thisInstance.sortHandler(element).then(function(data){
				thisInstance.updatePagination();
			});
		});
	},

	/**
	 * Function to handle next page navigation
	 */

	nextPageHandler : function(){
		var aDeferred = jQuery.Deferred();
		var pageLimit = jQuery('#pageLimit').val();
		var noOfEntries = jQuery('#noOfEntries').val();
		if(noOfEntries == pageLimit){
			var pageNumber = jQuery('#pageNumber').val();
			var nextPageNumber = parseInt(pageNumber) + 1;
			var pagingParams = {
					"page": nextPageNumber
				}
			var completeParams = this.getCompleteParams();
			jQuery.extend(completeParams,pagingParams);
			this.getPageRecords(completeParams).then(
				function(data){
					jQuery('#pageNumber').val(nextPageNumber);
					jQuery('#pageToJump').val(nextPageNumber);
					aDeferred.resolve(data);
				},

				function(textStatus, errorThrown){
					aDeferred.reject(textStatus, errorThrown);
				}
			);
		}
		return aDeferred.promise();
	},

	/**
	 * Function to handle Previous page navigation
	 */
	previousPageHandler : function(){
		var aDeferred = jQuery.Deferred();
		var pageNumber = jQuery('#pageNumber').val();
		var previousPageNumber = parseInt(pageNumber) - 1;
		if(pageNumber > 1){
			var pagingParams = {
				"page": previousPageNumber
			}
			var completeParams = this.getCompleteParams();
			jQuery.extend(completeParams,pagingParams);
			this.getPageRecords(completeParams).then(
				function(data){
					jQuery('#pageNumber').val(previousPageNumber);
					jQuery('#pageToJump').val(previousPageNumber);
					aDeferred.resolve(data);
				},

				function(textStatus, errorThrown){
					aDeferred.reject(textStatus, errorThrown);
				}
			);
		}
		return aDeferred.promise();
	},

	/**
	 * Function to register event for Paging
	 */
	registerEventForPagination : function(){
		var thisInstance = this;
		jQuery('#listViewNextPageButton').on('click',function(){
			thisInstance.nextPageHandler().then(function(data){
				thisInstance.updatePagination();
			});
		});
		jQuery('#listViewPreviousPageButton').on('click',function(){
			thisInstance.previousPageHandler().then(function(data){
				thisInstance.updatePagination();
			});
		});
		jQuery('#listViewPageJump').on('click',function(e){
			jQuery('#pageToJump').validationEngine('hideAll');
			var element = jQuery('#totalPageCount');
			var totalPageNumber = element.text();
			if(totalPageNumber == ""){
				var totalRecordCount = jQuery('#totalCount').val();
				if(totalRecordCount != '') {
					var recordPerPage = jQuery('#pageLimit').val();
					if(recordPerPage == '0') recordPerPage = 1;
					pageCount = Math.ceil(totalRecordCount/recordPerPage);
					if(pageCount == 0){
						pageCount = 1;
					}
					element.text(pageCount);
					return;
				}
				element.progressIndicator({});
				thisInstance.getPageCount().then(function(data){
					var pageCount = data['result']['page'];
					element.text(pageCount);
					element.progressIndicator({'mode': 'hide'});
			});
		}
		})

		jQuery('#listViewPageJumpDropDown').on('click','li',function(e){
			e.stopImmediatePropagation();
		}).on('keypress','#pageToJump',function(e){
			if(e.which == 13){
				e.stopImmediatePropagation();
				var element = jQuery(e.currentTarget);
				var response = Vtiger_WholeNumberGreaterThanZero_Validator_Js.invokeValidation(element);
				if(typeof response != "undefined"){
					element.validationEngine('showPrompt',response,'',"topLeft",true);
				} else {
					element.validationEngine('hideAll');
					var currentPageElement = jQuery('#pageNumber');
					var currentPageNumber = currentPageElement.val();
					var newPageNumber = parseInt(element.val());
					var totalPages = parseInt(jQuery('#totalPageCount').text());
					if(newPageNumber > totalPages){
						var error = app.vtranslate('JS_PAGE_NOT_EXIST');
						element.validationEngine('showPrompt',error,'',"topLeft",true);
						return;
					}
					if(newPageNumber == currentPageNumber){
						var message = app.vtranslate('JS_YOU_ARE_IN_PAGE_NUMBER')+" "+newPageNumber;
						var params = {
							text: message,
							type: 'info'
						};
						Vtiger_Helper_Js.showMessage(params);
						return;
					}
					var pagingParams = {
						"page": newPageNumber
					}
					var completeParams = thisInstance.getCompleteParams();
					jQuery.extend(completeParams,pagingParams);
					thisInstance.getPageRecords(completeParams).then(
						function(data){
							currentPageElement.val(newPageNumber);
							thisInstance.updatePagination();
							element.closest('.btn-group ').removeClass('open');
						},
						function(textStatus, errorThrown){
						}
					);
				}
				return false;
		}
		});
	},

	registerEventForListViewEntries : function(){
		var thisInstance = this;
		var popupPageContentsContainer = this.getPopupPageContainer();
		popupPageContentsContainer.on('click','.listViewEntries',function(e){
			if(app.getModuleName() == 'SalesOrder'){
			    //jQuery(this).children().find('input[type=checkbox]').prop('checked', true);
			    e.stopPropagation();		    							
			}else{
				thisInstance.getListViewEntries(e);
			}
		});
	},

	triggerDisplayTypeEvent : function() {
		var widthType = app.cacheGet('widthType', 'narrowWidthType');
		if(widthType) {
			var elements = jQuery('.listViewEntriesTable').find('td,th');
			elements.addClass(widthType);
		}
	},
		/**
	 * Function to get page count and total number of records in list
	 */
	getPageCount : function(){
		var aDeferred = jQuery.Deferred();
		var pageJumpParams = {
			'mode' : "getPageCount"
		}
		var completeParams = this.getCompleteParams();
		jQuery.extend(completeParams,pageJumpParams);
		AppConnector.request(completeParams).then(
			function(data) {
				var response;
				if(typeof data != "object"){
					response = JSON.parse(data);
				} else{
					response = data;
				}
				aDeferred.resolve(response);
			},
			function(error,err){

			}
		);
		return aDeferred.promise();
	},
	
	/**
	 * Function to show total records count in listview on hover
	 * of pageNumber text
	 */
	registerEventForTotalRecordsCount : function(){
		var thisInstance = this;
		jQuery('.pageNumbers').on('hover',function(e){
			var element = jQuery(e.currentTarget);
			var totalRecordsElement = jQuery('#totalCount');
			var totalNumberOfRecords = totalRecordsElement.val();
			if(totalNumberOfRecords == '') {
				thisInstance.getPageCount().then(function(data){
					totalNumberOfRecords = data['result']['numberOfRecords'];
					totalRecordsElement.val(totalNumberOfRecords);
				});
			}
			if(totalNumberOfRecords != ''){
				var titleWithRecords = app.vtranslate("JS_TOTAL_RECORDS")+" "+totalNumberOfRecords;
				element.data('tooltip').options.title = titleWithRecords;
			} else {
				element.data('tooltip').options.title = "";
			}
		})
	},
	
	/**
	 * Function to update Pagining status
	 */
	updatePagination : function(){
		var previousPageExist = jQuery('#previousPageExist').val();
		var nextPageExist = jQuery('#nextPageExist').val();
		var previousPageButton = jQuery('#listViewPreviousPageButton');
		var nextPageButton = jQuery('#listViewNextPageButton');
		var listViewEntriesCount = jQuery('#noOfEntries').val();
		var pageStartRange = jQuery('#pageStartRange').val();
		var pageEndRange = jQuery('#pageEndRange').val();
		var pageJumpButton = jQuery('#listViewPageJump');
		var pages = jQuery('#totalPageCount').text();

		if(pages == 1){
			pageJumpButton.attr('disabled',"disabled");
		}
		if(pages > 1){
			pageJumpButton.removeAttr('disabled');
		}

		if(previousPageExist != ""){
			previousPageButton.removeAttr('disabled');
		} else if(previousPageExist == "") {
			previousPageButton.attr("disabled","disabled");
		}

		if((nextPageExist != "") && (pages >1)){
			nextPageButton.removeAttr('disabled');
		} else if((nextPageExist == "") || (pages == 1)) {
			nextPageButton.attr("disabled","disabled");
		}
		if(listViewEntriesCount != 0){
			var pageNumberText = pageStartRange+" "+app.vtranslate('to')+" "+pageEndRange;
			jQuery('.pageNumbers').html(pageNumberText);
		} else {
			jQuery('.pageNumbers').html("");
		}

	},

	registerEvents: function(){
		var pageNumber = jQuery('#pageNumber').val();
		if(pageNumber == 1){
			jQuery('#listViewPreviousPageButton').attr("disabled", "disabled");
		}
		this.registerEventForSelectAllInCurrentPage();
		this.registerSelectButton();
		this.registerEventForCheckboxChange();
		this.registerEventForSearch();
		this.registerEventForEnter();
        this.registerEventQtyEnter();
		this.registerEventForSort();
		this.registerEventForListViewEntries();
		//this.triggerDisplayTypeEvent();
		var popupPageContainer = jQuery('#popupPageContainer');
		if(popupPageContainer.length > 0){
			this.registerEventForTotalRecordsCount();
			this.registerEventForPagination();
			jQuery('.pageNumbers').tooltip();
		}
	}
});

jQuery(document).ready(function() {	
	var sel_qty_val = '';
	var sel_prod_id = '';	
	var sel_prod_opt = '';	
	var sel_prod_opt_val = '';	
	var hid_area_store_sel_ids = '';
	if(window.opener && !window.opener.closed){
		sel_qty_val = window.opener.$("#sel_qty_val").val();
		sel_prod_id = window.opener.$("#sel_prod_id").val();
		sel_prod_opt = window.opener.$("#sel_prod_opt").val().split('###');
		hid_area_store_sel_ids = window.opener.$("#hid_area_store_sel_ids").val().split('###');		
		var store_id = window.opener.$('#'+hid_area_store_sel_ids[0]+' option:selected').val();
		
		if(sel_prod_opt.length > 1){		
			sel_prod_opt_val = sel_prod_opt[1];			
			var prod_desc_ary = sel_prod_opt[0].split('||');
			var prod_desc = '';
			if(prod_desc_ary.length > 1){
				sel_prod_opt_val = prod_desc_ary[0];
				prod_desc = prod_desc_ary[1];
			}
		}
	}
	$( "tr.listViewEntries" ).each(function() {
	  if(sel_prod_id != '' && jQuery(this).data('id') == sel_prod_id){
	  	jQuery('#popup_qty_'+sel_prod_id).val(sel_qty_val)
		jQuery(this).closest('table').addClass('prod-highlight');
		$("html, body").scrollTop($(this).offset().top);
		$("#sel_opt_"+sel_prod_id).val(sel_prod_opt_val).find("option[value=" + sel_prod_opt_val +"]").attr('selected', true);
		$('#popup_prod_desc_'+sel_prod_id).val(prod_desc);
		//jQuery('#sel_opt_'+sel_prod_id).prop()
	  }  
	});
	var popupInstance = Vtiger_Popup_Js.getInstance();
	var triggerEventName = jQuery('.triggerEventName').val();
	var documentHeight = (jQuery(document).height())+'px';
	jQuery('#popupPageContainer').css('height',documentHeight);
	popupInstance.setEventName(triggerEventName);
	popupInstance.registerEvents();
});
