<?php 
  $agentmobile=$_GET['agentmobile']; 
  //echo $agentmobile; 
 // mail("rahul@umstechlab.com","Get Call Data","agentmobile".$agentmobile);
  include "conn.php";
	if($agentmobile!='')
	{                                                     
		$result=mysqli_query($con,"select * from  ums_current_call where agentmobile=".$agentmobile) or  die("query fail".mysqli_error($con)); 
	while($row=mysqli_fetch_array($result))
	{
		$accountid=$row[0];
		$orgname=$row[1];
		$website=$row[2];
		$city=$row[3];
		$mobile=$row[4];
		$date_time=$row[6];
		
		//echo "$orgname";
		//echo "".$city;
	
		//$fullname=$firstname." ".$lastname;
		if($orgname!='')
		{
 				echo $accountid."~ Call On  : ".$date_time."<br>Name: ".$orgname.'<br>City  : '. $row[3].'<br>Mobile: '. $row[4]."*";   //pass data to notification.php
 		}
 		else if($mobile!='')
 		{
 				echo $accountid."~ Mobile: ". $row[4]."<br>*";   //pass data to notification.php

 		}
	}

	 
 			$result=mysqli_query($con,"delete from ums_current_call where agentmobile=".$agentmobile) or  die("query fail".mysqli_error()); //select current call data from ums_current_call table created by rahul

 }//if

?>