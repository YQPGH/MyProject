/**
 * Created by 41496 on 2017/10/11.
 */
(function(){
    function YouLeChang(type)
    {
        YouLeChang.__super.call(this);
        this.size(897,575);
        this.initBuilding(building.YouLeChang,'tex/youxiguanqia.png');
        this.pivot(450,350);
        this.scale(0.7,0.7);
        var name = this.getChildByName('BuildingName');
        name.pos(300,name.y);
        name.scale(1.3,1.3);
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);

        }
    }
    Laya.class(YouLeChang,'YouLeChang',Building);
    var proto = YouLeChang.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('娱乐场');
        var dialog = new YouLeChangDialog();
        dialog.popup();
    }
})();