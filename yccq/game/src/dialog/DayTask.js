/**
 * Created by 41496 on 2017/5/12.
 */
(function(){
    var Sprite = Laya.Sprite;
    function DayTaskDialog()
    {
        DayTaskDialog.__super.call(this);      
        this.initData();
    }
    Laya.class(DayTaskDialog,"DayTaskDialog",DayTaskUI);
    var _proto = DayTaskDialog.prototype;

    _proto.initData = function()
    { 
        this.confirm_dialog = new confirmUI(); 
         
        Utils.post('task/lists',{uid:localStorage.GUID},this.onInitDataReturn, onHttpErr, this);
    };
    _proto.onInitDataError = function(){
        this.confirm_dialog = new confirmUI();   
        this.confirm_dialog.content.changeText('数据操作失败！');
        this.confirm_dialog.popup();   
    };

    _proto.onInitDataReturn = function(res,self)
    {
        console.log(res);
        if(res.code == 0){            
            var items_lenght = res.data.length;
            for(i=0; i<items_lenght; i++){
                var key = i+1;
                var items_data = res.data[i];
                var title = items_data.name;
                var title_target = self.getChildByName('title_' + key);                   
                var icon_target  = self.getChildByName('icon_' + key + '_2');
                var icon_num     = self.getChildByName('text_' + key + '_2');
                var text_target  = self.getChildByName('text_' + key + '_3');  
                var text_target2 = self.getChildByName('text_' + key + '_4');   
                var btn_target   = self.getChildByName('btn_' + key);
                    title_target.changeText(title);//任务名称
                    if(items_data.money>0){
                        icon_target.skin = 'userinfo/lebi.png';
                        icon_target.type = 'jinbi';
                        icon_target.num = items_data.money;
                        icon_num.changeText(items_data.money);
                    }else if(items_data.ledou>0){
                        icon_target.skin = 'userinfo/ledou.png';
                        icon_target.type = 'ledou';
                        icon_target.num = items_data.ledou;
                        icon_num.changeText(items_data.ledou);
                       
                    }else if(items_data.xp>0){
                        icon_target.skin = 'daytask/exp.png';
                        icon_target.type = 'jingyan';
                        icon_target.num = items_data.xp;
                        icon_num.changeText(items_data.xp);
                       
                    }else if(items_data.shandian > 0){
                        icon_target.skin = 'userinfo/sandian.png';
                        icon_target.type = 'shandian';
                        icon_target.num = items_data.shandian;
                        icon_num.changeText(items_data.shandian);
                    }else if(items_data.shopid>0){
                        var  good_shop = ItemInfo[items_data.shopid];
                             good_icon = good_shop.thumb;   
                             icon_target.skin = good_icon;
                             icon_target.type = 'wupin';
                             icon_target.num = items_data.shop_num;
                             icon_target.shopid = items_data.shopid;
                             icon_num.changeText(items_data.shop_num);    
                    }

                    if(parseInt(items_data.finish_num) >= parseInt(items_data.task_num)){
                        text_target.color = '#008D00';
                    }

                    text_target.changeText(items_data.finish_num);
                    text_target2.changeText(items_data.task_num);
                if(items_data.is_recevie == 1){
                    btn_target.selected = true;
                    btn_target.disabled = true;
                }else if(items_data.is_finish == 1){
                    btn_target.visible = true;
                }
                if(items_data.is_finish == 0){                   
                    //btn_target.disabled = true;
                    btn_target.visible = false;
                }
                btn_target.on(Laya.Event.CLICK, self, self.clickRecevieBtn,[items_data.id,key]);



            }
        }else{
            self.confirm_dialog = new confirmUI();   
            self.confirm_dialog.content.changeText(res.code);
            self.confirm_dialog.popup();  
        }
       
      
    };
    _proto.clickRecevieBtn = function(task_id,index){
        var btn_target   = this.getChildByName('btn_' + index);
            btn_target.disabled = true;
        this.click_btn_index = index;
        Utils.post('task/get_task_prize',{uid:localStorage.GUID,id:task_id}, this.onTaskReturn, null, this);       
        
    }
    _proto.onTaskReturn = function(res,self){
        var icon_target  = self.getChildByName('icon_' + self.click_btn_index + '_2');
        var type = icon_target.type;
        var num = icon_target.num;
        if(res.code == 0){
            if(type == 'jinbi'){
                getMoney(num);
            }else if(type == 'ledou'){
                getBean(num);
            }else if(type == 'wupin'){
                var shopid = icon_target.shopid;
                getItem(shopid,num);
            }else if(type == 'shandian'){
                getShandian(num);
            }
            self.initData();
            //更新用户信息
             Laya.stage.getChildByName("MyGame").initUserinfo();
        }
    }  

   
})();