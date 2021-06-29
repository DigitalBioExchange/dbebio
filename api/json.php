<?php
header("Content-Type: application/json;charset=utf-8");
$arr = urlencode_data($arr);
echo json_encode($arr);
?>