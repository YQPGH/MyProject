/**
 * Created by 41496 on 2017/5/12.
 */
(function(){
    var Sprite = Laya.Sprite;
    var Event = laya.events.Event;
    function LuckDrawDialog()
    {
        LuckDrawDialog.__super.call(this);  
        this.check_index = 1;//抽奖的第一格
		this.circle_num = 1;//跑到第几圈
		this.circle_num_true = 0;//真实跑了几圈
		this.correct_index = 0; //停留在第几个格子   
		this.is_get_result = false;//是否出结果
		this.is_win = false;//是否中奖   
        this.returnError = false; 
        this.initData();
    }
    Laya.class(LuckDrawDialog,"LuckDrawDialog",LuckDrawUI);
    var _proto = LuckDrawDialog.prototype;

    _proto.initData = function()
    { 
        this.confirm_dialog = new confirmUI(); 
        this.start_btn.on(Event.CLICK, this, this.startRun); 
        Utils.post('prize/every_day_reward_list',{uid:localStorage.GUID},this.onInitDataReturn, this.onInitDataError, this);
    };
    _proto.onInitDataError = function(){
        this.returnError = true; 
        this.confirm_dialog = new confirmUI();   
        this.confirm_dialog.content.changeText('数据操作失败！');
        this.confirm_dialog.show();   
    };

    _proto.startRun = function(){  

        if(this.start_btn.name == 'again'){
            var confirm = new Confirm1('需要消耗2个乐豆，是否继续抽奖？');
                confirm.closeHandler = new Laya.Handler(this,this.onDialogClose);
                confirm.popup();
                return false;
        }

        this.start_btn.disabled = true;
        this.showCheck();
        Utils.post('prize/reward_start',{uid:localStorage.GUID}, this.onLuckDrawReturn,  this.onInitDataError, this);   
    }

    //跑马灯跑动
	_proto.showCheck = function(){
        if(this.returnError) return;
		if(this.circle_num == 4 && this.correct_index > 0){
			
		}
		if(this.circle_num >= 4 && this.correct_index > 0 && this.correct_index == (this.check_index - 1)){		
			
		    var result = new LuckDrawResultUI();
            var good_index = this.good_data[this.correct_index-1];
            var good_icon = '';
            var good_str = '';
                if(good_index.money>0){
                     good_icon = 'userinfo/lebi_big.png';
                     good_str = '恭喜获得' + this.shop_num+ '银元';
                }else if(good_index.xp>0){                               
                     good_icon = 'daytask/exp.png';  
                     good_str = '恭喜获得' + this.shop_num + '经验'; 
                }else{
                var  good_shop = ItemInfo[good_index.shopid];
                     good_icon = good_shop.thumb;    
                     good_str = '' + good_shop.name + '*' + this.shop_num;                           
                }   
                result.goods_icon.skin = good_icon;
                result.show_text.text = good_str;
                result.popup();
            
            this.start_btn.disabled = false;
            this.start_btn.skin = 'luckdraw/kaishichoujiang.png';
            this.start_btn.name = 'again';
			this.correct_index = 0;	
			this.is_get_result = false;				
			//if(this.correct_index == 1)
             this.circle_num = 1;
             //更新用户信息
             Laya.stage.getChildByName("MyGame").initUserinfo();
			return;
		}
		if(this.check_index == 9){
			var child = this.getChildByName('box_' + (9 - 1) ).getChildByName('mask_bg');
			    child.visible = true;
			this.check_index = 1;	
			//如果跑了6圈还不返回
			if(this.circle_num == 4 && this.is_get_result  == false){	
				this.circle_num = 3;
			}else{
				this.circle_num++;
			}		
			
		} 
		if(this.check_index > 1){
			var child = this.getChildByName('box_' + (this.check_index - 1) ).getChildByName('mask_bg');
			    child.visible = true;	
		}
		var child = this.getChildByName('box_' + this.check_index).getChildByName('mask_bg');	
			child.visible = false;	
		this.check_index++;
		this.circle_num_true ++;
		Laya.timer.once(100 * Math.ceil(this.circle_num / 2), this, this.showCheck);	
	}



    _proto.onInitDataReturn = function(res,self)
    {
        if(res.code == 0){
            //判断今天已经免费抽过了
            if(res.data.is_reward == '1'){
                self.start_btn.skin = 'luckdraw/kaishichoujiang.png';
                self.start_btn.name = 'again';
            }
           

            var good_data = res.data.list;
            self.good_data = good_data;
            console.log(good_data);
            for(var key in good_data){
                var good_icon = '';
                var good_index = good_data[key];
                var index = parseInt(key);  
                if(isNaN(index)) break;             
                var curr_good = self.getChildByName('box_'+(index+1)).getChildByName('goods');             
                if(good_index.money>0){
                     good_icon = 'userinfo/lebi.png';
                }else if(good_index.xp>0){                               
                     good_icon = 'daytask/exp.png';                       
                }else{
                var  good_shop = ItemInfo[good_index.shopid];
                     if(good_shop) good_icon = good_shop.thumb; 
                     
                }   
                curr_good.skin = good_icon;

            }
        }
        
       
      
    };
   
    _proto.onLuckDrawReturn = function(res,self){
        if(res.code == 0){
           self.correct_index = res.data.index;	
           self.shop_num =  res.data.shop_num;	
         
        }
    }  

    //确认框关闭
    _proto.onDialogClose = function(name)
    {

        if(name == Dialog.YES)
        {
            this.start_btn.name = '';
            this.startRun();
           
        }
    }
   
})();