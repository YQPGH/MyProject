/**
 * Created by 41496 on 2018/6/12.
 */
(function(){
    function TestEnd()
    {
        TestEnd.__super.call(this);
        this.Jiangpin.clickHandler = Laya.Handler.create(this,this.onJiangPinClick,null,false);
    }
    Laya.class(TestEnd,'TestEnd',TestEndUI);
    var proto = TestEnd.prototype;

    proto.onJiangPinClick = function()
    {
        var dialog = new MyPrize();
        dialog.popup();
    };
})();