/**
 * Created by 41496 on 2018/9/27.
 */
(function(){
    //虫子动画类
    function ChongziAni(type,name,mapType)
    {
        ChongziAni.__super.call(this);
        this.pivot(35,50);
        this.chongziType = type;
        this.startTime = 0;
        this.stopTime = 0;
        this.AllTime = 0;
        this.CurrTime = 0;
        this.number = null;

        this.init(mapType);

        //进度条
        this.createProgress();

        //虫子名字
        this.createOwnerName(name);



    }
    Laya.class(ChongziAni,'ChongziAni',Laya.Sprite);
    var proto = ChongziAni.prototype;

    proto.onChongziClick = function(){
        var dialog = new Confirm1('清除虫子后可获得该虫子收集的一半能量');
        dialog.popup();
        dialog.closeHandler = new Laya.Handler(this,function(name){
            if(name == Laya.Dialog.YES){
                console.log('虫子被点击，清除虫子');
                var self = this;
                Utils.post('Chongzi/clear',{uid:localStorage.GUID,number:this.number},function(res){
                    if(res.code == '0'){
                        self.clearChongzi();
                    }else {
                        var dialog = new CommomConfirm(res.msg);
                        dialog.popup();
                    }
                },onHttpErr);
            }
        });

    };

    proto.init = function(mapType){
        this.body = new Laya.Animation();
        this.body.interval = 300;
        switch(this.chongziType){
            case '3':
                this.body.play(0,true,'chongzi_big');
                break;
            case '2':
                this.body.play(0,true,'chongzi_middle');
                break;
            case '1':
                this.body.play(0,true,'chongzi_small');
                break;
        }
        this.addChild(this.body);
        this.body.size(this.body.getBounds().width,this.body.getBounds().height);
        if(mapType != 'FriendFarm') this.body.on(Laya.Event.CLICK,this,this.onChongziClick);

    };

    proto.createProgress = function(){
        this.progress = new Laya.ProgressBar('peiyu/progress_time.png');
        this.progress.anchorX = 0.5;
        this.progress.sizeGrid = '2,5,2,5';
        this.progress.size(80,14);
        this.progress.pos(25,-15);
        this.progress.value = 1;
        this.addChild(this.progress);
    };

    proto.createOwnerName = function(str){
        this.ownername = new Laya.Label(str);
        this.ownername.color = '#FFFFFF';
        this.ownername.fontSize = 16;
        this.ownername.align = 'center';
        this.ownername.size(200,30);
        this.ownername.anchorX = 0.5;
        this.ownername.pos(25,-30);
        this.addChild(this.ownername);
    };

    proto.setLiftTime = function(startTime,stopTime,nowTime){
        this.startTime = Utils.strToTime(startTime);
        this.stopTime = Utils.strToTime(stopTime);
        var now_time = Utils.strToTime(nowTime);
        this.AllTime = this.stopTime - this.startTime;
        if(now_time >= this.startTime){
            this.CurrTime = this.AllTime - (now_time - this.startTime);
        }else {
            this.CurrTime = this.AllTime;
        }
        if(this.CurrTime > 0){
            this.timer.loop(1000,this,this.countDown);
        }
    };

    proto.countDown = function()
    {
        if(this.CurrTime <= 0)
        {
            this.clearChongzi();
            return;
        }
        this.CurrTime --;
        this.changeProgress();
    };

    proto.changeProgress = function(){
        if(this.CurrTime > 0){
            this.progress.visible = true;
            this.progress.value = this.CurrTime/this.AllTime;
        }
    };

    proto.clearChongzi = function() {
        this.timer.clear(this,this.countDown);
        this.removeSelf();
        ChongziManager.instance().removeChongzi(this);
    };



})();