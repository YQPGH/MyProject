/**
 * Created by 41496 on 2017/7/6.
 */
//小篱笆装饰物
(function(){
    function LibaSmall(type)
    {
        LibaSmall.__super.call(this);
        this.initTree(building.Liba_xiao);
        this.scaleX = 1.2;
        this.scaleY = 1.2;
        switch(type)
        {
            case 0:
                this.pivot(60,50);
                break;
            case 1:
                this.pivot(15,25);
                break;
            case 2:
                this.skewY = 180;
                this.pivot(60,45);
                break;
            case 3:
                this.skewY = 180;
                this.pivot(10,30);
                break;
        }
    }
    Laya.class(LibaSmall,"LibaSmall",MyTree);
})();