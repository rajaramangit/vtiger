<?php 
//Accept POST Parameters
$UMSID='wignesh_tendercuts';
$UMSTOKEN='5e21a457a486a62bc47e53fc2466a702';
$lead_mobile=$_REQUEST['UMSTo'];
$message=$_REQUEST['UMSText'];



				//mail("rahul@umstechlab.com","Mr. Wignesh Proect",$lead_mobile."message".$message);

				$password=base64_encode($UMSID.':'.$UMSTOKEN);
				echo $password;
				$urltopost = "https://umscommonservicesms.appspot.com/umswebservice_sendsms";
				$User_Agent = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31';
				$headers = array();
				$headers[] = 'User-Agent: '. $User_Agent;
				$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*//*;q=0.8';
				$headers[] = 'Authorization:Basic '.$password;
				$ch=curl_init();
				curl_setopt($ch,CURLOPT_URL,$urltopost);
				curl_setopt($ch, CURLOPT_POST,1);			
				curl_setopt($ch, CURLOPT_POSTFIELDS,"UMSTo=".$lead_mobile."&UMSText=".$message);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$returndata = curl_exec ($ch);
				echo $returndata;
				//mail("rahul@umstechlab.com","SMSNotifier","Success".$returndata);





			
?>