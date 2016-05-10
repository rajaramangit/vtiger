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
	{assign var="MODULE_NAME" value=$MODULE_MODEL->get('name')}

					</div>
				</form>
			</div>
			<div class="related span2 marginLeftZero">
				<div class="">
					<ul class="nav nav-stacked nav-pills">
						{foreach item=RELATED_LINK from=$DETAILVIEW_LINKS['DETAILVIEWTAB']}
							<li class="{if $RELATED_LINK->getLabel()==$SELECTED_TAB_LABEL}active{/if}" data-url="{$RELATED_LINK->getUrl()}&tab_label={$RELATED_LINK->getLabel()}" data-label-key="{$RELATED_LINK->getLabel()}" data-link-key="{$RELATED_LINK->get('linkKey')}" >
                                                	{$contactrelatedmodule=vtranslate($RELATED_LINK->getLabel(),{$MODULE_NAME})}	
	                                                {if $contactrelatedmodule neq "Updates" && $contactrelatedmodule neq "Comments" && $contactrelatedmodule neq "Contact Details"}						
                                                         <a href="javascript:void(0);" class="textOverflowEllipsis" style="width:auto" title="{vtranslate($RELATED_LINK->getLabel(),{$MODULE_NAME})}"><strong>{vtranslate($RELATED_LINK->getLabel(),{$MODULE_NAME})}</strong></a>
        	                                        {/if}
							</li>
						{/foreach}
						{foreach item=RELATED_LINK from=$DETAILVIEW_LINKS['DETAILVIEWRELATED']}
							<li class="{if $RELATED_LINK->getLabel()==$SELECTED_TAB_LABEL}active{/if}" data-url="{$RELATED_LINK->getUrl()}&tab_label={$RELATED_LINK->getLabel()}" data-label-key="{$RELATED_LINK->getLabel()}" >
							{* Assuming most of the related link label would be module name - we perform dual translation *}
							{assign var="DETAILVIEWRELATEDLINKLBL" value= vtranslate($RELATED_LINK->getLabel(), $RELATED_LINK->getLabel())}
							{if $DETAILVIEWRELATEDLINKLBL neq "Activities" && $DETAILVIEWRELATEDLINKLBL neq "Emails" && $DETAILVIEWRELATEDLINKLBL neq "Quotes" && $DETAILVIEWRELATEDLINKLBL neq "Products" && $DETAILVIEWRELATEDLINKLBL neq "Purchase Order" && $DETAILVIEWRELATEDLINKLBL neq "Campaigns" && $DETAILVIEWRELATEDLINKLBL neq "Invoice" && $DETAILVIEWRELATEDLINKLBL neq "Service Contracts" && $DETAILVIEWRELATEDLINKLBL neq "Services" && $DETAILVIEWRELATEDLINKLBL neq "Projects" && $DETAILVIEWRELATEDLINKLBL neq "Assets" && $DETAILVIEWRELATEDLINKLBL neq "Opportunities" && $DETAILVIEWRELATEDLINKLBL neq "Documents"}
								{if $DETAILVIEWRELATEDLINKLBL eq "Sales Order"}
								<a href="javascript:void(0);" class="textOverflowEllipsis" style="width:auto;font-size:18px;" title="{vtranslate($RELATED_LINK->getLabel(),{$MODULE_NAME})}"><strong>{$DETAILVIEWRELATEDLINKLBL}</strong></a>
								{else}
								<a href="javascript:void(0);" class="textOverflowEllipsis" style="width:auto" title="{vtranslate($RELATED_LINK->getLabel(),{$MODULE_NAME})}"><strong>{$DETAILVIEWRELATEDLINKLBL}</strong></a>
								{/if}
							{/if}
						</li>
						{/foreach}
					</ul>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
</div>
{/strip}
