/**
 * Created by 41496 on 2017/7/28.
 */
//海浪动画
(function(){
    function HailangAni()
    {
        HailangAni.__super.call(this);
        Laya.Animation.createFrames(['donghua/daditu_han_1_1.png','donghua/daditu_han_1_2.png','donghua/daditu_han_1_3.png'],'hailang');

        this.body = new Laya.Animation();
        this.body.interval = 300;
        this.body.play(0,true,'hailang');
        this.addChild(this.body);
    }
    Laya.class(HailangAni,'HailangAni',Laya.Sprite);
    var proto = HailangAni.prototype;
})();