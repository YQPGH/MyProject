/**
 * Created by 41496 on 2017/7/4.
 */
(function(){
    function TreeRongshu()
    {
        TreeRongshu.__super.call(this);
        this.initTree(building.Tree_rongshu);
        this.pivot(Math.floor(this.width/2),140);

        Laya.Animation.createFrames(['donghua/rongshu1.png','donghua/rongshu2.png','donghua/rongshu3.png','donghua/rongshu4.png','donghua/rongshu5.png','donghua/rongshu6.png'],"rongshu");


        var rand = Math.random();
        if(rand < 0.5){
            this.body = new Laya.Animation();
            this.body.interval = 300;
            //this.body.play(0,true,'rongshu');
            this.addChild(this.body);
            this.timer.loop(5000,this,this.PlayAni);
        }

    }
    Laya.class(TreeRongshu,"TreeRongshu",MyTree);
    var proto = TreeRongshu.prototype;

    proto.PlayAni = function()
    {
        this.body.play(0,false,'rongshu');
    }
})();