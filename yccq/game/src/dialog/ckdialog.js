/**
 * Created by lkl on 2017/4/13.
 */
(function(){
    var self = null;
    function CKDialog()
    {
        CKDialog.__super.call(this);
        self = this;
        this.Building = this.stage.getChildByName('MyGame').depot;
        this.tabList = [this.tab_seed,this.tab_yanye,this.tab_recipe,this.tab_Cigarette,this.tab_lvzui,this.tab_huafei];
        this.lists = [this.list_seed,this.list_normal,this.list_dry,this.list_chun,this.list_recipe,this.list_cigarette,this.list_cigarette_pin,this.list_lvzui,this.list_huafei];

        for(var i = 0,len = this.lists.length; i < len; i++)
        {
            this.lists[i].scrollBar.hide = true;//隐藏列表的滚动条。
            this.lists[i].scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
            this.lists[i].scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
            this.lists[i].renderHandler = new Laya.Handler(this, this.updateItem);
        }

        this.tab_yanye_1.selectHandler = this.yanye_view_stack.setIndexHandler;
        this.tab_yan.selectHandler = this.yan_view_stack.setIndexHandler;

        for(var i = 0; i < this.tabList.length; i++)
        {
            this.tabList[i].clickHandler = new Laya.Handler(this,this.onTabClick,[i]);
        }

        this.upgrade_btn.clickHandler = new Laya.Handler(this,this.onUpgradeBtnClick);

        this.tab_seed.selected = true;
        console.log(this.Building.Level);
        this.max_num.changeText(config.StoreUpdate[this.Building.Level-1].size);

        //出售功能
        this.sale_btn.clickHandler = new Laya.Handler(this,this.onSaleBTNClick);
        this.add_btn.clickHandler = new Laya.Handler(this,this.onAddBTNClick);
        this.sub_btn.clickHandler = new Laya.Handler(this,this.onSubBTNClick);
        this.add_btn.on(Laya.Event.MOUSE_DOWN,this,this.onBtnPress,[this.add_btn,this.sale_num,this.onAddBTNClick]);
        this.sub_btn.on(Laya.Event.MOUSE_DOWN,this,this.onBtnPress,[this.sub_btn,this.sale_num,this.onSubBTNClick]);

        this.SelectedItem = null;

        this.panel.vScrollBar.hide = true;
        this.select_details.style.color = '#ffedd5';
        this.select_details.style.fontSize = 16;
        this.select_details.style.padding = [0,0,5,0];
        //this.select_details.style.marginBottom = 10;

        this.getCKData();
        this.zyf_btn.clickHandler = new Laya.Handler(this, this.gotoZyf);
    }
    Laya.class(CKDialog,"CKDialog",CKDialogUI);
    var proto = CKDialog.prototype;

    proto.getCKData = function()
    {
        Utils.post("store/lists",{uid:localStorage.GUID,type1:0},function(res){
            console.log(res);
            if(res.code == 0)
            {
                //var yanyeData = self.initYanyeData(res.data['烟叶']);
                self.list_seed.array = self.initListData(res.data.zhongzi||[]);

                self.list_normal.array = self.initListData(res.data.yanye||[]);
                self.list_dry.array = self.initListData(res.data.yanye_kao||[]);
                self.list_chun.array = self.initListData(res.data.yanye_chun||[]);
                self.list_recipe.array = self.initListData(res.data.peifang||[]);
                self.list_cigarette.array = self.initListData(res.data.yan||[]);
                self.list_cigarette_pin.array = self.initListData(res.data.yan_pin||[]);
                self.list_lvzui.array = self.initListData(res.data.lvzui||[]);
                self.list_huafei.array = self.initListData(res.data.daoju||[]);
                self.curr_num.changeText(res.data.used||0);
                self.progress.value = Number(self.curr_num.text)/Number(self.max_num.text);

                self.checkList();

                self.view_stack.visible = true;
            }
        });
    };

    //初始化列表数据
    proto.initListData = function(data)
    {
        var temp = [];
        for(var i = 0; i < data.length; i++)
        {
            if(Number(data[i].total))
            {
                temp.push({shop_id:data[i].shopid,item_id:data[i].id,item_name:ItemInfo[data[i].shopid].name,item_num:data[i].total,icon:ItemInfo[data[i].shopid].thumb,useable:true});
            }
        }
        return temp;
    };

    proto.updateItem = function(cell,index)
    {
        cell.on(Laya.Event.CLICK,this,this.onListItemClick,[cell,index]);
        cell.on(Laya.Event.MOUSE_DOWN,this,onItemPress,[cell,index,cell.dataSource.shop_id,false]);
        /*cell.on(Laya.Event.MOUSE_DOWN,this,showItemInfo,[cell,cell.dataSource.shop_id]);
        cell.on(Laya.Event.MOUSE_UP,this,hideItemInfo,[cell]);
        cell.on(Laya.Event.MOUSE_MOVE,this,hideItemInfo,[cell]);
        cell.on(Laya.Event.MOUSE_OUT,this,hideItemInfo,[cell]);*/
    };

    proto.onListItemClick = function(cell,index)
    {
        if(this.SelectedItem){
            this.SelectedItem.getChildByName('selected').visible = false;
        }
        this.SelectedItem = cell;
        this.SelectedItem.getChildByName('selected').visible = true;
        this.SelectedItem.ItemIndex = index;
        this.select_name.text = cell.dataSource.item_name;
        this.select_icon.skin = cell.dataSource.icon;
        this.select_num.changeText(cell.dataSource.item_num);
        this.sale_num.changeText(1);
        this.select_details.innerHTML = ItemInfo[cell.dataSource.shop_id].description;
        this.panel.vScrollBar.value = 0;
        this.panel.refresh();
        this.sale_price.changeText(ItemInfo[cell.dataSource.shop_id].back_money);
    };

    proto.onTabClick = function(index)
    {
        console.log(index);

        for(j = 0,len=this.tabList.length; j < len; j++) {
            if(j == index)
            {
                this.tabList[j].selected = true;
            }else
            {
                this.tabList[j].selected = false;
            }
        }

        if(index == 1)
        {
            this.tab_yanye_1.selectedIndex = 0;
        }

        if(index == 3)
        {
            this.tab_yan.selectedIndex = 0;
        }

        this.view_stack.selectedIndex = index;
    };

    proto.onUpgradeBtnClick = function()
    {
        if(this.Building.Level < config.StoreUpdate.length){
            var dialog = new Confirm1('储量升级到'+config.StoreUpdate[this.Building.Level].size+'需要'+config.StoreUpdate[this.Building.Level].money+'乐豆');
            dialog.closeHandler = new Laya.Handler(this,this.onUpgradeClose);
            dialog.popup();
        }else
        {
            var dialog = new CommomConfirm('仓库储量已经达到最大值');
            dialog.popup();
        }

    };

    proto.onUpgradeClose = function(name)
    {
        console.log(name);
        if(name == 'yes')
        {
            Utils.post("store/upgrade",{uid:localStorage.GUID},this.onStoreUpgradeReturn);
        }

    };

    proto.onStoreUpgradeReturn = function(res)
    {
        if(res.code == 0){
            var dialog = new kuorongUI();
            dialog.on(Laya.Event.CLICK,dialog,function(){
                this.close();
            });
            dialog.popup();
            self.max_num.changeText(config.StoreUpdate[self.Building.Level].size);
            self.progress.value = Number(self.curr_num.text)/Number(self.max_num.text);
            self.Building.setLevel(Number(self.Building.Level)+1);
            Laya.stage.getChildByName('MyGame').initUserinfo();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }

    };

    proto.onAddBTNClick = function()
    {
        if(this.SelectedItem && Number(this.sale_num.text) < Number(this.SelectedItem.dataSource.item_num)){
            this.sale_num.changeText(Number(this.sale_num.text)+1);
            this.sale_price.changeText(Number(this.sale_num.text)*Number(ItemInfo[this.SelectedItem.dataSource.shop_id].back_money));
        }
    };

    proto.onSubBTNClick = function()
    {
        if(this.SelectedItem && Number(this.sale_num.text) > 1){
            this.sale_num.changeText(Number(this.sale_num.text)-1);
            this.sale_price.changeText(Number(this.sale_num.text)*Number(ItemInfo[this.SelectedItem.dataSource.shop_id].back_money));
        }
    };

    proto.onSaleBTNClick = function()
    {
        console.log(this.SelectedItem);
        if(this.SelectedItem){
            var dialog = new Confirm1('确定出售'+this.SelectedItem.dataSource.item_name+'*'+this.sale_num.text+'吗？');
            dialog.popup();
            dialog.closeHandler = new Laya.Handler(this,this.onDialogClose);
        }

    };

    proto.onDialogClose = function(name)
    {
        if(name == Dialog.YES)
        {
            console.log('%c出售'+this.SelectedItem.dataSource.item_name+'*'+this.sale_num.text,"color:green");
            Utils.post('store/sale',{uid:localStorage.GUID,shopid:this.SelectedItem.dataSource.shop_id,total:this.sale_num.text},this.onSaleReturn,onHttpErr,this.SelectedItem);

        }
    };

    proto.onSaleReturn = function(res,obj)
    {
        console.log(obj);
        if(res.code == '0'){
            getMoney(res.data.money);
            if(Number(self.sale_num.text) == obj.dataSource.item_num)
            {
                obj.parent.parent.deleteItem(obj.ItemIndex);
                self.SelectedItem.getChildByName('selected').visible = false;
                self.SelectedItem = null;
                self.select_name.text = '';
                self.select_icon.skin = null;
                self.select_num.changeText('');
                self.sale_num.changeText(0);
                self.select_details.innerHTML = '';
                self.sale_price.changeText(0);
            }else {
                obj.dataSource.item_num -= Number(self.sale_num.text);
                obj.parent.parent.refresh();
                self.onListItemClick(obj,obj.ItemIndex);
            }
            self.curr_num.changeText(res.data.used);
            Laya.stage.getChildByName("MyGame").initUserinfo();
            self.checkList();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }

    };

    proto.onBtnPress = function(btn,label,onPress)
    {
        btn.timer.once(300, this, this.onHold,[btn,label,onPress]);
        btn.on(Laya.Event.MOUSE_UP, this, this.onBtnRelease,[btn,onPress]);
        btn.on(Laya.Event.MOUSE_OUT, this, this.onBtnRelease,[btn,onPress]);
    };

    proto.onHold = function(btn,label,onPress)
    {

        btn.isHold = true;
        console.log('按住');
        btn.timer.loop(100, this, onPress,[label]);


    };

    /** 鼠标放开后停止hold */
    proto.onBtnRelease = function(btn,onPress)
    {
        // 鼠标放开时，如果正在hold，则播放放开的效果
        if (btn.isHold)
        {
            btn.isHold = false;
            btn.timer.clear(this, onPress);
        }
        else // 如果未触发hold，终止触发hold
        {
            btn.timer.clear(this, this.onHold);
        }
        btn.off(Laya.Event.MOUSE_UP, this, this.onBtnRelease);

    };

    proto.checkList = function()
    {
        if(this.list_huafei.length){
            this.tab_huafei.gray = false;
            this.onTabClick(5);
        }else {
            this.tab_huafei.gray = true;
        }

        if(this.list_lvzui.length){
            this.tab_lvzui.gray = false;
            this.onTabClick(4);
        }else {
            this.tab_lvzui.gray = true;
        }

        if(this.list_cigarette.length || this.list_cigarette_pin.length){
            this.tab_Cigarette.gray = false;
            this.onTabClick(3);
            if(this.list_cigarette_pin.length)this.tab_yan.selectedIndex = 1;
            if(this.list_cigarette.length)this.tab_yan.selectedIndex = 0;
        }else {
            this.tab_Cigarette.gray = true;
        }

        if(this.list_recipe.length){
            this.tab_recipe.gray = false;
            this.onTabClick(2);
        }else {
            this.tab_recipe.gray = true;
        }

        if(this.list_normal.length || this.list_dry.length || this.list_chun.length){
            this.tab_yanye.gray = false;
            this.onTabClick(1);
            if(this.list_chun.length)this.tab_yanye_1.selectedIndex = 2;
            if(this.list_dry.length)this.tab_yanye_1.selectedIndex = 1;
            if(this.list_normal.length)this.tab_yanye_1.selectedIndex = 0;
        }else {
            this.tab_yanye.gray = true;
        }

        if(this.list_seed.length){
            this.tab_seed.gray = false;
            this.onTabClick(0);
        }else {
            this.tab_seed.gray = true;
        }

    };
    proto.gotoZyf = function(){
        this.close();
        var dialog = new JGCDialog();
        dialog.popup();
    }
})();