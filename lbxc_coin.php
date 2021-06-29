<?php
include "./inc/_common.php";

$_param = array(
	// 'symbol' => 'ALL'
	'symbol' => 'LBXC-USDT'
);
$_url = "https://global-openapi.bithumb.pro/openapi/v1/spot/ticker?".http_build_query($_param);

$curlObj = curl_init();
curl_setopt($curlObj, CURLOPT_URL, $_url);
curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curlObj, CURLOPT_HEADER, 0);
$response = curl_exec($curlObj);
$_json = json_decode($response,true);
curl_close($curlObj);

p_arr($_json);

$trix_usdt = $_json['data'][0]['c'];
$one_usd = 1 / $trix_usdt;
echo $one_usd;

$sql = "select * from rb_coin_price where cp_coin_name = 'LBXC' and cp_regdate = '".date('Y-m-d')."' ";
$data = sql_fetch($sql);
if (!$data['cp_idx']) {
	$sql = "select * from rb_coin_price where 1 order by cp_idx desc limit 0, 1";
	$data_before = sql_fetch($sql);
	if ($data_before['cp_idx']) {
		$cp_price_gap = $one_usd - $data_before['cp_price'];
		$cp_price_percent = $cp_price_gap / $data_before['cp_price'] * 100;
	} else {
		$cp_price_gap = 0.0000;
		$cp_price_percent = 0.00;
	}
	$sql_inc = "insert into rb_coin_price set 
							cp_price = '".$one_usd."',
							cp_price_gap = '".$cp_price_gap."',
							cp_price_percent = '".$cp_price_percent."',
							cp_coin_name = 'LBXC',
							cp_regdate = now()
						";
	sql_query($sql_inc);
}

exit;

?>