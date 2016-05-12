{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
{strip}
<input type='hidden' id='pageNumber' value="{$PAGE_NUMBER}">
<input type='hidden' id='pageLimit' value="{$PAGING_MODEL->getPageLimit()}">
<input type="hidden" id="noOfEntries" value="{$LISTVIEW_ENTIRES_COUNT}">
<input type="hidden" id="pageStartRange" value="{$PAGING_MODEL->getRecordStartRange()}" />
<input type="hidden" id="pageEndRange" value="{$PAGING_MODEL->getRecordEndRange()}" />
<input type="hidden" id="view" value="{$VIEW}"/>
<input type="hidden" id="previousPageExist" value="{$PAGING_MODEL->isPrevPageExists()}" />
<input type="hidden" id="nextPageExist" value="{$PAGING_MODEL->isNextPageExists()}" />
<input type="hidden" id="totalCount" value="{$LISTVIEW_COUNT}" />
{if (!empty($SUBPRODUCTS_POPUP)) and (!empty($PARENT_PRODUCT_ID))}
	<input type="hidden" id="subProductsPopup" value="{$SUBPRODUCTS_POPUP}" />
	<input type="hidden" id="parentProductId" value="{$PARENT_PRODUCT_ID}" />
{/if}

<div class="contents-topscroll">
    <div class="topscroll-div">
        &nbsp;
    </div>
</div>
<style type="text/css">
	div.img-cont {
	    width:200px;
	    height:200px;
	    position:relative;
	    display:inline-block;
	    overflow:hidden;
	}

	div.img-cont > img {
	    position:absolute;
	    min-height:100%;
	    display:block;
	    -webkit-transform: translate(-50%, -50% !important);
	    min-width:100%;
	}	
	.tbl-cont{
		width: 20%;float: left;
	}
	.prod-highlight{
		border: green 2px solid !important;
	}
	.qty-empty-highlight{
		border: red 1px solid !important;
	}
	table{
		height: 400px;
	}	
</style>

<div id="popupEntriesDiv">	
	<input type="hidden" value="{$ORDER_BY}" id="orderBy">
	<input type="hidden" value="{$SORT_ORDER}" id="sortOrder">
	<input type="hidden" value="Inventory_Popup_Js" id="popUpClassName"/>
	{assign var=WIDTHTYPE value=$CURRENT_USER_MODEL->get('rowheight')}
		{foreach item=LISTVIEW_ENTRY from=$LISTVIEW_ENTRIES name=popupListView}
			{$temp_val = 1}
			<table class="table table-bordered tbl-cont">
			<tr class="listViewEntries" data-id="{$LISTVIEW_ENTRY->getId()}" data-name='{$LISTVIEW_ENTRY->getName()}' data-info='{ZEND_JSON::encode($LISTVIEW_ENTRY->getRawData())}'
				{if $GETURL neq '' } data-url='{$LISTVIEW_ENTRY->$GETURL()}' {/if}  id="{$MODULE}_popUpListView_row_{$smarty.foreach.popupListView.index+1}">
				<td class="listViewEntryValue {$WIDTHTYPE}" style="vertical-align: top;">
				{foreach item=LISTVIEW_HEADER from=$LISTVIEW_HEADERS}
				{assign var=LISTVIEW_HEADERNAME value=$LISTVIEW_HEADER->get('name')}
				<div style="width: 100%;text-align: center;">
					{if $temp_val == 1}					
						<div id="imageContainer" class="img-cont">
							{foreach key=ITER item=IMAGE_INFO from=$IMAGE_DETAILS}							
								{if !empty($IMAGE_INFO[$LISTVIEW_ENTRY->getId()][0].path) && !empty({$IMAGE_INFO[$LISTVIEW_ENTRY->getId()][0].orgname})}
									<img src="{$IMAGE_INFO[$LISTVIEW_ENTRY->getId()][0].path}_{$IMAGE_INFO[$LISTVIEW_ENTRY->getId()][0].orgname}" >
								{/if}
							{/foreach}
						</div>
					{/if}
					{if $LISTVIEW_HEADER->isNameField() eq true or $LISTVIEW_HEADER->get('uitype') eq '2'}
						
						<div style="width:100%">
							<div style="float:left;width:90%;font-size: 14px;padding-bottom:10px;text-align: left;"><a><b>{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}</b></a></div>
							<div style="float:left;widht:10%">							
							{if $MULTI_SELECT}				
								<input class="entryCheckBox" type="checkbox" data-chk_pid="{$LISTVIEW_ENTRY->getId()}" />				
							{/if}							
							</div>
						</div>

						{if $temp_val == 1}
							<div style="width: 100%;display: none;" id="opt_con_{$LISTVIEW_ENTRY->getId()}">
								<div style="float: left;width: 30%;text-align: left;">Options : </div>
								<div style="float: left;width: 70%;text-align: left;" id="opt_val_con_{$LISTVIEW_ENTRY->getId()}"></div>
							</div>
							<div style="width: 100%">
								<div style="float: left;width: 30%;text-align: left;">Quantity:</div>
								<div style="float: left;width: 70%;text-align: left;">									
									<input id="popup_cmt_{$LISTVIEW_ENTRY->getId()}" type="hidden"></input>
									<input type="text" class="span2 popup_qty" id="popup_qty_{$LISTVIEW_ENTRY->getId()}" value="" style="width: 50px;"></input>&nbsp;&nbsp;Kg
								</div>
							</div>		
						{/if}					
					{else if $LISTVIEW_HEADER->get('uitype') eq '72'}
						{assign var=CURRENCY_SYMBOL_PLACEMENT value={$CURRENT_USER_MODEL->get('currency_symbol_placement')}}
						{if $CURRENCY_SYMBOL_PLACEMENT eq '1.0$'}
							{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}{$LISTVIEW_ENTRY->get('currencySymbol')}
						{else}
							<div style="width: 100%">
								<div style="float: left;width: 30%;text-align: left;">Price:</div>
								<div style="float: left;width: 70%;text-align: left;"><b>{$LISTVIEW_ENTRY->get('currencySymbol')}{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}</b></div>
							</div>							
						{/if}
					{else}
						{*$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)*}
					{/if}
				</div>
				{$temp_val = $temp_val+1}				
				{/foreach}
				</td>
				<!-- <td class="listViewEntryValue {$WIDTHTYPE}"> -->
					{if $LISTVIEW_ENTRY->get('subProducts') eq true}
					<!-- <a class="subproducts"><b>{vtranslate('LBL_SUB_PRODUCTS',$MODULE_NAME)}</b></a> -->
						<!--<img class="lineItemPopup cursorPointer alignMiddle" data-popup="ProductsPopup" title="{vtranslate('Products',$MODULE)}" data-module-name="Products" data-field-name="productid" src="{vimage_path('Products.png')}"/>-->
					{else}
					{*vtranslate('NOT_A_BUNDLE',$MODULE_NAME)*}
					{/if}
				<!-- </td> -->
			</tr>
			</table>
		{/foreach}

	<!--added this div for Temporarily -->
	{if $LISTVIEW_ENTIRES_COUNT eq '0'}
	<div class="row-fluid">
		<div class="emptyRecordsDiv">{vtranslate('LBL_NO', $MODULE)} {vtranslate($MODULE, $MODULE)} {vtranslate('LBL_FOUND', $MODULE)}.</div>
	</div>
	{/if}
</div>
{if (!empty($SUBPRODUCTS_POPUP)) and (!empty($PARENT_PRODUCT_ID))}
	<div class="row-fluid" style="margin-top:10px">
		<div class="span6">&nbsp;</div>
		<div class="span6">
			<div class="pull-right">
				<button type="button" class="btn" id="backToProducts"><strong>{vtranslate('LBL_BACK_TO_PRODUCTS', $MODULE)}</strong></button>
			</div>
		</div>
	</div>
{/if}
{/strip}
{literal}
<script type="text/javascript">
var prod_spec = {"38639":{"label":{"1":"Small","2":"Medium","3":"Large"}},"38652":{"label" :{"1":"Small","2":"Medium","3":"Large"}}};  
jQuery.each(prod_spec, function (key, data) {	
	options = '<select id="sel_opt_'+key+'" style="width:70%;">';
	options += '<option value="">Choose Option</option>';
	jQuery.each(data, function (index, data1) {
	  jQuery.each(data1, function (index1, data2) {          
	  	options += '<option value="'+index1+'">'+data2+'</option>';
	  })
	});
	options += '</select>';
	jQuery('#opt_val_con_'+key).html(options);		
	jQuery('#opt_con_'+key).show();
}); 
</script>
{/literal}