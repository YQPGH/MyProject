/**
 * Created by 41496 on 2017/6/28.
 */
(function(){
    var self = null;
    function FriendInfo(Item,index)
    {
        FriendInfo.__super.call(this);
        self = this;
        this.FriendItem = Item;
        this.ItemIndex = index;
        
        this.nickname.changeText(this.FriendItem.dataSource.nickname);
        this.thumb.skin = this.FriendItem.dataSource.thumb;
        this.achievement.changeText(this.FriendItem.dataSource.level);

        var friend_data = Item.dataSource;
        if(friend_data.yn_lv == '0'){
            this.PlantAchievement.changeText('无');
        }else {
            this.PlantAchievement.changeText(config.Achievement.Yannong.name[friend_data.yn_lv-1]);
        }
        if(friend_data.yn_lv > 0) this.PlantIcon.skin = 'userinfo/Plant' + friend_data.yn_lv + '.png';

        if(friend_data.jy_lv == '0'){
            this.JiaoyiAchievement.changeText('无');
        }else {
            this.JiaoyiAchievement.changeText(config.Achievement.Jiaoyi.name[friend_data.jy_lv-1]);
        }
        if(friend_data.jy_lv > 0) this.JiaoyiIcon.skin = 'userinfo/Jiaoyi' + friend_data.jy_lv + '.png';

        if(friend_data.pj_lv == '0'){
            this.PinjianAchievement.changeText('无');
        }else {
            this.PinjianAchievement.changeText(config.Achievement.Pinjian.name[friend_data.pj_lv-1]);
        }
        if(friend_data.pj_lv > 0) this.PinjianIcon.skin = 'userinfo/Pinjian' + friend_data.pj_lv + '.png';

        if(friend_data.zy_lv == '0'){
            this.ZhiyanAchievement.changeText('无');
        }else {
            this.ZhiyanAchievement.changeText(config.Achievement.Zhiyan.name[friend_data.zy_lv-1]);
        }
        if(friend_data.zy_lv > 0) this.ZhiyanIcon.skin = 'userinfo/Zhiyan' + friend_data.zy_lv + '.png';

        this.delete_btn.clickHandler = new Laya.Handler(this,this.onDeleteBtnClick);
        this.visit_btn.clickHandler = new Laya.Handler(this,this.onVisitBtnClick);

    }
    Laya.class(FriendInfo,'FriendInfo',FriendInfoUI);
    var proto = FriendInfo.prototype;

    proto.onDeleteBtnClick = function()
    {
        var dialog = new Confirm1('确定要删除该好友吗？');
        dialog.closeHandler = new Laya.Handler(this,function(name){
            if(Dialog.YES == name){
                Utils.post('friend/delete',{uid:localStorage.GUID,id:this.FriendItem.dataSource.id},this.onDeleteReturn);
            }
        });
        dialog.popup();
    };

    proto.onDeleteReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            self.FriendItem.parent.parent.deleteItem(self.ItemIndex);
            self.close();
        }
    };

    proto.onVisitBtnClick = function()
    {
         console.log("进入好友农场");
         Laya.stage.getChildByName('MyGame').map.tiledMap.destroy();
         Laya.stage.getChildByName('MyGame').map.beforeMapDestroy();
         Laya.stage.getChildByName("MyGame").map.destroy();
         Laya.stage.getChildByName("MyGame").destroy();
         //Laya.stage.getChildByName("mapSprite").destroy();

         ChongziManager.destroy();

         this.stage.offAll();
         Dialog.manager.closeAll();

         var FriendFarm = new Farm(this.FriendItem.dataSource);
         Laya.stage.addChild(FriendFarm);
    };

})();