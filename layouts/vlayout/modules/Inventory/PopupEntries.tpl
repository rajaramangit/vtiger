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
		height: 430px;
	}
	.sold-out{
		color: red;
		font-size: 25px;
		position: absolute;
		z-index: 1000;
		width: 100%;
		text-align: center;
	}
	.lbl-con{
		float: left;width: 35%;text-align: left;
	}
	.val_con{
		float: left;width: 65%;text-align: left;
	}
	.full-width{
		width: 100%;
	}
	.avail_qty{
		padding-bottom: 25px;
	}
	.pad-btm-15{
		padding-bottom: 15px;
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
				<div class="full-width" style="text-align: center;">
					{if $temp_val == 1}					
						<div id="imageContainer" class="img-cont">
							<div id="sold_out_{$LISTVIEW_ENTRY->getId()}" class="sold-out">Sold Out</div>
							{foreach key=ITER item=IMAGE_INFO from=$IMAGE_DETAILS}							
								{if !empty($IMAGE_INFO[$LISTVIEW_ENTRY->getId()][0].path) && !empty({$IMAGE_INFO[$LISTVIEW_ENTRY->getId()][0].orgname})}
									<img src="{$IMAGE_INFO[$LISTVIEW_ENTRY->getId()][0].path}_{$IMAGE_INFO[$LISTVIEW_ENTRY->getId()][0].orgname}" >
								{/if}
							{/foreach}							
						</div>
					{/if}
					{if $LISTVIEW_HEADER->isNameField() eq true or $LISTVIEW_HEADER->get('uitype') eq '2'}
						
						<div class="full-width">
							<div style="float:left;width:90%;font-size: 14px;padding-bottom:10px;text-align: left;"><a><b>{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}</b></a></div>
							<div style="float:left;widht:10%">							
							{if $MULTI_SELECT}				
								<input class="entryCheckBox" id="chk-bx-id-{$LISTVIEW_ENTRY->getId()}" type="checkbox" data-chk_pid="{$LISTVIEW_ENTRY->getId()}" disabled="disabled" />
							{/if}							
							</div>
						</div>
					
					{else if $LISTVIEW_HEADER->get('uitype') eq '72'}
						{assign var=CURRENCY_SYMBOL_PLACEMENT value={$CURRENT_USER_MODEL->get('currency_symbol_placement')}}
						{if $CURRENCY_SYMBOL_PLACEMENT eq '1.0$'}
							{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}{$LISTVIEW_ENTRY->get('currencySymbol')}
						{else}
							<div class="full-width">
								<div class="lbl-con">Price:</div>
								<div class="val_con pad-btm-15"><b><span id="prod_volume_{$LISTVIEW_ENTRY->getId()}"></span>{$LISTVIEW_ENTRY->get('currencySymbol')}&nbsp;{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}</b></div>
							</div>							
						{/if}
					{else}
						{*$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)*}
					{/if}
					{if $temp_val == 3}
						
						<div class="full-width">
							<div class="lbl-con" >Available Quantity:</div>
							<div class="val_con avail_qty">
								<span id="avail_qty_{$LISTVIEW_ENTRY->getId()}">0</span> Kg
							</div>
						</div>
						
						<input type="hidden" id="avail_qty_hid_{$LISTVIEW_ENTRY->getId()}" value="0"></input>
						
						<div class="full-width" style="display: none;" id="opt_con_{$LISTVIEW_ENTRY->getId()}">
							<div class="lbl-con">Options : </div>
							<div class="val_con pad-btm-15" id="opt_val_con_{$LISTVIEW_ENTRY->getId()}"></div>
						</div>						
						
						<div class="full-width" id="prod_incr_decr_cont_{$LISTVIEW_ENTRY->getId()}" style="display: none;">
							<div class="lbl-con">Quantity:</div>
							<div class="val_con">																	
								<input type="hidden" id="min_qty_{$LISTVIEW_ENTRY->getId()}" value="0"></input>
								<i title="Decriment" class="icon-trash prod-decri" style="display: inline-block; background-position: -433px -97px;" id="prod_decri_{$LISTVIEW_ENTRY->getId()}"></i>
								<input type="text" class="span2 popup_qty" disabled="disabled" id="popup_qty_{$LISTVIEW_ENTRY->getId()}" value="0" style="width: 20%;" readonly="true"></input>
								<i title="Increment" class="icon-trash prod-incri" style="display: inline-block; background-position: -407px -97px;" id="prod_incri_{$LISTVIEW_ENTRY->getId()}"></i>
							</div>
						</div>
						<!-- <div style="width: 100%">
							<div style="float: left;width: 35%;text-align: left;">Description:</div>
							<div style="float: left;width: 65%;text-align: left;">
								<textarea id="popup_prod_desc_{$LISTVIEW_ENTRY->getId()}" style="width: 70%;"></textarea>									
							</div>
						</div>									 -->
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
<script>document.write("<script type='text/javascript' src='js/product_json.js?v=" + Date.now() + "'><\/script>");</script>
<script type="text/javascript">
// var prod_spec = {"39798":{"label":{"3":"Small","4":"Big"}},"38639":{"label":{"5":"Half Slice","6":"Full Slice"}},"40666":{"label":{"7":"Small ","8":"Medium","9":"Large"}},"40732":{"label":{"10":"Small cut","11":"Medium cut","12":"Large cut"}},"40733":{"label":{"13":"Small cut","14":"Medium cut","15":"Large cut"}},"40734":{"label":{"16":"Small piece","17":"Medium piece"}},"40735":{"label":{"20":"Shoulder whole","18":"Shoulder small piece","19":"Shoulder large piece"}}} ;
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
	//jQuery('#opt_con_'+key).show();
});

// var product_min_qty ={"43":{"min_qty":"0.5","prodcut_spec":"1Kg"},"79":{"min_qty":"0.25","prodcut_spec":"1Kg"},"85":{"min_qty":"0.25","prodcut_spec":"1Kg"},"97":{"min_qty":"0.5","prodcut_spec":"1Kg"},"38639":{"min_qty":"0.5","prodcut_spec":"1Kg"},"38652":{"min_qty":"1","prodcut_spec":"1Unit"},"39798":{"min_qty":"0.5","prodcut_spec":"1kg"},"40857":{"min_qty":"1","prodcut_spec":"Pack of 2"},"39832":{"min_qty":"0.5","prodcut_spec":"500gm"},"40657":{"min_qty":"0.5","prodcut_spec":"1Kg"},"40666":{"min_qty":"1","prodcut_spec":"1Kg"},"40732":{"min_qty":"0.5","prodcut_spec":"1Kg"},"40733":{"min_qty":"0.5","prodcut_spec":"1Kg"},"40734":{"min_qty":"0.5","prodcut_spec":"1Kg"},"40735":{"min_qty":"0.5","prodcut_spec":"1Kg"}} ; 

jQuery.each(product_min_qty, function (key, data) {
	jQuery('#min_qty_'+key).val(data.min_qty);
	jQuery('#popup_qty_'+key).val(data.min_qty);
	// if(data.prodcut_spec){
	// 	var volume = data.prodcut_spec.toLowerCase();
	// 	var volume_txt  = '';
	// 	if(volume.indexOf('kg')){			
	// 		volume_txt = data.min_qty+'&nbsp;Kg&nbsp;&nbsp;&nbsp;';
	// 	}
	// 	else if(volume.indexOf('unit')){
	// 		volume_txt = data.prodcut_spec+'&nbsp;&nbsp;&nbsp;';
	// 	}
	// 	else if(volume.indexOf('pack')){
	// 		volume_txt = data.prodcut_spec+'&nbsp;&nbsp;&nbsp;';
	// 	}
	// 	jQuery('#prod_volume_'+key).html(volume_txt);
	// }	
});


</script>
{/literal}
<script type="text/javascript">
	var products_qty_of_store = {$PRODUCTS_QTY_OF_STORE};	
	jQuery(document).ready(function(){
		
		jQuery('.prod-incri').on('click',function(){
			var prod_id = jQuery(this).attr('id').split('prod_incri_')[1];
			var this_prod_qty_id = jQuery('#popup_qty_'+prod_id);
			var prod_min_qty = jQuery('#min_qty_'+prod_id).val();
			var prod_qty = ( parseFloat(this_prod_qty_id.val()) ) + ( parseFloat(prod_min_qty) );
			
			if(prod_qty <= jQuery('#avail_qty_hid_'+prod_id).val() ){				
				this_prod_qty_id.val(prod_qty);
				if(prod_qty > 0){
					jQuery('#chk-bx-id-'+prod_id).attr('checked','checked');
				}
			}else{
				$('#avail_qty_'+prod_id).css({
										   'color' : 'red'
										});
			}
		});

		jQuery('.prod-decri').on('click',function(){
			var prod_id 		= jQuery(this).attr('id').split('prod_decri_')[1];
			var this_prod_qty_id 	= jQuery('#popup_qty_'+prod_id);
			var prod_min_qty 		= jQuery('#min_qty_'+prod_id).val();
			var prev_prod_qty 		= parseFloat(this_prod_qty_id.val());

			var prod_qty = ( prev_prod_qty ) - ( parseFloat(prod_min_qty) );
			if(prod_qty >= parseFloat(jQuery('#min_qty_'+prod_id).val()) ){				
				this_prod_qty_id.val(prod_qty);
				if(prod_qty > 0){
					jQuery('#chk-bx-id-'+prod_id).attr('checked','checked');	
				}
			}
		});

		var hid_area_store_sel_ids = window.opener.$("#hid_area_store_sel_ids").val().split('###');				
		window.opener.$('#'+hid_area_store_sel_ids[1]).attr('disabled','disabled');
		window.opener.$("#"+hid_area_store_sel_ids[1]).trigger("liszt:updated");		

		var prod_id_array = {};	
		jQuery('tr').each(function(index){		
			var ary_key = parseInt(jQuery(this).data('id'));			
			var parseinfo = jQuery(this).data('info');
			var pkey = parseinfo[0].split('(#')[1].slice(0,-1);				
			prod_id_array[pkey] = jQuery(this).data('id');
		});

		$.map(products_qty_of_store,function(val,key){
			if(prod_id_array.hasOwnProperty(val.product_id)){
				var prod_min_qty = jQuery('#min_qty_'+prod_id_array[val.product_id]).val();
				if( parseFloat(val.qty) < parseFloat(prod_min_qty) || parseFloat(val.qty) == 0 ){
					
				}else{
					$('#popup_qty_'+prod_id_array[val.product_id]).removeAttr('disabled');
					$('#chk-bx-id-'+prod_id_array[val.product_id]).removeAttr('disabled');
					$('#opt_con_'+prod_id_array[val.product_id]).show();
					$('#prod_incr_decr_cont_'+prod_id_array[val.product_id]).show();
					$('#sold_out_'+prod_id_array[val.product_id]).hide();
				}		
				$('#avail_qty_hid_'+prod_id_array[val.product_id]).val(val.qty);
				$('#avail_qty_'+prod_id_array[val.product_id]).html(val.qty);
			}
     	});
	});          	
</script>
