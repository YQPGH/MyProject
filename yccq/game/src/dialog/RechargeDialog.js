/**
 * Created by 41496 on 2017/7/20.
 */
(function(){
    //充值弹窗
    var self = null;
    function RechargeDialog(type)
    {
        RechargeDialog.__super.call(this);
        self = this;
        this.type = type;
        this.isSale = 0;
        this.saleNum = 1;

        this.List.renderHandler = new Laya.Handler(this,this.updateItem);
        this.getIsSale();
    }
    Laya.class(RechargeDialog,'RechargeDialog',RechargeUI);
    var proto = RechargeDialog.prototype;

    proto.getIsSale = function()
    {
        Utils.post('user/is_sale_money_shandian',{uid:localStorage.GUID},function(res){
            if(res.code == '0')
            {
                self.isSale = Number(res.data.is_sale);
                self.saleNum = Number(res.data.sale_num);
                self.initList();
            }
        },onHttpErr);
    };

    proto.initData = function(data)
    {
        var result = [];
        for(var i = 0; i < data.length; i++)
        {
            result.push({icon:data[i].icon,num:data[i].num,song:data[i].song,bean:data[i].bean * this.saleNum});
        }
        return result;
    };

    proto.initList = function()
    {
        switch(this.type)
        {
            case 'money':
                this.title.skin = 'recharge/duiguanjinbi.png';
                this.tips.skin = 'recharge/duihuangshuoming_lebi.png';
                this.List.array = this.initData(config.Recharge.money);
                break;
            case 'shandian':
                this.title.skin = 'recharge/duihuanshandian.png';
                this.tips.skin = 'recharge/duihuangshuoming_sandian.png';
                this.List.array = this.initData(config.Recharge.shandian);
                break;
        }
    };

    proto.updateItem = function(cell, index)
    {
        var btn = cell.getChildByName('btn');
        btn.label = ''+cell.dataSource.bean;
        btn.clickHandler = new Laya.Handler(this,this.onBtnClick,[cell]);
        if(cell.dataSource.song){
            cell.getChildByName('song').changeText('送'+cell.dataSource.song);
        }else {
            cell.getChildByName('song').visible = false;
        }
        switch(this.type){
            case 'money':
                break;
            case 'shandian':
                cell.getChildByName('item').skin = 'userinfo/sandian.png';
        }
        if(this.isSale){
            cell.getChildByName('dazhe').visible = true;
        }else {
            cell.getChildByName('dazhe').visible = false;
        }
    };

    proto.onBtnClick = function(item)
    {
        var text = '兑换需要消耗';
        if(this.type == 'shandian'){
            text = '购买需要消耗';
        }
        text += item.dataSource.bean+'乐豆';
        var dialog = new Confirm1(text);
        dialog.closeHandler = new Laya.Handler(this,this.onOkBtnClick,[item]);
        dialog.popup();
    };


    proto.onOkBtnClick = function(item,name)
    {
        if(name == Dialog.YES){
            switch(this.type){
                case 'money':
                    Utils.post('user/ledou_to_money',{uid:localStorage.GUID,number:item.dataSource.bean},this.onDataReturn,onHttpErr,item.dataSource.num);
                    break;
                case 'shandian':
                    Utils.post('user/buy_shandian',{uid:localStorage.GUID,number:item.dataSource.bean},this.onDataReturn,onHttpErr,item.dataSource.num);
            }

        }
    };

    proto.onDataReturn = function (res,num)
    {
        if(res.code == 0)
        {
            switch(self.type){
                case 'money':
                    getMoney(num);
                    break;
                case 'shandian':
                    getShandian(num);
            }

            self.stage.getChildByName('MyGame').initUserinfo();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    }
})();