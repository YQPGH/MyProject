/**
 * Created by 41496 on 2017/6/16.
 */
(function(){
    //路边摊下架
    var self = null;
    function LBT_XJ(cell)
    {
        LBT_XJ.__super.call(this);
        self = this;
        this.cell = cell;

        this.icon.skin = this.cell.dataSource.icon;
        this.num.changeText(this.cell.dataSource.num);
        this.price.changeText(this.cell.dataSource.price);

        this.delete_btn.clickHandler = new Laya.Handler(this,this.onDeleteBtnClick);
        this.ad_btn.clickHandler = new Laya.Handler(this,this.onAdBtnClick);

        /*if(this.cell.dataSource.status == '0')
        {
            //this.delete_btn.disabled = true;
            //this.ad_btn.disabled = true;
        }*/

    }
    Laya.class(LBT_XJ,"LBT_XJ",LBT_XJUI);
    var proto = LBT_XJ.prototype;

    proto.onDeleteBtnClick = function()
    {
        console.log('下架number'+this.cell.dataSource.number);
        if(this.cell.dataSource.status == '0')
        {
            var dialog = new CommomConfirm('商品还在广告中，还不能下架哦');
            dialog.popup();
        }else {
            Utils.post("market/stop",{uid:localStorage.GUID,number:this.cell.dataSource.number},this.onMarketStopReturn);
        }


    };

    proto.onMarketStopReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            self.cell.ItemEmpty = true;
            self.cell.dataSource = null;
            self.cell.getChildByName('jinbi').visible = false;
            self.cell.getChildByName('price').changeText("");
            self.cell.getChildByName('num').changeText("");
            self.cell.getChildByName('gg').visible = false;
            self.cell.getChildByName('icon').skin = null;
            self.close();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onAdBtnClick = function()
    {
        console.log('打广告number'+this.cell.dataSource.number);
        var dialog = new Confirm1('发布广告可使其它玩家看到自己售卖的物品，是否确认使用1乐豆发布广告');
        dialog.popup();
        dialog.closeHandler = new Laya.Handler(this,function(name){
            if(Laya.Dialog.YES == name){
                Utils.post("market/restart",{uid:localStorage.GUID,number:this.cell.dataSource.number},this.onMarketRestartReturn);
            }
        });

    };

    proto.onMarketRestartReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            self.cell.getChildByName('gg').visible = true;
            //self.delete_btn.disabled = true;
            //self.ad_btn.disabled = true;
            self.stage.getChildByName('MyGame').initUserinfo();
            var dialog = new CommomConfirm('发布广告成功');
            dialog.popup();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    }
})();