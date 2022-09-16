/**
 * Created by 41496 on 2017/8/22.
 */
(function(){
    function BoyAni(map)
    {
        BoyAni.__super.call(this);
        this.map = map;
        Laya.Animation.createFrames(['donghua/boy_1_1.png','donghua/boy_1_2.png','donghua/boy_1_3.png','donghua/boy_1_4.png','donghua/boy_1_5.png','donghua/boy_1_6.png','donghua/boy_1_7.png','donghua/boy_1_8.png'],'boy_left');
        Laya.Animation.createFrames(['donghua/boy_1.png','donghua/boy_2.png','donghua/boy_3.png','donghua/boy_4.png','donghua/boy_5.png','donghua/boy_6.png','donghua/boy_7.png','donghua/boy_8.png'],'boy_right');

        this.body = new Laya.Animation();
        this.body.interval = 100;
        this.addChild(this.body);

        this.PlayAni('down');
        this.map.addBuilding(this,18,26);

        var point1 = this.map.getPosByindex(18,26),
            point2 = this.map.getPosByindex(18,29),
            point3 = this.map.getPosByindex(15,29);

        this.timeLine = new Laya.TimeLine();
        this.timeLine.addLabel("action01",0).to(this,{x:point2.x, y:point2.y},4000,null,0)
            .addLabel("action02",0).to(this,{x:point3.x, y:point3.y},4000,null,0)
            .addLabel("action03",0).to(this,{x:point2.x, y:point2.y},4000,null,0)
            .addLabel("action04",0).to(this,{x:point1.x, y:point1.y},4000,null,0);
        this.timeLine.play(0,true);
        this.timeLine.on(Laya.Event.COMPLETE,this,this.onTimeLineComplete);
        this.timeLine.on(Laya.Event.LABEL, this, this.onTimeLineLabel);

    }
    Laya.class(BoyAni,'BoyAni',Laya.Sprite);
    var proto = BoyAni.prototype;

    proto.PlayAni = function(type)
    {
        switch(type){
            case 'left':
                this.body.play(0,true,'boy_left');
                this.skewY = 0;
                break;
            case 'right':
                this.body.play(0,true,'boy_right');
                this.skewY = 0;
                break;
            case 'down':
                this.body.play(0,true,'boy_right');
                this.skewY = 180;
                break;
            case 'up':
                this.body.play(0,true,'boy_left');
                this.skewY = 180;
        }
        this.body.scale(0.5,0.5);
        var bounds = this.body.getBounds();
        this.pivot(bounds.width/2,bounds.height);
    };

    proto.onTimeLineComplete = function()
    {
        this.PlayAni('down');
    };

    proto.onTimeLineLabel = function(Label)
    {
        switch(Label)
        {
            case 'action01':
                this.PlayAni('down');
                break;
            case 'action02':
                this.PlayAni('left');
                //obj.zOrder = 30*100+29;
                break;
            case 'action03':
                this.PlayAni('right');
                break;
            case 'action04':
                this.PlayAni('up');
                break;

        }
    };

    proto.destroyAni = function()
    {
        this.timeLine.destroy();
        this.destroy();
    }
})();