/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Detail_Js("Inventory_Detail_Js",{
    sendEmailPDFClickHandler : function(url){
        var popupInstance = Vtiger_Popup_Js.getInstance();
        popupInstance.show(url,function(){}, app.vtranslate('JS_SEND_PDF_MAIL') );
    }

},{
    
    /**
	 * Function to register event for adding related record for module
	 */
	registerEventForAddingRelatedRecord : function(){
		var thisInstance = this;
		var detailContentsHolder = this.getContentHolder();
		detailContentsHolder.on('click','[name="addButton"]',function(e){
			var element = jQuery(e.currentTarget);
			var selectedTabElement = thisInstance.getSelectedTab();
			var relatedModuleName = thisInstance.getRelatedModuleName();
            var quickCreateNode = jQuery('#quickCreateModules').find('[data-name="'+ relatedModuleName +'"]');

			if(quickCreateNode.length <= 0 || selectedTabElement.data('labelKey') == 'Activities') {
                window.location.href = element.data('url');
                return;
            }

			var relatedController = new Vtiger_RelatedList_Js(thisInstance.getRecordId(), app.getModuleName(), selectedTabElement, relatedModuleName);
			relatedController.addRelatedRecord(element);
		})
	},
    /**
    * Function which will regiter all events for this page
    */
    registerEvents : function(){
		this._super();
        this.registerClickEvent();
    },

    /**
	 * Event handler which is invoked on click event happened on inventoryLineItemDetails
	 */
    registerClickEvent : function(){
        this.getDetails().on('click','.inventoryLineItemDetails',function(e){
            alert(jQuery(e.currentTarget).data("info"));
        });
    },

    /**
	 * This function will return the current page
	 */
    getDetails : function(){
        //hide cuts spec
        $("td[id^='SalesOrder_detailView_fieldLabel_cf_']").each(function(index) {            
            if(jQuery(this).text().toLowerCase() == 'cut spec'){
                var fcls = jQuery(this).closest('td');          
                fcls.hide();
                fcls.next().hide();                     
            }
            if(jQuery(this).text().toLowerCase() == 'area store'){
                jQuery(this).html('<lable class="muted pull-right marginRight10px">Area</label>');
                var fcls = jQuery(this).closest('td');
                fcls.closest( "tr" ).prev().hide();
                if( fcls.next().text().trim() && fcls.next().text().indexOf('@@@') &&  fcls.next().text().indexOf('###') ){
                    // var hid_html = fcls.next().text().split('@@@')[1].replace('###', ' ');
                    fcls.next().text(fcls.next().text().split('@@@')[1].split('###')[0]);
                }
            }            
        });        
        return jQuery('.details');
    }

});