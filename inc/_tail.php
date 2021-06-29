<?
if($user_agent == "web"){
	include $_cfg['web_home']."/_inc/_tail.php";
}else if($user_agent == "mobile"){
	include $_cfg['web_home']."/_inc/_tail_mobile.php";
}else if($user_agent == "app"){
	include $_cfg['web_home']."/_inc/_tail_app.php";
}
?>