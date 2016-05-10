<?php

$from=$_REQUEST['agent_mobile'];
$to=$_REQUEST['lead_mobile'];
//$urlid="https://tendercuts.in/callcenter";$_REQUEST['urlid'];

$statuscallback="https://tendercuts.in/callcenter/ums_integration/update_call_history.php?From1=".$from."To=".$to;
//echo $statuscallback;
$password=base64_encode('wignesh_tendercuts:5e21a457a486a62bc47e53fc2466a702');
//echo $password;
$urltopost = "https://ums-exotel-common-api.appspot.com/UMSWebservice_connectAgentToCustomer";
$User_Agent = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31';
$headers = array();
$headers[] = 'User-Agent: '. $User_Agent;
$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
$headers[] = 'Authorization:Basic '.$password;


$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$urltopost);
curl_setopt($ch, CURLOPT_POST,1);			
curl_setopt($ch, CURLOPT_POSTFIELDS,"UMSFrom=".$from."&UMSTo=".$to."&UMSStatusCallback=".$statuscallback);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
$returndata = curl_exec ($ch);
echo $returndata;


 ?>
