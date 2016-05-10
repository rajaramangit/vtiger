	<?php 
	include "conn.php";
	$number=$_REQUEST['mobile'];
	$sms_sid=$_REQUEST['SmsSid'];
	$sms_status=$_REQUEST['Status'];
	$sms_txt=$_REQUEST['msg'];
	$fg=$_REQUEST['flg'];
	//echo $fg;
	
	$flag=1;//dont check for sms id is exist in ums_exist_callsid.. it was for ob call only
	
	date_default_timezone_set("Asia/Kolkata");
	
	$date_time=date('Y-m-d H:i:s');

	if($flag==1)
	{   
	           

	 $number= trim($number);
	$count = strlen($number);
	if($count > 10)
	{
		$number= substr($number,1);
	}

	

	  
	 if(!$con)
	{
	  die('Could not connect: ' . mysql_error());
	}//if
	else
	{
	  if($number!='')
	  {
		
			   $query = "SELECT leadaddressid FROM  `vtiger_leadaddress` WHERE phone like '%$number' or mobile like '%$number'";
					 //    "Select leadaddressid from vtiger_leadaddress where phone like '".$From_Number."' or mobile like '".$From_Number.'"';
			   $result = mysql_query($query,$con);
			   $rowHistory=mysql_fetch_assoc($result);
			   $leadId=$rowHistory['leadaddressid'];
				//echo 'lead id '.$leadId;
			   $select_query = 'SELECT `cf_765` FROM `vtiger_leadscf` WHERE  `leadid` =  "'.$leadId.'"';
			   $select_result = mysql_query($select_query,$con);
			   $demo = mysql_fetch_assoc($select_result);
			   $smsHistory = $demo['cf_765'];
			   print_r ($smsHistory );
			  // $recording_url=$recording_url;//append  https:// part of recording url removed in  2 apps script files(Folder:UMS_VTiger_DIYS_Files )
												 //  2: UMS_VTiger Demo update Outgoing sms History (Making request to PHP file )
												 // and 3:  UMS_VTiger Demo Update  incoming sms History  (exotel flow after call passthru)
			   if($demo['cf_765']!='')//append previous history
			   {
										  //<a href = "<?php echo 'uploads/$image'; "></a> 
						//sharad changes				  
					//$temp1= $call_subjet.$date_time.'<br><a href ='.$recording_url.'>Recording Url</a>';
										
					
					$temp1= '$'.$sms_status.'$'.$date_time.'$'.$sms_txt;	// append $ before call Subject and recording url to handle in Detail View of Leads	(sms History  Section)	
					$temp1= $smsHistory .$temp1;
					$update_query = 'UPDATE `vtiger_leadscf` SET `cf_765` = "'.$temp1.'" WHERE `leadid` = "'.$leadId.'"';
					$update_result = mysql_query($update_query,$con);
					echo '<br>smsHistory exist<br>'.$update_query.'<br>update existing sms History  result '.$update_result;
			   }//if
			   else
			   { //sharad changes
					//$temp1= $call_subjet.$date_time.'<br><a href = "<?php echo $recording_url;">Recording Url</a> 
					$temp1=$sms_status.'$'.$date_time.'$'.$sms_txt;
					$update_query = 'UPDATE `vtiger_leadscf` SET `cf_765` = "'.$temp1.'" WHERE `leadid` = "'.$leadId.'"';
					$update_result = mysql_query($update_query,$con);
					echo '<br>new smsHistory <br>'.$update_query.'<br>update new sms History  result '.$update_result;
			   }//else
			}//if number exist
			else
			echo 'No Matched Number';
			
	}//else
	echo  $msg='Number:'.$number.' Sid:'.$sms_sid.' Status:'.$sms_status.' date'.$date_time;
	mail("sharad@umstechlab.com","sms History .php  called ",$msg.' result '.$update_query);
	
	
	
	//Append Status Call Back to SS and make CURL to App Script
	
	$cSession = curl_init(); 
				//step2
				curl_setopt($cSession,CURLOPT_URL,"https://script.google.com/macros/s/AKfycbxNfALEUiYZ6ZjDv_7YSUlLitNDs28X6mH8pw17BQRCg4JSkIDb/exec");
				curl_setopt($cSession, CURLOPT_POST,1);	
				curl_setopt($cSession, CURLOPT_POSTFIELDS,"Mobile_no=".$number."&smssid=".$sms_sid."&date=".$date_time."&message=".$sms_txt."&delivery=".$sms_status);
				
				curl_setopt($cSession,CURLOPT_HEADER, 0); 
				curl_setopt($cSession,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($cSession, CURLOPT_FOLLOWLOCATION,1);
				$contents = curl_exec($cSession);	//step4
				curl_close($cSession);
				echo $contents;
				
	mail("bhagyalaxmi@umstechlab.com","Update to SS",$contents);
	
	}//if flag
	
	
	
	?>