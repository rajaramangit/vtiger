{literal}
<link href="css/jquery_notification.css" type="text/css" rel="stylesheet"/> 
<script type="text/javascript" src="js/jquery_notification_v.1.js"></script>

<script type="text/javascript">
getagent();
function getagent()
{
//alert('get agent ');
var agentmobile='{/literal}{$CURRENT_USER_MODEL->get('phone_mobile')}{literal}'; // Get agent mobile no.
var message1='';
var print_message='';
var remaining_message='';
var cnt=-1;
 $.ajax({
  
            type: "GET",
			url: "ums_integration/GetCallData.php?agentmobile="+agentmobile,  // this webservice call Getdata.php file and pass agentmobile no.
            async:false,
            success:function(response){
            //alert(response);
			  if(response!='')
			  {
			  var all_res=response.split('~');
			 // alert(all_res);
			  var lead_id=all_res[0];
			  //alert(lead_id);
				//alert('All_Res cnt'+all_res.length);			  //seperate lead_id
			  for(var i=1;i<all_res.length;i++)
			  {	
			 remaining_message=remaining_message+"*"+all_res[i];     //seperate other msg except from lead_id 
			  //alert(lead_id);
			 // alert(message);
			}
			// alert(remaining_message);
			var msg=remaining_message.split('*');

			//alert('msg cnt'+msg.length+' '+msg);
			  for(var i=1;i<msg.length-1;i++)
			  {	 
			    message1="Incoming Call Details:<br><br>"+msg[1];//var cnt=msg[i].length;
			   //alert('Incoming Call From'+msg[i]);
			  
			if(i%2!=0)    // Rahul only select lead_ details avoid leadid.
			   {
				//message1="Incoming Call Details:<br>"+msg[0];
			 print_message=print_message+"<p style='page-break-after:always;'>Incoming Call Details<br><br>"+msg[i]+"</p>"; //get current call data from Getdata.php
				  cnt=cnt+1;
				//  print_message='';
				}
			}//for
//alert('response:'+print_message);			  
				   showNotification({
								message:message1,
								printmessage:print_message,
								leadid:lead_id,
								type: "success",
								//rows:total_rows,
								totalcall:cnt,
								autoClose: false,
								duration: 20
							});
					
				  // alert('after show');
				  
		      }//if
			  //else
			// alert('no response');
            }//res

        }); 



 }
setInterval(getagent, 8000);
</script>

{/literal}