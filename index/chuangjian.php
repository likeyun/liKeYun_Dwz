<?php

// 返回json格式的数据
header("Content-type:application/json");

// 数据库配置
include '../dbconfig/db.php';
include '../creat/sqlfzr.php';

// 创建连接
$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

// 创建的默认配置
$dwz_title = '快捷创建';
$dwz_reditype = '1';
$dwz_type = '1';
$dwz_keynum = '5';
$dwz_url = trim($_REQUEST["dwz_url"]);
$api_key = 'kuaijie';
$dwz_yxq = 'ever';

// 创建短网址id和Key
$dwz_id = rand(10000,99999);
$dwz_key = CreatKey($dwz_keynum);

// 验证apiKey是否为空
if ($api_key == '' || empty($api_key) || !isset($api_key) || $api_key == null || $api_key !== 'kuaijie') {
	$result = array(
		"code" => "108",
		"msg" => "ApiKey参数为空"
	);
	echo json_encode($result,JSON_UNESCAPED_UNICODE);
	exit;
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
}else{
	$dwz_ffym = '';
}

// 验证表单
if(empty($dwz_title)){
	$result = array(
		"code" => "101",
		"msg" => "标题不得为空"
	);
}else if(empty($dwz_yxq)){
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
	$sql_creat_dwz = "INSERT INTO dwz_list (dwz_id,dwz_key,dwz_url,dwz_title,dwz_type,dwz_yxq,dwz_reditype,dwz_rkym,dwz_ffym) VALUES ('$dwz_id','$dwz_key','$dwz_url','$dwz_title','$dwz_type','$dwz_yxq','$dwz_reditype','$dwz_rkym','$dwz_ffym')";

	// 判断创建结果
	if ($conn->query($sql_creat_dwz) === TRUE) {
		$result = array(
			"code" => "100",
			"msg" => "创建成功",
			"link" => $dwz_rkym.'/'.$dwz_key
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

// 创建短网址key
function CreatKey($length){
	$keystr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
	$randStr = str_shuffle($keystr);//打乱字符串
	$rands= substr($randStr,0,$length);
	return $rands;
}

echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>