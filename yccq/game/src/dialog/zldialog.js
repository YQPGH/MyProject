/**
 * Created by lkl on 2017/4/14.
 */
//商行弹出框
(function(){
    var self = null;
    function ZLDialog()
    {
        ZLDialog.__super.call(this);
        self = this;
        this.name ='zldialog';
        this.ZLData = null;//{seed:[],recipe:[],filterTip:[],huafei:[],other:[]};
        this.SMData = null;
        this.isSale = 0;
        this.saleNum = 1;
        this.zl_list_arr = [this.zl_seed,this.zl_recipe,this.zl_filter_tip,this.zl_other,this.sm_seed];

        //关联tab
        this.table.selectedIndex = 0;
        this.table.selectHandler = this.view_stack.setIndexHandler;

        this.tab_zl.selectedIndex = 0;
        //this.tab_zl.selectHandler = this.view_stack_zl.setIndexHandler;
        this.tab_zl.selectHandler = new Laya.Handler(this,function(index){
            this.view_stack_zl.selectedIndex = index;
        });

        this.tab_zl.on(Laya.Event.CLICK, this, function(){
            var index = this.tab_zl.selectedIndex;
            if(ZhiYinManager.step1 == 3 ){
                ZhiYinMask.instance().setZhiYin(1);
            }
        });

        //this.tab_sm.selectedIndex = 0;
        //this.tab_sm.selectHandler = this.view_stack_sm.setIndexHandler;

        for(var i =0; i < this.zl_list_arr.length; i++)
        {
            this.zl_list_arr[i].scrollBar.hide = true;//隐藏列表的滚动条。
            this.zl_list_arr[i].scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
            this.zl_list_arr[i].scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
        }

        this.refresh_btn.clickHandler = new Laya.Handler(this,this.onRefreshBtnClick);

        this.closeHandler = new Laya.Handler(this,this.onZLShopClose);

        this.help_btn.clickHandler = new Laya.Handler(this,function(){
            var dialog = new GuideBook(0);
            dialog.popup();
        });
        this.my_money.changeText(Laya.stage.getChildByName('MyGame').UI.userInfo.money);
        this.timer.loop(500,this,this.changeMoney);


        this.getIsSale();
        //this.sm_shop.visible = false;
        //this.checkZhiYin();
    }
    Laya.class(ZLDialog,"ZLDialog",ZLShopUI);
    var proto = ZLDialog.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 3){
            //ZhiYinMask.instance().setZhiYinContent(ZhiYinManager[0]);
            ZhiYinMask.instance().ZhiYinDialog();
        }
    };

    proto.onClosed = function()
    {
        if(ZhiYinManager.step1 == 3 && ZhiYinManager.step2 == 3) {
            ZhiYinManager.instance().showZhiYin();
            //ZhiYinMask.instance().close();
        }
    };

    proto.getIsSale = function()
    {
        Utils.post('user/is_sale_shop',{uid:localStorage.GUID},function(res){
            if(res.code == '0')
            {
                self.isSale = Number(res.data.is_sale);
                self.saleNum = Number(res.data.sale_num);
                self.getShopData();
            }
        });
    };

    proto.getShopData = function()
    {
        Utils.post("shop/lists",{uid:localStorage.GUID},this.initShop);
    };

    proto.initShop = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            self.ZLData = res.data.putong;
            self.SMData = res.data.shenmi;

            var ZL_seed_data = self.initShopData(self.ZLData.zhongzi);
            self.zl_seed.array = ZL_seed_data;
            self.zl_seed.renderHandler = new Laya.Handler(self, self.updateItem,[self.ZLData.zhongzi]);

            var ZL_recipe_data = self.initShopData(self.ZLData.peifang);
            self.zl_recipe.array = ZL_recipe_data;
            self.zl_recipe.renderHandler = new Laya.Handler(self, self.updateItem,[self.ZLData.peifang]);

            var ZL_filterTip_data = self.initShopData(self.ZLData.lvzui);
            self.zl_filter_tip.array = ZL_filterTip_data;
            self.zl_filter_tip.renderHandler = new Laya.Handler(self, self.updateItem,[self.ZLData.lvzui]);

            /*var ZL_huafei_data = self.initShopData(self.ZLData.huafei);
            self.zl_huafei.array = ZL_huafei_data;
            self.zl_huafei.renderHandler = new Laya.Handler(self, self.updateItem,[self.ZLData.huafei]);*/

            var other_arr = self.ZLData.tudi.concat(self.ZLData.chongzi);
            console.log(self.ZLData.chongzi);
            var ZL_other_data = self.initShopData(other_arr);
            self.zl_other.array = ZL_other_data;
            self.zl_other.renderHandler = new Laya.Handler(self, self.updateItem,[other_arr]);

            var SM_seed_data = self.initShopData(self.SMData.zhongzi,true);
            self.sm_seed.array = SM_seed_data;
            self.sm_seed.renderHandler = new Laya.Handler(self, self.updateItem,[self.SMData.zhongzi]);


            self.setCountdown(res.data.next_refresh_time,res.time);

            self.view_stack_zl.visible = true;
            self.sm_seed.visible = true;
        }
    };

    proto.initShopData = function(data,shenmi)
    {
        shenmi = shenmi?shenmi:false;
        if(!data) return null;
        var shop_data = [];
        for(var i = 0; i < data.length; i++)
        {
            var item_data = {id:data[i].shopid,icon:data[i].thumb,name:data[i].name,price:data[i].money,type:data[i].type1};
            if(shenmi){
                item_data.shenmi = true;
            }
            shop_data.push(item_data);
        }
        return shop_data;
    };

    proto.updateItem = function(data, cell, index)
    {
        cell.ItemIndex = data[index].id;
        //var buy_btn = cell.getChildByName("buy_btn");
        if(cell.dataSource.type == 'tudi'){
            var land_num = this.stage.getChildByName("MyGame").landArr.length;
            cell.getChildByName('price').text = (Number(cell.dataSource.price) + (land_num - 6) * 10000)*this.saleNum;
        }

        cell.dataSource.isSale = this.isSale;
        cell.dataSource.saleNum = this.saleNum;

        if(!cell.dataSource.shenmi){
            if(this.isSale){
                cell.getChildByName('sale').visible = true;
            }else {
                cell.getChildByName('sale').visible = false;
            }
        }

        cell.on(Laya.Event.CLICK,this,this.onBuyClick,[cell,index]);
    };

    proto.onBuyClick = function(item,index)
    {
        var buy_confirm = new BuyConfirm(item);
        buy_confirm.popup();
        buy_confirm.closeHandler = new Laya.Handler(this,function(){
            if(item.dataSource.shenmi && item.dataSource.bought){
                this.sm_seed.deleteItem(index);
            }
            item.parent.parent.refresh();
        });
    };

    proto.setCountdown = function(next_refresh_time,now)
    {
        this.CountDownTime = Utils.strToTime(next_refresh_time) - Utils.strToTime(now);
        if(this.CountDownTime >= 0){
            this.refresh_countdown.changeText('自动刷新:'+Utils.formatSeconds(this.CountDownTime));
            this.timer.loop(1000,this,this.CountDown);
        }
    };

    proto.CountDown = function()
    {
        if(this.CountDownTime == 0){
            this.timer.clear(this,this.CountDown);
            this.getShopData();
        }else {
            this.CountDownTime --;
            this.refresh_countdown.changeText('自动刷新:'+Utils.formatSeconds(this.CountDownTime));
            console.log(this.CountDownTime);
        }
    };

    proto.onRefreshBtnClick = function()
    {
        var dialog = new Confirm1('刷新需要消耗2乐豆');
        dialog.closeHandler = new Laya.Handler(this,this.onRefreshConfirmClose);
        dialog.popup();
    };

    proto.onRefreshConfirmClose = function(name)
    {
        if(name == Dialog.YES){
            Utils.post('shop/my_refresh',{uid:localStorage.GUID},this.onRefreshReturn,onHttpErr);
        }
    };

    proto.onRefreshReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            var SM_seed_data = self.initShopData(res.data.zhongzi,true);
            self.sm_seed.array = SM_seed_data;
            self.sm_seed.renderHandler = new Laya.Handler(self, self.updateItem,[res.data.zhongzi]);
            self.stage.getChildByName("MyGame").initUserinfo();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onZLShopClose = function()
    {
        this.timer.clear(this,this.CountDown);
        this.timer.clear(this,this.changeMoney);
    };

    proto.changeMoney = function()
    {
        this.my_money.changeText(Laya.stage.getChildByName('MyGame').UI.userInfo.money);
    };

})();