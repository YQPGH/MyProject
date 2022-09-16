(function(){
    function FragmentNewer(data){
        FragmentNewer.__super.call(this);
        this.item_list.scrollBar.hide = true;
        this.item_list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.item_list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
        this.item_list.array = data;
    }
    Laya.class(FragmentNewer,'FragmentNewer',FragmentNewerUI);
    var proto = FragmentNewer.prototype;
})();