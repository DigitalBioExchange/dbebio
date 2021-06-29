<?
$now_site_mode = "admin";
include $_SERVER['DOCUMENT_ROOT']."/_inc/_common_admin.php";
if(!in_array($_SERVER['REMOTE_ADDR'], $_cfg['supersuper_ip']) && 0){
	header("Content-type: text/html; charset=UTF-8");
	die ("작업중입니다.");
}
?>