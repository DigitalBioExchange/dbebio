<?php
$page_name = "index";
$page_option = "header-white";
include "../inc/_common.php";

$t_menu = 1;
$l_menu = 1;

if ($user_agent != "app") {
	$gnb = "2";
	goto_login();
}

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'company/measurement_result_list.tpl',

	'left'  =>	'inc/member_left.tpl',

	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));



// GET 값 구해서 각각 필요에 따라 파라미터 만들기
if($_GET['page']){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$querys[] = "page=".$page;

$order_query = "order by bf_regdate desc";

$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";
$query_page = (is_array($querys_page) && count($querys_page) > 0) ? implode("&", $querys_page) : "";


// 전체 데이터수 구하기
$sql_total = "select * from rb_body_fat where mb_idx = '".$member['mb_idx']."' $search_query";
$total = sql_total($sql_total);


//$total = 2367;
//$page = 46;
// 페이징 만들기 시작
$arr = array('total' => $total,
             'page' => $page,
             'row' => 10,
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
$tpl->assign($paging);
$tpl->assign('paging_data', $paging);

// 페이징 만들기 끝

if($total){
	$limit_query = " limit ".$paging['query']->limit." offset ".$paging['query']->offset;

	$sql = "select * from rb_body_fat where mb_idx = '".$member['mb_idx']."' $search_query $order_query $limit_query";
	$data = sql_list($sql);

	foreach ($data as $key => $value) {
		//체중범위
		if ($member['mb_sex'] == 1) {
			if ($member['mb_height'] < 150) {
				if ($value['weight'] < 45) {
					$weight_graph = 34 - (45 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 45 && $value['weight'] < 50) {
					$weight_graph = 50 - ((49 - $value['weight']) * 4);
				} else if ($value['weight'] >= 50 && $value['weight'] <= 54) {
					$weight_graph = 50 + (($value['weight'] - 50) * 4);
				} else if ($value['weight'] > 54) {
					$weight_graph = 67 + (($value['weight'] - 54) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 150 && $member['mb_height'] < 155) {
				if ($value['weight'] < 48) {
					$weight_graph = 34 - (48 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 48 && $value['weight'] < 53) {
					$weight_graph = 50 - ((52 - $value['weight']) * 4);
				} else if ($value['weight'] >= 53 && $value['weight'] <= 57) {
					$weight_graph = 50 + (($value['weight'] - 53) * 4);
				} else if ($value['weight'] > 57) {
					$weight_graph = 67 + (($value['weight'] - 57) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 155 && $member['mb_height'] < 160) {
				if ($value['weight'] < 52) {
					$weight_graph = 34 - (52 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 52 && $value['weight'] < 57) {
					$weight_graph = 50 - ((56 - $value['weight']) * 4);
				} else if ($value['weight'] >= 57 && $value['weight'] <= 61) {
					$weight_graph = 50 + (($value['weight'] - 57) * 4);
				} else if ($value['weight'] > 61) {
					$weight_graph = 67 + (($value['weight'] - 61) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 160 && $member['mb_height'] < 165) {
				if ($value['weight'] < 55) {
					$weight_graph = 34 - (55 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 55 && $value['weight'] < 60) {
					$weight_graph = 50 - ((59 - $value['weight']) * 4);
				} else if ($value['weight'] >= 60 && $value['weight'] <= 64) {
					$weight_graph = 50 + (($value['weight'] - 60) * 4);
				} else if ($value['weight'] > 64) {
					$weight_graph = 67 + (($value['weight'] - 64) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 165 && $member['mb_height'] < 170) {
				if ($value['weight'] < 59) {
					$weight_graph = 34 - (59 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 59 && $value['weight'] < 64) {
					$weight_graph = 50 - ((63 - $value['weight']) * 4);
				} else if ($value['weight'] >= 64 && $value['weight'] <= 68) {
					$weight_graph = 50 + (($value['weight'] - 64) * 4);
				} else if ($value['weight'] > 68) {
					$weight_graph = 67 + (($value['weight'] - 68) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 170 && $member['mb_height'] < 175) {
				if ($value['weight'] < 63) {
					$weight_graph = 34 - (63 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 63 && $value['weight'] < 68) {
					$weight_graph = 50 - ((67 - $value['weight']) * 4);
				} else if ($value['weight'] >= 68 && $value['weight'] <= 72) {
					$weight_graph = 50 + (($value['weight'] - 68) * 4);
				} else if ($value['weight'] > 72) {
					$weight_graph = 67 + (($value['weight'] - 72) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 175 && $member['mb_height'] < 180) {
				if ($value['weight'] < 67) {
					$weight_graph = 34 - (67 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 67 && $value['weight'] < 72) {
					$weight_graph = 50 - ((71 - $value['weight']) * 4);
				} else if ($value['weight'] >= 72 && $value['weight'] <= 76) {
					$weight_graph = 50 + (($value['weight'] - 72) * 4);
				} else if ($value['weight'] > 76) {
					$weight_graph = 67 + (($value['weight'] - 76) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 180) {
				if ($value['weight'] < 71) {
					$weight_graph = 34 - (71 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 71 && $value['weight'] < 76) {
					$weight_graph = 50 - ((75 - $value['weight']) * 4);
				} else if ($value['weight'] >= 76 && $value['weight'] <= 82) {
					$weight_graph = 50 + (($value['weight'] - 76) * 4);
				} else if ($value['weight'] > 82) {
					$weight_graph = 67 + (($value['weight'] - 82) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			}
		} else if ($member['mb_sex'] == 2) {
			if ($member['mb_height'] < 150) {
				if ($value['weight'] < 43) {
					$weight_graph = 34 - (43 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 43 && $value['weight'] < 48) {
					$weight_graph = 50 - ((47 - $value['weight']) * 4);
				} else if ($value['weight'] >= 48 && $value['weight'] <= 52) {
					$weight_graph = 50 + (($value['weight'] - 48) * 4);
				} else if ($value['weight'] > 52) {
					$weight_graph = 67 + (($value['weight'] - 52) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 150 && $member['mb_height'] < 155) {
				if ($value['weight'] < 46) {
					$weight_graph = 34 - (46 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 46 && $value['weight'] < 51) {
					$weight_graph = 50 - ((50 - $value['weight']) * 4);
				} else if ($value['weight'] >= 51 && $value['weight'] <= 55) {
					$weight_graph = 50 + (($value['weight'] - 51) * 4);
				} else if ($value['weight'] > 55) {
					$weight_graph = 67 + (($value['weight'] - 55) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 155 && $member['mb_height'] < 160) {
				if ($value['weight'] < 49) {
					$weight_graph = 34 - (49 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 49 && $value['weight'] < 54) {
					$weight_graph = 50 - ((53 - $value['weight']) * 4);
				} else if ($value['weight'] >= 54 && $value['weight'] <= 58) {
					$weight_graph = 50 + (($value['weight'] - 54) * 4);
				} else if ($value['weight'] > 58) {
					$weight_graph = 67 + (($value['weight'] - 58) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 160 && $member['mb_height'] < 165) {
				if ($value['weight'] < 53) {
					$weight_graph = 34 - (53 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 53 && $value['weight'] < 58) {
					$weight_graph = 50 - ((57 - $value['weight']) * 4);
				} else if ($value['weight'] >= 58 && $value['weight'] <= 64) {
					$weight_graph = 50 + (($value['weight'] - 58) * 4);
				} else if ($value['weight'] > 64) {
					$weight_graph = 67 + (($value['weight'] - 64) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 165 && $member['mb_height'] < 170) {
				if ($value['weight'] < 56) {
					$weight_graph = 34 - (56 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 56 && $value['weight'] < 61) {
					$weight_graph = 50 - ((60 - $value['weight']) * 4);
				} else if ($value['weight'] >= 61 && $value['weight'] <= 65) {
					$weight_graph = 50 + (($value['weight'] - 61) * 4);
				} else if ($value['weight'] > 65) {
					$weight_graph = 67 + (($value['weight'] - 65) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 170 && $member['mb_height'] < 175) {
				if ($value['weight'] < 60) {
					$weight_graph = 34 - (60 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 60 && $value['weight'] < 65) {
					$weight_graph = 50 - ((64 - $value['weight']) * 4);
				} else if ($value['weight'] >= 65 && $value['weight'] <= 69) {
					$weight_graph = 50 + (($value['weight'] - 65) * 4);
				} else if ($value['weight'] > 69) {
					$weight_graph = 67 + (($value['weight'] - 69) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 175 && $member['mb_height'] < 180) {
				if ($value['weight'] < 64) {
					$weight_graph = 34 - (64 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 64 && $value['weight'] < 69) {
					$weight_graph = 50 - ((68 - $value['weight']) * 4);
				} else if ($value['weight'] >= 69 && $value['weight'] <= 73) {
					$weight_graph = 50 + (($value['weight'] - 69) * 4);
				} else if ($value['weight'] > 73) {
					$weight_graph = 67 + (($value['weight'] - 73) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}

			} else if ($member['mb_height'] >= 180) {
				if ($value['weight'] < 69) {
					$weight_graph = 34 - (69 - $value['weight']);
					if ($weight_graph < 0) {
						$weight_graph = 0;
					}
				} else if ($value['weight'] >= 69 && $value['weight'] < 74) {
					$weight_graph = 50 - ((73 - $value['weight']) * 4);
				} else if ($value['weight'] >= 74 && $value['weight'] <= 78) {
					$weight_graph = 50 + (($value['weight'] - 74) * 4);
				} else if ($value['weight'] > 78) {
					$weight_graph = 67 + (($value['weight'] - 78) / 2);
					if ($weight_graph >= 100) {
						$weight_graph = 100;
					}
				}
				
			}

		}
		$data[$key]['weight_graph'] = $weight_graph;

		//BMI범위
		if ($value['BMI'] < 18.5) {
			$bmi_graph = 25 - ((18.5 - $value['BMI']) * 2.5);
			if ($bmi_graph < 0) {
				$bmi_graph = 0;
			}
		} else if ($value['BMI'] >= 18.5 && $value['BMI'] < 20.7) {
			$bmi_graph = 37.5 - ((20.7 - $value['BMI']) * 10);
		} else if ($value['BMI'] >= 20.7 && $value['BMI'] < 23) {
			$bmi_graph = 37.5 + (($value['BMI'] - 20.7) * 10);
		} else if ($value['BMI'] >= 23 && $value['BMI'] < 24) {
			$bmi_graph = 62.5 - ((23.9 - $value['BMI']) * 28);
		} else if ($value['BMI'] >= 24 && $value['BMI'] < 25) {
			$bmi_graph = 62.5 + (($value['BMI'] - 24) * 28);
		} else if ($value['BMI'] >= 25) {
			$bmi_graph = 75 + (($value['BMI'] - 25) * 2.5);
			if ($bmi_graph >= 100) {
				$bmi_graph = 100;
			}
		}
		$data[$key]['bmi_graph'] = $bmi_graph;

		//body_fat범위
		$age = cal_age($member['mb_birth']);
		if ($member['mb_sex'] == 1) {
			if ($age < 40) {
				if ($value['body_fat_rate'] < 8) {
					$body_fat_graph = 25 - ((7.9 - $value['body_fat_rate']) * 3);
					if ($body_fat_graph < 0) {
						$body_fat_graph = 0;
					}
				} else if ($value['body_fat_rate'] >= 8 && $value['body_fat_rate'] < 14) {
					$body_fat_graph = 37.5 - ((13.9 - $value['body_fat_rate']) * 2.1);
				} else if ($value['body_fat_rate'] >= 14 && $value['body_fat_rate'] < 20) {
					$body_fat_graph = 37.5 + (($value['body_fat_rate'] - 14) * 2.1);
				} else if ($value['body_fat_rate'] >= 20 && $value['body_fat_rate'] < 23) {
					$body_fat_graph = 62.5 - ((22.9 - $value['body_fat_rate']) * 4.3);
				} else if ($value['body_fat_rate'] >= 23 && $value['body_fat_rate'] < 26) {
					$body_fat_graph = 62.5 + (($value['body_fat_rate'] - 23) * 4.3);
				} else if ($value['body_fat_rate'] >= 26) {
					$body_fat_graph = 75 + (($value['body_fat_rate'] - 26) * 3);
					if ($body_fat_graph >= 100) {
						$body_fat_graph = 100;
					}
				}

			} else if ($age >= 40 && $age < 60) {
				if ($value['body_fat_rate'] < 11) {
					$body_fat_graph = 25 - ((10.9 - $value['body_fat_rate']) * 3);
					if ($body_fat_graph < 0) {
						$body_fat_graph = 0;
					}
				} else if ($value['body_fat_rate'] >= 11 && $value['body_fat_rate'] < 16.5) {
					$body_fat_graph = 37.5 - ((16.4 - $value['body_fat_rate']) * 2.3);
				} else if ($value['body_fat_rate'] >= 16.5 && $value['body_fat_rate'] < 22) {
					$body_fat_graph = 37.5 + (($value['body_fat_rate'] - 16.5) * 2.3);
				} else if ($value['body_fat_rate'] >= 22 && $value['body_fat_rate'] < 25.5) {
					$body_fat_graph = 62.5 - ((25.4 - $value['body_fat_rate']) * 3.6);
				} else if ($value['body_fat_rate'] >= 25.5 && $value['body_fat_rate'] < 29) {
					$body_fat_graph = 62.5 + (($value['body_fat_rate'] - 25.5) * 3.6);
				} else if ($value['body_fat_rate'] >= 29) {
					$body_fat_graph = 75 + (($value['body_fat_rate'] - 29) * 3);
					if ($body_fat_graph >= 100) {
						$body_fat_graph = 100;
					}
				}

			} else if ($age >= 60) {
				if ($value['body_fat_rate'] < 13) {
					$body_fat_graph = 25 - ((12.9 - $value['body_fat_rate']) * 2);
					if ($body_fat_graph < 0) {
						$body_fat_graph = 0;
					}
				} else if ($value['body_fat_rate'] >= 13 && $value['body_fat_rate'] < 19) {
					$body_fat_graph = 37.5 - ((18.9 - $value['body_fat_rate']) * 2.1);
				} else if ($value['body_fat_rate'] >= 19 && $value['body_fat_rate'] < 25) {
					$body_fat_graph = 37.5 + (($value['body_fat_rate'] - 19) * 2.1);
				} else if ($value['body_fat_rate'] >= 25 && $value['body_fat_rate'] < 28) {
					$body_fat_graph = 62.5 - ((27.9 - $value['body_fat_rate']) * 4.3);
				} else if ($value['body_fat_rate'] >= 28 && $value['body_fat_rate'] < 31) {
					$body_fat_graph = 62.5 + (($value['body_fat_rate'] - 28) * 4.3);
				} else if ($value['body_fat_rate'] >= 31) {
					$body_fat_graph = 75 + (($value['body_fat_rate'] - 31) * 2);
					if ($body_fat_graph >= 100) {
						$body_fat_graph = 100;
					}
				}

			}
		} else if ($member['mb_sex'] == 2) {
			if ($age < 40) {
				if ($value['body_fat_rate'] < 20) {
					$body_fat_graph = 25 - ((19.9 - $value['body_fat_rate']) * 2.2);
					if ($body_fat_graph < 0) {
						$body_fat_graph = 0;
					}
				} else if ($value['body_fat_rate'] >= 20 && $value['body_fat_rate'] < 26.5) {
					$body_fat_graph = 37.5 - ((26.4 - $value['body_fat_rate']) * 1.95);
				} else if ($value['body_fat_rate'] >= 26.5 && $value['body_fat_rate'] < 33) {
					$body_fat_graph = 37.5 + (($value['body_fat_rate'] - 26.5) * 1.95);
				} else if ($value['body_fat_rate'] >= 33 && $value['body_fat_rate'] < 36.5) {
					$body_fat_graph = 62.5 - ((36.4 - $value['body_fat_rate']) * 3.6);
				} else if ($value['body_fat_rate'] >= 36.5 && $value['body_fat_rate'] < 40) {
					$body_fat_graph = 62.5 + (($value['body_fat_rate'] - 36.5) * 3.6);
				} else if ($value['body_fat_rate'] >= 40) {
					$body_fat_graph = 75 + (($value['body_fat_rate'] - 40) * 2.2);
					if ($body_fat_graph >= 100) {
						$body_fat_graph = 100;
					}
				}

			} else if ($age >= 40 && $age < 60) {
				if ($value['body_fat_rate'] < 22) {
					$body_fat_graph = 25 - ((21.9 - $value['body_fat_rate']) * 2.1);
					if ($body_fat_graph < 0) {
						$body_fat_graph = 0;
					}
				} else if ($value['body_fat_rate'] >= 22 && $value['body_fat_rate'] < 28) {
					$body_fat_graph = 37.5 - ((27.9 - $value['body_fat_rate']) * 2.1);
				} else if ($value['body_fat_rate'] >= 28 && $value['body_fat_rate'] < 34) {
					$body_fat_graph = 37.5 + (($value['body_fat_rate'] - 28) * 2.1);
				} else if ($value['body_fat_rate'] >= 34 && $value['body_fat_rate'] < 37.5) {
					$body_fat_graph = 62.5 - ((37.4 - $value['body_fat_rate']) * 3.6);
				} else if ($value['body_fat_rate'] >= 37.5 && $value['body_fat_rate'] < 41) {
					$body_fat_graph = 62.5 + (($value['body_fat_rate'] - 37.5) * 3.6);
				} else if ($value['body_fat_rate'] >= 41) {
					$body_fat_graph = 75 + (($value['body_fat_rate'] - 41) * 2.1);
					if ($body_fat_graph >= 100) {
						$body_fat_graph = 100;
					}
				}

			} else if ($age >= 60) {
				if ($value['body_fat_rate'] < 23) {
					$body_fat_graph = 25 - ((22.9 - $value['body_fat_rate']) * 2);
					if ($body_fat_graph < 0) {
						$body_fat_graph = 0;
					}
				} else if ($value['body_fat_rate'] >= 23 && $value['body_fat_rate'] < 29.5) {
					$body_fat_graph = 37.5 - ((29.4 - $value['body_fat_rate']) * 1.95);
				} else if ($value['body_fat_rate'] >= 29.5 && $value['body_fat_rate'] < 36) {
					$body_fat_graph = 37.5 + (($value['body_fat_rate'] - 29.5) * 1.95);
				} else if ($value['body_fat_rate'] >= 36 && $value['body_fat_rate'] < 39.5) {
					$body_fat_graph = 62.5 - ((39.4 - $value['body_fat_rate']) * 3.6);
				} else if ($value['body_fat_rate'] >= 39.5 && $value['body_fat_rate'] < 43) {
					$body_fat_graph = 62.5 + (($value['body_fat_rate'] - 39.5) * 3.6);
				} else if ($value['body_fat_rate'] >= 43) {
					$body_fat_graph = 75 + (($value['body_fat_rate'] - 43) * 2);
					if ($body_fat_graph >= 100) {
						$body_fat_graph = 100;
					}
				}

			}
		}
		$data[$key]['body_fat_graph'] = $body_fat_graph;

		//내장지방 visceral_fat
		if ($value['visceral_fat'] < 9) {
			$visceral_fat_graph = 34 - ((8.9 - $value['visceral_fat']) * 3.6);
			if ($visceral_fat_graph < 0) {
				$visceral_fat_graph = 0;
			}
		} else if ($value['visceral_fat'] >= 9 && $value['visceral_fat'] < 12) {
			$visceral_fat_graph = 50 - ((11.9 - $value['visceral_fat']) * 5);
		} else if ($value['visceral_fat'] >= 12 && $value['visceral_fat'] < 15) {
			$visceral_fat_graph = 50 + (($value['visceral_fat'] - 12) * 5);
		} else if ($value['visceral_fat'] >= 15) {
			$visceral_fat_graph = 67 + (($value['visceral_fat'] - 15) * 3.6);
			if ($visceral_fat_graph >= 100) {
				$visceral_fat_graph = 100;
			}
		}
		$data[$key]['visceral_fat_graph'] = $visceral_fat_graph;

		//체수분률 body_water_rate
		if ($age < 18) {
			if ($value['body_water_rate'] < 70) {
				$body_water_graph = 34 - ((69.9 - $value['body_water_rate']) / 2);
				if ($body_water_graph < 0) {
					$body_water_graph = 0;
				}
			} else if ($value['body_water_rate'] >= 70 && $value['body_water_rate'] < 74) {
				$body_water_graph = 50 - ((73.9 - $value['body_water_rate']) * 3.8);
			} else if ($value['body_water_rate'] >= 74 && $value['body_water_rate'] < 78) {
				$body_water_graph = 50 + (($value['body_water_rate'] - 74) * 3.8);
			} else if ($value['body_water_rate'] >= 78) {
				$body_water_graph = 67 + (($value['body_water_rate'] - 78) * 1.5);
				if ($body_water_graph >= 100) {
					$body_water_graph = 100;
				}
			}
		} else if ($age >= 18 && $age < 65) {
			if ($member['mb_sex'] == 1) {
				if ($value['body_water_rate'] < 60) {
					$body_water_graph = 34 - ((59.9 - $value['body_water_rate']) / 1.7);
					if ($body_water_graph < 0) {
						$body_water_graph = 0;
					}
				} else if ($value['body_water_rate'] >= 60 && $value['body_water_rate'] < 61.5) {
					$body_water_graph = 50 - ((61.4 - $value['body_water_rate']) * 11);
				} else if ($value['body_water_rate'] >= 61.5 && $value['body_water_rate'] < 63) {
					$body_water_graph = 50 + (($value['body_water_rate'] - 61.5) * 11);
				} else if ($value['body_water_rate'] >= 78) {
					$body_water_graph = 67 + (($value['body_water_rate'] - 78) * 1.5);
					if ($body_water_graph >= 100) {
						$body_water_graph = 100;
					}
				}

			} else if ($member['mb_sex'] == 2) {
				if ($value['body_water_rate'] < 55) {
					$body_water_graph = 34 - ((54.9 - $value['body_water_rate']) / 1.7);
					if ($body_water_graph < 0) {
						$body_water_graph = 0;
					}
				} else if ($value['body_water_rate'] >= 55 && $value['body_water_rate'] < 57) {
					$body_water_graph = 50 - ((56.9 - $value['body_water_rate']) * 8.2);
				} else if ($value['body_water_rate'] >= 57 && $value['body_water_rate'] < 59) {
					$body_water_graph = 50 + (($value['body_water_rate'] - 57) * 8.2);
				} else if ($value['body_water_rate'] >= 59) {
					$body_water_graph = 67 + (($value['body_water_rate'] - 59) / 1.2);
					if ($body_water_graph >= 100) {
						$body_water_graph = 100;
					}
				}

			}
		} else if ($age >= 65) {
			if ($value['body_water_rate'] < 45) {
				$body_water_graph = 34 - ((44.9 - $value['body_water_rate']) / 1.3);
				if ($body_water_graph < 0) {
					$body_water_graph = 0;
				}
			} else if ($value['body_water_rate'] >= 45 && $value['body_water_rate'] < 45.5) {
				$body_water_graph = 50 - ((45.4 - $value['body_water_rate']) * 40);
			} else if ($value['body_water_rate'] >= 45.5 && $value['body_water_rate'] < 46) {
				$body_water_graph = 50 + (($value['body_water_rate'] - 45.5) * 40);
			} else if ($value['body_water_rate'] >= 46) {
				$body_water_graph = 67 + (($value['body_water_rate'] - 46) / 1.2);
				if ($body_water_graph >= 100) {
					$body_water_graph = 100;
				}
			}

		}
		$data[$key]['body_water_graph'] = $body_water_graph;

		//근육량 muscle_mass
		if ($member['mb_sex'] == 1) {
			if ($value['muscle_mass'] < 40) {
				$muscle_mass_graph = 50 - ((39.9 - $value['muscle_mass']) * 1.3);
				if ($muscle_mass_graph < 0) {
					$muscle_mass_graph = 0;
				}
			} else if ($value['muscle_mass'] >= 40) {
				$muscle_mass_graph = 50 + (($value['muscle_mass'] - 40) / 1.2);
				if ($muscle_mass_graph >= 100) {
					$muscle_mass_graph = 100;
				}
			}
		} else if ($member['mb_sex'] == 2) {
			if ($value['muscle_mass'] < 34) {
				$muscle_mass_graph = 50 - ((33.9 - $value['muscle_mass']) * 1.5);
				if ($muscle_mass_graph < 0) {
					$muscle_mass_graph = 0;
				}
			} else if ($value['muscle_mass'] >= 34) {
				$muscle_mass_graph = 50 + (($value['muscle_mass'] - 34) / 1.3);
				if ($muscle_mass_graph >= 100) {
					$muscle_mass_graph = 100;
				}
			}
		}
		$data[$key]['muscle_mass_graph'] = $muscle_mass_graph;

		//골질량 bone_mass
		if ($member['mb_sex'] == 1) {
			if ($value['weight'] < 60) {
				if ($value['bone_mass'] < 2.4) {
					$bone_mass_graph = 34 - ((2.39 - $value['bone_mass']) * 14.3);
					if ($bone_mass_graph < 0) {
						$bone_mass_graph = 0;
					}
				} else if ($value['bone_mass'] >= 2.4 && $value['bone_mass'] < 2.5) {
					$bone_mass_graph = 50 - ((2.49 - $value['bone_mass']) * 175);
				} else if ($value['bone_mass'] >= 2.5 && $value['bone_mass'] < 2.6) {
					$bone_mass_graph = 50 + (($value['bone_mass'] - 2.5) * 175);
				} else if ($value['bone_mass'] >= 2.6) {
					$bone_mass_graph = 67 + (($value['bone_mass'] - 2.6) * 14.3);
					if ($bone_mass_graph >= 100) {
						$bone_mass_graph = 100;
					}
				}
			} else if ($value['weight'] >= 60 && $value['weight'] < 76) {
				if ($value['bone_mass'] < 2.8) {
					$bone_mass_graph = 34 - ((2.79 - $value['bone_mass']) * 12.5);
					if ($bone_mass_graph < 0) {
						$bone_mass_graph = 0;
					}
				} else if ($value['bone_mass'] >= 2.8 && $value['bone_mass'] < 2.9) {
					$bone_mass_graph = 50 - ((2.89 - $value['bone_mass']) * 175);
				} else if ($value['bone_mass'] >= 2.9 && $value['bone_mass'] < 3.0) {
					$bone_mass_graph = 50 + (($value['bone_mass'] - 2.9) * 175);
				} else if ($value['bone_mass'] >= 3.0) {
					$bone_mass_graph = 67 + (($value['bone_mass'] - 3.0) * 12.5);
					if ($bone_mass_graph >= 100) {
						$bone_mass_graph = 100;
					}
				}
			} else if ($value['weight'] >= 76) {
				if ($value['bone_mass'] < 3.1) {
					$bone_mass_graph = 34 - ((3.09 - $value['bone_mass']) * 11.2);
					if ($bone_mass_graph < 0) {
						$bone_mass_graph = 0;
					}
				} else if ($value['bone_mass'] >= 3.1 && $value['bone_mass'] < 3.2) {
					$bone_mass_graph = 50 - ((3.19 - $value['bone_mass']) * 175);
				} else if ($value['bone_mass'] >= 3.2 && $value['bone_mass'] < 3.3) {
					$bone_mass_graph = 50 + (($value['bone_mass'] - 3.2) * 175);
				} else if ($value['bone_mass'] >= 3.3) {
					$bone_mass_graph = 67 + (($value['bone_mass'] - 3.3) * 11.2);
					if ($bone_mass_graph >= 100) {
						$bone_mass_graph = 100;
					}
				}
			}

		} else if ($member['mb_sex'] == 2) {
			if ($value['weight'] < 45) {
				if ($value['bone_mass'] < 1.7) {
					$bone_mass_graph = 34 - ((1.69 - $value['bone_mass']) * 20.2);
					if ($bone_mass_graph < 0) {
						$bone_mass_graph = 0;
					}
				} else if ($value['bone_mass'] >= 1.7 && $value['bone_mass'] < 1.8) {
					$bone_mass_graph = 50 - ((1.79 - $value['bone_mass']) * 175);
				} else if ($value['bone_mass'] >= 1.8 && $value['bone_mass'] < 1.9) {
					$bone_mass_graph = 50 + (($value['bone_mass'] - 1.8) * 175);
				} else if ($value['bone_mass'] >= 1.9) {
					$bone_mass_graph = 67 + (($value['bone_mass'] - 1.9) * 20.2);
					if ($bone_mass_graph >= 100) {
						$bone_mass_graph = 100;
					}
				}
			} else if ($value['weight'] >= 45 && $value['weight'] < 61) {
				if ($value['bone_mass'] < 2.1) {
					$bone_mass_graph = 34 - ((2.09 - $value['bone_mass']) * 16.3);
					if ($bone_mass_graph < 0) {
						$bone_mass_graph = 0;
					}
				} else if ($value['bone_mass'] >= 2.1 && $value['bone_mass'] < 2.2) {
					$bone_mass_graph = 50 - ((2.19 - $value['bone_mass']) * 175);
				} else if ($value['bone_mass'] >= 2.2 && $value['bone_mass'] < 2.3) {
					$bone_mass_graph = 50 + (($value['bone_mass'] - 2.2) * 175);
				} else if ($value['bone_mass'] >= 2.3) {
					$bone_mass_graph = 67 + (($value['bone_mass'] - 2.3) * 16.3);
					if ($bone_mass_graph >= 100) {
						$bone_mass_graph = 100;
					}
				}
			} else if ($value['weight'] >= 61) {
				if ($value['bone_mass'] < 2.4) {
					$bone_mass_graph = 34 - ((2.39 - $value['bone_mass']) * 14.3);
					if ($bone_mass_graph < 0) {
						$bone_mass_graph = 0;
					}
				} else if ($value['bone_mass'] >= 2.4 && $value['bone_mass'] < 2.5) {
					$bone_mass_graph = 50 - ((2.49 - $value['bone_mass']) * 175);
				} else if ($value['bone_mass'] >= 2.5 && $value['bone_mass'] < 2.6) {
					$bone_mass_graph = 50 + (($value['bone_mass'] - 2.5) * 175);
				} else if ($value['bone_mass'] >= 2.6) {
					$bone_mass_graph = 67 + (($value['bone_mass'] - 2.6) * 14.3);
					if ($bone_mass_graph >= 100) {
						$bone_mass_graph = 100;
					}
				}
			}

		}
		$data[$key]['bone_mass_graph'] = $bone_mass_graph;

		//기초대사량 BMR
		if ($member['mb_sex'] == 1) {
			if ($age < 30) {
				if ($value['BMR'] < 1360) {
					$bmr_graph = 34 - ((1359 - $value['BMR']) / 39.5);
					if ($bmr_graph < 0) {
						$bmr_graph = 0;
					}
				} else if ($value['BMR'] >= 1360 && $value['BMR'] < 1728) {
					$bmr_graph = 50 - ((1727 - $value['BMR']) / 22.9);
				} else if ($value['BMR'] >= 1782 && $value['BMR'] < 2150) {
					$bmr_graph = 50 + (($value['BMR'] - 1782) / 22.9);
				} else if ($value['BMR'] >= 2150) {
					$bmr_graph = 67 + (($value['BMR'] - 2150) / 39.5);
					if ($bmr_graph >= 100) {
						$bmr_graph = 100;
					}
				}

			} else if ($age >= 30 && $age < 50) {
				if ($value['BMR'] < 1367) {
					$bmr_graph = 34 - ((1366 - $value['BMR']) / 40.1);
					if ($bmr_graph < 0) {
						$bmr_graph = 0;
					}
				} else if ($value['BMR'] >= 1367 && $value['BMR'] < 1669) {
					$bmr_graph = 50 - ((1668 - $value['BMR']) / 19);
				} else if ($value['BMR'] >= 1669 && $value['BMR'] < 1971) {
					$bmr_graph = 50 + (($value['BMR'] - 1669) / 19);
				} else if ($value['BMR'] >= 1971) {
					$bmr_graph = 67 + (($value['BMR'] - 1971) / 40.1);
					if ($bmr_graph >= 100) {
						$bmr_graph = 100;
					}
				}
			} else if ($age >= 50) {
				if ($value['BMR'] < 1178) {
					$bmr_graph = 34 - ((1177 - $value['BMR']) / 34.6);
					if ($bmr_graph < 0) {
						$bmr_graph = 0;
					}
				} else if ($value['BMR'] >= 1178 && $value['BMR'] < 1493) {
					$bmr_graph = 50 - ((1492 - $value['BMR']) / 19.6);
				} else if ($value['BMR'] >= 1493 && $value['BMR'] < 1808) {
					$bmr_graph = 50 + (($value['BMR'] - 1493) / 19.6);
				} else if ($value['BMR'] >= 1808) {
					$bmr_graph = 67 + (($value['BMR'] - 1808) / 34.6);
					if ($bmr_graph >= 100) {
						$bmr_graph = 100;
					}
				}
			}
		} else if ($member['mb_sex'] == 2) {
			if ($age < 30) {
				if ($value['BMR'] < 1078) {
					$bmr_graph = 34 - ((1077 - $value['BMR']) / 31.6);
					if ($bmr_graph < 0) {
						$bmr_graph = 0;
					}
				} else if ($value['BMR'] >= 1078 && $value['BMR'] < 1311) {
					$bmr_graph = 50 - ((1310 - $value['BMR']) / 14.5);
				} else if ($value['BMR'] >= 1311 && $value['BMR'] < 1544) {
					$bmr_graph = 50 + (($value['BMR'] - 1311) / 14.5);
				} else if ($value['BMR'] >= 1544) {
					$bmr_graph = 67 + (($value['BMR'] - 1544) / 31.6);
					if ($bmr_graph >= 100) {
						$bmr_graph = 100;
					}
				}
			} else if ($age >= 30 && $age < 50) {
				if ($value['BMR'] < 1090) {
					$bmr_graph = 34 - ((1089 - $value['BMR']) / 32);
					if ($bmr_graph < 0) {
						$bmr_graph = 0;
					}
				} else if ($value['BMR'] >= 1090 && $value['BMR'] < 1315.5) {
					$bmr_graph = 50 - ((1315.4 - $value['BMR']) / 14.1);
				} else if ($value['BMR'] >= 1315.5 && $value['BMR'] < 1541) {
					$bmr_graph = 50 + (($value['BMR'] - 1315.5) / 14.1);
				} else if ($value['BMR'] >= 1541) {
					$bmr_graph = 67 + (($value['BMR'] - 1541) / 32);
					if ($bmr_graph >= 100) {
						$bmr_graph = 100;
					}
				}
			} else if ($age >= 50) {
				if ($value['BMR'] < 1024) {
					$bmr_graph = 34 - ((1023 - $value['BMR']) / 30);
					if ($bmr_graph < 0) {
						$bmr_graph = 0;
					}
				} else if ($value['BMR'] >= 1024 && $value['BMR'] < 1252) {
					$bmr_graph = 50 - ((1251 - $value['BMR']) / 14.2);
				} else if ($value['BMR'] >= 1252 && $value['BMR'] < 1480) {
					$bmr_graph = 50 + (($value['BMR'] - 1252) / 14.2);
				} else if ($value['BMR'] >= 1480) {
					$bmr_graph = 67 + (($value['BMR'] - 1480) / 30);
					if ($bmr_graph >= 100) {
						$bmr_graph = 100;
					}
				}
			}
		}
		$data[$key]['bmr_graph'] = $bmr_graph;

		//신체나이 metabolic_age
		if ($age >= $value['metabolic_age']) {
			$metabolic_graph = 50 - (($age - $value['metabolic_age']) * 3);
			if ($metabolic_graph < 0) {
				$metabolic_graph = 0;
			}
		} else if ($age < $value['metabolic_age']) {
			$metabolic_graph = 50 + (($value['metabolic_age'] - $age) * 3);
			if ($metabolic_graph >= 100) {
				$metabolic_graph = 100;
			}
		}
		$data[$key]['metabolic_graph'] = $metabolic_graph;
		

	}

	$tpl->assign('data', $data); 
}



include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>