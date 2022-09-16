(function(){
    var self = null;
    function Ranking(){
        Ranking.__super.call(this);
        self = this;
        this.ruleBtn.clickHandler = new Laya.Handler(this, this.onRule);
        this.showType = 0;
        this.list0.scrollBar.hide = true;//隐藏列表的滚动条。
        this.list0.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.list0.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
        this.list1.scrollBar.hide = true;//隐藏列表的滚动条。
        this.list1.scrollBar.elasticBackTime = 200;//设置橡皮筋回弹时间。单位为毫秒。
        this.list1.scrollBar.elasticDistance = 50;//设置橡皮筋极限距离。
        this.list1.renderHandler = new Laya.Handler(this,this.onList1Render);
        this.init();
    }
    Laya.class(Ranking,'Ranking',RankingUI);
    var proto = Ranking.prototype;

    proto.init = function(){

        this.tab.selectHandler = new Laya.Handler(this, this.selectTab);
        this.showtab();

    };

    proto.showtab = function(){
        Utils.post('ranking/show_type_ranking',{uid:localStorage.GUID},function(res){
            if(res.code == 0){
                //self.showType = Number(res.data.show_type);
                if(res.data.show_type == '1'){
                    self.tab_zhongzhi.visible = true;
                    self.tab_zhiyan.visible = false;
                    self.tab.selectedIndex = 2;
                    //self.selectTab(2);
                }else {
                    self.tab_zhongzhi.visible = false;
                    self.tab_zhiyan.visible = true;
                    self.tab.selectedIndex = 3;
                    //self.selectTab(3);
                }
                self.msg.text = res.data.vali_time;
            }
        });
    };

    proto.selectTab = function(index){
        console.log("tab0: " + index);
        if(index == 0){
            //乐豆周榜
            this.getLDRanking();
        }else if(index == 1){
            this.getMoneyRanking();
        }else if(index == 2){
            this.getZhongZhiRanking();
        }else if(index == 3){
            this.getZhiYanRanking();
        }
    };

    proto.getZhongZhiRanking = function(){
        Utils.post('ranking/zZJFRanking',{uid:localStorage.GUID},this.onZhongZhiRankingReturn,onHttpErr);
    };

    proto.onZhongZhiRankingReturn = function(res){
        if(res.code == 0){
            var arr = self.initList1Data(res.data.list);
            self.list0.array = arr;

            self.setMyRanking(res.data);

            var arr1 = self.initPrizeListData(res.data.prize_list,res.data.my_pre_ranking,res.data.is_receive,2);
            self.list1.array = arr1;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.getZhiYanRanking = function(){
        Utils.post('ranking/zYJFRanking',{uid:localStorage.GUID},this.onZhiYanRankingRturn,onHttpErr);
    };

    proto.onZhiYanRankingRturn = function(res){
        if(res.code == 0){
            var arr = self.initList1Data(res.data.list);
            self.list0.array = arr;

            self.setMyRanking(res.data);

            var arr1 = self.initPrizeListData(res.data.prize_list,res.data.my_pre_ranking,res.data.is_receive,3);
            self.list1.array = arr1;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.initList1Data = function(data){
        var arr = [];
        for (var i = 0; i < data.length; i++) {
            if (i==0) {
                arr.push({
                    img:'ranking/1.png',
                    username:data[i].nickname,
                    num:data[i].total
                });
            }
            if (i==1) {
                arr.push({
                    img:'ranking/2.png',
                    username:data[i].nickname,
                    num:data[i].total
                });
            }
            if (i==2) {
                arr.push({
                    img:'ranking/3.png',
                    username:data[i].nickname,
                    num:data[i].total
                });
            }
            if (i>2) {
                arr.push({
                    ranking:i+1,
                    username:data[i].nickname,
                    num:data[i].total
                });
            }
        }
        return arr;
    };

    proto.setMyRanking = function(data){
        this.current_ranking.text ='我的当前排行：'+((data.my_ranking=='无')?data.my_ranking:'第'+data.my_ranking+'名');
        this.last_ranking.text ='我的上周排行：'+((data.my_pre_ranking=='无')?data.my_pre_ranking:'第'+data.my_pre_ranking+'名');
    };

    proto.initPrizeListData = function(data,my_pre_ranking,is_receive,type){
        var arr1 = [];
        for(var j = 0; j< data.length; j++){
            var num = '第';
            if(data[j].rank_start == data[j].rank_end){
                num += data[j].rank_start+'名';
            }else if(Number(data[j].rank_end) == 1000000){
                num += '100名以后';
            }else {
                num += data[j].rank_start+'-'+data[j].rank_end+'名';
            }
            arr1.push({
                icon1:ItemInfo[data[j].shop1_id].thumb,
                shop1_total:data[j].shop1_total,
                icon2:ItemInfo[data[j].shop2_id].thumb,
                shop2_total:data[j].shop2_total,
                icon3:'userinfo/sandian.png',
                shop3_total:data[j].shandian,
                icon4:'userinfo/lebi.png',
                shop4_total:data[j].money,
                num:num,
                my_pre_ranking:Number(my_pre_ranking),
                rank_start:Number(data[j].rank_start),
                rank_end:Number(data[j].rank_end),
                type:type,
                is_receive:is_receive,
                shop1_id:data[j].shop1_id,
                shop2_id:data[j].shop2_id,
                shop3_id:data[j].shop3_id,
                icon5:data[j].shop3_id?ItemInfo[data[j].shop3_id].thumb:null,
                shop5_total:data[j].shop3_id?data[j].shop3_total:''
            });
        }
        return arr1;
    };

    proto.getMoneyRanking = function(){
        Utils.post('ranking/consumeMoneyRanking',{uid:localStorage.GUID},this.onMoneyRankingReturn,onHttpErr);
    };

    proto.onMoneyRankingReturn = function(res){
        if(res.code == 0){
            var arr = self.initList1Data(res.data.list);
            self.list0.array = arr;

            self.setMyRanking(res.data);

            //奖励

            var arr1 = self.initPrizeListData(res.data.prize_list,res.data.my_pre_ranking,res.data.is_receive,1);
            self.list1.array = arr1;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onList1Render = function(cell,index)
    {
        var data = cell.dataSource;
        var my_pre_ranking = data.my_pre_ranking;
        var receive_btn = cell.getChildByName('receive_btn');
        if(data.shop3_id){
            cell.getChildByName('bg5').visible = true;
            cell.getChildByName('icon5').visible = true;
            cell.getChildByName('shop5_total').visible = true;
            var shop5 = cell.getChildByName('icon5');
            shop5.on(Laya.Event.MOUSE_DOWN,this,showItemInfo,[shop5,data.shop3_id]);
            shop5.on(Laya.Event.MOUSE_UP,this,hideItemInfo,[shop5]);
            shop5.on(Laya.Event.MOUSE_MOVE,this,hideItemInfo,[shop5]);
            shop5.on(Laya.Event.MOUSE_OUT,this,hideItemInfo,[shop5]);
        }else{
            cell.getChildByName('bg5').visible = false;
            cell.getChildByName('icon5').visible = false;
            cell.getChildByName('shop5_total').visible = false;
        }
        if (data.rank_start <= my_pre_ranking && my_pre_ranking <= data.rank_end && data.is_receive == 0) {
            receive_btn.visible = true;
            /*if(data.is_receive == 1){
                receive_btn.disabled = true;
            }else {
                receive_btn.disabled = false;
            }*/
            receive_btn.clickHandler = new Laya.Handler(self, function(){
                var url = '';
                if(data.type == 0){
                    url = 'ranking/getRankingLDPrize';
                }else if(data.type == 1){
                    url = 'ranking/getRankingMoneyPrize';
                }else if(data.type == 2){
                    url = 'ranking/getRankingZZPrize';
                }else if(data.type == 3){
                    url = 'ranking/getRankingZYPrize';
                }
                Utils.post(url, {uid:localStorage.GUID}, self.onReceive,onHttpErr,data.type);
            });
        }else {
            receive_btn.visible = false;
        }
        var shop1 = cell.getChildByName('icon1');
        var shop2 = cell.getChildByName('icon2');
        var shop3 = cell.getChildByName('icon3');
        var shop4 = cell.getChildByName('icon4');


        shop1.on(Laya.Event.MOUSE_DOWN,this,showItemInfo,[shop1,data.shop1_id]);
        shop1.on(Laya.Event.MOUSE_UP,this,hideItemInfo,[shop1]);
        shop1.on(Laya.Event.MOUSE_MOVE,this,hideItemInfo,[shop1]);
        shop1.on(Laya.Event.MOUSE_OUT,this,hideItemInfo,[shop1]);
        shop2.on(Laya.Event.MOUSE_DOWN,this,showItemInfo,[shop2,data.shop2_id]);
        shop2.on(Laya.Event.MOUSE_UP,this,hideItemInfo,[shop2]);
        shop2.on(Laya.Event.MOUSE_MOVE,this,hideItemInfo,[shop2]);
        shop2.on(Laya.Event.MOUSE_OUT,this,hideItemInfo,[shop2]);
        shop3.on(Laya.Event.MOUSE_DOWN,this,showItemInfo,[shop3,'shandian']);
        shop3.on(Laya.Event.MOUSE_UP,this,hideItemInfo,[shop3]);
        shop3.on(Laya.Event.MOUSE_MOVE,this,hideItemInfo,[shop3]);
        shop3.on(Laya.Event.MOUSE_OUT,this,hideItemInfo,[shop3]);
        shop4.on(Laya.Event.MOUSE_DOWN,this,showItemInfo,[shop4,'money']);
        shop4.on(Laya.Event.MOUSE_UP,this,hideItemInfo,[shop4]);
        shop4.on(Laya.Event.MOUSE_MOVE,this,hideItemInfo,[shop4]);
        shop4.on(Laya.Event.MOUSE_OUT,this,hideItemInfo,[shop4]);

    };

    /*proto.showItemInfo = function(obj,shopid)
    {
        if(!obj.ItemInfo){
            obj.ItemInfo = new ItemInfoDialog(shopid);
            var point = new Laya.Point(obj.x,obj.y);
            obj.parent.localToGlobal(point);
            obj.ItemInfo.pos(point.x,point.y);
        }
        obj.ItemInfo.show();

    };

    proto.hideItemInfo = function(obj)
    {
        if(obj.ItemInfo) obj.ItemInfo.close();
    };*/

    proto.getLDRanking = function()
    {
        Utils.post('ranking/consumeLDRanking', {uid:localStorage.GUID},this.onLDRankingReturn,onHttpErr);
    };

    proto.onLDRankingReturn = function(res)
    {
        if(res.code == 0){
            var arr = self.initList1Data(res.data.list);
            self.list0.array = arr;

            self.setMyRanking(res.data);

            //  console.log(this.list1.getCell(0));
            var arr1 = self.initPrizeListData(res.data.prize_list,res.data.my_pre_ranking,res.data.is_receive,0);
            self.list1.array = arr1;
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
    };

    proto.onReceive = function(res,type){
        console.log('领取');
        if(res.code == 0){
            var ItemData = [];
            ItemData.push({shopid:res.data.shop1_id,num:res.data.shop1_total});
            ItemData.push({shopid:res.data.shop2_id,num:res.data.shop2_total});
            if(Number(res.data.shop3_id)){
                ItemData.push({shopid:res.data.shop3_id,num:res.data.shop3_total});
            }
            getItem(ItemData);
            getMoney(res.data.money);
            getShandian(res.data.shandian);
            self.stage.getChildByName("MyGame").initUserinfo();
            if(type == 0){
                self.getLDRanking();
            }else if(type == 1) {
                self.getMoneyRanking();
            }else if(type == 2) {
                self.getZhongZhiRanking();
            }else if(type == 3) {
                self.getZhiYanRanking();
            }
        }else {
            var dialog = new CommomConfirm(res.msg);
            dialog.popup();
        }
     
    };

    proto.onRule = function(){
        var dialog = new RankingRuleUI();  
        dialog.introduce_txt.style.fontSize = 20;
        dialog.introduce_txt.style.color = "#6f1c05";
        dialog.introduce_txt.style.leading = 5;
        dialog.panel.vScrollBarSkin = '';
        dialog.introduce_txt.innerHTML = '1.种植能手排行榜以每周<span style="color:red;">最先累计</span>的种植积分，由多到少进行排名，每<span style="color:red;">收获</span>一次种植成熟的烟叶可获取一定的种植积分，收获一/二/三/四/五星烟叶的积分分别为2/6/10/18/28；<br/>2.制烟能手排行榜以每周<span style="color:red;">最先累计</span>的制烟积分，由多至少进行排名，每<span style="color:red;">收获</span>一包制作完成的香烟可获取一定的制烟积分，收获一/二/三/四/五星香烟的积分分别为10/25/55/130/300；<br/>3.榜单的每周有效记录时间为本周周一0点至本周周日24点，奖励结算为下周周一0点至24点，结算后可领取奖励，<span style="color:red;">请在本周榜单结算之前领取上周排行榜的奖励</span>；<br/>4.排行榜前20名将获得<span style="color:red;">京东卡</span>，前<span style="color:red;">300名</span>可获得<span style="color:red;">种子、调香书、闪电、银元</span>奖励。';
        dialog.popup();
    };

    proto.hideReceiveBtn = function(){
        for(var i = 0; i < this.list1.length; i++){
            var cell = this.list1.getCell(i);
            cell.getChildByName('receive_btn').visible = false;
        }
    }
})();