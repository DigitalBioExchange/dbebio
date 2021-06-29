<?
if($user_agent == "web"){
	include $_cfg['web_home']."/_inc/_head.php";
}else if($user_agent == "mobile"){
	include $_cfg['web_home']."/_inc/_head_mobile.php";
}else if($user_agent == "app"){
	include $_cfg['web_home']."/_inc/_head_app.php";
}
$header_printed = 1;
?>