/**
 * Created by lkl on 2017/4/13.
 */
(function(){
    //真龙商行建筑类
    function ZLShop(type)
    {
        ZLShop.__super.call(this);
        this.initBuilding(building.ZLShop,"tex/zhenlongshanghang_text.png");
        this.pivot(Math.floor(this.width/2),220);
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);
        }

    }
    Laya.class(ZLShop,"ZLShop",Building);
    var proto = ZLShop.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('真龙商行');
        var dialog = new ZLDialog();
        dialog.popup();
    };
})();