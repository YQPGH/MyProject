/**
 * Created by 41496 on 2017/7/5.
 */
(function(){
    function TreeZhiwu3(type)
    {
        TreeZhiwu3.__super.call(this);
        this.initTree(building.Tree_zhiwu3);
        switch(type)
        {
            case 0:
                this.pivot(Math.floor(this.width/2),44);
                break;
            case 1:
                this.pivot(0,30);
                break;
            case 2:
                this.pivot(10,35);
                break;
            case 3:
                this.pivot(45,30);
                break;
        }
    }
    Laya.class(TreeZhiwu3,"TreeZhiwu3",MyTree);
})();