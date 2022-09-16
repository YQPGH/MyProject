/**
 * Created by 41496 on 2019/1/11.
 */
(function(){
    function Draws() {
        Draws.__super.call(this);
        this.goto.clickHandler = new Laya.Handler(this,this.showDraws);
    }
    Laya.class(Draws,'Draws',DrawsDialogUI);
    var proto = Draws.prototype;

    proto.init = function(shopid) {
        this.item_icon.skin = ItemInfo[shopid].thumb;
        this.item_name.text = ItemInfo[shopid].name;
    };

    proto.showDraws = function() {
        Laya.loader.load([{url:'dazhuanpan/haoyunlinmen_bg.png',type:Laya.Loader.IMAGE},{url:'dazhuanpan/haoyunlinmen_zhuanpan.png',type:Laya.Loader.IMAGE},{url:'res/atlas/dazhuanpan.atlas',typr:Laya.Loader.ATLAS}],new Laya.Handler(this,function(){
            var dialog = new DaZhuanPan();
            dialog.popup();
        }),null,Laya.Loader.TEXT);
        this.close();
    }
})();