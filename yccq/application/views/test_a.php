
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>chatroom</title>

</head>
<body>
<ul id="message">

</ul>

<div>
<!--    --><?php //foreach($list as $v) :?>
<!--        <input name="uid" value="--><?//= $v['uid']?><!--" ><br>-->
<!--    --><?php //endforeach;?>
     <h3 id="count"></h3>
    <h4 id="target" ></h4>
    <h4 id="show_msg"></h4>
<!--    <button type="button" id="send" >发送</button>-->
</div>
<script src="<?= base_url()?>static/questionnaire/js/jquery-1.8.3.min.js"></script>
<!--<script src='http://cdn.bootcss.com/socket.io/1.3.7/socket.io.js'></script>-->
<script>
//    jQuery(function($){


// 连接服务端，workerman.net:2120换成实际部署web-msg-sender服务的域名或者ip
//        var socket = io('http://'+document.domain+':2120');
//        // uid可以是自己网站的用户id，以便针对uid推送以及统计在线人数
//        uid = 122;
//        // socket连接后以uid登录
//        socket.on('connect', function(){
//            socket.emit('login', uid);
//        });
//        // 后端推送来消息时
//        socket.on('new_msg', function(msg){
//            console.log("收到消息："+msg);
//            $('#target').append(msg).append('<br>');
//        });
//        // 后端推送来在线数据时
//        socket.on('update_online_count', function(online_stat){
//            console.log(online_stat);
//            $('#count').html(online_stat);
//        });
        // 改成gateway端口8282
//       var ws = new WebSocket("ws://"+document.domain+":8282");
//        ws.onopen = function(){
//            var uid = 'abcc';
//            ws.send(uid);
//        };
//        ws.onmessage = function(e){
//            alert(e.data);
//        };
// 初始化一个 WebSocket 对象
//        var ws = new WebSocket('ws://'+document.domain+':8282');
//// 服务端主动推送消息时会触发这里的onmessage
//        ws.onmessage = function(e) {
//            // json数据转换成js对象
//            var data = eval("(" + e.data + ")");
//            var type = data.type || '';
//            switch (type) {
//                // Events.php中返回的init类型的消息，将client_id发给后台进行uid绑定
//                case 'init':
//                    // 利用jquery发起ajax请求，将client_id发给后端进行uid绑定
//                    $.post("<?//=  base_url()?>///api/Dragon/onMessage",
//                        {
//
//                            uid: "122",
//                            client_id: data.clientId
//
//                        },
//                        function (data) {
//
//                            console.log(data);
//                        },
//                        'json');
//                    break;
//                default :
//                    console.log(e.data);
//            }
//
//        }
        var uid = '122';
        var ws;
        $(function(){
            connect();
        });
        // 连接服务端
        function connect() {
            // 创建websocket
            ws = new WebSocket("ws://"+document.domain+":8282");
            // 当socket连接打开时，检测uid
            ws.onopen = onopen;
            // 当有消息时根据消息类型显示不同信息
            ws.onmessage = onmessage;
            ws.onclose = function() {
                console.log("连接关闭，定时重连");
                connect();
            };
            ws.onerror = function() {
                console.log("出现错误");
            };
        }

        // 连接建立时发送登录信息
        function onopen()
        {
//            if(!uid)
//            {
//                show_warn();
//                return;
//            }
            // 登录
            var login_data = '{"type":"push","uid":"'+uid+'"}';
            console.log("websocket握手成功，发送登录数据:"+login_data);
            ws.send(login_data);
            //每隔30秒发送心跳
            setInterval(heart,30000);
        }

        //发送心跳
        function heart(){
            console.info("向服务端发送心跳包字符串");
            var heart_data = '{"type":"heart"}';
            ws.send(heart_data);
        }

        // 服务端发来消息时
        function onmessage(e)
        {
            console.log(e.data);
            var data = e.data;
            switch(data['type']){
                // 登录
                case 'login':
                    //array('type'=>$message_data['type'],'client_id'=>$client_id,'uid'=>$message_data['uid'],'time'=>date('Y-m-d H:i:s'),'msg'=>"欢迎你 $client_id 登录，准备可以抽奖了")
//                    $('#show_msg').html(data['msg']);
                    //console.log(data['client_name']+"登录成功");
                    break;
                // 下线
                case 'logout':
                    console.log(data['msg']);
                    break;
                //离队推送消息
                case 'leave':
                    var trStr = '';

                    for(var i = 0; i< data['list'].length; i++){

                        trStr += '<h4 id="target">'+ data['list'][i].uid +'</h4>';
                    }
                    $('#target').html(trStr);

            }
        }

//        function show_warn(){
//            $('#show_warn').html("你没有带uid");
//        }
//    });
</script>
</body>
</html>

