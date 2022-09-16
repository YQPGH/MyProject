/**
 * Created by 41496 on 2017/7/31.
 */
(function(){
    //海岸动画类
    function HaianAni()
    {
        HaianAni.__super.call(this);
        Laya.Animation.createFrames(['donghua/haibian1.png','donghua/haibian2.png','donghua/haibian3.png','donghua/haibian4.png','donghua/haibian5.png','donghua/haibian6.png'],"haian");//缓存动作

        this.body = new Laya.Animation();
        this.body.interval = 300;
        this.body.play(0,true,'haian');

        this.addChild(this.body);

        //this.timer.loop(5000,this,this.PlayAni);

    }
    Laya.class(HaianAni,'HaianAni',Laya.Sprite);
    var proto = HaianAni.prototype;

    proto.PlayAni = function()
    {
        this.body.play(0,false,'haian');
    }
})();