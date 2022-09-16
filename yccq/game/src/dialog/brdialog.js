/**
 * Created by 41496 on 2017/4/26.
 */
//烘烤室弹出框
(function(){
    var self = null;
    function BRDialog()
    {
        BRDialog.__super.call(this);
        self = this;
        this.name ='hkdialog';
        this.selectedIndex = null;
        this.StatusFlag = ['bakeroom/dengdai.png','bakeroom/hongkaozhong.png'];

        this._fireTime = 0;
        this._jiasuTime = 0;
        this._destroyTime = 0;
        this._nowTime = 0;
        this._fireNumber = null;
        this.stop_btn.clickHandler = new Laya.Handler(this,this.outFire);

        this.StatusText = [this.status_0,this.status_1,this.status_2,this.status_3];
        this.nameList = [this.name_1,this.name_2,this.name_3,this.name_4];
        this.Building = this.stage.getChildByName("MyGame").BakingRoom;
        this.ItemList = [this.Item0,this.Item1,this.Item2,this.Item3];
        this.Lists = [this.List0,this.List1,this.List2,this.List3,this.List4];

        for(var i = 0,len = this.Lists.length; i < len; i++)
        {
            this.Lists[i].scrollBar.hide = true;//隐藏列表的滚动条。
            this.Lists[i].scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
            this.Lists[i].scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
        }

        //绑定tab、viewstack
        this.tab.selectHandler = this.view_stack.setIndexHandler;
        this.tab.selectedIndex = 0;
        this.tab.on(Laya.Event.CLICK,this,function(){
            if(ZhiYinManager.step1 == 5 && ZhiYinManager.step2 == 0)
            {
                ZhiYinMask.instance().setZhiYin(1);
            }
        });

        this.view_stack.on(Laya.Event.CLICK,this, function(){
            if(ZhiYinManager.step1 == 5 && ZhiYinManager.step2 == 0)
            {
                ZhiYinMask.instance().setZhiYin(2);
            }
        });


        //烘烤状态
        this.status = false;
        this.Level = this.Building.Level;

        //烘烤按钮事件
        this.Baking_btn.clickHandler = new Laya.Handler(this,this.onBakingBtnClick);

        //领取按钮事件
        this.lingqu_btn.clickHandler = new Laya.Handler(this,this.onLingquBtnClick);

        this.speedup_btn.clickHandler = new Laya.Handler(this,this.onSpeedUpBtnClick);

        this.help_btn.clickHandler = new Laya.Handler(this,function(){
            var dialog = new GuideBook(2);
            dialog.popup();
        });


        //烘烤槽点击事件
        for(var i = 0; i < this.ItemList.length; i++)
        {
            this.ItemList[i].on(Laya.Event.CLICK,this,this.onItemClick,[i]);
        }

        this.getTobacco();
        this.initBakingRoom();
        this.getFireStatus();
        //this.getTarget();
        this.timer.frameLoop(1,this,this.updateTime);
        //this.timer.loop(30,this,this.updateTime);

        this.closeHandler = new Laya.Handler(this,this.onDialogClose);
    }
    Laya.class(BRDialog,"BRDialog",BakingRoomUI);
    var proto = BRDialog.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 5)
        {
            ZhiYinMask.instance().ZhiYinDialog();
        }
    };

    proto.onClosed = function()
    {
        if(ZhiYinManager.step1 == 6) {
            ZhiYinManager.instance().showZhiYin();
            ZhiYinMask.instance().close();
        }
    };

    proto.initBakingRoom = function()
    {
        for(var i = 0; i < this.Building.ItemList.length; i++)
        {
            if(this.Building.ItemList[i].shopid)
            {
                var sprite = new Laya.Image("bakeroom/normal_"+this.Building.ItemList[i].shopid+".png");
                this.nameList[i].text = ItemInfo[this.Building.ItemList[i].shopid].name;
                sprite.anchorX = 0.5;
                sprite.anchorY = 0.5;
                sprite.pos(this.ItemList[i].width/2,this.ItemList[i].height/2);
                this.ItemList[i].addChild(sprite);
                if(this.Building.ItemList[i].done)
                {
                    sprite.skin = "bakeroom/kao_"+this.Building.ItemList[i].shopid+".png";
                }
            }

            if(this.Building.ItemList[i].status == 1)
            {
                this.onItemClick(i);
            }
        }
        if(this.selectedIndex == null) this.getTarget();
    };

    proto.getTobacco = function()
    {
        Utils.post("store/lists",{uid:localStorage.GUID,type1:"yanye"},this.onDataReturn);
    };

    proto.onDataReturn = function(res)
    {
        if(res.code == 0)
        {
            var list_data = {'1':[],'2':[],'3':[],'4':[],'5':[]};
            var work_data = {nowTime:Utils.strToTime(res.time),list:[]};
            for(var i = 0; i < res.data.length; i++)
            {
                if(Number(res.data[i].total))
                {
                    list_data[res.data[i].type2].push({id:res.data[i].shopid,icon:ItemInfo[res.data[i].shopid].thumb,name:ItemInfo[res.data[i].shopid].name,num:res.data[i].total});
                }
            }
            self.List0.array = list_data['1'];
            self.List1.array = list_data['2'];
            self.List2.array = list_data['3'];
            self.List3.array = list_data['4'];
            self.List4.array = list_data['5'];
            self.view_stack.visible = true;
            self.List0.renderHandler = new Laya.Handler(self, self.updateItem);
            self.List1.renderHandler = new Laya.Handler(self, self.updateItem);
            self.List2.renderHandler = new Laya.Handler(self, self.updateItem);
            self.List3.renderHandler = new Laya.Handler(self, self.updateItem);
            self.List4.renderHandler = new Laya.Handler(self, self.updateItem);

            for( i in list_data)
            {
                if(list_data[i].length > 0){
                    self.tab.selectedIndex = i-1;
                }
            }

            self.checkList();
        }
    };

    proto.updateItem = function(cell, index)
    {
        cell.on(Laya.Event.CLICK,this,this.onListItemClick,[cell,index]);
        cell.on(Laya.Event.MOUSE_DOWN,this,onItemPress,[cell,index,cell.dataSource.id,false]);
        this.checkList();
    };

    proto.onListItemClick = function(Item,index)
    {
        if(this.selectedIndex == null || this.ItemList[this.selectedIndex].numChildren) this.getTarget();

        if(this.ItemList[this.selectedIndex].numChildren || Item.dataSource.num == 0) return;
        var sprite = new Laya.Image("bakeroom/normal_"+Item.dataSource.id+".png");
        this.nameList[this.selectedIndex].text = ItemInfo[Item.dataSource.id].name;
        sprite.anchorX = 0.5;
        sprite.anchorY = 0.5;
        sprite.pos(this.ItemList[this.selectedIndex].width/2,this.ItemList[this.selectedIndex].height/2);
        sprite.ItemData = Item.dataSource;
        sprite.Item = Item;
        sprite.index = index;
        Item.dataSource.num --;
        if(Item.dataSource.num <= 0)
        {
            Item.getChildByName('gou').visible = false;
            Item.parent.parent.deleteItem(index);
        }
        else
        {
            Item.getChildByName('gou').visible = true;
        }

        Item.parent.parent.refresh();
        this.ItemList[this.selectedIndex].addChild(sprite);

        this.setNeedLeDou();
        this.setBakeBtn();
    };

    proto.onBakingBtnClick = function()
    {
        //if(this.selectedIndex == null) return;
        //console.log(this.Building.ItemList[this.selectedIndex].status);
        var ids = [];
        var index = [];
        for(var i = 0; i < this.ItemList.length; i++)
        {
            var item = this.ItemList[i];
            if(item.numChildren && this.Building.ItemList[i].shopid == null){

                ids.push(item.getChildAt(0).ItemData.id);
                index.push(i);
            }
        }
        var send_data = {uid:localStorage.GUID,bake_index:index.join(','),shopid:ids.join(',')};
        console.log(send_data);
        Utils.post('bake/bake_start',send_data,this.onStartBakeReturn,onHttpErr);


    };

    proto.onStartBakeReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            for(var i = 0; i < res.data.length; i++)
            {
                self.Building.setItem(res.data[i].start_time,res.data[i].stop_time,res.time,res.data[i].before_shopid,res.data[i].bake_index);
            }

            self.clearGou();

            if(ZhiYinManager.step1 == 5 && ZhiYinManager.step2 == 0)
            {
                ZhiYinManager.instance().setGuideStep(5,1);
                ZhiYinMask.instance().ZhiYinDialog();
            }

        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }

    };

    proto.onUpgradeBtnClick = function(type)
    {

        if(this.Level < 3)
        {
            var UpgradeConfirm = new Confirm1("升级需要消耗"+config.BakingRoomUpgrade[this.Level]+"乐豆");
            UpgradeConfirm.popup();
            UpgradeConfirm.closeHandler = new Laya.Handler(this,function(name){
                console.log(name);
                if(name == Dialog.YES)
                {
                    if(this.stage.getChildByName("MyGame").UI.subBean(config.BakingRoomUpgrade[this.Level]))
                    {
                        console.log('升级');
                        this.stage.getChildByName("MyGame").initUserinfo();
                        this.Level++;
                        this.Building.Level++;
                        switch(this.Level)
                        {
                            case 2:
                                this.upgrade1_btn.visible = false;
                                break;
                            case 3:
                                this.upgrade2_btn.visible = false;
                                break;
                        }
                    }
                    else
                    {
                        var dialog = new CommomConfirm("乐豆不足！");
                        dialog.popup();
                    }
                }
            });
        }
    };

    proto.onLingquBtnClick = function()
    {
        //if(this.selectedIndex == null) return;
        var indexs = [];
        for(var i = 0; i < this.ItemList.length; i++){
            if(this.Building.ItemList[i].status == 2){
                indexs.push(i);
            }
        }
        var index_str = indexs.join(',');
        console.log(index_str);
        Utils.post('bake/bake_gather',{uid:localStorage.GUID,bake_index:index_str},function(res){
            console.log(res);
            if(res.code == 0){
                for(var j = 0; j < indexs.length; j++){
                    self.Building.clear(indexs[j]);
                    self.ItemList[indexs[j]].removeChildren(0);
                    self.nameList[indexs[j]].text = '';
                }
                var items = [];
                var failed = [];
                var reduce = [];
                for(var j = 0; j < res.data.false.length; j++){
                    failed.push(res.data.false[j].after_shopid);
                }
                for(var r = 0; r < res.data.reduce.length; r++){
                    reduce.push(res.data.reduce[r].after_shopid);
                }
                if(failed.length || reduce.length){
                    var yanye_names = [];
                    var reduce_names = [];
                    var text = '';
                    for(var f = 0; f < failed.length; f++){
                        yanye_names.push(ItemInfo[failed[f]].name);
                    }
                    for(var k = 0; k < reduce.length; k++){
                        reduce_names.push(ItemInfo[Number(reduce[k])+10].name);
                        items.push(reduce[k]);
                    }
                    console.log(failed,reduce);
                    if(failed.length){
                        text += yanye_names.join(',');
                        text += '已被大火毁坏。';
                    }
                    if(reduce.length){
                        text += reduce_names.join(',');
                        text += '已被降级。';
                    }

                    var dialog = new CommomConfirm(text);
                    dialog.popup();
                }
                for(var i = 0; i < res.data.success.length; i++)
                {
                    items.push(res.data.success[i].after_shopid);
                }
                getItem(items);
				//获得抽奖机会
				/*
                if(Number(res.data.draws_times) == 1) {
                    var draw = new Draws();
                    draw.init(1610);
                    draw.popup();
                }*/

                if(res.data.suipian.length){
                    var suipian_tips = new FragmentGetTips(res.data.suipian);
                    suipian_tips.popup()
                }

                self.stage.getChildByName("MyGame").initUserinfo();

                if(ZhiYinManager.step1 == 5 && ZhiYinManager.step2 == 2){
                    ZhiYinManager.instance().setGuideStep(6,0,true);
                    ZhiYinMask.instance().ZhiYinClose();
                }

            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);

    };

    //烘烤
    proto.Baking = function()
    {
        this.status = true;
        //this.Baking_btn.label = "烘烤中";
        this.Baking_btn.selected = true;

        this.timerLoop(500,this,this.updateTime);

        this.Countdown.visible = true;
        this.setNeedLeDou();
    };

    proto.endBaking = function()
    {
        if(ZhiYinManager.step1 == 5 && ZhiYinManager.step2 == 1)
        {
            ZhiYinManager.instance().setGuideStep(5,2,true);
            ZhiYinMask.instance().ZhiYinDialog();
        }
    };

    proto.updateTime = function()
    {
        this.checkStatus();
        if(this.selectedIndex == null) return;
        if(this.Building.ItemList[this.selectedIndex].status == 0 && this.Building.ItemList[this.selectedIndex].shopid){
            this.Countdown.changeText('等待中');
            this.timeprogress.value = 1;
        }else if(this.Building.ItemList[this.selectedIndex].AllTime){
            this.Countdown.changeText(Utils.formatSeconds(this.Building.ItemList[this.selectedIndex].CurrTime));
            this.timeprogress.value = this.Building.ItemList[this.selectedIndex].CurrTime/this.Building.ItemList[this.selectedIndex].AllTime;
        }else {
            this.Countdown.changeText('');
            this.timeprogress.value = 0;
        }

        for(var i = 0; i < this.Building.ItemList.length; i++)
        {
            if(this.Building.ItemList[i].status == 2)
            {
                //console.log(this.ItemList[i].getChildAt(0));
                if(this.ItemList[i].numChildren){
                    this.ItemList[i].getChildAt(0).skin = "bakeroom/kao_"+this.Building.ItemList[i].shopid+".png";
                    this.nameList[i].text = ItemInfo[ItemInfo[this.Building.ItemList[i].shopid].mubiao].name;
                }
                if(ZhiYinManager.step1 == 5 && ZhiYinManager.step2 == 1){
                    ZhiYinManager.instance().setGuideStep(5,2);
                    ZhiYinMask.instance().ZhiYinDialog();
                }
            }
        }
        this.setNeedLeDou();
        this.setBakeBtn();
    };

    proto.onItemClick = function(index)
    {
        if(this.selectedIndex == index)
        {
            //取消
            var Item = this.ItemList[index];
            if(this.Building.ItemList[index].shopid == null && Item.numChildren)
            {
                console.log("取消");
                //this.List.getItem(Item.getChildAt(0).ItemIndex).num += 1;
                var count = 0;
                for(var i = 0; i < this.ItemList.length; i++){
                    if(this.ItemList[i].getChildAt(0) && this.ItemList[i].getChildAt(0).ItemData)
                    {
                        if(Item.getChildAt(0).ItemData.id == this.ItemList[i].getChildAt(0).ItemData.id)
                        {
                            count ++;
                        }
                    }
                }
                if(count == 1){
                    Item.getChildAt(0).Item.getChildByName('gou').visible = false;
                }
                Item.getChildAt(0).ItemData.num += 1;
                if(Item.getChildAt(0).ItemData.num == 1) Item.getChildAt(0).Item.parent.parent.addItemAt(Item.getChildAt(0).ItemData,Item.getChildAt(0).index);
                Item.getChildAt(0).Item.parent.parent.refresh();
                Item.removeChildren();
                this.nameList[index].text = '';
                this.setNeedLeDou();
            }
        }
        else
        {
            this.selectedIndex = index;
            this.item_selected.visible = true;
            this.item_selected.pos(this.ItemList[index].x,this.ItemList[index].y);
        }
        this.setBakeBtn();
    };

    proto.clearGou = function()
    {
        for(var i = 0; i < this.ItemList.length; i++){
            if(this.ItemList[i].numChildren && this.ItemList[i].getChildAt(0).Item)
            {
                this.ItemList[i].getChildAt(0).Item.getChildByName('gou').visible = false;
            }
        }
    };

    proto.getTarget = function()
    {
        for(var i = 0; i < this.ItemList.length; i++)
        {
            if(!this.ItemList[i].numChildren){
                this.onItemClick(i);
                break;
            }
        }
        if(ZhiYinManager.step1 == 5 && ZhiYinManager.step2 == 2){
            this.selectedIndex = null;
        }
        if(this.selectedIndex == null) this.onItemClick(0);
    };

    //检测列表是否有物品，没有则提示
    proto.checkList = function()
    {
        for(var i = 0; i < this.Lists.length; i++)
        {
            if(!this.Lists[i].length){
                this.Lists[i].getChildByName('tips').visible = true;
            }else {
                this.Lists[i].getChildByName('tips').visible = false;
            }
        }
    };

    proto.onSpeedUpBtnClick = function()
    {
        console.log(this.Building.ItemList[this.selectedIndex].status);
       
        if(this.selectedIndex != null && this.Building.ItemList[this.selectedIndex].status == 1){
            console.log('加速');
            var ledou_num = Number(this.need_ledou.text);
            if(ZhiYinManager.step1 == 5 && ZhiYinManager.step2 == 1)
            {
                ledou_num = 0;
            }

            var dialog = new Confirm1('加速需要消耗'+ledou_num+"闪电");
            dialog.popup();
            dialog.closeHandler = new Laya.Handler(this,function(name){
                if(name == Laya.Dialog.YES){
                    if(this.stage.getChildByName("MyGame").UI.subShandian(ledou_num))
                    {
                        Utils.post('bake/bake_jiasu',{uid:localStorage.GUID,bake_index:self.selectedIndex},function(res,index){
                            if(res.code == 0){
                                self.endBaking();
                                self.Building.stop(index);
                                self.Building.countDown();
                                if(self.Building.target) self.onItemClick(self.Building.target);
                                self.stage.getChildByName("MyGame").initUserinfo();
                            }else {
                                var dialog = new CommomConfirm(res.msg);
                                dialog.popup();
                            }

                        },onHttpErr,self.selectedIndex);
                    }
                    else
                    {
                        var dialog = new RechargeDialog('shandian');
                        dialog.popup();
                    }

                }

            });

        }else{
            var dialog = new CommomConfirm('请先选择烘烤中的烟叶');
            dialog.popup();
        }
    };

    proto.setNeedLeDou = function()
    {
        var time = this.Building.ItemList[this.selectedIndex].CurrTime;
        var num = count_shandian(time);
        if(ZhiYinManager.step1 == 5){
            num = 0;
        }
        this.need_ledou.changeText(num);
    };

    proto.setBakeBtn = function()
    {
        
        if(this.selectedIndex == null) return;
        if(this.Building.ItemList[this.selectedIndex].shopid == null && this.ItemList[this.selectedIndex].numChildren)
        {
            this.Baking_btn.disabled = false;
        }else {
            this.Baking_btn.disabled = true;
        }

        if(this.Building.ItemList[this.selectedIndex].status == 2)
        {
            this.Baking_btn.visible = false;
            this.lingqu_btn.visible = true;

        }else {
            this.Baking_btn.visible = true;
            this.lingqu_btn.visible = false;
        }
    };

    proto.checkStatus = function()
    {
        for(var i = 0; i < this.Building.ItemList.length; i++)
        {
            if(this.Building.ItemList[i].status == 0 && this.Building.ItemList[i].shopid){
                this.StatusText[i].visible = true;
                this.StatusText[i].skin = this.StatusFlag[0];
            }else if(this.Building.ItemList[i].status == 1){
                this.StatusText[i].visible = true;
                this.StatusText[i].skin = this.StatusFlag[1];
            }else {
                this.StatusText[i].visible = false;
            }
        }
    };

    proto.getFireStatus = function()
    {
        var self = this;
        Utils.post('fire/fire_status',{uid:localStorage.GUID},function(res){
            if(res.code == '0'){
                if(res.data){
                    self.fireStart(res.data.start_time,res.data.stop_time,res.data.jiasu_time,res.data.destroy_time,res.time,res.data.number);
                    self.fire_name.text = res.data.nickname+'给你添柴';
                }
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },null);
    };
    //灭火
    proto.outFire = function()
    {
        if(!this._fireNumber) return;
        var self = this;
        Utils.post('fire/outfire',{uid:localStorage.GUID,number:this._fireNumber},function(res){
            if(res.code == '0'){
                self.clearCountDown();
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.fireStart = function(start,stop,jiasu,destroy,now,number){
        this._fireNumber = number;
        var start_time = Utils.strToTime(start);
        var stop_time = Utils.strToTime(stop);
        var jiasu_time = Utils.strToTime(jiasu);
        var destroy_time = Utils.strToTime(destroy);
        var now_time = Utils.strToTime(now);
        this._nowTime = now_time;
        var all_time = stop_time - start_time;
        this._jiasuTime = jiasu_time;
        this._destroyTime = destroy_time;
        this._fireTime = all_time - (now_time - start_time);
        if(this._fireTime > 0){
            this.clearCountDown();
            this.timer.loop(1000,this,this.fireCountDown);
            this.fire_box.visible = true;
        }
        this.changeFireTips();
    };

    proto.fireCountDown = function()
    {
        this._fireTime--;
        this._nowTime ++;
        this.changeFireTips();
        if(this._fireTime<=0){
            this._fireTime = 0;
            this.clearCountDown();
        }
    };

    proto.changeFireTips = function()
    {
        if(this._nowTime <= this._jiasuTime){
            this.fire_text.text = '加速烘烤时长剩余'+Utils.formatSeconds(this._jiasuTime - this._nowTime);
        }else if(this._nowTime <= this._destroyTime) {
            this.fire_text.text = '火势过大，请在'+Utils.formatSeconds(this._destroyTime - this._nowTime)+'内灭火，否则可能造成烟叶毁坏或降级。';
        }else {
            this.fire_text.text = '火势过大,请及时灭火\n剩余'+Utils.formatSeconds(this._fireTime);
        }
    };

    proto.clearCountDown = function(){
        this.timer.clear(this,this.fireCountDown);
        this.fire_box.visible = false;
    };

    proto.onDialogClose = function(){
        this.clearCountDown();
    };

})();