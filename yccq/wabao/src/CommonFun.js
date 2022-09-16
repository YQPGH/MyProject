/*!
 * 公共函数
 *
 * 
 */
var comfun = {

    post: function (url, data, onComplete, onError, agrs) {
        var request = new Laya.HttpRequest();
        // console.log(request);
        // request._http.timeout = 5000;
        // request._http.ontimeout = function(){
        //     timeoutError();
        // }
        request.once(Laya.Event.COMPLETE, this, onHttpRequestComplete,[agrs]);
        request.once(Laya.Event.ERROR, this, onError || onHttpRequestError);
        request.send(base_url + url, comfun.urlEncode(data), 'post', 'json');
         
        // request.send('http://localhost/index.php' , comfun.urlEncode(data), 'post', 'json');
        function onHttpRequestComplete(agrs,response) {
            //console.log(response);
            //var result = JSON.parse(response);
            onComplete(response,agrs);
        }
        function onHttpRequestError(e) {
            console.log("请求错误:"+e);
        }
    },

    
    urlEncode: function (obj) {
        if (obj == null) return '';
        var urlStr = '';
        for (var key in obj) {
            urlStr += key + "=" + obj[key] + "&";
        }
        
        return urlStr;
    },

};

//网络超时提示
function timeoutError(){
    var dialog = new dialogConfirm1UI();
    dialog.zOrder = 1000;
    dialog.content.x = 270;
    dialog.content.text = '网络超时！';
    dialog.popup();
    }

/**
 通用连接失败提示
 */
function onHttpErr()
{
    var dialog = new dialogConfirm1UI();
    dialog.zOrder = 1000;
    dialog.content.x = 270;
    dialog.content.text = '连接失败！';
    
    dialog.popup();
    console.log("连接失败");
    return;
    

}





