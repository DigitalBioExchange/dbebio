<?
$_cfg['infobip']['api_key'] = "df4f6f817f837fc15ff23049b0c96f02-250edbb2-af11-4353-8150-ecaa5d025c9a";
// $_cfg['infobip']['send_url'] = "https://19ggyx.api.infobip.com/sms/2/text/advanced";
$_cfg['infobip']['send_url'] = "https://6jzj3d.api.infobip.com/sms/2/text/single";



function infobip_sms($to_num, $msg)
{
	global $_cfg;

	$msg2 = array();
	$msg2['messages'] = array();
	$msg_arr = array();
	$msg_arr['from'] = "dbe";
	// $msg_arr['destinations'] = array(
	// 	array("to" => $to_num)
	// );
	$msg_arr['to'] = $to_num;
	$msg_arr['text'] = $msg;
	$msg2['messages'][] = $msg_arr;

	$msg2['bulkId'] = date("YmdHis")."-".substr(md5(uniqid(rand(), TRUE)), 0, 20);
	$msg2['tracking'] = array("track" => "SMS", "type" => "ONE_TIME_PIN");

	// echo "<pre>";print_r($msg2);echo "</pre>";exit;

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $_cfg['infobip']['send_url'],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		// CURLOPT_POSTFIELDS => json_encode($msg2),
		CURLOPT_POSTFIELDS => json_encode($msg_arr),
		CURLOPT_HTTPHEADER => array(
			"Authorization: App ".$_cfg['infobip']['api_key'],
			"Content-Type: application/json",
			"Accept: application/json"
		),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return json_decode($response, true);
}

?>