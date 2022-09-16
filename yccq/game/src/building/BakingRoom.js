/**
 * Created by 41496 on 2017/4/26.
 */
(function(){
    function  BakeItem() {
        BakeItem.__super.call(this);
        this.AllTime = 0;
        this.CurrTime = 0;
        this.shopid = null;
        this.status = 0;
    }
    Laya.class(BakeItem,'BakeItem',Laya.Node);
    var p = BakeItem.prototype;


    //烘烤室建筑类
    function BakingRoom(data,now_time,type)
    {
        BakingRoom.__super.call(this);
        this.initBuilding(building.BakingRoom,"tex/hongkaoshi_text.png");
        this.pivot(Math.floor(this.width/2),240);
       
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);
            //this.on(Laya.Event.MOUSE_UP,this,this.onClick);
            //this.collectiblePos = [this.width/2+40,this.height/2+40];
            //this.setStatus(data,now_time);

        }
        this.target = null;
        this.ItemList = [];
        for(var i = 0; i < 4; i++){
            this.ItemList.push(new BakeItem());
        }

        this.timer.loop(1000,this,this.countDown);
        this.timer.loop(500,this,this.changeProgress);

    }
    Laya.class(BakingRoom,"BakingRoom",Building);
    var proto = BakingRoom.prototype;

    proto.initBake = function(bake)
    {
        for(var i = 0; i < bake.data.length; i ++)
        {
            if(bake.data[i].status == 1){
                this.setItem(bake.data[i].start_time,bake.data[i].stop_time,bake.time,bake.data[i].before_shopid,bake.data[i].bake_index);
            }
        }
        //this.getTarget();
        //this.start(this.target);

    };

    proto.onClick = function(e)
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('烘烤室');
        switch(this.status)
        {
            case 2:
                console.log("收取");
                //this.Gather("yanye/bake_gather");
                //break;
            case 1:
                // var dialog = new CommomConfirm("正在烘烤中，请稍后再来！");
                // dialog.popup();
                // break;
            case 0:
                var dialog = new BRDialog();
                dialog.popup();
                break;
        }
    };

    proto.setItem = function(startTime, stopTime, nowTime, shopid, index)
    {
        var item = this.ItemList[index];
        item.shopid = shopid;
        var start_time = Utils.strToTime(startTime);
        var stop_time = Utils.strToTime(stopTime);
        if(!nowTime) nowTime = startTime;
        var now_time = Utils.strToTime(nowTime);
        item.startTime = start_time;
        item.stopTime = stop_time;
        item.AllTime = stop_time - start_time;
        if(now_time >= start_time){
            item.CurrTime = item.AllTime - (now_time - start_time);
        }else {
            item.CurrTime = item.AllTime;//
        }

        if(this.target == null || this.ItemList[this.target].CurrTime <= 0)
        {
            this.getTarget();
            this.start(this.target);
        }

        if(stop_time <= now_time){
            this.stop(index)
        }

    };

    proto.start = function(index)
    {
        if(index == null) return;
        var item = this.ItemList[index];
        item.status = 1;
    };

    proto.stop = function(index)
    {
        var item = this.ItemList[index];
        item.CurrTime = 0;
        item.status = 2;
    };

    proto.clear = function(index)
    {
        var item = this.ItemList[index];
        item.AllTime = 0;
        item.CurrTime = 0;
        item.startTime = 0;
        item.stopTime = 0;
        item.shopid = null;
        item.status = 0;
    };

    proto.countDown = function()
    {
        if(this.target == null || this.ItemList[this.target].CurrTime <= 0)
        {
            this.getTarget();
            this.start(this.target);
            return;
        }
        var item = this.ItemList[this.target];
        item.CurrTime --;

        //console.log(this.target,item.CurrTime);
        if(item.CurrTime <= 0)
        {
            this.stop(this.target);
        }
    };

    proto.getTarget = function()
    {
        var target = null;
        for(var i = 0; i < this.ItemList.length; i++)
        {
            if(this.ItemList[i].status == 0 && this.ItemList[i].shopid)
            {
                if(target == null || this.ItemList[i].startTime < this.ItemList[target].startTime)
                {
                    target = i;
                }
            }
        }
        this.target = target;
    };

    proto.changeProgress = function(){
        if(this.ItemList[this.target] && this.ItemList[this.target].CurrTime > 0){
            this.progress.visible = true;
            this.progress.value = this.ItemList[this.target].CurrTime/this.ItemList[this.target].AllTime;
        }else {
            this.progress.visible = false;
        }

    }

})();