<?
if(!$is_admin) alert("로그인 후 이용하여 주세요.", "/admin/login.php");
if(!$is_super){
	if(!$menu_code) alert("잘못된 접근입니다.");
	if(!$menu_mode) alert("잘못된 접근입니다.");
	$is_menu_code = false;
	for($i=0;$i<count($_cfg[menu_data]);$i++){
		if($menu_code == $_cfg[menu_data][$i][menu_code]) $is_menu_code = true;
	}
	if(!$is_menu_code) alert("잘못된 접근입니다.");

	$auth_arr = explode(",", $member["auth_".$menu_mode]);
	if(!in_array($menu_code, $auth_arr)) alert("권한이 없습니다.");
}

$is_v = (in_array($menu_code, $auth_v_arr) || $is_super) ? 1 : 0;
$is_w = (in_array($menu_code, $auth_w_arr) || $is_super) ? 1 : 0;

?>