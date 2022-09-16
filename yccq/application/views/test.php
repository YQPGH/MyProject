
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

     <h3 id="count"></h3>
    <h4 id="target" ></h4>
    <h4 id="show_msg"></h4>
</div>
<script src="<?= base_url()?>static/questionnaire/js/jquery-1.8.3.min.js"></script>
<script src='http://cdn.bootcss.com/socket.io/1.3.7/socket.io.js'></script>
<script src='http://cdn.bootcss.com/socket.io/1.3.7/socket.io.js'></script>
<script>
    // 连接服务端，workerman.net:2120换成实际部署web-msg-sender服务的域名或者ip
    var socket = io('ws://"+document.domain+":8282');
    // uid可以是自己网站的用户id，以便针对uid推送以及统计在线人数
    uid = 123;
    // socket连接后以uid登录
    socket.on('connect', function(){
        socket.emit('login', uid);
    });
    // 后端推送来消息时
    socket.on('new_msg', function(msg){
        console.log("收到消息："+msg);
    });
    // 后端推送来在线数据时
    socket.on('update_online_count', function(online_stat){
        console.log(online_stat);
    });
</script>
</body>
</html>

