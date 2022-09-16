/**
 * Created by 41496 on 2017/7/7.
 */
(function(){
    function ShijieSmall(type)
    {
        ShijieSmall.__super.call(this);
        this.initTree(building.ShijieSmall);
        switch(type)
        {
            case 0:
                this.pivot(20,20);
                break;
            case 1:
                this.skewY = 180;
                this.pivot(Math.floor(this.width/2),29);
                break;
            case 2:
                this.skewY = 180;
                this.pivot(45,45);
                break;
            case 3:
                this.skewY = 180;
                this.pivot(10,25);
                break;
        }

    }
    Laya.class(ShijieSmall,"ShijieSmall",MyTree);
})();