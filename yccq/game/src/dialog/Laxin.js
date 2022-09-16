(function(){
    function Laxin() {
        Laxin.__super.call(this);
        this._invite_num = 0;
        this._total_ticket = 0;
        this._current_num = 0;
        this.userInfo = Laya.stage.getChildByName('MyGame').UI.userInfo;
        this.left_side_menu.selectHandler = new Laya.Handler(this, this.onMenuChange)//this.ViewStack.setIndexHandler;
        //我的进度列表设置
        this.newer_list.renderHandler = new Laya.Handler(this, this.onNewerListRender);
        this.newer_list.scrollBar.hide = true;
        this.newer_list.scrollBar.elasticBackTime = 200;
        this.newer_list.scrollBar.elasticDistance = 50;
        this.newer_list.scrollBar.changeHandler = new Laya.Handler(this, this.onNewerListScrollBarChange);

        //我的召集列表设置
        this.zhaoji_list.renderHandler = new Laya.Handler(this, this.onZhaojiListRender);
        this.zhaoji_list.scrollBar.hide = true;
        this.zhaoji_list.scrollBar.elasticBackTime = 200;
        this.zhaoji_list.scrollBar.elasticDistance = 50;
        this.zhaoji_list.scrollBar.changeHandler = new Laya.Handler(this, this.onMyListScrollBarChange);

        //好友列表设置
        this.friend_list.renderHandler = new Laya.Handler(this, this.onFriendListRender);
        this.friend_list.scrollBar.hide = true;
        this.friend_list.scrollBar.elasticBackTime = 200;
        this.friend_list.scrollBar.elasticDistance = 50;
        this.friend_list.selectEnable = true;
        this.friend_list.selectHandler = new Laya.Handler(this, this.onSelectFriend);
        this.selected_friend = null;

        //奖品列表设置
        this.exchange_list.renderHandler = new Laya.Handler(this, this.onPrizeListRender);
        this.exchange_list.scrollBar.hide = true;
        this.exchange_list.scrollBar.elasticBackTime = 200;
        this.exchange_list.scrollBar.elasticDistance = 50;
        this.exchange_list.selectHandler = new Laya.Handler(this, this.onSelectPrize);
        this.selected_prize = null;

        this.exchange_btn.clickHandler = new Laya.Handler(this, this.onExchangeBtnClick);
        this.inreo_btn.clickHandler = new Laya.Handler(this, function(){
            Laya.loader.load('laxin/tianchuang.png', new Laya.Handler(this, function(){
                var dialog = new LaxinDialogUI();
                dialog.popup();
            }),null, Laya.Loader.IMAGE);

        });

        this.zhaoji_btn.clickHandler = new Laya.Handler(this, function(){
            Laya.loader.load('laxin/laxin_zhiying.png', new Laya.Handler(this, function(){
                var dialog = new LaxinZhaojiUI();
                dialog.popup();
            }),null, Laya.Loader.IMAGE);

        });

        this.getTicketnum();
        this.isNewer();
    }
    Laya.class(Laxin, 'Laxin', LaxinUI);
    var proto = Laxin.prototype;

    proto.isNewer = function() {
        var lv = Laya.stage.getChildByName('MyGame').UI.userInfo.game_lv;
        if(lv < 3){
            this.left_side_menu.getChildByName('item0').visible = false;
            this.btn_bg_2.visible = false;
            this.left_side_menu.y = this.left_side_menu.y - 114;
            this.left_side_menu.selectedIndex = 1;
        }else {
            this.getMyList();
        }
    };

    proto.onMenuChange = function(index) {
        console.log(index);
        this.ViewStack.selectedIndex = index;
        switch (index) {
            case 0:
                this.getMyList();
                break;
            case 1:
                this.getNewerPrizeList();
                break;
            case 2:
                this.getPrizeList();
                break;
        }
    };

    proto.getTicketnum = function() {
        var self = this;
        Utils.post('Laxin/getTicketnum', {uid:localStorage.GUID}, function(res){
            if(res.code == 0){
                self.invite_num = Number(res.data.invite_num);
                self.total_ticket = Number(res.data.total_ticket);
                self.current_num = Number(res.data.current_num);
            }
        }, onHttpErr);
    };

    proto.getNewerPrizeList = function() {
        var self = this;
        Utils.post('Laxin/newer_prize_list', {uid:localStorage.GUID}, function(res){
            if(res.code == 0){
                var data = [];
                for(var i = 0; i < 20; i++) {
                    data.push({id:res.data.list[i+1].id,title:'达成条件'+(i+1)+':',content:'游戏等级达到'+(i+1)+'级',icon1:ItemIcon.MoneyIcon,num1:res.data.list[i+1].money,icon2:ItemIcon.ShandianIcon,num2:res.data.list[i+1].shandian,icon3:'laxin/laxin_jiangquan.png',num3:res.data.list[i+1].ticket_num,is_finish:res.data.list[i+1].is_finish,lv:i+1,is_receive:res.data.list[i+1].is_receive});
                }
                self.newer_list.array = data;
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        }, onHttpErr);
    };

    proto.onNewerListRender = function(cell, index) {
        var data = cell.dataSource;
        if(data.is_finish){
            cell.getChildByName('btn_lingqu').skin = 'laxin/yilingqu_2.png';
            cell.getChildByName('btn_lingqu').visible = true;
        }else {
            cell.getChildByName('btn_lingqu').visible = false;
        }
        cell.getChildByName('btn_lingqu').clickHandler = new Laya.Handler(this, this.onNewerBtnClick,[index]);
        if(data.is_receive == 1){
            cell.getChildByName('btn_lingqu').skin = 'laxin/yilingqu.png';
            cell.getChildByName('btn_lingqu').clickHandler = null;
        }

        var percent = this.userInfo.game_lv/data.lv;
        if(percent >= 1) percent = 1;
        var jindu = cell.getChildByName('jindu');
        jindu.width = 220 * percent;
    };

    proto.onNewerListScrollBarChange = function(val) {
        if(val/this.newer_list.scrollBar.max <= 0){
            this.newer_up.visible = false;
            this.newer_down.visible = true;
        }else if(val/this.newer_list.scrollBar.max < 1){
            this.newer_up.visible = true;
            this.newer_down.visible = true;
        }else if(val/this.newer_list.scrollBar.max >= 1){
            this.newer_up.visible = true;
            this.newer_down.visible = false;
        }
    };

    proto.onMyListScrollBarChange = function(val) {
        if(val/this.zhaoji_list.scrollBar.max <= 0){
            this.my_list_up.visible = false;
            this.my_list_down.visible = true;
        }else if(val/this.zhaoji_list.scrollBar.max < 1){
            this.my_list_up.visible = true;
            this.my_list_down.visible = true;
        }else if(val/this.zhaoji_list.scrollBar.max >= 1){
            this.my_list_up.visible = true;
            this.my_list_down.visible = false;
        }
    };

    proto.onNewerBtnClick = function(index) {
        console.log(index);
        var data = this.newer_list.getItem(index);
        var self = this;
        Utils.post('Laxin/newer_get_prize', {uid:localStorage.GUID,id:data.id}, function(res){
            if(res.code == 0){
                data.is_receive = 1;
                self.newer_list.changeItem(index,data);
                getMoney(Number(res.data.money));
                getShandian(Number(res.data.shandian));
                self.getTicketnum();
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.getMyList = function(){
        var self = this;
        Utils.post('Laxin/lists', {uid:localStorage.GUID}, function(res){
            if(res.code == 0){
                var friend_data = [];
                for(var i = 0; i < res.data.length; i++){
                    friend_data.push({
                        id: res.data[i].id,
                        header: res.data[i].head_img,
                        nickname: res.data[i].nickname,
                        lv: res.data[i].game_lv,
                        task: res.data[i].task
                    });
                }
                self.friend_list.array = friend_data;
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        }, onHttpErr);
    };

    proto.onSelectFriend = function(index){
        if(this.selected_friend != null){
            var selected_cell = this.friend_list.getCell(this.selected_friend);
            selected_cell.filters = null;
        }
        this.selected_friend = index;
        // 创建一个发光滤镜
        let glowFilter = new  Laya.GlowFilter("#00ff00", 5, 0, 0);
        // 设置滤镜集合为发光滤镜
        var selected_cell = this.friend_list.getCell(this.selected_friend);
        selected_cell.filters = [glowFilter];

        this.setTaskList(selected_cell.dataSource);
    };

    proto.setTaskList = function(data){
        var task_data = [];
        for(var i in data.task){
            task_data.push({
                id: data.id,
                task_id: data.task[i].task_id,
                title: '领取条件'+(i)+'：',
                content: Laxin.task_arr[i-1].content,
                is_finish: data.task[i].is_finish,
                is_receive: data.task[i].is_receive,
                num: data.task[i].ticket_num,
                user_lv: data.lv
            });
        }
        this.zhaoji_list.array = task_data;
    };

    proto.onZhaojiListRender = function(cell, index){
        var data = cell.dataSource;
        if(data.is_finish){
            cell.getChildByName('btn_lingqu').skin = 'laxin/yilingqu_2.png';
            cell.getChildByName('btn_lingqu').visible = true;
        }else {
            cell.getChildByName('btn_lingqu').visible = false;
        }
        cell.getChildByName('btn_lingqu').clickHandler = new Laya.Handler(this, this.onTaskBtnClick,[index]);
        if(data.is_receive == 1){
            cell.getChildByName('btn_lingqu').skin = 'laxin/yilingqu.png';
            cell.getChildByName('btn_lingqu').clickHandler = null;
        }

        var percent = data.user_lv/Laxin.task_arr[index].lv;
        if(percent >= 1) percent = 1;
        var jindu = cell.getChildByName('jindu');
        jindu.width = 316 * percent;
    };

    proto.onFriendListRender = function(cell, index){
        if(this.selected_friend === null && index == 0) this.onSelectFriend(index);
    };

    proto.onTaskBtnClick = function(index) {
        var task_data = this.zhaoji_list.getItem(index);
        var friend_data = this.friend_list.getItem(this.selected_friend);
        var self =this;
        Utils.post('Laxin/receive', {uid:localStorage.GUID,id:task_data.id,task_id:task_data.task_id},function(res){
            if(res.code == 0){
                task_data.is_receive = 1;
                friend_data.task[index+1].is_receive = 1;
                self.zhaoji_list.changeItem(index,task_data);
                self.friend_list.changeItem(self.selected_friend,friend_data);
                self.getTicketnum();
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.getPrizeList = function(){
        var self = this;
        Utils.post('Laxin/prize_list',{uid:localStorage.GUID},function(res){
            if(res.code == '0'){
                var PrizeData = [];
                for(i = 0; i < res.data.length; i++) {
                    PrizeData.push(res.data[i]);
                }
                self.exchange_list.array = PrizeData;
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.onPrizeListRender = function(cell, index) {
        if(this.selected_prize != index){
            cell.getChildByName('bg_selected').visible = false;
        }else {
            cell.getChildByName('bg_selected').visible = true;
        }
        let data = cell.dataSource;
        let icon = cell.getChildByName('icon');
        let num = cell.getChildByName('num');
        let need_quan = cell.getChildByName('need_quan');
        let sy_num = cell.getChildByName('sy_num');
        if(Number(data.money)){
            icon.skin = ItemIcon.MoneyIcon;
            num.text = 'x'+data.money;
        }else if(Number(data.shandian)){
            icon.skin = ItemIcon.ShandianIcon;
            num.text = 'x'+data.shandian;
        }else if(Number(data.shopid)){
            icon.skin = ItemInfo[data.shopid].thumb;
            num.text = 'x'+data.shop_total;
        }else if(Number(data.frame)) {
            icon.skin = Laxin.frame_arr[Number(data.frame)-1];
            num.text = 'x'+'1';
        }

        need_quan.text = data.ticket_num;
        sy_num.text = '剩余数量:'+ data.prize_num;
    };

    proto.onSelectPrize = function(index) {
        this.selected_prize = index;

        var selected_cell = this.exchange_list.getCell(this.selected_prize);
        selected_cell.getChildByName('bg_selected').visible = true;
    };

    proto.onExchangeBtnClick = function() {
        console.log(this.selected_prize);
        if(this.selected_prize === null){
            var dialog = new CommomConfirm('请先选择奖品');
            dialog.popup();
            return;
        }
        var data = this.exchange_list.getItem(this.selected_prize);
        var self = this;
        Utils.post('Laxin/exchange_ticket', {uid: localStorage.GUID, id: data.id}, function(res){
            if(res.code == '0') {
                if(Number(data.money)){
                    getMoney(data.money);
                }else if(Number(data.shandian)){
                    getShandian(data.shandian);
                }else if(Number(data.shopid)){
                    if(ItemInfo[data.shopid].type1 == 'prize'){
                        var dialog = new CommomConfirm('兑换成功，请到奖品界面查看');
                        dialog.popup();
                    }else {
                        getItem(data.shopid,data.shop_total);
                    }
                }else if(Number(data.frame)) {
                    var dialog = new CommomConfirm('兑换成功，请点击头像查看');
                    dialog.popup();
                }
                Laya.stage.getChildByName('MyGame').initUserinfo();
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
            self.getPrizeList();
            self.getTicketnum();
        },onHttpErr);
    };

    Laya.getset(false, proto, 'invite_num', function(){
        return this._invite_num;
    }, function(val){
        this._invite_num = Number(val);
        this.yq_num.text = val+'人';
    });

    Laya.getset(false, proto, 'total_ticket', function(){
        return this._total_ticket;
    }, function(val){
        this._total_ticket = Number(val);
        this.quan_num.text = val+'张';
    });

    Laya.getset(false, proto, 'current_num', function(){
        return this._current_num;
    }, function(val){
        this._current_num = Number(val);
        this.has_num.text = val+'张';
    });

    Laxin.task_arr = [
        {content:'邀请一名新玩家进入游戏且完成新手指引/达到3级',lv:3},
        {content:'被邀请玩家升至5级',lv:5},
        {content:'被邀请玩家升至10级',lv:10},
        {content:'被邀请玩家升至15级',lv:15},
        {content:'被邀请玩家升至18级',lv:18},
        {content:'被邀请玩家升至20级',lv:20},
    ];

    Laxin.frame_arr = [
        'ui/touxiangzhaoji_1.png',
        'ui/touxiangzhaoji_2.png',
        'ui/touxiangzhaoji_3.png'
    ];
})();