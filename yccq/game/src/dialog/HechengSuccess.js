/**
 * Created by 41496 on 2017/7/11.
 */
(function(){
    //品鉴成功界面
    function HechengSuccess(shopid) {
        HechengSuccess.__super.call(this);
        this.icon.skin = ItemInfo[shopid].thumb;
        this.closeHandler = new Laya.Handler(this,function(){
            getItem(shopid);
        });
    }
    Laya.class(HechengSuccess,"HechengSuccess",HeChengSuccessUI);
    var proto = HechengSuccess.prototype;
})();