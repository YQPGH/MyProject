/**
 * Created by 41496 on 2017/7/6.
 */
(function(){
    function GongGaoLan(type)
    {
        GongGaoLan.__super.call(this);

        this.initBuilding(building.GongGaoLan,"tex/gonggaolan_text.png");
        this.pivot(Math.floor(this.width/2),210);
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);
        }

    }
    Laya.class(GongGaoLan,"GongGaoLan",Building);
    var proto = GongGaoLan.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('公告栏');
        var dialog = new OrderListDialog();
        dialog.popup();
    };
})();