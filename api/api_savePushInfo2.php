<?php
$is_api = 1;
$argument = "post";
if($argument == "post"){
	foreach($_POST as $k => $v){
		${$k} = $v;
	}
	unset($_GET);

}else if($argument == "get"){
	foreach($_GET as $k => $v){
		${$k} = $v;
	}
	unset($_POST);

}

$return_type = (in_array($return_type,array("xml", "json"))) ? $return_type : "json";

$_POST['access_type'] = (in_array($_POST['access_type'],array("token", "session"))) ? $_POST['access_type'] : "session";
$_GET['access_type'] = (in_array($_GET['access_type'],array("token", "session"))) ? $_GET['access_type'] : "session";
$access_type = ($_POST['access_type']) ? $_POST['access_type'] : $_GET['access_type'];

$_POST['app_os'] = (in_array($_POST['app_os'],array("iOS", "Android"))) ? $_POST['app_os'] : "iOS";
$_GET['app_os'] = (in_array($_GET['app_os'],array("iOS", "Android"))) ? $_GET['app_os'] : "iOS";
$app_os = ($_POST['app_os']) ? $_POST['app_os'] : $_GET['app_os'];

$app_os_str = ($app_os == "iOS") ? "" : "_and";

$_POST['app_version'] = ($_POST['app_version']) ? $_POST['app_version'] : "1.0";
$_GET['app_version'] = ($_GET['app_version']) ? $_GET['app_version'] : "1.0";
$app_version = ($_POST['app_version']) ? $_POST['app_version'] : $_GET['app_version'];

$access_token = ($_POST['access_token']) ? $_POST['access_token'] : $_GET['access_token'];

$root_path = ($root_path) ? $root_path : ".."; // common.php 의 상대 경로

include "../inc/_common.php";

// XML 만들기
function array2XML($arr,$root)
{
	global $xml;
	$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><{$root}></{$root}>");
	if(is_array($arr)){
		array2XML2($arr, $xml);
	}
	return $xml->asXML();
}

function array2XML2($arr, $obj)
{
	if(is_array($arr)){
		foreach($arr as $k => $v){
			if(is_array($v)){
				if(is_numeric($k)){
					${$k} = $obj->addChild("data");
					array2XML2($v, ${$k});
				}else{
					${$k} = $obj->addChild($k);
					array2XML2($v, ${$k});
				}
			}else{
				$node = $obj->addChild($k);
				$node = dom_import_simplexml($node); 
				$no = $node->ownerDocument; 
				$node->appendChild($no->createCDATASection($v));
			}
		}
	}
}

// urlencode 하기
function urlencode_data($arg)
{
	if(is_array($arg)){
		foreach($arg as $k => $v){
			$arg[$k] = urlencode_data($v);
		}
	}else{
		if(gettype($arg) == "string")
			$arg = urlencode($arg);
		else if(is_int($arg) && substr($arg, 0, 1) != "0")
			$arg = (int)$arg;
		else if(is_numeric($arg) && substr($arg, 0, 1) != "0" && substr($arg, 0, 2) != "0.")
			$arg = (float)$arg;
		
	}

	return $arg;
}

// 에러처리
function make_error($error_code, $result_text = "")
{
	global $return_type;
	global $return_encode_type;
	global $root_path;

	$arr = array();
	$arr['result'] = $error_code;
	$arr['result_text'] = $result_text;

	include ($root_path."/api/".$return_type.".php");
	exit;
}

// 로그인체크 (1:로그인 체크, 0:체크안함-로그인 유지)
function make_member_login_check($check_login)
{
	global $access_type;
	global $access_token;
	global $member;
	global $is_member;

	if($check_login){
		if (!$is_member){
			make_error("2001", "로그인 후 이용해 주세요.");
		}
	}else{
	}
}

function api_savePushInfo2()
{
	global $_cfg;

	global $sv_code;


	foreach($_POST as $k => $v){
		${$k} = trim($v);
	}

	if (!$mb_id){
		make_error("2017", "아이디가 없습니다.");
	}

	$member = get_member($mb_id, 'and mb_certified = 1 and mb_status = 1');

	if (!$member['mb_id']) {
		make_error("2012", "가입된 회원이 아닙니다.");
	}

	if(!in_array($mb_os, array("1", "2", "4", "5"))){
		make_error("1200","OS가 정확하지 않습니다.");
	}

	if(!$mb_regnum){
		make_error("1201","기기고유번호가 없습니다.");
	}

	$arr = pushcat_regist_user($member, $sv_code, $mb_os, $mb_regnum);

	$arr['mb_ck'] = $member['mb_ck'];

	return $arr;
}

$arr = api_savePushInfo2();

include ("./".$return_type.".php");
?>