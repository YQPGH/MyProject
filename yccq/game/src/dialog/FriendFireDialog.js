/**
 * Created by 41496 on 2018/10/19.
 */
(function(){
    function FriendFireDialog(){
        FriendFireDialog.__super.call(this);
        this._fireTime = 0;
        this.fire_btn.clickHandler = new Laya.Handler(this,this.sendFireStart);
        this.closeHandler = new Laya.Handler(this,this.onDialogClose);
        this.getFireStatus();
    }
    Laya.class(FriendFireDialog,'FriendFireDialog',FireDialogUI);
    var proto = FriendFireDialog.prototype;

    proto.getFireStatus = function(){
        var self = this;
        Utils.post('fire/friend_fire_status',{uid:localStorage.GUID,code:localStorage.FUID},function(res){
            console.log(res);
            if(res.code == '0'){
                if(res.data){
                    self.fireStart(res.data.start_time,res.data.stop_time,res.time);
                }
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.sendFireStart = function(){
        var self = this;
        Utils.post('fire/start',{uid:localStorage.GUID,code:localStorage.FUID},function(res){
            console.log(res);
            if(res.code == '0'){
                self.fireStart(res.data.start_time,res.data.stop_time,res.time);
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.fireStart = function(start,stop,now){
        var start_time = Utils.strToTime(start);
        var stop_time = Utils.strToTime(stop);
        var now_time = Utils.strToTime(now);
        var all_time = stop_time - start_time;
        this._fireTime = all_time - (now_time - start_time);
        if(this._fireTime > 0){
            this.clearCountDown();
            this.timer.loop(1000,this,this.countdown);
            this.fire.visible = true;
        }
    };

    proto.countdown = function(){
        this._fireTime -= 1;
        if(this._fireTime<=0){
            this._fireTime = 0;
            this.clearCountDown();
        }
    };

    proto.clearCountDown = function(){
        this.timer.clear(this,this.countdown);
        this.fire.visible = false;
    };

    proto.onDialogClose = function(){
        this.clearCountDown();
    }
})();