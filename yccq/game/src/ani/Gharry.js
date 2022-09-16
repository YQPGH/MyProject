/**
 * Created by 41496 on 2017/8/1.
 */
//马车动画
(function(){
    function GharryAni()
    {
        GharryAni.__super.call(this);
        Laya.Animation.createFrames(['donghua/gharry1.png','donghua/gharry2.png','donghua/gharry3.png','donghua/gharry4.png'],'gharry_1');
        Laya.Animation.createFrames(['donghua/gharry_2_1.png','donghua/gharry_2_2.png','donghua/gharry_2_3.png','donghua/gharry_2_4.png'],'gharry_2');

        this.body = new Laya.Animation();
        this.body.interval = 300;

        this.addChild(this.body);
    }
    Laya.class(GharryAni,'GharryAni',Laya.Sprite);
    var proto = GharryAni.prototype;

    proto.PlayAni = function(type)
    {
        switch(Number(type)){
            case 0:
                this.body.play(0,true,'gharry_1');
                this.pivot(0,160);
                break;
            case 1:
                this.body.play(0,true,'gharry_2');
                //this.skewY = 180;
                this.pivot(200,165);
                break;
        }
    }
})();