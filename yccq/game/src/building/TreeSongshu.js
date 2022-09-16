/**
 * Created by 41496 on 2017/7/4.
 */
(function(){
    function TreeSongshu()
    {
        TreeSongshu.__super.call(this);
        this.initTree(building.Tree_songshu);
        this.pivot(Math.floor(this.width/2),135);
    }
    Laya.class(TreeSongshu,"TreeSongshu",MyTree);
})();