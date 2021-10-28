<?php

// 返回json格式的数据
header("Content-type:application/json");

// 开启session，判断登陆状态
session_start();
if(isset($_SESSION["lkydwz.admin"])){

	// 数据库配置
	include '../dbconfig/db.php';
	include '../creat/sqlfzr.php';

	// 创建连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	// 获得表单POST过来的数据
	$api_ip = trim($_POST["api_ip"]);
	$api_user = sqlfzr(trim($_POST["api_user"]));
	$api_yxq = trim($_POST["api_yxq"]);

	// 过滤表单
	if(empty($api_user)){
		$result = array(
			"code" => "101",
			"msg" => "请输入用户备注"
		);
	}else{

		// 字符编码设为utf8
		mysqli_query($conn, "SET NAMES UTF-8");

		// 生成api_id
		$api_id = rand(10000,99999);

		// 创建Key
		function CreatKey($length){
	    	$keystr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
	    	$randStr = str_shuffle($keystr);//打乱字符串
	    	$rands= substr($randStr,0,$length);
	    	return $rands;
		}
		$api_key = CreatKey(10);

		// 插入数据库
		$sql_addkey = "INSERT INTO dwz_api (api_user,api_id,api_key,api_yxq,api_ip) VALUES ('$api_user','$api_id','$api_key','$api_yxq','$api_ip')";

		// 判断创建结果
		if ($conn->query($sql_addkey) === TRUE) {
			$result = array(
				"code" => "100",
				"msg" => "创建成功"
			);
		}else{
			$result = array(
				"code" => "103",
				"msg" => "创建失败，数据库发生错误"
			);
		}
		
		// 断开数据库连接
		$conn->close();
	}
}else{
	$result = array(
		"code" => "102",
		"msg" => "未登录"
	);
}

// 输出JSON格式的数据
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>