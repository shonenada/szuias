$(function(){
	//头条设置使用图示
	$(".step").mouseover(function(){
		var PreStep = $(".step-on");
		var PrePicId = PreStep.attr("id").split("_")[0]+"_pic";
		var PicId = $(this).attr("id").split("_")[0]+"_pic";
		PreStep.removeClass("step-on");	
		$(this).addClass("step-on");
		$("#"+PrePicId).fadeOut();
		$("#"+PicId).fadeIn();
	});
	//保存按钮
	$("#submit").click(function(){
		common.alert.loading("正在保存...");
		var href = $(this).attr("data-href"),
		post = {};
		post.index_slider_nums = $("select[name='index_slider_nums']").val();
		post.index_slider_fresh_time = $("select[name='index_slider_fresh_time']").val();
		post.index_slider_source = $("select[name='index_slider_source']").val();
		post.activity_articles = $("input[name='activity_articles']").val();
		post.is_captcha = $("select[name='is_captcha']").val();
		$.post(href, post, function(data){
			common.alert.closeLoading();
			if (data.success) {
				common.alert.closeLoading();
				alert("保存成功!");
			}		
			else alert(data.info);
		}, 'json');
		return false;
	});
	
	//取消按钮
	$("#cancel").click(function(){
		window.location.reload();
	});
 });