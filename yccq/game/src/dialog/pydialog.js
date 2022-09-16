/**
 * Created by 41496 on 2017/5/15.
 */
(function(){
    var self = null;
    function PeiyushiDialog()
    {
        PeiyushiDialog.__super.call(this);
        self = this;
        this.name = 'peiyushi';
        this.BoxList = [this.item1,this.item2,this.item3,this.item4,this.item5,this.item6];
        //this.UpgradeBtn = [this.Upgrade1,this.Upgrade2,this.Upgrade3];
        this.progress = [this.progress1,this.progress2,this.progress3,this.progress4,this.progress5,this.progress6];
        this.countdowns = [this.countdown1,this.countdown2,this.countdown3,this.countdown4,this.countdown5,this.countdown6];
        this.Lists = [this.list0,this.list1,this.list2,this.list3,this.list4];


        for(var i = 0,len = this.BoxList.length; i < len; i++)
        {
            this.BoxList[i].on(Laya.Event.CLICK, this, this.onBoxSelect,[this.BoxList[i]]);
            this.BoxList[i].BoxIndex = i;
            this.BoxList[i].status = false;
            this.BoxList[i].usable = false;
            this.BoxList[i].countdown = function(BoxIndex)
            {
                if(this.BoxList[BoxIndex].TimeNum <= 0){
                    Laya.timer.clear(this,this.BoxList[BoxIndex].countdown);
                    this.BoxList[BoxIndex].getChildByName('seed').skin = ItemInfo[this.BoxList[BoxIndex].WorkData.seed].thumb;
                    this.BoxList[BoxIndex].getChildByName('leaf1').skin = null;
                    this.BoxList[BoxIndex].getChildByName('leaf1').ItemData = null;
                    this.BoxList[BoxIndex].getChildByName('leaf2').skin = null;
                    this.BoxList[BoxIndex].getChildByName('leaf2').ItemData = null;
                    this.progress[BoxIndex].value = 1;
                    this.countdowns[BoxIndex].text = '';
                }else {
                    this.BoxList[BoxIndex].TimeNum -= 1;
                    console.log(this.BoxList[BoxIndex].TimeNum);
                    this.progress[BoxIndex].value = this.BoxList[BoxIndex].TimeNum/this.BoxList[BoxIndex].WorkData.time;
                    this.countdowns[BoxIndex].text = Utils.formatSeconds(this.BoxList[BoxIndex].TimeNum);
                }

            }
        }

        this.selectedBox = null;
        this.Level = 3;

        //console.log(this.yanye_list);
        for(var i = 0,len = this.Lists.length; i < len; i++)
        {
            this.Lists[i].scrollBar.hide = true;//隐藏列表的滚动条。
            this.Lists[i].scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
            this.Lists[i].scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
            this.Lists[i].renderHandler = new Laya.Handler(this, this.updateItem);
        }


        //培育按钮事件
        this.peiyu_btn.clickHandler = new Laya.Handler(this,this.onPeiyuBtnClick);
        this.peiyu_btn.disabled = true;

        //检测培育按钮
        this.timerLoop(200,this,this.changePeiyuBtn);

        //关联tab和viewStack
        //this.tab_yanye.selectedIndex = 4;
        this.tab_yanye.selectHandler = this.view_stack.setIndexHandler;

        this.closeHandler = new Laya.Handler(this,this.onDialogClose);

        this.tab_peiyu.selectHandler = new Laya.Handler(this,this.onTabPeiyuSelected);

        this.help_btn.clickHandler = new Laya.Handler(this,function(){
            var dialog = new GuideBook(3);
            dialog.popup();
        });



        this.getPeiyuStatus();

        this.getTobacco();
    }
    Laya.class(PeiyushiDialog,"PeiyushiDialog",peiyushiUI);
    var proto = PeiyushiDialog.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.peiyu == 0)
        {
            this.tipsSetp = 0;
            this.tips_2_ele.skewY = 180;
            this.tips.on(Laya.Event.CLICK,this,this.nextTips);
            this.nextTips();
        }
    };

    proto.onClosed = function()
    {
        if(ZhiYinManager.peiyu == 0) {
            var tips = new tipsDialog();
            tips.content.innerHTML = '在这里能很快集生产原料，努力升级吧！还有更多惊喜在等着你！';
            tips.content.y = 100;
            tips.popup();
            tips.closeHandler = new Laya.Handler(this,function(){
                ZhiYinManager.peiyu = 1;
                Utils.post('Guide/close_tips',{uid:localStorage.GUID,building:'peiyu'},null);
            });
        }
    };

    proto.nextTips = function()
    {
        this.tipsSetp ++;
        if(this.tipsSetp <= 3)
        {
            this.tips.visible = true;
            if(this.tipsSetp > 1) this['tips_'+(this.tipsSetp-1)].visible = false;
            this['tips_'+this.tipsSetp].visible = true;
        }else {
            this.tips.visible = false;
        }
    };

    proto.getPeiyuStatus = function()
    {
        Utils.post("peiyu/status",{uid:localStorage.GUID},this.onStatusReturn,onHttpErr);
    };

    proto.onStatusReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            for(var i = 0,len = res.data.length; i < len; i++)
            {
                if(Number(res.data[i].status))
                {
                    self.initBox(res.data[i],res.time);
                }

            }
            self.setLevel(res.data.length);
        }
    };

    //设置等级
    proto.setLevel = function(Level)
    {
        this.Level = Level;
        switch(this.Level)
        {
            case 6:
                this.item6.usable = true;
                this.Upgrade3.visible = false;
            case 5:
                this.item5.usable = true;
                this.Upgrade2.visible = false;
            case 4:
                this.item4.usable = true;
                this.Upgrade1.visible = false;
            case 3:
                this.item1.usable = true;
                this.item2.usable = true;
                this.item3.usable = true;
                break;
        }
    };

    //初始化培育槽
    proto.initBox = function(data,now)
    {
        var box = this.BoxList[Number(data.number)-1];//培育槽

        box.status = true;//设置培育槽状态

        switch(Number(data.status))
        {
            case 1:
                var Items = [box.getChildByName('leaf1'),box.getChildByName('leaf2')];//两个叶子位

                var leaf_id = [data.yanye1,data.yanye2];
                for(var i = 0; i < 2; i++)
                {
                    Items[i].skin = ItemInfo[leaf_id[i]].thumb;
                    Items[i].ItemData = null;
                }
                var total_time = Utils.strToTime(data.stop_time) - Utils.strToTime(data.start_time);
                var curr_time = Utils.strToTime(now) - Utils.strToTime(data.start_time);
                this.startWork({time:total_time,seed:data.seed,TimeNum:total_time-curr_time},box.BoxIndex);
                break;
            case 2:
                box.WorkData = {seed:data.seed};
                box.getChildByName('seed').skin = ItemInfo[data.seed].thumb;
                break;
        }
    };

    proto.changePeiyuBtn = function()
    {
        if(!this.selectedBox) return;
        if(this.selectedBox.status) {
            this.peiyu_btn.disabled = true;
        }else {
            if(this.selectedBox.getChildByName('leaf1').skin && this.selectedBox.getChildByName('leaf2').skin){
                this.peiyu_btn.disabled = false;
            }else
            {
                this.peiyu_btn.disabled = true;
            }
        }

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
            for(var i = 0; i < res.data.length; i++)
            {
                if(Number(res.data[i].total))
                {

                    list_data[res.data[i].type2].push({id:res.data[i].shopid,icon:ItemInfo[res.data[i].shopid].thumb,name:ItemInfo[res.data[i].shopid].name,num:res.data[i].total});

                }
            }
            //self.yanye_list.array = list_data;
            self.list0.array = list_data['1'];
            self.list1.array = list_data['2'];
            self.list2.array = list_data['3'];
            self.list3.array = list_data['4'];
            self.list4.array = list_data['5'];
            self.view_stack.visible = true;

            for( i in list_data)
            {
                if(list_data[i].length > 0){
                    self.tab_yanye.selectedIndex = i-1;
                }
            }

            self.checkList();
        }
    };

    proto.updateItem = function(cell, index)
    {
        cell.on(Laya.Event.CLICK,this,this.onListItemClick,[cell,index]);
        cell.on(Laya.Event.MOUSE_DOWN,this,onItemPress,[cell,index,cell.dataSource.id,false]);
    };

    proto.onListItemClick = function(Item,index)
    {
        //console.log(Item.BoxIndex);

        if(this.selectedBox) {

            var BoxIndex = this.selectedBox.BoxIndex;
            var leaf = [this.selectedBox.getChildByName('leaf1'),this.selectedBox.getChildByName('leaf2')];
            for (var i = 0; i < 2; i++){

                if (!leaf[i].skin) {
                    if (Item.dataSource.num == 0) return;
                    leaf[i].skin = Item.dataSource.icon;
                    leaf[i].ItemData = Item.dataSource;
                    leaf[i].Item = Item;
                    leaf[i].index = index;
                    Item.dataSource.num--;
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
                    break;
                }
            }
        }
        else
        {
            var dialog = new CommomConfirm("请选择培育槽");
            dialog.popup();
        }

    };

    proto.onBoxSelect = function(Box)
    {
        if(Box.usable){
            if(!Box.status){
                var leaf = [Box.getChildByName('leaf1'),Box.getChildByName('leaf2')];

                if(this.selectedBox != Box)
                {
                    if(this.selectedBox)
                    {
                        var select_leaf = [this.selectedBox.getChildByName('leaf1'),this.selectedBox.getChildByName('leaf2')];
                        for(var i = 0; i < 2; i++)
                        {
                            if(select_leaf[i].skin)
                            {
                                select_leaf[i].Item.getChildByName('gou').visible = false;
                            }
                        }
                        this.selectedBox.filters = [];
                    }
                    for(var i = 0; i < 2; i++)
                    {
                        if(leaf[i].skin)
                        {
                            leaf[i].Item.getChildByName('gou').visible = true;
                        }
                    }
                    this.selectedBox =  Box;
                    var glowFilter = new Laya.GlowFilter("#49fd4b", 5, 0, 0);
                    //设置滤镜集合为发光滤镜
                    this.selectedBox.filters = [glowFilter,glowFilter];

                }else
                {
                    console.log("取消");

                    for(var i = 0; i < 2; i++)
                    {
                        if(leaf[i].skin){
                            var count = 0;
                            for(var j = 0; j < leaf.length; j++){
                                if(leaf[j].skin)
                                {
                                    if(leaf[i].ItemData.id == leaf[j].ItemData.id)
                                    {
                                        count ++;
                                    }
                                }
                            }

                            if(count == 1){
                                leaf[i].Item.getChildByName('gou').visible = false;
                            }
                            leaf[i].skin = null;
                            leaf[i].ItemData.num ++;
                            console.log(leaf[i].ItemData.num);
                            if(leaf[i].ItemData.num == 1) leaf[i].Item.parent.parent.addItemAt(leaf[i].ItemData,leaf[i].index);
                            leaf[i].Item.parent.parent.refresh();
                            leaf[i].index = null;
                            leaf[i].ItemData = null;
                            leaf[i].Item = null;
                            break;
                        }
                    }

                }
            }else {
                console.log('收取种子');
                this.onLingquBtnClick(Box.BoxIndex);
            }


        }else
        {
            console.log('需要升级',config.PeiYuLevel[this.Level+1],game_level);
              if (config.PeiYuLevel[this.Level+1] <= game_level) {
                var dialog = new Confirm1("升级需要"+config.PeiYuUpate[this.Level+1]+'银元');
                dialog.closeHandler = new Laya.Handler(this,this.onConfirmClose);
                dialog.popup();
            }else{
                var dialog = new CommomConfirm(config.PeiYuLevel[this.Level+1]+"级解锁，快努力升级吧！");
                dialog.popup();
            }


        }


        //console.log(Box);
    };

    proto.onConfirmClose = function(name)
    {
        if(name == Dialog.YES)
        {
            if(this.stage.getChildByName("MyGame").UI.subGlod(config.PeiYuUpate[this.Level+1])){
                Utils.post('peiyu/upgrade',{uid:localStorage.GUID},this.onUpdateReturn,onHttpErr);
            }else {
                var dialog = new CommomConfirm("银元不足！");
                dialog.popup();
            }

        }
    };

    proto.onUpdateReturn = function(res)
    {
        if(res.code == 0)
        {
            self.setLevel(Number(self.Level)+1);
            Laya.stage.getChildByName("MyGame").initUserinfo();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onPeiyuBtnClick = function()
    {
        var ids = [];
        for(var i = 0; i < 2; i++)
        {
            ids.push(this.selectedBox.getChildByName('leaf'+(i+1)).ItemData.id);
            this.selectedBox.getChildByName('leaf'+(i+1)).Item.getChildByName('gou').visible = false;
        }
        if(this.selectedBox.status || ids.length != 2) return;
        //console.log(send_data);
        var send_data = {number:this.selectedBox.BoxIndex+1,yanye1:ids[0],yanye2:ids[1],uid:localStorage.GUID};
        //发送开始培育请求
        console.log(send_data);

        Utils.post("peiyu/start",send_data,this.onStartPeiyuReturn,null,this.selectedBox.BoxIndex);


    };

    proto.onStartPeiyuReturn = function(res,BoxIndex)
    {
        if(res.code == 0)
        {
            var total = Utils.strToTime(res.data.stop_time) - Utils.strToTime(res.data.start_time);
            self.startWork({time:total,seed:res.data.seed,TimeNum:total},BoxIndex);

            /*var data = {time:Utils.strToTime(res.data.stop_time) - Utils.strToTime(res.data.start_time)};
            self.stage.getChildByName("MyGame").Peiyushi.startWorking(data);
            self.startWork();*/
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }

    };

    proto.onLingquBtnClick = function(BoxIndex)
    {
        Utils.post("peiyu/gather",{uid:localStorage.GUID,number:BoxIndex+1},this.onGatherReturn,null,BoxIndex);
    };

    proto.onGatherReturn = function(res,BoxIndex)
    {
        if(res.code == '0')
        {
            if(res.data.is_stolen == 1){
                var dialog = new CommomConfirm('糟糕，'+ItemInfo[res.data.seed].name+'被你的好友'+res.data.jd_name+'窃取了！');
                dialog.popup();
            }else {
                getItem(res.data.seed);
            }

            self.endWork(BoxIndex);
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    //开始
    proto.startWork = function(data,BoxIndex)
    {
        this.BoxList[BoxIndex].status = true;

        this.BoxList[BoxIndex].WorkData = data;
        this.BoxList[BoxIndex].TimeNum = data.TimeNum;
        //this.BoxList[BoxIndex].timer.clear(this,this.BoxList[BoxIndex].countdown);
        Laya.timer.loop(1000,this,this.BoxList[BoxIndex].countdown,[BoxIndex]);

        this.progress[BoxIndex].value = data.TimeNum/data.time;

    };

    proto.endWork = function(BoxIndex)
    {

        this.BoxList[BoxIndex].getChildByName('seed').skin = null;

        this.BoxList[BoxIndex].status = false;

    };

    proto.onDialogClose = function()
    {
        for(var i = 0; i < this.BoxList.length; i++)
        {
            Laya.timer.clear(this,this.BoxList[i].countdown);
        }

    };

    proto.onTabPeiyuSelected = function(index)
    {
        if(index == 1){
            console.log('进入小游戏');
            var dialog = new Confirm1('即将进入消消乐游戏');
            dialog.popup();
            dialog.closeHandler = Laya.Handler.create(this,this.onTipsClose);
        }
    };

    proto.onTipsClose = function(name)
    {
        if(name == Dialog.YES)
        {
            window.location.href = config.XiaoxiaoleURL;
        }
    };

    //检测列表是否有物品，没有则提示
    proto.checkList = function()
    {
        for(var i = 0; i < this.Lists.length; i++)
        {
            if(!this.Lists[i].length){
                this.Lists[i].getChildByName('tips').visible = true;
            }
        }
    }
})();