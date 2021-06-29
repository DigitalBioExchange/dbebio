<?
ini_set("memory_limit" , -1);
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$_is_ajax = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
	&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ){
		$_is_ajax = true;
}

if ($_is_ajax != true) {
	alert("정상적인 방법이 아닙니다.");
}

set_time_limit(600);


//$arr = array();
//$arr['result'] = "success";$arr['msg'] = "";$arr['POST'] = $_POST;$arr['FILE'] = $_FILES;echo json_encode($arr);exit;
$rslt = array();

if($_POST['mfile_cnt'] > 0){
	for($i=0;$i<$_POST['mfile_cnt'];$i++){
		$idx = $_POST['mfile_index_'.$i];
		$src = $_FILES["mfile_".$idx]['tmp_name'];
		$ext = strtolower(get_file_ext($_FILES["mfile_".$idx]['name']));
		$tgt_name = md5(uniqid(rand(), TRUE)).".".$ext;
		$org_name = $_FILES["mfile_".$idx]['name'];
		$tgt = $_cfg['web_home']."/data/tmp/".$tgt_name;
		@move_uploaded_file($src, $tgt);
		@chmod($tgt, 0666);
		$tf_type = $_FILES["mfile_".$idx]['type'];

		sql_query("insert into rb_tmp_file set tf_name = '$tgt_name', tf_type = '$tf_type', tf_regdate = now()");

		$temp = array();
		$temp['idx'] = $idx;
		$temp['filename'] = $tgt_name;
		$temp['org_filename'] = $org_name;
		$rslt[] = $temp;
	}
}

$arr = array();
$arr['result'] = "success";
$arr['msg'] = "";
$arr['data_cnt'] = count($rslt);
$arr['data'] = $rslt;
echo json_encode($arr);exit;
?>