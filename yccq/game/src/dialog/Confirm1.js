/**
 * Created by 41496 on 2017/5/23.
 */
(function(){
    //购买确认弹出框
    function Confirm1(content,obj)
    {
        Confirm1.__super.call(this);
        this.name = 'confirm1';
        this.content.text = content;
    }
    Laya.class(Confirm1,"Confirm1",confirm1UI);
    var proto = Confirm1.prototype;
    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 4 && ZhiYinManager.step2 == 1){
            ZhiYinMask.instance().setZhiYin(2);
        }
        if((ZhiYinManager.step1 == 5 || ZhiYinManager.step1 == 6) && ZhiYinManager.step2 == 1){
            ZhiYinMask.instance().setZhiYin(1);
        }
        if(ZhiYinManager.step1 == 7 && ZhiYinManager.step2 == 2){
            ZhiYinMask.instance().setZhiYin(1);
        }
        if(ZhiYinManager.step1 == 8 && ZhiYinManager.step2 == 0){
            ZhiYinMask.instance().setZhiYin(2);
        }
    };
})();