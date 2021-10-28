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
	$ym = trim($_POST["ym"]);
	$ym_type = sqlfzr(trim($_POST["ym_type"]));

	function is_url($v){
		$pattern="#(http|https)://(.*\.)?.*\..*#i";
		if(preg_match($pattern,$v)){ 
			return true; 
		}else{ 
			return false; 
		} 
	}

	// 过滤表单
	if(empty($ym)){
		$result = array(
			"code" => "101",
			"msg" => "请输入域名"
		);
	}else if(is_url($ym) == false){
		$result = array(
			"code" => "106",
			"msg" => "你输入的不是域名"
		);
	}else if(substr($ym, -1) == '/'){
		$result = array(
			"code" => "107",
			"msg" => "不能以 / 结束"
		);
	}else{

		// 字符编码设为utf8
		mysqli_query($conn, "SET NAMES UTF-8");

		// 生成域名id
		$ym_id = rand(10000,99999);

		// 验证是否已经存在此类域名
		$sql_checkym = "SELECT * FROM dwz_ym WHERE ym_type = '$ym_type'";
		$result_ym = $conn->query($sql_checkym);
		if ($result_ym->num_rows > 0) {
			if ($ym_type == '1') {
				$result = array(
					"code" => "102",
					"msg" => "入口域名只能添加一个"
				);
			}else if($ym_type == '2'){
				$result = array(
					"code" => "102",
					"msg" => "防封域名只能添加一个"
				);
			}
		}else{
			// 插入数据库
			$sql_addym = "INSERT INTO dwz_ym (ym,ym_id,ym_type) VALUES ('$ym','$ym_id','$ym_type')";

			// 判断创建结果
			if ($conn->query($sql_addym) === TRUE) {
				$result = array(
					"code" => "100",
					"msg" => "添加成功"
				);
			}else{
				$result = array(
					"code" => "105",
					"msg" => "添加失败，数据库发生错误"
				);
			}
		}
		
		// 断开数据库连接
		$conn->close();
	}
}else{
	$result = array(
		"code" => "104",
		"msg" => "未登录"
	);
}

// 输出JSON格式的数据
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>