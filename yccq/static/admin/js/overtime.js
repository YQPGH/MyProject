layui.use(['layer'], function () {
    var layer = layui.layer;

    var $ = layui.jquery ;//由于layer弹层依赖jQuery，所以可以直接得到

    var lastTime = new Date().getTime();
    var currentTime = new Date().getTime();
    var timeOut =30 * 60 * 1000; //设置超时时间： 30分

    $(function(){
        /* 鼠标移动事件 */
        $(document).mouseover(function(){
            lastTime = new Date().getTime(); //更新操作时间

        });
    });

    function testTime(){
        currentTime = new Date().getTime(); //更新当前时间
        if(currentTime - lastTime > timeOut){ //判断是否超时
            console.log("超时");
            window.location.href =  base_url+'admin/common/login_out';

        }
    }

    /* 定时器  间隔1秒检测是否长时间未操作页面  */
    window.setInterval(testTime, 1000);

});

