/**
 * Created by 41496 on 2017/8/7.
 */
(function(){
    function Gongren()
    {
        Gongren.__super.call(this);
        Laya.Animation.createFrames(['donghua/yanchanggongren_1_1.png','donghua/yanchanggongren_1_2.png','donghua/yanchanggongren_1_3.png','donghua/yanchanggongren_1_4.png','donghua/yanchanggongren_1_5.png','donghua/yanchanggongren_1_6.png','donghua/yanchanggongren_1_7.png','donghua/yanchanggongren_1_8.png'],'gongren_right');
        Laya.Animation.createFrames(['donghua/yanchanggongren_2_1.png','donghua/yanchanggongren_2_2.png','donghua/yanchanggongren_2_3.png','donghua/yanchanggongren_2_4.png','donghua/yanchanggongren_2_5.png','donghua/yanchanggongren_2_6.png','donghua/yanchanggongren_2_7.png','donghua/yanchanggongren_2_8.png'],'gongren_left');

        this.body = new Laya.Animation();
        this.body.interval = 100;
        this.addChild(this.body);

        this.PlayAni('up');
        this.pivot(21,88);

    }
    Laya.class(Gongren,'Gongren',Laya.Sprite);
    var proto = Gongren.prototype;

    proto.PlayAni = function(type)
    {
        switch(type){
            case 'right':
                this.body.play(0,true,'gongren_right');
                this.skewY = 0;
                break;
            case 'left':
                this.body.play(0,true,'gongren_left');
                this.skewY = 0;
                break;
            case 'down':
                this.body.play(0,true,'gongren_right');
                this.skewY = 180;
                break;
            case 'up':
                this.body.play(0,true,'gongren_left');
                this.skewY = 180;
        }
    }
})();