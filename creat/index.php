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
				"msg" => "创建成功",
				"url" => $dwz_rkym.'/'.$dwz_key
			);
			
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

// 创建短网址key
function CreatKey($length){
	$keystr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
	$randStr = str_shuffle($keystr);//打乱字符串
	$rands= substr($randStr,0,$length);
	return $rands;
}

echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>
