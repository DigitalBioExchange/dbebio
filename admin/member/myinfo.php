<?php
$menu_code = "100600";
$menu_mode = "v";

include "../inc/_common.php";
include "../inc/_head.php";
include "../inc/_check.php";

$tpl=new Template;
$tpl->define(array(
    'contents'  =>'member/myinfo.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'bottom'  =>'inc/bottom.tpl',
	'second_menu' => 'inc/second_menu.tpl',
));

$tpl->assign('page_title', get_txt_from_data($_cfg['menu_data'], $menu_code, "menu_code", "menu_name")."- 보기");
$tpl->assign('mode', "update");


$querys = array();
$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";


$data = sql_fetch("select * from rb_member  where mb_idx = '".$member['mb_idx']."' ");
if(!$data['mb_idx']) alert("없는 회원입니다.");

//otp_secret 없으면 만들기
// if($data['mb_otp_secret'] == ""){
// 	$data['mb_otp_secret'] = make_otp_secret_string($data['mb_idx'], $data['mb_otp_num']);
// 	sql_query("update rb_member set mb_otp_secret = '".$data['mb_otp_secret']."' where mb_idx = '".$data['mb_idx']."'");
// }
$tpl->assign('data', $data);


// $InitalizationKey = $data['mb_otp_secret'];
// //echo $InitalizationKey."<br>";
// $TimeStamp	  = Google2FA::get_timestamp();
// //echo $TimeStamp."<br>";
// $secretkey 	  = Google2FA::base32_decode($InitalizationKey);

// $otp       	  = Google2FA::oath_hotp($secretkey, $TimeStamp);
// $tpl->assign('otp', $otp);
//echo $otp."<br>";
//exit;

// 권한 목록
$data['auth_v_arr'] = explode(",", $data["auth_v"]);
$data['auth_w_arr'] = explode(",", $data["auth_w"]);

$temp = array();
$cnt = 0;
for($i=0;$i<count($_cfg['menu_data']);$i++){
	if(strlen($_cfg['menu_data'][$i]['menu_code']) == 6){
		$cnt++;
		$tmp = array();
		$tmp['menu_code'] = $_cfg['menu_data'][$i]['menu_code'];
		$tmp['menu_name'] = $_cfg['menu_data'][$i]['menu_name'];
		$tmp['checked_v'] = (in_array($tmp['menu_code'], $data['auth_v_arr']) || $data['user_level'] == 9) ? "checked" : "";
		$tmp['checked_w'] = (in_array($tmp['menu_code'], $data['auth_w_arr']) || $data['user_level'] == 9) ? "checked" : "";
		$tmp['last'] = (strlen($_cfg['menu_data'][($i+1)]['menu_code']) == 3 || !$_cfg['menu_data'][($i+1)]['menu_code']) ? $cnt : "";
		$temp[$last_i]['cnt'] = $cnt;
		$tmp['top_menu_code'] = substr($_cfg['menu_data'][$i]['menu_code'], 0, 3);
		$temp[] = $tmp;

	}else{
		$tmp = array();
		$tmp['menu_code'] = "";
		$tmp['menu_name'] = $_cfg['menu_data'][$i]['menu_name'];
		$tmp['top_menu_code'] = $_cfg['menu_data'][$i]['menu_code'];
		$tmp['cnt'] = 0;
		$temp[] = $tmp;
		$last_i = $i;
		$cnt = 0;
	}
}
$auth_data = $temp;



$tpl->assign('auth_data', $auth_data);

$tpl->print_('body');
include "../inc/_tail.php";
?>