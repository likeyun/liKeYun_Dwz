// 延迟关闭信息提示框
function closesctips(){
  $("#result .success").css('display','none');
  $("#result .error").css('display','none');
}

// 演示切换
$(document).ready(function(){
    $('.body .left a').click(function(){
        $(this).siblings().removeClass('select');
        $(this).addClass('select');
    })
});

// 监听
$("#gqsj_select").bind('input propertychange',function(e){
  var gqsj_select = $(this).val();
  if (gqsj_select == 'cus') {
    $("#gqsj").css("display","block");
  }else{
    $("#gqsj").css("display","none");
  }
})

// 删除短网址
function del(event){
	// 获得当前操作的id
	var dwzid = event.id;
	// alert(id)
	$('#'+dwzid+'').html('<span id="'+dwzid+'" onclick="qddel(this);">确定删除</span>');
    $('#'+dwzid+'').addClass('qddel');
}

// 确定删除
function qddel(event){
	// 获得当前操作的id
	var dwzid = event.id;
	$.ajax({
      type: "GET",
      url: "./del.php?dwzid="+dwzid,
      success: function (data) {
        // 删除成功
        location.reload();
      },
      error : function() {
        // 删除失败
        alert("删除失败，服务器发生错误")
      }
  });
}

// 创建短网址
function creatdwz(){
  $.ajax({
      type: "POST",
      url: "../creat/index.php",
      data: $('#creatdwz').serialize(),
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

// 分享
function share(event){
  // 获得当前操作的id
  var dwzid = event.id.substr(6,5);
  $.ajax({
      type: "GET",
      url: "./share.php?dwzid="+dwzid,
      success: function (data) {
        // 分享成功
        $("#share .modal-body .link").html('<p>短链接：'+data.url+'</p>')
        $("#share .modal-body .qrcode").html('<img src="./qr.php?content='+data.url+'" />')
        $("#share .modal-body .copy").html('<button type="button" class="btn copyurl" data-clipboard-text='+data.url+'>复制链接</button>')
      },
      error : function() {
        // 分享失败
        alert("分享失败，服务器发生错误")
      }
  });
}

// 复制链接
var clipboard = new Clipboard('#share .modal-body .copy .copyurl');
clipboard.on('success', function (e) {
    $("#share .modal-body .copy .copyurl").text('已复制')
});
clipboard.on('error', function(e) {
    alert("复制失败");
});

// 更新短网址
function updatedwz(){
  $.ajax({
      type: "POST",
      url: "./edit_do.php",
      data: $('#updatedwz').serialize(),
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