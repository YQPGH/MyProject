/*
 *  常用JS函数
	(C) 2010 gxfly Inc.	
	$Id: tangjian 2011-11-18 11:10:00   
*/


// 编辑器初始化
var editor;
var mKindEditor;
KindEditor.ready(function(K) {	
	mKindEditor = K;
	editor = K.editor({
		 		urlType :'relative'
				});
});

/**
 * 全选checkbox,注意：标识checkbox id固定为为check_box
 * @param string name 列表check名称,如 uid[]
 */
function selectall(name) {
	if ($("#check_box").attr("checked") == 'checked') {		
		$("input[name='"+name+"']").each(function() {			
			this.checked = true;
		});
	} else {		
		$("input[name='"+name+"']").each(function() {
			this.checked = false;
		});
	}
}

// 全选 反选
function checkall(name) {
	var userAgent = navigator.userAgent.toLowerCase();
	var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
	var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
	var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
	var e = is_ie ? event : checkall.caller.arguments[0];
	obj = is_ie ? e.srcElement : e.target;
	var arr = document.getElementsByName(name);
	var k = arr.length;
	for(var i=0; i<k; i++) {
		arr[i].checked = obj.checked;
	}
}

// 删除
function deletes(name, url) { 
	var ids = '';
	var	hasselect = false;
		
	$("input[name='"+name+"']").each(function() {			
		if(this.checked == true) hasselect = true;
	});
	if (hasselect == false) {
		alert("请先选择要删除的。。。");
		return ;
	}		
	if(confirm('请确认是否删除！')){	
		$("input[name='"+name+"']:checked").each(function() {
			if(ids==''){
				ids = this.value
			} else {
				ids += ',' + this.value;
			}			
			
		});	
		window.location.href = url + "&ids=" +ids;		
	}	
}

//去空格
function trim(str) { 
	var re = /\s*(\S[^\0]*\S)\s*/; 
	re.exec(str); 
	return RegExp.$1; 
}

//是否为合法的Email格式
function isEmail(str)
{
	var myReg = /^[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]*)*@[a-zA-Z0-9\-]+([\.][a-zA-Z0-9\-]+)+$/;
	if ( myReg.test(str) ) return true;
	return false;
}

// 是否是 字母、数字、下划线 6-20位 
function isNumber(str) 
{ 
	var patrn=/^(\w){6,20}$/; 
	if (!patrn.exec(str)) return false; 
	return true;
} 

// ================本站的==================

// 检查注册的各项
function checkreg() 
{
	var username = $("#username").val();	
	if (!username.match( /^[\u4E00-\u9FA5a-zA-Z0-9_\.@]{2,20}$/)) {		
		$("#tips_reg").html("汉字、字母、数字或邮箱，2-20位");		
		return false;
	}
	
	if($("#password").val().length < 6 ||  $("#password").val().length > 20){
		$("#tips_reg").html('密码 6-20位');	
		return false;
	}
	
	var email = $("#email").val();	
	if (!email.match( /^[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]*)*@[a-zA-Z0-9\-]+([\.][a-zA-Z0-9\-]+)+$/ )) {			
		$("#tips_reg").html("请输入正确的邮箱格式");			
		return false;
	}
	return true;	
}

// 获取时间 字符 如 2012-03-24 10:25:10
function getTimes()
{
	var now= new Date();
	var year=now.getFullYear();
	var month=now.getMonth()+1;
	var day=now.getDate();
	var hour=now.getHours();
	var minute=now.getMinutes();
	var second=now.getSeconds();
	
	return year+"-"+month+"-"+day+" "+hour+":"+minute+":"+second;	
}


// 检查查询关键词
function checkkeyword()
{
	if ( $("#keywords").val() == '搜索关键词' ) {
		$("#keywords").val('');
	}		
}

// 上传文件
function upfile(input) 
{	
	editor.loadPlugin('insertfile', function() {
			editor.plugin.fileDialog({
				fileUrl : $('#'+input).val(),
				showRemote : false,
				clickFn : function(url, title) {
					$('#'+input).val(url);
					editor.hideDialog();
				}
			});
		});
}


// 比较两个数字大小，用于数组排序
function sortNumber(vNum1,vNum2)
{
	if(vNum1>vNum2)	{
		return 1;
	}
	else if(vNum1<vNum2)	{
		return -1;
	}
	else {
		return 0;    
	}
}


// 提示 对话框
function dialog_alert(content,title,w,h) {
	var title = title ? title : '提示信息：';
	var w = w ? w : 400;
	var h = h ? h : 200;
	
	var dialog = mKindEditor.dialog({
					width : w,
					height: h,
					title : title,
					body : '<div style="margin:10px;"><strong>'+content+'</strong></div>',
					closeBtn : {
						name : '关闭',
						click : function(e) {
							dialog.remove();
							return false;							
						}
					},
					yesBtn : {
							name : '确定',
							click : function(e) {
								dialog.remove();
								return false;								
							}
					}				
				});

    return dialog;
		
}

// 确认对话框
function dialog_confirm(title,content) {	
	var dialog = mKindEditor.dialog({
					width : 400,
					height: 300,
					title : title,
					body : '<div style="margin:10px;"><strong>'+content+'</strong></div>',
					closeBtn : {
						name : '关闭',
						click : function(e) {
							dialog.remove();
							return false;
						}
					},
					yesBtn : {
							name : '确定',
							click : function(e) {
								dialog.remove();
								return true;
							}
					},
					noBtn : {
						name : '取消',
						click : function(e) {
							dialog.remove();
							return false;
						}
					}				
				});	
				
		return false;
}

//通用弹出对话框
function dialog_url(url,title,w,h) {
	var title = title ? title : '提示信息：';
	var w = w ? w : 800;
	var h = h ? h : 600;
	
	var dialog = mKindEditor.dialog({
					width : w,
					height: h,
					title : title,
					body : '<iframe src="'+url+'" id="iframe1" width="100%" name="iframe1" height="100%" frameborder="0" scrolling="yes" style="overflow: auto;"></iframe>',
					closeBtn : {
						name : '关闭',
						click : function(e) {
							dialog.remove();
						}
					}					
				});
	
	return dialog;
}
