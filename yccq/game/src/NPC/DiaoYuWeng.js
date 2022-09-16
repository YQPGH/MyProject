/**
 * Created by 41496 on 2017/9/19.
 */
//钓鱼翁
(function(){
    function DiaoYuWeng()
    {
        DiaoYuWeng.__super.call(this);
        this.body = null;
        this.yanAni = null;
        this.shuibiao = null;
        this.pivot(48,70);
        this.cacheAni();
        this.createBody();

    }
    Laya.class(DiaoYuWeng,'DiaoYuWeng',Laya.Sprite);
    var proto = DiaoYuWeng.prototype;

    proto.cacheAni = function()
    {
        Laya.Animation.createFrames(['donghua/diaoyuyan_1.png','donghua/diaoyuyan_2.png','donghua/diaoyuyan_3.png','donghua/diaoyuyan_4.png'],'diaoyuyan');
        Laya.Animation.createFrames(['donghua/shuibiao_1.png','donghua/shuibiao_2.png','donghua/shuibiao_3.png','donghua/shuibiao_4.png'],'shuibiao');
    };

    proto.createBody = function()
    {
        this.body = new Laya.Image('donghua/diaoyuweng.png');
        this.addChild(this.body);

        this.yanAni = new Laya.Animation();
        this.yanAni.interval = 300;
        this.yanAni.play(0,true,'diaoyuyan');
        this.yanAni.pivot(this.yanAni.getBounds().width/2,0);
        this.yanAni.pos(82,-10);
        this.addChild(this.yanAni);

        this.shuibiao = new Laya.Animation();
        this.shuibiao.interval = 700;
        this.shuibiao.play(0,true,'shuibiao');
        this.shuibiao.pivot(this.shuibiao.getBounds().width/2,0);
        this.shuibiao.pos(119,110);
        this.addChild(this.shuibiao);

    }
})();