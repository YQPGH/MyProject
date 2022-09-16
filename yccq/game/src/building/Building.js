/**
 * Created by lkl on 2017/4/13.
 */
//建筑基类
(function(){
    var self = null;

    function Building()
    {
        Building.__super.call(this);
        self = this;
        this.unlock = false;//是否解锁

        this.name = "building";
        this.status = 0;//工作状态0:空闲1:工作中2:完成可收取
        this.BuildingData = null;
        this.TimeNum = 0;

        this.Level = 1;

        this.ani = null;//动画
        this.collectibleIcon = [];//可收取图标
        this.collectiblePath = 'tex/box.png';

    }
    Laya.class(Building,"Building",Laya.Image);
    var proto = Building.prototype;

    proto.initBuilding = function(skin,name)
    {
        this.skin = skin;
        this.mouseThrough = true;

        //建筑标识
        var text = new Laya.Image(name);
        text.name = "BuildingName";
        text.anchorX = 0.5;
        text.anchorY = 1;
        text.pos(this.width/2,this.height-30);
        this.addChild(text);

        this.progress = new Laya.ProgressBar('peiyu/progress_time.png');
        this.progress.anchorX = 0.5;
        this.progress.sizeGrid = '2,5,2,5';
        this.progress.size(100,14);
        this.progress.pos(this.width/2,0);
        this.progress.value = 1;
        this.progress.visible = false;
        this.addChild(this.progress);


        //this.createAnimation();
    };
    proto.setStatus = function(data,now_time)
    {
        if(data)
        {
            this.setLevel(data.level);
            this.status = Number(data.status);
            var start_time = Utils.strToTime(data.start_time);
            var stop_time = Utils.strToTime(data.stop_time);
            var work_time = stop_time - start_time;
            var lifetime = work_time - (now_time - start_time);
            var work_data = {time:lifetime,work_time:work_time,shopid:data.shopid};
            if(data.temperature){
                work_data.temperature = data.temperature;
            }
            switch(this.status)
            {
                case 2:
                    //this.createCollectible(this.collectiblePath);
                    this.BuildingData = work_data;
                    break;
                case 1:

                    this.startWorking(work_data);
                    break;
                case 0:

                    break;
            }
        }
    };

    proto.setLevel = function(level)
    {
        this.Level = level;
    };

    proto.startWorking = function(data)
    {
        this.status = 1;
        //this.runAni();
        this.BuildingData = data;
        this.TimeNum = data.time;
        this.progress.value = this.TimeNum/this.BuildingData.work_time;
        this.progress.visible = true;
        this.timerLoop(1000,this,this.countDown);
    };

    proto.countDown = function()
    {
        if(this.TimeNum <= 0){
            this.endWorking();
        }else {
            this.TimeNum -= 1;
            console.log(this.TimeNum);
            this.progress.value = this.TimeNum/this.BuildingData.work_time;
        }
    };

    proto.endWorking = function()
    {
        this.status = 2;
        //this.stopAni();
        //this.createCollectible(this.collectiblePath);
        this.clearTimer(this,this.countDown);
        this.progress.visible = false;
        //this.BuildingData = null;
        this.TimeNum = 0;

    };

    //创建可收取图标
    proto.createCollectible = function(skin)
    {
        if(skin)
        {
            Laya.Animation.createFrames(['donghua/xiangzi_1.png','donghua/xiangzi_2.png','donghua/xiangzi_1.png','donghua/xiangzi_3.png','donghua/xiangzi_1.png','donghua/xiangzi_2.png','donghua/xiangzi_1.png','donghua/xiangzi_3.png'],'xiangzi');
            var collectibleIcon = new Laya.Animation();
            collectibleIcon.name = 'collectibleIcon';
            collectibleIcon.interval = 100;
            collectibleIcon.play(0,false,'xiangzi');
            //collectibleIcon.size(64,64);
            this.timer.loop(2000,this,function(){
                collectibleIcon.play(0,false,'xiangzi');
            });
            collectibleIcon.pos(this.collectiblePos[0],this.collectiblePos[1]);
            this.collectibleIcon.push(collectibleIcon);
            this.addChild(collectibleIcon);
        }
    };

    proto.removeCollectible = function()
    {
        if(this.collectibleIcon.length)
        {
            //this.removeChildByName('collectibleIcon');
            var collectibleIcon = this.collectibleIcon.pop();
            this.removeChild(collectibleIcon);
            collectibleIcon = null;
        }
    };

    proto.createAnimation = function()
    {
        if(this.ani) return;
        //动画
        var aniPath = 'donghua/chest_box.json';
        this.ani = new Laya.Animation();
        this.ani.loadAtlas(aniPath); // 加载图集动画
        this.ani.interval = 100;			// 设置播放间隔（单位：毫秒）
        this.ani.index = 1; 				// 当前播放索引
        this.ani.pos(this.width/2,this.height/2);

        //this.ani.play();
        this.ani.zOrder = 5;
        this.ani.visible = false;
        this.addChild(this.ani);
    };

    proto.runAni = function () {
        this.ani.play();
        this.ani.visible = true;
    };

    proto.stopAni = function()
    {
        this.ani.stop();
        this.ani.visible = false;
    };

    //收取成品
    proto.Gather = function(url)
    {
        //this.status = 0;
        Utils.post(url,{uid:localStorage.GUID},this.onGatherReturn,null,this);
    };

    proto.onGatherReturn = function(res,caller)
    {
        if(res.code == undefined)
        {
            var dialog = new CommomConfirm("数据连接失败");
            dialog.popup();
            return;
        }
        if(res.code == 0)
        {
            var ids = caller.BuildingData.shopid.split(",");
            for(var i = 0; i < ids.length; i++)
            {
                ids[i] = ItemInfo[ids[i]].mubiao;
            }
            getItem(ids);
            caller.removeCollectible();
            caller.status = 0;
            caller.BuildingData = null;
        }else
        {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
        self.stage.getChildByName("MyGame").initUserinfo();
    };
})();