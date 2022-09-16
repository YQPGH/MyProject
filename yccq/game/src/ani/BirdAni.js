/**
 * Created by 41496 on 2017/7/26.
 */
(function(){
    //小鸟动画类
    function BirdAni()
    {
        BirdAni.__super.call(this);
        Laya.Animation.createFrames(['donghua/niao_2_1.png','donghua/niao_2_2.png'],"bird_forward");//缓存动作
        Laya.Animation.createFrames(['donghua/niao_1.png','donghua/niao_2.png'],"bird_backward");

        this.body = new Laya.Animation();
        this.body.interval = 300;
        this.body.play(0,true,'bird_forward');

        this.addChild(this.body);
        this.timer.once(500,this,this.randomMove);

        this.pos(RandomNum(1500,4500),RandomNum(800,2350));
        this.zOrder = 20000;

    }
    Laya.class(BirdAni,'BirdAni',Laya.Sprite);
    var proto = BirdAni.prototype;

    proto.randomMove = function()
    {
        var x = [[0,1500],[4500,5500]];
        var y = [[0,800],[2350,3000]];
        var temX = x[Math.floor(Math.random()*x.length)];
        var temY = y[Math.floor(Math.random()*y.length)];
        var randX = RandomNum(temX[0],temX[1]);
        var randY = RandomNum(temY[0],temY[1]);
        if(randX > this.x){
            this.body.play(0,true,'bird_forward');
        }else {
            this.body.play(0,true,'bird_backward');
        }
        var t = Math.sqrt((randX - this.x)*(randX - this.x)+(randY - this.y)*(randY - this.y))/0.2;
        Laya.Tween.to(this,{x:randX,y:randY},t,null,new Laya.Handler(this,this.onMoveComplete),0,true);
    };

    proto.onMoveComplete = function()
    {
        this.randomMove();
    };
})();