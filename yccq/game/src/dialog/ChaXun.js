/**
 * Created by 41496 on 2018/1/17.
 */
(function(){
    var self = null;
    function ChaXun()
    {
        ChaXun.__super.call(this);
        self = this;
        this.name = 'chaxun';
        this.KCData = {};//材料库存数据；
        this.PeifangData = {'1':[],'2':[],'3':[],'4':[],'5':[]};
        this.selectedItem = null;

        this.tab.selectHandler = new Laya.Handler(this,this.onTabClick);

        this.PF_List.renderHandler = new Laya.Handler(this, this.updateItem);
        this.CL_List.renderHandler = new Laya.Handler(this, this.CLupdateItem);

        if(ZhiYinManager.step1 == 3 && ZhiYinManager.step2 ==3)
        {
            this.tab.on(Laya.Event.CLICK,this,function(){
                if(this.tab.selectedIndex == 0){
                    ZhiYinMask.instance().setZhiYin(3);
                }else {
                    ZhiYinMask.instance().setZhiYin(2);
                }
            });

            this.PF_List.on(Laya.Event.CLICK,this,function(){
                ZhiYinMask.instance().setZhiYin(4);
            });

            this.CL_List.on(Laya.Event.CLICK,this,function(){
                ZhiYinMask.instance().setZhiYin(5);
            });
        }

        this.getKCData();
    }
    Laya.class(ChaXun,'ChaXun',chaxunUI);
    var proto = ChaXun.prototype;

    proto.onOpened = function()
    {
        if(ZhiYinManager.step1 == 3 && ZhiYinManager.step2 == 3){
            //ZhiYinMask.instance().setZhiYinContent(ZhiYinManager[0]);
            ZhiYinMask.instance().setZhiYin(1);
        }
    };

    proto.onClosed = function()
    {
        if(ZhiYinManager.step1 == 3) {
            ZhiYinManager.instance().setGuideStep(4,0);
            ZhiYinMask.instance().close();
        }
    };

    proto.getKCData = function()
    {
        Utils.post("store/lists",{uid:localStorage.GUID,type1:0},this.onKCDataReturn);
    };

    proto.onKCDataReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {
            //self.KCData = res.data;
            //醇化烟叶数据
            if(res.data.yanye_chun){
                for(var i = 0,len = res.data.yanye_chun.length; i < len; i++)
                {
                    self.KCData[res.data.yanye_chun[i].shopid] = res.data.yanye_chun[i];
                }
            }

            //滤嘴数据
            if(res.data.lvzui){
                for(var i = 0,len = res.data.lvzui.length; i < len; i++)
                {
                    self.KCData[res.data.lvzui[i].shopid] = res.data.lvzui[i];
                }
            }

            //配方数据
            if(res.data.peifang){
                for(var i = 0,len = res.data.peifang.length; i < len; i++)
                {
                    if(Number(res.data.peifang[i].total))
                    {
                        self.PeifangData[res.data.peifang[i].type2].push(res.data.peifang[i]);
                    }
                }
            }

            self.tab.selectedIndex = 0;
        }
    };

    //配方列表更新
    proto.updateItem = function(cell,index)
    {
        cell.on(Laya.Event.CLICK,this,this.onListItemClick,[cell]);
    };
    //材料列表更新
    proto.CLupdateItem = function(cell,index)
    {
        if(cell.dataSource.hasNum < cell.dataSource.needNum)
        {
            cell.getChildByName('hasNum').color = "#FF0000";
        }else
        {
            cell.getChildByName('hasNum').color = "#00FF00";
        }
        cell.on(Laya.Event.MOUSE_DOWN,this,onItemPress,[cell,index,cell.dataSource.shopid,true]);
    };

    proto.onListItemClick = function(cell)
    {
        if(this.selectedItem) this.selectedItem.getChildByName('gou').visible = false;
        this.selectedItem = cell;
        cell.getChildByName('gou').visible = true;
        var Peifang = ItemInfo[cell.dataSource.shopid];

        //this.explain.changeText("说明：此书能制作"+ItemInfo[Peifang.mubiao].name);

        var data = [];
        if(Peifang.zhuliao_id){
            var num = 0;
            if(this.KCData[Peifang.zhuliao_id]){
                num = this.KCData[Peifang.zhuliao_id].total;
            }
            data.push({shopid:Peifang.zhuliao_id,icon:ItemInfo[Peifang.zhuliao_id].thumb,hasNum:Number(num),needNum:Peifang.zhuliao_count,name:ItemInfo[Peifang.zhuliao_id].name});
        }

        if(Peifang.fuliao1_id){
            var num = 0;
            if(this.KCData[Peifang.fuliao1_id]){
                num = this.KCData[Peifang.fuliao1_id].total;
            }
            data.push({shopid:Peifang.fuliao1_id,icon:ItemInfo[Peifang.fuliao1_id].thumb,hasNum:Number(num),needNum:Peifang.fuliao1_count,name:ItemInfo[Peifang.fuliao1_id].name});
        }

        if(Peifang.fuliao2_id){
            var num = 0;
            if(this.KCData[Peifang.fuliao2_id]){
                num = this.KCData[Peifang.fuliao2_id].total;
            }
            data.push({shopid:Peifang.fuliao2_id,icon:ItemInfo[Peifang.fuliao2_id].thumb,hasNum:Number(num),needNum:Peifang.fuliao2_count,name:ItemInfo[Peifang.fuliao2_id].name});
        }

        if(Peifang.lvzui_id){
            var num = 0;
            if(this.KCData[Peifang.lvzui_id]){
                num = this.KCData[Peifang.lvzui_id].total;
            }
            data.push({shopid:Peifang.lvzui_id,icon:ItemInfo[Peifang.lvzui_id].thumb,hasNum:Number(num),needNum:Peifang.lvzui_count,name:ItemInfo[Peifang.lvzui_id].name});
        }

        this.CL_List.array = data;
    };

    proto.onTabClick = function(index)
    {
        if(this.selectedItem) this.selectedItem.getChildByName('gou').visible = false;
        var data = [];
        for(var i = 0; i < this.PeifangData[index+1].length; i++)
        {
            data.push({shopid:this.PeifangData[index+1][i].shopid,icon:ItemInfo[this.PeifangData[index+1][i].shopid].thumb,name:ItemInfo[this.PeifangData[index+1][i].shopid].name});
        }
        this.PF_List.array = data;

        if(!this.PF_List.length){
            var text = ['一','二','三','四','五'];
            this.PF_List.getChildByName('tips').text = '还未获得'+text[index]+'星调香书，可通过'+((index > 0)?'每日挑战':'商行')+'或调香研究所获得';
            this.PF_List.getChildByName('tips').visible = true;
        }else {
            this.PF_List.getChildByName('tips').visible = false;
        }
    }
})();