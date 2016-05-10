<?php
   #!/usr/local/bin/php -q
   include "conn.php";
$selectcallsid="select callsid from vtiger_callhistory where callhistoryid In(Select callhistoryid  from vtiger_callhistorycf where cf_761=0 and callhistoryid In(Select crmid from vtiger_crmentity where deleted=0))"; 
//echo   $selectcallsid;
$result=mysqli_query($con,$selectcallsid);
   while($row=mysqli_fetch_array($result))
  {
	  $callsid=$row['callsid'];
 //echo  $callsid."</br>";

 
//$callsid="6bc4b87782d14b3750e823a79372b189";
//curl Request get exotel Response in XML
$password=base64_encode('iglyte:05b7e4a42524601f2319d11908a908a27e2303af');
//echo $password;
$urltopost = "https://twilix.exotel.in/v1/Accounts/iglyte/Calls/$callsid";
$User_Agent = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31';
$headers = array();
$headers[] = 'User-Agent: '. $User_Agent;
$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';

$headers[] = 'Authorization:Basic '.$password;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$urltopost);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
$output=curl_exec($ch);
curl_close($ch);

$xml = simplexml_load_string($output);
//print_r($xml);
$Sid= (string) $xml->Call->Sid;
//print_r($Sid);

$EndTime= (string) $xml->Call->EndTime;
print_r($EndTime);
//
$Status= (string) $xml->Call->Status;
//print_r($Status);

$Duration= (string) $xml->Call->Duration;
//print_r($Duration);
$selectid="select callhistoryid  from vtiger_callhistory where callsid='$callsid'"; 
//echo $selectid;
$result1=mysqli_query($con,$selectid);
 while($row1=mysqli_fetch_array($result1))
  {
	  $id=$row1['callhistoryid'];
  }
 $insertdata="update vtiger_callhistorycf SET cf_761=$Duration,cf_763='$EndTime',cf_765='$Status' where callhistoryid=$id";
 $result2=mysqli_query($con,$insertdata);
//echo $insertdata;
}//While close

?>