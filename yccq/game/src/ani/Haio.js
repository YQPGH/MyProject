/**
 * Created by 41496 on 2017/7/26.
 */
(function(){
    //海鸥动画类
    function HaioAni()
    {
        HaioAni.__super.call(this);
        Laya.Animation.createFrames(['donghua/haio_1_1.png','donghua/haio_1_2.png'],"forward");//缓存动作
        Laya.Animation.createFrames(['donghua/haio_2_1.png','donghua/haio_2_2.png'],"backward");
        this.body = new Laya.Animation();
        this.body.interval = 300;
        this.body.play(0,true,'forward');

        this.addChild(this.body);
        this.timer.once(500,this,this.randomMove);

        this.pos(RandomNum(4000,4500),RandomNum(1900,2350));
        this.zOrder = 20000;

    }
    Laya.class(HaioAni,'HaioAni',Laya.Sprite);
    var proto = HaioAni.prototype;

    proto.randomMove = function()
    {
        var randX = RandomNum(4000,4500);
        var randY = RandomNum(1900,2400);
        if(randX > this.x){
            this.body.play(0,true,'forward');
        }else {
            this.body.play(0,true,'backward');
        }
        Laya.Tween.to(this,{x:randX,y:randY},3000,null,new Laya.Handler(this,this.onMoveComplete),0,true);
    };

    proto.onMoveComplete = function()
    {
        this.randomMove();
    };
})();