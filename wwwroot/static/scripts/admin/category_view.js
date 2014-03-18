$(function(){	
	//增加分类
	$(".add-category").live("click", function(){
		var sort = 0,
			tableElement = $(this).parent().parent().find("td .in-table");
		tableElement.find("tbody .one-category").each(function(){
			var maxSort = parseInt($(this).find(".sort input").val(), 10);
			if (maxSort >= sort) {
				sort = maxSort + 1;
			}
		});
		var htmlStr = '<tr class="one-category"><td class="sort">'
					+ '<input type="text" name="sort" value="' + sort + '" /></td>'
					+ '<td class="title">'
					+ '<input type="text" name="title" value="" />'
					+ '</td>'
					+ '<td>0</td>'
					+ '<td>'
					+ '<input type="hidden" value="" />'
					+ '<input type="button" value="删除" class="operate delete-category" />'
					+ '</td></tr>';
		tableElement.find("tbody tr.no-category").before(htmlStr);
	});
	
	//删除分类
	$(".delete-category").live("click", function(){
		var cid = $(this).siblings("input[type='hidden']").val();
		if(/^\d+$/.test(cid)){
			var _this = $(this);
			if (!confirm("该分类拥有"+_this.parent().prev().text()+"篇文章，删除后此分类下所有文章都将被删除，是否确认？"))
				return false;
			$.post(deleteCategoryHref, {cid : cid}, function(data){
				if(data.success){
					_this.parent().parent().remove();
					window.location.reload();
				}
				else{
					alert(data.info);
				}
			}, 'json');
		}
		else{
			$(this).parent().parent().remove();
		}
	});
	
	//提交按钮
	$("#submit").click(function(){
		common.alert.loading("正在保存...");
		//保存前进行数据判断
		var flag = true;
		$("input[name='title']").each(function(){
			if ($(this).val().replace(" ","") == ""){
				flag = false;
				alert("分类名称不能为空！");
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
		$(".out-table>tbody>tr").each(function(){
			var menu = {};
			menu.mid = $(this).attr("data-mid");
			menu.classify = $(this).find(".must-category input").attr("checked")?1:0;
			menu.categories = [];
			$(this).find(".in-table>tbody>.one-category").each(function(){
				var category = {};
				category.cid = $(this).attr("data-cid") === undefined ? null : $(this).attr("data-cid");
				category.sort = $(this).find("input[name='sort']").val();
				category.title = $(this).find("input[name='title']").val();
				menu.categories.push(category);
			});
			post.push(menu);
		});
		JsonStr = $.toJSON(post);
		var href = $(this).attr("data-href");
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
	
	//取消按钮
	$("#cancel").click(function(){
		window.location.reload();
	});
});