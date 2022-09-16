/**
 * Created by 41496 on 2017/8/22.
 */
(function(){
    function GirlAni(map)
    {
        GirlAni.__super.call(this);
        this.map = map;
        Laya.Animation.createFrames(['donghua/girl_1_1.png','donghua/girl_1_2.png','donghua/girl_1_3.png','donghua/girl_1_4.png','donghua/girl_1_5.png','donghua/girl_1_6.png','donghua/girl_1_7.png','donghua/girl_1_8.png'],'girl_right');
        Laya.Animation.createFrames(['donghua/girl_1.png','donghua/girl_2.png','donghua/girl_3.png','donghua/girl_4.png','donghua/girl_5.png','donghua/girl_6.png','donghua/girl_7.png','donghua/girl_8.png'],'girl_left');

        this.body = new Laya.Animation();
        this.body.interval = 100;
        this.addChild(this.body);

        this.PlayAni('up');
        this.map.addBuilding(this,33,18);

        var point1 = this.map.getPosByindex(33,18),
            point2 = this.map.getPosByindex(32,12),
            point3 = this.map.getPosByindex(26,11);

        this.timeLine = new Laya.TimeLine();
        this.timeLine.addLabel("action01",0).to(this,{x:point2.x, y:point2.y},4000,null,0)
            .addLabel("action02",0).to(this,{x:point3.x, y:point3.y},4000,null,0)
            .addLabel("action03",0).to(this,{x:point2.x, y:point2.y},4000,null,0)
            .addLabel("action04",0).to(this,{x:point1.x, y:point1.y},4000,null,0);
        this.timeLine.play(0,true);
        this.timeLine.on(Laya.Event.COMPLETE,this,this.onTimeLineComplete);
        this.timeLine.on(Laya.Event.LABEL, this, this.onTimeLineLabel);

    }
    Laya.class(GirlAni,'GirlAni',Laya.Sprite);
    var proto = GirlAni.prototype;

    proto.PlayAni = function(type)
    {
        switch(type){
            case 'up':
                this.body.play(0,true,'girl_right');
                this.skewY = 0;
                break;
            case 'down':
                this.body.play(0,true,'girl_left');
                this.skewY = 0;
                break;
            case 'left':
                this.body.play(0,true,'girl_right');
                this.skewY = 180;
                break;
            case 'right':
                this.body.play(0,true,'girl_left');
                this.skewY = 180;
        }
        this.body.scale(0.5,0.5);
        var bounds = this.body.getBounds();
        this.pivot(bounds.width/2,bounds.height);
    };

    proto.onTimeLineComplete = function()
    {
        this.PlayAni('up');
    };

    proto.onTimeLineLabel = function(Label)
    {
        switch(Label)
        {
            case 'action01':
                this.PlayAni('up');
                break;
            case 'action02':
                this.PlayAni('left');
                //obj.zOrder = 30*100+29;
                break;
            case 'action03':
                this.PlayAni('right');
                break;
            case 'action04':
                this.PlayAni('down');
                break;

        }
    };

    proto.destroyAni = function()
    {
        this.timeLine.destroy();
        this.destroy();
    }
})();