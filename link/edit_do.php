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
	$dwz_id = sqlfzr(trim($_POST["dwz_id"]));
	
	// 安全拦截
	if (!isset($_POST['dwz_token']) || empty($_POST['dwz_token'])) {
		$result = array(
			"code" => "104",
			"msg" => "禁止操作"
		);
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
		exit;
	}

	// 获得表单POST过来的数据
	$dwz_title = sqlfzr(trim($_POST["dwz_title"]));
	$dwz_url = trim($_POST["dwz_url"]);
	$dwz_type = sqlfzr(trim($_POST["dwz_type"]));
	$dwz_reditype = sqlfzr(trim($_POST["dwz_reditype"]));
	$dwz_yxq = sqlfzr(trim($_POST["dwz_yxq"]));
	$dwz_status = sqlfzr(trim($_POST["dwz_status"]));
	if ($_POST["dwz_yxq"] == 'cus') {
		$dwz_yxq = sqlfzr(trim($_POST["dwz_zdyyxq"]));
	}else{
		$dwz_yxq = sqlfzr(trim($_POST["dwz_yxq"]));
	}

	if(empty($dwz_title)){
		$result = array(
			"code" => "101",
			"msg" => "请输入标题"
		);
	}else if(empty($dwz_url)){
		$result = array(
			"code" => "102",
			"msg" => "请粘贴长链接"
		);
	}else if(empty($dwz_yxq)){
		$result = array(
			"code" => "103",
			"msg" => "请输入有效期"
		);
	}else{

		// 设置字符编码为utf-8
		mysqli_query($conn, "SET NAMES UTF-8");

		// 更新数据库
		mysqli_query($conn,"UPDATE dwz_list SET dwz_title='$dwz_title',dwz_url='$dwz_url',dwz_type='$dwz_type',dwz_reditype='$dwz_reditype',dwz_yxq='$dwz_yxq',dwz_status='$dwz_status' WHERE dwz_id=".$dwz_id);

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