/**
 * Created by 41496 on 2017/9/8.
 */
//购买间谍
(function(){
    var self = null;
    function JianDieBuy()
    {
        JianDieBuy.__super.call(this);
        self = this;
        this.btn_guyong.clickHandler = new Laya.Handler(this,this.onBuyBtnClick);
    }
    Laya.class(JianDieBuy,'JianDieBuy',jiandieUI);
    var proto = JianDieBuy.prototype;

    proto.onBuyBtnClick = function()
    {
        var dialog = new Confirm1('雇佣间谍(7天)需要消耗20000银元');
        dialog.closeHandler = new Laya.Handler(this,this.onConfirmClose);
        dialog.popup();
    };

    proto.onConfirmClose = function(name)
    {
        if(Dialog.YES == name){
            Utils.post('jiandie/zu_jiandie',{uid:localStorage.GUID},this.onBuyReturn,onHttpErr);
        }
    };

    proto.onBuyReturn = function(res)
    {
        
        if(res.code == '0')
        {
            var MyGame = Laya.stage.getChildByName('MyGame');
            var firstTime = !Boolean(Number(ZhiYinManager.jiandie));
            MyGame.initJianDie({data:{zu:{start_time:res.data.start_time,stop_time:res.data.stop_time}},time:res.time},firstTime);
            MyGame.initUserinfo();
            MyGame.NPC.NPCEnable = false;
            self.btn_guyong.disabled = true;
            self.close();
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }

    }
})();