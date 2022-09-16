/**
 * Created by 41496 on 2017/6/8.
 */
(function(){
    //地图花草树木
    function MyTree(skin)
    {
        MyTree.__super.call(this);

    }
    Laya.class(MyTree,"MyTree",Laya.Sprite);
    var proto = MyTree.prototype;

    proto.initTree = function(skin)
    {
        this.loadImage(skin);
    }
})();