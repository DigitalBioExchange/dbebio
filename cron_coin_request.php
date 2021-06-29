<?php
#!/usr/local/bin/php
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
	$_SERVER['HTTP_HOST'] = "enftee.com"; //테스트 lusoft 셋팅
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
$_REPORT .= "[코인거래미완료체크]".$_gets;
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
$_REPORT .= PHP_EOL."지점정산대상일 : $_check_start ";

$_process = 'proceed';
//크론탭실행여부확인.
$_qry = " SELECT * FROM _cron_log WHERE chk_val = 'calculate_".$_week."' ";
// $_qry = " SELECT * FROM _cron_log WHERE chk_val = 'calculate_".$_check_start."_type1' ";
$_chk = sql_fetch($_qry);
// if ($_chk['seq'] != '') {
// 	$_process = 'skip';
// 	$_REPORT .= PHP_EOL."이미 처리한 내역이 있음";
// }
$_seq_cnt = 0;

//=================================
if ($_process == 'proceed'){ //미 완료된 코인거래 확인

	$_qry = "SELECT * FROM rb_order WHERE od_status = 1 AND od_coin_status = 1 ";
	$_res = sql_list($_qry);
	
	foreach ($_res as $key => $value) {

		$_param = array(
			'module' => 'transaction',
			'action' => 'getstatus',
			'txhash' => $value['od_tno'],
			'apikey' => 'DXQCBSEBE2GJKXSESV1FYGNDJCYUM78T2F',
		);

		// GET방식
		if ($_cfg['coin_server_web'] == true) {
			$_url = "https://api.etherscan.io/api?".http_build_query($_param); //실서버
		} else {
			$_url = "https://api-ropsten.etherscan.io/api?".http_build_query($_param); //테스트넷
		}

		$curlObj = curl_init();
		curl_setopt($curlObj, CURLOPT_URL, $_url);
		curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlObj, CURLOPT_HEADER, 0);
		$response = curl_exec($curlObj);
		$_json = json_decode($response,true);
		curl_close($curlObj);

		// print_r($_json);

		if ($_json['result']['isError'] == 0) { //거래 완료됨

			$sql = "select * from rb_member where mb_idx = '".$value['mb_idx']."' ";
			$mb = sql_fetch($sql);

			$sql = "select * from rb_product where pd_idx = '".$value['pd_idx']."' ";
			$data = sql_fetch($sql);

			//구매정보에 거래완료 업데이트
			$sql_upd = "update rb_order set
									od_status = 2,
									od_coin_status = 2,
									od_paydate = now()
									where od_idx = '".$data['od_idx']."'
							";
			sql_query($sql_upd);

			sql_query("update rb_order_cart set ct_status = '2' where od_idx = '".$data['od_idx']."'");


			// $al_contents = "The transaction of ".$data['pd_name']." goods has been completed.";

			// //거래완료 알림 발송
			// $sql_ins = "insert into rb_alarm_list set
			// 						mb_idx = '".$mb['mb_idx']."',
			// 						mb_id = '".$mb['mb_id']."',
			// 						al_contents = '".addslashes($al_contents)."',
			// 						al_regdate = now()
			// 				";
			// sql_query($sql_ins);
			// $al_idx = sql_insert_id();


			// //구매테이블 작성
			// $sql_ins = "insert into rb_product_buyer set
			// 						pd_idx = '".$data['pd_idx']."',
			// 						pd_img_url = '".$data['pd_img_url']."',
			// 						od_idx = '".$value['od_idx']."',
			// 						mb_idx = '".$mb['mb_idx']."',
			// 						mb_id = '".$mb['mb_id']."',
			// 						pb_price = '".$data['pd_price']."',
			// 						pb_coin_hash = '".$value['od_tno']."',
			// 						pb_regdate = now()
			// 					";
			// sql_query($sql_ins);

			// //뷰 히스토리 작성
			// $sql_his = "insert into rb_product_view_history set
			// 						ph_type = 2,
			// 						od_idx = '".$value['od_idx']."',
			// 						pd_idx = '".$value['pd_idx']."',
			// 						mb_idx = '".$mb['mb_idx']."',
			// 						mb_id = '".$mb['mb_id']."',
			// 						ph_regdate = now()
			// 					";
			// sql_query($sql_his);
		}
		$_seq_cnt++;
	}

	if($_seq_cnt > 0){
		$_REPORT .= PHP_EOL."결제완료생성 : ".$_seq_cnt.'건';
	}else{
		$_REPORT .= PHP_EOL."결제완료 내역없음";
	}

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

