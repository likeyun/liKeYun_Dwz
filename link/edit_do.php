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
	$dwz_key = sqlfzr(trim($_POST["dwz_key"]));
	
	// 获取有效期
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
	
    // 如果选择了防封模式，需要验证是否已经配置防封域名
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

	if(empty($dwz_title)){
		$result = array(
			"code" => "101",
			"msg" => "请输入标题"
		);
	}else if(empty($dwz_key)){
		$result = array(
			"code" => "103",
			"msg" => "请设置自定义参数"
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
		
		// 过滤自定义key
        if (preg_match("/[\x7f-\xff]/", $dwz_key)) { 
            $result = array(
    			"code" => "107",
    			"msg" => "自定义参数不能包含中文"
    		);
    		echo json_encode($result,JSON_UNESCAPED_UNICODE);
    		exit;
        }

		// 更新数据库
		mysqli_query($conn,"UPDATE dwz_list SET dwz_title='$dwz_title',dwz_key='$dwz_key',dwz_url='$dwz_url',dwz_type='$dwz_type',dwz_reditype='$dwz_reditype',dwz_yxq='$dwz_yxq_date',dwz_status='$dwz_status',dwz_ffym='$dwz_ffym' WHERE dwz_id=".$dwz_id);

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