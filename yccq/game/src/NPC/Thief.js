/**
 * Created by 41496 on 2017/9/14.
 */
//派遣到好友农场的间谍
(function(){
    function Thief() {
        Thief.__super.call(this);
        this.body = null;
        Laya.Animation.createFrames(['jiandie/jiandie_1_8.png'],'Thief_normal');
        this.createBody();

        this.on(Laya.Event.CLICK,this,this.onClick);

        this.timer.loop(10000,this,this.ChangePos);
    }
    Laya.class(Thief,'Thief',Laya.Sprite);
    var proto = Thief.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('间谍');
        var dialog = new ThiefDialog();
        dialog.popup();

    };

    proto.createBody = function()
    {
        this.body = new Laya.Animation();
        this.body.play(0,false,'Thief_normal');
        this.addChild(this.body);
        var bounds = this.body.getBounds();
        this.body.pivot(bounds.width-30,bounds.height);
        this.hitArea = new Laya.Rectangle(30-bounds.width,-bounds.height,bounds.width,bounds.height);

    };

    proto.ChangePos = function()
    {
        var ponit = config.Rongshu[Math.floor(Math.random()*config.Rongshu.length)];
        var map = this.stage.getChildByName('MyGame').map;
        var p = map.getPosByindex(ponit[0],ponit[1]);
        this.pos(p.x,p.y);
        this.zOrder = (ponit[0]-1)*100+(ponit[1]-1);
    };

    //派遣倒计时
    proto.setTimer = function(start,end)
    {
        var startTime = Utils.strToTime(start);

        var endTime = Utils.strToTime(end);
        var Time = endTime - startTime;
        console.log(Time);
        if(Time > 0){
            this.timer.once(Time*1000,this,this.goHome);
        }else {
            this.goHome();
        }
    };

    //回去
    proto.goHome = function()
    {
        this.destroy();
    }
})();