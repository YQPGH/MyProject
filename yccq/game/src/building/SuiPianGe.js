/**
 * Created by 41496 on 2019/01/30.
 */
(function(){
    function SuiPianGe(type)
    {
        SuiPianGe.__super.call(this);
        this.is_activity = false;
        this.size(356,300);
        //this.scale(0.85,0.85);
        this.initBuilding(building.SuiPianGe,'tex/shuipiange.png');
        this.pivot(130,210);

        this.lockTips = new Laya.Image('tex/huodongjieshu.png');
        this.lockTips.pos(110,150);
        this.addChild(this.lockTips);
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);
        }
        this.isActivity();
    }
    Laya.class(SuiPianGe,'SuiPianGe',Building);
    var proto = SuiPianGe.prototype;

    proto.isActivity = function(){
        var self = this;
        Utils.post('Fragment/is_activity',{uid:localStorage.GUID},function (res) {
            if(res.code == 0){
                if(res.data == '1'){
                    self.is_activity = false;
                    self.lockTips.visible = true;
                }else {
                    self.is_activity = true;
                    self.lockTips.visible = false;
                }
            }
        })
    };

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('能量槽');
        if(!this.is_activity){
            var dialog = new CommomConfirm('活动已结束');
            dialog.popup();
            return;
        }
        Laya.loader.load([{url:'fragment/suipiange_bg.png',type:Laya.Loader.IMAGE},{url:'fragment/xuanzebaoxiang_bg.png',type:Laya.Loader.IMAGE},{url:'fragment/xuanzehaoyou_bg.png',type:Laya.Loader.IMAGE},{url:'res/atlas/fragment.atlas',type:Laya.Loader.ATLAS}],new Laya.Handler(this,function(){
            var dialog = new Fragment();
            dialog.popup();
        }),null,Laya.Loader.TEXT);

    }
})();