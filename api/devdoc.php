<!DOCTYPE html>
<html>
<head>
	<title>liKeYun短链接生成开源程序2.0 - https://segmentfault.com/u/tanking</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
	<script src="http://www.likeyun.cloud/pc/js/instantclick.min.js"></script>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
</head>
<body>

<div class="content">
	<div class="top">
		<div class="dhnav">
			<div class="logo">
				<a href="./"><img src="../images/logo.png"></a>
			</div>
		</div>
	</div>
	<div class="body">
		<div class="left">
			<ul>
				<a href="../index/?lang=Zh_CN&token=<?php echo md5(rand(10000,99999)); ?>"><li>
					<div class="icon"><img src="../images/data_icon.png" /></div>
					<div class="text">数据总览</div>
				</li></a>
				<a href="../link/?lang=Zh_CN&token=<?php echo md5(rand(10000,99999)); ?>"><li>
					<div class="icon"><img src="../images/edit_icon.png" /></div>
					<div class="text">短链接管理</div>
				</li></a>
				<a href="../domain/?lang=Zh_CN&token=<?php echo md5(rand(10000,99999)); ?>"><li>
					<div class="icon"><img src="../images/set_icon.png" /></div>
					<div class="text">域名设置</div>
				</li></a>
				<a href="./?lang=Zh_CN&token=<?php echo md5(rand(10000,99999)); ?>" class="select"><li>
					<div class="icon"><img src="../images/api_icon.png" /></div>
					<div class="text">开放API</div>
				</li></a>
				<a href="../index/creat.php?lang=Zh_CN&token=<?php echo md5(rand(10000,99999)); ?>"><li>
					<div class="icon"><img src="../images/tools_icon.png" /></div>
					<div class="text">快捷页面</div>
				</li></a>
			</ul>
		</div>
		<div class="right">
			<h3>开发文档</h3>
			<div class="dhlist">
				<ul>
					<a href="./"><li style="background:none;color:#333;">KEY列表</li></a>
					<li>开发文档</li>
				</ul>
			</div>

			<?php
				$url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
			?>

			<!-- API -->
			<div class="apilink">请求地址(GET) ：<?php echo dirname(dirname($url)).'/creat/'; ?></div>
			<table class="table table-bordered" style="font-size: 15px;">
			    <thead>
			      <tr>
			        <th>请求参数</th>
			        <th>是否必填</th>
			        <th>数据类型</th>
			        <th>参数说明</th>
			      </tr>
			    </thead>
			    <tbody>
			      <tr>
			        <td>dwz_title</td>
			        <td>是</td>
			        <td>string</td>
			        <td>短网址标题</td>
			      </tr>
			      <tr>
			        <td>dwz_reditype</td>
			        <td>是</td>
			        <td>string</td>
			        <td>1直接跳转；2防封跳转</td>
			      </tr>
			      <tr>
			        <td>dwz_yxq</td>
			        <td>是</td>
			        <td>string</td>
			        <td>传入ever代表永久；传入数值就代表可用天数</td>
			      </tr>
			      <tr>
			        <td>dwz_type</td>
			        <td>是</td>
			        <td>string</td>
			        <td>1不限制打开方式；2只能微信内打开；<br/>3只能手机浏览器打开；4只能电脑浏览器打开；<br/>5只能Android设备打开；6只能iOS设备打开</td>
			      </tr>
			      <tr>
			        <td>dwz_url</td>
			        <td>是</td>
			        <td>string</td>
			        <td>需要缩短的链接</td>
			      </tr>
			      <tr>
			        <td>dwz_keynum</td>
			        <td>是</td>
			        <td>string</td>
			        <td>短网址的参数位数，可选4、5、6</td>
			      </tr>
			      <tr>
			        <td>api_key</td>
			        <td>是</td>
			        <td>string</td>
			        <td>请求接口需要的ApiKey</td>
			      </tr>
			    </tbody>
			</table>

			<p style="width: 95%;margin:10px auto;color: #333;font-size: 15px;">
				请求示例 ：<?php echo dirname(dirname($url)).'/api/creat.php?dwz_title=标题&dwz_reditype=1&dwz_yxq=ever&dwz_type=1&dwz_keynum=4&api_key=KpuFcxl9rd&dwz_url=http://www.baidu.com'; ?>
			</p>
		</div>
	</div>
</div>

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
      <form>
		<input type="text" name="" class="inputstyle" placeholder="给用户设置一个备注">
		<input type="text" name="" class="inputstyle" placeholder="IP白名单，不设置则不验证IP">
		<input type="text" name="" class="inputstyle" placeholder="有效期至（日期格式xxxx-xx-xx）不填则不设有效期">
      </form>

      <!-- 提示框 -->
      <div id="result">
			<div class="success">操作成功</div>
      </div>
    </div>

    <!-- 模态框底部 -->
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary">创建KEY</button>
    </div>

  </div>
</div>
</div>

<script data-no-instant>InstantClick.init();</script>
<script type="text/javascript">

$(document).ready(function(){
    $('.body .left a').click(function(){
        $(this).siblings().removeClass('select');
        $(this).addClass('select');
    })
});

$("#gqsj_select").bind('input propertychange',function(e){
  var gqsj_select = $(this).val();
  if (gqsj_select == '自定义') {
    $("#gqsj").css("display","block");
  }else{
    $("#gqsj").css("display","none");
  }
})

// 操作列表控制
function del(event){
	// 获得当前操作的id
	var id = event.id;
	$('#'+id+'').html('<span id="'+id+'" onclick="qddel(this);">确定删除</span>');
    $('#'+id+'').addClass('qddel');
}

function qddel(event){
	// 获得当前操作的id
	var id = event.id;
	location.reload();
}

</script>
</body>
</html>