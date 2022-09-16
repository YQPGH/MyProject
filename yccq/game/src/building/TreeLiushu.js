/**
 * Created by 41496 on 2017/7/4.
 */
(function(){
    function TreeLiushu()
    {
        TreeLiushu.__super.call(this);
        this.initTree(building.Tree_liushu);
        this.pivot(Math.floor(this.width/2),105);

        Laya.Animation.createFrames(['donghua/liushu1.png','donghua/liushu2.png','donghua/liushu3.png','donghua/liushu4.png','donghua/liushu5.png','donghua/liushu6.png'],"liushu");


        var rand = Math.random();
        if(rand < 0.5){
            this.body = new Laya.Animation();
            this.body.interval = 300;
            this.body.play(0,true,'liushu');
            this.addChild(this.body);
        }

    }
    Laya.class(TreeLiushu,"TreeLiushu",MyTree);
})();