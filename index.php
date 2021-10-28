<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
	<link rel="shortcut icon" href="../images/fvicon.png" type="image/x-icon"/>
</head>
<?php
header("Content-Type:text/html;charset=utf-8");

// 数据库配置
include './dbconfig/db.php';
include './creat/sqlfzr.php';

// 获得当前传过来的KEY
$key = sqlfzr(trim($_REQUEST["id"]));

// 过滤数据
if (trim(empty($key))) {
	echo '<title>404 Not Found</title>';
    echo "<h1 style='text-align:center;margin-top:30px;'>404 Not Found</h1><hr><p style='text-align:center;'>nginx</p>";
}else{

    // 创建连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	$sql = "SELECT dwz_url FROM dwz_list WHERE dwz_key='$key'";
	$result = $conn->query($sql);
	 
	if ($result->num_rows > 0) {
		// 更新当前短网址的访问量
		mysqli_query($conn,"UPDATE dwz_list SET dwz_pv=dwz_pv+1 WHERE dwz_key='$key'");

	    // 输出数据
	    while($row = $result->fetch_assoc()) {
	        $dwz_url = $row["dwz_url"];
	        echo '<title>正在跳转</title>';
	        echo '<script>location.href="'.$dwz_url.'";</script>';
	    }
	} else {
		echo '<title>温馨提示</title>';
	    echo "<br/><br/><br/><br/><br/><img src='./images/warning.png' style='width:120px;height:120px;display:block;margin:20px auto;' /><p style='text-align:center;color:#666;'>链接不存在或已被管理员删除</p>";
	}
	$conn->close();
	// 访问量记录
	$tingji_file = dirname(__FILE__).'../index/tongji.txt';
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
}
?>