<!DOCTYPE html>
<html>
<head>
	<title>liKeYun短链接生成开源程序2.0 - https://segmentfault.com/u/tanking</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
	<link rel="shortcut icon" href="../images/fvicon.png" type="image/x-icon"/>
</head>
<body>
<?php
// 页面字符编码
header("Content-type:text/html;charset=utf-8");
// 验证登录状态
session_start();
if(isset($_SESSION["lkydwz.admin"])){

	// 数据库配置
	include '../dbconfig/db.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	echo '<div class="content">
		<div class="top">
			<div class="dhnav">
				<div class="logo">
					<a href="./"><img src="../images/logo.png"></a>
				</div>
				<div class="login">'.$_SESSION["lkydwz.admin"].' <a href="../account/exitlogin.php">退出登录</a></div>
			</div>
		</div>
		<div class="body">
			<div class="left">
				<ul>
					<a href="./?lang=Zh_CN&token='.md5(rand(10000,99999)).'" class="select"><li>
						<div class="icon"><img src="../images/data_icon.png" /></div>
						<div class="text">数据总览</div>
					</li></a>
					<a href="../link/?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>
						<div class="icon"><img src="../images/edit_icon.png" /></div>
						<div class="text">短链接管理</div>
					</li></a>
					<a href="../domain/?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>
						<div class="icon"><img src="../images/set_icon.png" /></div>
						<div class="text">域名设置</div>
					</li></a>
					<a href="../api/?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>
						<div class="icon"><img src="../images/api_icon.png" /></div>
						<div class="text">开放API</div>
					</li></a>
					<a href="../index/creat.php?lang=Zh_CN&token='.md5(rand(10000,99999)).'" target="blank"><li>
						<div class="icon"><img src="../images/tools_icon.png" /></div>
						<div class="text">快捷页面</div>
					</li></a>
				</ul>
			</div>';

			// 获取访问量
			$tongji_file = dirname(__FILE__).'/tongji.txt';
			$fp=fopen($tongji_file,'r+');
			$content='';
			if (flock($fp,LOCK_EX)){
			    while (($buffer=fgets($fp,1024))!=false){
			        $content=$content.$buffer;
			    }
			    $data=unserialize($content);
			    //设置记录键值
			    $total = 'total';
			    $month = date('Ym');
			    $today = date('Ymd');
			    $yesterday = date('Ymd',strtotime("-1 day"));
			    $tongji = array();
			    // 总访问增加
			    @$tongji[$total] = $data[$total] + 1;
			    // 本月访问量增加
			    @$tongji[$month] = $data[$month] + 1;
			    // 今日访问增加
			    @$tongji[$today] = $data[$today] + 1;
			    // 保持昨天访问
			    @$tongji[$yesterday] = $data[$yesterday];
			    //输出数据
			    $total = $tongji[$total]-1;
			    $month = $tongji[$month]-1;
			    $today = $tongji[$today]-1;
			    $yesterday = $tongji[$yesterday]?$tongji[$yesterday]:0;
			}

			// 获取短网址总数
			$dwznum_sql = "SELECT * FROM dwz_list";
			$result_dwznum = $conn->query($dwznum_sql);
			$dwznum = $result_dwznum->num_rows;

			// 获取API请求次数
			$dwzapi_qqnum = "SELECT * FROM dwz_tongji";
			$result_dwzapi_qqnum = $conn->query($dwzapi_qqnum);
			if ($result_dwzapi_qqnum->num_rows > 0) {
				while($row_dwzapi_qqnum = $result_dwzapi_qqnum->fetch_assoc()) {
					$dwz_api_qq_num = $row_dwzapi_qqnum["dwz_api_qq_num"];
				}
			}


			echo '<div class="right">
				<div id="r">
					<h3>数据总览</h3>
					<div class="datacard">
						<ul>
							<li>
								<span class="text">总访问量</span>
								<span class="value">'.$total.'</span>
							</li>
							<li>
								<span class="text">今日访问量</span>
								<span class="value">'.$today.'</span>
							</li>
							<li>
								<span class="text">昨日访问量</span>
								<span class="value">'.$yesterday.'</span>
							</li>
							<li>
								<span class="text">本月访问量</span>
								<span class="value">'.$month.'</span>
							</li>
							<li>
								<span class="text">共有短链接</span>
								<span class="value">'.$dwznum.'</span>
							</li>
							<li>
								<span class="text">API请求次数</span>
								<span class="value">'.$dwz_api_qq_num.'</span>
							</li>
						</ul>
					</div>
					<div style="width:100%;float:left;margin-left:20px;font-size:14px;color:#666;"><a href="http://pic.iask.cn/fimg/805445297649.jpg" style="color:#666;text-decoration:none;" target="blank">加入开发者交流群>> </a><br/><a href="https://segmentfault.com/u/tanking" style="color:#666;text-decoration:none;" target="blank">访问开发者的博客>> </a><br/><a href="https://github.com/likeyun?tab=repositories" style="color:#666;text-decoration:none;" target="blank">访问开发者GitHub>> </a><br/><a href="http://www.likeyunba.com/hm/" style="color:#666;text-decoration:none;" target="blank">微信开源活码系统>> </a><br/><a href="https://github.com/likeyun/TbkTool" style="color:#666;text-decoration:none;" target="blank">淘宝客开源工具箱>> </a></div>
				</div>
			</div>
		</div>
	</div>';
}else{
	header('Location:../account/');
}
?>

<script data-no-instant>InstantClick.init();</script>
<script type="text/javascript">
$(document).ready(function(){
    $('.body .left a').click(function(){
        $(this).siblings().removeClass('select');
        $(this).addClass('select');
    })
});
</script>
</body>
</html>