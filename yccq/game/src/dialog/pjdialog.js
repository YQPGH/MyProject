/**
 * Created by 41496 on 2017/5/12.
 */
(function(){
    //品鉴弹出框
    var self = null;
    function PinjianDialog()
    {
        PinjianDialog.__super.call(this);
        self = this;
        this.name ='pjdialog';
        this.selectedItem = null;
        this.Lists = [this.wei_pinjian_list,this.yi_pinjian_list];
        this.tabList = [this.tab_pinjian,this.tab_shengji];

        this.panel.vScrollBarSkin = null;

        for(var i = 0; i < this.Lists.length; i++)
        {
            this.Lists[i].scrollBar.hide = true;//隐藏列表的滚动条。
            this.Lists[i].scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
            this.Lists[i].scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
            this.Lists[i].renderHandler = new Laya.Handler(this, this.updateItem);
        }

        for(var i = 0; i < this.tabList.length; i++)
        {
            this.tabList[i].clickHandler = new Laya.Handler(this,this.onTabClick,[i]);
        }
        this.tab_pinjian.selected = true;

        if(ZhiYinManager.step1 == 8 && ZhiYinManager.step2 == 0){
            this.wei_pinjian_list.on(Laya.Event.CLICK,this,function(){
                ZhiYinMask.instance().setZhiYin(1);
            });
        }


        //取消事件
        this.select_icon.on(Laya.Event.CLICK,this,this.onItemCancel);

        //点击鉴定按钮事件
        this.pinjian_btn.clickHandler = new Laya.Handler(this,this.onPinJianBTNClick);

        this.shengji_btn.clickHandler = new Laya.Handler(this,this.onShengJiBTNClick);

        this.quan.on(Laya.Event.CLICK,this,this.onQuanClick);

        this.use.on(Laya.Event.CHANGE,this,this.onUseSelect);

        this.help_btn.clickHandler = new Laya.Handler(this,function(){
            var dialog = new GuideBook(7);
            dialog.popup();
        });

        this.getYanData();

    }
    Laya.class(PinjianDialog,"PinjianDialog",pinjianUI);
    var proto = PinjianDialog.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 8 && ZhiYinManager.step2 == 0)
        {
            ZhiYinMask.instance().ZhiYinDialog();
        }
    };

    proto.onClosed = function()
    {
        if(ZhiYinManager.step1 == 8) {
            ZhiYinManager.instance().showZhiYin();
            ZhiYinMask.instance().close();
        }
    };

    proto.onTabClick = function(index)
    {
        console.log(index);
        this.onItemCancel();
        this.result.text = '';

        for(j = 0,len=this.tabList.length; j < len; j++) {
            if(j == index)
            {
                this.tabList[j].selected = true;
            }else
            {
                this.tabList[j].selected = false;
            }
        }

        switch(index)
        {
            case 0:
                this.getYanData();
                break;
            case 1:
                this.getYanPinData();
                break;
        }

        this.viewstack.selectedIndex = index;
    };

    proto.getYanData = function()
    {
        Utils.post("store/lists",{uid:localStorage.GUID,type1:"yan"},this.onYanDataReturn);
    };

    proto.onYanDataReturn = function(res)
    {
        console.log(res);
        if(res.code ==0)
        {
            var pinjian_data = [];
            for(var i = 0,len = res.data.length; i < len; i++)
            {
                if(Number(res.data[i].total))
                {
                    pinjian_data.push({id:res.data[i].shopid,icon:ItemInfo[res.data[i].shopid].thumb,num:res.data[i].total});
                }
            }

            self.wei_pinjian_list.array = pinjian_data;
            self.wei_pinjian_list.visible = true;
            if(!self.wei_pinjian_list.length){
                self.wei_pinjian_list.getChildByName('tips').visible = true;
            }
        }
    };

    proto.getYanPinData = function()
    {
        Utils.post("store/lists",{uid:localStorage.GUID,type1:"yan_pin"},this.onYanPinDataReturn);
    };

    proto.onYanPinDataReturn = function(res)
    {
        console.log(res);
        if(res.code ==0)
        {
            var pinjian_data = [];
            for(var i = 0,len = res.data.length; i < len; i++)
            {
                if(Number(res.data[i].total))
                {
                    pinjian_data.push({id:res.data[i].shopid,icon:ItemInfo[res.data[i].shopid].thumb,num:res.data[i].total,quan_shopid:res.data[i].quan_shopid,quan_total:res.data[i].quan_total,ledou:res.data[i].ledou});
                }
            }

            self.yi_pinjian_list.array = pinjian_data;
            self.yi_pinjian_list.visible = true;
            if(!self.yi_pinjian_list.length){
                self.yi_pinjian_list.getChildByName('tips').visible = true;
            }
        }
    };

    proto.updateItem = function(cell,index)
    {
        cell.ItemIndex = index;
        cell.on(Laya.Event.CLICK,this, this.onListItemClick,[cell]);
        cell.on(Laya.Event.MOUSE_DOWN,this,onItemPress,[cell,index,cell.dataSource.id,false]);
    };

    proto.onListItemClick = function(item)
    {
        console.log(item.dataSource);
        if(Number(item.dataSource.num)){
            if(this.selectedItem) this.selectedItem.getChildByName('gou').visible = false;
            this.selectedItem = item;
            item.getChildByName('gou').visible = true;
            this.use.selected = false;
            this.select_icon.skin = ItemInfo[item.dataSource.id].thumb;
            this.select_name.changeText(ItemInfo[item.dataSource.id].name);
            this.result.text = '';
            if(item.dataSource.quan_shopid){
                this.quan.skin = ItemInfo[item.dataSource.quan_shopid].thumb;
                this.has_quan.changeText(item.dataSource.quan_total);
                if(Number(item.dataSource.quan_total)){
                    this.has_quan.color = '#00FF00';
                }else {
                    this.has_quan.color = '#FF0000';
                }
            }
        }
    };

    proto.onItemCancel = function()
    {

        if(this.selectedItem && this.selectedItem.dataSource.quan_shopid){
            this.quan.skin = null;
            this.has_quan.changeText(0);
            this.has_quan.color = '#000000';
        }
        if(this.selectedItem)this.selectedItem.getChildByName('gou').visible = false;
        this.selectedItem = null;
        this.select_icon.skin = null;
        this.select_name.changeText('');
    };

    proto.onUseSelect = function()
    {
        if(this.selectedItem && this.use.selected && Number(this.selectedItem.dataSource.quan_total)){
            var dialog = new Confirm1('需要消耗一张'+ItemInfo[this.selectedItem.dataSource.quan_shopid].name);
            dialog.closeHandler = new Laya.Handler(this,function(name){
                if(name == Dialog.YES){

                }else {
                    this.use.selected = false;
                }
            });
            dialog.popup();
        }else {
            this.use.selected = false;
        }
    };

    proto.onPinJianBTNClick = function()
    {
        if(this.selectedItem)
        {
            var yan_id = this.selectedItem.dataSource.id;
            console.log(yan_id);
            var UI = this.stage.getChildByName("MyGame").UI;
            var gold = config.PinJian[ItemInfo[yan_id].type2-1];
            if(Number(UI.userInfo.pinjian_lv) > 0){
                gold = gold*config.Achievement.Pinjian.jiangli[Number(UI.userInfo.pinjian_lv)-1];
            }

            var dialog = new Confirm1('品鉴香烟需要花费'+gold+'银元');
            dialog.closeHandler = new Laya.Handler(this,function(name){
                if(name == Dialog.YES){
                    if(UI.subGlod(gold)){
                        Utils.post("yan/pinjian",{uid:localStorage.GUID,shopid:yan_id},function(res){
                            console.log(res);
                            if(res.code == 0)
                            {
                                self.stage.getChildByName("MyGame").initUserinfo();
                                self.selectedItem.dataSource.num -= 1;
                                if(self.selectedItem.dataSource.num <= 0)
                                {
                                    self.selectedItem.parent.parent.deleteItem(self.selectedItem.ItemIndex);
                                }
                                self.selectedItem.parent.parent.refresh();
                                self.onItemCancel();
                                var data = {};
                                data.result = res.data.text;
                                data.shopid = res.data.shopid;
                                self.result.changeText(data.result);

                                var dialog = new PinjianSuccess(data);
                                dialog.popup();

                                if(ZhiYinManager.step1 == 8 && ZhiYinManager.step2 == 0)
                                {
                                    ZhiYinManager.instance().setGuideStep(8,1,true);
                                    //ZhiYinMask.instance().ZhiYinDialog();
                                }

                            }else {
                                //self.result.changeText(res.msg);
                                var dialog = new CommomConfirm(res.msg);
                                dialog.popup();
                            }
                        });
                    }else {
                        var dialog = new CommomConfirm('银元不足');
                        dialog.popup();
                    }

                }
            });
            dialog.popup();

        }
        else
        {
            var dialog = new CommomConfirm("请放入香烟");
            dialog.popup();
        }
    };

    proto.onShengJiBTNClick = function()
    {
        if(this.selectedItem)
        {
            var yan_id = this.selectedItem.dataSource.id;
            var ledou = Number(this.selectedItem.dataSource.ledou);
            var quan_id = 0;
            if(this.use.selected && this.selectedItem.dataSource.quan_total){
                quan_id = this.selectedItem.dataSource.quan_shopid;
            }
            console.log(yan_id,quan_id);
            var dialog = new Confirm1('升级实体香烟需要花费'+ledou+'乐豆');
            dialog.closeHandler = new Laya.Handler(this,function(name){
                if(name == Dialog.YES){
                    if(this.stage.getChildByName("MyGame").UI.subBean(ledou)){
                        Utils.post("yan/upgrade",{uid:localStorage.GUID,shopid:yan_id,quan_shopid:quan_id},function(res){
                            console.log(res);
                            var data = {};
                            if(res.code == 0)
                            {
                                if(res.data.success == 1){
                                    //self.result.changeText('升级成功');
                                    self.selectedItem.dataSource.num -= 1;
                                    if(self.use.selected) self.selectedItem.dataSource.quan_total -= 1;
                                    self.selectedItem.parent.parent.refresh();
                                    self.onItemCancel();

                                    data.result = '恭喜您，升级成功，获得礼盒*1，在个人中心查看并填写地址便可获得实体烟奖励哦';
                                    data.shopid = res.data.mubiao;

                                    self.result.text = data.result;

                                    var dialog = new ShengjiSuccess(1,data);
                                    dialog.popup();
                                }else {
                                    //self.result.changeText(res.msg);
                                    if(Number(res.data.ledou)){
                                        data.result = '很遗憾，您的香烟没有升级成功，但您获得了'+res.data.ledou+'乐豆';
                                        data.shopid = 'ledou';
                                        getBean(Number(res.data.ledou));
                                    }else if(Number(res.data.money)){
                                        data.result = '很遗憾，您的香烟没有升级成功，但您获得了'+res.data.money+'银元';
                                        data.shopid = 'money';
                                        getMoney(Number(res.data.money));
                                    }else if(Number(res.data.shopid)){
                                        data.result = '很遗憾，您的香烟没有升级成功，但您获得了'+ItemInfo[res.data.shopid].name+'*'+res.data.shop_num;
                                        data.shopid = res.data.shopid;
                                        getItem(res.data.shopid,res.data.shop_num);
                                    }
                                    self.result.text = data.result;
                                    var dialog = new ShengjiSuccess(0,data);
                                    dialog.popup();
                                }


                            }else {
                                var dialog = new CommomConfirm(res.msg);
                                dialog.popup();
                            }
                            self.stage.getChildByName("MyGame").initUserinfo();
                        });
                    }else {
                        var dialog = new CommomConfirm('乐豆不足');
                        dialog.popup();
                    }
                }

            });
            dialog.popup();

        }
        else
        {
            var dialog = new CommomConfirm("请放入香烟");
            dialog.popup();
        }
    };

    proto.onQuanClick = function()
    {
        var dialog = new QuanDialog();
        dialog.popup();
    }
})();