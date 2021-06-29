<?
include "../_inc/_common.php";
header("Content-Type: application/json;charset=utf-8");

$pd_idx = $_GET[pd_idx];

/*
if(!$is_member){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "로그인 후 이용하세요";
	echo json_encode($arr);exit;
}
*/

$chk = sql_fetch("select * from rb_product where pd_idx = '{$pd_idx}'");
if(!$chk[pd_idx]){
	$arr = array();
	$arr[result] = "error";
	$arr[msg] = "없는 상품입니다.";
	echo json_encode($arr);exit;
}

if($_GET[page]){
	$page = $_GET[page];
}else{
	$page = 1;
}

$order_query = " order by pq_idx desc ";

// 전체 데이터수 구하기
$sql_total = "select * from rb_product_qna as q where q.pd_idx = '$pd_idx' $search_query";
$total = sql_total($sql_total);


//$total = 2367;
//$page = 46;
// 페이징 만들기 시작
$arr = array('total' => $total,
             'page' => $page,
             'row' => 5,
             'scale' => 5,
             'center' => $_cfg['admin_paging_center'],
			 'link' => $query_page,
			 'page_name' => ""
        );

try {$paging = C::paging($arr); }
catch (Exception $e) {
    print 'LINE: '.$e->getLine().' '
                  .C::get_errmsg($e->getmessage());
    exit;
}

// 페이징 만들기 끝

if($total){
	$limit_query = " limit ".$paging['query']->limit." offset ".$paging['query']->offset;

	$sql = "select * from rb_product_qna as q where q.pd_idx = '$pd_idx' $search_query $order_query $limit_query";
	$data = sql_list($sql);

	for($i=0;$i<count($data);$i++){
		$data[$i][pq_title_v] = get_text($data[$i][pq_title]);
		$data[$i][pq_contents_v] = conv_content($data[$i][pq_contents], '');
		$data[$i][pq_answer_v] = conv_content($data[$i][pq_answer], '');
	}

}

$arr = array();
$arr[result] = "success";
$arr[datas] = $data;
$arr[data_cnt] = count($data);
$arr[paging_data] = $paging;
echo json_encode($arr);exit;
?>