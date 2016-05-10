<?php

/**
 * Uninstall CRM4Ecommerce_ESync module
 * 
 * @category   modules/CRM4Ecommerce_ESync
 * @package    CRM4Ecommerce_ESync
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://www.crm4ecommerce.com
 * @version    2.0
 */
include_once('vtlib/Vtiger/Module.php');
$module = Vtiger_Module::getInstance('CRM4Ecommerce_ESync');
$module->delete();

print "<br>Uninstall Done...!";