/**
 * Javascript functions to show top nitification
 * Error/Success/Info/Warning messages
 * Developed By: Ravi Tamada
 * url: http://androidhive.info
 * © androidhive.info
 * 
 * Created On: 10/4/2011
 * version 1.0
 * 
 * Usage: call this function with params 
 showNotification(params);
 **/
 

var printmessage='';
var lead_id='';
var totalcall='';
function showNotification(params){
    // options array
    var options = { 
        'showAfter': 0, // number of sec to wait after page loads
        'duration': 0, // display duration
        'autoClose' : false, // flag to autoClose notification message
        'type' : 'success', // type of info message error/success/info/warning
        'message': '', // message to dispaly
        'link_notification' : '', // link flag to show extra description
        'description' : '' // link to desciption to display on clicking link message
    }; 
    // Extending array from params
    $.extend(true, options, params);
//	var flag='';
   lead_id=options['leadid'];  //Rahul get current lead id
   //var printmessage1=options['printmessage'];  //Rahul Get print message
  // var print_res=printmessage1.split('.');
   printmessage=printmessage+options['printmessage'];
  
    totalcall=options['totalcall'];
	var msgclass = 'succ_bg'; // default success message will shown
    if(options['type'] == 'error'){
        msgclass = 'error_bg'; // over write the message to error message
    } else if(options['type'] == 'information'){
        msgclass = 'info_bg'; // over write the message to information message
    } else if(options['type'] == 'warning'){
        msgclass = 'warn_bg'; // over write the message to warning message
    } 
    
    // Parent Div container


	var container = '<div id="info_message" class="'+msgclass+'"><div class="center_auto"><div class="info_message_text message_area">';
    container += options['message'];
	if(printmessage=='none')
	{
	printmessage='';
    container += '</div><div class="info_close_btn button_area" onclick="return closeNotification()"></div>';
	container += '</div><div class="info_close_btn openlead" ><input style="height:25px;border:2px solid #0000;border-radius:8px;height:35px;background-Color:#ff9900; width:120px;" type="button" value="Pickup Call" onClick=open_lead('+lead_id+') /></div>';
	container += '<div class="clearboth"></div>';
    container += '</div><div class="info_more_descrption"></div></div>';
	}
  else
	{
	
    container += '</div><div class="info_close_btn button_area" onclick="return closeNotification()"></div>';
	container += '</div><div class="info_close_btn openlead" ><input style="border:2px solid #0000;border-radius:8px;background-Color:#f6e1ff; width:120px;" type="button" value="Pickup Call" onClick=open_lead("'+lead_id+'") /></div>';
	//container += '<div class="info_close_btn print" ><input type="button" style="width:80px;border:2px solid #0000;border-radius:8px;background-Color:#f6e1ff;" value="Print" onClick=printpopup() /></div>';
		if(totalcall!=0)
		{
			container+=	'</div><div class="info_close_btn totalcall"><input type="button" style="border:2px solid #0000;border-radius:8px;height:35px background-Color:#b6fbeb;" value="View '+totalcall+' Remaining Calls" onClick=remaining_call() /></div>';
		}
	container += '<div class="clearboth"></div>';
    container += '</div><div class="info_more_descrption"></div></div>';
	
   }
    $notification = $(container);
    
    // Appeding notification to Body
    $('body').append($notification);
    
    var divHeight = $('div#info_message').height();
    // see CSS top to minus of div height
    $('div#info_message').css({
        top : '-'+divHeight+'px'
    });
    
    // showing notification message, default it will be hidden
    $('div#info_message').show();
    
    // Slide Down notification message after startAfter seconds
    slideDownNotification(options['showAfter'], options['autoClose'],options['duration']);
    
    $('.link_notification').live('click', function(){
        $('.info_more_descrption').html(options['description']).slideDown('fast');
    });
    
}
// function to close notification message
// slideUp the message
function closeNotification(duration){
    var divHeight = $('div#info_message').height();
    setTimeout(function(){
        $('div#info_message').animate({
            top: '-'+divHeight
        }); 
        // removing the notification from body
        setTimeout(function(){
            $('div#info_message').remove();
        },200);
    }, parseInt(duration * 1000));   
    

    
}

// sliding down the notification
function slideDownNotification(startAfter, autoClose, duration){    
    setTimeout(function(){
        $('div#info_message').animate({
            top: 0
        }); 
        if(autoClose){
            setTimeout(function(){
                closeNotification(duration);
            }, duration);
        }
    }, parseInt(startAfter * 1000));    
}
function open_lead(lead_id_param) {	
       //  alert(lead_id_param);
 if(isNaN(lead_id_param)){
var win=window.history.pushState("Accounts", "Accounts",lead_id_param,'_blank'); 


}else{
   var win=window.history.pushState("Accounts", "Accounts","index.php?module=Accounts&view=Detail&record="+lead_id_param,'_blank');

}

 //var win=window.history.pushState("Contacts", "Contacts", "index.php?module=Contacts&view=Detail&record="+lead_id_param,'_blank');//window.open("index.php?module=Contacts&view=Detail&record="+lead_id_param,'_blank');  //Open lead of notification on window 
 //var win=window.history.pushState("Organization", "Organization", " index.php?module=Accounts&view=Edit",'_blank');
  location.reload();

	//.reload();
    
}//Rahul end
function remaining_call() {	
         // alert(lead_id_param);
var printContents =printmessage;// document.getElementById(divName).innerHTML;
  //   alert('in print function'+printContents);
    // var originalContents = document.body.innerHTML;
     //var printContents =originalContents;
    // document.body.innerHTML = printContents;
	var mywindow=window.open("data:text/html," + encodeURIComponent(printContents),"_blank");
 //document.body.innerHTML = originalContents;
	myWindow.focus();
	//.reload();
    
}//Rahul end


function printpopup()
{
   var printContents =printmessage;// document.getElementById(divName).innerHTML;
  //   alert('in print function'+printContents);
     var originalContents = document.body.innerHTML;
     //var printContents =originalContents;
     document.body.innerHTML = printContents;

     window.print();
     //print_message='';
     document.body.innerHTML = originalContents;
}
 