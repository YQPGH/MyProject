/**
 * Created by 41496 on 2017/7/11.
 */
(function(){
    //品鉴成功界面
    function PinjianSuccess(data) {
        PinjianSuccess.__super.call(this);
        this.name = 'pinjian_result';
        this.icon.skin = ItemInfo[data.shopid].thumb;
        this.yan_name.changeText(ItemInfo[data.shopid].name);
        this.closeHandler = new Laya.Handler(this,function(){
            getItem(data.shopid);
        });

        this.panel.vScrollBarSkin = null;

        this.result.changeText(data.result);

        this.goto_choujiang.clickHandler = new Laya.Handler(this,this.goToChouJiang);
        this.goto_dingdan.clickHandler = new Laya.Handler(this,this.goToDingDan);
    }
    Laya.class(PinjianSuccess,"PinjianSuccess",PinjianSuccessUI);
    var proto = PinjianSuccess.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 8 && ZhiYinManager.step2 == 1)
        {
            ZhiYinMask.instance().ZhiYinDialog();
        }
    };

    proto.onClosed = function()
    {
        if(ZhiYinManager.step1 == 8 && ZhiYinManager.step2 == 1)
        {
            ZhiYinMask.instance().ZhiYinClose();
        }
    };

    proto.goToChouJiang = function()
    {
        Dialog.manager.closeAll();
        Laya.stage.getChildByName('MyGame').map.mapMoveTo(24,17);
    };

    proto.goToDingDan = function()
    {
        Dialog.manager.closeAll();
        Laya.stage.getChildByName('MyGame').map.mapMoveTo(28,23);
    };
})();