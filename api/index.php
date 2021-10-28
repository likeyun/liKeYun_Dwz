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

	// 获取总链接数量
	$dwzapinum_sql = "SELECT * FROM dwz_api";
	$result_dwzapinum = $conn->query($dwzapinum_sql);
	$dwzapinum = $result_dwzapinum->num_rows;

	// 每页显示的数量
	$lenght = 10;

	// 当前页码
	@$page = $_GET['p']?$_GET['p']:1;

	// 每页第一行
	$offset = ($page-1)*$lenght;

	// 总数页
	$allpage = ceil($dwzapinum/$lenght);

	// 上一页     
	$prepage = $page-1;
	if($page==1){
		$prepage=1;
	}

	// 下一页
	$nextpage = $page+1;
	if($page==$allpage){
		$nextpage=$allpage;
	}

	if (isset($_GET["apikey"])) {
		// 查询短网址
		$sql_keylist = "SELECT * FROM dwz_api WHERE api_key='".$_GET["apikey"]."'";
	}else{
		// 获取短网址列表
		$sql_keylist = "SELECT * FROM dwz_api ORDER BY ID DESC limit {$offset},{$lenght}";
	}
	$result_keylist = $conn->query($sql_keylist);

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
				<a href="../domain/?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>
					<div class="icon"><img src="../images/set_icon.png" /></div>
					<div class="text">域名设置</div>
				</li></a>
				<a href="../api/?lang=Zh_CN&token='.md5(rand(10000,99999)).'" class="select"><li>
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
			<h3>开放API</h3>
			<div class="dhlist">
				<ul>
					<a href="./?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>KEY列表</li></a>
					<li style="background:none;color:#333;" data-toggle="modal" data-target="#Search_dlj">查询KEY</li>
					<li style="background:none;color:#333;" data-toggle="modal" data-target="#Creat_dlj">API授权</li>
					<a href="devdoc.php?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li style="background:none;color:#333;">开发文档</li></a>
				</ul>
			</div>
			<!-- 表格 -->
			<div class="datalist">
				<!-- 表头 -->
				<div class="datalist_title">
					<div class="title">用户备注</div>
					<div class="title">KEY</div>
					<div class="date">IP白名单</div>
					<div class="date">创建时间</div>
					<div class="date">有效期</div>
					<div class="status">状态</div>
					<div class="do">操作</div>
				</div>';
				if ($result_keylist->num_rows > 0) {
					while($row_keylist = $result_keylist->fetch_assoc()) {

						$api_key = $row_keylist['api_key'];
						$api_ip = $row_keylist['api_ip'];
						$api_id = $row_keylist['api_id'];
						$api_user = $row_keylist['api_user'];
						$api_status = $row_keylist['api_status'];
						$api_yxq = $row_keylist['api_yxq'];
						$api_creat_time = $row_keylist['api_creat_time'];

						if ($api_ip == null || $api_ip == '' || empty($api_ip)) {
							$api_ip = '不限';
						}

						if ($api_yxq == null || $api_yxq == '' || empty($api_yxq)) {
							$api_yxq = '不限';
						}

						if ($api_status == 1) {
							$api_status = '正常';
						}else{
							$api_status = '停用';
						}

						echo '<div class="list">
						<div class="title">'.$api_user.'</div>
						<div class="title">'.$api_key.'</div>
						<div class="date">'.$api_ip.'</div>
						<div class="date">'.$api_creat_time.'</div>
						<div class="date">'.$api_yxq.'</div>
						<div class="status">'.$api_status.'</div>
						<div class="do">
						<div class="dropdown">
						<button class="dropbtn">•••</button>
						<div class="dropdown-content">
						<a href="./edit.php?apiid='.$api_id.'&lang=Zh_CN&token='.md5(rand(10000,99999)).'">编辑</a>
						<a href="javascript:;" id="'.$api_id.'" onclick="delapi(this)">删除</a>
						</div>
						</div>
						</div>
						</div>';
					}

					// 分页
					echo '<div class="fenye"><ul class="pagination pagination-sm">';
					if ($page == 1 && $allpage == 1) {
						 // 当前页面是第一页，并且仅有1页
						 // 不显示翻页控件
					}else if ($page == 1) {
						 // 当前页面是第一页，还有下一页
					echo '<li class="page-item"><a class="page-link" href="./?lang=Zh_CN&token='.md5(rand(10000,99999)).'">首页</a></li>
						 <li class="page-item"><a class="page-link" href="./?p='.$nextpage.'&lang=zh_CN&token='.md5(rand(10000,99999)).'">下一页</a></li>
						 <li class="page-item"><a class="page-link" href="#">第'.$page.'页</a></li>';
					}else if ($page == $allpage) {
						// 当前页面是最后一页
					echo '<li class="page-item"><a class="page-link" href="./?lang=Zh_CN&token='.md5(rand(10000,99999)).'">首页</a></li>
						 <li class="page-item"><a class="page-link" href="./?p='.$prepage.'&lang=zh_CN&token='.md5(rand(10000,99999)).'">上一页</a></li>
						 <li class="page-item"><a class="page-link" href="#">最后一页</a></li>';
					}else{
					echo '<li class="page-item"><a class="page-link" href="./?lang=Zh_CN&token='.md5(rand(10000,99999)).'">首页</a></li>
						 <li class="page-item"><a class="page-link" href="./?p='.$prepage.'&lang=zh_CN&token='.md5(rand(10000,99999)).'">上一页</a></li>
						 <li class="page-item"><a class="page-link" href="./?p='.$nextpage.'&lang=zh_CN&token='.md5(rand(10000,99999)).'">下一页</a></li>
						 <li class="page-item"><a class="page-link" href="#">第'.$page.'页</a></li>';
					}

				}else{
					echo '<br/><p class="zanwu">暂无KEY，请添加</p>';
				}
				
			echo '</div>
		</div>
	</div>
</div>';
}else{
	header('Location:../account/');
}
?>

<!-- API授权 -->
<div class="modal fade" id="Creat_dlj">
<div class="modal-dialog">
  <div class="modal-content">

    <!-- API授权 -->
    <div class="modal-header">
      <h4 class="modal-title">API授权</h4>
      <button type="button" class="close" data-dismiss="modal" style="outline: none;">&times;</button>
    </div>

    <!-- 模态框主体 -->
    <div class="modal-body">
      <form onsubmit="return false" id="addkey">
		<input type="text" name="api_user" class="inputstyle" placeholder="给用户设置一个备注">
		<input type="text" name="api_ip" class="inputstyle" placeholder="IP白名单，不设置则不验证IP">
		<input type="text" name="api_yxq" class="inputstyle" placeholder="例如: <?php echo date('Y-m-d'); ?> 留空则永久">
      </form>

      <!-- 提示框 -->
      <div id="result"></div>
    </div>

    <!-- 模态框底部 -->
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" onclick="addkey();">创建KEY</button>
    </div>
  </div>
</div>
</div>

<!-- 查询KEY -->
<div class="modal fade" id="Search_dlj">
	<div class="modal-dialog">
	  <div class="modal-content">

	    <!-- 查询短链接 -->
	    <div class="modal-header">
	      <h4 class="modal-title">查询API KEY</h4>
	      <button type="button" class="close" data-dismiss="modal" style="outline: none;">&times;</button>
	    </div>

	    <!-- 模态框主体 -->
	    <div class="modal-body">
	      <form action="./?lang=Zh_CN" method="get">
			<input type="text" name="apikey" class="inputstyle" placeholder="请输入API KEY">
	    </div>

	    <!-- 模态框底部 -->
	    <div class="modal-footer">
	      <input type="submit" class="btn btn-secondary" value="查询" />
	      </form>
	    </div>

	  </div>
	</div>
</div>

<script data-no-instant>InstantClick.init();</script>
<script type="text/javascript">
// 延迟关闭信息提示框
function closesctips(){
  $("#result .success").css('display','none');
  $("#result .error").css('display','none');
}

// 创建key
function addkey(){
  $.ajax({
      type: "POST",
      url: "./addkey.php",
      data: $('#addkey').serialize(),
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

// 删除Key
function delapi(event){
	var apiid = event.id;
	$.ajax({
      type: "GET",
      url: "./delapi.php?apiid="+apiid,
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