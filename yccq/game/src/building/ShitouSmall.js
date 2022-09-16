/**
 * Created by 41496 on 2017/7/7.
 */
(function(){
    function ShitouSmall(type)
    {
        ShitouSmall.__super.call(this);
        this.initTree(building.ShitouSmall);
        //this.scaleX = 1.2;
        //this.scaleY = 1.2;
        switch(type)
        {
            case 0:
                this.pivot(10,0);
                break;
            case 1:
                this.skewY = 180;
                this.pivot(-5,15);
                break;
            case 2:
                this.skewX = -180;
                this.pivot(-5,15);
                break;
            case 3:
                this.skewY = 180;
                this.pivot(30,40);
                break;
            case 4:
                this.pivot(25,25);
                break;
        }

    }
    Laya.class(ShitouSmall,"ShitouSmall",MyTree);
})();