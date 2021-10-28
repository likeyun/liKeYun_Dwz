<?php
header("Content-type:application/json");
session_start();
if(isset($_SESSION["lkydwz.admin"])){

	// 数据库配置
	include '../dbconfig/db.php';
	include '../creat/sqlfzr.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	// 获取id
	$dwz_id = sqlfzr(trim($_GET["dwzid"]));

	if(empty($dwz_id)){
		$result = array(
			"code" => 101,
			"msg" => "非法请求"
		);
	}else{

		// 获取域名和key
		$sql_yuming = "SELECT dwz_rkym,dwz_key FROM dwz_list WHERE dwz_id = '$dwz_id'";
		$result_yuming = $conn->query($sql_yuming);
		if ($result_yuming->num_rows > 0) {
			while($row_yuming = $result_yuming->fetch_assoc()) {
				$dwz_rkym = $row_yuming["dwz_rkym"]; // 域名
				$dwz_key = $row_yuming["dwz_key"]; // key

				// 返回结果
				$result = array(
					"code" => 100,
					"msg" => "分享成功",
					"url" => $dwz_rkym.'/'.$dwz_key
				);
			}
		}else{
			$result = array(
				"code" => 103,
				"msg" => "分享发生错误"
			);
		}
	}
}else{
	$result = array(
		"code" => 102,
		"msg" => "未登录"
	);
}
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>