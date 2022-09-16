/**
 * Created by 41496 on 2017/7/5.
 */
(function(){
    function TreeZhiwu2(type)
    {
        TreeZhiwu2.__super.call(this);
        this.initTree(building.Tree_zhiwu2);
        switch(type)
        {
            case 0:
                this.pivot(Math.floor(this.width/2),50);
                break;
            case 1:
                this.pivot(50,30);
                break;
            case 2:
                this.pivot(50,50);
                break;
            case 3:
                break;
        }

    }
    Laya.class(TreeZhiwu2,"TreeZhiwu2",MyTree);
})();