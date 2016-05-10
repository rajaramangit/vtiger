<?php 
	include "conn.php";
	$callsid=$_REQUEST['CallSid'];
	$recording_url=$_REQUEST['RecordingUrl'];
	$calltype=$_REQUEST['Direction'];
	$date_time=$_REQUEST['StartTime'];
	

	
	/*mail("rahul@umstechlab.com","Call History.php  called "," From ".$callsid);
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,"https://script.google.com/macros/s/AKfycbyjaEjgUeeFtHDWXI9K4fbDqZB_p83Bx0Tp2loaRfX5HGzgxxAI/exec?data=$callsid");
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$output=curl_exec($ch);
	curl_close($ch);*/
    $call_subjet='';
	if($calltype=='incoming')
	{
		$umsstatus=$_REQUEST['umsstatus'];
		if($umsstatus=='Missedcall')
		{
			$call_subjet="Missed call";
		}
		else
		{
			$call_subjet="Inbound";
		}
		$number=$_REQUEST['From'];
		$agentnumber=$_REQUEST['DialWhomNumber'];	 
	}
	else
	{
		$call_subjet="Outbound"; 
		$date_time=$_REQUEST['DateUpdated']; //if OB Call(C2C) then lead no will be To parameter
		$mb=$_REQUEST['From1'];
		$data=split('To=',$mb);
		$From=$data[0];
		$To=$data[1];
		$agentnumber=$From;
		$number=$To;
	}                    
	$number= trim($number);
	$count = strlen($number);
	if($count > 10)
	{
		$number= substr($number,1);
	}
	if(!$con)
	{
		die('Could not connect: ' . mysqli_error($con));
	}
	else
	{
		$accountid='';
		if($number!='')
		{

			$query = "Select crmid from vtiger_crmentity where deleted=0 and crmid In(SELECT accountid FROM  `vtiger_account` WHERE phone like '%$number' or otherphone like '%$number')";
			echo $query;
			$result = mysqli_query($con,$query);
			while($rowHistory=mysqli_fetch_array($result))
			{
			   $accountid=$rowHistory['crmid'];
			}
			  
			//mail("rahul@umstechlab.com","Call History.php  Call history",'function call contactid'.$contactid);
			//if($accountid!='')
			//{   
				date_default_timezone_set('Asia/Kolkata');
				$currenttime=date('Y/m/d h:i:s a', time());
				$query1="select * from vtiger_callhistory WHERE callsid='$callsid'";
			    $result1=mysqli_query($query1,$con);
				$rowcount=mysqli_num_rows($result1);
				//mail("rahul@umstechlab.com","Call History.php  Call history",'function call rowcount'.$rowcount.'result'.$result1.'query'.$query1."currenttime".$currenttime);
			
				if($rowcount==0)
				{
					$query2="select id from vtiger_crmentity_seq";
					$result2=mysqli_query($con,$query2);
					$crmid_result=mysqli_fetch_assoc($result2);
					$crmid=$crmid_result['id'];
					$seqid=$crmid+1;
			
					$insertquery="INSERT INTO `vtiger_crmentity` (`crmid` ,`smcreatorid` ,`smownerid` ,`modifiedby` ,`setype` ,`description` ,`createdtime` ,`modifiedtime` ,`viewedtime` ,`status` ,`version` ,`presence` ,`deleted` ,`label`)VALUES ($seqid,  '1',  '1',  '1',  'CallHistory', NULL ,'$currenttime','$currenttime', NULL , NULL ,  '0',  '1',  '0','$call_subjet')";
					$result = mysqli_query($con,$insertquery);
					//  mail("rahul@umstechlab.com","Call History.php CRM Entity",'insertquery'.$insertquery.'crmid'.$crmid."result". $result);
					
					$result11=mysqli_query($con,"Delete from vtiger_crmentity_seq where id=$crmid") or  die("query fail".mysqli_error($con)); 
					
					$result12=mysqli_query($con,"insert into vtiger_crmentity_seq (id) values($seqid)") or  die("query fail".mysqli_error($con)); 
			
					$insertcallhistory="INSERT INTO `vtiger_callhistory` (`callhistoryid`,`calltype`, `callsid`,`calltime`,`recordurl`,`contactid`,accountid) VALUES ($seqid,'$call_subjet','$callsid','$date_time','$recording_url','','$accountid')";
					$result = mysqli_query($con,$insertcallhistory);
			 
					$insertcallhistorycf="INSERT INTO `vtiger_callhistorycf` VALUES ($seqid,$number)";
					$result = mysqli_query($con,$insertcallhistorycf);
					// mail("rahul@umstechlab.com","Call History.php  Call history",'insertcallhistory'.$insertcallhistory."result". $result.'recording_url'.$recording_url);
			   
				//}
			}
		}
	}//else
	//echo  $msg='Number:'.$number.' Sid:'.$callsid.' Recording:'.$recording_url.' Type:'.$call_subjet.' date'.$date_time.'Agentnumber:'.$agentnumber.'AgentName:'.$agentname.'Sql1:'.$sql1.'Quary'.$query.'SelectQuery'.$select_query.'update_query'.$update_query.'sql2'.$sql2;

?>