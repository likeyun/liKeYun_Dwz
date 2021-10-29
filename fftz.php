<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
	<link rel="shortcut icon" href="../images/fvicon.png" type="image/x-icon"/>
	<style type="text/css">
		*{
			margin:0;
			padding:0;
		}
		#topyd{
			width: 100%;
			margin:0 auto;
			position: fixed;
			top: 0;
		}
		#topyd img{
			max-width: 100%;
		}
		.znzllqdk{
			text-align: center;
			margin-top: 230px;
			font-size: 20px;
			color: #3464e0;
		}
	</style>
</head>
<?php
header("Content-Type:text/html;charset=utf-8");

// 数据库配置
include './dbconfig/db.php';
include './creat/sqlfzr.php';

// 获得当前传过来的KEY
@$key = sqlfzr(trim($_REQUEST["id"]));

// 过滤数据
if (trim(empty($key))) {
	echo '<title>温馨提示</title>';
    echo "请传入参数";
}else{

    // 创建连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	$sql = "SELECT dwz_url,dwz_type FROM dwz_list WHERE dwz_key='$key'";
	$result = $conn->query($sql);
	 
	if ($result->num_rows > 0) {

	    // 输出数据
	    while($row = $result->fetch_assoc()) {

	    	// 长链接
	        $dwz_url = $row["dwz_url"];
	        // 设备限制
	        $dwz_type = $row["dwz_type"];

	        // 判断设备限制
        	if ($dwz_type == '1') {
        		echo '<title>正在跳转</title>';
        		echo '<script>location.href="'.$dwz_url.'";</script>';
        	}else if($dwz_type == '2') {
				if(strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') === false) {
					echo '<title>正在跳转</title>';
					echo '<title>温馨提示</title>';
    				echo "<br/><br/><br/><br/><br/><img src='./images/warning.png' style='width:120px;height:120px;display:block;margin:20px auto;' /><p style='text-align:center;color:#666;'>该页面只能在微信打开</p>";
				}else{
					echo '<script>location.href="'.$dwz_url.'";</script>';
				}
        	}else if($dwz_type == '3') {
        		if(strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') === false) {
        			echo '<title>正在跳转</title>';
					echo '<script>location.href="'.$dwz_url.'";</script>';
				}else{
					echo '<title>温馨提示</title>';
					$agent_iphone_android = strtolower($_SERVER['HTTP_USER_AGENT']);
    				$is_iphone = (strpos($agent_iphone_android, 'iphone')) ? true : false;
    				$is_android = (strpos($agent_iphone_android, 'android')) ? true : false;
    				if ($is_iphone) {
    					echo '<div id="topyd"><img src="./images/ios.jpg"/></div>';
    					echo '<p class="znzllqdk">本页面只能在浏览器打开</p>';
    				}else if($is_android){
    					echo '<div id="topyd"><img src="./images/android.jpg"/></div>';
    					echo '<p class="znzllqdk">本页面只能在浏览器打开</p>';
    				}
				}
        	}else if($dwz_type == '4') {
        		$agent_pc = strtolower($_SERVER['HTTP_USER_AGENT']);
    			$is_pc = (strpos($agent_pc, 'windows nt')) ? true : false;
    			if($is_pc){
    				echo '<title>正在跳转</title>';
    				echo '<script>location.href="'.$dwz_url.'";</script>';
    			}else{
    				echo '<title>温馨提示</title>';
    				echo "<br/><br/><br/><br/><br/><img src='./images/warning.png' style='width:120px;height:120px;display:block;margin:20px auto;' /><p style='text-align:center;color:#666;'>该页面只能在电脑浏览器打开</p>";
    			}
        	}else if($dwz_type == '5') {
        		$agent_android = strtolower($_SERVER['HTTP_USER_AGENT']);
    			$is_android = (strpos($agent_android, 'android')) ? true : false;
    			if($is_android){
    				echo '<title>正在跳转</title>';
    				echo '<script>location.href="'.$dwz_url.'";</script>';
    			}else{
    				echo '<title>温馨提示</title>';
    				echo "<br/><br/><br/><br/><br/><img src='./images/warning.png' style='width:120px;height:120px;display:block;margin:20px auto;' /><p style='text-align:center;color:#666;'>该页面只能在Android设备打开</p>";
    			}
        	}else if($dwz_type == '6') {
        		$agent_iphone = strtolower($_SERVER['HTTP_USER_AGENT']);
    			$is_iphone = (strpos($agent_iphone, 'iphone')) ? true : false;
    			if($is_iphone){
    				echo '<title>正在跳转</title>';
    				echo '<script>location.href="'.$dwz_url.'";</script>';
    			}else{
    				echo '<title>温馨提示</title>';
    				echo "<br/><br/><br/><br/><br/><img src='./images/warning.png' style='width:120px;height:120px;display:block;margin:20px auto;' /><p style='text-align:center;color:#666;'>该页面只能在iPhone打开</p>";
    			}
        	}
	    }
	} else {
		echo '<title>温馨提示</title>';
	    echo "<br/><br/><br/><br/><br/><img src='./images/warning.png' style='width:120px;height:120px;display:block;margin:20px auto;' /><p style='text-align:center;color:#666;'>链接不存在或已被管理员删除</p>";
	}
	$conn->close();
}
?>