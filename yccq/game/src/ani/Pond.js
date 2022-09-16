/**
 * Created by 41496 on 2017/7/27.
 */
(function(){
    //池塘动画类
    function PondAni()
    {
        PondAni.__super.call(this);

        this.body = new Laya.Animation();
        //this.body.interval = 300;

        this.body.loadAnimation('ani/yu.ani');//加载IDE制作的动画
        this.body.play(0,false,'ani2');

        this.addChild(this.body);

        Laya.timer.loop(5000,this,this.PlayAni);
        this.body.on(Laya.Event.COMPLETE,this,this.onAnimation);


    }
    Laya.class(PondAni,'PondAni',Laya.Sprite);
    var proto = PondAni.prototype;

    proto.PlayAni = function()
    {
        this.body.play(0,false,'ani1');
    };

    proto.onAnimation = function()
    {
        this.body.play(0,false,'ani2');
    }

})();