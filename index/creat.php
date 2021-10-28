<!DOCTYPE html>
<html>
<head>
	<title>liKeYun短链接生成开源程序2.0 - https://segmentfault.com/u/tanking</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/creatpage.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
  <script src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
  <link rel="shortcut icon" href="../images/fvicon.png" type="image/x-icon"/>
</head>
<body>
	<div class="top">
		<div class="dhnav">
			<div class="logo">
				<a href="./"><img src="../images/logo.png"></a>
			</div>
		</div>
	</div>

	<div id="nav">
		<div class="text">
			<div class="left">
				<p class="bigtitle">个人自建短链接生成工具</p>
				<p class="minititle">社群营销、短信营销、互联网推广、私域流量</p>
				<p class="inttext">🔗防封跳转&nbsp;&nbsp;&nbsp;🔗设备限制&nbsp;&nbsp;&nbsp;🔗域名绑定</p>
				<p class="inttext">🔗开放API&nbsp;&nbsp;&nbsp;&nbsp;🔗数据统计&nbsp;&nbsp;&nbsp;&nbsp;🔗域名检测</p>
			</div>
			<div class="right">
				<img src="../images/banner.png" style="width: 400px; height: 250px;">
			</div>
		</div>
	</div>

	<div id="form_view">
	   <form onsubmit="return false" id="creatdwz">
       	<input type="text" name="dwz_url" class="input" placeholder="请粘贴你需要缩短的链接">
       	<button type="button" class="btn" onclick="creatdwz();">生成</button>
       </form>
       <div class="result"></div>
	</div>

<script data-no-instant>InstantClick.init();</script>
<script type="text/javascript">
// 延迟关闭信息提示框
function closesctips(){
  $("#form_view .result").css('display','none');
}


$(document).ready(function(){
    $('.body .left a').click(function(){
        $(this).siblings().removeClass('select');
        $(this).addClass('select');
    })
});

// 创建短网址
function creatdwz(){
  $.ajax({
      type: "POST",
      url: "./chuangjian.php",
      data: $('#creatdwz').serialize(),
      success: function (data) {
        // 创建成功
        if (data.code == 100) {
        	$("#form_view .result").css('display','block');
        	$("#form_view .result").text(data.link)
        }else{
        	$("#form_view .result").css('display','block');
        	$("#form_view .result").text(data.msg)
        	setTimeout('closesctips()', 2000);
        }
      },
      error : function() {
        // 创建失败
        $("#form_view .result").css('display','block');
        	$("#form_view .result").text("创建失败，服务器发生错误")
        	setTimeout('closesctips()', 2000);
      }
  });
}
</script>
</body>
</html>