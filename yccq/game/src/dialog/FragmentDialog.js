(function(){
    function FragmentDialog() {
        FragmentDialog.__super.call(this);
        this.selected = null;
        this.tab_top.selectHandler = new Laya.Handler(this, this.obTabSelected);
        this.side_list.renderHandler = new Laya.Handler(this,this.onSideListRender);
        //this.setSideList([{id:0},{id:1}]);
        this.setMySuiPian({green:99,blue:99,red:999});
        //this.setLiBao(FragmentDialog.libao_arr[0]);
        this.tab_top.selectedIndex = 0;
        this.duihuan_btn.clickHandler = new Laya.Handler(this, this.onDuiHuanBtnClick);
    }
    Laya.class(FragmentDialog,'FragmentDialog',FragmentUI);
    var proto = FragmentDialog.prototype;

    proto.obTabSelected = function(index) {
        this.setSideList(FragmentDialog.tabSelectData[index]);
    };

    proto.onSideListRender = function(cell,index) {
        cell.on(Laya.Event.CLICK,this, this.onSideItemClick,[cell]);
        if(index == 0){
            cell.event('click',[cell]);
        }
    };

    proto.onSideItemClick = function(cell) {
        if(this.selected) this.selected.getChildByName('selected_bg').visible = false;
        this.selected = cell;
        cell.getChildByName('selected_bg').visible = true;
        this.setLiBao(FragmentDialog.libao_arr[cell.dataSource.id]);
    };

    proto.setSideList = function(data) {
        var list_data = [];
        for(var i = 0; i < data.length; i++) {
            list_data.push({id:data[i].id,icon:FragmentDialog.icon_arr[data[i].id]});
        }
        this.side_list.array = list_data;
        //this.onSideItemClick(this.side_list.getCell(0));
    };

    proto.onDuiHuanBtnClick = function() {
        if(!this.selected) return;
        var dialog = new FragmentConfirm(this.selected.dataSource.id);
        dialog.closeHandler = new Laya.Handler(this,function(name){
            if(name == Laya.Dialog.YES){
                console.log(dialog.libao_num);
                this.duiHuan(this.selected.dataSource.id,dialog.libao_num);
            }
        });
        dialog.popup();
    };

    proto.duiHuan = function(id,num) {
        var my_green = Number(this.sp_green_num.text);
        var my_blue = Number(this.sp_blue_num.text);
        var my_red = Number(this.sp_red_num.text);

        var need_green = FragmentDialog.libao_arr[id].need.green * num;
        var need_blue = FragmentDialog.libao_arr[id].need.blue * num;
        var need_red = FragmentDialog.libao_arr[id].need.red * num;

        if(my_green >= need_green && my_blue >= need_blue && my_red >= need_red){
            this.sp_green_num.text = (my_green - need_green)+'';
            this.sp_blue_num.text = (my_blue - need_blue)+'';
            this.sp_red_num.text = (my_red - need_red)+'';

            var award = FragmentDialog.libao_arr[id].award;
            for(var i = 0; i < award.length; i++){
                if(award[i].shopid) getItem(award[i].shopid,award[i].num * num);
                if(award[i].money) getMoney(award[i].money * num);
                if(award[i].shandian) getShandian(award[i].shandian * num);
            }
        }else {
            var dialog = new CommomConfirm('碎片数量不足');
            dialog.popup();
        }

    };

    proto.setMySuiPian = function(data) {
        this.sp_green_num.text = data.green;
        this.sp_blue_num.text = data.blue;
        this.sp_red_num.text = data.red;
    };

    proto.setNeedNum = function(data) {
        this.need_gren_num.text = data.green;
        this.need_blue_num.text = data.blue;
        this.need_red_num.text = data.red;
    };

    proto.setLiBaoName = function(name) {
        this.title.text = name;
    };

    proto.setLiBao = function(data) {
        this.libao_icon.skin = FragmentDialog.icon_arr[data.id];
        this.setNeedNum(data.need);
        this.setLiBaoName(data.name);
        this.setAward(data.award);
    };

    proto.setAward = function(data) {
        var list_data = [];
        for(var i = 0; i < data.length; i++) {
            if(Number(data[i].shopid)) list_data.push({icon:ItemInfo[data[i].shopid].thumb,num:data[i].num});
            if(Number(data[i].money)) list_data.push({icon:ItemIcon.MoneyIcon,money:data[i].money});
            if(Number(data[i].shandian)) list_data.push({icon:ItemIcon.ShandianIcon,shandian:data[i].shandian});
        }
        this.award_list.array = list_data;
    };
    FragmentDialog.tabSelectData = [[{id:0},{id:1}],[{id:2}]];
    FragmentDialog.libao_arr = [
        {id:0,name:"礼包1",need:{green:10,blue:2,red:12},award:[{shopid:215,num:10},{money:1000}]},
        {id:1,name:"礼包2",need:{green:2,blue:10,red:8},award:[{shopid:625,num:5},{shandian:200}]},
        {id:2,name:"代金券礼包1",need:{green:10,blue:20,red:12},award:[{shopid:1205,num:1}]}
    ];
    FragmentDialog.icon_arr = ['fragment/shuipianlibao_dalibao_1.png','fragment/shuipianlibao_dalibao_2.png','fragment/shuipianlibao_daijinquan_1.png'];

})();