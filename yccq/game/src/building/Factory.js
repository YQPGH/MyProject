/**
 * Created by 41496 on 2017/5/4.
 */
(function(){
    //加工厂建筑类
    function Factory(data,now_time,type)
    {
        Factory.__super.call(this);
        this.size(617,483);
        this.initBuilding(building.Factory,"tex/zhiyanfang_text.png");
        this.FactoryData = [null,null,null];
        this.FactoryTimeNum = [0,0,0];

        this.CountDown = [{},{},{}];
        this.isWaitting = [false,false,false];


        this.Status = [false,false,false];

        for(var i = 0; i < this.CountDown.length; i++)
        {
            this.CountDown[i] = function(index)
            {
                if(this.FactoryData[index].time <= this.FactoryData[index].work_time){
                    if(this.FactoryTimeNum[index] <= 0){
                        this.FactoryEnding(index);
                    }else {
                        this.FactoryTimeNum[index] -= 1;
                        //console.log(this.FactoryTimeNum[index]);
                    }
                }
            }
        }

        this.pivot(Math.floor(this.width/2),320);
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);
            this.collectiblePos = [this.width-130,this.height/2+60];
            this.setFactory(data,now_time);
            this.timer.loop(500,this,this.changeProgress);
        }
    }
    Laya.class(Factory,"Factory",Building);
    var proto = Factory.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('加工厂');
        switch(this.status)
        {
            case 2:
                console.log("收取");
                //this.Gather("process/gather");
                //break;
            case 1:
                /*var dialog = new CommomConfirm("正在加工中，请稍后再来！");
                dialog.popup();
                break;*/
            case 0:
                //var dialog = new FactoryDialog();
                var dialog = new JGCDialog();
                dialog.popup();
                break;
        }
    };

    proto.setFactory = function(data,now_time)
    {
        if(data)
        {
            this.setLevel(data.level);
            for(var i = 0; i < data.data.length; i++)
            {
                var start_time = Utils.strToTime(data.data[i].start_time);
                var stop_time = Utils.strToTime(data.data[i].stop_time);
                var work_time = stop_time - start_time;
                var lifetime = work_time - (now_time - start_time);
                if(lifetime >= work_time){
                    lifetime = work_time;
                }
                var work_data = {time:lifetime,work_time:work_time,shopid:data.data[i].shopid,start_time:start_time,stop_time:stop_time,now_time:now_time};
                switch(Number(data.data[i].status))
                {
                    case 2:
                        //this.createCollectible(this.collectiblePath);
                        this.FactoryData[i] = work_data;
                        break;
                    case 1:
                        this.FactoryWorking(i,work_data);
                        break;
                    case 0:
                        break;
                }
            }
        }
    };

    proto.FactoryWorking = function(index,data)
    {
        this.Status[index] = true;
        this.status = 1;
        this.FactoryData[index] = data;
        this.FactoryTimeNum[index] = data.time;
        if(data.start_time > data.now_time){

            //this.FactoryTimeNum[index] = '等待中';
            this.isWaitting[index] = true;

        }else {
            this.isWaitting[index] = false;
            this.timerLoop(1000,this,this.CountDown[index],[index]);
        }

        this.timerLoop(1000,this,this.createYan);

    };

    proto.FactoryCountDown = function(index)
    {
        if(this.FactoryTimeNum[index] <= 0){
            this.FactoryEnding(index);
        }else {
            this.FactoryTimeNum[index] -= 1;
            //console.log(this.FactoryTimeNum[index]);
        }
    };

    proto.FactoryEnding = function(index)
    {
        /*var other_index = Number(!index);
        if(this.Status[other_index]){
            this.isWaitting[other_index] = false;
            this.timerLoop(1000,this,this.CountDown[other_index],[other_index]);
        }*/


        this.Status[index] = false;
        if(!this.Status[0] && !this.Status[1]){
            this.timer.clear(this,this.createYan);
        }

        this.status = 2;
        //this.createCollectible(this.collectiblePath);
        this.clearTimer(this,this.CountDown[index]);
        this.FactoryTimeNum[index] = 0;
        //this.FactoryData[index] = null;
        if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 2)
        {
            ZhiYinManager.instance().setGuideStep(7,3,true);
            ZhiYinMask.instance().ZhiYinDialog();
        }
    };

    proto.createYan = function()
    {
        //烟
        var yan = Laya.Pool.getItemByClass("yan",YanAni);
        yan.pos(this.width/2-50,20);
        this.addChild(yan);

        /*var yan1 = Laya.Pool.getItemByClass("yan",YanAni);
        yan1.pos(this.width/2+20,80);
        this.addChild(yan1);*/
    };

    proto.changeProgress = function(){
        if(this.FactoryTimeNum[0] > 0 || this.FactoryTimeNum[1] > 0 || this.FactoryTimeNum[2] > 0){
            this.progress.visible = true;
            var max = this.FactoryTimeNum[0];
            var index = 0;

            for(var i = 0; i < this.FactoryTimeNum.length; i++)
            {
                if(max < this.FactoryTimeNum[i]){
                    max = this.FactoryTimeNum[i];
                    index = i;
                }
            }
            this.progress.value = max/this.FactoryData[index].work_time;
        }else {
            this.progress.visible = false;
        }

    }


})();