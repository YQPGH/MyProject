var NumPass = (function (_super) {
    var self;
    function NumPass() {
        
        NumPass.super(this);
        self = this;
       
        // this.zOrder = 1000;
        var ary = [];
        // console.log(pass);
        for (var i = 0; i < 6; i++) {
            // console.log('返回值',record[i+1]);
            if (i < pass) {
                if (!record[i + 1]) {
                    ary.push(arr_guanqia[i]);
                } else {
                    var total = i + 1;
                    ary.push({
                        bg: 'guanqia/num' + total + '.png'
                    });                
                }
            } else {
                var count = i + 1;
                // console.log(count);
                ary.push({
                    bg: 'guanqia/lock_' + count + '.png'
                });
            }
        }
        // console.log(this.list);
        this.list.array = ary;
        this.list.selectEnable = true;
        this.list.selectHandler = new Laya.Handler(this, this.onSelect);
        this.arrowBtn.clickHandler = new Laya.Handler(this, this.onarrowBtnClick);
        this.psBtn.clickHandler = new Laya.Handler(this, this.onpsBtnClick);
       
    }


    //注册类
    Laya.class(NumPass, "NumPass", _super);
    var _proto = NumPass.prototype;


    //选择关卡
    _proto.onSelect = function (index) {
       
        this.index_1 = index;
        console.log('剩余免费次数='+play_times);
        console.log("当前选择的索引：" + index);
            if (index + 1 <= pass) {
                if (record[index + 1]) {
                    console.log(record[index + 1]);
                    this.PromptShow();
                    console.log("该关卡今天已通关");
                } else if(play_times > 0){
                    //更新数据库的挑战券数量
                    this.updatePlayTimes();
                    this.GetGamePass();
                }else{
                    console.log("今日挑战券已经用完");
                    this.Confirm();
                    }
            }else {
               this.PromptShow();
               this.dia.content.changeText("该关卡尚未解锁");
         }
    }

    //获取关卡
    _proto.GetGamePass = function(){
            pass = this.index_1 + 1;
            console.log(pass);
            level = 200 + (pass - 1) * 150;
            addStage();
            Laya.stage.removeChild(this);
            if (pass >= 2) {
                //获取上一关
                var last = pass - 1;
                if (last) {
                    spliceArray();
                }
                mineral();
            }
            if (score < level) {
                spliceArray();
                mineral();
            }
            if (pass == 1) {
                spliceArray();
                mineral(); 
            }
            this.che = new CarMove();
            Laya.stage.addChild(this.che);

            var dialog = new PassLevel(this);
            dialog.popup();  
    }

    //提示是否继续游戏
    _proto.Confirm = function(){
      
            var confirm = new dialogConfirmUI();    
                confirm.content.text = '需要消耗2个乐豆，是否继续闯关？';
                confirm.closeHandler = new Laya.Handler(this, this.onDialogClose);
                confirm.popup();
    }

    //确认框关闭
    _proto.onDialogClose = function(name){
        console.log('当前索引：'+self.index_1);
        if(name == 'yes'){    
            comfun.post('Hunt_game/beans', {uid:localStorage.GUID}, this.ConfirmGame, onHttpErr);       
        }else{
             
             this.removeSelf();
             MyGameStart();
        }
    }

    _proto.ConfirmGame =  function(res){
        // console.log(res);
        if(res.code > 0){
             var dialog = new dialogConfirm1UI();
             dialog.content.text = res.msg; 
             dialog.closeHandler = new Laya.Handler(self, function(){
                     self.removeSelf();
                     MyGameStart();
             });
             dialog.popup();   
            
        }else{
            self.GetGamePass();
           
        }
         
    }

    //提示关卡是否已通关
    _proto.PromptShow = function () {

        this.dia = new dialogConfirm1UI();
        this.dia.content.changeText("该关卡今天已通关！");
        this.dia.popup();
    }


    _proto.updatePlayTimes = function () {
        comfun.post('Hunt_game/updatePlayTimes', {uid: localStorage.GUID}, this.onupdatePlayTimesReturn, onHttpErr);
    };

    _proto.onupdatePlayTimesReturn = function (res) {
        
        console.log("更新后的挑战券=", res.data.my_pass.play_times);
        play_times = res.data.my_pass.play_times;
    }

    //返回游戏开始界面
    _proto.onarrowBtnClick = function () {    
        this.removeSelf();    
        MyGameStart();
    }

    //游戏说明
    _proto.onpsBtnClick = function () { 
         
        var desc = new game_desc(this);
        desc.popup();
    }

    return NumPass;
})(ui.NumPassUI);