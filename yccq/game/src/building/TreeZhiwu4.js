/**
 * Created by 41496 on 2017/7/5.
 */
(function(){
    function TreeZhiwu4(type)
    {
        TreeZhiwu4.__super.call(this);
        this.initTree(building.Tree_zhiwu4);
        switch(type)
        {
            case 0:
                this.pivot(Math.floor(this.width/2),45);
                break;
            case 1:
                this.pivot(60,30);
                break;
            case 2:
                this.pivot(20,30);
                break;
            case 3:
                this.pivot(45,30);
                break;
        }

    }
    Laya.class(TreeZhiwu4,"TreeZhiwu4",MyTree);
})();