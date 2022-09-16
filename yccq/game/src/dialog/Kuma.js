(function(){
    function Kuma(data){
        Kuma.__super.call(this);
        this.ListData = data;
        this.list.scrollBar.hide = true;//隐藏列表的滚动条。
        this.list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
        this.lingqu_btn.clickHandler = new Laya.Handler(this, this.getProp);

        this.list.scrollBar.changeHandler = new Laya.Handler(this,this.onScrollBarChange);
        this.init();
    }
    Laya.class(Kuma, 'Kuma', kumaUI);
    var proto = Kuma.prototype;

    proto.init = function(){
        var data = [];
        for(var i = 0; i < this.ListData.length; i++) {
            data.push({icon:ItemIcon.MoneyIcon,item_name:'银元*'+this.ListData[i].money});
            data.push({icon:ItemInfo[this.ListData[i].shopid].thumb,item_name:ItemInfo[this.ListData[i].shopid].name+"*"+this.ListData[i].shop_num});
        }
        if(this.ListData.length == 1) {
            this.list.spaceX = 100;
        }
        this.list.array = data;
    };

    proto.onScrollBarChange = function(value)
    {
        if(this.list.length <=2) return;
        (value <= this.list.scrollBar.min)?this.tips_left.visible = false:this.tips_left.visible = true;
        (value >= this.list.scrollBar.max)?this.tips_right.visible = false:this.tips_right.visible = true;
    };

    proto.getProp = function() {
        var self = this;
        Utils.post("kuma/getProp",{uid:localStorage.GUID},function(res){
            if(res.code == "0") {
                getMoney(res.data.money);
                var data = [];
                for(var i in res.data.list) {
                    data.push({shopid:res.data.list[i].shopid, num: res.data.list[i].shop_num});
                }
                getItem(data);
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
            self.close();
        },onHttpErr);
    }
})();