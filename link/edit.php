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
</head>
<body style="overflow-x: hidden; overflow-y: auto; ">

<?php
// 页面字符编码
header("Content-type:text/html;charset=utf-8");
// 验证登录状态
session_start();
if(isset($_SESSION["lkydwz.admin"])){

	// 数据库配置
	include '../dbconfig/db.php';
	include '../creat/sqlfzr.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	// 获取id
	$dwz_id = sqlfzr(trim($_GET["dwzid"]));
	
	// 安全拦截
	if (!isset($_GET['token']) || empty($_GET['token'])) {
		echo "禁止操作";
		exit;
	}

	// 获取当前id的信息
	$sql_dwzinfo = "SELECT * FROM dwz_list WHERE dwz_id = '$dwz_id'";
	$res_dwzinfo = $conn->query($sql_dwzinfo);
	if ($res_dwzinfo->num_rows > 0) {
		while($row_dwzinfo = $res_dwzinfo->fetch_assoc()) {
			$dwz_title = $row_dwzinfo['dwz_title'];
			$dwz_url = $row_dwzinfo['dwz_url'];
			$dwz_type = $row_dwzinfo['dwz_type'];
			$dwz_reditype = $row_dwzinfo['dwz_reditype'];
			$dwz_yxq = $row_dwzinfo['dwz_yxq'];
			$dwz_status = $row_dwzinfo['dwz_status'];
			$dwz_key = $row_dwzinfo['dwz_key'];
		}
	}else{
		echo "短网址不存在";
		exit;
	}

	// 界面
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
				<a href="../index/?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>
					<div class="icon"><img src="../images/data_icon.png" /></div>
					<div class="text">数据总览</div>
				</li></a>
				<a href="./?lang=Zh_CN&token='.md5(rand(10000,99999)).'" class="select"><li>
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
				<a href="../tools/?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>
					<div class="icon"><img src="../images/tools_icon.png" /></div>
					<div class="text">推广工具</div>
				</li></a>
				<a href="../account/?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>
					<div class="icon"><img src="../images/user_icon.png" /></div>
					<div class="text">个人中心</div>
				</li></a>
			</ul>
		</div>
		<div class="right">
			<h3>编辑短链接</h3>
			<div class="dhlist">
				<ul>
					<li>编辑短链接</li>
					<a href="./"><li style="background:none;color:#333;">返回上一页</li></a>
				</ul>
			</div>
			<div class="datalist">
				<form onsubmit="return false" id="updatedwz">
					<input type="text" name="dwz_title" class="inputstyle" value="'.$dwz_title.'" placeholder="短链接标题">';
					if ($dwz_reditype== '1') {
						echo '<div class="radio">
						<input id="radio-1" class="radio" name="dwz_reditype" type="radio" value="1" checked>
						<label for="radio-1" class="radio-label">直接跳转</label>
						<input id="radio-2" class="radio" name="dwz_reditype" type="radio" value="2">
						<label for="radio-2" class="radio-label">防封跳转</label>
						</div>';
					}else{
						echo '<div class="radio">
						<input id="radio-1" class="radio" name="dwz_reditype" type="radio" value="1">
						<label for="radio-1" class="radio-label">直接跳转</label>
						<input id="radio-2" class="radio" name="dwz_reditype" type="radio" value="2" checked>
						<label for="radio-2" class="radio-label">防封跳转</label>
						</div>';
					}
					if ($dwz_status== '1') {
						echo '<div class="radio">
						<input id="radio-3" class="radio" name="dwz_status" type="radio" value="1" checked>
						<label for="radio-3" class="radio-label">正常访问</label>
						<input id="radio-4" class="radio" name="dwz_status" type="radio" value="2">
						<label for="radio-4" class="radio-label">暂停访问</label>
						</div>';
					}else{
						echo '<div class="radio">
						<input id="radio-3" class="radio" name="dwz_status" type="radio" value="1">
						<label for="radio-3" class="radio-label">正常访问</label>
						<input id="radio-4" class="radio" name="dwz_status" type="radio" value="2" checked>
						<label for="radio-4" class="radio-label">暂停访问</label>
						</div>';
					}
					if ($dwz_yxq == 'ever') {
						echo '<select name="dwz_yxq" class="selectstyle" id="gqsj_select">
						<option value ="ever">永久有效</option>
						<option value="7">7天有效期</option>
						<option value="30">30天有效期</option>
						<option value="cus">自定义有效期</option>
						</select>';
					}else if ($dwz_yxq == '7') {
						echo '<select name="dwz_yxq" class="selectstyle" id="gqsj_select">
						<option value="7">7天有效期</option>
						<option value ="ever">永久有效</option>
						<option value="30">30天有效期</option>
						<option value="cus">自定义有效期</option>
						</select>';
					}else if ($dwz_yxq == '30') {
						echo '<select name="dwz_yxq" class="selectstyle" id="gqsj_select">
						<option value="30">30天有效期</option>
						<option value="7">7天有效期</option>
						<option value ="ever">永久有效</option>
						<option value="cus">自定义有效期</option>
						</select>';
					}else {
						echo '<select name="dwz_yxq" class="selectstyle" id="gqsj_select">
						<option value="cus">自定义有效期</option>
						<option value="30">30天有效期</option>
						<option value="7">7天有效期</option>
						<option value ="ever">永久有效</option>
						</select>';
					}
					if ($dwz_yxq !== '7' && $dwz_yxq !== '30' && $dwz_yxq !== 'ever') {
						echo '<input type="text" name="dwz_zdyyxq" class="inputstyle" value="'.$dwz_yxq.'" style="display: block;" id="gqsj" placeholder="输入可访问的天数，例如：60">';
					}else{
						echo '<input type="text" name="dwz_zdyyxq" class="inputstyle" style="display: none;" id="gqsj" placeholder="输入可访问的天数，例如：60">';
					}
					if ($dwz_type == '1') {
						echo '<select name="dwz_type" class="selectstyle" id="open_select">
						<option value ="1">不限制打开方式</option>
						<option value="2">只能微信内打开</option>
						<option value="3">只能手机浏览器打开</option>
						<option value="4">只能电脑浏览器打开</option>
						<option value="5">只能Android设备打开</option>
						<option value="6">只能iOS设备打开</option>
						</select>';
					}else if ($dwz_type == '2') {
						echo '<select name="dwz_type" class="selectstyle" id="open_select">
						<option value="2">只能微信内打开</option>
						<option value ="1">不限制打开方式</option>
						<option value="3">只能手机浏览器打开</option>
						<option value="4">只能电脑浏览器打开</option>
						<option value="5">只能Android设备打开</option>
						<option value="6">只能iOS设备打开</option>
						</select>';
					}else if ($dwz_type == '3') {
						echo '<select name="dwz_type" class="selectstyle" id="open_select">
						<option value="3">只能手机浏览器打开</option>
						<option value="2">只能微信内打开</option>
						<option value ="1">不限制打开方式</option>
						<option value="4">只能电脑浏览器打开</option>
						<option value="5">只能Android设备打开</option>
						<option value="6">只能iOS设备打开</option>
						</select>';
					}else if ($dwz_type == '4') {
						echo '<select name="dwz_type" class="selectstyle" id="open_select">
						<option value="4">只能电脑浏览器打开</option>
						<option value="3">只能手机浏览器打开</option>
						<option value="2">只能微信内打开</option>
						<option value ="1">不限制打开方式</option>
						<option value="5">只能Android设备打开</option>
						<option value="6">只能iOS设备打开</option>
						</select>';
					}else if ($dwz_type == '5') {
						echo '<select name="dwz_type" class="selectstyle" id="open_select">
						<option value="5">只能Android设备打开</option>
						<option value="4">只能电脑浏览器打开</option>
						<option value="3">只能手机浏览器打开</option>
						<option value="2">只能微信内打开</option>
						<option value ="1">不限制打开方式</option>
						<option value="6">只能iOS设备打开</option>
						</select>';
					}
					else if ($dwz_type == '6') {
						echo '<select name="dwz_type" class="selectstyle" id="open_select">
						<option value="6">只能iOS设备打开</option>
						<option value="5">只能Android设备打开</option>
						<option value="4">只能电脑浏览器打开</option>
						<option value="3">只能手机浏览器打开</option>
						<option value="2">只能微信内打开</option>
						<option value ="1">不限制打开方式</option>
						</select>';
					}
					echo '
					<input type="text" name="dwz_key" class="inputstyle" value="'.$dwz_key.'" placeholder="请设置自定义参数">
					<input type="url" name="dwz_url" class="inputstyle" value="'.$dwz_url.'" placeholder="请粘贴长链接">
					<input type="hidden" name="dwz_id" value="'.$dwz_id.'">
					<input type="hidden" name="dwz_token" value="'.md5(time()).'">
					<button type="button" class="btn btn-update" onclick="updatedwz();">立即更新</button>
					<div id="result" style="width:100%;margin-top:30px;"></div>
		      	</form>
			</div>
		</div>
	</div>
</div>';
}else{
	header('Location:../account/');
}
?>
<script src="./do.js"></script>
</body>
</html>