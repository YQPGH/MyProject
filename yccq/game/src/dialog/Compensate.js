/**
 * Created by 41496 on 2018/7/26.
 */
(function(){
    var self = null;
    function Compensate(content,id)
    {
        Compensate.__super.call(this);
        self = this;
        this.content.text = content;
        this.id = id;
        this.closeHandler = new Laya.Handler(this,this.onCloseClick);
    }
    Laya.class(Compensate,'Compensate',confirmUI);
    var proto = Compensate.prototype;

    proto.onCloseClick = function(name)
    {
        Utils.post("Compensate/getCompensate",{uid:localStorage.GUID,id:this.id},function(res){
            if(res.code == '0')
            {
                if(res.data.money > 0) getMoney(res.data.money);
                if(res.data.shandian > 0) getShandian(res.data.shandian);
                if(res.data.shopid > 0) getItem(res.data.shopid, res.data.shop_num);
                self.stage.getChildByName("MyGame").initUserinfo();
            }
        },null);
    }
})();