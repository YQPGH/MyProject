/**
 * Created by 41496 on 2017/7/7.
 */
(function(){
    function TreeGrass(type)
    {
        TreeGrass.__super.call(this);
        this.initTree(building.Grass);
        switch(type)
        {
            case 0:
                this.pivot(10,10);
                break;
            case 1:
                this.pivot(34,34);
                break;
            case 2:
                this.pivot(15,40);
                break;
            case 3:
                this.pivot(65,15);
                break;
            case 4:
                this.pivot(-10,10);
                break;
            case 5:
                this.pivot(-10,20);
                break;
        }

    }
    Laya.class(TreeGrass,"TreeGrass",MyTree);
})();