/**
 * Created by 41496 on 2017/7/6.
 */
(function(){
    function Shitou(type)
    {
        Shitou.__super.call(this);
        this.initTree(building.Shitou);
        //this.scaleX = 1.2;
        //this.scaleY = 1.2;
        switch(type)
        {
            case 0:
                this.pivot(Math.floor(this.width/2),28);
                break;
            case 1:
                this.skewY = 180;
                this.pivot(45,30);
                break;
        }

    }
    Laya.class(Shitou,"Shitou",MyTree);
})();