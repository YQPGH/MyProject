/**
 * Created by 41496 on 2017/9/14.
 */
(function(){
    function ThiefDialog()
    {
        ThiefDialog.__super.call(this);
        var sayArr = [{say:'jiandie/duihua_1_1.png',btn:'jiandie/duihua_1_2.png'},{say:'jiandie/duihua_2_1.png',btn:'jiandie/duihua_2_2.png'}];
        var say = sayArr[Math.floor(Math.random()*sayArr.length)];
        this.say.skin = say.say;
        this.btn_ok.skin = say.btn;

        this.closeHandler = new Laya.Handler(this,this.onDialogClose);
    }
    Laya.class(ThiefDialog,'ThiefDialog',ThiefUI);
    var proto = ThiefDialog.prototype;

    proto.onDialogClose = function(name)
    {
        console.log(name);
        if(Dialog.YES == name)
        {
            var Thief = this.stage.getChildByName('MyGame').Thief;

            Utils.post('jiandie/clear',{uid:localStorage.GUID,number:Thief.number},function(res) {
                if(res.code == '0')
                {
                    if(Thief){
                        Thief.goHome();
                    }
                }else {
                    var dialog = new CommomConfirm(res.msg);
                    dialog.popup();
                }

            },onHttpErr);


        }
    };

})();