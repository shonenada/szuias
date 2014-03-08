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
		post.index_top_1 = $("input[name='index_top_1']").val();
		post.index_top_2 = $("input[name='index_top_2']").val();
		post.index_top_3 = $("input[name='index_top_3']").val();
		post.index_pic_count = $("select[name='index_pic_count']").val();
		post.index_pic_renewtime = $("select[name='index_pic_renewtime']").val();
		post.index_pic_source = $("select[name='index_pic_source']").val();
		$.post(href, post, function(data){
			common.alert.closeLoading();
			if (data.success) {
				common.alert.closeLoading();
				alert("保存成功!");
			}		
			else alert(data.info);
		});
		return false;
	});
	
	//取消按钮
	$("#cancel").click(function(){
		window.location.reload();
	});
 });