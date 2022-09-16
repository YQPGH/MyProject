
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset="utf-8">
    <title>聊天A</title>

</head>
<body>
<ul id="message">

</ul>

<form style="width: 800px" id="" method="post" action="" onsubmit="return false">


    <div class="thumbnail">
        <div class="caption" id="userlist">
            <?php if(count($list)) {?>
            <?php foreach($list as $key=>$value):?>
                    <?php  if($value['uid']==$uid){?>
                        <p style="width: 400px ;float: right"><?= $nickname?> : <?= $value['content']?> <?= $value['addtime']?></p>
                        <?php ;}?>
                    <?php  if($value['uid']==$friend_uid){?>
                        <p style="width: 400px ;float: left"><?= $fnickname?>  : <?= $value['content']?> <?= $value['addtime']?></p>
                        <?php ;}?>

            <?php endforeach;?>
            <?php ;}?>
            <p style="clear: both"></p>
        </div>
    </div>
    <div id="dialog">
        <p id="target" ></p>
    </div>
    <div style="clear: both"></div>
    <div>
        <input id="uid" type="hidden" value="<?= $uid ?>">
        <input id="nickname" type="hidden" value="<?= $nickname?>">
        <input id="fnickname" type="hidden" value="<?= $fnickname?>">
        <input id="friend_uid" type="hidden" value="<?= $friend_uid?>">

    </div>
    <div>

        <h3 id="count">

        </h3>

        <h4 id="show_msg"></h4>
        <textarea  id="content" placeholder="请输入聊天内容……" name="content"></textarea>
        <input id="submit" type="submit" value="发表">
    </div>
</form>

<script src="<?= base_url()?>static/questionnaire/js/jquery-1.8.3.min.js"></script>

<script>

        var uid = document.getElementById('uid').value;
        var friend_uid = document.getElementById('friend_uid').value;
        var nickname = document.getElementById('nickname').value;
        var fnickname = document.getElementById('fnickname').value;
        var ws,client_id;

        $(function(){
            connect();


        });

        // 连接服务端
        function connect() {
            // 创建websocket
            ws = new WebSocket("wss://"+document.domain+":8282");
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

            // 登录
            var login_data = '{"type":"bind","uid":"'+uid+'","friend_uid":"'+friend_uid+'"}';
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
            var data = JSON.parse(e.data);
            client_id = data['client_id'];
            switch(data['type']) {
                // 服务端ping客户端
                // 登录 更新用户列表
                case 'init':
                    var bild = '{"type":"bind","uid":"'+uid+'"}';
                    ws.send(bild);
                    console.log("绑定成功");
                    return;
                //  发言    聊天推送消息 保存 更新
                case 'save':
                    console.log('保存数据');
                $.post("<?= base_url()?>api/chat/sendMsg",
                {
                    uid:uid,
                    friend_uid:friend_uid,
                    is_read:data['is_read'],
                    content:data['content']

                },
                function(msg){

                    var dataObj=eval("("+msg+")");//转换为json对象
                    console.log(dataObj);
                    if(dataObj.code == 0){
                        if(friend_uid==data['uid']){
                            $("#dialog").append('<div class="speech_item" style="float: left">'+fnickname+dataObj.data['addtime']+'<p>'+dataObj.data['content']+'</p> </div>');

                        }
                        else
                        {
                            $("#dialog").append('<div class="speech_item" style="float:right;">'+nickname+dataObj.data['addtime']+'<p>'+dataObj.data['content']+'</p> </div>');

                        }
                    }else{
                        console.log(dataObj.msg);
                    }
                });

                return;
            }
        }

        $('#submit').click(function(){

            var content = $('#content').val();
            var data = '{"uid":"'+uid+'","type":"say","friend_uid":"'+friend_uid+'","client_id":"'+client_id+'","content":"'+content+'"}';
            ws.send(data);

        });


</script>
</body>
</html>

