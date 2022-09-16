/**
 * Created by 41496 on 2017/5/18.
 */
(function(){
    //配方研究所界面
    var self = null;
    var index_type = null;
    function YJSDialog()
    {
        YJSDialog.__super.call(this);
        self = this;
        this.name = 'yanjiusuo';
        this.ItemList = [this.left_item0,this.left_item1,this.left_item2];
        this.Lists = [this.List0,this.List1,this.List2,this.List3,this.List4];

        for(var i = 0,len = this.Lists.length; i < len; i++)
        {
            this.Lists[i].scrollBar.hide = true;//隐藏列表的滚动条。
            this.Lists[i].scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
            this.Lists[i].scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
            this.Lists[i].renderHandler = new Laya.Handler(self, self.updateItem);
        }

        //合成按钮事件
        this.compound_btn.clickHandler = new Laya.Handler(this,this.onCompoundBtnClick);

        //取消叶子点击事件
        for(var i = 0; i < this.ItemList.length; i++)
        {
            this.ItemList[i].on(Laya.Event.CLICK,this,this.onItemCancel,[this.ItemList[i]]);
        }

        this.tab.selectHandler = new Laya.Handler(this,this.TabSelected);
        this.tab.selectedIndex = 0;

        //this.hecheng_btn.selected = true;
        //this.guanka_btn.clickHandler = new Laya.Handler(this,this.onGuankaBtnClick);

        this.help_btn.clickHandler = new Laya.Handler(this,function(){
            var dialog = new GuideBook(6);
            dialog.popup();
        });


        this.ListData();
    }
    Laya.class(YJSDialog,"YJSDialog",YJSDialogUI);
    var proto = YJSDialog.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.tiaoxiang == 0)
        {
            this.tipsSetp = 0;
            this.tips_1_ele.skewY = 180;
            this.tips.on(Laya.Event.CLICK,this,this.nextTips);
            this.nextTips();
        }
    };

    proto.onClosed = function()
    {
        if(ZhiYinManager.tiaoxiang == 0) {
            var tips = new tipsDialog();
            tips.content.innerHTML = '在这里能很快集生产原料，努力升级吧！还有更多惊喜在等着你！';
            tips.content.y = 100;
            tips.popup();
            tips.closeHandler = new Laya.Handler(this,function(){
                ZhiYinManager.tiaoxiang = 1;
                Utils.post('Guide/close_tips',{uid:localStorage.GUID,building:'tiaoxiang'},null);
            });
        }
    };

    proto.nextTips = function()
    {
        this.tipsSetp ++;
        if(this.tipsSetp <= 2)
        {
            this.tips.visible = true;
            if(this.tipsSetp > 1) this['tips_'+(this.tipsSetp-1)].visible = false;
            this['tips_'+this.tipsSetp].visible = true;
        }else {
            this.tips.visible = false;
        }
    };

    proto.TabSelected = function(index)
    {
        console.log(index);
        this.type_num = index;
        this.view_stack.selectedIndex = index;
        //this.ListData(index+1);
    };

    proto.ListData = function(type2)
    {
        index_type = null;
        var type2 = type2?type2:0;
        Utils.post("store/lists",{uid:localStorage.GUID,type1:"peifang",type2:type2},this.onDataReturn);
    };

    proto.onDataReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            var list_data = {1:[],2:[],3:[],4:[],5:[]};
            for(var i = 0; i < res.data.length; i++)
            {
                if(res.data[i].total > 0){
                    list_data[res.data[i].type2].push({id:res.data[i].shopid,icon:ItemInfo[res.data[i].shopid].thumb,name:ItemInfo[res.data[i].shopid].name,num:res.data[i].total});
                }
            }
            console.log(list_data);
            self.List0.array = list_data[1];
            self.List1.array = list_data[2];
            self.List2.array = list_data[3];
            self.List3.array = list_data[4];
            self.List4.array = list_data[5];
            self.view_stack.visible = true;

            for( i in list_data)
            {
                if(list_data[i].length > 0){
                    if (index_type != null) {
                        self.tab.selectedIndex = index_type;
                    }else{
                        self.tab.selectedIndex = i-1;
                    }
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
        console.log(Item);
        this.left_result.skin = null;
        for(var i = 0; i < this.ItemList.length; i++)
        {
            if(this.ItemList[i].numChildren == 0)
            {
                if(Item.dataSource.num == 0) return;
                var sprite = new Laya.Image(Item.dataSource.icon);
                sprite.anchorX = 0.5;
                sprite.anchorY = 0.5;
                sprite.pos(this.ItemList[i].width/2,this.ItemList[i].height/2);
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
                this.ItemList[i].addChild(sprite);
                break;
            }
        }
    };

    proto.onCompoundBtnClick = function()
    {
        if(this.left_item0.numChildren && this.left_item1.numChildren && this.left_item2.numChildren){
            var send_data = {peifang1:this.left_item0.getChildAt(0).ItemData.id,peifang2:this.left_item1.getChildAt(0).ItemData.id,peifang3:this.left_item2.getChildAt(0).ItemData.id};

            if(this.status) return;


            //发送开始合成请求
            send_data.uid = localStorage.GUID;
            console.log(send_data);
            Utils.post("peifang/start",send_data,this.onCompoundReturn,onHttpErr);
        }
    };

    proto.onCompoundReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            if(res.data.is_stolen == 0){
                self.left_result.skin = ItemInfo[res.data.peifang].thumb;
                var dialog = new HechengSuccess(res.data.peifang);
                dialog.popup();
            }else {
                var dialog = new CommomConfirm('糟糕，'+ItemInfo[res.data.peifang].name+'被你的好友'+res.data.jd_name+'窃取了！');
                dialog.popup();
            }

            for(var i = 0; i < self.ItemList.length; i++)
            {
                self.ItemList[i].getChildAt(0).Item.getChildByName('gou').visible = false;
                self.ItemList[i].removeChildren();
            }

        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
        self.ListData();
        index_type = self.type_num;
    };

    proto.onItemCancel = function(Item)
    {
        if(!this.status && Item.numChildren)
        {
            console.log("取消");
            Item.getChildAt(0).ItemData.num += 1;
            var count = 0;
            for(var i = 0; i < this.ItemList.length; i++){
                if(this.ItemList[i].getChildAt(0))
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
            if(Item.getChildAt(0).ItemData.num == 1) Item.getChildAt(0).Item.parent.parent.addItemAt(Item.getChildAt(0).ItemData,Item.getChildAt(0).index);
            Item.getChildAt(0).Item.parent.parent.refresh();
            Item.removeChildren();

        }
    };

    proto.onGuankaBtnClick = function()
    {
        var dialog = new Confirm1('即将进入欢乐挖宝游戏');
        dialog.popup();
        dialog.closeHandler = Laya.Handler.create(this,this.onTipsClose);

    };

    proto.onTipsClose = function(name)
    {
        if(name == Dialog.YES)
        {
            window.location.href = config.WabaoURL;
        }
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
    }
})();