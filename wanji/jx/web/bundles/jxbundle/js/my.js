function loadnav(){
	$("#nav_ul").append("<li id='magic-line'></li>");
    
    var $magicLine = $("#magic-line");
    
    $magicLine
        .width($(".current_page_item").width())
		.css("left", $(".current_page_item a").position().left);

    var url = window.location.href;
	if(typeof(loc)=="undefined"||loc==null||loc == "")
	{
    	loc = url.substring(url.lastIndexOf('jx/')+3, url.length);
	}
	if(loc=='recommend'){
		//alert("here");
			$magicLine.stop().animate({
            left: 467,
            width: 64
         });
		}
	else if(loc=='driver'){
		//alert("here");
			$magicLine.stop().animate({
            left: 327,
            width: 96
         });
		}
	else if(loc=='rent'){
		//alert("here");
			$magicLine.stop().animate({
            left: 187,
            width: 96
         });
		}
	else if(loc==''){
		//alert("here");
			$magicLine.stop().animate({
            left: 15,
            width: 128
         });
		}
	else if(loc=='myfollow'){
		//alert("here");
			$magicLine.stop().animate({
            left: 575,
            width: 64
         });
		}
	else{
		$magicLine.stop().animate({
            left: 15,
            width: 128
         });
		}	
}

function islogin(){
	
	var info_json={}
	$.ajax({
				type:"POST", 
				url:'/userprofile', 
				dataType:"json",
				data:$.toJSON(info_json),
				timeout:5000,
				cache:true,
				async:false,
				success: function (data, textStatus) {
					$("#linker").val(data.nickname);
					$("#linker_tel").val(data.phone);
					},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					switch(XMLHttpRequest.status)
					{	
						case 403:
							alert('请登录后查看');
							window.location.href="/jx/login";
						case 400: //没数据
							break;
						default:
							//alert('加载个人信息出现错误，请重试');
					}
					}
	  });
	}




/*根据输入框的内容来判断是否显示x号*/
function search_box(){
	var search_box = $("#search_box").val();
	if(search_box){$("#search_close").attr("style","");} else{$("#search_close").attr("style","display:none");};
	}

$(function(){
	var profile_json = {};
	ajaxPost(
      'userprofile',
      profile_json,
      function(data, textStatus){
        $("#nav_right").html("<a href='/jx/myinfo' style='color:#fff;'>"+data.nickname+"</a>&nbsp;&nbsp;&nbsp;<a href='/logout' style='color:#fff;'>注销</a>");
		},
      function(XMLHttpRequest, textStatus, errorThrown){
        $("#nav_right").html("<img src='/bundles/jxbundle/images/face.png'/>&nbsp;&nbsp;<a style='color: #fff;' href='/jx/login'>用户登陆</a>&nbsp;&nbsp;&nbsp;<a style='color: #fff;' href='/jx/register'>注册</a>");
      }
    );
});