/**
 * Created by 41496 on 2018/10/26.
 */
(function(){
    function Doubel11(type,data)
    {
        Doubel11.__super.call(this);
        this.DType = type;
        this.Data = data;
        this.tipsArr = ['温馨提示：领取礼包后可进入【仓库】建筑查看。','温馨提示：领取礼包后进入【幸运抽奖】建筑即可使用抽奖道具参与抽奖。'];

        this.btn_lingqu.clickHandler = new Laya.Handler(this,this.onLingquBtnClick);
        this.list.renderHandler = new Laya.Handler(this,this.listRender);

        this.init();
    }
    Laya.class(Doubel11,'Doubel11',LdChangeGiftUI);
    var proto = Doubel11.prototype;

    proto.init = function()
    {
        if(this.DType == 1){
            this.daoju_title.visible = true;
            this.list.repeatX = 5;
            this.list.spaceX = 0;
        }

        if(this.DType == 2){
            this.choujiang_title.visible = true;
            this.list.repeatX = 3;
            this.list.spaceX = 110;
        }
        var data = [];
        for(var i = 0; i < this.Data.length; i++){
            data.push({shopid:this.Data[i].shopid,num:this.Data[i].shop_num,type:this.Data[i].type});
        }
        this.list.array = data;
        this.list.visible = true;
        this.tips.text = this.tipsArr[this.DType-1];
        this.tips.visible = true;

    };

    proto.listRender = function(cell,index)
    {
        var data = cell.dataSource;
        switch(data.type)
        {
            case 'shandian':
                cell.getChildByName('item_icon').skin = ItemIcon.ShandianIcon;
                cell.getChildByName('item_name').text = '闪电*'+data.num;
                break;
            case 'money':
                cell.getChildByName('item_icon').skin = ItemIcon.MoneyIcon;
                cell.getChildByName('item_name').text = '银元*'+data.num;
                break;
            default:
                cell.getChildByName('item_icon').skin = ItemInfo[data.shopid].thumb;
                cell.getChildByName('item_name').text = ItemInfo[data.shopid].name+'*'+data.num;
        }

    };

    proto.onLingquBtnClick = function()
    {
        var self =this;
        Utils.post('gift/getLdChangeGift',{uid:localStorage.GUID,type:this.DType},function(res){
            console.log(res);
            if(res.code == 0){
                for(var i = 0; i < res.data.list.length; i++){
                    switch(res.data.list[i].type)
                    {
                        case 'shandian':
                            getShandian(res.data.list[i].shop_num);
                            break;
                        case 'money':
                            getMoney(res.data.list[i].shop_num);
                            break;
                        default:
                            getItem(res.data.list[i].shopid,res.data.list[i].shop_num);
                    }
                }

            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
            self.close();
            self.stage.getChildByName("MyGame").initUserinfo();
        },onHttpErr);

    };
})();