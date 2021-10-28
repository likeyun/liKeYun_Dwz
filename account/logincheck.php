<?php
	// 返回json格式的数据
	header("Content-type:application/json");

	// 数据库配置
	include '../dbconfig/db.php';
	include '../creat/sqlfzr.php';

	$user = trim($_POST["user"]);
	$pwd = trim($_POST["pwd"]);

	if (empty($user)) {
		$result = array(
			"code" => "101",
			"msg" => "未输入账号"
		);
	}else if(empty($pwd)){
		$result = array(
			"code" => "102",
			"msg" => "未输入密码"
		);
	}else if($user !== $admin_user){
		$result = array(
			"code" => "103",
			"msg" => "账号错误"
		);
	}else if($pwd !== $admin_pwd){
		$result = array(
			"code" => "104",
			"msg" => "密码错误"
		);
	}else if($user !== $admin_user && $pwd !== $admin_pwd){
		$result = array(
			"code" => "105",
			"msg" => "账号和密码错误"
		);
	}else{
		$result = array(
			"code" => "100",
			"msg" => "登录成功"
		);
		session_start();
		$_SESSION['lkydwz.admin'] = $user;
	}

// 输出json格式的数据
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>