/**
 * Created by 41496 on 2018/9/28.
 */
(function(){
    function NengLiangCao(type)
    {
        NengLiangCao.__super.call(this);
        this.size(184,265);
        //this.scale(0.85,0.85);
        this.initBuilding(building.NengLiangCao,'tex/nengliangcao_text.png');
        this.pivot(92,210);
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);
        }
    }
    Laya.class(NengLiangCao,'NengLiangCao',Building);
    var proto = NengLiangCao.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('能量槽');
        Laya.loader.load('res/atlas/chongzi.atlas',Laya.Handler.create(this,function(){
            var dialog = new ChongziDialog();
            dialog.popup();
        }),null,Laya.Loader.ATLAS);

    }
})();