/**
 * Created by 41496 on 2017/11/28.
 */
//游戏虫灾、旱灾、烘烤室事件管理器
(function(){
    var self = null;
    function ShiJianManager()
    {
        self = this;
        this.init();

    }
    Laya.class(ShiJianManager,'ShiJianManager');
    var proto = ShiJianManager.prototype;

    proto.init = function()
    {
        this.ShiJian_btn = Laya.stage.getChildByName('MyGame').UI.ShiJian_tips;
        this.ShiJian_btn.clickHandler = new Laya.Handler(this,this.openList);
        this.ShiJian_num = Laya.stage.getChildByName('MyGame').UI.ShiJian_num;

        Utils.post('event/lists',{uid:localStorage.GUID},this.onListDataReturn,onHttpErr);

    };

    proto.onListDataReturn = function(res)
    {
        console.log(res);
        if(res.code == 0){
            self.setShiJianNum(res.data.length);
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.openList = function()
    {
        Laya.loader.load('res/atlas/shijiang.atlas',Laya.Handler.create(this, function(){
            var dialog = new ShiJianList();
            dialog.popup();
        }), null, Laya.loader.TEXT);

    };

    proto.tips = function()
    {
        var ShiJianFlag = getShiJianTips();
        if(ShiJianFlag){
            setShiJianTips();
        }
        if(!ShiJianManager.isTips && ShiJianFlag && ShiJianManager.TipsType != 0){
            var dialog = new CommomConfirm(text[ShiJianManager.TipsType]);
            dialog.popup();
            ShiJianManager.isTips = true;
        }
    };

    proto.setShiJianNum = function(num)
    {
        if(num > 0){
            this.ShiJian_btn.visible = true;
        }else {
            this.ShiJian_btn.visible = false;
        }
        this.ShiJian_num.changeText(num);
    };

    ShiJianManager.instance=function(){
        if (!ShiJianManager._instance){
            ShiJianManager._instance=new ShiJianManager();
        }
        return ShiJianManager._instance;
    };

    var text = ['','好多虫子，田里突然出现了好多虫子，今天种植时可要注意除虫！','这天气可真热啊，土地都有点干旱了呢，种植时要注意灌溉哦~'];

    ShiJianManager._instance=null;
    ShiJianManager.isTips = false;
    ShiJianManager.TipsType = 0;
})();