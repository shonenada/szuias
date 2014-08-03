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
		// post.index_slider_nums = $("select[name='index_slider_nums']").val();
		// post.index_slider_fresh_time = $("select[name='index_slider_fresh_time']").val();
		// post.index_slider_source = $("select[name='index_slider_source']").val();
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

	//保存按钮
	$("#slider_save").click(function(){
		common.alert.loading("正在保存...");
		//保存前进行数据判断
		var flag = true;
		$("input[name='img_url']").each(function(){
			if ($(this).val().replace(" ","") == ""){
				flag = false;
				alert("图片 URL不能为空");
				return false;
			}
		});
		$("input[name='sort']").each(function(){
			if (!/^\d+$/.test($(this).val())){
				flag = false;
				alert("排序只能为数字！");
				return false;
			}
		});
		if (!flag) {
			common.alert.closeLoading();
			return false;
		}
		//组装数据
		var post = [], JsonStr = "";
		$(".one_slider").each(function(){
			var data = {};
			var current = $(this);
			data.id = current.find("input[name='id']").val();
			data.title = current.find("input[name='title']").val();
			data.sort = current.find("input[name='sort']").val();
			data.img_url = current.find("input[name='img_url']").val();
			data.target_url = current.find("input[name='target_url']").val();
			//将结果压入数组
			post.push(data);
		});
		JsonStr = $.toJSON(post);
		var href = $(this).attr("data-href");
		$.post(href, {"sliders" : encodeURIComponent(JsonStr)}, function(data){
			common.alert.closeLoading();
			if (data.success) {
				alert("保存成功!");
				window.location.reload();
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