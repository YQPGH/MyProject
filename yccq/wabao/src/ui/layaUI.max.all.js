var CLASS$=Laya.class;
var STATICATTR$=Laya.static;
var View=laya.ui.View;
var Dialog=laya.ui.Dialog;
var CarMoveUI=(function(_super){
		function CarMoveUI(){
			
		    this.che=null;

			CarMoveUI.__super.call(this);
		}

		CLASS$(CarMoveUI,'ui.CarMoveUI',_super);
		var __proto__=CarMoveUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(CarMoveUI.uiView);
		}
		CarMoveUI.uiView={"type":"View","props":{"width":960,"height":640},"child":[{"type":"Image","props":{"y":54,"x":788,"var":"che","skin":"hunt/che.png"}}]};
		return CarMoveUI;
	})(View);
var dialogConfirmUI=(function(_super){
		function dialogConfirmUI(){
			
		    this.content=null;

			dialogConfirmUI.__super.call(this);
		}

		CLASS$(dialogConfirmUI,'ui.dialogConfirmUI',_super);
		var __proto__=dialogConfirmUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(dialogConfirmUI.uiView);
		}
		dialogConfirmUI.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"hunt/tankuang.png"}},{"type":"Label","props":{"y":62,"x":102,"wordWrap":true,"width":283,"var":"content","valign":"middle","leading":10,"height":122,"fontSize":30,"font":"SimSun","color":"#501f17","bold":true,"align":"left"}},{"type":"Button","props":{"y":206,"x":250,"stateNum":"3","skin":"button1/queding.png","scaleY":0.8,"scaleX":0.8,"name":"yes"}},{"type":"Button","props":{"y":206,"x":58,"stateNum":"3","skin":"button1/quxiao.png","scaleY":0.8,"scaleX":0.8,"name":"no"}}]};
		return dialogConfirmUI;
	})(Dialog);
var dialogConfirm1UI=(function(_super){
		function dialogConfirm1UI(){
			
		    this.content=null;

			dialogConfirm1UI.__super.call(this);
		}

		CLASS$(dialogConfirm1UI,'ui.dialogConfirm1UI',_super);
		var __proto__=dialogConfirm1UI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(dialogConfirm1UI.uiView);
		}
		dialogConfirm1UI.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":10,"x":123,"skin":"hunt/tankuang.png"}},{"type":"Label","props":{"y":61,"x":248,"wordWrap":true,"width":216,"var":"content","valign":"middle","leading":10,"height":134,"fontSize":30,"font":"SimSun","color":"#501f17","bold":true,"align":"left"}},{"type":"Button","props":{"y":216,"x":271,"stateNum":"3","skin":"button1/queding.png","scaleY":0.8,"scaleX":0.8,"name":"close"}}]};
		return dialogConfirm1UI;
	})(Dialog);
var GameInfoUI=(function(_super){
		function GameInfoUI(){
			
		    this.levelLabel=null;
		    this.scoreLabel=null;
		    this.timeLabel=null;
		    this.musicBtn=null;
		    this.pauseBtn=null;

			GameInfoUI.__super.call(this);
		}

		CLASS$(GameInfoUI,'ui.GameInfoUI',_super);
		var __proto__=GameInfoUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(GameInfoUI.uiView);
		}
		GameInfoUI.uiView={"type":"View","props":{"width":960,"mouseThrough":true,"height":640},"child":[{"type":"Image","props":{"y":-3,"x":3,"skin":"hunt/diwen_wabao_1.png"},"child":[{"type":"Label","props":{"y":20,"x":135,"width":79,"var":"levelLabel","valign":"middle","height":31,"fontSize":26,"color":"#fcca92","align":"center"}},{"type":"Image","props":{"y":20,"x":26,"skin":"hunt/mubiao.png"}}]},{"type":"Image","props":{"y":12,"x":354,"width":182,"skin":"hunt/dangqian.png","height":43},"child":[{"type":"Image","props":{"y":-2,"x":172,"skin":"hunt/shuzidiwen.png"},"child":[{"type":"Label","props":{"y":0,"x":0,"width":106,"var":"scoreLabel","valign":"middle","height":50,"fontSize":40,"color":"#fffc00","align":"center"}}]}]},{"type":"Image","props":{"y":-3,"x":710,"skin":"hunt/diwen_wabao_1.png"},"child":[{"type":"Label","props":{"y":21,"x":146,"width":57,"var":"timeLabel","valign":"middle","height":27,"fontSize":26,"color":"#fcca92","align":"center"}},{"type":"Image","props":{"y":15,"x":83,"skin":"hunt/shizhong.png"}},{"type":"Button","props":{"y":8,"x":4,"var":"musicBtn","toggle":true,"stateNum":"2","skin":"button/yinyue.png"}}]},{"type":"Button","props":{"y":64,"x":859,"visible":false,"var":"pauseBtn","stateNum":"2","skin":"button/zhanting.png"}}]};
		return GameInfoUI;
	})(View);
var gameOverUI=(function(_super){
		function gameOverUI(){
			
		    this.returnBtn=null;
		    this.quitBtn=null;

			gameOverUI.__super.call(this);
		}

		CLASS$(gameOverUI,'ui.gameOverUI',_super);
		var __proto__=gameOverUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(gameOverUI.uiView);
		}
		gameOverUI.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":72,"x":480,"skin":"hunt/over.png","anchorX":0.5}},{"type":"Button","props":{"y":407,"x":247,"width":200,"var":"returnBtn","skin":"button/tuichuyouxi-05.png","height":90}},{"type":"Button","props":{"y":407,"x":495,"width":200,"var":"quitBtn","skin":"button/tuichuyouxi (2).png","height":90}}]};
		return gameOverUI;
	})(Dialog);
var GamePauseUI=(function(_super){
		function GamePauseUI(){
			
		    this.resumeBtn=null;

			GamePauseUI.__super.call(this);
		}

		CLASS$(GamePauseUI,'ui.GamePauseUI',_super);
		var __proto__=GamePauseUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(GamePauseUI.uiView);
		}
		GamePauseUI.uiView={"type":"Dialog","props":{},"child":[{"type":"Button","props":{"y":148,"x":44,"width":200,"var":"resumeBtn","stateNum":"3","skin":"button/tuichuyouxi-05.png","height":80}},{"type":"Image","props":{"y":66,"x":47,"skin":"hunt/zhi_2.png"}}]};
		return GamePauseUI;
	})(Dialog);
var gamePrizeUI=(function(_super){
		function gamePrizeUI(){
			
		    this.prizeBox=null;
		    this.box_bg_2=null;
		    this.goods_icon_1=null;
		    this.box_bg_3=null;
		    this.goods_icon_2=null;
		    this.box_bg_1=null;
		    this.lebi=null;
		    this.show_text_1=null;
		    this.show_text_2=null;
		    this.recieveBtn=null;

			gamePrizeUI.__super.call(this);
		}

		CLASS$(gamePrizeUI,'ui.gamePrizeUI',_super);
		var __proto__=gamePrizeUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(gamePrizeUI.uiView);
		}
		gamePrizeUI.uiView={"type":"Dialog","props":{"width":960,"height":640},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"sign/tongguan.png"}},{"type":"Box","props":{"y":202,"x":261,"width":442,"var":"prizeBox","height":155},"child":[{"type":"Image","props":{"y":14,"x":156,"width":131,"visible":false,"var":"box_bg_2","skin":"hunt/物品框.png","height":131},"child":[{"type":"Image","props":{"y":66,"x":64,"width":66,"var":"goods_icon_1","pivotY":32.14285714285717,"pivotX":31.000000000000057,"height":66}}]},{"type":"Image","props":{"y":14,"x":299,"width":131,"visible":false,"var":"box_bg_3","skin":"hunt/物品框.png","height":131},"child":[{"type":"Image","props":{"y":69,"x":67,"width":66,"var":"goods_icon_2","pivotY":33.571428571428555,"pivotX":32.42857142857133,"height":66}}]},{"type":"Image","props":{"y":13,"x":11,"width":131,"var":"box_bg_1","skin":"hunt/物品框.png","height":131},"child":[{"type":"Image","props":{"y":17,"x":14,"var":"lebi","skin":"hunt/lebi.png"}}]}]},{"type":"Label","props":{"y":388,"x":285,"width":380,"var":"show_text_1","name":"show_text","height":46,"fontSize":26,"color":"#712f1f","bold":true,"align":"center"}},{"type":"Label","props":{"y":442,"x":285,"width":380,"var":"show_text_2","name":"show_text","height":45,"fontSize":26,"color":"#712f1f","bold":true,"align":"center"}},{"type":"Button","props":{"y":538,"x":382,"var":"recieveBtn","stateNum":"2","skin":"button/lingqu.png","name":"close"}}]};
		return gamePrizeUI;
	})(Dialog);
var GameStartUI=(function(_super){
		function GameStartUI(){
			
		    this.startBtn=null;
		    this.endBtn=null;

			GameStartUI.__super.call(this);
		}

		CLASS$(GameStartUI,'ui.GameStartUI',_super);
		var __proto__=GameStartUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(GameStartUI.uiView);
		}
		GameStartUI.uiView={"type":"View","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"hunt/挖宝加载2.png"}},{"type":"Image","props":{"y":-22,"x":-12,"skin":"hunt/xunbaologo.png"}},{"type":"Button","props":{"y":283,"x":167,"var":"startBtn","stateNum":"2","skin":"button/kaishi.png"}},{"type":"Button","props":{"y":417,"x":167,"var":"endBtn","stateNum":"2","skin":"button/tuichu.png"}},{"type":"Image","props":{"y":234,"x":365,"skin":"button/sheng.png"}},{"type":"Image","props":{"y":233,"x":209,"skin":"button/sheng.png"}},{"type":"Image","props":{"y":372,"x":209,"skin":"button/sheng.png"}},{"type":"Image","props":{"y":372,"x":365,"skin":"button/sheng.png"}},{"type":"Image","props":{"y":405,"x":342,"skin":"button/tengmai_1.png"}},{"type":"Image","props":{"y":390,"x":82,"skin":"button/tengmai_2.png"}}]};
		return GameStartUI;
	})(View);
var gameVictoryUI=(function(_super){
		function gameVictoryUI(){
			

			gameVictoryUI.__super.call(this);
		}

		CLASS$(gameVictoryUI,'ui.gameVictoryUI',_super);
		var __proto__=gameVictoryUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(gameVictoryUI.uiView);
		}
		gameVictoryUI.uiView={"type":"Dialog","props":{"name":"game_victory"},"child":[{"type":"Image","props":{"y":99,"x":480,"skin":"hunt/victory.png","anchorX":0.5}}]};
		return gameVictoryUI;
	})(Dialog);
var game_descUI=(function(_super){
		function game_descUI(){
			
		    this.descLabel_1=null;
		    this.descLabel_2=null;

			game_descUI.__super.call(this);
		}

		CLASS$(game_descUI,'ui.game_descUI',_super);
		var __proto__=game_descUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(game_descUI.uiView);
		}
		game_descUI.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":145,"x":0,"width":686,"skin":"hunt/wb.png","pivotY":8.69565217391306,"pivotX":-2.173913043478251,"height":388}},{"type":"Label","props":{"y":314,"x":87,"wordWrap":true,"width":550,"var":"descLabel_1","valign":"middle","leading":8,"height":71,"fontSize":26,"font":"SimHei","color":"#140503","align":"left"}},{"type":"Button","props":{"y":164,"x":593,"stateNum":"2","skin":"button/guanbi.png","name":"close"}},{"type":"Image","props":{"y":181,"x":248,"skin":"hunt/youxishuoming.png"}},{"type":"Label","props":{"y":436,"x":87,"wordWrap":true,"width":550,"var":"descLabel_2","valign":"middle","height":38,"fontSize":26,"font":"SimHei","color":"#140503","align":"left"}},{"type":"Label","props":{"y":263,"x":60,"width":130,"valign":"middle","text":"游戏介绍","height":46,"fontSize":26,"font":"SimHei","color":"#140503","bold":true,"align":"left"}},{"type":"Label","props":{"y":389,"x":60,"width":135,"valign":"middle","text":"游戏目标","height":51,"fontSize":26,"font":"SimHei","color":"#140503","bold":true,"align":"left"}}]};
		return game_descUI;
	})(Dialog);
var HookUI=(function(_super){
		function HookUI(){
			
		    this.role=null;
		    this.hook=null;

			HookUI.__super.call(this);
		}

		CLASS$(HookUI,'ui.HookUI',_super);
		var __proto__=HookUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(HookUI.uiView);
		}
		HookUI.uiView={"type":"View","props":{"width":960,"mouseThrough":false,"height":640},"child":[{"type":"Image","props":{"y":52,"x":400,"width":177,"skin":"hunt/che.png","height":148}},{"type":"Image","props":{"y":94,"x":475,"var":"role","skin":"role/role1.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":159,"x":483,"var":"hook","skin":"hunt/hook.png","pivotY":2.118644067796623,"mouseThrough":false,"anchorX":0.5}}]};
		return HookUI;
	})(View);
var NumPassUI=(function(_super){
		function NumPassUI(){
			
		    this.arrowBtn=null;
		    this.list=null;
		    this.psBtn=null;

			NumPassUI.__super.call(this);
		}

		CLASS$(NumPassUI,'ui.NumPassUI',_super);
		var __proto__=NumPassUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(NumPassUI.uiView);
		}
		NumPassUI.uiView={"type":"View","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"hunt/xuanguan.png"}},{"type":"Button","props":{"y":2,"x":4,"var":"arrowBtn","stateNum":"2","skin":"button/fanhui.png"}},{"type":"List","props":{"y":246,"x":118,"width":741,"var":"list","spaceY":40,"spaceX":80,"repeatY":2,"repeatX":3,"height":270},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"guanqia/num.png"}},{"type":"Image","props":{"name":"bg"}}]}]},{"type":"Button","props":{"y":515,"x":662,"var":"psBtn","stateNum":"2","skin":"button/bt.png"},"child":[{"type":"Image","props":{"y":26,"x":47,"skin":"hunt/选关切图_26.png"}}]}]};
		return NumPassUI;
	})(View);
var PassLevelUI=(function(_super){
		function PassLevelUI(){
			
		    this.scoreLabel=null;
		    this.passLabel=null;
		    this.nextBtn=null;
		    this.LevelLabel=null;
		    this.QuitBtn=null;

			PassLevelUI.__super.call(this);
		}

		CLASS$(PassLevelUI,'ui.PassLevelUI',_super);
		var __proto__=PassLevelUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(PassLevelUI.uiView);
		}
		PassLevelUI.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":63,"width":560,"skin":"hunt/wb.png"}},{"type":"Label","props":{"y":182,"x":243,"width":200,"var":"scoreLabel","valign":"middle","height":41,"fontSize":30,"font":"SimHei","color":"#501f17","align":"left"}},{"type":"Label","props":{"y":225,"x":243,"width":200,"var":"passLabel","valign":"middle","height":41,"fontSize":30,"font":"SimHei","color":"#501f17","align":"left"}},{"type":"Image","props":{"y":36,"x":254,"skin":"hunt/zhi_1.png"}},{"type":"Button","props":{"y":274,"x":177,"width":170,"var":"nextBtn","skin":"button/1kaishi.png","height":70}},{"type":"Label","props":{"y":138,"x":243,"width":200,"var":"LevelLabel","valign":"middle","height":41,"fontSize":30,"font":"SimHei","color":"#501f17","align":"left"}},{"type":"Button","props":{"y":274,"x":358,"width":170,"var":"QuitBtn","skin":"button/1tuichu.png","labelAlign":"center","height":70}}]};
		return PassLevelUI;
	})(Dialog);
var ProgressBarUI=(function(_super){
		function ProgressBarUI(){
			
		    this.proBar=null;
		    this.proLabel=null;
		    this.cloud1=null;
		    this.cloud2=null;

			ProgressBarUI.__super.call(this);
		}

		CLASS$(ProgressBarUI,'ui.ProgressBarUI',_super);
		var __proto__=ProgressBarUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ProgressBarUI.uiView);
		}
		ProgressBarUI.uiView={"type":"View","props":{"width":960,"height":640},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"progressbar/jiazaibeijin.png"}},{"type":"ProgressBar","props":{"y":389,"x":254,"var":"proBar","value":0,"skin":"progressbar/bar.png","sizeGrid":"5,5,5,14"}},{"type":"Label","props":{"y":403,"x":366,"width":200,"var":"proLabel","height":22,"fontSize":18,"align":"center"}},{"type":"Image","props":{"y":-154,"x":-1,"var":"cloud1","skin":"progressbar/cloud1.png"}},{"type":"Image","props":{"y":-21,"x":592,"var":"cloud2","skin":"progressbar/cloud2.png"}},{"type":"Image","props":{"y":-18,"x":168,"skin":"progressbar/xunbaologo.png"}}]};
		return ProgressBarUI;
	})(View);