<?php
// 设置页面返回的字符编码为json格式
header("Content-type:application/json");

// 开启session，验证登录状态
session_start();
if(isset($_SESSION["lkydwz.admin"])){

	// 数据库配置
	include '../dbconfig/db.php';
	include '../creat/sqlfzr.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	// 获取id
	$api_id = sqlfzr(trim($_POST["api_id"]));
	
	// 安全拦截
	if (!isset($_POST['api_token']) || empty($_POST['api_token'])) {
		$result = array(
			"code" => "104",
			"msg" => "禁止操作"
		);
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
		exit;
	}

	// 获得表单POST过来的数据
	$api_user = sqlfzr(trim($_POST["api_user"]));
	$api_yxq = trim($_POST["api_yxq"]);
	$api_ip = trim($_POST["api_ip"]);
	$api_key = sqlfzr(trim($_POST["api_key"]));
	$api_status = sqlfzr(trim($_POST["api_status"]));

	if(empty($api_user)){
		$result = array(
			"code" => "101",
			"msg" => "请输入用户备注"
		);
	}else if(empty($api_key)){
		$result = array(
			"code" => "102",
			"msg" => "请生成KEY"
		);
	}else{

		// 设置字符编码为utf-8
		mysqli_query($conn, "SET NAMES UTF-8");

		// 更新数据库
		mysqli_query($conn,"UPDATE dwz_api SET api_user='$api_user',api_yxq='$api_yxq',api_ip='$api_ip',api_key='$api_key',api_status='$api_status' WHERE api_id=".$api_id);

		$result = array(
			"code" => "100",
			"msg" => "更新成功"
		);
	}
}else{
	$result = array(
		"code" => "106",
		"msg" => "未登录"
	);
}

// 输出JSON格式的数据
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>