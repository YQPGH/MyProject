/**
 * Created by 41496 on 2017/7/5.
 */
(function(){
    function TreeZhiwu1(type)
    {
        TreeZhiwu1.__super.call(this);
        this.initTree(building.Tree_zhiwu1);
        switch(type)
        {
            case 0:
                this.pivot(Math.floor(this.width/2),64);
                break;
            case 1:
                break;
            case 2:
                break;
            case 3:
                break;
        }

    }
    Laya.class(TreeZhiwu1,"TreeZhiwu1",MyTree);
})();