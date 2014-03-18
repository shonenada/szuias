$(function(){
	//导航栏
	$('li.left-first-nav a').click(function(){
	    $(this).parent().find('ul').toggle("fast");
	});

	//转页
	$("#go_page").click(function(){
		var base = location.origin + location.pathname;
		var href = base + '?page=' + $("input[name='page']").val();
		window.location.href = href;
	});

	//删除
	$(".delete").click(function(){
		var ele = $(this),
			href = ele.attr("href"),
			title = $(this).parent().parent().find("td:eq(2)").text().replace(/[\x20\t\f\n\r]*/g, "");
		if ( confirm("是否确认删除「"+title+"」?")){
			$.post(href,function(data){
			//成功
			if (data.success){
				ele.parent().parent().remove();
				alert("删除成功！");
			}
			//失败
			else alert(data.info);
			}, "json");
		}
		//取消默认行为
		return false;
	});

	//重置
	$(".reset_btn").click(function(){
		$("input[name='title']").val("请输入列表标题");
		$("select[name='cid']").val(0);
		$("select[name='creator']").val(0);
		$("select[name='time']").val(0);
	});

	//文章置顶		
	$(".maketop").live("click",function(){
		var ele = $(this),
			href = ele.attr("href");
			$.post(href,function(data){
				//成功
				if (data.success){
					alert("置顶成功！");
					window.location.reload();
				}
				//失败
				else alert(data.info);
			},"json"); 
			//取消默认行为
			return false;
	});

	//取消文章置顶		
	$(".cancel_maketop").live("click",function(){
		var ele = $(this),
			href = ele.attr("href");
			$.post(href,function(data){
				//成功
				if (data.success){
					alert("成功取消置顶！");
					window.location.reload();
				}
				//失败
				else alert(data.info);
			},"json"); 
			//取消默认行为
			return false;
	});

	//隐藏文章	
	$(".hide").live("click",function(){
		var ele = $(this),
			href = ele.attr("href");
			$.post(href,function(data){
				//成功
				if (data.success){
					alert("成功设置该文章为隐藏状态！");
					window.location.reload();
				}
				//失败
				else alert(data.info);
			}, "json"); 
			//取消默认行为
			return false;
	});

	//显示文章		
	$(".display").live("click",function(){
		var ele = $(this),
			href = ele.attr("href");
			$.post(href,function(data){
				//成功
				if (data.success){
					alert("成功设置该文章为显示状态！");
					window.location.reload();
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

		$("input[name='modelsort']").each(function(){
			if (!/^\d+$/.test($(this).val())){
				flag = false;
				alert("排序只能为数字！");
				window.location.reload();
				return false;
			}
		});
		if (!flag) {
			common.alert.closeLoading();
			return false;
		}
		//组装数据
		var post = [], JsonStr = "";
		$("#show_list tr").each(function(i){
			if(i==0) return ;
			var ListInfo = {},
				listaid = $(this).attr("data-aid"),
				listsort = $(this).find("input[name='sort']").val();
			ListInfo.aid = listaid === undefined ? null : listaid;
			ListInfo.sort = listsort;
			
			//将结果压入数组
			post.push(ListInfo);
		});
		
		JsonStr = $.toJSON(post);
		var href = $(this).attr("data-href");
		$.post(href, {"sorts" : encodeURIComponent(JsonStr)}, function(data){
			common.alert.closeLoading();
			if (data.success) {
				alert("保存成功!");
				window.location.reload();
			}		
			else alert(data.info);
		},"json");
		return false;
	});
	
	//取消按钮
	$("#cancel").click(function(){
		window.location.reload();
	});
});