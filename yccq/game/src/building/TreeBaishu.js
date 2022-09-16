/**
 * Created by 41496 on 2017/6/8.
 */
(function(){
    function TreeBaishu()
    {
        TreeBaishu.__super.call(this);
        this.initTree(building.Tree_baishu);
        this.pivot(Math.floor(this.width/2),145);
    }
    Laya.class(TreeBaishu,"TreeBaishu",MyTree);
})();