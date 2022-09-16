/**
 * Created by 41496 on 2017/7/5.
 */
//篱笆装饰物
(function(){
    function Liba(type)
    {
        Liba.__super.call(this);
        this.initTree(building.Liba_da);
        switch(type)
        {
            case 0:
                this.pivot(75,55);
                break;
            case 1:
                this.pivot(20,30);
                break;
            case 2:
                this.skewY = 180;
                this.pivot(75,60);
                break;
            case 3:
                this.pivot(this.width,30);
                break;
        }
    }
    Laya.class(Liba,"Liba",MyTree);
})();