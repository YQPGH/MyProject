/**
 * Created by 41496 on 2017/4/17.
 */
(function(){
    //购买确认确认弹出框
    function CommomConfirm(content,obj)
    {
        CommomConfirm.__super.call(this);
        this.content.text = content;
    }
    Laya.class(CommomConfirm,"CommomConfirm",confirmUI);
    var proto = CommomConfirm.prototype;
})();