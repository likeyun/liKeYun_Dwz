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

	$sql = "SELECT * FROM dwz_list WHERE dwz_key='$key'";
	$result = $conn->query($sql);
	 
	if ($result->num_rows > 0) {

		// 更新当前短网址的访问量
		mysqli_query($conn,"UPDATE dwz_list SET dwz_pv=dwz_pv+1 WHERE dwz_key='$key'");

		// 更新总访问量、昨天、今天总访问量
		$tingji_file = './index/tongji.txt';
		$fp=fopen($tingji_file,'r+');
		$content='';
		if (flock($fp,LOCK_EX)){
		    while (($buffer=fgets($fp,1024))!=false){
		        $content=$content.$buffer;
		    }
		    $data=unserialize($content);
		    $total = 'total';
		    $month = date('Ym');
		    $today = date('Ymd');
		    $yesterday = date('Ymd',strtotime("-1 day"));
		    $tongji = array();
		    $tongji[$total] = $data[$total] + 1;
		    $tongji[$month] = $data[$month] + 1;
		    $tongji[$today] = $data[$today] + 1;
		    $tongji[$yesterday] = $data[$yesterday];
		    ftruncate($fp,0);
		    rewind($fp);
		    fwrite($fp, serialize($tongji));
		    flock($fp,LOCK_UN);
		    fclose($fp);
		}

	    // 输出数据
	    while($row = $result->fetch_assoc()) {

	    	// 长链接
	        $dwz_url = $row["dwz_url"];
	        // 状态
	        $dwz_status = $row["dwz_status"];
	        // 有效期
	        $dwz_yxq = $row["dwz_yxq"];
	        // 跳转类型
	        $dwz_reditype = $row["dwz_reditype"];
	        // 设备限制
	        $dwz_type = $row["dwz_type"];
	        // 入口域名
	        $dwz_rkym = $row["dwz_rkym"];
	        // 防封域名
	        $dwz_ffym = $row["dwz_ffym"];

	        // 判断是否被停用
	        if ($dwz_status !== '1') {
	        	echo '<title>温馨提示</title>';
	    		echo "<br/><br/><br/><br/><br/><img src='./images/warning.png' style='width:120px;height:120px;display:block;margin:20px auto;' /><p style='text-align:center;color:#666;'>链接已被管理员暂停访问</p>";
	    		exit;
	        }

	        // 判断是否到期
	        date_default_timezone_set("Asia/Shanghai");
			$thisdate = date("Y-m-d");
	        if (strtotime($thisdate)>=strtotime($dwz_yxq) && $dwz_yxq !== 'ever') {
	        	echo '<title>温馨提示</title>';
	    		echo "<br/><br/><br/><br/><br/><img src='./images/warning.png' style='width:120px;height:120px;display:block;margin:20px auto;' /><p style='text-align:center;color:#666;'>链接已过期</p>";
	    		exit;
	        }

	        // 判断直跳还是防封跳
	        if ($dwz_reditype == '1') {
	        	// 直跳
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
					$agent_pc = strtolower($_SERVER['HTTP_USER_AGENT']);
					$is_pc = (strpos($agent_pc, 'windows nt')) ? true : false;
					if($is_pc){
						echo '<title>温馨提示</title>';
						echo "<br/><br/><br/><br/><br/><img src='./images/warning.png' style='width:120px;height:120px;display:block;margin:20px auto;' /><p style='text-align:center;color:#666;'>该页面只能在手机浏览器打开</p>";
						exit;
					}
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
	        }else{
	        	// 防封跳
	        	echo '<title>正在跳转</title>';
	        	$SERVER = $dwz_ffym.$_SERVER["REQUEST_URI"];
				$ffurl = dirname($SERVER);
        		echo '<script>location.href="'.$ffurl.'/fftz.php?id='.$key.'";</script>';
	        }
	    }
	} else {
		echo '<title>温馨提示</title>';
	    echo "<br/><br/><br/><br/><br/><img src='./images/warning.png' style='width:120px;height:120px;display:block;margin:20px auto;' /><p style='text-align:center;color:#666;'>链接不存在或已被管理员删除</p>";
	}
	$conn->close();
}
?>
