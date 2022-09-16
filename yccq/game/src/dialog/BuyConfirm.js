/**
 * Created by 41496 on 2017/5/17.
 */
(function(){
    //购买确认弹出框
    var self = null;
    function BuyConfirm(Item)
    {
        BuyConfirm.__super.call(this);
        self = this;
        this.name = 'zlbuy';
        this.ItemData = Item.dataSource;
        console.log(this.ItemData);

        this.item_name.changeText(this.ItemData.name);
        this.item_cion.skin = this.ItemData.icon;
        //this.item_details.changeText(ItemInfo[this.ItemData.id].description);
        //console.log(this.details);
        var html = '<div style="width:100%;font-family:Arial;font-size: 18px;line-height: 22px;color:#582f11;">'+ItemInfo[this.ItemData.id].description+'</div>';
        this.details.innerHTML = html;
        this.buy_num.changeText(1);
        if(this.ItemData.type == 'tudi'){
            var curr_land_num = this.stage.getChildByName("MyGame").landArr.length;
            var price = (Number(this.ItemData.price) + (curr_land_num - 6) * 10000)*this.ItemData.saleNum;
        }else {

            var price = this.ItemData.price;
        }
        this.buy_totals.changeText(price);
        this.my_totals.changeText(this.stage.getChildByName("MyGame").UI.userInfo.money);

        this.buy_btn.clickHandler = new Laya.Handler(this,this.onBuyBTNClick);

        this.sub_btn.clickHandler = new Laya.Handler(this,this.onSubBTNClick);
        this.add_btn.clickHandler = new Laya.Handler(this,this.onAddBTNClick);
        if(this.ItemData.shenmi){
            this.sub_btn.visible = false;
            this.add_btn.visible = false;
        }else {
            this.sale.visible = Boolean(this.ItemData.isSale);
        }
        this.on(Laya.Event.CLICK,this,this.onBGClick);
        Utils.post('store/detail',{uid:localStorage.GUID,shopid:this.ItemData.id},function(res){
            if(res.code == 0 && res.data){
                self.kucun.text = res.data.total
            }
        });

    }
    Laya.class(BuyConfirm,"BuyConfirm",BuyConfirmUI);
    var proto = BuyConfirm.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 3){
            ZhiYinMask.instance().setZhiYin(2);
        }
    };

    proto.onBGClick = function()
    {
        if(ZhiYinManager.step1 == 3 && ZhiYinManager.step2 == 0){
            ZhiYinMask.instance().setZhiYin(3);
        }
    };

    proto.onBuyBTNClick = function()
    {
        var curr_land_num = this.stage.getChildByName("MyGame").landArr.length;
        var Max_land_num = config.landPosIndex.length;
        if(this.ItemData.type == 'tudi' && (curr_land_num == Max_land_num))
        {
            var dialog = new CommomConfirm("土地数量已达到上限");
            dialog.popup();
            return;
        }
        console.log('购买商品ID'+this.ItemData.id+'数量:'+Number(this.buy_num.text));

        Utils.post("shop/buy",{uid:localStorage.GUID,shopid:this.ItemData.id,total:Number(this.buy_num.text)},this.onBuyReturn,onHttpErr);

    };

    proto.onBuyReturn = function(res)
    {
        console.log(res);
        self.stage.getChildByName("MyGame").initUserinfo();
        if(res.code == 0)
        {
            if(self.ItemData.type == "tudi")
            {
                console.log("土地");
                var land_ids = res.data.land_id.split(",");
                console.log(land_ids);
                for(var i = 0,len = land_ids.length; i < len; i++)
                {
                    self.stage.getChildByName("MyGame").addLand(land_ids[i]);
                }

            }else if(self.ItemData.type == 'chongzi'){
                getItem(self.ItemData.id,1);
            }else {
                getItem(self.ItemData.id,self.buy_num.text);
            }
            self.ItemData.bought = true;

        }else
        {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
        self.close();
        if(ZhiYinManager.step1 == 3 && ZhiYinManager.step2 == 0){
            ZhiYinManager.instance().setGuideStep(3, 1);
            ZhiYinMask.instance().ZhiYinDialog();
        }else if(ZhiYinManager.step1 == 3 && ZhiYinManager.step2 == 1){
            ZhiYinManager.instance().setGuideStep(3, 2);
            ZhiYinMask.instance().ZhiYinDialog();
        }else if(ZhiYinManager.step1 == 3 && ZhiYinManager.step2 == 2){
            ZhiYinManager.instance().setGuideStep(3, 3, true);
            ZhiYinMask.instance().ZhiYinClose();
        }
    };

    proto.onSubBTNClick = function()
    {
        if(this.ItemData.shenmi) return;
        var num = Number(this.buy_num.text);
        if(num == 1) return;
        num --;
        if(this.ItemData.type == 'tudi'){
            var curr_land_num = this.stage.getChildByName("MyGame").landArr.length;
            var a = Number(this.ItemData.price) + (curr_land_num - 6) * 10000;
            var price = (num) * Number(a) + ((num) * ((num) -1)/2.0) * 10000;
        }else {
            var price = num*Number(this.ItemData.price);
        }
        this.buy_num.changeText(num);
        this.buy_totals.changeText(price);
    };

    proto.onAddBTNClick = function()
    {
        if(this.ItemData.shenmi) return;
        var num = Number(this.buy_num.text);
        var curr_land_num = this.stage.getChildByName("MyGame").landArr.length;
        var Max_land_num = config.landPosIndex.length;
        num ++;
        if(this.ItemData.type == 'tudi' && ((curr_land_num+num > Max_land_num) || curr_land_num+num > (6+Math.ceil(Number(this.stage.getChildByName("MyGame").UI.userInfo.game_lv)-3)/3.0)))
        {
            var dialog = new CommomConfirm("土地数量已达到上限");
            dialog.popup();
        }else
        {
            if(this.ItemData.type == 'tudi'){

                var a = Number(this.ItemData.price) + (curr_land_num - 6) * 10000;
                var price = (num) * Number(a) + ((num) * ((num) -1)/2.0) * 10000;
            }else {
                var price = num*Number(this.ItemData.price);
            }
            this.buy_num.changeText(num);
            this.buy_totals.changeText(price);
        }
        //if(num == 0) return;

        if(num == 10 && ZhiYinManager.step1 == 3 && ZhiYinManager.step2 == 2){
            ZhiYinMask.instance().setZhiYin(3);
        }


    };
})();