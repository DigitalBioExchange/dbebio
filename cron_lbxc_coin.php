<?php
#!/usr/bin/php
##############################################
/*
크론탭 시간설정
/home/devel07.rcsoft.co.kr/docs/j2h/cron_auto.sh
분		시		일		월		요일		명령
0		*		*		*		*			매시간마다.(매일)
7		8-20	*		*		*			08~20 만


예시
00 * * * * /usr/local/bin/php /home2/tongyoung.dqplus.co.kr/docs/cron_calculate_week.php
30 3 1 * * /usr/local/bin/php /home2/tongyoung.dqplus.co.kr/docs/cron_calculate_week.php



CREATE TABLE IF NOT EXISTS `_cron_log` (
  `seq` bigint(20) NOT NULL AUTO_INCREMENT,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `elapsed` varchar(200) NOT NULL,
  `report_text` text NOT NULL,
  PRIMARY KEY (`seq`),
  KEY `start_time` (`start_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


cron_calculate_week.php

*/

##############################################
// cli 아니면 종료
if (php_sapi_name() != 'cli'){
	//header('Location: /');
	die('only play CLI');
	exit;
}

//=======================================================
// 정산내역생성
// 매주월요일 새벽 한가한 시간에 실행하세요.

//========================================================
// 크론탭위한(CLI) 서버변수설정.
if($_SERVER['DOCUMENT_ROOT'] == ""){
	$_SERVER['DOCUMENT_ROOT'] = "/var/www/html"; //테스트 lusoft 셋팅
	// $_SERVER['DOCUMENT_ROOT'] = "/home/mukkebi"; //클라우드서버셋팅
}
if( !isset($_SERVER['HTTP_HOST']) || $_SERVER['HTTP_HOST'] == ""){
	$_SERVER['HTTP_HOST'] = "dbe.lusoft.co.kr"; //테스트 lusoft 셋팅
	// $_SERVER['HTTP_HOST'] = "boss.mukkebi.com"; //클라우드서버셋팅
}
if(  !isset($_SERVER['REMOTE_ADDR']) || $_SERVER['REMOTE_ADDR'] == ""){
	$_SERVER['REMOTE_ADDR'] = "CLI";
}
if(  !isset($_SERVER['HTTP_USER_AGENT']) ||  $_SERVER['HTTP_USER_AGENT'] == ""){
	$_SERVER['HTTP_USER_AGENT'] = "CLI";
}
//========================================================
include_once('_inc/_common.php');
$_gets = trim($argv[1]);
$_REPORT .= "[LBXC일별시세]".$_gets;
##############################################
## 시작시간 설정
$begin_time = get_microtime();
$_start_time = date('Y-m-d H:i:s');
// echo "Start : $_start_time ";
$_REPORT .= PHP_EOL."Start : $_start_time ";

//echo $_REPORT;
//echo 'task start.';
#################
sql_query("INSERT INTO _cron_log SET start_time=NOW(), report_text='".$_REPORT."' ");
$_SEQ  = mysql_insert_id();

##################################################

//자료가져오기.
header("Content-Type: text/html;charset=utf-8");
//=================================
//날짜검색조건
//저번주 월요일(1) ~ 일요일(0) 정산 날짜계산
// $w = date('w', strtotime('2018-02-10'));
// $w = date('w', time());
// if ($w == 0 || $w == 1 || $w == 2) {
// 	$row = $w - 1 + 14;
// } else {
// 	$row = $w - 1 + 7;
// }
$row = 7;
// $_time = strtotime('-'.$row.' day', strtotime('2020-12-01'));
$_time = strtotime('-'.$row.' day', time());
$_time_2 = strtotime('+6 day', $_time);
$_week = date('W', $_time);
$_check_start = date('Y-m-d', $_time);
// $_check_end = date('Y-m-d', $_time_2);
$_check_end = date('Y-m-d', $_time);
//echo "[$_month]".PHP_EOL;
// $_REPORT .= PHP_EOL."정산대상주 : $_week ";
$_REPORT .= PHP_EOL."LBXC일별시세 : $_check_start ";

$_process = 'proceed';
//크론탭실행여부확인.
$_qry = " SELECT * FROM _cron_log WHERE chk_val = 'LBXC_".$_check_start."' ";
// $_qry = " SELECT * FROM _cron_log WHERE chk_val = 'calculate_".$_check_start."_type1' ";
$_chk = sql_fetch($_qry);
// if ($_chk['seq'] != '') {
// 	$_process = 'skip';
// 	$_REPORT .= PHP_EOL."이미 처리한 내역이 있음";
// }
$_seq_cnt = 0;

//=================================
if ($_process == 'proceed'){ //미 완료된 코인거래 확인

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

	// p_arr($_json);

	$trix_usdt = $_json['data'][0]['c'];
	$one_usd = 1 / $trix_usdt;
	// echo $one_usd;

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

	// if($_seq_cnt > 0){
	// 	$_REPORT .= PHP_EOL."결제완료생성 : ".$_seq_cnt.'건';
	// }else{
	// 	$_REPORT .= PHP_EOL."결제완료 내역없음";
	// }

	//

}

##################################################
## 경과시간 측정
//// echo "<hr>";

$_elapsed = get_microtime() - $begin_time;
$_end_time = date('Y-m-d H:i:s');
$_REPORT .= PHP_EOL."End : $_end_time ";
$_REPORT .= PHP_EOL."elapsed : ".number_format($_elapsed,3)." [".$begin_time." - ".get_microtime()."]";



$_qry_up =" UPDATE _cron_log SET
	end_time	= NOW()
	,chk_val = 'calculate_".$_week."'
	,elapsed	= '".number_format($_elapsed,3)."'
	,cnt	= '".$_seq_cnt."'
	,report_text	= '".$_REPORT."'
	WHERE seq = '".$_SEQ."'
";
sql_query($_qry_up);


//echo $_REPORT;
//print_r_text($_qry_up);
//print_r_text($_REPORT);
##################################################
##################################################
if($_process == 'proceed'){
	echo 'task end, elapsed time '.number_format($_elapsed,3).' seconds '.PHP_EOL;
}else{
	echo 'task end,[error or mismatch, review log] , elapsed time '.number_format($_elapsed,3).' seconds '.PHP_EOL;
}

