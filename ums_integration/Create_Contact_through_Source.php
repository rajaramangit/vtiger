<?php
include "conn.php";
$number=$_REQUEST['From'];
$leadsource=$_REQUEST['Source'];
$lastname='Through IVR';
$firstname='New Prospect';
date_default_timezone_set('asia/kolkata');
$dt_tm=date('Y-m-d H:i:s');
//mail("rahul@umstechlab.com","number",$number.'leadsource'.$leadsource.'lastname'.$lastname.'firstname'.$firstname);
 $number= trim($number);
	$count = strlen($number);
	if($count > 10)
	{
		$number= substr($number,1);
	}
if($leadsource=='Exotel')
{
   //Check number in Contact Module
    $checknumber="SELECT crmid FROM vtiger_crmentity WHERE deleted =0 AND crmid IN (SELECT contactid FROM  `vtiger_contactdetails` WHERE phone like '%$number' OR mobile like '%$number')";
    				$result1=mysqli_query($con,$checknumber);
				  $rowcount=mysqli_num_rows($result1);
				 // echo "rowcount".$rowcount;
    				if($rowcount==0)
    				{
						$maxcontactid="SELECT MAX(contactid) FROM vtiger_contactdetails";
					   $result2=mysqli_query($con,$maxcontactid) or  die("query fail".mysqli_error($con)); //get maximum salesorderid from vtiger_salesorder table
						while($res2=mysqli_fetch_array($result2))
						{
						$contactid1=$res2[0];
						}
						echo $contactid1;
					
                        $selectcontactid="SELECT contact_no FROM vtiger_contactdetails where contactid=".$contactid1;
						echo "</br>selectcontactid".$selectcontactid;
						$reslt3=mysqli_query($con,$selectcontactid) or  die("query fail".mysqli_error($con)); //get lead id from vtiger_leadaddress table
						while($rs3=mysqli_fetch_array($reslt3))
						{
						$contactno=$rs3[0];
						}
						$contactno=substr($contactno,3);
						$contactno1=$contactno+1;
						$contactno="CON".$contactno1;
						$contactid1=$contactid1+1;
						echo "Contact no after increment".$contactno;
						echo "Contact id after increment".$contactid1;
						$subject="New Contact";
						$label="New Contact Through Exotel";
						$contact="Contacts";
						$selectmaxcrmid="SELECT MAX(crmid) FROM vtiger_crmentity";
						echo "</br>selectmaxcrmid".$selectmaxcrmid;
						$reslt4=mysqli_query($con,$selectmaxcrmid) or  die("query fail".mysqli_error($con)); //get maximum contactid from vtiger_crmentity table
						
						while($res4=mysqli_fetch_array($reslt4))
						{
						$crmid=$res4[0];
						}
						$seqid=$crmid;
						$crmid=$crmid+1;
						echo "</br>".$crmid;
						
						
						$insertcrmentity="INSERT INTO `vtiger_crmentity`(`crmid`, `smcreatorid`, `smownerid`, `modifiedby`, `setype`,`createdtime`, `modifiedtime`,`label`) VALUES ($crmid,1,1,1,'$contact','$dt_tm','$dt_tm','$label')";
						echo "</br>insertcrmentity".$insertcrmentity;
						$result7=mysqli_query($con,$insertcrmentity) or  die("query fail".mysqli_error($con)); // store lead details to ums_current_call table
						
						$insertcontact="INSERT INTO `vtiger_contactdetails`(`contactid`,`contact_no`,`firstname`,`lastname`,`phone`,`mobile`) VALUES ('$crmid','$contactno','$firstname','$lastname','$number','$number')";						
						echo "</br>insertcontact".$insertcontact;
						$result5=mysqli_query($con,$insertcontact) or  die("query fail".mysqli_error($con)); // store order details to table
						
						$insertcontactsubdetails="INSERT INTO `vtiger_contactsubdetails`(`contactsubscriptionid`,`leadsource`) VALUES ($crmid,'$leadsource')";
						$result10=mysqli_query($con,$insertcontactsubdetails) or  die("query fail".mysqli_error($con)); // store lead details to ums_current_call table
						
						$insertcontactscf="INSERT INTO `vtiger_contactscf`(`contactid`) VALUES ($crmid)";
						$result11=mysqli_query($con,$insertcontactscf) or  die("query fail".mysqli_error($con)); // store lead details to ums_current_call table
						
						$insertcontactaddress="INSERT INTO `vtiger_contactaddress`(`contactaddressid`) VALUES ($crmid)";
						$result13=mysqli_query($con,$insertcontactaddress) or  die("query fail".mysqli_error($con)); // store lead details to ums_current_call table
						
						
						$crmentity_seq="UPDATE vtiger_crmentity_seq SET id=$crmid where 1";
						
						$result12=mysqli_query($con,$crmentity_seq) or  die("query fail".mysqli_error($con)); 
						$update_contact=$contactno1+1;
						$update_update_contactno = "UPDATE  `vtiger_modentity_num` SET `cur_id` = $update_contact  WHERE `num_id` =4";
						$update_saleorder_result = mysqli_query($con,$update_update_contactno);

				
			        	//mail("rahul@umstechlab.com","Webform",$contents.'Url'.$url);*/
			        }
			   mail("rahul@umstechlab.com","rowcount",$rowcount.'  '.$checknumber );

}	

?>