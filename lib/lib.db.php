<?
/*************************************************************************
**
**  SQL 관련 함수 모음
**
*************************************************************************/


// DB 연결
function sql_connect($host, $user, $pass)
{
	//@mysqli_query("set names utf8mb4 ");
    return @mysqli_connect($host, $user, $pass);
}


// DB 선택
function sql_select_db($db, $connect)
{
	//@mysqli_query(" set names utf8mb4 ");
    return @mysqli_select_db($connect, $db);
	@mysqli_query($connect, "set names utf8mb4");
}

// DB2 연결
function sql_connect2($host, $user, $pass)
{
	//@mysqli_query(" set names euc-kr ");
    return @mysqli_connect($host, $user, $pass);
}


// DB2 선택
function sql_select_db2($db, $connect)
{
	//@mysqli_query(" set names euc-kr ");
    return @mysqli_select_db($connect, $db);
	@mysqli_query($connect, "set names euc-kr");
}


function db_1()
{
	global $mysql_host;
	global $mysql_user;
	global $mysql_password;
	global $mysql_db;

	$connect_db = @sql_connect($mysql_host, $mysql_user, $mysql_password);
	$select_db = @sql_select_db($mysql_db, $connect_db);
	if (!$select_db) 
		die("<meta http-equiv='content-type' content='text/html; charset=utf-8'><script language='JavaScript'> alert('DB Connection Error1'); </script>");
	@mysqli_query($connect_db, "set names utf8mb4");
	return $connect_db;
}


function db_2()
{
	global $mysql_host2;
	global $mysql_user2;
	global $mysql_password2;
	global $mysql_db2;

	$connect_db = @sql_connect($mysql_host2, $mysql_user2, $mysql_password2);
	$select_db = @sql_select_db($mysql_db2, $connect_db);
	if (!$select_db) 
		die("<meta http-equiv='content-type' content='text/html; charset=utf-8'><script language='JavaScript'> alert('DB Connection Error'); </script>");
	@mysqli_query($connect_db, "set names utf8");
	//echo "연결완료";
	return $connect_db;
}

function c_db1()
{
	global $mysql_host;
	global $mysql_user;
	global $mysql_password;
	global $mysql_db;
	global $connect_db;

	@mysqli_close($connect_db);
    $connect_db = sql_connect($mysql_host, $mysql_user, $mysql_password);
    $select_db = sql_select_db($mysql_db, $connect_db);
}

function c_db2()
{
	global $mysql_host2;
	global $mysql_user2;
	global $mysql_password2;
	global $mysql_db2;
	global $connect_db;

	@mysqli_close($connect_db);
    $connect_db = sql_connect($mysql_host2, $mysql_user2, $mysql_password2);
    $select_db = sql_select_db($mysql_db2, $connect_db);
    if (!$select_db) c_db1();
}

// mysql_query 와 mysql_error 를 한꺼번에 처리
function sql_query($sql, $error=TRUE)
{
	global $connect_db;

    if ($error)
        $result = @mysqli_query($connect_db, $sql) or die("<p>$sql<p>" . mysqli_errno($connect_db) . " : " .  mysqli_error($connect_db) . "<p>error file : ".$_SERVER['PHP_SELF']);
		//$result = @mysqli_query($connect_db, $sql);
    else
        $result = @mysqli_query($connect_db, $sql);
    return $result;
}

// 결과값에서 총 수를 구한다

function sql_total2($sql)
{
	global $connect_db;
	$result = @mysqli_query($sql);
    $row = @mysqli_affected_rows($connect_db);
    return $row;
}


// 결과값에서 총 수를 구한다 - 더 빠름
function sql_total($sql)
{
	global $connect_db;
	$sql_array = explode(" from ", strtolower($sql));

	if(custom_count($sql_array) > 2){
		$sql_from = "";
		$comma = "";
		for($i=1;$i<custom_count($sql_array);$i++){
			$sql_from .= $comma.$sql_array[$i];
			$comma = " from ";
		}
	}else{
		$sql_from = $sql_array[1];
	}

	$sql_2 = "select count(*) from ".$sql_from;
	$result = @sql_query($sql_2);
	$row = @mysqli_fetch_array($result);

    return $row[0];
}

// 쿼리를 실행한 후 결과값에서 한행을 얻는다.
function sql_fetch($sql, $error=TRUE)
{
	global $connect_db;
    $result = sql_query($sql, $error);
	if($result->num_rows == 0){
		return array();
	}

	if ($error)
	    $row = @sql_fetch_array($result) or die("<p>$sql<p>" . mysqli_errno($connect_db) . " : " .  mysqli_error($connect_db) . "<p>error file : ".$_SERVER['PHP_SELF']);
	else 
		$row = @sql_fetch_array($result);
    return $row;
}


// 결과값에서 한행 연관배열(이름으로)로 얻는다.
function sql_fetch_array($result)
{
    $row = @mysqli_fetch_assoc($result);
    return $row;
}


// $result에 대한 메모리(memory)에 있는 내용을 모두 제거한다.
// sql_free_result()는 결과로부터 얻은 질의 값이 커서 많은 메모리를 사용할 염려가 있을 때 사용된다.
// 단, 결과 값은 스크립트(script) 실행부가 종료되면서 메모리에서 자동적으로 지워진다.
function sql_free_result($result)
{
    return mysqli_free_result($result);
}

function sql_list($sql)
{

	$sql_q = sql_query($sql);
	$sql_list = array();
	while($sql_r = sql_fetch_array($sql_q)){
		$sql_list[]= $sql_r;
	}

	return $sql_list;
}

function sql_password($value)
{
    $row = sql_fetch(" select sha2('$value', 256) as pass ");
    return $row['pass'];
}

function sql_insert_id()
{
	global $connect_db;
	return mysqli_insert_id($connect_db);
}

if(!function_exists("mysql_insert_id")){
	function mysql_insert_id()
	{
		global $connect_db;
		return mysqli_insert_id($connect_db);
	}
}
// DB관련함수 끝
?>