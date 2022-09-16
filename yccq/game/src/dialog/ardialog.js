/**
 * Created by 41496 on 2017/4/28.
 */
//醇化室弹出框
(function(){
    var self = null;
    function ARDialog()
    {
        ARDialog.__super.call(this);
        self = this;
        this.name = 'chdialog';
        this.selectedIndex = null;
        this.StatusFlag = ['bakeroom/dengdai.png','agingroom/chunhuazhong.png'];
        this.StatusText = [this.status_0,this.status_1,this.status_2,this.status_3];
        this.nameList = [this.name_1,this.name_2,this.name_3,this.name_4];
        this.Building = this.stage.getChildByName("MyGame").AgingRoom;
        this.ItemList = [this.left_item0,this.left_item1,this.left_item2,this.left_item3];
        this.Lists = [this.List0,this.List1,this.List2,this.List3,this.List4];
        for(var i = 0,len = this.Lists.length; i < len; i++)
        {
            this.Lists[i].scrollBar.hide = true;//隐藏列表的滚动条。
            this.Lists[i].scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
            this.Lists[i].scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
        }

        this.tab.selectHandler = this.view_stack.setIndexHandler;
        this.tab.selectedIndex = 0;
        this.tab.on(Laya.Event.CLICK,this,function(){
            if(ZhiYinManager.step1 == 6 && ZhiYinManager.step2 == 0)
            {
                ZhiYinMask.instance().setZhiYin(1);
            }
        });

        this.view_stack.on(Laya.Event.CLICK,this, function(){
            if(ZhiYinManager.step1 == 6 && ZhiYinManager.step2 == 0)
            {
                ZhiYinMask.instance().setZhiYin(2);
            }
        });

        //醇化状态
        this.status = false;
        this.Level = this.Building.Level;

        //醇化按钮事件
        this.Aging_btn.clickHandler = new Laya.Handler(this,this.onAgingBtnClick);

        //加速按钮事件
        this.speedup_btn.clickHandler = new Laya.Handler(this,this.onSpeedUpBtnClick);

        //领取按钮事件
        this.lingqu_btn.clickHandler = new Laya.Handler(this,this.onLingquBtnClick);

        //升级按钮事件
        //this.upgrade1_btn.on(Laya.Event.CLICK,this,this.onUpgradeBtnClick,[1]);
        //this.upgrade2_btn.on(Laya.Event.CLICK,this,this.onUpgradeBtnClick,[2]);

        /*switch(Number(this.Level))
        {
            case 2:
                this.upgrade1_btn.visible = false;
                break;
            case 3:
                this.upgrade1_btn.visible = false;
                this.upgrade2_btn.visible = false;
        }*/

        //取消叶子点击事件
        for(var i = 0; i < this.ItemList.length; i++)
        {
            this.ItemList[i].on(Laya.Event.CLICK,this,this.onItemClick,[i]);
        }

        this.help_btn.clickHandler = new Laya.Handler(this,function(){
            var dialog = new GuideBook(4);
            dialog.popup();
        });

        this.getTobacco();
        this.initAgingRoom();
        this.timer.frameLoop(1,this,this.updateTime);
        //this.timer.loop(30,this,this.updateTime);
    }
    Laya.class(ARDialog,"ARDialog",ARDialogUI);
    var proto = ARDialog.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 6)
        {
            ZhiYinMask.instance().ZhiYinDialog();
        }
    };

    proto.onClosed = function()
    {
        if(ZhiYinManager.step1 == 7) {
            ZhiYinManager.instance().showZhiYin();
            ZhiYinMask.instance().close();
        }
    };

    proto.initAgingRoom = function()
    {
        for(var i = 0; i < this.Building.ItemList.length; i++)
        {
            if(this.Building.ItemList[i].shopid)
            {
                var sprite = new Laya.Image(ItemInfo[this.Building.ItemList[i].shopid].thumb);
                this.nameList[i].text = ItemInfo[this.Building.ItemList[i].shopid].name;
                sprite.size(64,64);
                sprite.anchorX = 0.5;
                sprite.anchorY = 0.5;
                sprite.pos(this.ItemList[i].width/2,this.ItemList[i].height/2);
                this.ItemList[i].addChild(sprite);
                if(this.Building.ItemList[i].done)
                {
                    sprite.skin = ItemInfo[ItemInfo[this.Building.ItemList[i].shopid].mubiao].thumb;
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

        Utils.post("store/lists",{uid:localStorage.GUID,type1:"yanye_kao"},this.onDataReturn);
    };

    proto.onDataReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            var list_data = {'1':[],'2':[],'3':[],'4':[],'5':[]};
            var work_data = {nowTime:Utils.strToTime(res.time),list:[]};
            for(var i = 0; i < res.data.length; i++)
            {
                if(Number(res.data[i].total) > 0)
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
        var sprite = new Laya.Image(ItemInfo[Item.dataSource.id].thumb);
        this.nameList[this.selectedIndex].text = ItemInfo[Item.dataSource.id].name;
        sprite.size(64,64);
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

    proto.onAgingBtnClick = function()
    {
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
        var send_data = {uid:localStorage.GUID,aging_index:index.join(','),shopid:ids.join(',')};
        console.log(send_data);
        Utils.post('aging/aging_start',send_data,this.onStartAgingReturn,onHttpErr);
    };

    proto.onStartAgingReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            for(var i = 0; i < res.data.length; i++)
            {
                self.Building.setItem(res.data[i].start_time,res.data[i].stop_time,res.time,res.data[i].before_shopid,res.data[i].aging_index);
            }

            self.clearGou();

            if(ZhiYinManager.step1 == 6 && ZhiYinManager.step2 == 0)
            {
                ZhiYinManager.instance().setGuideStep(6,1);
                ZhiYinMask.instance().ZhiYinDialog();
            }

        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }

    };

    proto.onSpeedUpBtnClick = function()
    {
        console.log(this.Building.ItemList[this.selectedIndex].status);

        if(this.selectedIndex != null && this.Building.ItemList[this.selectedIndex].status == 1){
            console.log('加速');
            var ledou_num = Number(this.need_ledou.text);
            if(ZhiYinManager.step1 == 6 && ZhiYinManager.step2 == 1)
            {
                ledou_num = 0;
            }

            var dialog = new Confirm1('加速需要消耗'+ledou_num+"闪电");
            dialog.popup();
            dialog.closeHandler = new Laya.Handler(this,function(name){
                if(name == Laya.Dialog.YES){
                    if(this.stage.getChildByName("MyGame").UI.subShandian(ledou_num))
                    {
                        Utils.post('aging/aging_jiasu',{uid:localStorage.GUID,aging_index:self.selectedIndex},function(res,index){
                            if(res.code == 0){
                                self.endAging();
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
            var dialog = new CommomConfirm('请先选择醇化中的烟叶');
            dialog.popup();
        }
    };

    proto.onUpgradeBtnClick = function(type)
    {

        if(this.Level < 3)
        {
            var UpgradeConfirm = new Confirm1("使用"+config.AgingRoomUpgrade[this.Level]+"银元增加一个生产位");
            UpgradeConfirm.popup();
            UpgradeConfirm.closeHandler = new Laya.Handler(this,function(name){
                console.log(name);
                if(name == Dialog.YES)
                {
                    if(this.stage.getChildByName("MyGame").UI.subGlod(config.AgingRoomUpgrade[this.Level]))
                    {
                        console.log('升级');
                        Utils.post('yanye/upgrade_aging',{uid:localStorage.GUID},this.onUpgradeReturn,onHttpErr);
                    }
                    else
                    {
                        var dialog = new CommomConfirm("银元不足！");
                        dialog.popup();
                    }
                }
            });
        }
    };

    proto.onUpgradeReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            self.stage.getChildByName("MyGame").initUserinfo();
            self.Level++;
            self.Building.Level++;
            switch(self.Level)
            {
                case 2:
                    self.upgrade1_btn.visible = false;
                    break;
                case 3:
                    self.upgrade2_btn.visible = false;
                    break;
            }
        }else {
            var dialog = new Laya.CommomConfirm(res.msg);
            dialog.popup();
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
        Utils.post('aging/aging_gather',{uid:localStorage.GUID,aging_index:index_str},function(res){
            console.log(res);
            if(res.code == 0){
                for(var j = 0; j < indexs.length; j++){
                    self.Building.clear(indexs[j]);
                    self.ItemList[indexs[j]].removeChildren(0);
                    self.nameList[indexs[j]].text = '';
                }
                var items = [];
                for(var i = 0; i < res.data.list.length; i++)
                {
                    items.push(res.data.list[i].after_shopid);
                }
                getItem(items);
				//获得抽奖机会
                /*if(Number(res.data.draws_times) == 1) {
                    var draw = new Draws();
                    draw.init(1610);
                    draw.popup();
                }*/

                if(res.data.suipian.length){
                    var suipian_tips = new FragmentGetTips(res.data.suipian);
                    suipian_tips.popup()
                }

                self.stage.getChildByName("MyGame").initUserinfo();

                if(ZhiYinManager.step1 == 6 && ZhiYinManager.step2 == 2){
                    ZhiYinManager.instance().setGuideStep(7,0,true);
                    ZhiYinMask.instance().ZhiYinClose();
                }

            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
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
        if(ZhiYinManager.step1 == 6 && ZhiYinManager.step2 == 2){
            this.selectedIndex = null;
        }
        if(this.selectedIndex == null) this.onItemClick(0);
    };

    //醇化
    proto.Aging = function()
    {
        this.status = true;
        this.Aging_btn.selected = true;

        this.timerLoop(300,this,this.updateTime);

        this.Countdown.visible = true;
        this.setNeedLeDou();
    };

    proto.endAging = function()
    {
        /*this.clearTimer(this,this.updateTime);
        this.Countdown.changeText('');
        this.Countdown.visible = false;

        //this.Aging_btn.label = "醇化";
        this.Aging_btn.selected = false;
        this.Aging_btn.visible = false;
        this.lingqu_btn.visible = true;

        this.status = false;*/

        if(ZhiYinManager.step1 == 6 && ZhiYinManager.step2 == 1)
        {
            ZhiYinManager.instance().setGuideStep(6,2,true);
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
                    this.ItemList[i].getChildAt(0).skin = ItemInfo[ItemInfo[this.Building.ItemList[i].shopid].mubiao].thumb;
                    this.nameList[i].text = ItemInfo[ItemInfo[this.Building.ItemList[i].shopid].mubiao].name;
                }

                if(ZhiYinManager.step1 == 6 && ZhiYinManager.step2 == 1){
                    ZhiYinManager.instance().setGuideStep(6,2);
                    ZhiYinMask.instance().ZhiYinDialog();
                }
            }
        }
        this.setNeedLeDou();
        this.setBakeBtn();
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

    proto.setNeedLeDou = function()
    {

        var time = this.Building.ItemList[this.selectedIndex].CurrTime;
        var num = count_shandian(time);
        if(ZhiYinManager.step1 == 6){
            num = 0;
        }
        this.need_ledou.changeText(num);
    };

    proto.setBakeBtn = function()
    {
        if(this.selectedIndex == null) return;
        if(this.Building.ItemList[this.selectedIndex].shopid == null && this.ItemList[this.selectedIndex].numChildren)
        {
            this.Aging_btn.disabled = false;
        }else {
            this.Aging_btn.disabled = true;
        }

        if(this.Building.ItemList[this.selectedIndex].status == 2)
        {
            this.Aging_btn.visible = false;
            this.lingqu_btn.visible = true;
        }else {
            this.Aging_btn.visible = true;
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

})();