(function(){
    function FragmentGetTips(data){
        FragmentGetTips.__super.call(this);
        this.item_list.scrollBar.hide = true;//隐藏列表的滚动条。
        this.item_list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.item_list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
        var list_data = [];
        for(var i = 0; i < data.length; i++) {
            list_data.push({icon:ItemInfo[data[i].shop].thumb,item_name:data[i].name+'*'+data[i].total});
        }
        if(data.length == 1){
            this.item_list.itemRender.props.x = 98;
            this.item_list.spaceX = 0;
        }else if(data.length == 2){
            this.item_list.itemRender.props.x = 30;
            this.item_list.spaceX = 50;
        }else if(data.length >= 3){
            this.item_list.itemRender.props.x = 0;
            this.item_list.spaceX = 0;
        }
        this.item_list.array = list_data;
    }
    Laya.class(FragmentGetTips,'FragmentGetTips',FragmentGetTipsUI);
    var proto = FragmentGetTips.prototype;


})();