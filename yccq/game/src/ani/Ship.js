/**
 * Created by 41496 on 2017/7/26.
 */
(function(){
    //海鸥动画类
    function ShipAni(map)
    {
        ShipAni.__super.call(this);
        Laya.Animation.createFrames(['donghua/chuan1.png','donghua/chuan2.png','donghua/chuan3.png','donghua/chuan4.png','donghua/chuan5.png','donghua/chuan6.png','donghua/chuan7.png','donghua/chuan8.png'],"ship");//缓存动作
        this.body = new Laya.Animation();
        this.body.interval = 200;
        this.body.play(0,false,'ship');

        this.map = map;

        this.addChild(this.body);

        this.goDest = map.getPosByindex(37,8);
        this.backDest = map.getPosByindex(36,22);

        this.timer.loop(5000,this,this.PlayAni);

    }
    Laya.class(ShipAni,'ShipAni',Laya.Sprite);
    var proto = ShipAni.prototype;

    proto.PlayAni = function()
    {
        this.body.play(0,false,'ship');
    };

    proto.startWork = function()
    {
        Laya.Tween.to(this,{x:this.goDest.x,y:this.goDest.y},5000,null,new Laya.Handler(this,this.back));
    };

    proto.back = function()
    {
        Laya.Tween.to(this,{x:this.backDest.x,y:this.backDest.y},5000,null,new Laya.Handler(this,this.backComplete));
    };

    proto.backComplete = function()
    {
        this.map.BackRoad1();
    }
})();