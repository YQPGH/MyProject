/**
 * Created by 41496 on 2019/1/3.
 */
(function(){
    function DaZhuanPan() {
        DaZhuanPan.__super.call(this);
        this.isRun = false;
        //this.item_list = [this.item0,this.item1,this.item2,this.item3,this.item4,this.item5,this.item6,this.item7,this.item8,this.item9,this.item10,this.item11];
        this.start_btn.clickHandler = new Laya.Handler(this,this.start);
        this.prize_arr = [
            {'id':1,'min':2,'max':41,'prize':'银元','num':2000,'icon':ItemIcon.MoneyIcon},
            {'id':2,'min':45,'max':60,'prize':'京东卡（40元E卡）','num':1,'icon':ItemIcon.MoneyIcon},
            {'id':3,'min':63,'max':103,'prize':'活性炭嘴棒','shopid':1002,'num':1},
            {'id':4,'min':107,'max':135,'prize':'闪电','num':15,'icon':ItemIcon.ShandianIcon},
            {'id':5,'min':138,'max':184,'prize':'银元','num':1000,'icon':ItemIcon.MoneyIcon},
            {'id':6,'min':188,'max':210,'prize':'京东卡（30元E卡）','num':1,'icon':ItemIcon.MoneyIcon},
            {'id':7,'min':213,'max':255,'prize':'二星咖香调香书','shopid':613,'num':1},
            {'id':8,'min':258,'max':283,'prize':'京东卡（10元E卡）','num':1,'icon':ItemIcon.MoneyIcon},
            {'id':9,'min':286,'max':358,'prize':'一星吕宋种子','shopid':204,'num':2}
        ];

        this.init();
    }
    Laya.class(DaZhuanPan,'DaZhuanPan',DaZhuanPanUI);
    var proto = DaZhuanPan.prototype;

    proto.init = function() {
        var self = this;
        Utils.post('Turntable/draw_times',{uid:localStorage.GUID},function(res){
            console.log(res);
            if(res.code == '0'){
                self.activity_num.text = res.data.draw_times;
                self.bean_num.text = res.data.ledou_draw_times;
                if(Number(res.data.draw_times) > 0){
                    self.start_btn.getChildByName('bean').visible = false;
                    self.start_btn.getChildByName('tips').visible = false;
                }else {
                    self.start_btn.getChildByName('bean').visible = true;
                    self.start_btn.getChildByName('tips').visible = true;
                }
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        });
    };

    proto.setItem = function(target,icon,name) {
        if(!target) return;
        target.getChildByName('icon').skin = icon;
        target.getChildByName('itemname').text = name;
    };

    proto.start = function() {
        if(this.isRun) return;
        if(Number(this.activity_num.text) > 0) {
            this.run();
        }else if(Number(this.bean_num.text) > 0) {
            var dialog = new DaZhuanPanConfirmUI();
            dialog.closeHandler = new Laya.Handler(this,function(name){
                if(name == Laya.Dialog.YES){
                    this.run();
                }
            });
            dialog.popup();
        }else {
            var dialog = new CommomConfirm('抽奖次数已用完');
            dialog.popup();
        }
    };

    proto.run = function() {
        this.isRun = true;
        var self = this;
        Utils.post('Turntable/start',{uid:localStorage.GUID},function(res){
            console.log(res);
            if(res.code == '0'){
                var prize = self.prize_arr[Number(res.data.index)-1];
                var a = parseInt(Math.random()*(prize.max-prize.min+1)+prize.min,10);
                console.log(a);
                Laya.Tween.to(self.zhuanpan,{rotation:-(2880+a)},10000,Laya.Ease.sineInOut,new Laya.Handler(self,function(){
                    this.isRun = false;
                    this.zhuanpan.rotation = this.zhuanpan.rotation%360;
                    //console.log(ItemInfo[res.shopid].name);
                    this.showResult(res.data);
                    this.init();
                    self.stage.getChildByName("MyGame").initUserinfo();
                }));
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }

        },onHttpErr);
    };

    proto.showResult = function(data) {
        var prize = this.prize_arr[Number(data.index)-1];
        var dialog = new DaZhuanPanResultUI();
        dialog.item_name.text = data.name+'*'+data.num;
        if(data.type == 'shop'){
            dialog.item_icon.skin = ItemInfo[data.shopid].thumb;
        }else {
            dialog.item_icon.skin = prize.icon;
        }
        dialog.popup();
    };

})();