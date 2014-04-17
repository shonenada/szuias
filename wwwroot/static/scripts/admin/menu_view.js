$(function(){
	//绑定一级菜单菜单类型下拉框
	$(".first-menu-select").live("change",function(){
			var val = parseInt($(this).val(), 10);
			var	input = $(this).parent().next().find('input');
			if (val == 0) {
				$(this).val(0);
				input.hide();
			}

			else if (val == 1) {
				$(this).val(1);
				input.hide();
			}
			else if (val == 2) {
				$(this).val(2);
				input.hide();
			}
			else if (val == 3) {
				$(this).val(3);
				input.show();
				$(this).parent().parent().parent().next().find(".two_menu").remove();
			}
		});
	
	//绑定二级菜单菜单类型下拉框
	$(".second-menu-select").live("change",function(){
		var val = $(this).val();
		var input = $(this).parent().next().find('input');
		if(val == 1) {
			$(this).val(1);
			input.hide();
		}
		else if (val == 2) {
			$(this).val(2);
			input.hide();
		}
		else if (val == 3){
			input.show();
			$(this).val(3);
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
					/*if(confirm("确定要删除吗？"))*/
					ele.parent().parent().parent().parent().parent().remove();
					alert("删除成功！");
				}
				//失败
				else alert(data.info);
			}, "json"); 
			//取消默认行为
			return false;
	});
	
	//删除二级菜单
	$(".deletesecond").live("click",function(){
		var ele = $(this),
			href = ele.attr("href");
		//如果是页面上新增的
		if (/^javascript/i.test(href)){
			ele.parent().parent().remove();
			return false;
		}
		count = ele.attr("data-count");
		if (count && count > 0){
			if(!confirm("该菜单拥有"+count+"篇文章，删除后所有文章都将被删除！"))
				return false;
		}
		$.post(href,function(data){
			//成功
			if (data.success){
/*				if(confirm("确定要删除吗？"))
*/			    ele.parent().parent().remove();
				alert("删除成功！");
					
			}
			//失败
			else alert(data.info);
		}, "json");
		//取消默认行为
		return false;
	});

	//新增一级菜单
	$("#new_one a").click(function(){
			var htmlStr = "",
				MaxSort = 0;
			$("#category .one_menu table thead input[name='sort']").each(function(){
				var val = parseInt($(this).val(),10);
				if (val >= MaxSort) MaxSort = val + 1;
			});
			htmlStr += '<div class="one_menu">'
	          		 + '<table><thead><tr>'
	                 + '<td class="td1"><input type="text" name="sort" class="order" value="'
	                 + MaxSort
	                 + '"></td>'
	                 + '<td class="td2"><input type="text" name="title" class="links" value=""></td>'
	                 + '<td class="td3"><select name="type" class="select-type first-menu-select"><option value="0" selected="">节点菜单</option><option value="1">单页内容</option><option value="2">多记录列表</option><option value="3">外部URL</option>'
	                 + '</select></td>'
	                 + '<td class="td4"><input type="text" name="outside_url" class="outlinks" value="" style="display:none;"></td>'
	                 + '<td class="td5"><select name="open_style"><option value="0">原窗口打开</option><option value="1">新窗口打开</option>'
	                 + '</select></td>'
	                 + '<td class="td9"><select name="is_show"><option value="1">是</option><option value="0">否</option>'
	                 + '</select></td>'
	                 // + '<td class="td10"><select name="intranet"><option value="0">公网</option><option value="1">仅内网</option></select></td>'
	                 + '<td class="td6"><a class="deletefirst" href="javascript:void(0)">删除</a></td>'
	           		 + '</tr></thead><tbody><tr><td colspan="6" class="td8">'
	                 + '<a href="javascript:void(0)" class="add-second-menu">新增二级菜单</a></td>'
	                 + '</tr></tbody></table></div>';
		   $('#category').append(htmlStr);
	});
	
	//新增二级菜单
	$(".add-second-menu").live("click",function(){
		var MaxSort = 0,
			Tr = $(this).parent().parent(),
			htmlStr = "";
		//进行判断，是否允许新增，当类型为外部URL时不允许新增
		var TypeTr = Tr.parent().parent().find("thead tr"),
			type = TypeTr.attr("data-type") === undefined ? TypeTr.find("select[name='type']").val() : TypeTr.attr("data-type");
		if (parseInt(type, 10) == 3){
			alert("外部URL菜单不允许新增二级菜单！");
			return false;
		}
		
		Tr.parent().find(".two_menu").each(function(){
			var val = parseInt($(this).find("input[name='sort']").val(),10);
			if (val >= MaxSort) MaxSort = val + 1;
		});
		htmlStr += '<tr class="two_menu">'
		       + '<td class="td1"><input type="text" name="sort" class="order" value="'
		       + MaxSort
		       + '"></td>'
		       + '<td class="td7"><input type="text" name="title" class="links" value=""></td>'
		       + '<td class="td3"><select name="type" class="select-type second-menu-select">'
		       + '<option value="1" selected="">单页内容</option><option value="2">多记录列表</option><option value="3">外部URL</option>'
		       + '</select></td>'
		       + '<td class="td4"><input type="text" name="outside_url" class="outlinks" value="" style="display:none;"></td>'
		       + '<td class="td5"><select name="open_style"><option value="0">原窗口打开</option><option value="1">新窗口打开</option></select></td>'
		       + '<td class="td9"><select name="is_show"><option value="1">是</option><option value="0">否</option></select></td>'
		       // + '<td class="td9"><select name="intranet"><option value="0">公网</option><option value="1">仅内网</option></select></td>'
		       + '<td class="td6"><a class="deletesecond" href="javascript:void(0)">删除</a></td>'
		       + '</tr>';
       Tr.before(htmlStr);
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
				// window.location.reload();
				return false;
			}
		});
		$("input[name='sort']").each(function(){
			if (!/^\d+$/.test($(this).val())){
				flag = false;
				alert("排序只能为数字！");
				// window.location.reload();
				return false;
			}
		});
		if (!flag) {
			common.alert.closeLoading();
			return false;
		}
		//组装数据
		var post = [], JsonStr = "";
		$("#category .one_menu").each(function(){
			var FirstMenu = {},
				thead = $(this).find("table thead tr"),
				tbody = $(this).find("table tbody .two_menu");
			FirstMenu.id = thead.attr("data-id") === undefined ? null : thead.attr("data-id");
			FirstMenu.sort = thead.find("input[name='sort']").val();
			FirstMenu.title = thead.find("input[name='title']").val();
			FirstMenu.outside_url = thead.find("input[name='outside_url']").val();
			FirstMenu.intro = "";
			FirstMenu.type = thead.find("select[name='type']").val();
			FirstMenu.open_style = thead.find("select[name='open_style']").val();
			FirstMenu.is_show = thead.find("select[name='is_show']").val();
			// FirstMenu.intranet = thead.find("select[name='intranet']").val();
			FirstMenu.subMenus = [];
			
			//组装二级菜单
			tbody.each(function(){
				var subMenu = {};
				
				subMenu.id = $(this).attr("data-id") === undefined ? null : $(this).attr("data-id");
				subMenu.sort = $(this).find("input[name='sort']").val();
				subMenu.title = $(this).find("input[name='title']").val();
				subMenu.outside_url = $(this).find("input[name='outside_url']").val();
				subMenu.intro = "";
				subMenu.type = $(this).find("select[name='type']").val();
				subMenu.open_style = $(this).find("select[name='open_style']").val();
				subMenu.is_show = $(this).find("select[name='is_show']").val();
				// subMenu.intranet = $(this).find("select[name='intranet']").val();
				FirstMenu.subMenus.push(subMenu);
				
			});
			//将结果压入数组
			post.push(FirstMenu);
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
	
	//取消按钮
	$("#cancel").click(function(){
		window.location.reload();
	});
	
	
	//绑定显示选项
	$("select[name='is_show']").each(function(){
		var preThis = this;
		$(this).live("change",function(){
			if($(preThis).val()==1){
				$(preThis).parent().parent().parent().next().find("tr").each(function(){
					$(this).find(".td9").children().val(0);
				});
			}
			else{
				$(preThis).parent().parent().parent().next().find("tr").each(function(){
					$(this).find(".td9").children().val(1);
			    });
		    }
		});
	
	});
	
	$("thead tr[data-id='<?php echo $first['id'];?>']").each(function(){
		var preThis = this;
		$(this).live("change",function(){
			if($(preThis).val()==1){
				$(preThis).parent().parent().parent().parent().next().find("tr").each(function(){
					$(this).find("select[name='is_show']").children().val(0);
				});
			}
		});
	
	});
	
	
	$(".one_menu").each(function(){
		$(this).find(".two_menu").each(function(){
			$(this).find(".td9").find("select").live("change",function(){
				var flag = true;
				var firV = '';
				var node = $(this).parent().parent().parent().children();
				var len = node.length;
				node.each(function(i){
					if( i == 0) firV = $(this).find(".td9").children().val();
					if(i==Number(len-1)) return;
					if(firV == 0){
						$(this).parent().prev().find(".td9").children().val(0);
						return ;
					}
					if(firV != $(this).find(".td9").children().val()){
						flag = false;
						return ;
					}
				});
				
				if(flag&&firV==1){
					$(this).parent().parent().parent().prev().find(".td9").children().val(1);
				}else{
					$(this).parent().parent().parent().prev().find(".td9").children().val(0);
				}
				
			});
		});
	});
	
	//绑定访问权限选项
	// $("select[name='intranet']").each(function(){
	// 	var preThis = this;
	// 	$(this).live("change",function(){
	// 		if($(preThis).val()==1){
	// 			$(preThis).parent().parent().parent().next().find("tr").each(function(){
	// 				$(this).find(".td10").children().val(1);
	// 			});
	// 		}
	// 		else{
	// 			$(preThis).parent().parent().parent().next().find("tr").each(function(){
	// 				$(this).find(".td10").children().val(0);
	// 		    });
	// 	    }
	// 	});
	
	// });
	
	$("thead tr[data-id='<?php echo $first['id'];?>']").each(function(){
		var preThis = this;
		$(this).live("change",function(){
			if($(preThis).val()==1){
				$(preThis).parent().parent().parent().parent().next().find("tr").each(function(){
					// $(this).find("select[name='intranet']").children().val(1);
				});
			}
		});
	
	});
	
	
	$(".one_menu").each(function(){
		$(this).find(".two_menu").each(function(){
			$(this).find(".td10").find("select").live("change",function(){
				var flag = true;
				var firV = '';
				var node = $(this).parent().parent().parent().children();
				var len = node.length;
				node.each(function(i){
					if( i == 0) firV = $(this).find(".td10").children().val();
					if(i==Number(len-1)) return;
					if(firV == 0){
						$(this).parent().prev().find(".td10").children().val(0);
						return ;
					}
					if(firV != $(this).find(".td10").children().val()){
						flag = false;
						return ;
					}
				});
				
				if(flag&&firV==1){
					$(this).parent().parent().parent().prev().find(".td10").children().val(1);
				}else{
					$(this).parent().parent().parent().prev().find(".td10").children().val(0);
				}
				
			});
		});
	});
	
	
	
 });