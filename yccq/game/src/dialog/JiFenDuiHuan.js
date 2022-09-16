/**
 * Created by 41496 on 2017/11/1.
 */
(function(){
    var self = null;
    function JiFenDuiHuan(){
        JiFenDuiHuan.__super.call(this);
        self = this;
        this.name = 'jifenduihuan';
        this.selectedItem = null;
        this.tab.selectHandler = this.ViewStack.setIndexHandler;

        this.sanxing_list.scrollBar.hide = true;//隐藏列表的滚动条。
        this.sanxing_list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.sanxing_list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.erxing_list.scrollBar.hide = true;//隐藏列表的滚动条。
        this.erxing_list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.erxing_list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.yixing_list.scrollBar.hide = true;//隐藏列表的滚动条。
        this.yixing_list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.yixing_list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.sanxing_list.renderHandler = new Laya.Handler(this,this.updateItem);
        this.erxing_list.renderHandler = new Laya.Handler(this,this.updateItem);
        this.yixing_list.renderHandler = new Laya.Handler(this,this.updateItem);

        this.duihuan_btn.clickHandler = new Laya.Handler(this,this.onDuiHuanBtnClick);
        this.qianwang_btn.clickHandler = new Laya.Handler(this,this.onQianWangBtnClick);

        this.getMyJiFen();
        this.getMyYan();
    }
    Laya.class(JiFenDuiHuan,'JiFenDuiHuan',JiFenDuiHuanUI);
    var proto = JiFenDuiHuan.prototype;
    //获取积分
    proto.getMyJiFen = function()
    {
        Utils.post('user/detail',{uid:localStorage.GUID},this.onJiFenReturn,onHttpErr);
    };

    proto.onJiFenReturn = function(res)
    {
        if(res.code == 0)
        {
            self.jifen_all.changeText(res.data.jifen);
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    //获取烟列表
    proto.getMyYan = function()
    {
        Utils.post('store/lists',{uid:localStorage.GUID,type1:'yan_pin'},this.onYanReturn,onHttpErr);
    };

    proto.onYanReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            var data = {'1':[],'2':[],'3':[]};
            for(var i = 0; i < res.data.length; i++)
            {
                if(Number(res.data[i].type2) <= 3 && Number(res.data[i].total) > 0){
                    data[res.data[i].type2].push({icon:ItemInfo[res.data[i].shopid].thumb,name:ItemInfo[res.data[i].shopid].name,num:res.data[i].total,xingji:res.data[i].type2,shopid:res.data[i].shopid});
                }
            }
            console.log(data);
            self.sanxing_list.array = data[3];
            self.erxing_list.array = data[2];
            self.yixing_list.array = data[1];
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.updateItem = function(cell, index)
    {
        cell.ItemIndex = index;
        cell.on(Laya.Event.CLICK,this,this.onListItemClick,[cell]);
    };

    //列表选中事件
    proto.onListItemClick = function(item)
    {
        if(this.selectedItem == item){
            this.selectedItem.getChildByName('selected').visible = false;
            this.selectedItem = null;
        }else {
            if(this.selectedItem){
                this.selectedItem.getChildByName('selected').visible = false;
            }
            item.getChildByName('selected').visible = true;
            this.selectedItem = item;
        }

        /*if(item.getChildByName('selected').visible){
            item.getChildByName('selected').visible = false;

        }else {*/

        //}
        console.log(this.selectedItem);
        var jifen = 0;
        /*for(var i = 0; i < this.selectedItem.length; i++)
        {
            console.log(config.Duihuan[this.selectedItem[i].dataSource.xingji]);*/
            if(this.selectedItem){
                jifen += config.Duihuan[this.selectedItem.dataSource.xingji];
            }

        //}
        this.jifen_curr.changeText(jifen);
    };

    proto.onDuiHuanBtnClick = function()
    {
        if(this.selectedItem){
            var shopid_arr = [];
            /*for(var i = 0; i < this.selectedItem.length; i++)
            {*/
                shopid_arr.push(this.selectedItem.dataSource.shopid);
            //}
            var shopids = shopid_arr.join(",");
            Utils.post('yan/jifen',{uid:localStorage.GUID,shopid:shopids},this.onDuiHuanReturn,onHttpErr);

        }else {
            var dialog = new CommomConfirm('请选择要兑换的香烟!');
            dialog.popup();
        }
    };

    proto.onDuiHuanReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            self.jifen_curr.changeText('0');
            self.jifen_all.changeText(res.data.jifen);
            self.selectedItem.getChildByName('selected').visible = false;
            self.selectedItem.dataSource.num --;
            if(self.selectedItem.dataSource.num <= 0)
            {
                self.selectedItem.parent.parent.deleteItem(self.selectedItem.ItemIndex);
            }
            self.selectedItem.parent.parent.refresh();
            self.selectedItem = null;
            var dialog = new CommomConfirm('兑换成功');
            dialog.popup();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onQianWangBtnClick = function()
    {
        this.close();
    };
})();