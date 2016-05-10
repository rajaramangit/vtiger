<?php

$contactmobile=$_REQUEST["From"]; 
$agentmobile=$_REQUEST["DialWhomNumber"];// get agent mobile DialWhomNumber
$status=$_REQUEST["Status"];// get agent mobile status
date_default_timezone_set("Asia/Kolkata");
$date_time= date("d M H:i:s");
//mail("rahul@umstechlab.com","ExotelDuring Passthru","contactmobile".$contactmobile);

if($contactmobile!="")
{
	//echo $contactmobile;
	//echo $agentmobile;
  include "conn.php";
if($status=='busy')
{   
 


	$contactmobile= trim($contactmobile);
	$count = strlen($contactmobile);
	if($count > 10)
	{
			$contactmobile= substr($contactmobile,1);
	}


	$agentmobile= trim($agentmobile);
	$count = strlen($agentmobile);
	if($count > 10)
	{
		$agentmobile= substr($agentmobile,1);
	}
		
		$accountid='';
		$select_accountid="SELECT crmid FROM vtiger_crmentity WHERE deleted =0 AND crmid IN (select accountid from vtiger_account where phone like '%$contactmobile' or otherphone like '%$contactmobile')";
		$result11=mysqli_query($con,$select_accountid) or  die("query fail".mysqli_error($con)); 

		while($res=mysqli_fetch_array($result11))
		{
			$accountid=$res['crmid'];
		}
		//echo "accountid". $accountid ."</br>";
		if($accountid!='')
		{
			$result=mysqli_query($con,"select * from vtiger_account Natural Join vtiger_accountbillads  where vtiger_account.accountid=vtiger_accountbillads.accountaddressid and accountid=$accountid") or  die("query fail".mysqli_error($con));  
			while($res=mysqli_fetch_array($result))
			{
				$orgname=$res['accountname'];
				$website=$res['website'];
				$city =$res['bill_city'];
			}

			$insertdata="insert into ums_current_call values('$accountid','$orgname','$website','$city','$contactmobile','$agentmobile','$date_time')";
			//echo $insertdata;
			$result=mysqli_query($con,$insertdata) or  die("query fail".mysqli_error($con)); // store lead 
			//echo "save";
		}
		else
		{
			$insertdata="insert into ums_current_call values('https://tendercuts.in/callcenter/index.php?module=Accounts&view=Edit&phone=$contactmobile','','','','$contactmobile','$agentmobile','$date_time')";
			//echo $insertdata;
			$result=mysqli_query($con,$insertdata) or  die("query fail".mysqli_error($con)); // store lead details to ums_current_call table
	//echo "save";
			    //	$updatemobile="UPDATE `vtiger_field` SET `defaultvalue`=$contactmobile WHERE `fieldid`=3";
		 	//$result=mysqli_query($con,$updatemobile) or  die("query fail".mysqli_error($con));
		}

 

}//if status
}//else
else
 echo 'Mob not present or is blank';

?>
