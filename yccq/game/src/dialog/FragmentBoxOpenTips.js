(function(){
    function FragmentBoxOpenTips(data){
        FragmentBoxOpenTips.__super.call(this);
        this.itemData = data;
        this.item_icon.skin = data.icon;
        this.item_tips.text = '恭喜开箱获得'+data.name+'*'+data.num;
        this.btn_get_item.clickHandler = new Laya.Handler(this,this.getItem);
    }
    Laya.class(FragmentBoxOpenTips,'FragmentBoxOpenTips',box_open_tipsUI);
    var proto = FragmentBoxOpenTips.prototype;

    proto.getItem = function() {
        //getItem(this.itemData.shopid,this.itemData.num);
        this.close();
    }
})();