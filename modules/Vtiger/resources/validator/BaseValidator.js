/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
jQuery.Class("Vtiger_Base_Validator_Js",{

	/**
	 *Function which invokes field validation
	 *@param accepts field element as parameter
	 * @return error if validation fails true on success
	 */
	invokeValidation: function(field, rules, i, options){
		//If validation engine already maked the field as error 
		// we dont want to proceed
		if(typeof options !=  "undefined") {
			if(options.isError == true){
				return;
			}
		}
		var listOfValidators = Vtiger_Base_Validator_Js.getValidator(field);
		for(var i=0; i<listOfValidators.length; i++){
			var validatorList = listOfValidators[i];
			var validatorName = validatorList.name;
			var validatorInstance = new validatorName();
			validatorInstance.setElement(field);
			if(validatorList.hasOwnProperty("params")){
				var result = validatorInstance.validate(validatorList.params);
			}else{
				var result = validatorInstance.validate();
			}
			if(!result){
				return validatorInstance.getError();
			}
		}
	},

	/**
	 *Function which gets the complete list of validators based on type and data-validator
	 *@param accepts field element as parameter
	 * @return list of validators for field
	 */
	getValidator: function(field){
        var listOfValidators = new Array();
		var fieldData = field.data();
		var fieldInfo = fieldData.fieldinfo;
		if(typeof fieldInfo == 'string') {
			fieldInfo = JSON.parse(fieldInfo);
		}
		var dataValidator = "validator";
		var module = app.getModuleName();
		var fieldInstance = Vtiger_Field_Js.getInstance(fieldInfo);
		var validatorsOfType = Vtiger_Base_Validator_Js.getValidatorsFromFieldType(fieldInstance);
		for(var key in validatorsOfType){
			//IE for loop fix
			if(!validatorsOfType.hasOwnProperty(key)){
				continue;
			}
			var value = validatorsOfType[key]; 
			if(value != ""){
				var tempValidator = {'name' : value}; 
				listOfValidators.push(tempValidator); 
			}
		} 
		if(fieldData.hasOwnProperty(dataValidator)){
			var specialValidators = fieldData[dataValidator];
			for(var key in specialValidators){
				//IE for loop fix
				if(!specialValidators.hasOwnProperty(key)){
					continue;
				}
				var specialValidator = specialValidators[key];
				var tempSpecialValidator = jQuery.extend({},specialValidator);
				var validatorOfNames = Vtiger_Base_Validator_Js.getValidatorClassName(specialValidator.name);
				if(validatorOfNames != ""){
					tempSpecialValidator.name =  validatorOfNames;							
					if(! jQuery.isEmptyObject(tempSpecialValidator)){
						listOfValidators.push(tempSpecialValidator);
					} 
				}
			}
		}
		return listOfValidators;
	},

	/**
	 *Function which gets the list of validators based on data type of field
	 *@param accepts fieldInstance as parameter
	 * @return list of validators for particular field type
	 */
	getValidatorsFromFieldType: function(fieldInstance){
        var fieldType = fieldInstance.getType();
		var validatorsOfType = new Array();
		fieldType = fieldType.charAt(0).toUpperCase() + fieldType.slice(1).toLowerCase();
		validatorsOfType.push(Vtiger_Base_Validator_Js.getValidatorClassName(fieldType));
        return validatorsOfType;
	},
	
	getValidatorClassName: function(validatorName){
		var validatorsOfType = '';
		var className = Vtiger_Base_Validator_Js.getClassName(validatorName);
		var fallBackClassName = Vtiger_Base_Validator_Js.getFallBackClassName(validatorName);
		if (typeof window[className] != 'undefined'){
			validatorsOfType = (window[className]);
		}else if (typeof window[fallBackClassName] != 'undefined'){
			validatorsOfType = (window[fallBackClassName]);
		}
		return validatorsOfType;
	},
	/**
	 *Function which gets validator className
	 *@param accepts validatorName as parameter
	 * @return module specific validator className
	 */
	getClassName: function(validatorName){
		var moduleName = app.getModuleName();
		return moduleName+"_"+validatorName+"_Validator_Js";
	},

	/**
	 *Function which gets validator className
	 *@param accepts validatorName as parameter
	 * @return generic validator className
	 */
	getFallBackClassName: function(validatorName){
		return "Vtiger_"+validatorName+"_Validator_Js";
	}
},{
	field: "",
	error: "",

	/**
	 *Function which validates the field data
	 * @return true
	 */
	validate: function(){
		
		return true;
	},

	/**
	 *Function which gets error message
	 * @return error message
	 */
	getError: function(){
		if(this.error != null){
			return this.error;
		}
		return "Validation Failed";
	},

	/**
	 *Function which sets error message
	 * @return Instance
	 */
	setError: function(errorInfo){
		this.error = errorInfo;
        return this;
	},

	/**
	 *Function which sets field attribute of class
	 * @return Instance
	 */
	setElement: function(field){
		this.field = field;
        return this;
	},

	/**
	 *Function which gets field attribute of class
	 * @return Instance
	 */
    getElement: function(){
        return this.field;
    },

	/**
	 *Function which gets trimed field value
	 * @return fieldValue
	 */
    getFieldValue: function(){
        var field = this.getElement();
        return jQuery.trim(field.val());
    },
    init: function(){
    	var cut_spec_str = '';
		$("textarea[id^='SalesOrder_editView_fieldName_cf_']").each(function(index) {
			jQuery(this).val('');
		});    	
		$("tr[id^='row']").each(function() {
		    var tr_id = jQuery(this).attr('id').split('row')[1];
		    if(tr_id && tr_id != 'undefined'){
		    	if(tr_id != 0){
		    		if(jQuery('#popup_cmnt_hidden_'+tr_id).length){
		    			var popup_cmnt_hid = jQuery('#popup_cmnt_hidden_'+tr_id).val().split('###');
						if(popup_cmnt_hid.length > 1){
							cut_spec_str += popup_cmnt_hid[0] + ',';
						}else{
							if(popup_cmnt_hid.length == 1){
								cut_spec_str +=  popup_cmnt_hid+',';	
							}else{
								cut_spec_str +=  'null,';	
							}
						}							    			
		    		}else{
		    			cut_spec_str +=  'null,';
		    		}		    
		    	}
		    }
		});	
		if(cut_spec_str != ''){
			cut_spec_str = cut_spec_str.slice(0,-1);
		}
		$("textarea[id^='SalesOrder_editView_fieldName_cf_']").each(function(index) {
			var so_responseData = JSON.parse(jQuery(this).attr('data-fieldinfo'));
			var so_len = Object.keys(so_responseData).length;
			if(so_len > 1 && so_responseData.label){
				if(so_responseData.label.toLowerCase() == 'cut spec'){
					jQuery(this).val(cut_spec_str);
				}
			}				
		});
    }
});

