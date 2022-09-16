/**
 * Created by lkl on 2017/4/13.
 */
(function(){
    function MyHouse(type)
    {
        MyHouse.__super.call(this);

        this.initBuilding(building.MyHouse,"tex/wodexiaowu_text.png");
        this.pivot(Math.floor(this.width/2),200);
        if(type != 'FriendFarm'){
            this.on(Laya.Event.CLICK,this,this.onClick);
        }

    }
    Laya.class(MyHouse,"MyHouse",Building);
    var proto = MyHouse.prototype;

    proto.onClick = function()
    {
        if(hasScale) return;
        if(hasMove) return;
        console.log('我的小屋');
        var dialog = new UserInfoDialog();
        dialog.popup();
    };
})();