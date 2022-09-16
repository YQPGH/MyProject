/**
 * Created by 41496 on 2017/6/23.
 */
(function(){
    var self = null;
    function FriendDialog()
    {
        FriendDialog.__super.call(this);
        self = this;
        this.tab_friend.selectHandler = new Laya.Handler(this,this.onTabSelected);

        this.list_friend.scrollBar.hide = true;//隐藏列表的滚动条。
        this.list_friend.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.list_friend.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.latest_friend.scrollBar.hide = true;//隐藏列表的滚动条。
        this.latest_friend.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.latest_friend.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.add_friend_btn.clickHandler = new Laya.Handler(this,this.onAddFriendBtnClick);

        this.list_friend.renderHandler = new Laya.Handler(this,this.updateItem);
        this.latest_friend.renderHandler = new Laya.Handler(this,this.updateItem);

        this.popupEffect = new Laya.Handler(this,this.myPopupEffect);
        this.closeEffect = new Laya.Handler(this,this.myCloseEffect);

        this.tuijian_btn.clickHandler = new Laya.Handler(this,this.getRandom);


    }
    Laya.class(FriendDialog,"FriendDialog",FriendUI);
    var proto = FriendDialog.prototype;

    proto.onOpened = function()
    {
        this.getFriendList();
    };

    proto.myPopupEffect = function(dialog)
    {
        dialog.scale(1,1);
        Laya.Tween.from(dialog,{y:Laya.stage.height},300,Laya.Ease.backOut,Laya.Handler.create(this,Dialog.manager.doOpen,[dialog]));

    };

    proto.myCloseEffect = function (dialog,type){
        Laya.Tween.to(dialog,{y:Laya.stage.height},300,Laya.Ease.strongOut,Laya.Handler.create(this,Dialog.manager.doClose,[dialog,type]));
    };

    proto.getFriendList = function()
    {
        Utils.post("friend/lists",{uid:localStorage.GUID},this.onFriendListReturn);
    };

    proto.onFriendListReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            var data = [];
            for(var i = 0,len = res.data.list.length; i < len; i++)
            {
                var obj_data = {id:res.data.list[i].id,nickname:res.data.list[i].nickname,thumb:res.data.list[i].user_thumb,level:res.data.list[i].game_lv,code:res.data.list[i].code};
                obj_data.yn_total = res.data.list[i].yannong_total;
                obj_data.yn_lv = res.data.list[i].yannong_lv;

                obj_data.jy_total = res.data.list[i].jiaoyi_total;
                obj_data.jy_lv = res.data.list[i].jiaoyi_lv;

                obj_data.pj_total = res.data.list[i].pinjian_total;
                obj_data.pj_lv = res.data.list[i].pinjian_lv;

                obj_data.zy_total = res.data.list[i].zhiyan_total;
                obj_data.zy_lv = res.data.list[i].zhiyan_lv;

                data.push(obj_data);
            }

            self.list_friend.array = data;
            self.list_friend.visible = true;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.show();
        }
    };

    proto.getLatestList = function()
    {
        Utils.post("friend/lists_visit",{uid:localStorage.GUID},this.onLatestListReturn);
    };

    proto.onLatestListReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            var data = [];
            for(var i = 0,len = res.data.length; i < len; i++)
            {
                var obj_data = {id:res.data[i].id,nickname:res.data[i].nickname,thumb:res.data[i].user_thumb,level:res.data[i].game_lv,code:res.data[i].code};
                    obj_data.yn_total = res.data[i].yannong_total;
                    obj_data.yn_lv = res.data[i].yannong_lv;

                    obj_data.jy_total = res.data[i].jiaoyi_total;
                    obj_data.jy_lv = res.data[i].jiaoyi_lv;

                    obj_data.pj_total = res.data[i].pinjian_total;
                    obj_data.pj_lv = res.data[i].pinjian_lv;

                    obj_data.zy_total = res.data[i].zhiyan_total;
                    obj_data.zy_lv = res.data[i].zhiyan_lv;

                data.push(obj_data);
         }
          
            self.latest_friend.array = data;
            self.latest_friend.visible = true;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.show();
        }
    };

    proto.onAddFriendBtnClick = function()
    {
        console.log('添加好友');
        Utils.post("friend/mark_url",{uid:localStorage.GUID},this.onUrlReturn);
    };

    proto.onUrlReturn = function(res)
    {
        console.log(res);
        var url = new UrlSearch(res.data.url);
        console.log(Request.domain+'?code='+url.code);
        url_code = '?code='+url.code;
        var userinfo = Laya.stage.getChildByName('MyGame').UI.userInfo;
        var lv_text = '烟厂萌新';
        if(Number(userinfo.game_lv) >= 21 && Number(userinfo.game_lv) <= 40){
            lv_text = '烟厂达人';
        }else if(Number(userinfo.game_lv) >= 41){
            lv_text = '烟厂大佬';
        }
        var nickname = userinfo.nickname;
        var share_title = '[' + nickname + ']想添加你为好友一起玩烟草传奇！';
        var share_content = '你的好友'+nickname+lv_text+'邀请你来玩烟草传奇！快来和我一起成为烟厂主吧！还有机会获得实体香烟等奖励喔！';
        //console.log(share_title, share_content);
		share(share_title, share_content);
        showShare();
    };

    proto.onTabSelected = function(index)
    {
        console.log(index);
        switch(index)
        {
            case 0:
                this.getFriendList();
                break;
            case 1:
                this.getLatestList();
                break;
        }
        this.view_stack.selectedIndex = index;
    };

    proto.updateItem = function(cell, index)
    {
        cell.on(Laya.Event.CLICK,this,this.onItemClick,[cell,index]);
    };

    proto.onItemClick = function(Item,index)
    {
        var dialog = new FriendInfo(Item,index);
        dialog.popup();
    };

    proto.getRandom = function()
    {
        var dialog = new TuiJian();
        dialog.popup();
    };


})();