<?php
/**
 * Short description.
 *
 * @author  CU
 * @version 1.0
 * @package main
 */

function print_r_text($_arr){
	if (php_sapi_name() == 'cli'){
		print_r($_arr);
	}else{
		echo '
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" />
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
		';
		echo "<div style='display:block;background-color:#fff;width:90%;margin:10px;'><textarea style='min-width:350px;min-height:100px;resize:auto;display:block;margin:7px;padding:5px;font-family:tahoma;font-size:11px;line-height:130%;letter-spacing:0;'>";
		print_r($_arr);
		echo "</textarea></div>";
	}
}
echo "hash 지원 알고리즘";
print_r_text(hash_algos());

//시그니처 테스트 P_MKEY 용

echo "sha256 해시테스트";
$_hash_data = 'TEST';
$_signKey = 'TEST';
$_signature = hash_hmac('SHA256', $_hash_data, $_signKey );

print_r_text($_signature);

?>