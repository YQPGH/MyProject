/**
 * Created by 41496 on 2017/6/14.
 */
(function(){
    //路边摊
    var self = null;
    function LBTDialog(type)
    {
        LBTDialog.__super.call(this);
        self = this;
        this.FarmType = type;

        this.ListData = [{},{},{},{},{},{},{},{}];

        this.MyList.renderHandler = new Laya.Handler(this, this.updateItem);

        this.news_btn.clickHandler = new Laya.Handler(this,this.onNewsBtnClick);

        this.help_btn.clickHandler = new Laya.Handler(this,function(){
            var dialog = new GuideBook(10);
            dialog.popup();
        });

        this.getMyMarket();
    }
    Laya.class(LBTDialog,"LBTDialog",LuBianTanUI);
    var proto = LBTDialog.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.xiaotan == 0 && !this.FarmType)
        {
            this.tipsSetp = 0;
            this.tips.on(Laya.Event.CLICK,this,this.nextTips);
            this.nextTips();
        }
    };

    proto.onClosed = function()
    {
        if(ZhiYinManager.xiaotan == 0) {
            var tips = new tipsDialog();
            tips.content.innerHTML = '在这里能很快集生产原料，努力升级吧！还有更多惊喜在等着你！';
            tips.content.y = 100;
            tips.popup();
            tips.closeHandler = new Laya.Handler(this,function(){
                ZhiYinManager.xiaotan = 1;
                Utils.post('Guide/close_tips',{uid:localStorage.GUID,building:'xiaotan'},null);
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

    proto.getMyMarket = function()
    {
        if(this.FarmType == 'FriendFarm'){
            console.log(localStorage.FUID);
            Utils.post("friend/market",{uid:localStorage.GUID,code:localStorage.FUID},this.onListReturn);
        }else {
            Utils.post("market/list_my",{uid:localStorage.GUID},this.onListReturn);
        }

    };

    proto.onListReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            for(var i =0,len = res.data.length; i < len; i++)
            {
                self.ListData[i] = {id:res.data[i].id,number:res.data[i].number,icon:ItemInfo[res.data[i].shopid].thumb,price:res.data[i].money,num:res.data[i].total,status:res.data[i].status};
            }
        }
        console.log(self.ListData);
        self.MyList.array = self.ListData;
    };

    proto.updateItem = function(cell, index)
    {
        //console.log(cell.dataSource);
        if(cell.dataSource.number){
            cell.ItemEmpty = false;
            cell.getChildByName("num").changeText('数量:'+cell.dataSource.num);
            cell.getChildByName("jinbi").visible = true;
            cell.getChildByName("shoujia").visible = true;
            switch(Number(cell.dataSource.status))
            {
                case 1:
                    cell.getChildByName("sold").visible = true;
                    break;
                case 2:
                    if(this.FarmType == 'FriendFarm')
                    {

                        //cell.disabled = true;
                    }
                    break;
                case 0:
                    cell.getChildByName("gg").visible = true;

            }
        }else {
            cell.ItemEmpty = true;
        }
        cell.on(Laya.Event.CLICK,this,this.onItemClick,[cell,index]);
    };

    proto.onItemClick = function(cell,index)
    {
        console.log(cell);
        if(this.FarmType == 'FriendFarm')
        {
            if(!cell.ItemEmpty && cell.dataSource.status != '1'){
                console.log('购买朋友物品');
                var dialog = new Confirm1("购买需要消耗"+cell.dataSource.price+"银元");
                dialog.closeHandler = new Laya.Handler(this,this.onConfirmClose,[cell]);
                dialog.popup();
            }
        }else
        {
            if(cell.ItemEmpty){
                console.log('上架物品');
                var dialog = new LBT_SJ(cell,index);
                dialog.popup();
            }else
            {

                if(cell.dataSource.status == '1')
                {
                    console.log('已出售，收钱');
                    Utils.post("market/sold",{uid:localStorage.GUID,number:cell.dataSource.number},this.onMarketSoldReturn,onHttpErr,index);
                }else {
                    console.log('弹出上下架、打广告界面');
                    var dialog = new LBT_XJ(cell);
                    dialog.popup();
                }
            }
        }

    };

    proto.onNewsBtnClick = function()
    {
        var dialog = new LBT_ALL();
        dialog.popup();
    };

    proto.onMarketSoldReturn = function(res,index)
    {
        console.log(res);
        if(res.code == 0)
        {
            var cell = self.MyList.getCell(index);
            self.MyList.changeItem(index,{});
            self.stage.getChildByName("MyGame").initUserinfo();
            cell.ItemEmpty = true;
            //cell.dataSource = null;
            cell.getChildByName("jinbi").visible = false;
            cell.getChildByName("sold").visible = false;
            cell.getChildByName("gg").visible = false;
            cell.getChildByName("icon").skin = null;
            cell.getChildByName("num").changeText("");
            cell.getChildByName("price").changeText("");

            //cell.parent.parent.refresh();
        }
    };

    proto.onConfirmClose = function (cell,name) {
        console.log(name);
        if(name == 'yes'){
            Utils.post('market/buy',{uid:localStorage.GUID,number:cell.dataSource.number},this.onMarketBuyReturn,onHttpErr,cell);
        }
    };

    proto.onMarketBuyReturn = function(res,cell)
    {
        console.log(res);
        if(res.code == 0)
        {
            cell.dataSource.status = 1;
            cell.getChildByName("sold").visible = true;
            cell.getChildByName("gg").visible = false;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.show();
        }
    };
})();