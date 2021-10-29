<?php

// 返回json格式的数据
header("Content-type:application/json");

// 开启session，判断登陆状态
session_start();
if(isset($_SESSION["lkydwz.admin"])){

	// 数据库配置
	include '../dbconfig/db.php';
	include './sqlfzr.php';

	// 创建连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	// 获得表单POST过来的数据
	$dwz_title = sqlfzr(trim($_REQUEST["dwz_title"]));
	$dwz_reditype = sqlfzr(trim($_REQUEST["dwz_reditype"]));
	$dwz_type = sqlfzr(trim($_REQUEST["dwz_type"]));
	$dwz_keynum = sqlfzr(trim($_REQUEST["dwz_keynum"]));
	$dwz_url = trim($_REQUEST["dwz_url"]);
	$api_key = sqlfzr(trim($_REQUEST["api_key"]));

	// 获取有效期的参数
	if ($_REQUEST["dwz_yxq"] == 'cus') {
		date_default_timezone_set("Asia/Shanghai");
		$dwz_yxq = sqlfzr(trim($_REQUEST["dwz_zdyyxq"]));
		$dwz_yxq_date = date('Y-m-d',strtotime("+".$dwz_yxq." day"));
	}else if($_REQUEST["dwz_yxq"] !== 'cus' && $_REQUEST["dwz_yxq"] !== 'ever') {
		date_default_timezone_set("Asia/Shanghai");
		$dwz_yxq = sqlfzr(trim($_REQUEST["dwz_yxq"]));
		$dwz_yxq_date = date('Y-m-d',strtotime("+".$dwz_yxq." day"));
	}else{
		$dwz_yxq_date = 'ever';
	}

	// 创建短网址id和Key
	$dwz_id = rand(10000,99999);
	$dwz_key = CreatKey($dwz_keynum);

	// 验证apiKey是否为空
	if ($api_key == '' || empty($api_key) || !isset($api_key) || $api_key == null) {
		$result = array(
			"code" => "108",
			"msg" => "ApiKey参数为空"
		);
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
		exit;
	}

	// 验证apiKey授权状态
	$sql_apikey = "SELECT * FROM dwz_api WHERE api_key = '$api_key'";
	$res_apikey = $conn->query($sql_apikey);
	if ($res_apikey->num_rows == '0' && $api_key !== 'local') {
		$result = array(
			"code" => "105",
			"msg" => "ApiKey未授权"
		);
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
		exit;
	}

	// 验证apiKey启用状态
	$sql_apikey_status = "SELECT * FROM dwz_api WHERE api_key = '$api_key'";
	$res_apikey_status = $conn->query($sql_apikey_status);
	if ($res_apikey_status->num_rows > 0 && $api_key !== 'local') {
		while($row_apikey_status = $res_apikey_status->fetch_assoc()) {
			$api_status = $row_apikey_status['api_status'];
		}
		if ($api_status !== '1') {
			$result = array(
				"code" => "104",
				"msg" => "ApiKey已被停用"
			);
			echo json_encode($result,JSON_UNESCAPED_UNICODE);
			exit;
		}
	}

	// 验证apiKey有效期
	$sql_apikey_yxq = "SELECT * FROM dwz_api WHERE api_key = '$api_key'";
	$res_apikey_yxq = $conn->query($sql_apikey_yxq);
	if ($res_apikey_yxq->num_rows > 0 && $api_key !== 'local') {
		while($row_apikey_yxq = $res_apikey_yxq->fetch_assoc()) {
			$api_yxq = $row_apikey_yxq['api_yxq'];
		}
		date_default_timezone_set("Asia/Shanghai");
		$thisdate = date("Y-m-d");
		if (strtotime($thisdate)>strtotime($api_yxq) && !empty($api_yxq)) {
			$result = array(
				"code" => "112",
				"msg" => "你的ApiKey已过期"
			);
			echo json_encode($result,JSON_UNESCAPED_UNICODE);
			exit;
		}
	}

	// 验证白名单ip
	$sql_apikey_ip = "SELECT * FROM dwz_api WHERE api_key = '$api_key'";
	$res_apikey_ip = $conn->query($sql_apikey_ip);
	if ($res_apikey_ip->num_rows > 0) {
		while($row_apikey_ip = $res_apikey_ip->fetch_assoc()) {
			$api_ip = $row_apikey_ip['api_ip'];
			if ($api_ip == '' || $api_ip == null || empty($api_ip) || !isset($api_ip)) {
				$api_ip = "不限";
			}
		}
		if(get_server_ip() !== $api_ip && $api_ip !== "不限"){
			$result = array(
				"code" => "104",
				"msg" => "服务器IP不在白名单中"
			);
			echo json_encode($result,JSON_UNESCAPED_UNICODE);
			exit;
		}
	}

	// 验证入口域名
	$sql_rkym = "SELECT * FROM dwz_ym WHERE ym_type = '1'";
	$res_rkym = $conn->query($sql_rkym);
	if ($res_rkym->num_rows == '0') {
		$result = array(
			"code" => "106",
			"msg" => "未设置入口域名"
		);
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
		exit;
	}

	// 验证防封域名
	if ($dwz_reditype == '2') {
		$sql_ffym = "SELECT * FROM dwz_ym WHERE ym_type = '2'";
		$res_ffym = $conn->query($sql_ffym);
		if ($res_ffym->num_rows == '0') {
			$result = array(
				"code" => "107",
				"msg" => "未设置防封域名"
			);
			echo json_encode($result,JSON_UNESCAPED_UNICODE);
			exit;
		}
	}

	// 获取入口域名
	$sql_rkym = "SELECT * FROM dwz_ym WHERE ym_type = '1'";
	$res_rkym = $conn->query($sql_rkym);
	if ($res_rkym->num_rows > 0) {
		while($row_rkym = $res_rkym->fetch_assoc()) {
			$dwz_rkym = $row_rkym["ym"];
		}
	}

	// 获取防封域名
	if ($dwz_reditype == '2') {
		$sql_ffym = "SELECT * FROM dwz_ym WHERE ym_type = '2'";
		$res_ffym = $conn->query($sql_ffym);
		if ($res_ffym->num_rows > 0) {
			while($row_ffym = $res_ffym->fetch_assoc()) {
				$dwz_ffym = $row_ffym["ym"];
			}
		}
	}

	// 验证表单
	if(empty($dwz_title)){
		$result = array(
			"code" => "101",
			"msg" => "标题不得为空"
		);
	}else if(empty($dwz_yxq_date)){
		$result = array(
			"code" => "102",
			"msg" => "请输入有效期"
		);
	}else if(empty($dwz_url)){
		$result = array(
			"code" => "103",
			"msg" => "请粘贴长链接"
		);
	}else{
		
		// 插入数据库
		$sql_creat_dwz = "INSERT INTO dwz_list (dwz_id,dwz_key,dwz_url,dwz_title,dwz_type,dwz_yxq,dwz_reditype,dwz_rkym,dwz_ffym) VALUES ('$dwz_id','$dwz_key','$dwz_url','$dwz_title','$dwz_type','$dwz_yxq_date','$dwz_reditype','$dwz_rkym','$dwz_ffym')";

		// 判断创建结果
		if ($conn->query($sql_creat_dwz) === TRUE) {
			$result = array(
				"code" => "100",
				"msg" => "创建成功"
			);

			// 更新请求次数
			if ($api_key !== 'local') {
				mysqli_query($conn,"UPDATE dwz_tongji SET dwz_api_qq_num=dwz_api_qq_num+1");
			}

		}else{
			$result = array(
				"code" => "105",
				"msg" => "创建失败，数据库发生错误"
			);
		}
		
		// 断开数据库连接
		$conn->close();
	}

}else{
	$result = array(
		"code" => "104",
		"msg" => "未登录"
	);
	echo json_encode($result,JSON_UNESCAPED_UNICODE);
	exit;
}

// 获取当前服务器的ip
function get_server_ip() { 
    if (isset($_SERVER)) { 
        if($_SERVER['SERVER_ADDR']) {
            $server_ip = $_SERVER['SERVER_ADDR']; 
        } else { 
            $server_ip = $_SERVER['LOCAL_ADDR']; 
        } 
    } else { 
        $server_ip = getenv('SERVER_ADDR');
    } 
    return $server_ip; 
}

// 创建短网址key
function CreatKey($length){
	$keystr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
	$randStr = str_shuffle($keystr);//打乱字符串
	$rands= substr($randStr,0,$length);
	return $rands;
}

echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>