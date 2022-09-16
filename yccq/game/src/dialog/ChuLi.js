/**
 * Created by 41496 on 2017/11/28.
 */
(function(){
    var self = null;
    function ChuLi(data)
    {
        ChuLi.__super.call(this);
        self = this;
        this.ShiJianData = data;
        this.landArr = Laya.stage.getChildByName('MyGame').landArr;
        this.init();
        this.chuli_btn.clickHandler = new Laya.Handler(this,this.onChuLiBtnClick);
    }
    Laya.class(ChuLi,'ChuLi',ChuLiDialogUI);
    var proto = ChuLi.prototype;

    proto.init = function()
    {
        if(!this.ShiJianData) return;
        if(this.ShiJianData.type == 1){
            this.setChongZi();
        }else if(this.ShiJianData.type == 2){
            this.setGanHan();
        }
    };

    proto.setChongZi = function () {
        this.tool_normal.skin = 'shijiang/shachongji_1.png';
        this.tool_working.skin = 'shijiang/shacongji_2.png';
        this.tips.skin = 'shijiang/zhi_1_1.png';
        this.plant.visible = true;
        this.chongzi.visible = true;
        this.chongzi1.visible = true;
    };

    proto.setGanHan = function()
    {
        this.land.skin = 'shijiang/tudi_gan.png';
        this.tool_normal.skin = 'shijiang/huasha_1.png';
        this.tool_working.skin = 'shijiang/huasha_2.png';
        this.tips.skin = 'shijiang/zhi_2_1.png';
        this.plant.visible = false;
        this.chongzi.visible = false;
        this.chongzi1.visible = false;
    };
    proto.onChuLiBtnClick = function()
    {
        Utils.post('event/change',{uid:localStorage.GUID,id:this.ShiJianData.id},this.onChuLiReturn,onHttpErr);
    };

    proto.onChuLiReturn = function(res)
    {
        console.log(self.ShiJianData.land_id);
        if(res.code == 0){
            self.tool_normal.visible = false;
            self.tool_working.visible = true;
            self.man.skin = 'shijiang/yannong_kaixin.png';
            if(self.ShiJianData.type == 1){
                self.chongzi.visible = false;
                self.chongzi1.visible = false;
                self.tips.skin = 'shijiang/zhi_1_2.png';

                var land = self.getLandById(self.ShiJianData.land_id);
                console.log(land);
                if(land){
                    land.clearChongZi();
                }

            }else if(self.ShiJianData.type == 2){
                self.land.skin = 'shijiang/tudi_zhengchang.png';
                self.tips.skin = 'shijiang/zhi_2_2.png';
                console.log(self.ShiJianData.land_id);
                var land = self.getLandById(self.ShiJianData.land_id);
                if(land){
                    land.clearHanZai();
                }

            }

            self.chuli_btn.disabled = true;

            var dailog = new CommomConfirm(shouyi_text[RandomNum(0,2)]);
            dailog.popup();
            getMoney(100);
            self.stage.getChildByName("MyGame").initUserinfo();
        }else {
            var dailog = new CommomConfirm(res.msg);
            dailog.popup();
        }

    };

    proto.getLandById = function(id)
    {
        console.log(this.landArr);
        for(var i = 0; i < this.landArr.length; i++)
        {
            if(this.landArr[i].land1.landIndex == id){
                return this.landArr[i];
                break;
            }
        }
    };

    var shouyi_text = ['处理得不错，能及时发现问题，真是个优秀的烟厂主人呢~奖励你100银元。','天道酬勤，有你这么负责的烟厂主人，烟叶一定能健康成长呢~这100银元就当作奖励吧~','多亏你及时发现并处理了灾害呢，否则后果不堪设想~为了感谢你辛勤的付出，奖励100银元~'];

})();