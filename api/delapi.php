<?php
// 字符编码是json
header("Content-type:application/json");

// 验证登录状态
session_start();
if(isset($_SESSION["lkydwz.admin"])){

	// 数据库配置
	include '../dbconfig/db.php';
	include '../creat/sqlfzr.php';
	
	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	// 获得要删除的id
	$api_id = sqlfzr(trim($_GET["apiid"]));

	if(empty($api_id)){
		$result = array(
			"code" => "101",
			"msg" => "非法请求"
		);
	}else{
		// 删除活码数据
		mysqli_query($conn,"DELETE FROM dwz_api WHERE api_id=".$api_id);
		
		// 返回结果
		$result = array(
			"code" => "100",
			"msg" => "已删除"
		);
	}
}else{
	$result = array(
		"code" => "102",
		"msg" => "未登录"
	);
}

// 输出json格式的数据
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>