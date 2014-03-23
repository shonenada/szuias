$(function(){
	//定义新增用户dialog
	$("#reg").dialog({
		dialogClass : "common-ui-dialog",
		title : "新增管理员用户",
		resizable  : false,
		show : { effect: 'drop', direction: "up" },
		hide: { effect: 'drop', direction: "up" },
		autoOpen : false,
		draggable : true,
		modal : true,
		width: 540,
		beforeClose : function(){
			//清空数据
			$("#reg input[type='text'],#reg input[type='password']").val("");
		},
		buttons : {
			"新增"  : function(){
				var username = $("#reg input[name='username']").val(),
					nickname = $("#reg input[name='nickname']").val().replace(" ",""),
					password = $("#reg input[name='password']").val(),
					repassword = $("#reg input[name='repassword']").val();
				//判断参数
				if (!/^[a-zA-Z_]\w{4,15}/.test(username)){
					alert("账号格式错误！");
					return;
				}
				if(username.length < 1){
					alert("用户名不能为空！");
					return;
				}
				if (!/^\w{5,16}/.test(password)){
					alert("密码格式错误！");
					return;
				}
				if (password != repassword){
					alert("两次输入的密码不相同！");
					return;
				}
				//提交请求
				$.post(RegPostHref,{username:username,nickname:nickname,password:password,repassword:repassword},function(data){
					if(data.success){
						alert(data.info);
						window.location.reload();
					}
					else{
						alert(data.info);
					}
				}, 'json');
			},
			"取消" : function(){
				$(this).dialog("close");
			}
		}
	});
	//定义修改权限dialog
	$("#change_power").dialog({
		dialogClass : "common-ui-dialog",
		title : "修改权限",
		resizable  : false,
		show : { effect: 'drop', direction: "up" },
		hide: { effect: 'drop', direction: "up" },
		autoOpen : false,
		draggable : true,
		modal : true,
		width: 650,
		beforeClose : function(){
			//清空数据
			$(this).dialog("option","title","修改权限");
			$(this).find("input[name='userid']").val("");
			$(".model_label_list label input[name='model']").attr("checked", false);
			$(".menu_label_list label input[name='menu']").attr("checked", false);
		},
		buttons : {
			"修改"  : function(){
				var uid = $(this).find("input[name='userid']").val(),
					modelStr = "", midStr = "",
					models = [],mids = [];
				$(".model_label_list label input[name='model']").each(function(){
					if ($(this).attr("checked")) models.push($(this).val());
				});
				$(".menu_label_list input[name='menu']").each(function(){
					if ($(this).attr("checked")) mids.push($(this).val());
				});
				modelStr = models.join(",");
				midStr = mids.join(",");
				$.post(SavePower,{uid:uid,models:modelStr,mids:midStr},function(data){
					$("#change_power").dialog("close");
					alert(data.info);
				}, 'json');
			},
			"取消" : function(){
				$(this).dialog("close");
			}
		} 
	});
	$(".add-user").click(function(){
		$("#reg").dialog('open');
	});
	//绑定输入框提示事件
	$(".reg-input").focus(function(){
		$(this).parent().next().fadeIn();
	});
	$(".reg-input").blur(function(){
		$(this).parent().next().fadeOut();
	});
	//绑定修改权限按钮
	$(".change-power").click(function(){
		//更改dialog
		var name = $(this).parent().parent().find(".td-2").text(),
			uid = $(this).parent().find("input[name='uid']").val();
		$("#change_power").dialog("option","title","修改<span style=\"color:green;\">"+name+"</span>的权限");
		$("#change_power input[name='userid']").val(uid);
		//加载权限数据
		$.post(LoadPower,{uid:uid},function(data){
			$(".model_label_list label input[name='model']").each(function(){
				for(var i = 0, k = data.model.length; i < k; i++){
					if ($(this).val() == data.model[i]){
						$(this).attr("checked", true);
						break;
					}
				}
			});
			$(".menu_label_list label input[name='menu']").each(function(){
				for(var i = 0, k = data.menu.length; i < k; i++){
					if ($(this).val() == data.menu[i]){
						$(this).attr("checked", true);
						break;
					}
				}
			});
		}, 'json');
		$("#change_power").dialog("open");
	});
	
	//绑定全选按钮
	$("#model_check").click(function(){
		$(".model_label_list input[name='model']").attr("checked", true);
	});
	$("#menu_check").click(function(){
		$(".menu_label_list input[name='menu']").attr("checked", true);
	});
	//绑定反选按钮
	$("#model_check_invert").click(function(){
		$(".model_label_list input[name='model']").each(function(){
			if ($(this).attr("checked"))
				$(this).attr("checked", false);
			else
				$(this).attr("checked", true);
		});
	});
	$("#menu_check_invert").click(function(){
		$(".menu_label_list input[name='menu']").each(function(){
			if ($(this).attr("checked"))
				$(this).attr("checked", false);
			else
				$(this).attr("checked", true);
		});
	});
	
	//删除用户
 	$(".delete-user").click(function(){
 		var uid = $(this).parent().find("input[name='uid']").val(),
 			name = $(this).parent().parent().find(".td-2").text();
 		if (!confirm("是否确定要删除『"+name+"』用户")) return;
 		$.post(DeleteHref,{uid:uid},function(data){
 			if(data.success){
 				alert(data.info);
 				window.location.reload();
			}
			else{
				alert(data.info);
			}
 		}, 'json');
 	});
 	
 	//重置用户密码
 	$(".reset-password").click(function(){
 		var uid = $(this).parent().find("input[name='uid']").val(),
 			name = $(this).parent().parent().find(".td-2").text();
 		if (!confirm("是否确定要重置『"+name+"』用户的密码")) return;
 		$.post(ResetHref,{uid:uid},function(data){
 			if(data.success){
 				alert("该用户密码已经重置为："+data.info);
 				window.location.reload();
			}
			else{
				alert(data.info);
			}
 		}, 'json');
 	});
});