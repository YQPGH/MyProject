/**
 * Created by 41496 on 2017/11/2.
 */
(function(){
    var self = null;
    function SelectYan(lv,caller)
    {
        SelectYan.__super.call(this);
        self = this;
        this.caller = caller;
        this.lv = lv;
        this.selectedItem = null;

        this.yan_list.scrollBar.hide = true;//隐藏列表的滚动条。
        this.yan_list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.yan_list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.yan_list.renderHandler = new Laya.Handler(this,this.updateItem);

        this.ok_btn.clickHandler = new Laya.Handler(this,this.onOkBtnClick);

        this.getMyYan();
    }
    Laya.class(SelectYan,'SelectYan',SelectYanUI);
    var proto = SelectYan.prototype;

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
            var data = [];
            for(var i = 0; i < res.data.length; i++)
            {
                if(Number(res.data[i].type2) == self.lv && Number(res.data[i].total) > 0){
                    data.push({icon:ItemInfo[res.data[i].shopid].thumb,name:ItemInfo[res.data[i].shopid].name,num:res.data[i].total,xingji:res.data[i].type2,shopid:res.data[i].shopid});
                }
            }
            console.log(data);
            self.yan_list.array = data;
            self.yan_list.visible = true;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.updateItem = function(cell, index)
    {
        cell.on(Laya.Event.CLICK,this,this.onListItemClick,[cell]);
    };

    //列表选中事件
    proto.onListItemClick = function(item)
    {
        if(this.selectedItem){
            this.selectedItem.getChildByName('selected').visible = false;
        }
        item.getChildByName('selected').visible = true;
        this.selectedItem = item;
    };

    proto.onOkBtnClick = function()
    {
        if(this.selectedItem){
            this.caller.setYan(this.selectedItem.dataSource.shopid);
            this.close();
            /*Laya.loader.load('luckdraw/jiangchi_bg.png',Laya.Handler.create(this,function(){
                if(this.lv ==4){
                    //var dialog = new ZY.ChouJiangZhong(this.selectedItem.dataSource.shopid);
                    //dialog.popup();

                }else if(this.lv ==5){
                    var dialog = new ZY.ChouJiangGao(this.selectedItem.dataSource.shopid);
                    dialog.popup();
                }
            }));*/
        }else {
            var dialog = new CommomConfirm('请选择一包香烟!');
            dialog.popup();
        }
    }

})();