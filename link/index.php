<!DOCTYPE html>
<html>
<head>
	<title>liKeYun短链接生成开源程序2.0 - https://segmentfault.com/u/tanking</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/clipboard.js"></script>
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
	$dwznum_sql = "SELECT * FROM dwz_list";
	$result_dwznum = $conn->query($dwznum_sql);
	$dwznum = $result_dwznum->num_rows;

	// 每页显示的数量
	$lenght = 10;

	// 当前页码
	@$page = $_GET['p']?$_GET['p']:1;

	// 每页第一行
	$offset = ($page-1)*$lenght;

	// 总数页
	$allpage = ceil($dwznum/$lenght);

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

	if (isset($_GET["dwzkey"])) {
		// 查询短网址
		$sql_dwzlist = "SELECT * FROM dwz_list WHERE dwz_key='".$_GET["dwzkey"]."'";
	}else{
		// 获取短网址列表
		$sql_dwzlist = "SELECT * FROM dwz_list ORDER BY ID DESC limit {$offset},{$lenght}";
	}
	$result_dwzlist = $conn->query($sql_dwzlist);

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
				<a href="../link/?lang=Zh_CN&token='.md5(rand(10000,99999)).'" class="select"><li>
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
		</div>
		<div class="right">
			<h3>短链接管理</h3>
			<div class="dhlist">
				<ul>
					<a href="./?lang=Zh_CN&token='.md5(rand(10000,99999)).'"><li>短链接列表</li></a>
					<li style="background:none;color:#333;" data-toggle="modal" data-target="#Search_dlj">查询短链接</li>
					<li style="background:none;color:#333;" data-toggle="modal" data-target="#Creat_dlj">创建短链接</li>
				</ul>
			</div>
			<div class="datalist">';
				if ($result_dwzlist->num_rows > 0) {
					echo '<!-- 表格 -->
					<div class="datalist_title">
						<div class="title">标题</div>
						<div class="link">链接</div>
						<div class="date">时间</div>
						<div class="status">时限</div>
						<div class="status">状态</div>
						<div class="status">类型</div>
						<div class="pv">访问</div>
						<div class="do">操作</div>
					</div>';
					while($row_dwzlist = $result_dwzlist->fetch_assoc()) {

						// 读取字段
						$dwz_id = $row_dwzlist['dwz_id'];
						$dwz_key = $row_dwzlist['dwz_key'];
						$dwz_title = $row_dwzlist['dwz_title'];
						$dwz_url = $row_dwzlist['dwz_url'];
						$dwz_type = $row_dwzlist['dwz_type'];
						$dwz_reditype = $row_dwzlist['dwz_reditype'];
						$dwz_yxq = $row_dwzlist['dwz_yxq'];
						$dwz_creat_time = $row_dwzlist['dwz_creat_time'];
						$dwz_pv = $row_dwzlist['dwz_pv'];
						$dwz_status = $row_dwzlist['dwz_status'];
						$dwz_rkym = $row_dwzlist['dwz_rkym'];

						// 判断状态
						if ($dwz_status == 1) {
							$dwz_status = '正常';
						}else{
							$dwz_status = '<span style="color:#f00;">关闭</span>';
						}

						// 判断类型
						if ($dwz_reditype == '1') {
							$dwz_reditype = '直跳';
						}else if($dwz_reditype == '2'){
							$dwz_reditype = '防封';
						}

						// 有效期
						if ($dwz_yxq == 'ever') {
							$dwz_yxq = '永久';
						}else{
							$dwz_yxq = $dwz_yxq.'天';
						}

						// 遍历字段
						echo '<div class="list">
							<div class="title">'.$dwz_title.'</div>
							<div class="link">'.$dwz_rkym.'/'.$dwz_key.'</div>
							<div class="date">'.$dwz_creat_time.'</div>
							<div class="status">'.$dwz_yxq.'</div>
							<div class="status">'.$dwz_status.'</div>
							<div class="status">'.$dwz_reditype.'</div>
							<div class="pv">'.$dwz_pv.'</div>
							<div class="do">
								<div class="dropdown" style="float:left;">
								  <button class="dropbtn">•••</button>
								  <div class="dropdown-content">
								    <a href="#" id="share-'.$dwz_id.'" onclick="share(this);" data-toggle="modal" data-target="#share">分享</a>
								    <a href="./edit.php?dwzid='.$dwz_id.'&lang=Zh_CN&token='.md5(rand(10000,99999)).'">编辑</a>
								    <a href="#" id="'.$dwz_id.'" onclick="del(this);">删除</a>
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
					echo '<!-- 表格 -->
					<div class="datalist_title">
						<div class="title">标题</div>
						<div class="link">链接</div>
						<div class="date">时间</div>
						<div class="status">时限</div>
						<div class="status">状态</div>
						<div class="status">类型</div>
						<div class="pv">访问</div>
						<div class="do">操作</div>
					</div>';
					echo '<br/>';
					echo '<p class="zanwu">暂无链接，请创建</p>';
				}
			echo '</div>
		</div>
	</div>
</div>';
}else{
	header('Location:../account/');
}
?>

<!-- 创建短链接 -->
<div class="modal fade" id="Creat_dlj">
	<div class="modal-dialog">
	  <div class="modal-content">

	    <!-- 创建短链接 -->
	    <div class="modal-header">
	      <h4 class="modal-title">创建短链接</h4>
	      <button type="button" class="close" data-dismiss="modal" style="outline: none;">&times;</button>
	    </div>

	    <!-- 模态框主体 -->
	    <div class="modal-body">
	      <form onsubmit="return false" id="creatdwz">
			<input type="text" name="dwz_title" class="inputstyle" placeholder="短链接标题">
			<div class="radio">
				<input id="radio-1" class="radio" name="dwz_reditype" type="radio" value="1" checked>
				<label for="radio-1" class="radio-label">直接跳转</label>
				<input id="radio-2" class="radio" name="dwz_reditype" type="radio" value="2">
				<label for="radio-2" class="radio-label">防封跳转</label>
			</div>
			<select name="dwz_yxq" class="selectstyle" id="gqsj_select">
			  <option value ="ever">永久有效</option>
			  <option value="7">7天有效期</option>
			  <option value="30">30天有效期</option>
			  <option value="cus">自定义有效期</option>
			</select>
			<input type="text" name="dwz_zdyyxq" class="inputstyle" style="display: none;" id="gqsj" placeholder="输入可访问的天数，例如：60">
			<select name="dwz_type" class="selectstyle" id="open_select">
			  <option value ="1">不限制打开方式</option>
			  <option value="2">只能微信内打开</option>
			  <option value="3">只能手机浏览器打开</option>
			  <option value="4">只能电脑浏览器打开</option>
			  <option value="5">只能Android设备打开</option>
			  <option value="6">只能iOS设备打开</option>
			</select>
			<select name="dwz_keynum" class="selectstyle">
			  <option value ="4">4位随机数</option>
			  <option value="5">5位随机数</option>
			  <option value="6">6位随机数</option>
			</select>
			<input type="url" name="dwz_url" class="inputstyle" placeholder="请粘贴长链接">
			<input type="hidden" name="api_key" value="local">
	      </form>

	      <!-- 提示框 -->
	      <div id="result"></div>
	    </div>

	    <!-- 模态框底部 -->
	    <div class="modal-footer">
	      <button type="button" class="btn btn-secondary" onclick="creatdwz();">立即创建</button>
	    </div>

	  </div>
	</div>
</div>

<!-- 查询短链接 -->
<div class="modal fade" id="Search_dlj">
	<div class="modal-dialog">
	  <div class="modal-content">

	    <!-- 查询短链接 -->
	    <div class="modal-header">
	      <h4 class="modal-title">查询短链接</h4>
	      <button type="button" class="close" data-dismiss="modal" style="outline: none;">&times;</button>
	    </div>

	    <!-- 模态框主体 -->
	    <div class="modal-body">
	      <form action="./?lang=Zh_CN" method="get">
			<input type="text" name="dwzkey" class="inputstyle" placeholder="请输入短链接Key（链接后面的参数）">
	    </div>

	    <!-- 模态框底部 -->
	    <div class="modal-footer">
	      <input type="submit" class="btn btn-secondary" value="查询" />
	      </form>
	    </div>

	  </div>
	</div>
</div>

<!-- 分享 -->
<div class="modal fade" id="share">
	<div class="modal-dialog modal-sm">
	  <div class="modal-content">

	    <!-- 模态框头部 -->
	    <div class="modal-header">
	      <h4 class="modal-title">分享短链接</h4>
	      <button type="button" class="close" data-dismiss="modal">&times;</button>
	    </div>

	    <!-- 模态框主体 -->
	    <div class="modal-body">
	      <p class="link"></p>
	      <p class="qrcode"></p>
	      <p class="copy"></p>
	    </div>
	  </div>
	</div>
</div>
<script src="./do.js"></script>
</body>
</html>