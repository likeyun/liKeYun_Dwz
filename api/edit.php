<!DOCTYPE html>
<html>
<head>
	<title>liKeYun短链接生成开源程序2.0 - https://segmentfault.com/u/tanking</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
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
	$api_id = sqlfzr(trim($_GET["apiid"]));
	
	// 安全拦截
	if (!isset($_GET['token']) || empty($_GET['token'])) {
		echo "禁止操作";
		exit;
	}

	// 获取当前id的信息
	$sql_apiinfo = "SELECT * FROM dwz_api WHERE api_id = '$api_id'";
	$res_apiinfo = $conn->query($sql_apiinfo);
	if ($res_apiinfo->num_rows > 0) {
		while($row_apiinfo = $res_apiinfo->fetch_assoc()) {
			$api_user = $row_apiinfo['api_user'];
			$api_key = $row_apiinfo['api_key'];
			$api_yxq = $row_apiinfo['api_yxq'];
			$api_ip = $row_apiinfo['api_ip'];
			$api_status = $row_apiinfo['api_status'];
		}
	}else{
		echo "KEY不存在";
		exit;
	}

	// 界面
	echo '<div class="content">
	<div class="top">
		<div class="dhnav">
			<div class="logo">
				<a href="./"><img src="../images/logo.png"></a>
			</div>
			<div class="login">TANKING <a href="">退出登录</a></div>
		</div>
	</div>
	<div class="body">
		<div class="left">
			<ul>
				<a href="../index/?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>
					<div class="icon"><img src="../images/data_icon.png" /></div>
					<div class="text">数据总览</div>
				</li></a>
				<a href="./?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>
					<div class="icon"><img src="../images/edit_icon.png" /></div>
					<div class="text">短链接管理</div>
				</li></a>
				<a href="../domain/?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>
					<div class="icon"><img src="../images/set_icon.png" /></div>
					<div class="text">域名设置</div>
				</li></a>
				<a href="../api/?lang=Zh_CN&token='.md5(rand(10000,99999)).'" class="select"><li>
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
			<h3>编辑API</h3>
			<div class="dhlist">
				<ul>
					<li>编辑API</li>
					<a href="./"><li style="background:none;color:#333;">返回上一页</li></a>
				</ul>
			</div>
			<div class="datalist">
				<form onsubmit="return false" id="updateapi">
					<input type="text" name="api_user" class="inputstyle" value="'.$api_user.'" placeholder="API授权用户">
					<input type="text" name="api_yxq" class="inputstyle" value="'.$api_yxq.'" placeholder="API有效期，格式：xxxx-xx-xx">
					<input type="text" name="api_ip" class="inputstyle" value="'.$api_ip.'" placeholder="API白名单IP地址">';
					if ($api_status== '1') {
						echo '<div class="radio">
						<input id="radio-1" class="radio" name="api_status" type="radio" value="1" checked>
						<label for="radio-1" class="radio-label">正常授权</label>
						<input id="radio-2" class="radio" name="api_status" type="radio" value="2">
						<label for="radio-2" class="radio-label">停止授权</label>
						</div>';
					}else{
						echo '<div class="radio">
						<input id="radio-1" class="radio" name="api_status" type="radio" value="1">
						<label for="radio-1" class="radio-label">正常授权</label>
						<input id="radio-2" class="radio" name="api_status" type="radio" value="2" checked>
						<label for="radio-2" class="radio-label">停止授权</label>
						</div>';
					}
					echo '<input type="url" name="api_key" class="inputstyle apikey" value="'.$api_key.'" placeholder="请输入Key">
					<input type="hidden" name="api_id" value="'.$api_id.'">
					<input type="hidden" name="api_token" value="'.md5(time()).'">
					<button type="button" class="btn btn-update" onclick="randomString(10);">生成KEY</button>
					<button type="button" class="btn btn-update" onclick="updateapi();">立即更新</button>
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
<!-- 生成KEY -->
<script type="text/javascript">
// 延迟关闭信息提示框
function closesctips(){
  $("#result .success").css('display','none');
  $("#result .error").css('display','none');
}

function randomString(e) {    
    e = e || 32;
    var t = "ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678",
    a = t.length,
    n = "";
    for (i = 0; i < e; i++) n += t.charAt(Math.floor(Math.random() * a));
    $(".content .right .datalist .apikey").val(n)
}

// 更新短网址
function updateapi(){
  $.ajax({
      type: "POST",
      url: "./edit_do.php",
      data: $('#updateapi').serialize(),
      success: function (data) {
        // 更新成功
        if (data.code == 100) {
          $("#result").html('<div class="success">'+data.msg+'</div>');
          setTimeout('location.href="./"', 1500);
        }else{
          $("#result").html('<div class="error">'+data.msg+'</div>');
        }
      },
      error : function() {
        // 更新失败
        $("#result").html('<div class="error">服务器发生错误</div>');
      }
  });
  setTimeout('closesctips()', 2000);
}
</script>
</body>
</html>