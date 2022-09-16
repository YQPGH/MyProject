/**
 * Created by 41496 on 2017/5/12.
 */
(function(){
    var self = null;
    function SignInDialog(type)
    {
        SignInDialog.__super.call(this);
        self = this;
        this.openType = type;
        this.signin_btn.on(laya.events.Event.CLICK, this, this.signIn);
        this.signItemList = [this.day01,this.day02,this.day03,this.day04,this.day05,this.day06,this.day07];
        this.sign.clickHandler = new Laya.Handler(this,this.changeTab,[0]);
        this.task.clickHandler = new Laya.Handler(this,this.changeTab,[1]);

        //this.day_task.renderHandler = new Laya.Handler(this,this.updateItem);
        this.new_day_task.renderHandler = new Laya.Handler(this,this.updateItem);
        this.scan_day_task.renderHandler = new Laya.Handler(this,this.scanUpdateItem);

        this.initData();
    }
    Laya.class(SignInDialog,"SignInDialog",SignInUI);
    var _proto = SignInDialog.prototype;

    _proto.changeTab = function(index)
    {
        this.view_stack.selectedIndex = index;
        if(index){
            this.task.selected = true;
            this.sign.selected = false;
        }else {
            this.task.selected = false;
            this.sign.selected = true;
        }
    };

    _proto.initData = function()
    { 
        this.confirm_dialog = new confirmUI();
        Utils.post('sign/lists',{uid:localStorage.GUID},this.onInitDataReturn, onHttpErr, this);
        Utils.post('task/lists',{uid:localStorage.GUID},this.onTaskDataReturn, onHttpErr, this);
    };

    _proto.onInitDataReturn = function(res,self)
    {
        console.log(res);
        if(res.code == '0'){
            if(res.data.sign_today == 1){                 
               // self.signin_btn.skin = 'sign/signed_btn.png';
                self.signin_btn.off(laya.events.Event.CLICK, self, self.signIn) ;   
                self.signin_btn.selected = true;
                self.changeTab(1);
            }else {
                self.changeTab(0);
            }
            if(self.openType == 'signin'){
                self.changeTab(0);
            }else if(self.openType == 'task'){
                self.changeTab(1);
            }

             //签到天数  
             var sign_list = res.data.sign_list;  
             //物品列表 
             var reward_list = res.data.reward;   
             for(var key in sign_list){
                 //当前天数
                 var day_index = sign_list[key];
                 //当前物品
                 var good_index = reward_list[key-1];
                 //物品
                 var good = self.signItemList[key-1].getChildByName('day01_good');
                 //物品
                 var good_num = self.signItemList[key-1].getChildByName('good_num');
                 if(good_index.money>0){
                     good.skin = 'userinfo/lebi.png';
                     good_num.changeText('x'+good_index.money);
                 }else if(good_index.xp>0){
                     good.skin = 'sign/exp.png';
                     good_num.changeText('x'+good_index.xp);
                 }else if(good_index.shandian > 0){
                     good.skin = 'userinfo/sandian.png';
                     good_num.changeText('x'+good_index.shandian);
                 }else{
                     var good_shop = ItemInfo[good_index.shop1];
                     good.skin = good_shop.thumb;
                     good_num.changeText('x'+good_index.shop1_total);
                 }
                 if(day_index == 1){

                     var signed = self.signItemList[key-1].getChildByName('signed_bg');
                     signed.visible = true;
                 }
             }
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };
    _proto.onTaskDataReturn = function(res,self)
    {
        console.log(res);
        if(res.code == 0)
        {
            var data = [];
            var scan_data = [];
            for(var i = 0; i < res.data.length; i++)
            {
                if(i < 3){
                    var item_data = {task_id:res.data[i].id,task_index:'任务'+NumberToChinese(i+1),task_title:res.data[i].name,need_num:Number(res.data[i].task_num),curr_num:Number(res.data[i].finish_num),is_finish:res.data[i].is_finish,is_recevie:res.data[i].is_recevie};
                    if(res.data[i].money > 0){
                        item_data.icon = 'userinfo/lebi.png';
                        item_data.item_name = '银元*'+res.data[i].money;
                        item_data.type = 'money';
                    }else if(res.data[i].ledou > 0){
                        item_data.icon = 'userinfo/ledou.png';
                        item_data.item_name = '乐豆*'+res.data[i].ledou;
                        item_data.type = 'ledou';
                    }else if(res.data[i].xp > 0){
                        item_data.icon = 'sgin/exp.png';
                        item_data.item_name = '经验*'+res.data[i].xp;
                        item_data.type = 'xp';
                    }else if(res.data[i].shandian > 0){
                        item_data.icon = 'userinfo/sandian.png';
                        item_data.item_name = '闪电*'+res.data[i].shandian;
                        item_data.type = 'shandian';
                    }else if(res.data[i].shop.length > 0){
                        item_data.icon = ItemInfo[res.data[i].shop[0].shopid].thumb;
                        item_data.item_name = ItemInfo[res.data[i].shop[0].shopid].name+'*'+res.data[i].shop[0].num;
                        item_data.type = 'wupin';
                        item_data.shopid = res.data[i].shopid;
                    }
                    data.push(item_data);
                }else {
                    var item_data = {task_id:res.data[i].id,task_index:'任务'+NumberToChinese(i+1),task_title:res.data[i].name,need_num:Number(res.data[i].task_num),curr_num:Number(res.data[i].finish_num),is_finish:res.data[i].is_finish,is_recevie:res.data[i].is_recevie,shop:[]};
                    if(res.data[i].money > 0){
                        item_data.shop.push({icon:'userinfo/lebi.png',item_name:'银元*'+res.data[i].money});
                    }else if(res.data[i].ledou > 0){
                        item_data.shop.push({icon:'userinfo/ledou.png',item_name:'乐豆*'+res.data[i].ledou});
                    }else if(res.data[i].xp > 0){
                        item_data.shop.push({icon:'userinfo/exp.png',item_name:'经验*'+res.data[i].xp});
                    }else if(res.data[i].shandian > 0){
                        item_data.shop.push({icon:'userinfo/sandian.png',item_name:'经验*'+res.data[i].shandian});
                    }
                    for(var j = 0; j < res.data[i].shop.length; j++){
                        item_data.shop.push({icon:ItemInfo[res.data[i].shop[j].shopid].thumb,item_name:ItemInfo[res.data[i].shop[j].shopid].name+'*'+res.data[i].shop[j].num});
                    }
                    scan_data.push(item_data);
                }

            }
            self.new_day_task.array = data;
            self.scan_day_task.array = scan_data;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };
    _proto.signIn = function(){
        Utils.post('sign/sign',{uid:localStorage.GUID}, this.onSingInReturn, onHttpErr, this);
       
        console.log('singin');
    };

    _proto.onSingInReturn = function(res,self){
        if(res.code == 0){
            self.signin_btn.selected = true;
            self.signin_btn.off(laya.events.Event.CLICK, self, self.signIn) ;   
            Utils.post('sign/lists',{uid:localStorage.GUID}, self.onInitDataReturn, onHttpErr, self);
            var result = new SignInResultUI();
            var good_index = res.data;
            var good_icon = '';
            var good_str = '';
            if(good_index.money>0){
                good_icon = 'userinfo/lebi.png';
                good_str = '恭喜获得' + good_index.money + '银元';
            }else if(good_index.xp>0){
                good_icon = 'sign/exp.png';
                good_str = '恭喜获得' + good_index.xp + '经验';
            }else{
                var  good_shop = ItemInfo[good_index.shopid];
                good_icon = good_shop.thumb;
                good_str = '' + good_shop.name + '';
                result.closeHandler = new Laya.Handler(self,function(){getItem(good_index.shopid);});

            }
            result.goods_icon.skin = good_icon;
            result.show_text.text = good_str;
            result.popup();
            Laya.stage.getChildByName("MyGame").initUserinfo();
        }
    };

    _proto.updateItem = function(cell, index)
    {
        var data = cell.dataSource;
        var lingqu = cell.getChildByName('lingqu');
        var curr_num = cell.getChildByName('curr_num');
        var tips = cell.getChildByName('tips');
        if(data.is_recevie == 1){
            lingqu.selected = true;
            lingqu.disabled = true;
            tips.visible = false;
        }else if(data.is_finish == 1){
            lingqu.visible = true;
            tips.visible = false;
        }else{
            lingqu.visible = false;
            tips.visible = true;
        }

        if(data.curr_num >= data.need_num)
        {
            curr_num.color = '#00ff00';
        }
        lingqu.clickHandler = new Laya.Handler(this,this.clickRecevieBtn,[cell,index]);
    };

    //扫码任务列表渲染
    _proto.scanUpdateItem = function(cell,index) {
        this.updateItem(cell,index);
        var data = cell.dataSource;
        var list = cell.getChildByName('item_list');
        if(data.shop.length == 2){
            list.spaceX = 94;
        }
        list.array = data.shop;
    };

    _proto.clickRecevieBtn = function(cell,index){
        var btn_target   = cell.getChildByName('lingqu');
        btn_target.disabled = true;
        Utils.post('task/get_task_prize',{uid:localStorage.GUID,id:cell.dataSource.task_id}, this.onTaskReturn, onHttpErr, cell);

    };
    _proto.onTaskReturn = function(res,cell){
        var type = cell.dataSource.type;
        var num = cell.dataSource.num;
        if(res.code == 0){
            if(res.data.money > 0){
                getMoney(res.data.money);
            }
            if(res.data.ledou > 0){
                getBean(res.data.ledou);
            }
            if(res.data.shop && res.data.shop.length > 0){
                getItem(res.data.shop);
            }
            if(res.data.shandian > 0){
                getShandian(res.data.shandian);
            }
            console.log(res.data.suipian.length);
            if(res.data.suipian.length){
                var suipian_tips = new FragmentGetTips(res.data.suipian);
                suipian_tips.popup();
            }
            self.initData();
            //更新用户信息
            Laya.stage.getChildByName("MyGame").initUserinfo();
        }else {
            var btn_target   = cell.getChildByName('lingqu');
            btn_target.disabled = false;
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };


})();