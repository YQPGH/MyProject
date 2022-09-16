(function(){
    function CarMove(){
         CarMove.__super.call(this);
         this.name = 'CarMove';
         this.My_CarMove();
    }

    Laya.class(CarMove,'CarMove',CarMoveUI);
    var proto = CarMove.prototype;

     proto.My_CarMove = function(){
        Laya.Animation.createFrames(['role/jueshe_2_1.png', 'role/jueshe_2_2.png'], "role");
        
        this.body = new Laya.Animation();
        this.body.interval = 300;
        this.body.play(0, true, 'role'); 
        this.che.addChild(this.body);

        this.bod = new Laya.Animation();
        this.bod.interval = 300;
        this.bod.play(0, true, 'yan'); 
        this.bod.pos(160,-20);
        this.che.addChild(this.bod);

        Laya.Tween.to(this.che,
		{
			x: 402
		}, 4000,null,Laya.Handler.create(this,function(){
            this.bod.clear();
        }));

    }
  

})();


