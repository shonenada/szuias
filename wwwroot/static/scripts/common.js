/*
* 共用JS
*/
(function(window, undefined){
	function addPopDiv(str, left, top){
		if(str === undefined) str = "正在提交...";
		if(left === undefined) left = "-70px";
		if(top === undefined) top = "-20px";
		var htmlStr = '<div class="pop-div-out" style="position: fixed;'
					+ 'z-index: 9999;opacity: 0.4;filter: alpha(opacity=40);width: 100%;height: 100%;'
					+ 'background-color: black;top: 0;left: 0;"></div>'
					+ '<div class="pop-div-out" style="position: absolute;top:0;left:0;width: 100%;height: 100%;z-index: 10000;">'
					+ '<div class="pop-div-in" style="'
					+ 'margin:'+top+' 0 0 '+left+';'
					+ '">'
					+ str
					+ '</div>'
					+ '</div>';
		$("body").append(htmlStr);
	}
	//删除遮蔽层 
	function deletePopDiv(){
		$(".pop-div-out").remove();
	}
	function addBg(){
		var htmlStr = '<div class="pop-div-out" style="position: fixed;'
					+ 'z-index: 9999;opacity: 0.4;filter: alpha(opacity=40);width: 100%;height: 100%;'
					+ 'background-color: black;top: 0;left: 0;"></div>';
		$("body").append(htmlStr);
	}
	var alert = {
		loading : addPopDiv,
		closeLoading : deletePopDiv,
		addBg : addBg,
		deleteBg : deletePopDiv
	};
	
	var	common = {
		alert : alert
	};
	window.common = common;
})(window);