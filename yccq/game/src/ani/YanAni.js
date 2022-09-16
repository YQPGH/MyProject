/**
 * Created by 41496 on 2017/8/9.
 */
(function(){
    function YanAni()
    {
        YanAni.__super.call(this);
        this.pivot(31,100);

        Laya.Animation.createFrames(['donghua/yan_1.png','donghua/yan_2.png','donghua/yan_3.png','donghua/yan_4.png','donghua/yan_5.png','donghua/yan_6.png','donghua/yan_7.png','donghua/yan_8.png','donghua/yan_9.png','donghua/yan_10.png'],'yan');

        this.body = new Laya.Animation();
        this.body.interval = 300;
        this.body.play(0,true,'yan');

        this.addChild(this.body);

        this.tween = Laya.Tween.to(this,{y:this.y-100},3000,null,Laya.Handler.create(this,this.removeYan));
    }
    Laya.class(YanAni,'YanAni',Laya.Sprite);
    var proto = YanAni.prototype;

    proto.removeYan = function()
    {
        //Laya.Tween.clear();
        //this.body.clear();
        this.removeSelf();
        //Laya.Pool.recover("yan",this);
    }

})();