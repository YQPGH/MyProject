/**
 * Created by 41496 on 2017/6/16.
 */
(function(){
    var self = null;
    function LBT_ALL()
    {
        LBT_ALL.__super.call(this);
        self = this;
        this.curr_page = 1;

        this.MarketList.renderHandler = new Laya.Handler(this,this.updateItem);

        this.pre_page.clickHandler = new Laya.Handler(this,this.onPrePageClick);
        this.next_page.clickHandler = new Laya.Handler(this,this.onNextPageClick);

        this.getMarketAll(this.curr_page);
    }
    Laya.class(LBT_ALL,"LBT_ALL",LBT_ALLUI);
    var proto = LBT_ALL.prototype;

    proto.getMarketAll = function(page)
    {
        Utils.post("market/list_all",{uid:localStorage.GUID,page:page},this.onMarketAllReturn);
    };

    proto.onMarketAllReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            var List_data = [];
            if(res.data.list.length)
            {
                for(var i = 0; i < res.data.list.length; i ++)
                {
                    List_data.push({shopid:res.data.list[i].shopid,nickname:res.data.list[i].nickname,icon:ItemInfo[res.data.list[i].shopid].thumb,price:res.data.list[i].money,name:ItemInfo[res.data.list[i].shopid].name,num:res.data.list[i].total,number:res.data.list[i].number});
                }
                self.curr_page = res.data.page;
                self.MarketList.array = List_data;
                self.MarketList.visible = true;
            }else {
                self.curr_page = (res.data.page == 1)?1:res.data.page-2;
            }
        }
    };

    proto.onPrePageClick = function()
    {

        this.curr_page --;
        if(this.curr_page <= 0) this.curr_page = 1;
        this.getMarketAll(this.curr_page);
    };

    proto.onNextPageClick = function()
    {
        this.curr_page ++;
        this.getMarketAll(this.curr_page);
    };

    proto.updateItem = function(cell,index)
    {
        cell.ItemIndex = index;
        cell.on(Laya.Event.CLICK,this,this.onListItemClick,[cell]);
    };

    proto.onListItemClick = function(cell)
    {
        console.log(cell.dataSource.number);
        var dialog = new Confirm1("购买需要消耗"+cell.dataSource.price+"银元");
        dialog.closeHandler = new Laya.Handler(this,this.onConfirmClose,[cell]);
        dialog.popup();
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
            //self.MarketList.deleteItem(cell.ItemIndex);
            getItem(cell.dataSource.shopid);
            self.stage.getChildByName('MyGame').initUserinfo();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.show();
        }
    };

})();