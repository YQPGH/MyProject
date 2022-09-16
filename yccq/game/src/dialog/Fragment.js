(function(){
    function Fragment(){
        Fragment.__super.call(this);
        this.sp_arr = [this.suipian_1,this.suipian_2,this.suipian_3,this.suipian_4,this.suipian_5,this.suipian_6];
        this.show_item_arr = [this.show_item_1,this.show_item_2,this.show_item_3,this.show_item_4,this.show_item_5,this.show_item_6];
        this.box_arr = [this.box_base_1,this.box_base_2,this.box_base_3,this.box_base_4,this.box_base_5,this.box_base_6];
        this.sp_name = ['A','B','C','D','E','F'];
        this.show_item_pos = [[105,239],[128,139],[229,82],[359,82],[466,139],[488,239]];
        this.curr_select_index = null;
        this._key_num = 0;
        this.sp_num = [0,0,0,0,0,0];
        this.openBoxEnable = false;
        this.wrap_tab.selectHandler = new Laya.Handler(this,this.onTabChange);//this.viewStack.setIndexHandler;
        this.tab_record.selectHandler = new Laya.Handler(this,this.onRecordTabChange);

        this.record_list.scrollBar.hide = true;
        this.record_list.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.record_list.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        for(var i = 0; i < this.sp_arr.length; i++){
            this.sp_arr[i].on(Laya.Event.CLICK,this,this.onSPSelected,[i]);
        }
        this.btn_composite.clickHandler = new Laya.Handler(this,this.onBtnCompositeClick);
        this.btn_use_key.clickHandler = new Laya.Handler(this,this.onBtnUseKeyClick);
        this.btn_giving.clickHandler = new Laya.Handler(this,this.onBtnGivingClick);
        this.btn_blag.clickHandler = new Laya.Handler(this,this.onBtnAskingClick);
        for(var i = 0; i < this.box_arr.length; i++) {
            this.box_arr[i].on(Laya.Event.CLICK,this,this.onBoxClick,[i]);
        }
        this.initSuiPian();
        this.onSPSelected(0);

        this.getSPNum();
        this.key_num = 0;

        this.getKeyNum();
        //this.initBox();
        this.reateTimeLine();
        this.getTodayNum();
        this.setShuoming();
    }
    Laya.class(Fragment,'Fragment',FragmentUI);
    var proto = Fragment.prototype;

    proto.onTabChange = function(index){
        switch (index) {
            case 0:
                this.getSPNum();
                break;
            case 1:
                this.initBox();
                this.getPrizeList();
                break;
            case 2:
                if(this.tab_record.selectedIndex == 0){
                    this.getRecord();
                }else {
                    this.shareRecord();
                }
                break;
        }
        this.viewStack.selectedIndex = index;
    };

    proto.onRecordTabChange = function(index) {
        switch (index) {
            case 0:
                this.getRecord();
                break;
            case 1:
                this.shareRecord();
                break;
        }
    };

    //获取碎片数量
    proto.getSPNum = function() {
        var self = this;
        Utils.post('Fragment/fragment_num',{uid:localStorage.GUID},function(res){
            if(res.code == 0) {
                for(var i in res.data.list){
                    self.sp_num[Number(i)-1] = res.data.list[i];
                }
                self.initSuiPian();
                self.onSPSelected(self.curr_select_index);
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },null);
    };

    //获取钥匙数量
    proto.getKeyNum = function() {
        var self = this;
        Utils.post('Fragment/queryKeynum',{uid:localStorage.GUID},function(res){
            if(res.code == 0) {
                self.key_num = Number(res.data.num);
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },null);
    };

    //选择碎片
    proto.onSPSelected = function(index){
        this.unSelect(this.curr_select_index);
        this.curr_select_index = index;
        this.setSelect(index);
        this.changeCurrNum(index);
    };

    //设置碎片选择状态
    proto.setSelect = function(index){
        this.sp_arr[index].getChildByName('base').visible = false;
        if(this.sp_num[index] > 0){
            this.sp_arr[index].getChildByName('selected').visible = true;
        }else {
            this.sp_arr[index].getChildByName('disable_selected').visible = true;
        }
    };

    proto.setDisable = function(index) {
        this.sp_arr[index].getChildByName('base').gray = true;
    };

    proto.setEnable = function(index) {
        this.sp_arr[index].getChildByName('base').gray = false;
    };

    //清除碎片选择状态
    proto.unSelect = function(index){
        if(this.curr_select_index === null) return;
        this.sp_arr[index].getChildByName('base').visible = true;
        this.sp_arr[index].getChildByName('selected').visible = false;
        this.sp_arr[index].getChildByName('disable_selected').visible = false;
    };

    //更新当前选择碎片数量
    proto.changeCurrNum = function(index) {
        this.suipian_num_title.text = "碎片"+this.sp_name[index]+"数量:";
        this.suipian_num.text = ''+this.sp_num[index];
    };

    proto.initSuiPian = function() {
        var compose_enable = true;
        for(var i = 0; i < this.sp_num.length; i++){
            if(this.sp_num[i] > 0) {
                //this.sp_arr[i].gray = false;
                this.setEnable(i);
            }else {
                compose_enable = false;
                //this.sp_arr[i].gray = true;
                this.setDisable(i);
            }
        }
        this.btn_composite.disabled = compose_enable? false: true;
    };

    proto.onBtnCompositeClick = function() {
        var self = this;
        Utils.post('Fragment/composeFragment',{uid:localStorage.GUID},function(res){
            if(res.code == 0) {
                var dialog = new FragmentGetKeyTipsUI();
                dialog.popup();
                dialog.closeHandler = new Laya.Handler(self,function(name){
                    self.key_num += 1;
                    self.updateSPNum();
                    self.initSuiPian();
                });
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },null);

    };

    proto.onBtnGivingClick = function() {
        /*if(this.sp_num[this.curr_select_index] <= 0){
            var comfirm = new CommomConfirm('碎片数量不足');
            comfirm.popup();
            return;
        }*/
        Laya.loader.load(['fragment/ask_bg.png'],new Laya.Handler(this,function(){
            var dialog = new FragmentAskGiving();
            dialog.popup();
            dialog.closeHandler = new Laya.Handler(this,function(name){
                this.getSPNum();
            });
        }));

    };

    proto.onBtnAskingClick = function() {
        var self = this;
        var dialog = new FragmentConfirm('ask');
        dialog.popup();
        dialog.closeHandler = new Laya.Handler(this,function(name){
            if(name == Laya.Dialog.YES){
                Utils.post('Fragment/Ask',{uid:localStorage.GUID,type:Number(self.curr_select_index)+1},function(res){
                    if(res.code == 0){
                        var dialog = new CommomConfirm('索要请求已发送');
                        dialog.popup();
                    }else {
                        var dialog = new CommomConfirm(res.msg);
                        dialog.popup();
                    }
                },onHttpErr);
            }
            this.getSPNum();
        });
    };

    proto.updateSPNum = function(){
        for(var i = 0; i < this.sp_num.length; i++){
            if(this.sp_num[i] <= 0) continue;
            this.sp_num[i] -= 1;
        }
    };

    proto.initBox = function() {
        this.box_show.visible = true;
        this.closeBox();
        for(var i = 0; i < this.show_item_arr.length; i++) {
            this.show_item_arr[i].visible = true;
            this.show_item_arr[i].x = this.show_item_pos[i][0];
            this.show_item_arr[i].y = this.show_item_pos[i][1];
        }
        for(var i = 0; i < this.box_arr.length; i++) {
            this.box_arr[i].visible = false;
        }
    };

    proto.onBtnUseKeyClick = function() {
        //this.btn_use_key.mouseEnabled = false;
        this.openBoxEnable = true;//允许点击开箱子
        this.initBox();
        this.aniItem();
    };

    proto.aniItem = function() {
        var dis_point = {x:this.ani_show_item_point.x,y:this.ani_show_item_point.y};
        for(var i = 0; i < this.show_item_arr.length; i++) {
            Laya.Tween.to(this.show_item_arr[i],{x:dis_point.x,y:dis_point.y},500,null,new Laya.Handler(this,this.onAniItemEnd,[i]));
        }
    };

    proto.onAniItemEnd = function(index) {
        this.show_item_arr[index].visible = false;
        if(index == 5) {
            this.timeline.play(0,false);
        }
    };

    proto.reateTimeLine = function() {
        this.timeline = new Laya.TimeLine();
        this.timeline.addLabel('right',0).to(this.box_show,{rotation:10},100,null,0)
            .addLabel('rightReturn',0).to(this.box_show,{rotation:0},100,null,0)
            .addLabel('left',0).to(this.box_show,{rotation:-10},100,null,0)
            .addLabel('leftReturn',0).to(this.box_show,{rotation:0},100,null,0)
            .addLabel('right1',0).to(this.box_show,{rotation:10},100,null,0)
            .addLabel('rightReturn1',0).to(this.box_show,{rotation:0},100,null,0)
            .addLabel('left1',0).to(this.box_show,{rotation:-10},100,null,0)
            .addLabel('leftReturn1',0).to(this.box_show,{rotation:0},100,null,0);
        this.timeline.on(Laya.Event.COMPLETE, this, this.aniBoxStart);
    };

    proto.aniBoxStart = function(){
        this.box_show.visible = false;
        for(var i = 0; i < this.box_arr.length; i++) {
            this.box_arr[i].visible = true;
            this.box_arr[i].mouseEnabled = false;
            Laya.Tween.from(this.box_arr[i],{x:this.box_base_5.x,y:this.box_base_5.y},500,null,new Laya.Handler(this,this.onAniBoxEnd,[i]));
        }
    };

    proto.onAniBoxEnd = function(index) {
        this.box_arr[index].mouseEnabled = true;
    };

    proto.onBoxClick = function(index) {
        if(!this.openBoxEnable) return;
        this.openBoxEnable = false;
        console.log('open box '+(index+1));
        var self = this;
        Utils.post('Fragment/prize_exchange',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                self.key_num -= 1;
                self.selectBox(index);
                self.openSelectedBox(index,res.data.prize);
                var k = 0;
                for(var i = 0; i < self.box_arr.length; i++){
                    if(i != index) {
                        self.openBaseBox(i,res.data.list[k]);
                        k++;
                    }
                    self.box_arr[i].mouseEnabled = false;
                }
                self.showOpenTips(index,res.data.prize);
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);

    };

    proto.selectBox = function(index) {
        this.box_arr[index].getChildByName('box_base').visible = false;
        this.box_arr[index].getChildByName('box_open_base').visible = false;
        this.box_arr[index].getChildByName('box_selected').visible = true;
        this.box_arr[index].getChildByName('box_open_selected').visible = false;
        this.box_arr[index].getChildByName('item_icon').visible = false;
    };

    proto.openBaseBox = function(index,data) {
        this.box_arr[index].getChildByName('box_base').visible = false;
        this.box_arr[index].getChildByName('box_open_base').visible = true;
        this.box_arr[index].getChildByName('box_selected').visible = false;
        this.box_arr[index].getChildByName('box_open_selected').visible = false;
        if(data.money != 0) this.box_arr[index].getChildByName('item_icon').skin = ItemIcon.MoneyIcon;
        if(data.shandian != 0) this.box_arr[index].getChildByName('item_icon').skin = ItemIcon.ShandianIcon;
        if(data.shopid != 0) this.box_arr[index].getChildByName('item_icon').skin = ItemInfo[data.shopid].thumb;
        this.box_arr[index].getChildByName('item_icon').visible = true;
    };

    proto.openSelectedBox = function(index,data) {
        this.box_arr[index].getChildByName('box_base').visible = false;
        this.box_arr[index].getChildByName('box_open_base').visible = false;
        this.box_arr[index].getChildByName('box_selected').visible = false;
        this.box_arr[index].getChildByName('box_open_selected').visible = true;
        if(data.money != 0) this.box_arr[index].getChildByName('item_icon').skin = ItemIcon.MoneyIcon;
        if(data.shandian != 0) this.box_arr[index].getChildByName('item_icon').skin = ItemIcon.ShandianIcon;
        if(data.shopid != 0) this.box_arr[index].getChildByName('item_icon').skin = ItemInfo[data.shopid].thumb;
        this.box_arr[index].getChildByName('item_icon').visible = true;
    };

    proto.closeBox = function () {
        for(var i = 0; i < this.box_arr.length; i++) {
            this.box_arr[i].getChildByName('box_base').visible = true;
            this.box_arr[i].getChildByName('box_open_base').visible = false;
            this.box_arr[i].getChildByName('box_selected').visible = false;
            this.box_arr[i].getChildByName('box_open_selected').visible = false;
            this.box_arr[i].getChildByName('item_icon').visible = false;
        }
    };

    proto.showOpenTips = function(index,data) {
        var icon = '';
        var num = 1;
        var name = '';

        if(data.money != 0) {icon = ItemIcon.MoneyIcon; num = data.money; name = '银元';}
        if(data.shandian != 0) {icon = ItemIcon.ShandianIcon; num = data.shandian; name = '闪电';}
        if(data.shopid != 0) {icon = ItemInfo[data.shopid].thumb; num = data.shop1_total; name = ItemInfo[data.shopid].name;}
        var dialog = new FragmentBoxOpenTips({icon:icon,num:num,name:name});
        dialog.popup();
        if(this.key_num > 0) this.btn_use_key.mouseEnabled = true;
    };

    proto.getPrizeList = function() {
        var self = this;
        Utils.post('Fragment/prize_lists',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                for(var i = 0; i < res.data.list.length; i++){
                    if(res.data.list[i].money != 0) self.setShowItem(self.show_item_arr[i],'money', res.data.list[i].money);
                    if(res.data.list[i].shandian != 0) self.setShowItem(self.show_item_arr[i],'shandian', res.data.list[i].shandian);
                    if(res.data.list[i].shopid != 0) self.setShowItem(self.show_item_arr[i],'shopid', res.data.list[i].shop1_total, res.data.list[i].shopid);
                }
            }
        },null);
    };

    proto.setShowItem = function(item,type,num,shopid) {
        switch (type) {
            case 'money':
                item.getChildByName('item_icon').skin = ItemIcon.MoneyIcon;
                item.getChildByName('item_name').text = '银元*'+num;
                break;
            case 'shandian':
                item.getChildByName('item_icon').skin = ItemIcon.ShandianIcon;
                item.getChildByName('item_name').text = '闪电*'+num;
                break;
            case 'shopid':
                item.getChildByName('item_icon').skin = ItemInfo[shopid].thumb;
                item.getChildByName('item_name').text = ItemInfo[shopid].name+'*'+num;
                break;
        }
    };

    //获得记录
    proto.getRecord = function() {
        this.record_list_tile.text = '获得途径';
        var self = this;
        Utils.post('Fragment/getRecord',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                var data = [];
                for(var i = 0; i < res.data.list.length; i++) {
                    data.push({sp_type:res.data.list[i].type,sp_getway:res.data.list[i].resource,sp_addtime:res.data.list[i].add_time});
                }
                self.record_list.array = data;
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    //送出记录
    proto.shareRecord = function() {
        this.record_list_tile.text = '赠送好友';
        var self = this;
        Utils.post('Fragment/shareRecord',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                var data = [];
                for(var i = 0; i < res.data.list.length; i++) {
                    data.push({sp_type:res.data.list[i].type,sp_getway:res.data.list[i].nickname,sp_addtime:res.data.list[i].receive_time});
                }
                self.record_list.array = data;
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.getTodayNum = function() {
        var self = this;
        Utils.post('Fragment/today_num',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                self.today_num.text = ''+res.data.num;
            }
        },null);
    };

    proto.setShuoming = function(){
        console.log(this.sm_panel);
        this.sm_panel.vScrollBar.hide = true;
        this.shuoming.style.leading = 10;
        this.shuoming.style.fontSize = 16;
        this.shuoming.style.color = '#ffffff';
        var text = '1、活动时间：<span style="color:#76faff">2019年5月7日0点至2019年7月31日24点；</span><br/>' +
            '2、活动期间内，完成以下指定任务或游戏环节即可获得碎片，具体规则如下：<br/>' +
            '（1）在收获<span style="color:#76faff">种植、烘烤、醇化、制烟、</span>通关每日挑战<span style="color:#76faff">欢乐挖宝/开心消消乐、完成订单</span>时有机率获得碎片，每天最多获得2片；<br/>' +
            '（2）完成<span style="color:#76faff">每日任务</span>中的<span style="color:#76faff">累计3次扫码任务必得1片</span>碎片（参与活动的香烟规格为：珍品、致青春、凌云、刘三姐、轩云）； <br/>' +
            '（3）索要碎片，<span style="color:#76faff">每天最多可进行一次索要</span>，每次索要最多被好友<span style="color:#76faff">赠送1片碎片</span>；<br/>' +
            '（4）<span style="color:#76faff">未参与过《烟草传奇》的微信用户</span>，参与（珍品）烟盒扫码<span style="color:#76faff">必得1片碎片</span>；<br/>' +
            '（5）进入游戏后完成新手指引，可通过新手礼包再领取1片碎片；<br/>' +
            '3、拼图共有6种类型，<span style="color:#76faff">消耗每种类型的各1片碎片</span>可以<span style="color:#76faff">合成1把钥匙</span>，钥匙可以<span style="color:#76faff">打开宝箱</span>，宝箱打开后有几率获得<span style="color:#76faff">（200元京东礼品）、（100元京东礼品）、（20元京东礼品）、（五星原生态调香书*1）、（闪电*30）、（银元*1000）</span>其中之一。';
        this.shuoming.innerHTML = text;
    };

    Laya.getset(false,proto,'key_num',function(){
        return this._key_num;
    },function(value){
        this._key_num = value;
        this.key_num_ui.text = ''+this._key_num;
        this.key_num_ui_2.text = ''+this._key_num;
        if(this._key_num > 0) {
            this.btn_use_key.disabled = false;
        }else {
            this.btn_use_key.disabled = true;
        }
    });

})();