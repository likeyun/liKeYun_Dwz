<!DOCTYPE html>
<html>
<head>
	<title>liKeYun短链接生成开源程序2.0安装程序</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
	<link rel="shortcut icon" href="../images/fvicon.png" type="image/x-icon"/>
	<style>
		/*安装*/
		.content .jumbotron .install_form_view{
			width: 100%;
			/*background: #fff;*/
		}
	</style>
</head>
<body style="background: #fff!important;">

<?php
header("Content-type:text/html;charset=utf-8");
$phpv = PHP_VERSION; // php版本检测
?>


<div class="content">
	<div class="top">
		<div class="dhnav">
			<div class="logo">
				<a href="./"><img src="../images/logo.png"></a>
			</div>
		</div>
	</div>

	<div class="jumbotron" style="padding:35px 50px;background: #f2f2f2;width: 800px;margin:100px auto 0;">
	<h2>liKeYun短链接生成开源程序2.0</h2>
	<p>这是一套开源、免费、自建的短链接生成程序，可以通过本套程序快速自建属于自己的短链接生成平台，有丰富的功能和便捷的API，可以帮助你进行各项推广任务！</p>
	<!-- 验证安装环境 -->
	<table class="table table-bordered" style="background: #fff;">
	    <thead>
	      <tr>
	        <th>系统参数</th>
	        <th>要求</th>
	        <th>是否符合</th>
	      </tr>
	    </thead>
	    <tbody>
	      <tr>
	        <td>PHP版本</td>
	        <td>php5.5 - 7.4版本</td>
	        <?php
	          if ($phpv >= '5.5' && $phpv <= '7.6') {
	            echo '<td><span class="badge badge-success">符合</span></td>';
	          }else{
	            echo '<td><span class="badge badge-danger">不符合</span> 当前:'.$phpv.'</td>';
	          }
	        ?>
	      </tr>
	    </tbody>
	</table>

		<!-- 表单 -->
		<div class="install_form_view" style="display: none;">
			<form onsubmit="return false" id="install">
				<div class="input-group mb-3">
			      <div class="input-group-prepend dark">
			        <span class="input-group-text">数据库地址</span>
			      </div>
			      <input type="text" class="form-control" placeholder="宝塔面板可填localhost，虚拟主机可以填" id="dburl" name="dburl">
			    </div>

			    <div class="input-group mb-3">
			      <div class="input-group-prepend">
			        <span class="input-group-text">数据库账号</span>
			      </div>
			      <input type="text" class="form-control" placeholder="数据库账号" id="dbuser" name="dbuser">
			    </div>

			    <div class="input-group mb-3">
			      <div class="input-group-prepend">
			        <span class="input-group-text">数据库密码</span>
			      </div>
			      <input type="text" class="form-control" placeholder="数据库密码" id="dbpwd" name="dbpwd">
			    </div>

			    <div class="input-group mb-3">
			      <div class="input-group-prepend">
			        <span class="input-group-text">数据库名称</span>
			      </div>
			      <input type="text" class="form-control" placeholder="数据库名称" id="dbname" name="dbname">
			    </div>

			    <div class="input-group mb-3">
			      <div class="input-group-prepend">
			        <span class="input-group-text">管理员账号</span>
			      </div>
			      <input type="text" class="form-control" placeholder="管理员账号" id="user" name="user">
			    </div>

			    <div class="input-group mb-3">
			      <div class="input-group-prepend">
			        <span class="input-group-text">管理员密码</span>
			      </div>
			      <input type="text" class="form-control" placeholder="管理员密码" id="pwd" name="pwd">
			    </div>
			</form>

			<div id="result" style="width: 100%;"></div>
		</div>

		<!-- 安装按钮 -->
		<?php
		  if ($phpv >= '5.5' && $phpv <= '7.6') {
		    echo '<div class="btn_view"><button type="button" class="btn btn-dark" style="background:#3464e0;border:none;margin:20px auto 0;display: block;" onclick="checkinstall();">开始安装</button></div>';
		  }
		?>
	</div>
</div>

<script type="text/javascript">
function checkinstall(){
	$(".content .jumbotron .table").css("display","none");
	$(".content .jumbotron .install_form_view").css("display","block");
	$(".content .btn_view").html('<button type="button" class="btn btn-dark" style="background:#3464e0;border:none;margin:20px auto 0;display: block;" onclick="install_dwz();">立即安装</button>');
}

// 延迟关闭信息提示框
function closesctips(){
  $("#result .success").css('display','none');
  $("#result .error").css('display','none');
}

// 安装
function install_dwz(){
  $.ajax({
      type: "POST",
      url: "./install.php",
      data: $('#install').serialize(),
      success: function (data) {
        // 安装成功
        if (data.code == '100') {
        	$("#result").html("<div class=\"success\">"+data.msg+"</div>");
        	$(".content .jumbotron").html("<h3>liKeYun短链接2.0安装成功！</h3><p><a href='../index/' target='blank'>管理后台>&nbsp;&nbsp;&nbsp;</a> <a href='../index/creat.php' target='blank'>快捷生成页> </a>&nbsp;&nbsp;&nbsp;<a href='http://pic.iask.cn/fimg/805445297649.jpg' target='blank'>加入交流群></a> </p>");
        }else{
        	$("#result").html("<div class=\"error\">"+data.msg+"</div>");
        }
      },
      error : function() {
        // 安装失败
        $("#result").html("<div class=\"error\">服务器发生错误</div>");
      }
  });
  setTimeout('closesctips()', 2000);
}
</script>
</body>
</html>