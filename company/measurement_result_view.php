<?php
$page_name = "index";
$page_option = "header-white";

include "../inc/_common.php";

if ($user_agent != "app") {
	$gnb = "2";
	goto_login();
}

$t_menu = 1;
$l_menu = 1;

$tpl=new Template;
$tpl->define(array(
	'contents'  =>'company/measurement_result_view.tpl',
	'left'  =>	'inc/member_left.tpl',
	'body'  =>'inc/body.tpl',
	'top'  =>'inc/top.tpl',
	'quick'  => 'inc/quick.tpl',
	'bottom'  => 'inc/bottom.tpl',
));


$querys = array();
$querys[] = "page=".$page;
$query = (is_array($querys) && count($querys) > 0) ? implode("&", $querys) : "";

$data = sql_fetch("select * from rb_body_fat where mb_idx = '".$member['mb_idx']."' and bf_idx = '".$bf_idx."' $search_query");

if(!$data['bf_idx']) alert($_lang['inc']['text_0746']);


//체중범위
if ($member['mb_sex'] == 1) {
	if ($member['mb_height'] < 150) {
		if ($data['weight'] < 45) {
			$weight_graph = 34 - (45 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 45 && $data['weight'] < 50) {
			$weight_graph = 50 - ((49 - $data['weight']) * 4);
		} else if ($data['weight'] >= 50 && $data['weight'] <= 54) {
			$weight_graph = 50 + (($data['weight'] - 50) * 4);
		} else if ($data['weight'] > 54) {
			$weight_graph = 67 + (($data['weight'] - 54) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 150 && $member['mb_height'] < 155) {
		if ($data['weight'] < 48) {
			$weight_graph = 34 - (48 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 48 && $data['weight'] < 53) {
			$weight_graph = 50 - ((52 - $data['weight']) * 4);
		} else if ($data['weight'] >= 53 && $data['weight'] <= 57) {
			$weight_graph = 50 + (($data['weight'] - 53) * 4);
		} else if ($data['weight'] > 57) {
			$weight_graph = 67 + (($data['weight'] - 57) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 155 && $member['mb_height'] < 160) {
		if ($data['weight'] < 52) {
			$weight_graph = 34 - (52 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 52 && $data['weight'] < 57) {
			$weight_graph = 50 - ((56 - $data['weight']) * 4);
		} else if ($data['weight'] >= 57 && $data['weight'] <= 61) {
			$weight_graph = 50 + (($data['weight'] - 57) * 4);
		} else if ($data['weight'] > 61) {
			$weight_graph = 67 + (($data['weight'] - 61) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 160 && $member['mb_height'] < 165) {
		if ($data['weight'] < 55) {
			$weight_graph = 34 - (55 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 55 && $data['weight'] < 60) {
			$weight_graph = 50 - ((59 - $data['weight']) * 4);
		} else if ($data['weight'] >= 60 && $data['weight'] <= 64) {
			$weight_graph = 50 + (($data['weight'] - 60) * 4);
		} else if ($data['weight'] > 64) {
			$weight_graph = 67 + (($data['weight'] - 64) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 165 && $member['mb_height'] < 170) {
		if ($data['weight'] < 59) {
			$weight_graph = 34 - (59 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 59 && $data['weight'] < 64) {
			$weight_graph = 50 - ((63 - $data['weight']) * 4);
		} else if ($data['weight'] >= 64 && $data['weight'] <= 68) {
			$weight_graph = 50 + (($data['weight'] - 64) * 4);
		} else if ($data['weight'] > 68) {
			$weight_graph = 67 + (($data['weight'] - 68) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 170 && $member['mb_height'] < 175) {
		if ($data['weight'] < 63) {
			$weight_graph = 34 - (63 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 63 && $data['weight'] < 68) {
			$weight_graph = 50 - ((67 - $data['weight']) * 4);
		} else if ($data['weight'] >= 68 && $data['weight'] <= 72) {
			$weight_graph = 50 + (($data['weight'] - 68) * 4);
		} else if ($data['weight'] > 72) {
			$weight_graph = 67 + (($data['weight'] - 72) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 175 && $member['mb_height'] < 180) {
		if ($data['weight'] < 67) {
			$weight_graph = 34 - (67 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 67 && $data['weight'] < 72) {
			$weight_graph = 50 - ((71 - $data['weight']) * 4);
		} else if ($data['weight'] >= 72 && $data['weight'] <= 76) {
			$weight_graph = 50 + (($data['weight'] - 72) * 4);
		} else if ($data['weight'] > 76) {
			$weight_graph = 67 + (($data['weight'] - 76) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 180) {
		if ($data['weight'] < 71) {
			$weight_graph = 34 - (71 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 71 && $data['weight'] < 76) {
			$weight_graph = 50 - ((75 - $data['weight']) * 4);
		} else if ($data['weight'] >= 76 && $data['weight'] <= 82) {
			$weight_graph = 50 + (($data['weight'] - 76) * 4);
		} else if ($data['weight'] > 82) {
			$weight_graph = 67 + (($data['weight'] - 82) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	}
} else if ($member['mb_sex'] == 2) {
	if ($member['mb_height'] < 150) {
		if ($data['weight'] < 43) {
			$weight_graph = 34 - (43 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 43 && $data['weight'] < 48) {
			$weight_graph = 50 - ((47 - $data['weight']) * 4);
		} else if ($data['weight'] >= 48 && $data['weight'] <= 52) {
			$weight_graph = 50 + (($data['weight'] - 48) * 4);
		} else if ($data['weight'] > 52) {
			$weight_graph = 67 + (($data['weight'] - 52) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 150 && $member['mb_height'] < 155) {
		if ($data['weight'] < 46) {
			$weight_graph = 34 - (46 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 46 && $data['weight'] < 51) {
			$weight_graph = 50 - ((50 - $data['weight']) * 4);
		} else if ($data['weight'] >= 51 && $data['weight'] <= 55) {
			$weight_graph = 50 + (($data['weight'] - 51) * 4);
		} else if ($data['weight'] > 55) {
			$weight_graph = 67 + (($data['weight'] - 55) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 155 && $member['mb_height'] < 160) {
		if ($data['weight'] < 49) {
			$weight_graph = 34 - (49 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 49 && $data['weight'] < 54) {
			$weight_graph = 50 - ((53 - $data['weight']) * 4);
		} else if ($data['weight'] >= 54 && $data['weight'] <= 58) {
			$weight_graph = 50 + (($data['weight'] - 54) * 4);
		} else if ($data['weight'] > 58) {
			$weight_graph = 67 + (($data['weight'] - 58) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 160 && $member['mb_height'] < 165) {
		if ($data['weight'] < 53) {
			$weight_graph = 34 - (53 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 53 && $data['weight'] < 58) {
			$weight_graph = 50 - ((57 - $data['weight']) * 4);
		} else if ($data['weight'] >= 58 && $data['weight'] <= 64) {
			$weight_graph = 50 + (($data['weight'] - 58) * 4);
		} else if ($data['weight'] > 64) {
			$weight_graph = 67 + (($data['weight'] - 64) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 165 && $member['mb_height'] < 170) {
		if ($data['weight'] < 56) {
			$weight_graph = 34 - (56 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 56 && $data['weight'] < 61) {
			$weight_graph = 50 - ((60 - $data['weight']) * 4);
		} else if ($data['weight'] >= 61 && $data['weight'] <= 65) {
			$weight_graph = 50 + (($data['weight'] - 61) * 4);
		} else if ($data['weight'] > 65) {
			$weight_graph = 67 + (($data['weight'] - 65) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 170 && $member['mb_height'] < 175) {
		if ($data['weight'] < 60) {
			$weight_graph = 34 - (60 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 60 && $data['weight'] < 65) {
			$weight_graph = 50 - ((64 - $data['weight']) * 4);
		} else if ($data['weight'] >= 65 && $data['weight'] <= 69) {
			$weight_graph = 50 + (($data['weight'] - 65) * 4);
		} else if ($data['weight'] > 69) {
			$weight_graph = 67 + (($data['weight'] - 69) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 175 && $member['mb_height'] < 180) {
		if ($data['weight'] < 64) {
			$weight_graph = 34 - (64 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 64 && $data['weight'] < 69) {
			$weight_graph = 50 - ((68 - $data['weight']) * 4);
		} else if ($data['weight'] >= 69 && $data['weight'] <= 73) {
			$weight_graph = 50 + (($data['weight'] - 69) * 4);
		} else if ($data['weight'] > 73) {
			$weight_graph = 67 + (($data['weight'] - 73) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}

	} else if ($member['mb_height'] >= 180) {
		if ($data['weight'] < 69) {
			$weight_graph = 34 - (69 - $data['weight']);
			if ($weight_graph < 0) {
				$weight_graph = 0;
			}
		} else if ($data['weight'] >= 69 && $data['weight'] < 74) {
			$weight_graph = 50 - ((73 - $data['weight']) * 4);
		} else if ($data['weight'] >= 74 && $data['weight'] <= 78) {
			$weight_graph = 50 + (($data['weight'] - 74) * 4);
		} else if ($data['weight'] > 78) {
			$weight_graph = 67 + (($data['weight'] - 78) / 2);
			if ($weight_graph >= 100) {
				$weight_graph = 100;
			}
		}
		
	}

}
$data['weight_graph'] = $weight_graph;

//BMI범위
if ($data['BMI'] < 18.5) {
	$bmi_graph = 25 - ((18.5 - $data['BMI']) * 2.5);
	if ($bmi_graph < 0) {
		$bmi_graph = 0;
	}
} else if ($data['BMI'] >= 18.5 && $data['BMI'] < 20.7) {
	$bmi_graph = 37.5 - ((20.7 - $data['BMI']) * 10);
} else if ($data['BMI'] >= 20.7 && $data['BMI'] < 23) {
	$bmi_graph = 37.5 + (($data['BMI'] - 20.7) * 10);
} else if ($data['BMI'] >= 23 && $data['BMI'] < 24) {
	$bmi_graph = 62.5 - ((23.9 - $data['BMI']) * 28);
} else if ($data['BMI'] >= 24 && $data['BMI'] < 25) {
	$bmi_graph = 62.5 + (($data['BMI'] - 24) * 28);
} else if ($data['BMI'] >= 25) {
	$bmi_graph = 75 + (($data['BMI'] - 25) * 2.5);
	if ($bmi_graph >= 100) {
		$bmi_graph = 100;
	}
}
$data['bmi_graph'] = $bmi_graph;

//body_fat범위
$age = cal_age($member['mb_birth']);
if ($member['mb_sex'] == 1) {
	if ($age < 40) {
		if ($data['body_fat_rate'] < 8) {
			$body_fat_graph = 25 - ((7.9 - $data['body_fat_rate']) * 3);
			if ($body_fat_graph < 0) {
				$body_fat_graph = 0;
			}
		} else if ($data['body_fat_rate'] >= 8 && $data['body_fat_rate'] < 14) {
			$body_fat_graph = 37.5 - ((13.9 - $data['body_fat_rate']) * 2.1);
		} else if ($data['body_fat_rate'] >= 14 && $data['body_fat_rate'] < 20) {
			$body_fat_graph = 37.5 + (($data['body_fat_rate'] - 14) * 2.1);
		} else if ($data['body_fat_rate'] >= 20 && $data['body_fat_rate'] < 23) {
			$body_fat_graph = 62.5 - ((22.9 - $data['body_fat_rate']) * 4.3);
		} else if ($data['body_fat_rate'] >= 23 && $data['body_fat_rate'] < 26) {
			$body_fat_graph = 62.5 + (($data['body_fat_rate'] - 23) * 4.3);
		} else if ($data['body_fat_rate'] >= 26) {
			$body_fat_graph = 75 + (($data['body_fat_rate'] - 26) * 3);
			if ($body_fat_graph >= 100) {
				$body_fat_graph = 100;
			}
		}

	} else if ($age >= 40 && $age < 60) {
		if ($data['body_fat_rate'] < 11) {
			$body_fat_graph = 25 - ((10.9 - $data['body_fat_rate']) * 3);
			if ($body_fat_graph < 0) {
				$body_fat_graph = 0;
			}
		} else if ($data['body_fat_rate'] >= 11 && $data['body_fat_rate'] < 16.5) {
			$body_fat_graph = 37.5 - ((16.4 - $data['body_fat_rate']) * 2.3);
		} else if ($data['body_fat_rate'] >= 16.5 && $data['body_fat_rate'] < 22) {
			$body_fat_graph = 37.5 + (($data['body_fat_rate'] - 16.5) * 2.3);
		} else if ($data['body_fat_rate'] >= 22 && $data['body_fat_rate'] < 25.5) {
			$body_fat_graph = 62.5 - ((25.4 - $data['body_fat_rate']) * 3.6);
		} else if ($data['body_fat_rate'] >= 25.5 && $data['body_fat_rate'] < 29) {
			$body_fat_graph = 62.5 + (($data['body_fat_rate'] - 25.5) * 3.6);
		} else if ($data['body_fat_rate'] >= 29) {
			$body_fat_graph = 75 + (($data['body_fat_rate'] - 29) * 3);
			if ($body_fat_graph >= 100) {
				$body_fat_graph = 100;
			}
		}

	} else if ($age >= 60) {
		if ($data['body_fat_rate'] < 13) {
			$body_fat_graph = 25 - ((12.9 - $data['body_fat_rate']) * 2);
			if ($body_fat_graph < 0) {
				$body_fat_graph = 0;
			}
		} else if ($data['body_fat_rate'] >= 13 && $data['body_fat_rate'] < 19) {
			$body_fat_graph = 37.5 - ((18.9 - $data['body_fat_rate']) * 2.1);
		} else if ($data['body_fat_rate'] >= 19 && $data['body_fat_rate'] < 25) {
			$body_fat_graph = 37.5 + (($data['body_fat_rate'] - 19) * 2.1);
		} else if ($data['body_fat_rate'] >= 25 && $data['body_fat_rate'] < 28) {
			$body_fat_graph = 62.5 - ((27.9 - $data['body_fat_rate']) * 4.3);
		} else if ($data['body_fat_rate'] >= 28 && $data['body_fat_rate'] < 31) {
			$body_fat_graph = 62.5 + (($data['body_fat_rate'] - 28) * 4.3);
		} else if ($data['body_fat_rate'] >= 31) {
			$body_fat_graph = 75 + (($data['body_fat_rate'] - 31) * 2);
			if ($body_fat_graph >= 100) {
				$body_fat_graph = 100;
			}
		}

	}
} else if ($member['mb_sex'] == 2) {
	if ($age < 40) {
		if ($data['body_fat_rate'] < 20) {
			$body_fat_graph = 25 - ((19.9 - $data['body_fat_rate']) * 2.2);
			if ($body_fat_graph < 0) {
				$body_fat_graph = 0;
			}
		} else if ($data['body_fat_rate'] >= 20 && $data['body_fat_rate'] < 26.5) {
			$body_fat_graph = 37.5 - ((26.4 - $data['body_fat_rate']) * 1.95);
		} else if ($data['body_fat_rate'] >= 26.5 && $data['body_fat_rate'] < 33) {
			$body_fat_graph = 37.5 + (($data['body_fat_rate'] - 26.5) * 1.95);
		} else if ($data['body_fat_rate'] >= 33 && $data['body_fat_rate'] < 36.5) {
			$body_fat_graph = 62.5 - ((36.4 - $data['body_fat_rate']) * 3.6);
		} else if ($data['body_fat_rate'] >= 36.5 && $data['body_fat_rate'] < 40) {
			$body_fat_graph = 62.5 + (($data['body_fat_rate'] - 36.5) * 3.6);
		} else if ($data['body_fat_rate'] >= 40) {
			$body_fat_graph = 75 + (($data['body_fat_rate'] - 40) * 2.2);
			if ($body_fat_graph >= 100) {
				$body_fat_graph = 100;
			}
		}

	} else if ($age >= 40 && $age < 60) {
		if ($data['body_fat_rate'] < 22) {
			$body_fat_graph = 25 - ((21.9 - $data['body_fat_rate']) * 2.1);
			if ($body_fat_graph < 0) {
				$body_fat_graph = 0;
			}
		} else if ($data['body_fat_rate'] >= 22 && $data['body_fat_rate'] < 28) {
			$body_fat_graph = 37.5 - ((27.9 - $data['body_fat_rate']) * 2.1);
		} else if ($data['body_fat_rate'] >= 28 && $data['body_fat_rate'] < 34) {
			$body_fat_graph = 37.5 + (($data['body_fat_rate'] - 28) * 2.1);
		} else if ($data['body_fat_rate'] >= 34 && $data['body_fat_rate'] < 37.5) {
			$body_fat_graph = 62.5 - ((37.4 - $data['body_fat_rate']) * 3.6);
		} else if ($data['body_fat_rate'] >= 37.5 && $data['body_fat_rate'] < 41) {
			$body_fat_graph = 62.5 + (($data['body_fat_rate'] - 37.5) * 3.6);
		} else if ($data['body_fat_rate'] >= 41) {
			$body_fat_graph = 75 + (($data['body_fat_rate'] - 41) * 2.1);
			if ($body_fat_graph >= 100) {
				$body_fat_graph = 100;
			}
		}

	} else if ($age >= 60) {
		if ($data['body_fat_rate'] < 23) {
			$body_fat_graph = 25 - ((22.9 - $data['body_fat_rate']) * 2);
			if ($body_fat_graph < 0) {
				$body_fat_graph = 0;
			}
		} else if ($data['body_fat_rate'] >= 23 && $data['body_fat_rate'] < 29.5) {
			$body_fat_graph = 37.5 - ((29.4 - $data['body_fat_rate']) * 1.95);
		} else if ($data['body_fat_rate'] >= 29.5 && $data['body_fat_rate'] < 36) {
			$body_fat_graph = 37.5 + (($data['body_fat_rate'] - 29.5) * 1.95);
		} else if ($data['body_fat_rate'] >= 36 && $data['body_fat_rate'] < 39.5) {
			$body_fat_graph = 62.5 - ((39.4 - $data['body_fat_rate']) * 3.6);
		} else if ($data['body_fat_rate'] >= 39.5 && $data['body_fat_rate'] < 43) {
			$body_fat_graph = 62.5 + (($data['body_fat_rate'] - 39.5) * 3.6);
		} else if ($data['body_fat_rate'] >= 43) {
			$body_fat_graph = 75 + (($data['body_fat_rate'] - 43) * 2);
			if ($body_fat_graph >= 100) {
				$body_fat_graph = 100;
			}
		}

	}
}
$data['body_fat_graph'] = $body_fat_graph;

//내장지방 visceral_fat
if ($data['visceral_fat'] < 9) {
	$visceral_fat_graph = 34 - ((8.9 - $data['visceral_fat']) * 3.6);
	if ($visceral_fat_graph < 0) {
		$visceral_fat_graph = 0;
	}
} else if ($data['visceral_fat'] >= 9 && $data['visceral_fat'] < 12) {
	$visceral_fat_graph = 50 - ((11.9 - $data['visceral_fat']) * 5);
} else if ($data['visceral_fat'] >= 12 && $data['visceral_fat'] < 15) {
	$visceral_fat_graph = 50 + (($data['visceral_fat'] - 12) * 5);
} else if ($data['visceral_fat'] >= 15) {
	$visceral_fat_graph = 67 + (($data['visceral_fat'] - 15) * 3.6);
	if ($visceral_fat_graph >= 100) {
		$visceral_fat_graph = 100;
	}
}
$data['visceral_fat_graph'] = $visceral_fat_graph;

//체수분률 body_water_rate
if ($age < 18) {
	if ($data['body_water_rate'] < 70) {
		$body_water_graph = 34 - ((69.9 - $data['body_water_rate']) / 2);
		if ($body_water_graph < 0) {
			$body_water_graph = 0;
		}
	} else if ($data['body_water_rate'] >= 70 && $data['body_water_rate'] < 74) {
		$body_water_graph = 50 - ((73.9 - $data['body_water_rate']) * 3.8);
	} else if ($data['body_water_rate'] >= 74 && $data['body_water_rate'] < 78) {
		$body_water_graph = 50 + (($data['body_water_rate'] - 74) * 3.8);
	} else if ($data['body_water_rate'] >= 78) {
		$body_water_graph = 67 + (($data['body_water_rate'] - 78) * 1.5);
		if ($body_water_graph >= 100) {
			$body_water_graph = 100;
		}
	}
} else if ($age >= 18 && $age < 65) {
	if ($member['mb_sex'] == 1) {
		if ($data['body_water_rate'] < 60) {
			$body_water_graph = 34 - ((59.9 - $data['body_water_rate']) / 1.7);
			if ($body_water_graph < 0) {
				$body_water_graph = 0;
			}
		} else if ($data['body_water_rate'] >= 60 && $data['body_water_rate'] < 61.5) {
			$body_water_graph = 50 - ((61.4 - $data['body_water_rate']) * 11);
		} else if ($data['body_water_rate'] >= 61.5 && $data['body_water_rate'] < 63) {
			$body_water_graph = 50 + (($data['body_water_rate'] - 61.5) * 11);
		} else if ($data['body_water_rate'] >= 78) {
			$body_water_graph = 67 + (($data['body_water_rate'] - 78) * 1.5);
			if ($body_water_graph >= 100) {
				$body_water_graph = 100;
			}
		}

	} else if ($member['mb_sex'] == 2) {
		if ($data['body_water_rate'] < 55) {
			$body_water_graph = 34 - ((54.9 - $data['body_water_rate']) / 1.7);
			if ($body_water_graph < 0) {
				$body_water_graph = 0;
			}
		} else if ($data['body_water_rate'] >= 55 && $data['body_water_rate'] < 57) {
			$body_water_graph = 50 - ((56.9 - $data['body_water_rate']) * 8.2);
		} else if ($data['body_water_rate'] >= 57 && $data['body_water_rate'] < 59) {
			$body_water_graph = 50 + (($data['body_water_rate'] - 57) * 8.2);
		} else if ($data['body_water_rate'] >= 59) {
			$body_water_graph = 67 + (($data['body_water_rate'] - 59) / 1.2);
			if ($body_water_graph >= 100) {
				$body_water_graph = 100;
			}
		}

	}
} else if ($age >= 65) {
	if ($data['body_water_rate'] < 45) {
		$body_water_graph = 34 - ((44.9 - $data['body_water_rate']) / 1.3);
		if ($body_water_graph < 0) {
			$body_water_graph = 0;
		}
	} else if ($data['body_water_rate'] >= 45 && $data['body_water_rate'] < 45.5) {
		$body_water_graph = 50 - ((45.4 - $data['body_water_rate']) * 40);
	} else if ($data['body_water_rate'] >= 45.5 && $data['body_water_rate'] < 46) {
		$body_water_graph = 50 + (($data['body_water_rate'] - 45.5) * 40);
	} else if ($data['body_water_rate'] >= 46) {
		$body_water_graph = 67 + (($data['body_water_rate'] - 46) / 1.2);
		if ($body_water_graph >= 100) {
			$body_water_graph = 100;
		}
	}

}
$data['body_water_graph'] = $body_water_graph;

//근육량 muscle_mass
if ($member['mb_sex'] == 1) {
	if ($data['muscle_mass'] < 40) {
		$muscle_mass_graph = 50 - ((39.9 - $data['muscle_mass']) * 1.3);
		if ($muscle_mass_graph < 0) {
			$muscle_mass_graph = 0;
		}
	} else if ($data['muscle_mass'] >= 40) {
		$muscle_mass_graph = 50 + (($data['muscle_mass'] - 40) / 1.2);
		if ($muscle_mass_graph >= 100) {
			$muscle_mass_graph = 100;
		}
	}
} else if ($member['mb_sex'] == 2) {
	if ($data['muscle_mass'] < 34) {
		$muscle_mass_graph = 50 - ((33.9 - $data['muscle_mass']) * 1.5);
		if ($muscle_mass_graph < 0) {
			$muscle_mass_graph = 0;
		}
	} else if ($data['muscle_mass'] >= 34) {
		$muscle_mass_graph = 50 + (($data['muscle_mass'] - 34) / 1.3);
		if ($muscle_mass_graph >= 100) {
			$muscle_mass_graph = 100;
		}
	}
}
$data['muscle_mass_graph'] = $muscle_mass_graph;

//골질량 bone_mass
if ($member['mb_sex'] == 1) {
	if ($data['weight'] < 60) {
		if ($data['bone_mass'] < 2.4) {
			$bone_mass_graph = 34 - ((2.39 - $data['bone_mass']) * 14.3);
			if ($bone_mass_graph < 0) {
				$bone_mass_graph = 0;
			}
		} else if ($data['bone_mass'] >= 2.4 && $data['bone_mass'] < 2.5) {
			$bone_mass_graph = 50 - ((2.49 - $data['bone_mass']) * 175);
		} else if ($data['bone_mass'] >= 2.5 && $data['bone_mass'] < 2.6) {
			$bone_mass_graph = 50 + (($data['bone_mass'] - 2.5) * 175);
		} else if ($data['bone_mass'] >= 2.6) {
			$bone_mass_graph = 67 + (($data['bone_mass'] - 2.6) * 14.3);
			if ($bone_mass_graph >= 100) {
				$bone_mass_graph = 100;
			}
		}
	} else if ($data['weight'] >= 60 && $data['weight'] < 76) {
		if ($data['bone_mass'] < 2.8) {
			$bone_mass_graph = 34 - ((2.79 - $data['bone_mass']) * 12.5);
			if ($bone_mass_graph < 0) {
				$bone_mass_graph = 0;
			}
		} else if ($data['bone_mass'] >= 2.8 && $data['bone_mass'] < 2.9) {
			$bone_mass_graph = 50 - ((2.89 - $data['bone_mass']) * 175);
		} else if ($data['bone_mass'] >= 2.9 && $data['bone_mass'] < 3.0) {
			$bone_mass_graph = 50 + (($data['bone_mass'] - 2.9) * 175);
		} else if ($data['bone_mass'] >= 3.0) {
			$bone_mass_graph = 67 + (($data['bone_mass'] - 3.0) * 12.5);
			if ($bone_mass_graph >= 100) {
				$bone_mass_graph = 100;
			}
		}
	} else if ($data['weight'] >= 76) {
		if ($data['bone_mass'] < 3.1) {
			$bone_mass_graph = 34 - ((3.09 - $data['bone_mass']) * 11.2);
			if ($bone_mass_graph < 0) {
				$bone_mass_graph = 0;
			}
		} else if ($data['bone_mass'] >= 3.1 && $data['bone_mass'] < 3.2) {
			$bone_mass_graph = 50 - ((3.19 - $data['bone_mass']) * 175);
		} else if ($data['bone_mass'] >= 3.2 && $data['bone_mass'] < 3.3) {
			$bone_mass_graph = 50 + (($data['bone_mass'] - 3.2) * 175);
		} else if ($data['bone_mass'] >= 3.3) {
			$bone_mass_graph = 67 + (($data['bone_mass'] - 3.3) * 11.2);
			if ($bone_mass_graph >= 100) {
				$bone_mass_graph = 100;
			}
		}
	}

} else if ($member['mb_sex'] == 2) {
	if ($data['weight'] < 45) {
		if ($data['bone_mass'] < 1.7) {
			$bone_mass_graph = 34 - ((1.69 - $data['bone_mass']) * 20.2);
			if ($bone_mass_graph < 0) {
				$bone_mass_graph = 0;
			}
		} else if ($data['bone_mass'] >= 1.7 && $data['bone_mass'] < 1.8) {
			$bone_mass_graph = 50 - ((1.79 - $data['bone_mass']) * 175);
		} else if ($data['bone_mass'] >= 1.8 && $data['bone_mass'] < 1.9) {
			$bone_mass_graph = 50 + (($data['bone_mass'] - 1.8) * 175);
		} else if ($data['bone_mass'] >= 1.9) {
			$bone_mass_graph = 67 + (($data['bone_mass'] - 1.9) * 20.2);
			if ($bone_mass_graph >= 100) {
				$bone_mass_graph = 100;
			}
		}
	} else if ($data['weight'] >= 45 && $data['weight'] < 61) {
		if ($data['bone_mass'] < 2.1) {
			$bone_mass_graph = 34 - ((2.09 - $data['bone_mass']) * 16.3);
			if ($bone_mass_graph < 0) {
				$bone_mass_graph = 0;
			}
		} else if ($data['bone_mass'] >= 2.1 && $data['bone_mass'] < 2.2) {
			$bone_mass_graph = 50 - ((2.19 - $data['bone_mass']) * 175);
		} else if ($data['bone_mass'] >= 2.2 && $data['bone_mass'] < 2.3) {
			$bone_mass_graph = 50 + (($data['bone_mass'] - 2.2) * 175);
		} else if ($data['bone_mass'] >= 2.3) {
			$bone_mass_graph = 67 + (($data['bone_mass'] - 2.3) * 16.3);
			if ($bone_mass_graph >= 100) {
				$bone_mass_graph = 100;
			}
		}
	} else if ($data['weight'] >= 61) {
		if ($data['bone_mass'] < 2.4) {
			$bone_mass_graph = 34 - ((2.39 - $data['bone_mass']) * 14.3);
			if ($bone_mass_graph < 0) {
				$bone_mass_graph = 0;
			}
		} else if ($data['bone_mass'] >= 2.4 && $data['bone_mass'] < 2.5) {
			$bone_mass_graph = 50 - ((2.49 - $data['bone_mass']) * 175);
		} else if ($data['bone_mass'] >= 2.5 && $data['bone_mass'] < 2.6) {
			$bone_mass_graph = 50 + (($data['bone_mass'] - 2.5) * 175);
		} else if ($data['bone_mass'] >= 2.6) {
			$bone_mass_graph = 67 + (($data['bone_mass'] - 2.6) * 14.3);
			if ($bone_mass_graph >= 100) {
				$bone_mass_graph = 100;
			}
		}
	}

}
$data['bone_mass_graph'] = $bone_mass_graph;

//기초대사량 BMR
if ($member['mb_sex'] == 1) {
	if ($age < 30) {
		if ($data['BMR'] < 1360) {
			$bmr_graph = 34 - ((1359 - $data['BMR']) / 39.5);
			if ($bmr_graph < 0) {
				$bmr_graph = 0;
			}
		} else if ($data['BMR'] >= 1360 && $data['BMR'] < 1728) {
			$bmr_graph = 50 - ((1727 - $data['BMR']) / 22.9);
		} else if ($data['BMR'] >= 1782 && $data['BMR'] < 2150) {
			$bmr_graph = 50 + (($data['BMR'] - 1782) / 22.9);
		} else if ($data['BMR'] >= 2150) {
			$bmr_graph = 67 + (($data['BMR'] - 2150) / 39.5);
			if ($bmr_graph >= 100) {
				$bmr_graph = 100;
			}
		}

	} else if ($age >= 30 && $age < 50) {
		if ($data['BMR'] < 1367) {
			$bmr_graph = 34 - ((1366 - $data['BMR']) / 40.1);
			if ($bmr_graph < 0) {
				$bmr_graph = 0;
			}
		} else if ($data['BMR'] >= 1367 && $data['BMR'] < 1669) {
			$bmr_graph = 50 - ((1668 - $data['BMR']) / 19);
		} else if ($data['BMR'] >= 1669 && $data['BMR'] < 1971) {
			$bmr_graph = 50 + (($data['BMR'] - 1669) / 19);
		} else if ($data['BMR'] >= 1971) {
			$bmr_graph = 67 + (($data['BMR'] - 1971) / 40.1);
			if ($bmr_graph >= 100) {
				$bmr_graph = 100;
			}
		}
	} else if ($age >= 50) {
		if ($data['BMR'] < 1178) {
			$bmr_graph = 34 - ((1177 - $data['BMR']) / 34.6);
			if ($bmr_graph < 0) {
				$bmr_graph = 0;
			}
		} else if ($data['BMR'] >= 1178 && $data['BMR'] < 1493) {
			$bmr_graph = 50 - ((1492 - $data['BMR']) / 19.6);
		} else if ($data['BMR'] >= 1493 && $data['BMR'] < 1808) {
			$bmr_graph = 50 + (($data['BMR'] - 1493) / 19.6);
		} else if ($data['BMR'] >= 1808) {
			$bmr_graph = 67 + (($data['BMR'] - 1808) / 34.6);
			if ($bmr_graph >= 100) {
				$bmr_graph = 100;
			}
		}
	}
} else if ($member['mb_sex'] == 2) {
	if ($age < 30) {
		if ($data['BMR'] < 1078) {
			$bmr_graph = 34 - ((1077 - $data['BMR']) / 31.6);
			if ($bmr_graph < 0) {
				$bmr_graph = 0;
			}
		} else if ($data['BMR'] >= 1078 && $data['BMR'] < 1311) {
			$bmr_graph = 50 - ((1310 - $data['BMR']) / 14.5);
		} else if ($data['BMR'] >= 1311 && $data['BMR'] < 1544) {
			$bmr_graph = 50 + (($data['BMR'] - 1311) / 14.5);
		} else if ($data['BMR'] >= 1544) {
			$bmr_graph = 67 + (($data['BMR'] - 1544) / 31.6);
			if ($bmr_graph >= 100) {
				$bmr_graph = 100;
			}
		}
	} else if ($age >= 30 && $age < 50) {
		if ($data['BMR'] < 1090) {
			$bmr_graph = 34 - ((1089 - $data['BMR']) / 32);
			if ($bmr_graph < 0) {
				$bmr_graph = 0;
			}
		} else if ($data['BMR'] >= 1090 && $data['BMR'] < 1315.5) {
			$bmr_graph = 50 - ((1315.4 - $data['BMR']) / 14.1);
		} else if ($data['BMR'] >= 1315.5 && $data['BMR'] < 1541) {
			$bmr_graph = 50 + (($data['BMR'] - 1315.5) / 14.1);
		} else if ($data['BMR'] >= 1541) {
			$bmr_graph = 67 + (($data['BMR'] - 1541) / 32);
			if ($bmr_graph >= 100) {
				$bmr_graph = 100;
			}
		}
	} else if ($age >= 50) {
		if ($data['BMR'] < 1024) {
			$bmr_graph = 34 - ((1023 - $data['BMR']) / 30);
			if ($bmr_graph < 0) {
				$bmr_graph = 0;
			}
		} else if ($data['BMR'] >= 1024 && $data['BMR'] < 1252) {
			$bmr_graph = 50 - ((1251 - $data['BMR']) / 14.2);
		} else if ($data['BMR'] >= 1252 && $data['BMR'] < 1480) {
			$bmr_graph = 50 + (($data['BMR'] - 1252) / 14.2);
		} else if ($data['BMR'] >= 1480) {
			$bmr_graph = 67 + (($data['BMR'] - 1480) / 30);
			if ($bmr_graph >= 100) {
				$bmr_graph = 100;
			}
		}
	}
}
$data['bmr_graph'] = $bmr_graph;

//신체나이 metabolic_age
if ($age >= $data['metabolic_age']) {
	$metabolic_graph = 50 - (($age - $data['metabolic_age']) * 3);
	if ($metabolic_graph < 0) {
		$metabolic_graph = 0;
	}
} else if ($age < $data['metabolic_age']) {
	$metabolic_graph = 50 + (($data['metabolic_age'] - $age) * 3);
	if ($metabolic_graph >= 100) {
		$metabolic_graph = 100;
	}
}
$data['metabolic_graph'] = $metabolic_graph;




$tpl->assign('data', $data);


include "../inc/_head.php";
$tpl->print_('body');
include "../inc/_tail.php";
?>