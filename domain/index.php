<!DOCTYPE html>
<html>
<head>
	<title>liKeYun短链接生成开源程序2.0 - https://segmentfault.com/u/tanking</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="../images/fvicon.png" type="image/x-icon"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
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

	// 获取域名列表
	$sql_ymlist = 'SELECT * FROM dwz_ym';
	$result_ymlist = $conn->query($sql_ymlist);

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
				<a href="../link/?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>
					<div class="icon"><img src="../images/edit_icon.png" /></div>
					<div class="text">短链接管理</div>
				</li></a>
				<a href="../domain/?lang=Zh_CN&token='.md5(rand(10000,99999)).'" class="select"><li>
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
		</div>
		<div class="right">
			<h3>域名管理</h3>
			<div class="dhlist">
				<ul>
					<a href="./?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>域名管理</li></a>
					<li style="background:none;color:#333;" data-toggle="modal" data-target="#Creat_dlj">添加域名</li>
				</ul>
			</div>
			<!-- 表格 -->
			<div class="datalist">
				<!-- 表头 -->
				<div class="datalist_title">
					<div class="ym">域名</div>
					<div class="ym_type">类型</div>
					<div class="ym_do">操作</div>
				</div>';
				if ($result_ymlist->num_rows > 0) {
					while($row_ymzlist = $result_ymlist->fetch_assoc()) {

						$ym = $row_ymzlist['ym'];
						$ym_type = $row_ymzlist['ym_type'];
						$ym_id = $row_ymzlist['ym_id'];

						if ($ym_type == '1') {
							$ym_type = '入口域名';
						}else{
							$ym_type = '防封域名';
						}
						echo '<div class="list">
						<div class="ym">'.$ym.'</div>
						<div class="ym_type">'.$ym_type.'</div>
						<div class="ym_do"><a href="javascript:;" id="'.$ym_id.'" onclick="delym(this);">删除</a></div>
						</div>';
					}
				}else{
					echo '<br/><p class="zanwu">暂无域名，请添加</p>';
				}
			echo '</div>
		</div>
	</div>
</div>';
}else{
	header('Location:../account/');
}
?>

<!-- 添加域名 -->
<div class="modal fade" id="Creat_dlj">
<div class="modal-dialog">
  <div class="modal-content">

    <!-- 添加域名 -->
    <div class="modal-header">
      <h4 class="modal-title">添加域名</h4>
      <button type="button" class="close" data-dismiss="modal" style="outline: none;">&times;</button>
    </div>

    <!-- 模态框主体 -->
    <div class="modal-body">
      <form onsubmit="return false" id="addym">
		<input type="text" name="ym" class="inputstyle" placeholder="请输入你要添加的域名">
		<div class="radio">
			<input id="radio-1" class="radio" name="ym_type" type="radio" value="1" checked>
			<label for="radio-1" class="radio-label">入口域名</label>
			<input id="radio-2" class="radio" name="ym_type" type="radio" value="2">
			<label for="radio-2" class="radio-label">防封域名</label>
		</div>
		<p style="color: #999;font-size: 14px;width:90%;margin:0 auto;">域名要求http或https开头，结尾不要加 “/”</p>
      </form>

      <!-- 提示框 -->
      <div id="result"></div>
    </div>

    <!-- 模态框底部 -->
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" onclick="addym();">立即添加</button>
    </div>

  </div>
</div>
</div>

<script type="text/javascript">
// 延迟关闭信息提示框
function closesctips(){
  $("#result .success").css('display','none');
  $("#result .error").css('display','none');
}

// 添加域名
function addym(){
  $.ajax({
      type: "POST",
      url: "./addym.php",
      data: $('#addym').serialize(),
      success: function (data) {
        // 创建成功
        if (data.code == 100) {
        	$("#result").html('<div class="success">'+data.msg+'</div>');
        	setTimeout('location.reload()', 1000);
        }else{
        	$("#result").html('<div class="error">'+data.msg+'</div>');
        }
      },
      error : function() {
        // 创建失败
        $("#result").html('<div class="error">服务器发生错误</div>');
      }
  });
  setTimeout('closesctips()', 2000);
}

// 删除域名
function delym(event){
	var ymid = event.id;
	$.ajax({
      type: "GET",
      url: "./delym.php?ymid="+ymid,
      success: function (data) {
        // 删除成功
        if (data.code == 100) {
        	location.reload();
        }else{
        	alert('删除失败');
        }
      },
      error : function() {
        // 删除失败
        alert('服务器发生错误');
      }
  });
  setTimeout('closesctips()', 2000);
}
</script>
</body>
</html>