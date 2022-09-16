/**
 * Created by 41496 on 2017/5/12.
 */
(function(){
    //品鉴建筑
    function Pinjian(type)
    {
        Pinjian.__super.call(this);
        this.initBuilding(building.Pinjian,"tex/pingjian_text.png");
        this.pivot(Math.floor(this.width/2),225);
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);
        }

    }
    Laya.class(Pinjian,"Pinjian",Building);
    var proto = Pinjian.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('品鉴');
        var dialog = new PinjianDialog();
        dialog.popup();
    };
})();