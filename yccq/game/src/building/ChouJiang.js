/**
 * Created by 41496 on 2017/10/19.
 */
(function(){
    function ChouJiangBuilding(type)
    {
        ChouJiangBuilding.__super.call(this);
        this.size(249,265);
        this.scale(0.85,0.85);
        this.initBuilding(building.ChouJiang,'tex/xingyuchoujiang.png');
        this.pivot(100,200);
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);
        }
    }
    Laya.class(ChouJiangBuilding,'ChouJiangBuilding',Building);
    var proto = ChouJiangBuilding.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('抽奖');
        var dialog = new JiangChi();
        dialog.popup();
    }
})();