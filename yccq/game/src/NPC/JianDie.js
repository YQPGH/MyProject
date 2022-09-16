/**
 * Created by 41496 on 2017/9/8.
 */
(function(){
    //间谍NPC
    function JianDie(start_time,stop_time,now,firstTime)
    {
        JianDie.__super.call(this);
        this.start_time = start_time;
        this.stop_time = stop_time;
        this._NPCEnable = false;
        this.ani = null;
        Laya.Animation.createFrames(['tex/jiandiezhan_1.png','tex/jiandiezhan_2.png'],'jiandie_ani');
        Laya.Animation.createFrames(['jiandie/jiandie_1_1.png','jiandie/jiandie_1_2.png','jiandie/jiandie_1_3.png','jiandie/jiandie_1_4.png','jiandie/jiandie_1_5.png','jiandie/jiandie_1_6.png','jiandie/jiandie_1_7.png','jiandie/jiandie_1_8.png'],'jiandie_1');
        Laya.Animation.createFrames(['jiandie/jiandie_2_1.png','jiandie/jiandie_2_2.png','jiandie/jiandie_2_3.png','jiandie/jiandie_2_4.png','jiandie/jiandie_2_5.png','jiandie/jiandie_2_6.png','jiandie/jiandie_2_7.png','jiandie/jiandie_2_8.png'],'jiandie_2');
        this.body = new Laya.Animation();
        //this.body.interval = 1000;
        //this.body.play(0,true,'jiandie_ani');
        this.addChild(this.body);
        this.playAni('stand');
        //this.size(this.body.getBounds().width,this.body.getBounds().height);

        this.hitArea = new Laya.Rectangle(-41,-146,82,146);

        //this.pivot(41,146);

        this.tanhao = new Laya.Image('tex/zhanggui_tanhao.png');
        this.tanhao.anchorX = 0.5;
        this.tanhao.anchorY = 1;
        this.tanhao.pos(-10,-120);
        this.addChild(this.tanhao);

        this.scaleX = 0.7;
        this.scaleY = 0.7;

        this.on(Laya.Event.CLICK,this,this.onClick);
        this.setGuYongTimer(stop_time,now);

        if(firstTime){
            this.tips = new Laya.Image('zhiyin/zhiying_qipao_1-17.png');
            this.tips.skewY = 180;
            this.tips.scale(2,2);
            this.tips.pos(-20,-360);
            var text = new Laya.Label('点击间谍人物，在弹窗中选择好友派遣间谍，派遣时间内有几率盗取好友种子或调香书。');
            text.width = 370;
            text.wordWrap = true;
            text.skewY = 180;
            text.pos(330,25);
            text.fontSize = 28;
            text.scale(0.6,0.6);
            text.color = '#ffffff';
            this.tips.addChild(text);
            this.addChild(this.tips);
        }
    }
    Laya.class(JianDie,"JianDie",Laya.Sprite);
    var proto = JianDie.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        if(this._NPCEnable){
            console.log('间谍');
            var dialog = new JianDieInfo();
            dialog.popup();
            if(this.tips)
            {
                this.tips.destroy(true);
                ZhiYinManager.jiandie = 1;
                Utils.post('Guide/close_tips',{uid:localStorage.GUID,building:'jiandie'},null);
            }
        }else {
            console.log('不可用');
        }


    };

    proto.playAni = function(action)
    {
        switch(action)
        {
            case 'stand':
                this.body.play(0,true,'jiandie_ani');
                this.body.interval = 1000;
                break;
            case 'right':
                this.body.play(0,true,'jiandie_1');
                this.body.interval = 200;
                break;
            case 'left':
                this.body.play(0,true,'jiandie_2');
                this.body.interval = 200;
                break;
            case 'up':
                this.body.play(0,true,'jiandie_2');
                this.body.interval = 200;
                this.body.skewY = 180;
                break;
            case 'down':
                this.body.play(0,true,'jiandie_1');
                this.body.interval = 200;
                this.body.skewY = 180;
                break;

        }
        this.size(this.body.getBounds().width,this.body.getBounds().height);
        this.body.pivot(this.body.getBounds().width/2,this.body.getBounds().height);
    };

    proto.start = function(start,end)
    {
        var map = Laya.stage.getChildByName('MyGame').map;

        this.playAni('down');
        this.NPCEnable = false;

        var point = map.getPosByindex(22,33);
        Laya.Tween.to(this,{x:point.x,y:point.y,alpha:0},5000,null,Laya.Handler.create(this,this.onStartAniCom,[start,end]));
    };

    proto.onStartAniCom = function(start,end)
    {
        this.setTimer(start,end);
    };

    proto.comeBack = function()
    {
        var map = Laya.stage.getChildByName('MyGame').map;
        this.playAni('up');

        var point = map.getPosByindex(22,28);
        Laya.Tween.to(this,{x:point.x,y:point.y,alpha:1},5000,null,Laya.Handler.create(this,this.onComeBackAniComplete));
    };

    proto.onComeBackAniComplete = function()
    {
        this.playAni('stand');
        this.NPCEnable = true;
        this.tanhao.pos(10,-120);
    };
    //派遣倒计时
    proto.setTimer = function(start,end)
    {
        var startTime = Utils.strToTime(start);

        var endTime = Utils.strToTime(end);
        var Time = endTime - startTime;
        console.log(Time);
        if(Time > 0){
            var map = Laya.stage.getChildByName('MyGame').map;
            var point = map.getPosByindex(22,33);
            this.pos(point.x,point.y);
            this.alpha = 0;
            this.NPCEnable = false;
            this.timer.once(Time*1000,this,this.comeBack);
        }
    };

    //雇佣倒计时
    proto.setGuYongTimer = function(stop,now)
    {
        var nowTime = Utils.strToTime(now);

        var endTime = Utils.strToTime(stop);
        this.Time = endTime - nowTime;
        this.timer.loop(1000,this,this.countdown);
        if(this.Time > 0){
            this.NPCEnable = true;
        }
    };

    proto.countdown = function()
    {
        this.Time --;
        if(this.Time <= 0){
            this.timer.clear(this,this.countdown);
            this.destroy();
        }
    };

    Laya.getset(0,proto,'NPCEnable',function(){
        return this._NPCEnable;
    },function(val){
        this._NPCEnable = val;
        this.tanhao.visible= this._NPCEnable;

    });
})();