$(function(){
	$(".first-menu-select").live("change",function(){
		var val = parseInt($(this).val(), 10);
		var	input = $("#outside_url");
		if (val == 0) {
			$(this).val(0);
			input.attr('disabled', 'disabled');
		}
		else if (val == 1) {
			$(this).val(1);
			input.attr('disabled', 'disabled');
		}
		else if (val == 2) {
			$(this).val(2);
			input.attr('disabled', 'disabled');
		}
		else if (val == 3) {
			$(this).val(3);
			input.removeAttr('disabled');
			
		}
	});

	//删除一级菜单		
	$(".deletefirst").live("click",function(){
		var ele = $(this),
			href = ele.attr("href");
			//如果是页面上新增的
			if (/^javascript/i.test(href)){
				ele.parent().parent().parent().parent().parent().remove();
				return false;
			}
			count = ele.attr("data-count");
			if (count > 0){
				if(!confirm("该菜单拥有"+count+"篇文章，删除后所有文章都将被删除！"))
					return false;
			}
			$.post(href,function(data){
				//成功
				if (data.success){
					location.reload();
					alert("删除成功！");
				}
				//失败
				else alert(data.info);
			}, "json"); 
			//取消默认行为
			return false;
	});

	//保存按钮
	$("#submit").click(function(){
		common.alert.loading("正在保存...");
		//保存前进行数据判断
		var flag = true;
		$("input[name='title']").each(function(){
			if ($(this).val().replace(" ","") == ""){
				flag = false;
				alert("菜单标题不能为空！");
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
		var post = {}, JsonStr = "";
		form = $('#submit-form');
		post.sort = form.find("input[name='sort']").val();
		post.title = form.find("input[name='title']").val();
		post.title_eng = form.find("input[name='title_eng']").val();
		post.outside_url = form.find("input[name='outside_url']").val();
		post.type = form.find("select[name='type']").val();
		post.open_style = form.find("select[name='open_style']").val();
		post.is_show = form.find("select[name='is_show']").val();
		JsonStr = $.toJSON(post);
		var href = $(this).attr("href");
		$.post(href, {"menus" : encodeURIComponent(JsonStr)}, function(data){
			common.alert.closeLoading();
			if (data.success) {
				alert("保存成功!");
				location.href = document.referrer;
			}
			else alert(data.info);
		}, 'json');
		return false;
	});

	//保存按钮
	$("#save").click(function(){
		common.alert.loading("正在保存...");
		//保存前进行数据判断
		var flag = true;
		$("input[name='title']").each(function(){
			if ($(this).val().replace(" ","") == ""){
				flag = false;
				alert("菜单标题不能为空！");
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
		$(".one_menu").each(function(){
			var menu = {};
			var current = $(this);
			menu.id = current.attr("data-id") === undefined ? null : current.attr("data-id");
			menu.sort = current.find("input[name='sort']").val();
			menu.title = current.find("input[name='title']").val();
			menu.title_eng = current.find("input[name='title_eng']").val();
			menu.outside_url = current.find("input[name='outside_url']").val();
			menu.type = current.find("select[name='type']").val();
			menu.open_style = current.find("select[name='open_style']").val();
			menu.is_show = current.find("select[name='is_show']").val();
			//将结果压入数组
			post.push(menu);
		});
		JsonStr = $.toJSON(post);
		var href = $(this).attr("href");
		$.post(href, {"menus" : encodeURIComponent(JsonStr)}, function(data){
			common.alert.closeLoading();
			if (data.success) {
				alert("保存成功!");
				window.location.reload();
			}
			else alert(data.info);
		}, 'json');
		return false;
	});
});