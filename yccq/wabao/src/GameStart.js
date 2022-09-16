var GameStart=(function(_super){
    var self;
     function GameStart(){
       
         GameStart.super(this);   
         self = this; 
         this.zOrder = 1000;
        //按钮点击事件
          this.startBtn.clickHandler = new Laya.Handler(this,this.onstartBtnClick);
          this.endBtn.clickHandler = new Laya.Handler(this,this.onendBtnClick);
     }


      //注册类
    Laya.class(GameStart,"GameStart",_super);
    var _proto = GameStart.prototype;

    _proto.onendBtnClick = function(){
       location.href = yccq_url;
    }

    _proto.onstartBtnClick = function(){
        
          //点击“开始”，从数据库获取解锁关卡数
          this.getPass();                   
    }

    _proto.getPass = function(){
        comfun.post('Hunt_game/getPass', {uid:localStorage.GUID}, this.ongetPassReturn, onHttpErr);
    };

    _proto.ongetPassReturn = function(res){
        
        console.log("当前关数",res.data.my_pass.pass);
        pass = res.data.my_pass.pass;
        play_times = res.data.my_pass.play_times;
        // console.log(res.data.my_record);
        for(var i in res.data.my_record){
            if(Object.prototype.hasOwnProperty.call(res.data.my_record,i)){
                record[res.data.my_record[i].pass] = 1;
            }
        }
        console.log(record);
        self.removeSelf();
        var guanqia = new NumPass(this);
        Laya.stage.addChild(guanqia);
        level = 200 + (pass-1)*150;
    }


 return GameStart;
})(ui.GameStartUI);