/**
 * Created by 41496 on 2018/9/28.
 */
(function(){
    function ChongziDialog(){
        ChongziDialog.__super.call(this);
        this.tab_top.selectHandler = new Laya.Handler(this,this.onTabTopSelected);
        this.tab_nengliang.selectHandler = new Laya.Handler(this,this.onTabNengLiangSelected);

        this.list_nengliang.scrollBar.hide = true;//隐藏列表的滚动条。
        this.list_nengliang.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.list_nengliang.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.list_zhuanghuan.scrollBar.hide = true;//隐藏列表的滚动条。
        this.list_zhuanghuan.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.list_zhuanghuan.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.list_jilu.scrollBar.hide = true;//隐藏列表的滚动条。
        this.list_jilu.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.list_jilu.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。

        this.btn_goto_buy.clickHandler = new Laya.Handler(this,function(){
            var dialog = new ZLDialog();
            dialog.popup();
            this.close();
        });

        this.energy_next_page.clickHandler = new Laya.Handler(this,this.EnergyNextPage);
        this.energy_pre_page.clickHandler = new Laya.Handler(this,this.EnergyPrePage);

        this.change_next_page.clickHandler = new Laya.Handler(this,this.ChangeNextPage);
        this.change_pre_page.clickHandler = new Laya.Handler(this,this.ChangePrePage);

        this.ruqin_next_page.clickHandler = new Laya.Handler(this,this.RuqinNextPage);
        this.ruqin_pre_page.clickHandler = new Laya.Handler(this,this.RuqinPrePage);


        this.list_nengliang.renderHandler = new Laya.Handler(this,this.onEnergyListRender);
        this.list_zhuanghuan.renderHandler = new Laya.Handler(this,this.onChangeListRender);
        this.list_jilu.renderHandler = new Laya.Handler(this,this.onRuqinListRender);

        this.btn_lingqu.clickHandler = new Laya.Handler(this,this.onLingQuBtnCLick);

        this.help_btn.clickHandler = new Laya.Handler(this,function(){
            var dialog = new GuideBook(11);
            dialog.popup();
        });

        //this.list_paiqian.renderHandler = new Laya.Handler(this,this.onPaiQianListUpdate);
        this.chongzi = [this.chongzi_small,this.chongzi_middle,this.chongzi_big];
        var self = this;
        for(var i = 0; i < this.chongzi.length; i++){
            this.chongzi[i].countdown = function(){
                this.CurrTime --;
                this.getChildByName('countdown').text = Utils.formatSeconds(this.CurrTime);
                if(this.CurrTime <= 0){
                    this.timer.clear(this,this.countdown);
                    self.getChongziStatus();
                }
            };
            this.chongzi[i].PQcountdown = function(){//派遣倒计时
                this.PQCurrTime --;
                this.getChildByName('info').text = "在"+this.PQNickName+"好友家收集能量还有"+Utils.formatSeconds(this.PQCurrTime)+"可回归";
                if(this.PQCurrTime <= 0){
                    this.timer.clear(this,this.PQcountdown);
                    self.getChongziStatus();
                }
            };
            this.chongzi[i].setStatus = function(data){
                this.getChildByName('icon').skin = data.icon;
                if(data.id){
                    if(data.status == '0') {
                        this.getChildByName('btn_paiqian').disabled = false;
                        this.getChildByName('cando').visible = true;
                        var start_time = Utils.strToTime(data.start_time);
                        var stop_time = Utils.strToTime(data.stop_time);
                        var now_time = Utils.strToTime(data.now_time);
                        var all_time = stop_time - start_time;
                        this.CurrTime = all_time - (now_time - start_time);
                        this.getChildByName('countdown').text = Utils.formatSeconds(this.CurrTime);
                        if(this.CurrTime > 0){
                            this.timer.loop(1000,this,this.countdown);
                        }

                    }else {
                        this.getChildByName('btn_paiqian').disabled = true;
                        this.getChildByName('countdown').text = '已过期';
                        if(data.type == '3') this.getChildByName('countdown').text = '99粉丝节开放购买';
                    }
                }else {
                    this.getChildByName('btn_paiqian').disabled = true;
                    this.getChildByName('countdown').text = '未购买';
                    if(data.type == '3') this.getChildByName('countdown').text = '99粉丝节开放购买';
                }
            };
            this.chongzi[i].setPaiQian = function(data){
                if(data.status == '0'){
                    this.PQNickName = data.nickname;
                    this.getChildByName('btn_paiqian').skin = "chongzi/btn_yipaiqian.png";
                    this.getChildByName('btn_paiqian').disabled = true;
                    this.getChildByName('cando').visible = false;
                    this.getChildByName('info').visible = true;
                    var start_time = Utils.strToTime(data.start_time);
                    var stop_time = Utils.strToTime(data.stop_time);
                    var now_time = Utils.strToTime(data.now_time);
                    var all_time = stop_time - start_time;
                    this.PQCurrTime = all_time - (now_time - start_time);
                    this.getChildByName('info').text = "在"+data.nickname+"好友家收集能量还有"+Utils.formatSeconds(this.PQCurrTime)+"可回归";
                    if(this.PQCurrTime > 0){
                        this.timer.loop(1000,this,this.PQcountdown);
                    }
                }else {
                    this.getChildByName('btn_paiqian').skin = "chongzi/btn_paiqian.png";
                    this.getChildByName('btn_paiqian').disabled = false;
                    this.getChildByName('cando').visible = true;
                    this.getChildByName('info').visible = false;
                }
            };
            this.chongzi[i].getChildByName('btn_paiqian').clickHandler = new Laya.Handler(this,this.popFriendList,[i+1]);
        }

        this.closeHandler = new Laya.Handler(this,this.onChongziDialogClose);
        //翻页
        this.EnergyCurrPage = 0;
        this.EnergyTotalPage = 1;

        this.ChangeCurrPage = 0;
        this.ChangeTotalPage = 1;

        this.RuqinCurrPage = 0;
        this.RuqinTotalPage = 1;

        this.onTabTopSelected(0);
    }
    Laya.class(ChongziDialog,'ChongziDialog',chongziUI);
    var proto = ChongziDialog.prototype;

    proto.onTabTopSelected = function(index){
        this.viewstack.selectedIndex = index;
        switch(index){
            case 0:
                this.getChongziStatus();
                break;
            case 1:
                this.onTabNengLiangSelected(this.tab_nengliang.selectedIndex);
                break;
            case 2:
                this.getRuqinRecord();
                break;
        }
    };

    proto.onTabNengLiangSelected = function(index){
        this.viewstack_nengliang.selectedIndex = index;
        switch(index){
            case 0:
                this.getCurrEnergy();
                break;
            case 1:
                this.getChangeRecord();
                break;
        }
    };

    proto.getChongziStatus = function(){
        var self = this;
        Utils.post('Chongzi/chongzi_status',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                var data = [
                    {id:0,icon:"chongzi/chong_small.png",type:1},
                    {id:0,icon:"chongzi/chong_middle.png",type:2},
                    {id:0,icon:"chongzi/chong_big.png",type:3}
                ];
                for(var i = 0; i < res.data.buy.length; i++){
                    data[Number(res.data.buy[i].type)-1].id = res.data.buy[i].id;
                    data[Number(res.data.buy[i].type)-1].type = res.data.buy[i].type;
                    data[Number(res.data.buy[i].type)-1].status = res.data.buy[i].status;
                    data[Number(res.data.buy[i].type)-1].start_time = res.data.buy[i].start_time;
                    data[Number(res.data.buy[i].type)-1].stop_time = res.data.buy[i].stop_time;
                    data[Number(res.data.buy[i].type)-1].now_time = res.time;
                }
                self.chongzi_big.setStatus(data[2]);
                self.chongzi_middle.setStatus(data[1]);
                self.chongzi_small.setStatus(data[0]);

                for(var j = 0; j < res.data.chongzi_put.length; j++){
                    self.chongzi[Number(res.data.chongzi_put[j].type)-1].setPaiQian({status:res.data.chongzi_put[j].status,start_time:res.data.chongzi_put[j].start_time,stop_time:res.data.chongzi_put[j].stop_time,now_time:res.time,nickname:res.data.chongzi_put[j].nickname})
                }
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.popFriendList = function(type){
        var dialog = new ChongziFriendList(type);
        dialog.popup();
        dialog.closeHandler = new Laya.Handler(this,this.onFriendListClose);
    };

    proto.onFriendListClose = function(name){
        this.getChongziStatus();
    };

    proto.getCurrEnergy = function(){
        var self =this;
        Utils.post('Chongzi/current_energy',{uid:localStorage.GUID,page:this.EnergyCurrPage},function(res){
            if(res.code == '0'){
                console.log(res);
                var data = [];
                for(var i = 0; i < res.data.list.length; i++){
                    data.push(res.data.list[i]);
                }
                self.list_nengliang.repeatY = data.length;
                self.list_nengliang.array = data;
                self.list_nengliang.visible = true;
                self.list_nengliang.scrollBar.value = 0;
                self.EnergyCurrPage = Number(res.data.page.curr_page)-1;
                self.EnergyTotalPage = Number(res.data.page.total_page);
                self.EnergyPages.text = ""+(self.EnergyCurrPage+1)+"/"+self.EnergyTotalPage;
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
        this.getEnergyTotal();
    };

    proto.EnergyNextPage = function(){
        this.EnergyCurrPage ++;
        if(this.EnergyCurrPage >= this.EnergyTotalPage) this.EnergyCurrPage = this.EnergyTotalPage-1;
        this.getCurrEnergy();
    };

    proto.EnergyPrePage = function(){
        this.EnergyCurrPage --;
        if(this.EnergyCurrPage < 0) this.EnergyCurrPage = 0;
        this.getCurrEnergy();
    };

    proto.onEnergyListRender = function(cell,index){
        var btn_lingqu = cell.getChildByName('btn_lingqu')
        btn_lingqu.clickHandler = new Laya.Handler(this,this.onEnergyShouJiBtnClick,[cell]);
        cell.getChildByName('info').text = ""+cell.dataSource.add_time+"\n"+"在"+cell.dataSource.nickname+"好友家收集"+cell.dataSource.energy+"能量";
        switch(cell.dataSource.type){
            case '1':
                cell.getChildByName('icon').skin = "chongzi/chong_small.png";
                break;
            case '2':
                cell.getChildByName('icon').skin = "chongzi/chong_middle.png";
                break;
            case '3':
                cell.getChildByName('icon').skin = "chongzi/chong_big.png";
                break;
        }
        if(cell.dataSource.status == '1'){
            btn_lingqu.skin = 'chongzi/btn_yishouji.png';
            btn_lingqu.disabled = true;
        }else {
            btn_lingqu.skin = 'chongzi/btn_lingqu_nengliang.png';
            btn_lingqu.disabled = false;
        }
    };

    proto.onEnergyShouJiBtnClick = function(cell){
        console.log(cell.dataSource);
        var self = this;
        Utils.post('Chongzi/lingqu',{uid:localStorage.GUID,type:cell.dataSource.type,id:cell.dataSource.id},function(res){
            if(res.code == '0'){
                self.getCurrEnergy();
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.getEnergyTotal = function(){
        var self = this;
        Utils.post('Chongzi/energy_total',{uid:localStorage.GUID},function(res){
            if(res.code == '0'){
                var num = 50;
                self.energy_text.text = '当前能量'+res.data.energy+'可转换'+parseInt(Number(res.data.energy)/num)+'闪电';
                var percent = (Number(res.data.energy)/num >= 1? 1 : Number(res.data.energy)/num);
                var width = self.jindutiao.width * percent;
                var height = self.jindutiao.height;
                self.jindutiao.mask = null;
                var sp = new Laya.Sprite();
                sp.graphics.drawRect(0,0,width,height,'#FF0000');
                self.jindutiao.mask = sp;
                if(percent == 1){
                    self.btn_lingqu.disabled = false;
                }else {
                    self.btn_lingqu.disabled = true;
                }
            }
        });
    };

    proto.onLingQuBtnCLick = function(){
        var self =this;
        Utils.post('Chongzi/receive_shouyi',{uid:localStorage.GUID},function(res){
            if(res.code == '0'){
                res.data.shandian && getShandian(res.data.shandian);
                self.getEnergyTotal();
                self.stage.getChildByName("MyGame").initUserinfo();
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.getChangeRecord = function(){
        var self = this;
        Utils.post('Chongzi/change_record',{uid:localStorage.GUID,page:this.ChangeCurrPage},function(res){
            if(res.code == '0'){
                console.log(res);
                var data = [];
                for(var i = 0; i < res.data.list.length; i++){
                    data.push(res.data.list[i]);
                }
                self.list_zhuanghuan.repeatY = data.length;
                self.list_zhuanghuan.array = data;
                self.list_zhuanghuan.scrollBar.value = 0;
                self.list_zhuanghuan.visible = true;
                self.ChangeCurrPage = Number(res.data.page.curr_page)-1;
                self.ChangeTotalPage = Number(res.data.page.total_page);
                self.ChangePages.text = ""+(self.ChangeCurrPage+1)+"/"+self.ChangeTotalPage;
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.onChangeListRender = function(cell,index){
        cell.getChildByName('time').text = cell.dataSource.add_time;
        cell.getChildByName('info').text = '将'+cell.dataSource.total+'能量转换为'+cell.dataSource.shandian+'闪电';
    };

    proto.ChangeNextPage = function(){
        this.ChangeCurrPage ++;
        if(this.ChangeCurrPage >= this.ChangeTotalPage) this.ChangeCurrPage = this.ChangeTotalPage-1;
        this.getChangeRecord();
    };

    proto.ChangePrePage = function(){
        this.ChangeCurrPage --;
        if(this.ChangeCurrPage < 0) this.ChangeCurrPage = 0;
        this.getChangeRecord();
    };

    proto.getRuqinRecord = function(){
        var self = this;
        Utils.post('Chongzi/Ruqin',{uid:localStorage.GUID,page:this.RuqinCurrPage},function(res){
            if(res.code == '0'){
                console.log(res);
                var data = [];
                for(var i = 0; i < res.data.list.length; i++){
                    data.push(res.data.list[i]);
                }
                self.list_jilu.repeatY = data.length;
                self.list_jilu.array = data;
                self.list_jilu.scrollBar.value = 0;
                self.list_jilu.visible = true;
                self.RuqinCurrPage = Number(res.data.page.curr_page)-1;
                self.RuqinTotalPage = Number(res.data.page.total_page);
                self.JiLuPages.text = ""+(self.RuqinCurrPage+1)+"/"+self.RuqinTotalPage;
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.onRuqinListRender = function(cell,index){
        cell.getChildByName('header').skin = cell.dataSource.head_img;
        cell.getChildByName('lv').text = cell.dataSource.game_lv;
        cell.getChildByName('time').text = cell.dataSource.start_time;
        cell.getChildByName('info').text = '好友'+cell.dataSource.nickname+'来烟厂使坏，放置了1只虫子在种植地，快及时清除！';
        cell.getChildByName('energy').text = '已获取能量值：'+cell.dataSource.energy;
        cell.getChildByName('clear_btn').clickHandler = new Laya.Handler(this,this.onClearBtnClick,[cell]);
        if(cell.dataSource.status == '1'){
            cell.getChildByName('clear_btn').skin = 'chongzi/btn_yiqingchu.png';
            cell.getChildByName('clear_btn').disabled = true;
        }else {
            cell.getChildByName('clear_btn').skin = 'chongzi/btn_qingchu.png';
            cell.getChildByName('clear_btn').disabled = false;
        }
    };

    proto.onClearBtnClick = function(cell){
        console.log(cell.dataSource);
        var dialog = new Confirm1('清除虫子后可获得该虫子收集的一半能量');
        dialog.popup();
        dialog.closeHandler = new Laya.Handler(this,function(name){
            if(name == Laya.Dialog.YES){
                console.log('虫子被点击，清除虫子');
                var self = this;
                Utils.post('Chongzi/clear',{uid:localStorage.GUID,number:cell.dataSource.number},function(res){
                    if(res.code == '0'){
                        self.getRuqinRecord();
                        ChongziManager.instance().chongzi && ChongziManager.instance().chongzi.clearChongzi();
                    }else {
                        var dialog = new CommomConfirm(res.msg);
                        dialog.popup();
                    }
                },onHttpErr);
            }
        });

    };

    proto.RuqinNextPage = function(){
        this.RuqinCurrPage ++;
        if(this.RuqinCurrPage >= this.RuqinTotalPage) this.RuqinCurrPage = this.RuqinTotalPage-1;
        this.getRuqinRecord();
    };

    proto.RuqinPrePage = function(){
        this.RuqinCurrPage --;
        if(this.RuqinCurrPage < 0) this.RuqinCurrPage = 0;
        this.getRuqinRecord();
    };

    proto.onChongziDialogClose = function(name){
        for(var i = 0; i < this.chongzi.length; i++){
            this.chongzi[i].timer.clear(this.chongzi[i],this.chongzi[i].countdown);
            this.chongzi[i].timer.clear(this.chongzi[i],this.chongzi[i].PQcountdown);
        }
    };
})();