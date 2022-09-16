/**
 * Created by 41496 on 2018/9/18.
 */
(function(){
    var self = null;
    function Holidays(list){
        Holidays.__super.call(this);
        self = this;
        this.init(list);
        this.lingqu_btn.clickHandler = new Laya.Handler(this,this.getHolidayGift);
    }
    Laya.class(Holidays,'Holidays',HolidaysUI);
    var proto = Holidays.prototype;

    proto.init = function(list){
        if(Number(list.money)) this.setItem(this.item0,'money',list.money);
        if(Number(list.shandian)) this.setItem(this.item1,'shandian',list.shandian);
        if(Number(list.shopid)&&Number(list.shop_num)) this.setItem(this.item2,'shop',list.shop_num,list.shopid);
    };

    proto.setItem = function(item,type,num,shopid){
        switch(type){
            case 'money':
                console.log(ItemIcon.MoneyIcon);
                item.getChildByName('icon').skin = ItemIcon.MoneyIcon;
                item.getChildByName('item_name').text = '银元*'+num;
                break;
            case 'shandian':
                console.log(ItemIcon.ShandianIcon);
                item.getChildByName('icon').skin = ItemIcon.ShandianIcon;
                item.getChildByName('item_name').text = '闪电*'+num;
                break;
            case 'shop':
                console.log(ItemInfo[shopid].thumb);
                item.getChildByName('icon').skin = ItemInfo[shopid].thumb;
                item.getChildByName('item_name').text = ItemInfo[shopid].name+'*'+num;
                break;
        }
    };

    proto.getHolidayGift = function(){
        Utils.post('User/getHolidayGift',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                if(Number(res.data.money)) getMoney(res.data.money);
                if(Number(res.data.shandian)) getShandian(res.data.shandian);
                if(Number(res.data.shopid) && Number(res.data.shop_num)) getItem(res.data.shopid,res.data.shop_num);
                self.stage.getChildByName('MyGame').initUserinfo();
                self.close();
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        });
    }
})();