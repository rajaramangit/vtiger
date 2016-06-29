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
{include file="ums_integration/call_notifiction.php"}
{literal}
<script type="text/javascript">
// Rahul create Call_lead Function to call agent and user
function tempAlert(msg,duration)//css properties for popup message box.

 {
 var colorcode="";
 var colorname="{/literal}{$USER_MODEL->get('theme')}{literal}";
 switch (colorname)
 {      
 case "softed" :  colorcode='#1560BD'; 
 break;
 case "woodspice" : colorcode='#C19803'; 
 break;  
 case "alphagrey" :  colorcode='#fff';
 break;    
 case "bluelagoon" : colorcode='#204E81';
 break;   
 case "nature" :  colorcode='#008D4C';
 break;   
 case "orchid" :   colorcode='#C65479';  
 break;   
 case  "firebrick" :  colorcode='#E51400';  
 break;
 case "twilight" :colorcode='#fff';   
 break;    
 case "almond" :colorcode='#894400';  
 break; 
 } 
 var el = document.createElement("div"); el.setAttribute("style","background-color:#fff; margin: 0 auto; width:280px; height:5px; padding:70px; border-radius: 12px; color:"+ colorcode +"; -webkit-box-shadow: rgba(0,0,0,0.5) 0px 3px 10px, rgba(0,0,0,.75) 0 0 70px inset;-moz-box-shadow: rgba(0,0,0,0.5) 0px 3px 10px, rgba(0,0,0,.75) 0 0 70px inset;box-shadow: rgba(0,0,0,0.5) 0px 3px 10px, rgba(0,0,0,.75) 0 0 70px inset; background: #7d7e7d; background: -moz-linear-gradient(top, #7d7e7d, #0e0e0e); background: -webkit-linear-gradient(top, #7d7e7d, #0e0e0e); background: -ms-linear-gradient(top, #7d7e7d, #0e0e0e); background: -o-linear-gradient(top, #7d7e7d, #0e0e0e); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7d7e7d', endColorstr='#0e0e0e',GradientType=0 );background: linear-gradient(to bottom, #7d7e7d, #0e0e0e); border-top: 2px solid #666; overflow: hidden; font-weight: bold; align:centre; position:absolute; top:35%; left:35%; border-color: #FF0033;border-top-style: none; font-size: x-large; font-family: 'Helvetica Neue,Helvetica,Arial,sans-serif', cursive, sans-serif;");
 el.innerHTML = msg;  
 setTimeout(function(){  el.parentNode.removeChild(el); },duration); 
 document.body.appendChild(el);
 } 
 

function missedcall_org()
 {
        var org_mobile=document.getElementById("CallHistory_editView_fieldName_cf_718").value; //get current callhistory mobile number from textbox        
        
    var agent_mobile=document.getElementById("CURRENT_USER_ID").value;  //get current login agent mobile number from textbox 
        
    var urlid=document.URL; 
    urlid=urlid.split('/index.php')[0];
    var url1 = "ums_integration/Call_Generic_Webservice_for_Calling.php?lead_mobile="+org_mobile+"&agent_mobile="+agent_mobile;//+"&urlid="+urlid; // pass org_mobile no. and agent_mobile no. to webservice for call agent and lead 
                jQuery.ajax({ 
                 type: "GET",
                 url:url1,              
                async: false,
                 success:function(response)
                 {
                
                        if(response!='')        
                        {
                                //alert("Response   "+response);                
                        }                       
                 }
        });
    tempAlert('Calling please wait',2000);
 } 


 
 <!----Added by Dipti---->
 function call_org()
 {
	var org_mobile=document.getElementById("Accounts_editView_fieldName_phone").value; //get current organization mobile number from textbox	
	
    var agent_mobile=document.getElementById("CURRENT_USER_ID").value;  //get current login agent mobile number from textbox 
	
    var urlid=document.URL; 
    urlid=urlid.split('/index.php')[0];
    var url1 = "ums_integration/Call_Generic_Webservice_for_Calling.php?lead_mobile="+org_mobile+"&agent_mobile="+agent_mobile;//+"&urlid="+urlid; // pass org_mobile no. and agent_mobile no. to webservice for call agent and lead 
		jQuery.ajax({ 
		 type: "GET",
		 url:url1, 		
		async: false,
		 success:function(response)
		 {
		
			if(response!='')	
			{
				//alert("Response   "+response);		
			}			
		 }
	});
    tempAlert('Calling please wait',2000);
 } 
 function sendsms_org()
{	
	
		var el = document.createElement("div");
		el.id="elid";
        var label1=document.createElement("label");
        label1.innerHTML="SMS Body: ";
        	var but1=document.createElement("button");
			but1.textContent="Send";
			but1.onclick=function()
			{
				var smstext=document.getElementById("message").value;
				var lead_mobile=document.getElementById("Accounts_editView_fieldName_phone").value;
				jQuery.ajax({ 
				 type: "GET",
				 url:"ums_integration/Send_SMS_Notifier.php?UMSTo="+lead_mobile+"&UMSText="+smstext, // this webservice return contact number no.					
				 async: false,
				 success:function(response)
				 {
				  if(response!='')	
				  {
					//alert("Response   "+response);		
				 }
				 }
				});		 
			//	alert("ok"+smstext+"userid "+lead_mobile);
				var el=document.getElementById("elid");
				document.body.removeChild(el);
				};
				but1.id="send";
				but1.setAttribute("style","margin-right:5%;width:25%;margin-left:20%;");
       
      
	
		var but2=document.createElement("button");
        but2.textContent="Cancel";
		but2.onclick=closetemp;
		but2.id="cancel";
		
		var textarea=document.createElement("input");
		textarea.type="text";
		textarea.id="message";
	
        el.appendChild(label1);
        el.appendChild(document.createElement("br"));
		el.appendChild(textarea);
        el.appendChild(document.createElement("br"));
        el.appendChild(document.createElement("br"));
		
			  el.appendChild(but1);
		

		el.appendChild(but2);
		
		el.setAttribute("style","background: rgb(226,226,226); background: -moz-linear-gradient(top,  rgba(226,226,226,1) 0%, rgba(219,219,219,1) 50%, rgba(209,209,209,1) 51%, rgba(254,254,254,1) 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(226,226,226,1)), color-stop(50%,rgba(219,219,219,1)), color-stop(51%,rgba(209,209,209,1)), color-stop(100%,rgba(254,254,254,1))); background: -webkit-linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); background: -o-linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); background: -ms-linear-gradient(top,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); background: linear-gradient(to bottom,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 51%,rgba(254,254,254,1) 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e2e2e2', endColorstr='#fefefe',GradientType=0 ); position:fixed;top:200px;left:500px;z-index:1000;overflow: hidden;  align:centre; position:absolute;font-size: medium; font-family: Bookshelf Symbol 7; font-weight: bold;color: #000000;padding:30px;-webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;-moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;");
		document.body.appendChild(el);

}
 
 


function closetemp()
{
 var el=document.getElementById("elid");
 document.body.removeChild(el);

}
 
 </script> 
 {/literal}

	{assign var="MODULE_NAME" value=$MODULE_MODEL->get('name')}
	<input type="hidden" id="CURRENT_USER_ID" value='{$CURRENT_USER_MODEL->get('phone_mobile')}'/><!-- Rahul add hidden input filed for get current user mobile number -->	
	
	<input id="recordId" type="hidden" value="{$RECORD->getId()}" />
	<div class="detailViewContainer">
		<div class="row-fluid detailViewTitle">
			<div class="{if $NO_PAGINATION} span12 {else} span10 {/if}">
				<div class="row-fluid">
					<div class="span5">
						<div class="row-fluid">
							{include file="DetailViewHeaderTitle.tpl"|vtemplate_path:$MODULE}
						</div>
					</div>

					<div class="span7">
						<div class="pull-right detailViewButtoncontainer">
							<div class="btn-toolbar">
							
							<!-----Added by Dipti------->
							{if $MODULE_NAME eq 'Accounts'}
							<span class="btn-group">
								<button class="btn" id="call" onclick="call_org();" >
									<strong>Call</strong>
								</button>
							</span>
							<span class="btn-group">
								<button class="btn" id="sms" onclick="sendsms_org();" >
									<strong>SMS</strong>
								</button>
							</span>
							{/if}
							{if $MODULE_NAME eq 'CallHistory'}
                                                        <span class="btn-group">
                                                                <button class="btn" id="call" onclick="missedcall_org();" >
                                                                        <strong>Call</strong>
                                                                </button>
                                                        </span>
							{/if}
							
							
							{foreach item=DETAIL_VIEW_BASIC_LINK from=$DETAILVIEW_LINKS['DETAILVIEWBASIC']}
							<span class="btn-group">
								<button class="btn" id="{$MODULE_NAME}_detailView_basicAction_{Vtiger_Util_Helper::replaceSpaceWithUnderScores($DETAIL_VIEW_BASIC_LINK->getLabel())}"
									{if $DETAIL_VIEW_BASIC_LINK->isPageLoadLink()}
										onclick="window.location.href='{$DETAIL_VIEW_BASIC_LINK->getUrl()}'"
									{else}
										onclick={$DETAIL_VIEW_BASIC_LINK->getUrl()}
									{/if}>
									<strong>{vtranslate($DETAIL_VIEW_BASIC_LINK->getLabel(), $MODULE_NAME)}</strong>
								</button>
							</span>
							{/foreach}
							{if $DETAILVIEW_LINKS['DETAILVIEW']|@count gt 0}
							<span class="btn-group">
								<button class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
									<strong>{vtranslate('LBL_MORE', $MODULE_NAME)}</strong>&nbsp;&nbsp;<i class="caret"></i>
								</button>
								<ul class="dropdown-menu pull-right">
									{foreach item=DETAIL_VIEW_LINK from=$DETAILVIEW_LINKS['DETAILVIEW']}
									{if {vtranslate($DETAIL_VIEW_LINK->getLabel(), $MODULE_NAME)} neq 'Delete Sales Order'}
										<li id="{$MODULE_NAME}_detailView_moreAction_{Vtiger_Util_Helper::replaceSpaceWithUnderScores($DETAIL_VIEW_LINK->getLabel())}">
											<a href={$DETAIL_VIEW_LINK->getUrl()} >{vtranslate($DETAIL_VIEW_LINK->getLabel(), $MODULE_NAME)}</a>
										</li>
									{/if}
									{/foreach}
								</ul>
							</span>
							{/if}
							{if $DETAILVIEW_LINKS['DETAILVIEWSETTING']|@count gt 0}
								<span class="btn-group">
									<button class="btn dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon-wrench" alt="{vtranslate('LBL_SETTINGS', $MODULE_NAME)}" title="{vtranslate('LBL_SETTINGS', $MODULE_NAME)}"></i>&nbsp;&nbsp;<i class="caret"></i></button>
									<ul class="listViewSetting dropdown-menu">
										{foreach item=DETAILVIEW_SETTING from=$DETAILVIEW_LINKS['DETAILVIEWSETTING']}
											<li><a href={$DETAILVIEW_SETTING->getUrl()}>{vtranslate($DETAILVIEW_SETTING->getLabel(), $MODULE_NAME)}</a></li>
										{/foreach}
									</ul>
								</span>
							{/if}
							</div>
						</div>
					</div>
				</div>
			</div>
			{if !{$NO_PAGINATION}}
				<div class="span2 detailViewPagingButton">
					<span class="btn-group pull-right">
						<button class="btn" id="detailViewPreviousRecordButton" {if empty($PREVIOUS_RECORD_URL)} disabled="disabled" {else} onclick="window.location.href='{$PREVIOUS_RECORD_URL}'" {/if}><i class="icon-chevron-left"></i></button>
						<button class="btn" id="detailViewNextRecordButton" {if empty($NEXT_RECORD_URL)} disabled="disabled" {else} onclick="window.location.href='{$NEXT_RECORD_URL}'" {/if}><i class="icon-chevron-right"></i></button>
					</span>
				</div>
			{/if}
		</div>
		<div class="detailViewInfo row-fluid">
			<div class="{if $NO_PAGINATION} span12 {else} span10 {/if} details">
				<form id="detailView" data-name-fields='{ZEND_JSON::encode($MODULE_MODEL->getNameFields())}'>
					<div class="contents">
{/strip}
