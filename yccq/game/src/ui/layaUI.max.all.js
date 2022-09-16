var CLASS$=Laya.class;
var STATICATTR$=Laya.static;
var View=laya.ui.View;
var Dialog=laya.ui.Dialog;
var AddressUI=(function(_super){
		function AddressUI(){
			
		    this.btn_other=null;
		    this.btn_dijiaquan=null;
		    this.view_stack=null;
		    this.list=null;
		    this.pre_page=null;
		    this.next_page=null;
		    this.curr_page_text=null;
		    this.total_page_text=null;
		    this.dijia_list=null;
		    this.pre_page_dijia=null;
		    this.next_page_dijia=null;
		    this.curr_page_text_dijia=null;
		    this.total_page_text_dijia=null;

			AddressUI.__super.call(this);
		}

		CLASS$(AddressUI,'ui.AddressUI',_super);
		var __proto__=AddressUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(AddressUI.uiView);
		}

		STATICATTR$(AddressUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":7,"x":9,"width":854,"skin":"prize/bg.png","sizeGrid":"67,88,58,51","height":518}},{"type":"Button","props":{"y":74,"x":18,"var":"btn_other","stateNum":"2","skin":"prize/button_other.png"}},{"type":"Button","props":{"y":306,"x":18,"var":"btn_dijiaquan","stateNum":"2","skin":"prize/button_dijiaquan.png"}},{"type":"Button","props":{"y":-22,"x":823,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"ViewStack","props":{"y":72,"x":83,"width":765,"var":"view_stack","selectedIndex":1,"height":449},"child":[{"type":"Box","props":{"name":"item0"},"child":[{"type":"List","props":{"y":7,"x":8,"width":747,"visible":false,"var":"list","repeatY":4,"repeatX":1,"height":385},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"prize/item_bg_1.png"}},{"type":"Image","props":{"y":19,"x":20,"width":82,"name":"icon","height":82}},{"type":"Label","props":{"y":13,"x":122,"width":463,"valign":"middle","name":"name","height":60,"fontSize":26,"font":"SimHei"}},{"type":"Label","props":{"y":72,"x":123,"width":462,"valign":"middle","name":"end_time","height":37,"fontSize":20,"font":"SimHei","align":"left"}}]}]},{"type":"Box","props":{"y":398,"x":243,"visible":false},"child":[{"type":"Button","props":{"var":"pre_page","stateNum":"2","skin":"bakeroom/tab.png","labelSize":18,"labelFont":"SimHei","labelColors":"#672416,#672416","label":"上一页"}},{"type":"Button","props":{"x":145,"var":"next_page","stateNum":"2","skin":"bakeroom/tab.png","labelSize":18,"labelFont":"SimHei","labelColors":"#672416,#672416","label":"下一页"}},{"type":"Label","props":{"y":10,"x":76,"width":30,"var":"curr_page_text","valign":"middle","text":"1","height":32,"fontSize":20,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":10,"x":107,"width":8,"valign":"middle","text":"/","height":32,"fontSize":20,"color":"#ffffff"}},{"type":"Label","props":{"y":10,"x":114,"width":30,"var":"total_page_text","valign":"middle","text":"1","height":32,"fontSize":20,"font":"SimHei","color":"#ffffff","align":"left"}}]}]},{"type":"Box","props":{"name":"item1"},"child":[{"type":"List","props":{"y":7,"x":8,"width":747,"visible":false,"var":"dijia_list","repeatY":20,"repeatX":1,"height":385},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"prize/item_bg_2.png"}},{"type":"Image","props":{"y":20,"x":20,"width":81,"name":"icon","height":81}},{"type":"Label","props":{"y":13,"x":109,"width":435,"valign":"middle","name":"name","height":60,"fontSize":26,"font":"SimHei"}},{"type":"Label","props":{"y":72,"x":110,"width":432,"visible":true,"valign":"middle","name":"end_time","height":37,"fontSize":20,"font":"SimHei","align":"left"}},{"type":"Button","props":{"y":36,"x":567,"stateNum":"1","skin":"prize/goto.png","name":"goto"}}]}]},{"type":"Box","props":{"y":398,"x":243,"visible":false},"child":[{"type":"Button","props":{"var":"pre_page_dijia","stateNum":"2","skin":"bakeroom/tab.png","labelSize":18,"labelFont":"SimHei","labelColors":"#672416,#672416","label":"上一页"}},{"type":"Button","props":{"x":145,"var":"next_page_dijia","stateNum":"2","skin":"bakeroom/tab.png","labelSize":18,"labelFont":"SimHei","labelColors":"#672416,#672416","label":"下一页"}},{"type":"Label","props":{"y":10,"x":76,"width":30,"var":"curr_page_text_dijia","valign":"middle","text":"1","height":32,"fontSize":20,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":10,"x":107,"width":8,"valign":"middle","text":"/","height":32,"fontSize":20,"color":"#ffffff"}},{"type":"Label","props":{"y":10,"x":114,"width":30,"var":"total_page_text_dijia","valign":"middle","text":"1","height":32,"fontSize":20,"font":"SimHei","color":"#ffffff","align":"left"}}]}]}]}]};}
		]);
		return AddressUI;
	})(Dialog);
var ARDialogUI=(function(_super){
		function ARDialogUI(){
			
		    this.left_item0=null;
		    this.left_item1=null;
		    this.left_item2=null;
		    this.left_item3=null;
		    this.left_item4=null;
		    this.left_item5=null;
		    this.item_selected=null;
		    this.Aging_btn=null;
		    this.speedup_btn=null;
		    this.need_ledou=null;
		    this.Countdown=null;
		    this.timeprogress=null;
		    this.lingqu_btn=null;
		    this.upgrade1_btn=null;
		    this.upgrade2_btn=null;
		    this.help_btn=null;
		    this.status_0=null;
		    this.status_1=null;
		    this.status_2=null;
		    this.status_3=null;
		    this.name_1=null;
		    this.name_2=null;
		    this.name_3=null;
		    this.name_4=null;
		    this.tab=null;
		    this.view_stack=null;
		    this.List0=null;
		    this.List1=null;
		    this.List2=null;
		    this.List3=null;
		    this.List4=null;

			ARDialogUI.__super.call(this);
		}

		CLASS$(ARDialogUI,'ui.ARDialogUI',_super);
		var __proto__=ARDialogUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ARDialogUI.uiView);
		}

		STATICATTR$(ARDialogUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"agingroom/bg.png","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Image","props":{"y":476,"x":33,"skin":"agingroom/shuoming_aging.png"}}]},{"type":"Box","props":{"y":52,"x":63,"width":416,"name":"left","height":439},"child":[{"type":"Image","props":{"y":160,"x":46,"var":"left_item0","skin":"depot/wupindiban.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":160,"x":155,"var":"left_item1","skin":"depot/wupindiban.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":160,"x":265,"var":"left_item2","skin":"depot/wupindiban.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":160,"x":372,"var":"left_item3","skin":"depot/wupindiban.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":276,"x":16,"visible":false,"var":"left_item4","skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":276,"x":326,"visible":false,"var":"left_item5","skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":159,"x":46,"width":79,"var":"item_selected","skin":"lubiantan/wupingkuang_1.png","height":79,"anchorY":0.5,"anchorX":0.5}},{"type":"Button","props":{"y":389,"x":647,"var":"Aging_btn","stateNum":"2","skin":"agingroom/button_aging.png","scaleY":1.2,"scaleX":1.2}},{"type":"Button","props":{"y":385,"x":495,"width":107,"var":"speedup_btn","stateNum":"2","skin":"bakeroom/button_jiashu.png","scaleY":1.2,"scaleX":1.2,"height":47},"child":[{"type":"Label","props":{"y":10,"x":57,"width":28,"var":"need_ledou","valign":"middle","text":"0","height":29,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Image","props":{"y":304,"x":92,"skin":"agingroom/shijian_5.png"},"child":[{"type":"Label","props":{"y":-26,"x":66,"width":100,"var":"Countdown","valign":"middle","strokeColor":"#672416","stroke":2,"height":20,"fontSize":16,"color":"#ffffff","align":"center"}},{"type":"ProgressBar","props":{"y":6,"x":27,"width":196,"var":"timeprogress","value":0,"skin":"agingroom/progress.png","sizeGrid":"0,5,0,3","height":14}},{"type":"Image","props":{"y":-2,"x":-2,"skin":"agingroom/shijian_2.png"}},{"type":"Image","props":{"y":-30,"x":-1,"skin":"agingroom/shijian_1.png"}}]},{"type":"Button","props":{"y":390,"x":650,"width":110,"visible":false,"var":"lingqu_btn","stateNum":"2","skin":"bakeroom/button_lingqu.png","scaleY":1.2,"scaleX":1.2,"height":41}},{"type":"Image","props":{"y":287,"x":26,"visible":false,"var":"upgrade1_btn","skin":"agingroom/jia.png"}},{"type":"Image","props":{"y":287,"x":337,"visible":false,"var":"upgrade2_btn","skin":"agingroom/jia.png"}},{"type":"Button","props":{"y":-35,"x":738,"width":68,"var":"help_btn","stateNum":"2","skin":"ui/button_help.png","height":36}},{"type":"Image","props":{"y":184,"x":46,"visible":false,"var":"status_0","skin":"bakeroom/dengdai.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":184,"x":155,"visible":false,"var":"status_1","skin":"bakeroom/dengdai.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":184,"x":265,"visible":false,"var":"status_2","skin":"bakeroom/dengdai.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":184,"x":372,"visible":false,"var":"status_3","skin":"bakeroom/dengdai.png","anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":198,"x":-3,"wordWrap":true,"width":100,"var":"name_1","valign":"middle","height":28,"fontSize":16,"font":"SimHei","align":"center"}},{"type":"Label","props":{"y":198,"x":106,"wordWrap":true,"width":100,"var":"name_2","valign":"middle","height":28,"fontSize":16,"font":"SimHei","align":"center"}},{"type":"Label","props":{"y":198,"x":214,"wordWrap":true,"width":100,"var":"name_3","valign":"middle","height":28,"fontSize":16,"font":"SimHei","align":"center"}},{"type":"Label","props":{"y":198,"x":323,"wordWrap":true,"width":100,"var":"name_4","valign":"middle","height":28,"fontSize":16,"font":"SimHei","align":"center"}}]},{"type":"Box","props":{"y":60,"x":514,"width":365,"name":"right","height":369},"child":[{"type":"Tab","props":{"y":-7,"x":-9,"var":"tab","stateNum":2,"space":5,"skin":"bakeroom/tab.png","selectedIndex":0,"labels":"一星,二星,三星,四星,五星","labelSize":26,"labelColors":"#672416,#672416","labelBold":true}},{"type":"ViewStack","props":{"y":58,"x":10,"width":345,"visible":false,"var":"view_stack","height":300},"child":[{"type":"List","props":{"y":0,"x":0,"width":345,"var":"List0","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":15,"repeatY":2,"repeatX":3,"name":"item0","height":300},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Label","props":{"y":76,"x":7,"width":89,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":101,"x":2,"wordWrap":true,"width":100,"name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":21,"x":20,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":113,"x":35,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得一星烘烤烟叶，可通过烘烤获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":345,"var":"List1","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":15,"repeatY":2,"repeatX":3,"name":"item1","height":300},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Label","props":{"y":76,"x":7,"width":89,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":101,"x":2,"wordWrap":true,"width":100,"name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":21,"x":20,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":113,"x":35,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得二星烘烤烟叶，可通过烘烤获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":345,"var":"List2","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":15,"repeatY":2,"repeatX":3,"name":"item2","height":300},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Label","props":{"y":76,"x":7,"width":89,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":101,"x":2,"wordWrap":true,"width":100,"name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":21,"x":20,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":113,"x":35,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得三星烘烤烟叶，可通过烘烤获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":345,"var":"List3","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":15,"repeatY":2,"repeatX":3,"name":"item3","height":300},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Label","props":{"y":76,"x":7,"width":89,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":101,"x":2,"wordWrap":true,"width":100,"name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":21,"x":20,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":113,"x":35,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得四星烘烤烟叶，可通过烘烤获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":345,"var":"List4","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":15,"repeatY":2,"repeatX":3,"name":"item4","height":300},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Label","props":{"y":76,"x":7,"width":89,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":101,"x":2,"wordWrap":true,"width":100,"name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":21,"x":20,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":113,"x":35,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得五星烘烤烟叶，可通过烘烤获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]}]}]},{"type":"Button","props":{"y":-7,"x":892,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}}]};}
		]);
		return ARDialogUI;
	})(Dialog);
var BakingRoomUI=(function(_super){
		function BakingRoomUI(){
			
		    this.Item0=null;
		    this.Item1=null;
		    this.Item2=null;
		    this.Item3=null;
		    this.item_selected=null;
		    this.Countdown=null;
		    this.timeprogress=null;
		    this.status_0=null;
		    this.status_1=null;
		    this.status_2=null;
		    this.status_3=null;
		    this.name_1=null;
		    this.name_2=null;
		    this.name_3=null;
		    this.name_4=null;
		    this.fire_box=null;
		    this.stop_btn=null;
		    this.fire_text=null;
		    this.fire_name=null;
		    this.tab=null;
		    this.view_stack=null;
		    this.List0=null;
		    this.List1=null;
		    this.List2=null;
		    this.List3=null;
		    this.List4=null;
		    this.help_btn=null;
		    this.speedup_btn=null;
		    this.need_ledou=null;
		    this.Baking_btn=null;
		    this.lingqu_btn=null;

			BakingRoomUI.__super.call(this);
		}

		CLASS$(BakingRoomUI,'ui.BakingRoomUI',_super);
		var __proto__=BakingRoomUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(BakingRoomUI.uiView);
		}

		STATICATTR$(BakingRoomUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"bakeroom/hongkaodiban.png","name":"bg"}},{"type":"Box","props":{"y":51,"x":50,"width":408,"height":441},"child":[{"type":"Image","props":{"y":142,"x":47,"var":"Item0","skin":"bakeroom/item_bg.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":142,"x":152,"var":"Item1","skin":"bakeroom/item_bg.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":142,"x":256,"var":"Item2","skin":"bakeroom/item_bg.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":142,"x":361,"var":"Item3","skin":"bakeroom/item_bg.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":142,"x":47,"visible":false,"var":"item_selected","skin":"bakeroom/item_selected.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":278,"x":88,"skin":"agingroom/shijian_5.png"},"child":[{"type":"Label","props":{"y":-22,"x":66,"width":100,"var":"Countdown","valign":"middle","strokeColor":"#672416","stroke":2,"height":20,"fontSize":16,"color":"#ffffff","align":"center"}},{"type":"ProgressBar","props":{"y":6,"x":27,"width":196,"var":"timeprogress","value":0,"skin":"agingroom/progress.png","sizeGrid":"0,5,0,3","height":14}},{"type":"Image","props":{"y":-2,"x":-2,"skin":"agingroom/shijian_2.png"}},{"type":"Image","props":{"y":-30,"x":-1,"skin":"agingroom/shijian_1.png"}}]},{"type":"Image","props":{"y":173,"x":47,"visible":false,"var":"status_0","skin":"bakeroom/dengdai.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":173,"x":152,"visible":false,"var":"status_1","skin":"bakeroom/dengdai.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":173,"x":256,"visible":false,"var":"status_2","skin":"bakeroom/dengdai.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":173,"x":361,"visible":false,"var":"status_3","skin":"bakeroom/dengdai.png","anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":201,"x":0,"wordWrap":true,"width":97,"var":"name_1","valign":"middle","height":34,"fontSize":16,"font":"SimHei","align":"center"}},{"type":"Label","props":{"y":201,"x":104,"wordWrap":true,"width":97,"var":"name_2","valign":"middle","height":34,"fontSize":16,"font":"SimHei","align":"center"}},{"type":"Label","props":{"y":201,"x":207,"wordWrap":true,"width":97,"var":"name_3","valign":"middle","height":34,"fontSize":16,"font":"SimHei","align":"center"}},{"type":"Label","props":{"y":201,"x":311,"wordWrap":true,"width":97,"var":"name_4","valign":"middle","height":34,"fontSize":16,"font":"SimHei","align":"center"}},{"type":"Box","props":{"y":320,"x":74,"visible":false,"var":"fire_box"},"child":[{"type":"Button","props":{"y":56,"x":56,"var":"stop_btn","stateNum":"2","skin":"bakeroom/tiancai_miehuo.png"}},{"type":"Image","props":{"y":0,"x":0,"width":260,"skin":"bakeroom/tiancai_diban_shijian.png","sizeGrid":"15,10,15,10","height":62}},{"type":"Label","props":{"y":24,"x":10,"wordWrap":true,"width":240,"var":"fire_text","valign":"middle","leading":2,"height":34,"fontSize":16,"font":"SimHei","color":"#672416","align":"center"}},{"type":"Label","props":{"y":2,"x":10,"width":240,"var":"fire_name","valign":"middle","height":24,"fontSize":16,"font":"SimHei","color":"#672416","align":"center"}}]}]},{"type":"Box","props":{"y":75,"x":482},"child":[{"type":"Tab","props":{"y":-34,"x":-9,"var":"tab","stateNum":2,"space":5,"skin":"bakeroom/tab.png","labels":"一星,二星,三星,四星,五星","labelSize":26,"labelColors":"#672416,#672416","labelBold":true}},{"type":"ViewStack","props":{"y":25,"x":0,"width":378,"visible":false,"var":"view_stack","height":293},"child":[{"type":"List","props":{"width":387,"var":"List0","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":30,"repeatY":3,"repeatX":3,"name":"item0","height":293},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Label","props":{"y":76,"x":7,"width":89,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":18,"color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":101,"x":2,"wordWrap":true,"width":100,"name":"name","height":34,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":20,"x":20,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":110,"x":53,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得一星烟叶，可通过种植获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":387,"var":"List1","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":30,"repeatY":3,"repeatX":3,"name":"item1","height":293},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Label","props":{"y":76,"x":7,"width":89,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":18,"color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":101,"x":2,"wordWrap":true,"width":100,"name":"name","height":34,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":20,"x":20,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":110,"x":53,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得二星烟叶，可通过种植获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":387,"var":"List2","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":30,"repeatY":3,"repeatX":3,"name":"item2","height":293},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Label","props":{"y":76,"x":7,"width":89,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":18,"color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":101,"x":2,"wordWrap":true,"width":100,"name":"name","height":34,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":20,"x":20,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":110,"x":53,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得三星烟叶，可通过种植获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":387,"var":"List3","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":30,"repeatY":3,"repeatX":3,"name":"item3","height":293},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Label","props":{"y":76,"x":7,"width":89,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":18,"color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":101,"x":2,"wordWrap":true,"width":100,"name":"name","height":34,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":20,"x":20,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":110,"x":53,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得四星烟叶，可通过种植获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":387,"var":"List4","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":30,"repeatY":3,"repeatX":3,"name":"item4","height":293},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Label","props":{"y":76,"x":7,"width":89,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":18,"color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":101,"x":2,"wordWrap":true,"width":100,"name":"name","height":34,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":20,"x":20,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":110,"x":53,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得五星烟叶，可通过种植获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]}]},{"type":"Button","props":{"y":-66,"x":304,"width":68,"var":"help_btn","stateNum":"2","skin":"ui/button_help.png","height":36}}]},{"type":"Button","props":{"y":-17,"x":859,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Button","props":{"y":424,"x":515,"var":"speedup_btn","stateNum":"2","skin":"bakeroom/button_jiashu.png"},"child":[{"type":"Label","props":{"y":16,"x":73,"width":33,"var":"need_ledou","valign":"middle","text":"0","height":29,"fontSize":22,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Button","props":{"y":425,"x":665,"var":"Baking_btn","stateNum":"2","skin":"bakeroom/button_hongkao.png"}},{"type":"Button","props":{"y":425,"x":665,"visible":false,"var":"lingqu_btn","stateNum":"2","skin":"bakeroom/button_lingqu.png"}}]};}
		]);
		return BakingRoomUI;
	})(Dialog);
var blackUI=(function(_super){
		function blackUI(){
			

			blackUI.__super.call(this);
		}

		CLASS$(blackUI,'ui.blackUI',_super);
		var __proto__=blackUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(blackUI.uiView);
		}

		STATICATTR$(blackUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"black/jinggao_2.png"}},{"type":"Button","props":{"y":260,"x":176,"stateNum":"2","skin":"ui/button_queding.png","name":"close"}}]};}
		]);
		return blackUI;
	})(Dialog);
var box_open_tipsUI=(function(_super){
		function box_open_tipsUI(){
			
		    this.item_icon=null;
		    this.item_tips=null;
		    this.btn_get_item=null;

			box_open_tipsUI.__super.call(this);
		}

		CLASS$(box_open_tipsUI,'ui.box_open_tipsUI',_super);
		var __proto__=box_open_tipsUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(box_open_tipsUI.uiView);
		}

		STATICATTR$(box_open_tipsUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"fragment/open_box_tips.png"}},{"type":"Image","props":{"y":119,"x":181,"var":"item_icon","skin":"icon/peifang_5_5.png"}},{"type":"Label","props":{"y":239,"x":18,"width":400,"var":"item_tips","valign":"middle","text":"恭喜开箱获得四星原生态调香书*5","height":30,"fontSize":24,"font":"SimHei","color":"#ffea00","align":"center"}},{"type":"Button","props":{"y":330,"x":153,"var":"btn_get_item","stateNum":"1","skin":"fragment/btn_get_item.png"}}]};}
		]);
		return box_open_tipsUI;
	})(Dialog);
var bozhongUI=(function(_super){
		function bozhongUI(){
			
		    this.bg=null;
		    this.table=null;
		    this.upgrade_btn=null;
		    this.Plant_btn=null;
		    this.view_stack=null;
		    this.list0=null;
		    this.list1=null;
		    this.list2=null;
		    this.list3=null;
		    this.list4=null;

			bozhongUI.__super.call(this);
		}

		CLASS$(bozhongUI,'ui.bozhongUI',_super);
		var __proto__=bozhongUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(bozhongUI.uiView);
		}

		STATICATTR$(bozhongUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"y":270,"x":50,"popupCenter":false,"pivotY":270,"pivotX":50,"mouseThrough":true},"child":[{"type":"Image","props":{"y":0,"x":0,"var":"bg","skin":"bozhong/bozhongdiban.png","sizeGrid":"30,5,5,5","mouseThrough":false,"mouseEnabled":true,"cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Box","props":{"y":173,"x":107},"child":[{"type":"Label","props":{"text":"可到","fontSize":24,"font":"SimHei"}},{"type":"Label","props":{"x":48,"text":"真龙商行","fontSize":24,"font":"SimHei","color":"#ff0000"}},{"type":"Label","props":{"x":146,"text":"购买新土地","fontSize":24,"font":"SimHei"}},{"type":"Label","props":{"y":0,"x":254,"text":"（长按显示物品名称）","fontSize":24,"font":"SimHei"}}]}]},{"type":"Tab","props":{"y":1,"x":15,"var":"table","stateNum":2,"space":-5,"skin":"bozhong/tab.png","selectedIndex":0,"labels":"一星, 二星,三星,四星,五星","labelSize":26,"labelPadding":"0,0,2,0","labelColors":"#672416,#672416","labelBold":true}},{"type":"Button","props":{"y":16,"x":532,"var":"upgrade_btn","stateNum":"2","skin":"bozhong/button_tudishengji.png"}},{"type":"Button","props":{"y":90,"x":527,"var":"Plant_btn","stateNum":"2","skin":"bozhong/button_zhongzhi.png"}},{"type":"ViewStack","props":{"y":66,"x":20,"width":502,"var":"view_stack","height":100},"child":[{"type":"List","props":{"y":0,"x":0,"width":502,"var":"list0","spaceX":5,"repeatY":1,"repeatX":5,"name":"item0","height":100},"child":[{"type":"Box","props":{"y":2,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":94,"skin":"bakeroom/kuang.png","height":94}},{"type":"Image","props":{"y":5,"x":5,"width":86,"name":"icon","height":86}},{"type":"Label","props":{"y":66,"x":2,"width":85,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":22,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Image","props":{"y":-1,"x":1,"width":94,"visible":false,"skin":"lubiantan/wupingkuang_1.png","sizeGrid":"10,10,10,10","name":"select","height":94}}]},{"type":"Label","props":{"y":13,"x":65,"wordWrap":true,"width":372,"visible":false,"valign":"middle","text":"还未获得一星种子，可到真龙商行、种子培育中心培育或参加关卡游戏获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":502,"var":"list1","spaceX":5,"repeatY":1,"repeatX":5,"name":"item1","height":100},"child":[{"type":"Box","props":{"y":2,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":94,"skin":"bakeroom/kuang.png","height":94}},{"type":"Image","props":{"y":5,"x":5,"width":86,"name":"icon","height":86}},{"type":"Label","props":{"y":66,"x":2,"width":85,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":22,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Image","props":{"y":-1,"x":1,"width":94,"visible":false,"skin":"lubiantan/wupingkuang_1.png","sizeGrid":"10,10,10,10","name":"select","height":94}}]},{"type":"Label","props":{"y":13,"x":65,"wordWrap":true,"width":372,"visible":false,"valign":"middle","text":"还未获得二星种子，可到真龙商行、种子培育中心培育或参加关卡游戏获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":502,"var":"list2","spaceX":5,"repeatY":1,"repeatX":5,"name":"item2","height":100},"child":[{"type":"Box","props":{"y":2,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":94,"skin":"bakeroom/kuang.png","height":94}},{"type":"Image","props":{"y":5,"x":5,"width":86,"name":"icon","height":86}},{"type":"Label","props":{"y":66,"x":2,"width":85,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":22,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Image","props":{"y":-1,"x":1,"width":94,"visible":false,"skin":"lubiantan/wupingkuang_1.png","sizeGrid":"10,10,10,10","name":"select","height":94}}]},{"type":"Label","props":{"y":13,"x":65,"wordWrap":true,"width":372,"visible":false,"valign":"middle","text":"还未获得三星种子，可到真龙商行、种子培育中心培育或参加关卡游戏获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":502,"var":"list3","spaceX":5,"repeatY":1,"repeatX":5,"name":"item3","height":100},"child":[{"type":"Box","props":{"y":2,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":94,"skin":"bakeroom/kuang.png","height":94}},{"type":"Image","props":{"y":5,"x":5,"width":86,"name":"icon","height":86}},{"type":"Label","props":{"y":66,"x":2,"width":85,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":22,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Image","props":{"y":-1,"x":1,"width":94,"visible":false,"skin":"lubiantan/wupingkuang_1.png","sizeGrid":"10,10,10,10","name":"select","height":94}}]},{"type":"Label","props":{"y":13,"x":65,"wordWrap":true,"width":372,"visible":false,"valign":"middle","text":"还未获得四星种子，可通过种子培育中心或神秘商行获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":502,"var":"list4","spaceX":5,"repeatY":1,"repeatX":5,"name":"item4","height":100},"child":[{"type":"Box","props":{"y":2,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":94,"skin":"bakeroom/kuang.png","height":94}},{"type":"Image","props":{"y":5,"x":5,"width":86,"name":"icon","height":86}},{"type":"Label","props":{"y":66,"x":2,"width":85,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":22,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Image","props":{"y":-1,"x":1,"width":94,"visible":false,"skin":"lubiantan/wupingkuang_1.png","sizeGrid":"10,10,10,10","name":"select","height":94}}]},{"type":"Label","props":{"y":13,"x":65,"wordWrap":true,"width":372,"visible":false,"valign":"middle","text":"还未获得五星种子，可通过种子培育中心或神秘商行获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]}]}]};}
		]);
		return bozhongUI;
	})(Dialog);
var BuyConfirmUI=(function(_super){
		function BuyConfirmUI(){
			
		    this.buy_btn=null;
		    this.item_name=null;
		    this.item_cion=null;
		    this.item_details=null;
		    this.details=null;
		    this.sub_btn=null;
		    this.add_btn=null;
		    this.buy_num=null;
		    this.buy_totals=null;
		    this.sale=null;
		    this.my_totals=null;
		    this.kucun=null;

			BuyConfirmUI.__super.call(this);
		}

		CLASS$(BuyConfirmUI,'ui.BuyConfirmUI',_super);
		var __proto__=BuyConfirmUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(BuyConfirmUI.uiView);
		}

		STATICATTR$(BuyConfirmUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"shop/dikuang.png"},"child":[{"type":"Button","props":{"y":372,"x":170,"var":"buy_btn","stateNum":"2","skin":"shop/btn_goumai.png"}},{"type":"Image","props":{"y":26,"x":136,"skin":"shop/pinmingdikuang.png"},"child":[{"type":"Label","props":{"y":13,"x":24,"width":166,"var":"item_name","valign":"middle","overflow":"hidden","height":34,"fontSize":18,"color":"#ffebb9","bold":true,"align":"center"}}]},{"type":"Image","props":{"y":106,"x":73,"skin":"shop/wupindiban.png"},"child":[{"type":"Image","props":{"y":8,"x":7,"width":64,"var":"item_cion","height":64}}]},{"type":"Label","props":{"y":113,"x":152,"wordWrap":true,"width":263,"visible":false,"var":"item_details","overflow":"scroll","leading":5,"height":122,"fontSize":20,"font":"SimHei","color":"#582f11","align":"left"}},{"type":"HTMLDivElement","props":{"y":113,"x":152,"width":263,"var":"details","height":122}},{"type":"Image","props":{"y":237,"x":228,"width":100,"skin":"shop/wupinshuzhi.png","sizeGrid":"13,13,13,13","height":36},"child":[{"type":"Button","props":{"y":-3,"x":-17,"width":40,"var":"sub_btn","stateNum":"2","skin":"depot/jian.png","height":40}},{"type":"Button","props":{"y":-3,"x":65,"width":40,"var":"add_btn","stateNum":"2","skin":"depot/jia.png","height":40}},{"type":"Label","props":{"y":5,"x":22,"width":49,"var":"buy_num","valign":"middle","text":"0","strokeColor":"#000000","stroke":2,"height":24,"fontSize":18,"color":"#ffffff","align":"left"}},{"type":"Label","props":{"y":7,"x":-64,"text":"数量","fontSize":22,"font":"SimHei","color":"#582f11"}}]},{"type":"Image","props":{"y":305,"x":228,"width":100,"skin":"shop/wupinshuzhi.png","sizeGrid":"13,13,13,13","height":36},"child":[{"type":"Image","props":{"y":-2,"x":-19,"width":40,"skin":"userinfo/lebi_big.png","height":40}},{"type":"Label","props":{"y":5,"x":22,"width":72,"var":"buy_totals","valign":"middle","text":"0","strokeColor":"#000000","stroke":2,"height":24,"fontSize":18,"color":"#ffffff","align":"left"}},{"type":"Label","props":{"y":6,"x":-64,"text":"花费","fontSize":22,"font":"SimHei","color":"#582f11"}},{"type":"Image","props":{"y":-3,"x":-123,"width":57,"visible":false,"var":"sale","skin":"shop/sale.png","height":44}}]},{"type":"Image","props":{"y":341,"x":228,"width":100,"skin":"shop/wupinshuzhi.png","sizeGrid":"13,13,13,13","height":36},"child":[{"type":"Image","props":{"y":-2,"x":-19,"width":40,"skin":"userinfo/lebi_big.png","height":40}},{"type":"Label","props":{"y":5,"x":22,"width":73,"var":"my_totals","valign":"middle","text":"0","strokeColor":"#000000","stroke":2,"height":24,"fontSize":18,"color":"#ffffff","align":"left"}},{"type":"Label","props":{"y":6,"x":-64,"text":"拥有","fontSize":22,"font":"SimHei","color":"#582f11"}}]},{"type":"Image","props":{"y":271,"x":228,"width":100,"skin":"shop/wupinshuzhi.png","sizeGrid":"13,13,13,13","height":36},"child":[{"type":"Label","props":{"y":5,"x":22,"width":73,"var":"kucun","valign":"middle","text":"0","strokeColor":"#000000","stroke":2,"height":24,"fontSize":18,"color":"#ffffff","align":"left"}},{"type":"Label","props":{"y":6,"x":-64,"text":"库存","fontSize":22,"font":"SimHei","color":"#582f11"}}]}]},{"type":"Button","props":{"y":-1,"x":458,"width":54,"stateNum":"2","skin":"ui/button_guanbi_fang.png","name":"close","height":49}}]};}
		]);
		return BuyConfirmUI;
	})(Dialog);
var ChangeHeaderUI=(function(_super){
		function ChangeHeaderUI(){
			
		    this.header_list=null;
		    this.selected=null;
		    this.time=null;
		    this.header_name=null;
		    this.from=null;

			ChangeHeaderUI.__super.call(this);
		}

		CLASS$(ChangeHeaderUI,'ui.ChangeHeaderUI',_super);
		var __proto__=ChangeHeaderUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ChangeHeaderUI.uiView);
		}

		STATICATTR$(ChangeHeaderUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"ui/touxiangdiban.png","cacheAsBitmap":true,"cacheAs":"bitmap"}},{"type":"List","props":{"y":88,"x":48,"width":328,"var":"header_list","vScrollBarSkin":"ui/vscroll.png","repeatY":2,"repeatX":3,"height":227},"child":[{"type":"Box","props":{"y":0,"x":0,"width":108,"renderType":"render","height":110},"child":[{"type":"Image","props":{"y":55,"x":54,"width":81,"skin":"ui/touxiangkuang_0.png","name":"header","height":76,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":55,"x":54,"width":131,"visible":false,"skin":"ui/touxiangshuanzhong.png","name":"selected","height":113,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":55,"x":54,"width":87,"visible":false,"skin":"ui/zhuling_shuo.png","name":"suo","height":45,"anchorY":0.5,"anchorX":0.5}}]}]},{"type":"Box","props":{"y":96,"x":399,"width":143,"height":213},"child":[{"type":"Image","props":{"y":40,"x":65,"width":100,"var":"selected","height":93,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":122.5,"x":85.5,"var":"time","text":"永久","fontSize":20,"font":"SimHei","color":"#210208"}},{"type":"Label","props":{"y":101.5,"x":66.5,"width":100,"var":"header_name","text":"劳动最光荣","height":20,"fontSize":20,"font":"SimHei","color":"#ffe0a5","anchorY":0.5,"anchorX":0.5,"align":"center"}},{"type":"Label","props":{"y":184.5,"x":67.5,"wordWrap":true,"width":135,"var":"from","valign":"middle","text":"解锁劳动最光荣成就","height":28,"fontSize":14,"font":"SimHei","color":"#473524","anchorY":0.5,"anchorX":0.5,"align":"center"}}]},{"type":"Button","props":{"y":-26,"x":531,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Label","props":{"y":314,"x":131,"text":"可上下滑动进行查看","fontSize":18,"font":"SimHei","color":"#65443c"}}]};}
		]);
		return ChangeHeaderUI;
	})(Dialog);
var chaxunUI=(function(_super){
		function chaxunUI(){
			
		    this.tab=null;
		    this.PF_List=null;
		    this.CL_List=null;

			chaxunUI.__super.call(this);
		}

		CLASS$(chaxunUI,'ui.chaxunUI',_super);
		var __proto__=chaxunUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(chaxunUI.uiView);
		}

		STATICATTR$(chaxunUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"factory/tiaoxiangshujiemian.png"}},{"type":"Tab","props":{"y":77,"x":53,"var":"tab","stateNum":2,"skin":"factory/tab.png","labels":"一星,二星,三星,四星,五星","labelSize":26,"labelPadding":"0,0,0,2","labelColors":"#74260a,#74260a","labelBold":true}},{"type":"List","props":{"y":130,"x":47,"width":840,"var":"PF_List","spaceX":7,"repeatY":1,"repeatX":7,"height":115},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"factory/peifang_zhi.png"}},{"type":"Image","props":{"y":10,"x":11,"width":91,"name":"icon","height":91}},{"type":"Label","props":{"y":77,"x":5,"wordWrap":true,"width":103,"valign":"middle","strokeColor":"#915832","stroke":2,"name":"name","height":31,"fontSize":16,"font":"SimHei","color":"#ffffff","align":"center"}},{"type":"Image","props":{"y":24,"x":23,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":21,"x":263,"wordWrap":true,"width":313,"visible":false,"valign":"middle","text":"还未获得一星调香书，可通过商行或调香研究所获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"List","props":{"y":301,"x":185,"width":611,"var":"CL_List","spaceX":5,"repeatY":1,"repeatX":4,"height":172},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":149,"skin":"factory/tiaoxiang_wupin_1219.png","height":171}},{"type":"Image","props":{"y":37,"x":31,"width":85,"name":"icon","height":85}},{"type":"Label","props":{"y":6,"x":6,"wordWrap":true,"width":136,"valign":"middle","name":"name","height":28,"fontSize":14,"font":"SimHei","color":"#915832","align":"center"}},{"type":"Label","props":{"y":127,"x":7,"width":60,"valign":"middle","text":"0","name":"hasNum","height":26,"fontSize":24,"font":"SimHei","align":"right"}},{"type":"Label","props":{"y":127,"x":65,"width":5,"valign":"middle","text":"/","height":26,"fontSize":24,"font":"SimHei","align":"center"}},{"type":"Label","props":{"y":127,"x":76,"width":64,"valign":"middle","text":"0","name":"needNum","height":26,"fontSize":24,"font":"SimHei","align":"left"}}]}]},{"type":"Button","props":{"y":-4,"x":881,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}}]};}
		]);
		return chaxunUI;
	})(Dialog);
var chengjiuUI=(function(_super){
		function chengjiuUI(){
			
		    this.pin_icon_0=null;
		    this.pin_icon_1=null;
		    this.pin_icon_2=null;
		    this.pin_icon_3=null;
		    this.pin_text=null;
		    this.plant_icon_0=null;
		    this.plant_icon_1=null;
		    this.plant_icon_2=null;
		    this.plant_icon_3=null;
		    this.plant_text=null;
		    this.jiao_icon_0=null;
		    this.jiao_icon_1=null;
		    this.jiao_icon_2=null;
		    this.jiao_icon_3=null;
		    this.jiao_text=null;
		    this.jia_icon_0=null;
		    this.jia_icon_1=null;
		    this.jia_icon_2=null;
		    this.jia_icon_3=null;
		    this.jia_text=null;

			chengjiuUI.__super.call(this);
		}

		CLASS$(chengjiuUI,'ui.chengjiuUI',_super);
		var __proto__=chengjiuUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(chengjiuUI.uiView);
		}

		STATICATTR$(chengjiuUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"userinfo/cehngjiu_bg.png"}},{"type":"Box","props":{"y":117,"x":30,"width":794,"height":110},"child":[{"type":"Label","props":{"y":5,"x":155,"width":296,"valign":"middle","text":"火眼金睛","strokeColor":"#000000","stroke":3,"height":49,"fontSize":26,"font":"SimHei","color":"#ffffff"}},{"type":"Image","props":{"y":8,"x":360,"var":"pin_icon_0","skin":"userinfo/Pinjian0.png","gray":true}},{"type":"Image","props":{"y":5,"x":469,"var":"pin_icon_1","skin":"userinfo/Pinjian1.png","gray":true}},{"type":"Image","props":{"y":5,"x":578,"var":"pin_icon_2","skin":"userinfo/Pinjian2.png","gray":true}},{"type":"Image","props":{"y":3,"x":687,"var":"pin_icon_3","skin":"userinfo/Pinjian3.png","gray":true}},{"type":"Label","props":{"y":60,"x":156,"wordWrap":true,"width":204,"var":"pin_text","valign":"middle","leading":5,"height":47,"fontSize":18,"font":"SimHei"}},{"type":"Label","props":{"y":82,"x":21,"width":106,"valign":"middle","text":"品鉴成就","strokeColor":"#000000","stroke":3,"height":22,"fontSize":22,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":230,"x":30,"width":794,"height":110},"child":[{"type":"Label","props":{"y":5,"x":155,"width":299,"valign":"middle","text":"劳作最光荣","strokeColor":"#000000","stroke":3,"height":49,"fontSize":26,"font":"SimHei","color":"#ffffff"}},{"type":"Image","props":{"y":5,"x":360,"var":"plant_icon_0","skin":"userinfo/Plant0.png","gray":true}},{"type":"Image","props":{"y":5,"x":469,"var":"plant_icon_1","skin":"userinfo/Plant1.png","gray":true}},{"type":"Image","props":{"y":5,"x":578,"var":"plant_icon_2","skin":"userinfo/Plant2.png","gray":true}},{"type":"Image","props":{"y":5,"x":687,"var":"plant_icon_3","skin":"userinfo/Plant3.png","gray":true}},{"type":"Label","props":{"y":60,"x":156,"wordWrap":true,"width":204,"var":"plant_text","valign":"middle","leading":5,"height":47,"fontSize":18,"font":"SimHei"}},{"type":"Label","props":{"y":82,"x":21,"width":106,"valign":"middle","text":"种植成就","strokeColor":"#000000","stroke":3,"height":22,"fontSize":22,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":340,"x":30,"width":794,"height":110},"child":[{"type":"Label","props":{"y":5,"x":155,"width":287,"valign":"middle","text":"致富之路","strokeColor":"#000000","stroke":3,"height":49,"fontSize":26,"font":"SimHei","color":"#ffffff"}},{"type":"Image","props":{"y":7,"x":360,"var":"jiao_icon_0","skin":"userinfo/Jiaoyi0.png","gray":true}},{"type":"Image","props":{"y":7,"x":469,"var":"jiao_icon_1","skin":"userinfo/Jiaoyi1.png","gray":true}},{"type":"Image","props":{"y":7,"x":578,"var":"jiao_icon_2","skin":"userinfo/Jiaoyi2.png","gray":true}},{"type":"Image","props":{"y":7,"x":687,"var":"jiao_icon_3","skin":"userinfo/Jiaoyi3.png","gray":true}},{"type":"Label","props":{"y":60,"x":156,"wordWrap":true,"width":204,"var":"jiao_text","valign":"middle","leading":5,"height":47,"fontSize":18,"font":"SimHei"}},{"type":"Label","props":{"y":82,"x":21,"width":106,"valign":"middle","text":"销售成就","strokeColor":"#000000","stroke":3,"height":22,"fontSize":22,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":454,"x":30,"width":794,"height":110},"child":[{"type":"Label","props":{"y":5,"x":155,"width":303,"valign":"middle","text":"能工巧匠","strokeColor":"#000000","stroke":3,"height":49,"fontSize":26,"font":"SimHei","color":"#ffffff"}},{"type":"Image","props":{"y":6,"x":360,"var":"jia_icon_0","skin":"userinfo/Zhiyan0.png","gray":true}},{"type":"Image","props":{"y":4,"x":469,"var":"jia_icon_1","skin":"userinfo/Zhiyan1.png","gray":true}},{"type":"Image","props":{"y":4,"x":578,"var":"jia_icon_2","skin":"userinfo/Zhiyan2.png","gray":true}},{"type":"Image","props":{"y":3,"x":687,"var":"jia_icon_3","skin":"userinfo/Zhiyan3.png","gray":true}},{"type":"Label","props":{"y":60,"x":156,"wordWrap":true,"width":204,"var":"jia_text","valign":"middle","leading":5,"height":47,"fontSize":18,"font":"SimHei"}},{"type":"Label","props":{"y":82,"x":21,"width":106,"valign":"middle","text":"制烟成就","strokeColor":"#000000","stroke":3,"height":22,"fontSize":22,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Button","props":{"y":-24,"x":814,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}}]};}
		]);
		return chengjiuUI;
	})(Dialog);
var chengjiu_introUI=(function(_super){
		function chengjiu_introUI(){
			
		    this.guang=null;
		    this.icon=null;
		    this.text=null;
		    this.share_btn=null;
		    this.chengjiu_name=null;

			chengjiu_introUI.__super.call(this);
		}

		CLASS$(chengjiu_introUI,'ui.chengjiu_introUI',_super);
		var __proto__=chengjiu_introUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(chengjiu_introUI.uiView);
		}

		STATICATTR$(chengjiu_introUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"userinfo/chengjiu_intro_bg.png"}},{"type":"Image","props":{"y":30,"x":148,"var":"guang","skin":"userinfo/chengjiu_guang.png"}},{"type":"Image","props":{"y":84,"x":202,"skin":"userinfo/chengjiu_intro.png"}},{"type":"Image","props":{"y":85,"x":208,"width":141,"var":"icon","height":141}},{"type":"Label","props":{"y":290,"x":75,"wordWrap":true,"width":405,"var":"text","valign":"middle","leading":5,"height":94,"fontSize":22,"font":"SimHei","color":"#fff0c5","align":"center"}},{"type":"Button","props":{"y":401,"x":202,"var":"share_btn","stateNum":"2","skin":"userinfo/button_share.png"}},{"type":"Button","props":{"y":-32,"x":519,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Label","props":{"y":43,"x":153,"width":250,"var":"chengjiu_name","valign":"middle","height":34,"fontSize":22,"font":"SimHei","color":"#fff0c5","align":"center"}}]};}
		]);
		return chengjiu_introUI;
	})(Dialog);
var chongziUI=(function(_super){
		function chongziUI(){
			
		    this.tab_top=null;
		    this.viewstack=null;
		    this.btn_goto_buy=null;
		    this.chongzi_big=null;
		    this.chongzi_middle=null;
		    this.chongzi_small=null;
		    this.tab_nengliang=null;
		    this.viewstack_nengliang=null;
		    this.list_nengliang=null;
		    this.jindutiao=null;
		    this.energy_text=null;
		    this.btn_lingqu=null;
		    this.energy_pre_page=null;
		    this.energy_next_page=null;
		    this.EnergyPages=null;
		    this.list_zhuanghuan=null;
		    this.change_pre_page=null;
		    this.change_next_page=null;
		    this.ChangePages=null;
		    this.list_jilu=null;
		    this.ruqin_pre_page=null;
		    this.ruqin_next_page=null;
		    this.JiLuPages=null;
		    this.help_btn=null;

			chongziUI.__super.call(this);
		}

		CLASS$(chongziUI,'ui.chongziUI',_super);
		var __proto__=chongziUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(chongziUI.uiView);
		}

		STATICATTR$(chongziUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":71,"x":17,"width":725,"skin":"chongzi/bg_common.png","sizeGrid":"10,50,40,50","height":457}},{"type":"Tab","props":{"y":0,"x":0,"var":"tab_top","selectedIndex":0},"child":[{"type":"Button","props":{"y":0,"x":0,"stateNum":"2","skin":"chongzi/btn_paiqiancongzi.png","name":"item0"}},{"type":"Button","props":{"y":0,"x":166,"stateNum":"2","skin":"chongzi/btn_wodenengliang.png","name":"item1"}},{"type":"Button","props":{"y":0,"x":332,"stateNum":"2","skin":"chongzi/btn_ruqingjilu.png","name":"item2"}}]},{"type":"ViewStack","props":{"y":76,"x":24,"var":"viewstack","selectedIndex":0},"child":[{"type":"Box","props":{"y":0,"x":0,"width":710,"name":"item0","height":450},"child":[{"type":"Box","props":{"y":5,"x":12},"child":[{"type":"Image","props":{"y":9,"x":-8,"skin":"chongzi/paiqiancongzi_zi2.png"}},{"type":"Button","props":{"y":-5,"x":486,"var":"btn_goto_buy","stateNum":"2","skin":"chongzi/btn_gotobuy.png"}},{"type":"Box","props":{"y":92,"x":502,"var":"chongzi_big"},"child":[{"type":"Image","props":{"skin":"chongzi/paiqian_bg.png"}},{"type":"Image","props":{"y":35,"x":5,"name":"icon"}},{"type":"Button","props":{"y":176,"x":27,"stateNum":"2","skin":"chongzi/btn_paiqian.png","name":"btn_paiqian"}},{"type":"Label","props":{"y":238,"x":0,"wordWrap":true,"width":158,"visible":false,"valign":"middle","name":"info","leading":3,"height":96,"fontSize":20,"font":"SimHei","color":"#776425","align":"center"}},{"type":"Image","props":{"y":243,"x":31,"visible":false,"skin":"chongzi/paiqiancongzi_zi1.png","name":"cando"}},{"type":"Label","props":{"y":139,"x":4,"width":150,"valign":"middle","name":"countdown","height":24,"fontSize":16,"font":"SimHei","align":"center"}}]},{"type":"Box","props":{"y":92,"x":264,"var":"chongzi_middle"},"child":[{"type":"Image","props":{"skin":"chongzi/paiqian_bg.png"}},{"type":"Image","props":{"y":35,"x":5,"name":"icon"}},{"type":"Button","props":{"y":176,"x":27,"stateNum":"2","skin":"chongzi/btn_paiqian.png","name":"btn_paiqian"}},{"type":"Label","props":{"y":238,"x":0,"wordWrap":true,"width":158,"visible":false,"valign":"middle","name":"info","leading":3,"height":96,"fontSize":20,"font":"SimHei","color":"#776425","align":"center"}},{"type":"Image","props":{"y":243,"x":31,"visible":false,"skin":"chongzi/paiqiancongzi_zi1.png","name":"cando"}},{"type":"Label","props":{"y":139,"x":4,"width":150,"valign":"middle","name":"countdown","height":24,"fontSize":16,"font":"SimHei","align":"center"}}]},{"type":"Box","props":{"y":92,"x":26,"var":"chongzi_small"},"child":[{"type":"Image","props":{"skin":"chongzi/paiqian_bg.png"}},{"type":"Image","props":{"y":35,"x":5,"name":"icon"}},{"type":"Button","props":{"y":176,"x":27,"stateNum":"2","skin":"chongzi/btn_paiqian.png","name":"btn_paiqian"}},{"type":"Label","props":{"y":238,"x":0,"wordWrap":true,"width":158,"visible":false,"valign":"middle","name":"info","leading":3,"height":96,"fontSize":20,"font":"SimHei","color":"#776425","align":"center"}},{"type":"Image","props":{"y":243,"x":31,"visible":false,"skin":"chongzi/paiqiancongzi_zi1.png","name":"cando"}},{"type":"Label","props":{"y":139,"x":4,"width":150,"valign":"middle","name":"countdown","height":24,"fontSize":16,"font":"SimHei","align":"center"}}]}]}]},{"type":"Box","props":{"width":710,"name":"item1","height":450},"child":[{"type":"Tab","props":{"y":1,"x":7,"var":"tab_nengliang","selectedIndex":0},"child":[{"type":"Button","props":{"y":0,"x":0,"stateNum":"2","skin":"chongzi/btn_common.png","name":"item0","labelSize":18,"labelFont":"SimHei","labelColors":"#661a02,#661a02","label":"当前能量"}},{"type":"Button","props":{"y":0,"x":115,"stateNum":"2","skin":"chongzi/btn_common.png","name":"item1","labelSize":18,"labelFont":"SimHei","labelColors":"#661a02,#661a02","label":"转换记录"}}]},{"type":"ViewStack","props":{"y":40,"x":0,"width":710,"var":"viewstack_nengliang","selectedIndex":0,"height":411},"child":[{"type":"Box","props":{"y":0,"x":0,"width":709,"name":"item0","height":410},"child":[{"type":"Image","props":{"y":307,"x":-6,"skin":"chongzi/wodenengliang_dadiban_2_1.png"}},{"type":"List","props":{"y":0,"x":16,"width":676,"visible":false,"var":"list_nengliang","vScrollBarSkin":"ui/vscroll.png","repeatY":20,"height":277},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":676,"skin":"chongzi/wodenengliang_diban1.png","sizeGrid":"5,8,5,5","height":92}},{"type":"Image","props":{"y":13,"x":0,"width":94,"skin":"chongzi/chong_big.png","name":"icon","height":65}},{"type":"Label","props":{"y":6,"x":98,"wordWrap":true,"width":458,"valign":"middle","text":"2018-08-30\\n在XXXXX好友家收集100能量","name":"info","leading":3,"height":80,"fontSize":20,"font":"SimHei","color":"#776425","align":"center"}},{"type":"Button","props":{"y":17,"x":561,"stateNum":"2","skin":"chongzi/btn_lingqu_nengliang.png","name":"btn_lingqu"}}]}]},{"type":"Box","props":{"y":322,"x":37},"child":[{"type":"Box","props":{"y":0,"x":0},"child":[{"type":"Image","props":{"y":19,"x":70,"width":380,"var":"jindutiao","skin":"chongzi/wodenengliang_2.png","height":38},"child":[{"type":"Sprite","props":{"y":0,"x":0,"width":380,"renderType":"mask","height":38},"child":[{"type":"Rect","props":{"y":0,"x":0,"width":380,"lineWidth":1,"height":38,"fillColor":"#ff0000"}}]}]},{"type":"Image","props":{"y":0,"x":0,"width":473,"skin":"chongzi/wodenengliang_1_1.png","sizeGrid":"20,25,20,75","height":72}},{"type":"Label","props":{"y":23,"x":70,"width":380,"var":"energy_text","valign":"middle","text":"可转换99闪电","strokeColor":"#4f8332","stroke":4,"height":30,"fontSize":20,"font":"SimHei","color":"#fffeae","bold":true,"align":"center"}}]},{"type":"Image","props":{"y":5,"x":10,"skin":"chongzi/shandian.png"}},{"type":"Button","props":{"y":6,"x":490,"var":"btn_lingqu","stateNum":"2","skin":"chongzi/btn_lingqu.png"}}]},{"type":"Box","props":{"y":273,"x":187},"child":[{"type":"Button","props":{"y":2,"x":0,"var":"energy_pre_page","stateNum":"2","skin":"ui/tab.png","labelSize":18,"labelPadding":"5,0,8,0","labelFont":"SimHei","labelColors":"#661a02,#661a02","label":"上一页"}},{"type":"Button","props":{"y":2,"x":220,"var":"energy_next_page","stateNum":"2","skin":"ui/tab.png","labelSize":18,"labelPadding":"5,0,8,0","labelFont":"SimHei","labelColors":"#661a02,#661a02","label":"下一页"}},{"type":"Label","props":{"y":6,"x":115,"width":107,"var":"EnergyPages","valign":"middle","height":29,"fontSize":18,"font":"SimHei","color":"#661a02","align":"center"}}]}]},{"type":"Box","props":{"y":0,"x":0,"width":710,"name":"item1","height":410},"child":[{"type":"List","props":{"y":0,"x":15,"width":680,"visible":false,"var":"list_zhuanghuan","vScrollBarSkin":"ui/vscroll.png","repeatY":4,"height":366},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":680,"skin":"chongzi/wodenengliang_diban1.png","sizeGrid":"5,8,5,5","height":92}},{"type":"Label","props":{"y":7,"x":20,"width":202,"valign":"middle","text":"2018-09-06 15:00","name":"time","height":26,"fontSize":18,"font":"SimHei","color":"#8d3b17"}},{"type":"Label","props":{"y":33,"x":20,"wordWrap":true,"width":640,"valign":"middle","name":"info","height":53,"fontSize":18,"font":"SimHei","color":"#8d3b17"}}]}]},{"type":"Box","props":{"y":368,"x":187},"child":[{"type":"Button","props":{"y":2,"x":0,"var":"change_pre_page","stateNum":"2","skin":"ui/tab.png","labelSize":18,"labelPadding":"5,0,8,0","labelFont":"SimHei","labelColors":"#661a02,#661a02","label":"上一页"}},{"type":"Button","props":{"y":2,"x":220,"var":"change_next_page","stateNum":"2","skin":"ui/tab.png","labelSize":18,"labelPadding":"5,0,8,0","labelFont":"SimHei","labelColors":"#661a02,#661a02","label":"下一页"}},{"type":"Label","props":{"y":6,"x":114,"width":107,"var":"ChangePages","valign":"middle","height":29,"fontSize":18,"font":"SimHei","color":"#661a02","align":"center"}}]}]}]}]},{"type":"Box","props":{"width":710,"name":"item2","height":450},"child":[{"type":"List","props":{"y":39,"x":15,"width":680,"visible":false,"var":"list_jilu","vScrollBarSkin":"ui/vscroll.png","repeatY":4,"height":365},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":680,"skin":"chongzi/wodenengliang_diban1.png","sizeGrid":"5,8,5,5","height":92}},{"type":"Image","props":{"y":13,"x":14,"width":69,"skin":"ui/header.jpg","name":"header","height":66}},{"type":"Image","props":{"y":11,"x":11,"width":75,"skin":"ui/header_bg.png","height":70}},{"type":"Image","props":{"y":-7,"x":-10,"width":49,"skin":"ui/xing1.png","height":43}},{"type":"Label","props":{"y":17,"x":16,"width":24,"valign":"middle","text":"50","strokeColor":"#000000","stroke":3,"name":"lv","height":30,"fontSize":16,"font":"SimHei","color":"#ffffff","anchorY":0.5,"anchorX":0.5,"align":"center"}},{"type":"Label","props":{"y":8,"x":98,"width":202,"valign":"middle","text":"2018-09-06 15:00","name":"time","height":26,"fontSize":18,"font":"SimHei","color":"#8d3b17"}},{"type":"Label","props":{"y":32,"x":98,"wordWrap":true,"width":504,"valign":"middle","text":"好友XXXXXXXXXXXX来烟厂使坏，放置了1只虫子在种植地，快及时清除！","name":"info","height":36,"fontSize":18,"font":"SimHei","color":"#8d3b17"}},{"type":"Label","props":{"y":69,"x":98,"width":162,"valign":"middle","text":"已获取能量值：20","name":"energy","height":22,"fontSize":18,"font":"SimHei","color":"#00658c"}},{"type":"Button","props":{"y":1,"x":604,"stateNum":"2","skin":"chongzi/btn_qingchu.png","name":"clear_btn"}}]}]},{"type":"Box","props":{"y":407,"x":187},"child":[{"type":"Button","props":{"y":2,"x":0,"var":"ruqin_pre_page","stateNum":"2","skin":"ui/tab.png","labelSize":18,"labelPadding":"5,0,8,0","labelFont":"SimHei","labelColors":"#661a02,#661a02","label":"上一页"}},{"type":"Button","props":{"y":2,"x":220,"var":"ruqin_next_page","stateNum":"2","skin":"ui/tab.png","labelSize":18,"labelPadding":"5,0,8,0","labelFont":"SimHei","labelColors":"#661a02,#661a02","label":"下一页"}},{"type":"Label","props":{"y":6,"x":114,"width":107,"var":"JiLuPages","valign":"middle","height":29,"fontSize":18,"font":"SimHei","color":"#661a02","align":"center"}}]}]}]},{"type":"Button","props":{"y":35,"x":698,"stateNum":"2","skin":"chongzi/btn_close.png","name":"close"}},{"type":"Button","props":{"y":75,"x":620,"width":80,"var":"help_btn","stateNum":"2","skin":"ui/button_help.png","height":42}}]};}
		]);
		return chongziUI;
	})(Dialog);
var chongzi_friendlistUI=(function(_super){
		function chongzi_friendlistUI(){
			
		    this.friend_list=null;

			chongzi_friendlistUI.__super.call(this);
		}

		CLASS$(chongzi_friendlistUI,'ui.chongzi_friendlistUI',_super);
		var __proto__=chongzi_friendlistUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(chongzi_friendlistUI.uiView);
		}

		STATICATTR$(chongzi_friendlistUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"width":635,"skin":"chongzi/bg_common.png","sizeGrid":"8,50,42,50","height":432},"child":[{"type":"Label","props":{"y":9,"x":167,"width":301,"valign":"middle","text":"选择好友进行派遣","strokeColor":"#776425","stroke":4,"height":40,"fontSize":24,"font":"SimHei","color":"#FFFFFF","align":"center"}}]},{"type":"List","props":{"y":54,"x":32,"width":570,"var":"friend_list","vScrollBarSkin":"ui/vscroll.png","spaceY":3,"repeatY":4,"height":358},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":570,"skin":"chongzi/wodenengliang_diban1.png","sizeGrid":"5,5,5,5","height":87}},{"type":"Image","props":{"y":9,"x":11,"width":71,"name":"icon","height":68}},{"type":"Image","props":{"y":5,"x":6,"width":81,"skin":"ui/header_bg.png","height":77}},{"type":"Label","props":{"y":9,"x":110,"width":209,"valign":"middle","name":"nickname","height":30,"fontSize":24,"font":"SimHei","color":"#776425"}},{"type":"Button","props":{"y":14,"x":448,"stateNum":"2","skin":"chongzi/btn_paiqian.png","name":"btn_paiqian"}}]}]},{"type":"Button","props":{"y":-33,"x":587,"stateNum":"2","skin":"chongzi/btn_close.png","name":"close"}}]};}
		]);
		return chongzi_friendlistUI;
	})(Dialog);
var ChouJiangUI=(function(_super){
		function ChouJiangUI(){
			
		    this.item0=null;
		    this.item1=null;
		    this.item2=null;
		    this.item3=null;
		    this.item4=null;
		    this.item5=null;
		    this.item6=null;
		    this.item7=null;
		    this.item8=null;
		    this.item9=null;
		    this.item10=null;
		    this.item11=null;
		    this.start_btn=null;

			ChouJiangUI.__super.call(this);
		}

		CLASS$(ChouJiangUI,'ui.ChouJiangUI',_super);
		var __proto__=ChouJiangUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ChouJiangUI.uiView);
		}

		STATICATTR$(ChouJiangUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"luckdraw/jiangchi_bg.png","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Image","props":{"y":280,"x":428,"skin":"luckdraw/chu.png"}},{"type":"Label","props":{"y":367,"x":417,"width":126,"valign":"middle","text":"800积分/次","height":26,"fontSize":20,"font":"SimHei","color":"#9B2A2E","align":"center"}}]},{"type":"Box","props":{"y":186,"x":171,"width":92,"var":"item0","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":303,"width":92,"var":"item1","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":435,"width":92,"var":"item2","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":567,"width":92,"var":"item3","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":699,"width":92,"var":"item4","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":316,"x":699,"width":92,"var":"item5","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":699,"width":92,"var":"item6","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":567,"width":92,"var":"item7","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":435,"width":92,"var":"item8","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":303,"width":92,"var":"item9","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":171,"width":92,"var":"item10","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":316,"x":171,"width":92,"var":"item11","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Button","props":{"y":123,"x":901,"width":52,"stateNum":"2","skin":"ui/guanbi.png","name":"close","height":58}},{"type":"Button","props":{"y":391,"x":436,"var":"start_btn","stateNum":"2","skin":"luckdraw/btn_choujiang.png"}}]};}
		]);
		return ChouJiangUI;
	})(Dialog);
var ChouJiangGaoUI=(function(_super){
		function ChouJiangGaoUI(){
			
		    this.item0=null;
		    this.item1=null;
		    this.item2=null;
		    this.item3=null;
		    this.item4=null;
		    this.item5=null;
		    this.item6=null;
		    this.item7=null;
		    this.item8=null;
		    this.item9=null;
		    this.item10=null;
		    this.item11=null;
		    this.start_btn=null;
		    this.selected_name=null;

			ChouJiangGaoUI.__super.call(this);
		}

		CLASS$(ChouJiangGaoUI,'ui.ChouJiangGaoUI',_super);
		var __proto__=ChouJiangGaoUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ChouJiangGaoUI.uiView);
		}

		STATICATTR$(ChouJiangGaoUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/jiangchi_bg.png"},"child":[{"type":"Image","props":{"y":280,"x":428,"skin":"luckdraw/gao.png"}}]},{"type":"Box","props":{"y":186,"x":171,"width":92,"var":"item0","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":303,"width":92,"var":"item1","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":435,"width":92,"var":"item2","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":567,"width":92,"var":"item3","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":699,"width":92,"var":"item4","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":316,"x":699,"width":92,"var":"item5","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":699,"width":92,"var":"item6","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":567,"width":92,"var":"item7","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":435,"width":92,"var":"item8","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":303,"width":92,"var":"item9","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":171,"width":92,"var":"item10","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":316,"x":171,"width":92,"var":"item11","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Button","props":{"y":123,"x":901,"width":52,"stateNum":"2","skin":"ui/guanbi.png","name":"close","height":58}},{"type":"Button","props":{"y":391,"x":436,"var":"start_btn","stateNum":"2","skin":"luckdraw/btn_choujiang.png"}},{"type":"Label","props":{"y":381,"x":480,"wordWrap":true,"width":199,"var":"selected_name","valign":"middle","height":26,"fontSize":20,"font":"SimHei","color":"#9B2A2E","anchorY":0.5,"anchorX":0.5,"align":"center"}}]};}
		]);
		return ChouJiangGaoUI;
	})(Dialog);
var ChouJiangZhongUI=(function(_super){
		function ChouJiangZhongUI(){
			
		    this.item11=null;
		    this.item10=null;
		    this.item9=null;
		    this.item8=null;
		    this.item7=null;
		    this.item6=null;
		    this.item5=null;
		    this.item4=null;
		    this.item3=null;
		    this.item2=null;
		    this.item1=null;
		    this.item0=null;
		    this.start_btn=null;
		    this.selected_name=null;

			ChouJiangZhongUI.__super.call(this);
		}

		CLASS$(ChouJiangZhongUI,'ui.ChouJiangZhongUI',_super);
		var __proto__=ChouJiangZhongUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ChouJiangZhongUI.uiView);
		}

		STATICATTR$(ChouJiangZhongUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"luckdraw/jiangchi_bg.png"}},{"type":"Box","props":{"y":316,"x":171,"width":92,"var":"item11","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":171,"width":92,"var":"item10","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":303,"width":92,"var":"item9","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":435,"width":92,"var":"item8","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":567,"width":92,"var":"item7","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":446,"x":699,"width":92,"var":"item6","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":316,"x":699,"width":92,"var":"item5","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":699,"width":92,"var":"item4","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":567,"width":92,"var":"item3","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":435,"width":92,"var":"item2","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":303,"width":92,"var":"item1","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":186,"x":171,"width":92,"var":"item0","height":90},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png","name":"bg"}},{"type":"Image","props":{"y":44,"x":44,"width":67,"name":"icon","height":67,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"skin":"luckdraw/item_selected.png","name":"item_mask"}},{"type":"Label","props":{"y":56,"x":8,"wordWrap":true,"width":76,"valign":"middle","strokeColor":"#000000","stroke":3,"name":"num","height":28,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Button","props":{"y":123,"x":901,"width":52,"stateNum":"2","skin":"ui/guanbi.png","name":"close","height":58}},{"type":"Button","props":{"y":391,"x":436,"var":"start_btn","stateNum":"2","skin":"luckdraw/btn_choujiang.png"}},{"type":"Label","props":{"y":379,"x":479,"wordWrap":true,"width":199,"var":"selected_name","valign":"middle","height":26,"fontSize":20,"font":"SimHei","color":"#9B2A2E","anchorY":0.5,"anchorX":0.5,"align":"center"}},{"type":"Image","props":{"y":280,"x":428,"skin":"luckdraw/zhong.png"}}]};}
		]);
		return ChouJiangZhongUI;
	})(Dialog);
var ChuLiDialogUI=(function(_super){
		function ChuLiDialogUI(){
			
		    this.man=null;
		    this.tips=null;
		    this.chuli_btn=null;
		    this.tool_normal=null;
		    this.land=null;
		    this.plant=null;
		    this.chongzi=null;
		    this.chongzi1=null;
		    this.tool_working=null;

			ChuLiDialogUI.__super.call(this);
		}

		CLASS$(ChuLiDialogUI,'ui.ChuLiDialogUI',_super);
		var __proto__=ChuLiDialogUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ChuLiDialogUI.uiView);
		}

		STATICATTR$(ChuLiDialogUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":24,"skin":"shijiang/tufashijian.png"},"child":[{"type":"Image","props":{"y":225,"x":33,"var":"man","skin":"shijiang/yannong_shiwang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":84,"x":129,"var":"tips","skin":"shijiang/zhi_1_1.png"}},{"type":"Button","props":{"y":341,"x":319,"var":"chuli_btn","stateNum":"2","skin":"shijiang/chuli.png"}},{"type":"Button","props":{"y":341,"x":103,"stateNum":"2","skin":"shijiang/fuanhui.png","name":"close"}},{"type":"Image","props":{"y":270,"x":496,"var":"tool_normal","skin":"shijiang/huasha_1.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":211,"x":204,"var":"land","skin":"shijiang/tudi_zhengchang.png"},"child":[{"type":"Image","props":{"y":-37,"x":5,"visible":false,"var":"plant","skin":"shijiang/yanyezhiwu_da.png"}},{"type":"Image","props":{"y":-36,"x":32,"visible":false,"var":"chongzi","skin":"shijiang/chong.png"}},{"type":"Image","props":{"y":22,"x":67,"visible":false,"var":"chongzi1","skin":"shijiang/chongzi.png"}}]},{"type":"Image","props":{"y":171,"x":357,"visible":false,"var":"tool_working","skin":"shijiang/huasha_2.png","anchorY":0.5,"anchorX":0.5}}]}]};}
		]);
		return ChuLiDialogUI;
	})(Dialog);
var CKDialogUI=(function(_super){
		function CKDialogUI(){
			
		    this.select_name=null;
		    this.select_icon=null;
		    this.select_num=null;
		    this.panel=null;
		    this.select_details=null;
		    this.add_btn=null;
		    this.sub_btn=null;
		    this.sale_num=null;
		    this.sale_currency=null;
		    this.sale_price=null;
		    this.sale_btn=null;
		    this.view_stack=null;
		    this.list_seed=null;
		    this.tab_yanye_1=null;
		    this.yanye_view_stack=null;
		    this.list_normal=null;
		    this.list_dry=null;
		    this.list_chun=null;
		    this.list_recipe=null;
		    this.tab_yan=null;
		    this.yan_view_stack=null;
		    this.list_cigarette=null;
		    this.list_cigarette_pin=null;
		    this.list_lvzui=null;
		    this.list_huafei=null;
		    this.progress=null;
		    this.max_num=null;
		    this.curr_num=null;
		    this.upgrade_btn=null;
		    this.tab_seed=null;
		    this.tab_yanye=null;
		    this.tab_recipe=null;
		    this.tab_Cigarette=null;
		    this.tab_lvzui=null;
		    this.tab_huafei=null;
		    this.close_btn=null;
		    this.zyf_btn=null;

			CKDialogUI.__super.call(this);
		}

		CLASS$(CKDialogUI,'ui.CKDialogUI',_super);
		var __proto__=CKDialogUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(CKDialogUI.uiView);
		}

		STATICATTR$(CKDialogUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"mouseThrough":false},"child":[{"type":"Box","props":{"y":0,"x":0,"staticCache":true,"name":"bg","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"depot/diban.png","sizeGrid":"0,0,0,0","mouseThrough":false}},{"type":"Image","props":{"y":33,"x":285,"skin":"depot/shengzi.png"}},{"type":"Image","props":{"y":1,"x":247,"skin":"depot/gan.png"}},{"type":"Image","props":{"y":15,"x":215,"width":92,"skin":"depot/yanye.png","height":513}},{"type":"Image","props":{"y":122,"x":503,"skin":"depot/shuoming_cangku.png"}}]},{"type":"Box","props":{"y":197,"x":23,"width":219,"name":"left","height":323},"child":[{"type":"Label","props":{"y":-8,"x":91,"wordWrap":true,"width":114,"var":"select_name","valign":"middle","leading":5,"height":52,"fontSize":18,"color":"#652114","bold":true}},{"type":"Image","props":{"y":-10,"x":12,"skin":"depot/wupindiban.png"},"child":[{"type":"Image","props":{"y":3,"x":3,"width":73,"var":"select_icon","height":73}}]},{"type":"Label","props":{"y":51,"x":90,"text":"当前数量","fontSize":16,"font":"SimHei","color":"#582f11"}},{"type":"Label","props":{"y":50,"x":155,"width":53,"var":"select_num","valign":"middle","height":20,"fontSize":18,"font":"SimHei","color":"#582f13","bold":true}},{"type":"Panel","props":{"y":76,"x":23,"width":176,"var":"panel","vScrollBarSkin":"ui/vscroll.png","height":84},"child":[{"type":"HTMLDivElement","props":{"y":3,"x":0,"width":175,"var":"select_details","height":85}}]},{"type":"Label","props":{"y":185,"x":7,"text":"数量","fontSize":22,"font":"SimHei","color":"#582f11"}},{"type":"Image","props":{"y":174,"x":81,"skin":"depot/shuzhidiban.png","sizeGrid":"10,15,10,15"},"child":[{"type":"Button","props":{"y":-6,"x":72,"width":55,"var":"add_btn","stateNum":"2","skin":"depot/jia.png","height":55}},{"type":"Button","props":{"y":-6,"x":-30,"width":55,"var":"sub_btn","stateNum":"2","skin":"depot/jian.png","height":55}},{"type":"Label","props":{"y":5,"x":23,"width":55,"var":"sale_num","valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"height":33,"fontSize":22,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Label","props":{"y":231,"x":7,"text":"总价","fontSize":22,"font":"SimHei","color":"#582f11"}},{"type":"Image","props":{"y":224,"x":139,"width":40,"var":"sale_currency","skin":"userinfo/lebi_big.png","height":40}},{"type":"Label","props":{"y":227,"x":67,"width":68,"var":"sale_price","valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"height":33,"fontSize":22,"font":"SimHei","color":"#ffffff","align":"center"}},{"type":"Button","props":{"y":264,"x":14,"var":"sale_btn","stateNum":"2","skin":"depot/btn_chushou.png"}}]},{"type":"Box","props":{"y":37,"x":262,"width":571,"name":"right","height":490},"child":[{"type":"ViewStack","props":{"y":153,"x":37,"width":481,"visible":false,"var":"view_stack","selectedIndex":0,"height":325},"child":[{"type":"List","props":{"y":0,"x":0,"width":478,"var":"list_seed","vScrollBarSkin":"ui/vscroll.png","spaceX":15,"repeatY":4,"repeatX":5,"name":"item0","mouseThrough":false,"height":320},"child":[{"type":"Box","props":{"y":-1,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":4,"width":72,"name":"icon","height":72}},{"type":"Label","props":{"y":54,"x":8,"width":64,"visible":false,"name":"item_name","height":20,"align":"center"}},{"type":"Label","props":{"y":78,"x":9,"width":60,"valign":"middle","text":"0","name":"item_num","height":18,"fontSize":16,"color":"#7c341a","bold":true,"align":"center"}},{"type":"Image","props":{"y":0,"x":0,"width":77,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected","height":77}}]}]},{"type":"Box","props":{"y":0,"x":0,"width":478,"name":"item1","height":320},"child":[{"type":"Tab","props":{"y":-48,"x":-18,"var":"tab_yanye_1","stateNum":2,"skin":"depot/tab_xiaofenlei.png","labels":"普通烟叶,烟叶(烤),烟叶(醇)","labelSize":14,"labelPadding":"0,0,1,0","labelColors":"#672416,#672416","labelBold":true}},{"type":"ViewStack","props":{"y":0,"x":0,"width":478,"var":"yanye_view_stack","height":320},"child":[{"type":"List","props":{"width":478,"var":"list_normal","vScrollBarSkin":"ui/vscroll.png","spaceX":15,"repeatY":4,"repeatX":5,"name":"item0","mouseThrough":false,"height":320},"child":[{"type":"Box","props":{"y":-1,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":4,"width":72,"name":"icon","height":72}},{"type":"Label","props":{"y":54,"x":8,"width":64,"visible":false,"name":"item_name","height":20,"align":"center"}},{"type":"Label","props":{"y":78,"x":9,"width":60,"valign":"middle","text":"0","name":"item_num","height":18,"fontSize":16,"color":"#7c341a","bold":true,"align":"center"}},{"type":"Image","props":{"width":77,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected","height":77}}]}]},{"type":"List","props":{"width":478,"var":"list_dry","vScrollBarSkin":"ui/vscroll.png","spaceX":15,"repeatY":4,"repeatX":5,"name":"item1","mouseThrough":false,"height":320},"child":[{"type":"Box","props":{"y":-1,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":4,"width":72,"name":"icon","height":72}},{"type":"Label","props":{"y":54,"x":8,"width":64,"visible":false,"name":"item_name","height":20,"align":"center"}},{"type":"Label","props":{"y":78,"x":9,"width":60,"valign":"middle","text":"0","name":"item_num","height":18,"fontSize":16,"color":"#7c341a","bold":true,"align":"center"}},{"type":"Image","props":{"width":77,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected","height":77}}]}]},{"type":"List","props":{"width":478,"var":"list_chun","vScrollBarSkin":"ui/vscroll.png","spaceX":15,"repeatY":4,"repeatX":5,"name":"item2","mouseThrough":false,"height":320},"child":[{"type":"Box","props":{"y":-1,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":4,"width":72,"name":"icon","height":72}},{"type":"Label","props":{"y":54,"x":8,"width":64,"visible":false,"name":"item_name","height":20,"align":"center"}},{"type":"Label","props":{"y":78,"x":9,"width":60,"valign":"middle","text":"0","name":"item_num","height":18,"fontSize":16,"color":"#7c341a","bold":true,"align":"center"}},{"type":"Image","props":{"width":77,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected","height":77}}]}]}]}]},{"type":"List","props":{"width":478,"var":"list_recipe","vScrollBarSkin":"ui/vscroll.png","spaceX":15,"repeatY":4,"repeatX":5,"name":"item2","mouseThrough":false,"height":320},"child":[{"type":"Box","props":{"y":-1,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":4,"width":72,"name":"icon","height":72}},{"type":"Label","props":{"y":54,"x":8,"width":64,"visible":false,"name":"item_name","height":20,"align":"center"}},{"type":"Label","props":{"y":78,"x":9,"width":60,"valign":"middle","text":"0","name":"item_num","height":18,"fontSize":16,"color":"#7c341a","bold":true,"align":"center"}},{"type":"Image","props":{"width":77,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected","height":77}}]}]},{"type":"Box","props":{"width":478,"name":"item3","height":320},"child":[{"type":"Tab","props":{"y":-48,"x":-18,"var":"tab_yan","stateNum":2,"skin":"depot/tab_xiaofenlei.png","labels":"普通香烟,香烟(品)","labelSize":14,"labelPadding":"0,0,1,0","labelColors":"#672416,#672416","labelBold":true}},{"type":"ViewStack","props":{"var":"yan_view_stack"},"child":[{"type":"List","props":{"width":478,"var":"list_cigarette","vScrollBarSkin":"ui/vscroll.png","spaceX":15,"repeatY":4,"repeatX":5,"name":"item0","mouseThrough":false,"height":320},"child":[{"type":"Box","props":{"y":-1,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":4,"width":72,"name":"icon","height":72}},{"type":"Label","props":{"y":54,"x":8,"width":64,"visible":false,"name":"item_name","height":20,"align":"center"}},{"type":"Label","props":{"y":78,"x":9,"width":60,"valign":"middle","text":"0","name":"item_num","height":18,"fontSize":16,"color":"#7c341a","bold":true,"align":"center"}},{"type":"Image","props":{"width":77,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected","height":77}}]}]},{"type":"List","props":{"width":478,"var":"list_cigarette_pin","vScrollBarSkin":"ui/vscroll.png","spaceX":15,"repeatY":4,"repeatX":5,"name":"item1","mouseThrough":false,"height":320},"child":[{"type":"Box","props":{"y":-1,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":4,"width":72,"name":"icon","height":72}},{"type":"Label","props":{"y":54,"x":8,"width":64,"visible":false,"name":"item_name","height":20,"align":"center"}},{"type":"Label","props":{"y":78,"x":9,"width":60,"valign":"middle","text":"0","name":"item_num","height":18,"fontSize":16,"color":"#7c341a","bold":true,"align":"center"}},{"type":"Image","props":{"width":77,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected","height":77}}]}]}]}]},{"type":"List","props":{"width":478,"var":"list_lvzui","vScrollBarSkin":"ui/vscroll.png","spaceX":15,"repeatY":4,"repeatX":5,"name":"item4","mouseThrough":false,"height":320},"child":[{"type":"Box","props":{"y":-1,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":4,"width":72,"name":"icon","height":72}},{"type":"Label","props":{"y":54,"x":8,"width":64,"visible":false,"name":"item_name","height":20,"align":"center"}},{"type":"Label","props":{"y":78,"x":9,"width":60,"valign":"middle","text":"0","name":"item_num","height":18,"fontSize":16,"color":"#7c341a","bold":true,"align":"center"}},{"type":"Image","props":{"width":77,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected","height":77}}]}]},{"type":"List","props":{"width":478,"var":"list_huafei","vScrollBarSkin":"ui/vscroll.png","spaceX":15,"repeatY":4,"repeatX":5,"name":"item5","mouseThrough":false,"height":320},"child":[{"type":"Box","props":{"y":-1,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":4,"width":72,"name":"icon","height":72}},{"type":"Label","props":{"y":54,"x":8,"width":64,"visible":false,"name":"item_name","height":20,"align":"center"}},{"type":"Label","props":{"y":78,"x":9,"width":60,"valign":"middle","text":"0","name":"item_num","height":18,"fontSize":16,"color":"#7c341a","bold":true,"align":"center"}},{"type":"Image","props":{"width":77,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected","height":77}}]}]}]},{"type":"Box","props":{"y":61,"x":37,"width":200,"height":33,"cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"ProgressBar","props":{"y":1,"x":-2,"width":162,"var":"progress","skin":"depot/progress.png","height":26}},{"type":"Label","props":{"y":4,"x":105,"width":45,"var":"max_num","valign":"middle","text":"1000","strokeColor":"#000000","stroke":3,"height":20,"fontSize":18,"color":"#ffffff","align":"left"}},{"type":"Label","props":{"y":4,"x":95,"width":5,"valign":"middle","text":"/","strokeColor":"#000000","stroke":3,"height":20,"fontSize":18,"color":"#ffffff"}},{"type":"Label","props":{"y":4,"x":45,"width":50,"var":"curr_num","valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"height":20,"fontSize":18,"color":"#ffffff","align":"right"}},{"type":"Button","props":{"y":-9,"x":148,"var":"upgrade_btn","stateNum":"2","skin":"depot/button_upgrade.png"}},{"type":"Label","props":{"y":3,"x":5,"width":36,"valign":"middle","text":"储量：","strokeColor":"#000000","stroke":3,"height":20,"fontSize":16,"color":"#ffffff"}}]},{"type":"Box","props":{"y":95,"x":521,"width":94,"mouseThrough":true,"height":375,"cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Button","props":{"y":10,"x":-1,"var":"tab_seed","strokeColors":"#ecd2af,#672416","stateNum":"2","skin":"depot/changku_tab_2.png","labelStroke":2,"labelSize":20,"labelPadding":"2,0,0,3","labelFont":"SimHei","labelColors":"#1c0608,#ecd2af","gray":true}},{"type":"Button","props":{"y":67,"x":-1,"var":"tab_yanye","strokeColors":"#ecd2af,#672416","stateNum":"2","skin":"depot/changku_tab_3.png","labelStroke":2,"labelSize":20,"labelPadding":"2,0,0,3","labelFont":"SimHei","labelColors":"#1c0608,#ecd2af","gray":true}},{"type":"Button","props":{"y":124,"x":0,"var":"tab_recipe","strokeColors":"#ecd2af,#672416","stateNum":"2","skin":"depot/changku_tab_4.png","gray":true}},{"type":"Button","props":{"y":182,"x":0,"var":"tab_Cigarette","stateNum":"2","skin":"depot/changku_tab_5.png","gray":true}},{"type":"Button","props":{"y":239,"x":0,"var":"tab_lvzui","stateNum":"2","skin":"depot/changku_tab_1.png","gray":true}},{"type":"Button","props":{"y":296,"x":0,"var":"tab_huafei","stateNum":"2","skin":"depot/changku_tab_6.png","gray":true}}]}]},{"type":"Button","props":{"y":38,"x":806,"var":"close_btn","stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Button","props":{"y":146,"x":629,"var":"zyf_btn","stateNum":"2","skin":"depot/zyf_btn.png"}}]};}
		]);
		return CKDialogUI;
	})(Dialog);
var confirmUI=(function(_super){
		function confirmUI(){
			
		    this.content=null;

			confirmUI.__super.call(this);
		}

		CLASS$(confirmUI,'ui.confirmUI',_super);
		var __proto__=confirmUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(confirmUI.uiView);
		}

		STATICATTR$(confirmUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"ui/confirm_bg.png","sizeGrid":"25,5,5,5"},"child":[{"type":"Label","props":{"y":32,"x":50,"wordWrap":true,"width":363,"var":"content","valign":"middle","padding":"10","height":108,"fontSize":20,"font":"SimHei","color":"#421203","align":"center"}},{"type":"Button","props":{"y":149,"x":163,"stateNum":"2","skin":"ui/button_queding.png","name":"yes","labelBold":true}}]}]};}
		]);
		return confirmUI;
	})(Dialog);
var confirm1UI=(function(_super){
		function confirm1UI(){
			
		    this.yes=null;
		    this.content=null;

			confirm1UI.__super.call(this);
		}

		CLASS$(confirm1UI,'ui.confirm1UI',_super);
		var __proto__=confirm1UI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(confirm1UI.uiView);
		}

		STATICATTR$(confirm1UI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"ui/confirm_bg.png","sizeGrid":"30,5,5,5"},"child":[{"type":"Button","props":{"y":141,"x":55,"width":140,"var":"yes","stateNum":"2","skin":"dati/button_queding.png","name":"yes","height":51}},{"type":"Button","props":{"y":138,"x":271,"stateNum":"2","skin":"ui/button_cancel.png","name":"no"}},{"type":"Label","props":{"y":46,"x":51,"wordWrap":true,"width":360,"var":"content","valign":"middle","leading":10,"height":90,"fontSize":20,"font":"SimHei","color":"#421203","align":"center"}}]}]};}
		]);
		return confirm1UI;
	})(Dialog);
var DatiUI=(function(_super){
		function DatiUI(){
			
		    this.ti_num=null;
		    this.content=null;
		    this.option1=null;
		    this.option2=null;
		    this.option3=null;
		    this.option4=null;
		    this.cuo=null;
		    this.dui=null;
		    this.precision=null;
		    this.progress=null;
		    this.intro_bg=null;
		    this.start_btn=null;

			DatiUI.__super.call(this);
		}

		CLASS$(DatiUI,'ui.DatiUI',_super);
		var __proto__=DatiUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("Text",laya.display.Text);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(DatiUI.uiView);
		}

		STATICATTR$(DatiUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":848,"height":469},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"dati/datiban.png","name":"bg"},"child":[{"type":"Image","props":{"y":67,"x":117,"skin":"dati/diwen_4.png"}},{"type":"Image","props":{"y":146,"x":114,"skin":"dati/ren.png"}},{"type":"Image","props":{"y":16,"x":351,"skin":"dati/meiridati.png"}},{"type":"Image","props":{"y":70,"x":119,"skin":"dati/diwen_3.png"}},{"type":"Image","props":{"y":381,"x":122,"skin":"dati/diwen_1.png"}}]},{"type":"Button","props":{"y":23,"x":775,"width":45,"stateNum":"2","skin":"ui/button_guanbi_fang.png","name":"close","height":41}},{"type":"Box","props":{"y":79,"x":123,"width":600,"height":371},"child":[{"type":"Label","props":{"y":0,"x":14,"width":56,"var":"ti_num","valign":"middle","text":"第1题","height":20,"fontSize":20,"font":"SimHei","color":"#c79368","align":"center"}},{"type":"Label","props":{"y":13,"x":118,"wordWrap":true,"width":436,"var":"content","valign":"middle","height":90,"fontSize":36,"font":"SimHei","color":"#efcfb0","align":"center"}},{"type":"Box","props":{"y":147,"x":182,"width":302,"name":"options","height":172},"child":[{"type":"Button","props":{"y":0,"x":-17,"visible":false,"var":"option1","stateNum":"2","skin":"dati/button_datianjian.png","labelSize":26,"labelFont":"SimHei","labelColors":"#401208,#401208","label":"A. "}},{"type":"Button","props":{"y":55,"x":-17,"visible":false,"var":"option2","stateNum":"2","skin":"dati/button_datianjian.png","labelSize":26,"labelFont":"SimHei","labelColors":"#401208,#401208","label":"B. "}},{"type":"Button","props":{"y":110,"x":-17,"visible":false,"var":"option3","stateNum":"2","skin":"dati/button_datianjian.png","labelSize":26,"labelFont":"SimHei","labelColors":"#401208,#401208","label":"C. "}},{"type":"Button","props":{"y":165,"x":-17,"visible":false,"var":"option4","stateNum":"2","skin":"dati/button_datianjian.png","labelSize":26,"labelFont":"SimHei","labelColors":"#401208,#401208","label":"D. "}},{"type":"Image","props":{"y":25.5,"x":331.5,"visible":false,"var":"cuo","skin":"dati/chuo.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":27.5,"x":331.5,"visible":false,"var":"dui","skin":"dati/dui.png","anchorY":0.5,"anchorX":0.5}}]},{"type":"Label","props":{"y":314,"x":17,"width":120,"visible":false,"var":"precision","valign":"middle","text":"正确率: 0%","height":22,"fontSize":20,"font":"SimHei","color":"#bf8d60"}},{"type":"Label","props":{"y":324,"x":17,"width":120,"var":"progress","valign":"middle","text":"答题进度 0/0","height":22,"fontSize":20,"font":"SimHei","color":"#bf8d60"}}]},{"type":"Image","props":{"y":64,"x":100,"var":"intro_bg","skin":"dati/datibeijin.png"},"child":[{"type":"Button","props":{"y":352,"x":252,"var":"start_btn","stateNum":"2","skin":"dati/kaishidati.png"}},{"type":"Text","props":{"y":26,"x":261,"wordWrap":true,"width":350,"text":"商行掌柜每天会在题板出五道考题，参与答题的烟农将会得到掌柜的奖励。答对的题数越多，得到的奖励越丰厚喔~","leading":8,"fontSize":18,"font":"SimHei","color":"#ffffff"}},{"type":"Image","props":{"y":128,"x":364,"skin":"dati/libaotu.png"}},{"type":"Text","props":{"y":297,"x":299,"text":"小提示：答对越多奖励越丰富喔！","fontSize":18,"font":"SimHei","color":"#fede8f"}},{"type":"Text","props":{"y":348,"x":484,"text":"活动时间：全天","fontSize":18,"font":"SimHei","color":"#ffffff"}}]}]};}
		]);
		return DatiUI;
	})(Dialog);
var Dati_jianliUI=(function(_super){
		function Dati_jianliUI(){
			
		    this.item1=null;
		    this.item0=null;
		    this.item2=null;
		    this.item3=null;
		    this.title=null;
		    this.content=null;
		    this.ok_btn=null;

			Dati_jianliUI.__super.call(this);
		}

		CLASS$(Dati_jianliUI,'ui.Dati_jianliUI',_super);
		var __proto__=Dati_jianliUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(Dati_jianliUI.uiView);
		}

		STATICATTR$(Dati_jianliUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":848,"height":469},"child":[{"type":"Image","props":{"skin":"dati/datiban.png","name":"bg"},"child":[{"type":"Image","props":{"y":154,"x":120,"skin":"dati/ren.png"}},{"type":"Image","props":{"y":13,"x":351,"skin":"dati/datijiangli.png"}},{"type":"Image","props":{"y":317,"x":351,"visible":false,"skin":"dati/mu.png"}}]},{"type":"Button","props":{"y":25,"x":780,"stateNum":"2","skin":"ui/button_guanbi_fang.png","name":"close"}},{"type":"Box","props":{"y":74,"x":162,"width":523,"height":243},"child":[{"type":"Image","props":{"y":58,"x":222,"visible":false,"var":"item1","skin":"dati/diwen_2.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":23,"x":188,"width":70,"var":"item0","height":70}},{"type":"Image","props":{"y":21,"x":284,"visible":false,"var":"item2","skin":"dati/diwen_2.png"}},{"type":"Image","props":{"y":59,"x":323,"width":70,"var":"item3","height":70,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":117,"x":119,"width":285,"var":"title","valign":"middle","height":22,"fontSize":20,"font":"SimHei","color":"#401208","bold":true,"align":"center"}},{"type":"Label","props":{"y":159,"x":119,"width":285,"var":"content","valign":"middle","leading":5,"height":83,"fontSize":26,"font":"SimHei","color":"#401208","align":"center"}},{"type":"Button","props":{"y":252,"x":191,"var":"ok_btn","stateNum":"2","skin":"dati/button_queding.png","name":"close"}}]}]};}
		]);
		return Dati_jianliUI;
	})(Dialog);
var DaZhuanPanUI=(function(_super){
		function DaZhuanPanUI(){
			
		    this.zhuanpan=null;
		    this.start_btn=null;
		    this.activity_num=null;
		    this.bean_num=null;

			DaZhuanPanUI.__super.call(this);
		}

		CLASS$(DaZhuanPanUI,'ui.DaZhuanPanUI',_super);
		var __proto__=DaZhuanPanUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(DaZhuanPanUI.uiView);
		}

		STATICATTR$(DaZhuanPanUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":960,"height":640},"child":[{"type":"Image","props":{"y":0,"x":1,"skin":"dazhuanpan/haoyunlinmen_bg.png","name":"caidai"}},{"type":"Box","props":{"y":310,"x":469,"width":482,"var":"zhuanpan","rotation":0,"height":482,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Image","props":{"y":241,"x":241,"skin":"dazhuanpan/haoyunlinmen_zhuanpan.png","rotation":0,"anchorY":0.5,"anchorX":0.5}}]},{"type":"Image","props":{"y":252,"x":466,"skin":"dazhuanpan/haoyunlinmen_zhizhen.png","anchorY":1,"anchorX":0.5}},{"type":"Button","props":{"y":310,"x":469,"var":"start_btn","stateNum":"1","skin":"dazhuanpan/haoyunlinmen_mianfeianniu.png","anchorY":0.5,"anchorX":0.5},"child":[{"type":"Image","props":{"y":88,"x":112,"width":43,"visible":false,"skin":"userinfo/ledou.png","name":"bean","height":32}},{"type":"Label","props":{"y":113,"x":30,"width":90,"visible":false,"valign":"middle","text":"10乐豆/次","strokeColor":"#000000","stroke":1,"name":"tips","height":26,"fontSize":20,"font":"SimHei","color":"#e3c009","align":"center"}}]},{"type":"Label","props":{"y":562,"x":781,"width":39,"var":"activity_num","valign":"middle","text":"0","height":26,"fontSize":20,"font":"SimHei","color":"#dd0d0a","bold":true,"align":"center"}},{"type":"Label","props":{"y":562,"x":830,"width":38,"var":"bean_num","valign":"middle","text":"0","height":26,"fontSize":20,"font":"SimHei","color":"#dd0d0a","bold":true,"align":"center"}},{"type":"Button","props":{"y":26,"x":869,"stateNum":"1","skin":"ui/close_btn.png","name":"close"}}]};}
		]);
		return DaZhuanPanUI;
	})(Dialog);
var DaZhuanPanConfirmUI=(function(_super){
		function DaZhuanPanConfirmUI(){
			

			DaZhuanPanConfirmUI.__super.call(this);
		}

		CLASS$(DaZhuanPanConfirmUI,'ui.DaZhuanPanConfirmUI',_super);
		var __proto__=DaZhuanPanConfirmUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(DaZhuanPanConfirmUI.uiView);
		}

		STATICATTR$(DaZhuanPanConfirmUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"dazhuanpan/haoyunlinmen_zhuyi.png"}},{"type":"Button","props":{"y":280,"x":28,"stateNum":"1","skin":"dazhuanpan/haoyunlinmen_fou.png","name":"no"}},{"type":"Button","props":{"y":280,"x":191,"stateNum":"1","skin":"dazhuanpan/haoyunlinmen_shi.png","name":"yes"}}]};}
		]);
		return DaZhuanPanConfirmUI;
	})(Dialog);
var DaZhuanPanResultUI=(function(_super){
		function DaZhuanPanResultUI(){
			
		    this.item_name=null;
		    this.item_icon=null;

			DaZhuanPanResultUI.__super.call(this);
		}

		CLASS$(DaZhuanPanResultUI,'ui.DaZhuanPanResultUI',_super);
		var __proto__=DaZhuanPanResultUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(DaZhuanPanResultUI.uiView);
		}

		STATICATTR$(DaZhuanPanResultUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"dazhuanpan/haoyunlinmen_jiangping.png"},"child":[{"type":"Label","props":{"y":213,"x":42,"width":271,"var":"item_name","valign":"middle","height":26,"fontSize":20,"font":"SimHei","align":"center"}},{"type":"Image","props":{"y":130,"x":142,"width":72,"skin":"bakeroom/wupindiwen.png","height":72},"child":[{"type":"Image","props":{"y":4,"x":4,"width":64,"var":"item_icon","height":64}}]}]},{"type":"Button","props":{"y":283,"x":110,"stateNum":"1","skin":"dazhuanpan/haoyunlinmen_queding.png","name":"close"}}]};}
		]);
		return DaZhuanPanResultUI;
	})(Dialog);
var Doubel12UI=(function(_super){
		function Doubel12UI(){
			
		    this.choujiang_title=null;
		    this.daoju_title=null;
		    this.btn_lingqu=null;
		    this.list=null;
		    this.tips=null;

			Doubel12UI.__super.call(this);
		}

		CLASS$(Doubel12UI,'ui.Doubel12UI',_super);
		var __proto__=Doubel12UI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(Doubel12UI.uiView);
		}

		STATICATTR$(Doubel12UI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":21,"x":32,"skin":"double11/1212bg.png"}},{"type":"Box","props":{"y":135,"x":188,"width":595,"height":450},"child":[{"type":"Image","props":{"y":77,"x":94,"visible":false,"var":"choujiang_title","skin":"double11/choujiangwenzi.png"}},{"type":"Image","props":{"y":77,"x":94,"visible":false,"var":"daoju_title","skin":"double11/daojuwenzi.png"}},{"type":"Button","props":{"y":351,"x":177,"var":"btn_lingqu","stateNum":"1","skin":"double11/lingqu.png"}},{"type":"List","props":{"y":150,"x":22,"width":550,"visible":false,"var":"list","spaceX":110,"repeatX":3,"height":120},"child":[{"type":"Box","props":{"y":0,"x":0,"width":110,"name":"render","height":120},"child":[{"type":"Image","props":{"y":0,"x":18,"skin":"shop/shenmikuan_1.png"}},{"type":"Label","props":{"y":76,"x":0,"wordWrap":true,"width":110,"valign":"middle","name":"item_name","leading":2,"height":40,"fontSize":16,"color":"#3a1a03","align":"center"}},{"type":"Image","props":{"y":5,"x":23,"width":64,"name":"item_icon","height":64}}]}]},{"type":"Label","props":{"y":274,"x":0,"width":595,"var":"tips","valign":"middle","text":"温馨提示：领取礼包后可进入【仓库】建筑查看。","height":28,"fontSize":16,"font":"SimHei","color":"#3a1a03","align":"center"}}]}]};}
		]);
		return Doubel12UI;
	})(Dialog);
var DrawsDialogUI=(function(_super){
		function DrawsDialogUI(){
			
		    this.goto=null;
		    this.item_icon=null;
		    this.item_name=null;

			DrawsDialogUI.__super.call(this);
		}

		CLASS$(DrawsDialogUI,'ui.DrawsDialogUI',_super);
		var __proto__=DrawsDialogUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(DrawsDialogUI.uiView);
		}

		STATICATTR$(DrawsDialogUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"draws/tanchuang_haoyun.png"},"child":[{"type":"Button","props":{"y":263,"x":102,"var":"goto","stateNum":"1","skin":"draws/tanchuang_lijiqianwang.png"}},{"type":"Image","props":{"y":117,"x":128,"width":72,"skin":"bakeroom/wupindiwen.png","height":72},"child":[{"type":"Image","props":{"y":4,"x":4,"width":64,"var":"item_icon","height":64}}]},{"type":"Label","props":{"y":196,"x":29,"width":271,"var":"item_name","valign":"middle","height":26,"fontSize":20,"font":"SimHei","align":"center"}}]}]};}
		]);
		return DrawsDialogUI;
	})(Dialog);
var FireDialogUI=(function(_super){
		function FireDialogUI(){
			
		    this.fire=null;
		    this.tips=null;
		    this.fire_btn_box=null;
		    this.fire_btn=null;

			FireDialogUI.__super.call(this);
		}

		CLASS$(FireDialogUI,'ui.FireDialogUI',_super);
		var __proto__=FireDialogUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(FireDialogUI.uiView);
		}

		STATICATTR$(FireDialogUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"bakeroom/tiancai_diban_weikao.png"}},{"type":"Image","props":{"y":87,"x":98,"visible":false,"var":"fire","skin":"bakeroom/tiancai_diban_fire.png"}},{"type":"Box","props":{"y":301,"x":8,"var":"tips"},"child":[{"type":"Image","props":{"width":474,"skin":"bakeroom/fire_text_bg.png","sizeGrid":"5,8,8,8","height":81}},{"type":"Label","props":{"y":9,"x":9,"wordWrap":true,"width":455,"valign":"middle","text":"添柴后可给好友烘烤加速2小时，之后12小时好友未浇灭火，可能会烧坏好友烟叶，确定添柴吗？","strokeColor":"#000000","stroke":2,"leading":5,"height":62,"fontSize":20,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Button","props":{"y":11,"x":366,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Box","props":{"y":260,"x":91,"var":"fire_btn_box"},"child":[{"type":"Button","props":{"stateNum":"2","skin":"bakeroom/tiancai_tab_quxiao.png","name":"close"}},{"type":"Button","props":{"x":245,"var":"fire_btn","stateNum":"2","skin":"bakeroom/tiancai_tab_queding.png"}}]}]};}
		]);
		return FireDialogUI;
	})(Dialog);
var FragmentUI=(function(_super){
		function FragmentUI(){
			
		    this.showBoxAni=null;
		    this.wrap_tab=null;
		    this.viewStack=null;
		    this.suipian_1=null;
		    this.suipian_2=null;
		    this.suipian_3=null;
		    this.suipian_4=null;
		    this.suipian_5=null;
		    this.suipian_6=null;
		    this.suipian_num_title=null;
		    this.key_num_ui=null;
		    this.suipian_num=null;
		    this.btn_blag=null;
		    this.btn_composite=null;
		    this.btn_giving=null;
		    this.show_item_1=null;
		    this.show_item_2=null;
		    this.show_item_3=null;
		    this.show_item_4=null;
		    this.show_item_5=null;
		    this.show_item_6=null;
		    this.box_show=null;
		    this.box_base_1=null;
		    this.box_base_2=null;
		    this.box_base_3=null;
		    this.box_base_4=null;
		    this.box_base_5=null;
		    this.box_base_6=null;
		    this.btn_use_key=null;
		    this.key_num_ui_2=null;
		    this.ani_show_item_point=null;
		    this.record_list=null;
		    this.record_list_tile=null;
		    this.tab_record=null;
		    this.sm_panel=null;
		    this.shuoming=null;
		    this.today_num=null;

			FragmentUI.__super.call(this);
		}

		CLASS$(FragmentUI,'ui.FragmentUI',_super);
		var __proto__=FragmentUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(FragmentUI.uiView);
		}

		STATICATTR$(FragmentUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"fragment/suipiange_bg.png"}},{"type":"Tab","props":{"y":52,"x":6,"var":"wrap_tab","selectedIndex":0},"child":[{"type":"Button","props":{"y":0,"x":0,"stateNum":"2","skin":"fragment/btn_suipian.png","name":"item0"}},{"type":"Button","props":{"y":94,"stateNum":"2","skin":"fragment/btn_box.png","name":"item1"}},{"type":"Button","props":{"y":188,"stateNum":"2","skin":"fragment/btn_record.png","name":"item2"}},{"type":"Button","props":{"y":282,"x":0,"stateNum":"2","skin":"fragment/btn_shuming.png","name":"item3","label":"label"}}]},{"type":"ViewStack","props":{"y":50,"x":88,"width":600,"var":"viewStack","selectedIndex":0,"height":400},"child":[{"type":"Box","props":{"width":600,"name":"item0","height":400},"child":[{"type":"Box","props":{"y":29,"x":56},"child":[{"type":"Box","props":{"y":0,"x":1,"var":"suipian_1"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"fragment/suipian_1.png","name":"base"}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"fragment/suipian_1_selected.png","name":"selected"}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"fragment/suipian_1_disable_selected.png","name":"disable_selected"}},{"type":"Poly","props":{"y":0,"x":0,"renderType":"hit","points":"69.53777537505064,160.13109879713474,61.831598864711424,154.54385727801048,59.357615894039725,143.83443708609272,75.4070796460177,127.46902654867256,64.0442477876106,116.35398230088498,0,116,0,0,160,0,159.3651877133106,45.03857299246572,143.10863545624312,48.53834760770174,132.63648657350763,40.21591860390238,110.93161182304075,39.65200592439952,98.73584905660377,55.931289844806486,111.73800330943186,69.14748667661883,129.89795918367344,70.16326530612241,140.63265306122446,60.734693877550995,151.24489795918373,59.68386599922985,159.2366189002896,64.39960842042206,159.83354943324315,117.1236716589251,91.56036781203105,116.44825973444833,79.78722010674917,128.011752646666,94.00104464425127,141.20259153165333,95.06017621385311,152.60233605826156,86.23731704537187,160.94233707107378","lineWidth":1,"lineColor":"#ff0000","fillColor":"#00ffff"}}]},{"type":"Box","props":{"y":0,"x":104,"var":"suipian_2"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"fragment/suipian_2.png","name":"base"}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"fragment/suipian_2_selected.png","name":"selected"}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"fragment/suipian_2_disable_selected.png","name":"disable_selected"}},{"type":"Poly","props":{"y":0,"x":0,"renderType":"hit","points":"127.70193872806561,73.93962883315794,116.1545946172111,90.10591058835425,131.39708884353905,105.11745793246511,128.3947793747169,116.66480204331961,58.186927180721455,116.43385516110254,57.64941593677901,65.67430352130876,47.536702563669905,58.31734476219742,38.57537384243125,58.34772501435822,25.96607861440026,67.57218058863242,12.419922210944208,67.11698475968134,1.499156639365907,60.89797069959164,0.8695826571603504,51.88486036789695,11.15523138538893,41.67047680299356,27.709006928406495,41.60969976905312,37.856812933025424,51.006928406466486,47.84988452655898,51.461893764434194,57.92378752886833,45.612009237875284,57,0,217,1,217,45,232,54,251.8678038379531,41.44136460554371,271.8528784648188,45.507462686567166,272.1471215351812,62.705756929637545,256.0660980810234,69.3454157782516,232.49253731343282,55,216.84743526386234,65.39459419112566,218.69501032159917,117.35764268997087,148.25621124538662,117.12669580775383,144.79200801213028,108.1197674012873,150.33473318534038,100.03662652368914,158.18692718072145,89.18212305948592,147.79431748095243,73.70868195094087","lineWidth":1,"lineColor":"#ff0000","fillColor":"#00ffff"}}]},{"type":"Box","props":{"y":0.625990928323894,"x":323.5744415478665,"var":"suipian_3"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"fragment/suipian_3.png","name":"base"}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"fragment/suipian_3_selected.png","name":"selected"}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"fragment/suipian_3_disable_selected.png","name":"disable_selected"}},{"type":"Poly","props":{"y":0,"x":-4,"renderType":"hit","points":"4.360870924054552,0.18394773265072217,157.90228765026473,0.7166645034534369,159.3454234134855,117.81624505050338,101.98712886556302,116.4951000677985,89.09485102492727,123.28472961738616,90.84909256859402,132.9993257960867,103.85731634758116,146.52863587124494,95.87885128835177,159.0923310563417,75.76804842031896,159.9764490375233,65.18991428793163,145.30947504244602,79.1425690483864,131.67036075635576,79.16644188028067,123.20861644369896,68.5740940091959,117.00085695013229,3.7017971958072735,117.6128740397871,2.7872207739332566,65.58491813043798,16.534679140537605,59.10385587173876,37.07848578706336,72.09479242763005,58.226522040839825,66.65672596237324,61.54978488071896,52.759444995605875,52.78845557558299,40.372738046965395,34.359452554434995,39.16427883246388,17.743138355039207,50.946756173853615,4.450086995522611,44.90446010134606","lineWidth":1,"lineColor":"#ff0000","fillColor":"#00ffff"}}]},{"type":"Box","props":{"y":118.65683052977369,"x":0.9781021897809978,"var":"suipian_4"},"child":[{"type":"Image","props":{"y":-1,"x":-1,"skin":"fragment/suipian_4.png","name":"base"}},{"type":"Image","props":{"y":-1,"x":-1,"visible":false,"skin":"fragment/suipian_4_selected.png","name":"selected"}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"fragment/suipian_4_disable_selected.png","name":"disable_selected"}},{"type":"Poly","props":{"y":-1,"x":-1,"renderType":"hit","points":"212.27676009113154,66.8466634400693,206.07238052908772,75.24082402401089,188.18916885025564,75.24082402401095,179.43004476266444,65.75177292912039,169.2110666604746,64.29191891452186,159.35705206193447,70.13133497291602,158.62712505463512,119.03644446196708,-0.13199903295605964,119.76637146926646,-0.5109489051095011,-0.4379562043795602,65.85401459854012,0.22627737226278555,70.24817518248173,11.197080291970792,57.10948905109487,26.350364963503665,63.37165060208045,39.839364169996315,75.4154462225184,44.583889717441565,89.64902286485415,43.48899920649262,101.32785498164247,27.0656415422591,82.7147162955111,9.547393367076609,91.4738403831023,-0.6715847351131572,159.35705206193447,-0.30662123146356635,158.62712505463517,52.61308679773359,169.2110666604746,59.18242986342699,179.43004476266432,58.45250285612775,190.37894987215336,47.86856125028828,205.34245352178849,49.69337876853646,212.64172359478115,55.897758330580274","lineWidth":1,"lineColor":"#ff0000","fillColor":"#00ffff"}}]},{"type":"Box","props":{"y":74,"x":161,"var":"suipian_5"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"fragment/suipian_5.png","name":"base"}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"fragment/suipian_5_selected.png","name":"selected"}},{"type":"Image","props":{"visible":false,"skin":"fragment/suipian_5_disable_selected.png","name":"disable_selected"}},{"type":"Poly","props":{"y":0,"x":0,"renderType":"hit","points":"91.29798033943337,45.073527569927336,159.55866644972758,45.32733467652628,159.35711374742834,98.54620598139346,153.72110299795094,102.68774076789686,143.06120452079358,102.94154787449582,131.10077619814354,93.12273280168844,113.35095219577227,93.50251715827812,101.864791167205,106.73198101392299,112.0967375157174,121.97708086941967,129.66362964445148,124.71378358405221,141.15505472916203,114.78414852102236,152.23104107161345,114.42648172885859,158.8135381181038,116.98136508144924,159.16977683959107,165.88103662519373,1.3268126841649064,165.17427146492523,1.3112337313303328,120.0394909865858,6.886984867806007,114.49485537979504,16.34990046882899,114.28008233343289,26.375172350256037,123.39541470041405,39.93015956253231,124.46927993222474,55.01967362902849,116.23428938072095,55.275428104731816,102.93505664414806,47.602793833632006,93.72789551882838,28.932717107289307,92.19336866460844,18.191029127749744,99.09873950859821,10.774149332353318,102.6793021684447,0.6553792743964095,97.67768028026182,0.19515259156577258,44.58900994173581,72.73477997205157,44.15709125425154,75.02673557096523,32.001318322039765,59.99957813694732,17.220845308091867,70.73110611775678,0.820468092456764,90.02692127069895,0.6332663628181763,99.46440502216643,15.52814485444378,85.70804898656519,32.82927705866527","lineWidth":1,"lineColor":"#ff0000","fillColor":"#00ffff"}}]},{"type":"Box","props":{"y":118,"x":267.87234672926604,"var":"suipian_6"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"fragment/suipian_6.png","name":"base"}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"fragment/suipian_6_selected.png","name":"selected"}},{"type":"Image","props":{"visible":false,"skin":"fragment/suipian_6_disable_selected.png","name":"disable_selected"}},{"type":"Poly","props":{"y":0,"x":0,"renderType":"hit","points":"0.3960273792314979,68.25180702878686,-1.0638266353669792,58.03282892659715,7.695297452224452,48.178814328056916,24.848582123757183,48.54377783170662,33.972669714998176,58.032828926597006,44.55661132083736,59.12771943754592,54.77558942302727,52.923339875502194,55.32846715328469,0.6569343065693545,124.978102189781,1.3211678832117002,129.00729927007308,10.467153284671525,114.77372262773724,25.255474452554722,119.37412956901261,39.054726736816065,135.4325237295966,44.52917929156061,151.49091789018053,38.3247997295168,156.9653704449251,24.09122308718102,143.09675730623877,10.222609948494949,146.01646533543618,0.7335588536043929,210.97996898507097,0.3685953499547452,210.97996898507097,119.71166104338548,55.14055292667683,120.44158805068474,55.14055292667683,71.53647856163349,44.55661132083753,66.79195301418838,32.877779204049205,67.52188002148762,23.753691612808268,76.6459676127285,8.060260955873957,75.55107710177958","lineWidth":1,"lineColor":"#ff0000","fillColor":"#00ffff"}}]}]},{"type":"Box","props":{"y":284,"x":60},"child":[{"type":"Label","props":{"width":140,"valign":"middle","text":"当前拥有钥匙数量:","strokeColor":"#4f4f4f","stroke":2,"height":20,"fontSize":18,"font":"SimHei","color":"#e2e2e2"}},{"type":"Label","props":{"x":360,"width":80,"var":"suipian_num_title","valign":"middle","text":"碎片A数量:","strokeColor":"#4f4f4f","stroke":2,"height":20,"fontSize":18,"font":"SimHei","color":"#e2e2e2"}},{"type":"Label","props":{"y":0,"x":151,"width":48,"var":"key_num_ui","valign":"middle","text":"100","height":20,"fontSize":18,"font":"SimHei","color":"#ffea00"}},{"type":"Label","props":{"y":0,"x":449,"width":48,"var":"suipian_num","valign":"middle","text":"100","height":20,"fontSize":18,"font":"SimHei","color":"#ffea00"}}]},{"type":"Box","props":{"y":322,"x":50},"child":[{"type":"Button","props":{"x":366,"var":"btn_blag","stateNum":"1","skin":"fragment/btn_ask.png"}},{"type":"Button","props":{"x":183,"var":"btn_composite","stateNum":"1","skin":"fragment/btn_composite.png"}},{"type":"Button","props":{"var":"btn_giving","stateNum":"1","skin":"fragment/btn_giving_sp.png"}}]}]},{"type":"Box","props":{"width":600,"name":"item1","height":400},"child":[{"type":"Image","props":{"y":8,"x":38,"skin":"fragment/xuanzebaoxiang_bg.png"}},{"type":"Box","props":{"y":239,"x":105,"width":130,"var":"show_item_1","height":104,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Image","props":{"y":6,"x":18,"skin":"fragment/item_effect.png"}},{"type":"Image","props":{"y":52,"x":65,"width":64,"name":"item_icon","height":64,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":84,"x":0,"width":130,"valign":"middle","name":"item_name","height":22,"fontSize":14,"font":"SimHei","color":"#f9f7c7","align":"center"}}]},{"type":"Box","props":{"y":139,"x":128,"width":130,"var":"show_item_2","height":104,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Image","props":{"y":6,"x":18,"skin":"fragment/item_effect.png"}},{"type":"Image","props":{"y":52,"x":65,"width":64,"name":"item_icon","height":64,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":84,"x":0,"width":130,"valign":"middle","name":"item_name","height":22,"fontSize":14,"font":"SimHei","color":"#f9f7c7","align":"center"}}]},{"type":"Box","props":{"y":82,"x":229,"width":130,"var":"show_item_3","height":104,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Image","props":{"y":6,"x":18,"skin":"fragment/item_effect.png"}},{"type":"Image","props":{"y":52,"x":65,"width":64,"name":"item_icon","height":64,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":84,"x":0,"width":130,"valign":"middle","name":"item_name","height":22,"fontSize":14,"font":"SimHei","color":"#f9f7c7","align":"center"}}]},{"type":"Box","props":{"y":82,"x":359,"width":130,"var":"show_item_4","height":104,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Image","props":{"y":6,"x":18,"skin":"fragment/item_effect.png"}},{"type":"Image","props":{"y":52,"x":65,"width":64,"name":"item_icon","height":64,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":84,"x":0,"width":130,"valign":"middle","name":"item_name","height":22,"fontSize":14,"font":"SimHei","color":"#f9f7c7","align":"center"}}]},{"type":"Box","props":{"y":139,"x":466,"width":130,"var":"show_item_5","height":104,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Image","props":{"y":6,"x":18,"skin":"fragment/item_effect.png"}},{"type":"Image","props":{"y":52,"x":65,"width":64,"name":"item_icon","height":64,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":84,"x":0,"width":130,"valign":"middle","name":"item_name","height":22,"fontSize":14,"font":"SimHei","color":"#f9f7c7","align":"center"}}]},{"type":"Box","props":{"y":239,"x":488,"width":130,"var":"show_item_6","height":104,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Image","props":{"y":6,"x":18,"skin":"fragment/item_effect.png"}},{"type":"Image","props":{"y":52,"x":65,"width":64,"name":"item_icon","height":64,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":84,"x":0,"width":130,"valign":"middle","name":"item_name","height":22,"fontSize":14,"font":"SimHei","color":"#f9f7c7","align":"center"}}]},{"type":"Box","props":{"y":238,"x":277,"width":210,"var":"box_show","pivotY":96,"pivotX":77,"height":167},"compId":73,"child":[{"type":"Image","props":{"y":-81,"x":-33,"skin":"fragment/box_effect.png"}},{"type":"Image","props":{"skin":"fragment/box_show.png"}}]},{"type":"Box","props":{"y":46,"x":69,"width":455,"height":247},"child":[{"type":"Box","props":{"y":4,"x":0,"visible":false,"var":"box_base_1"},"child":[{"type":"Image","props":{"skin":"fragment/box.png","name":"box_base"}},{"type":"Image","props":{"visible":false,"skin":"fragment/box_selected.png","name":"box_selected"}},{"type":"Image","props":{"y":-66,"x":-15,"visible":false,"skin":"fragment/box_open.png","name":"box_open_base"}},{"type":"Image","props":{"y":-67,"x":-16,"visible":false,"skin":"fragment/box_open_selected.png","name":"box_open_selected"}},{"type":"Image","props":{"y":-14,"x":40,"width":64,"visible":false,"skin":"icon/peifang_5_5.png","name":"item_icon","height":64}}]},{"type":"Box","props":{"y":4,"x":160,"visible":false,"var":"box_base_2"},"child":[{"type":"Image","props":{"skin":"fragment/box.png","name":"box_base"}},{"type":"Image","props":{"visible":false,"skin":"fragment/box_selected.png","name":"box_selected"}},{"type":"Image","props":{"y":-66,"x":-15,"visible":false,"skin":"fragment/box_open.png","name":"box_open_base"}},{"type":"Image","props":{"y":-67,"x":-16,"visible":false,"skin":"fragment/box_open_selected.png","name":"box_open_selected"}},{"type":"Image","props":{"y":-14,"x":40,"width":64,"visible":false,"skin":"icon/peifang_5_5.png","name":"item_icon","height":64}}]},{"type":"Box","props":{"y":4,"x":319,"visible":false,"var":"box_base_3"},"child":[{"type":"Image","props":{"skin":"fragment/box.png","name":"box_base"}},{"type":"Image","props":{"visible":false,"skin":"fragment/box_selected.png","name":"box_selected"}},{"type":"Image","props":{"y":-66,"x":-15,"visible":false,"skin":"fragment/box_open.png","name":"box_open_base"}},{"type":"Image","props":{"y":-67,"x":-16,"visible":false,"skin":"fragment/box_open_selected.png","name":"box_open_selected"}},{"type":"Image","props":{"y":-14,"x":40,"width":64,"visible":false,"skin":"icon/peifang_5_5.png","name":"item_icon","height":64}}]},{"type":"Box","props":{"y":136,"x":0,"visible":false,"var":"box_base_4"},"child":[{"type":"Image","props":{"skin":"fragment/box.png","name":"box_base"}},{"type":"Image","props":{"visible":false,"skin":"fragment/box_selected.png","name":"box_selected"}},{"type":"Image","props":{"y":-66,"x":-15,"visible":false,"skin":"fragment/box_open.png","name":"box_open_base"}},{"type":"Image","props":{"y":-67,"x":-16,"visible":false,"skin":"fragment/box_open_selected.png","name":"box_open_selected"}},{"type":"Image","props":{"y":-14,"x":40,"width":64,"visible":false,"skin":"icon/peifang_5_5.png","name":"item_icon","height":64}}]},{"type":"Box","props":{"y":136,"x":160,"visible":false,"var":"box_base_5"},"child":[{"type":"Image","props":{"skin":"fragment/box.png","name":"box_base"}},{"type":"Image","props":{"visible":false,"skin":"fragment/box_selected.png","name":"box_selected"}},{"type":"Image","props":{"y":-66,"x":-15,"visible":false,"skin":"fragment/box_open.png","name":"box_open_base"}},{"type":"Image","props":{"y":-67,"x":-16,"visible":false,"skin":"fragment/box_open_selected.png","name":"box_open_selected"}},{"type":"Image","props":{"y":-14,"x":40,"width":64,"visible":false,"skin":"icon/peifang_5_5.png","name":"item_icon","height":64}}]},{"type":"Box","props":{"y":136,"x":319,"visible":false,"var":"box_base_6"},"child":[{"type":"Image","props":{"skin":"fragment/box.png","name":"box_base"}},{"type":"Image","props":{"visible":false,"skin":"fragment/box_selected.png","name":"box_selected"}},{"type":"Image","props":{"y":-66,"x":-15,"visible":false,"skin":"fragment/box_open.png","name":"box_open_base"}},{"type":"Image","props":{"y":-67,"x":-16,"visible":false,"skin":"fragment/box_open_selected.png","name":"box_open_selected"}},{"type":"Image","props":{"y":-14,"x":40,"width":64,"visible":false,"skin":"icon/peifang_5_5.png","name":"item_icon","height":64}}]}]},{"type":"Button","props":{"y":322,"x":236,"var":"btn_use_key","stateNum":"1","skin":"fragment/btn_use_key.png"}},{"type":"Box","props":{"y":293,"x":60},"child":[{"type":"Label","props":{"width":140,"valign":"middle","text":"当前拥有钥匙数量:","strokeColor":"#4f4f4f","stroke":2,"height":20,"fontSize":18,"font":"SimHei","color":"#e2e2e2"}},{"type":"Label","props":{"x":151,"width":48,"var":"key_num_ui_2","valign":"middle","text":"100","height":20,"fontSize":18,"font":"SimHei","color":"#ffea00"}}]},{"type":"Sprite","props":{"y":227,"x":284,"visible":false,"var":"ani_show_item_point"}}]},{"type":"Box","props":{"width":600,"name":"item2","height":400},"child":[{"type":"List","props":{"y":43,"x":47,"width":500,"var":"record_list","vScrollBarSkin":"ui/vscroll.png","height":251},"child":[{"type":"Box","props":{"y":0,"x":0,"width":500,"name":"render","height":24},"child":[{"type":"Label","props":{"y":0,"x":7,"width":70,"valign":"middle","name":"sp_type","height":24,"fontSize":16,"font":"SimHei","color":"#ffffff"}},{"type":"Label","props":{"y":0,"x":90,"width":243,"valign":"middle","overflow":"hidden","name":"sp_getway","height":24,"fontSize":16,"font":"SimHei","color":"#ffffff","align":"center"}},{"type":"Label","props":{"y":0,"x":354,"width":145,"valign":"middle","name":"sp_addtime","height":24,"fontSize":16,"font":"SimHei","color":"#ffffff"}}]}]},{"type":"Box","props":{"y":19,"x":56},"child":[{"type":"Label","props":{"text":"种类","fontSize":16,"font":"SimHei","color":"#66a0cd"}},{"type":"Label","props":{"x":173,"var":"record_list_tile","text":"获得途径","fontSize":16,"font":"SimHei","color":"#66a0cd"}},{"type":"Label","props":{"x":403,"text":"时间","fontSize":16,"font":"SimHei","color":"#66a0cd"}}]},{"type":"Tab","props":{"y":324,"x":55,"var":"tab_record","selectedIndex":0},"child":[{"type":"Button","props":{"x":355,"stateNum":"2","skin":"fragment/btn_giving_record.png","name":"item1"}},{"type":"Button","props":{"stateNum":"2","skin":"fragment/btn_get_record.png","name":"item0"}}]},{"type":"Label","props":{"y":297,"x":244,"text":"显示最近七天记录","fontSize":14,"font":"SimHei","color":"#ffffff"}}]},{"type":"Box","props":{"width":600,"name":"item3","height":400},"child":[{"type":"Image","props":{"y":4,"x":228,"skin":"fragment/shuoming.png"}},{"type":"Panel","props":{"y":48,"x":42,"width":510,"var":"sm_panel","vScrollBarSkin":"ui/vscroll.png","height":266},"child":[{"type":"HTMLDivElement","props":{"y":0,"x":0,"width":509,"var":"shuoming","height":280}}]}]}]},{"type":"Button","props":{"y":7,"x":711,"stateNum":"2","skin":"ui/button_guanbi.png","name":"close"}},{"type":"Label","props":{"y":437,"x":419,"width":80,"var":"today_num","valign":"middle","text":"0","height":30,"fontSize":26,"font":"SimHei","color":"#a42a24"}}],"animations":[{"nodes":[{"target":73,"keyframes":{"rotation":[{"value":0,"tweenMethod":"linearNone","tween":true,"target":73,"key":"rotation","index":0},{"value":-10,"tweenMethod":"linearNone","tween":true,"target":73,"key":"rotation","index":3},{"value":0,"tweenMethod":"linearNone","tween":true,"target":73,"key":"rotation","index":6},{"value":10,"tweenMethod":"linearNone","tween":true,"target":73,"key":"rotation","index":9},{"value":0,"tweenMethod":"linearNone","tween":true,"target":73,"key":"rotation","index":12}]}}],"name":"showBoxAni","id":1,"frameRate":24,"action":0}]};}
		]);
		return FragmentUI;
	})(Dialog);
var FragmentConfirmUI=(function(_super){
		function FragmentConfirmUI(){
			
		    this.giving_tips=null;
		    this.ask_tips=null;

			FragmentConfirmUI.__super.call(this);
		}

		CLASS$(FragmentConfirmUI,'ui.FragmentConfirmUI',_super);
		var __proto__=FragmentConfirmUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(FragmentConfirmUI.uiView);
		}

		STATICATTR$(FragmentConfirmUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"fragment/tips_bg.png"}},{"type":"Image","props":{"y":93,"x":83,"visible":false,"var":"giving_tips","skin":"fragment/gving_tips.png"}},{"type":"Image","props":{"y":93,"x":83,"visible":false,"var":"ask_tips","skin":"fragment/ask_tips.png"}},{"type":"Box","props":{"y":178,"x":65},"child":[{"type":"Button","props":{"stateNum":"1","skin":"ui/btn_no.png","name":"no"}},{"type":"Button","props":{"x":168,"stateNum":"1","skin":"ui/btn_yes.png","name":"yes"}}]}]};}
		]);
		return FragmentConfirmUI;
	})(Dialog);
var FragmentGetKeyTipsUI=(function(_super){
		function FragmentGetKeyTipsUI(){
			

			FragmentGetKeyTipsUI.__super.call(this);
		}

		CLASS$(FragmentGetKeyTipsUI,'ui.FragmentGetKeyTipsUI',_super);
		var __proto__=FragmentGetKeyTipsUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(FragmentGetKeyTipsUI.uiView);
		}

		STATICATTR$(FragmentGetKeyTipsUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":10,"x":10,"skin":"fragment/get_key_tips.png"}},{"type":"Button","props":{"y":303,"x":99,"stateNum":"1","skin":"fragment/btn_get_item.png","name":"close"}}]};}
		]);
		return FragmentGetKeyTipsUI;
	})(Dialog);
var FragmentGetTipsUI=(function(_super){
		function FragmentGetTipsUI(){
			
		    this.item_list=null;

			FragmentGetTipsUI.__super.call(this);
		}

		CLASS$(FragmentGetTipsUI,'ui.FragmentGetTipsUI',_super);
		var __proto__=FragmentGetTipsUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(FragmentGetTipsUI.uiView);
		}

		STATICATTR$(FragmentGetTipsUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"ui/get_item_bg.png"}},{"type":"List","props":{"y":56,"x":52,"width":285,"var":"item_list","repeatY":1,"height":110,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Box","props":{"y":0,"x":98,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":88,"skin":"bakeroom/kuang.png","height":88}},{"type":"Image","props":{"y":5,"x":5,"width":79,"name":"icon","height":79}},{"type":"Label","props":{"y":87,"x":4,"width":80,"valign":"middle","name":"item_name","height":22,"fontSize":16,"font":"SimHei","align":"center"}}]}]},{"type":"Box","props":{"y":172,"x":152},"child":[{"type":"Button","props":{"y":0,"x":0,"stateNum":"1","skin":"ui/btn_get_item.png","name":"close"}}]}]};}
		]);
		return FragmentGetTipsUI;
	})(Dialog);
var FragmentGivingUI=(function(_super){
		function FragmentGivingUI(){
			
		    this.giving_list=null;

			FragmentGivingUI.__super.call(this);
		}

		CLASS$(FragmentGivingUI,'ui.FragmentGivingUI',_super);
		var __proto__=FragmentGivingUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(FragmentGivingUI.uiView);
		}

		STATICATTR$(FragmentGivingUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"fragment/ask_bg.png"}},{"type":"Button","props":{"y":-9,"x":518,"stateNum":"2","skin":"ui/button_guanbi.png","name":"close"}},{"type":"List","props":{"y":61,"x":27,"width":505,"visible":false,"var":"giving_list","vScrollBarSkin":"ui/vscroll.png","spaceY":5,"height":395},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"fragment/list_item_bg.png","sizeGrid":"7,10,10,7"}},{"type":"Image","props":{"y":4,"x":8,"width":70,"skin":"friend/haoyou_2.png","height":70}},{"type":"Image","props":{"y":7,"x":11,"width":64,"name":"header","height":64}},{"type":"Image","props":{"y":4,"x":8,"width":70,"skin":"friend/touxiang_2_2.png","height":70}},{"type":"Label","props":{"y":8,"x":83,"wordWrap":true,"width":128,"overflow":"hidden","name":"nickname","height":36,"fontSize":16,"font":"SimHei"}},{"type":"Label","props":{"y":46,"x":83,"width":104,"valign":"middle","name":"sp_name","height":30,"fontSize":16,"font":"SimHei"}},{"type":"Image","props":{"y":4,"x":219,"width":70,"skin":"bakeroom/kuang.png","height":70}},{"type":"Image","props":{"y":9,"x":225,"width":60,"name":"icon","height":60}},{"type":"Label","props":{"y":8,"x":294,"width":89,"text":"持有：0","name":"sp_num","height":20,"fontSize":14,"font":"SimHei"}},{"type":"ProgressBar","props":{"y":54,"x":291,"value":0,"skin":"fragment/progress.png","name":"progress"}},{"type":"Label","props":{"y":37,"x":314,"width":44,"valign":"middle","text":"0/1","name":"giving_num","height":20,"fontSize":14,"font":"SimHei","align":"center"}},{"type":"Button","props":{"y":25,"x":420,"visible":false,"stateNum":"1","skin":"fragment/btn_giving.png","name":"btn_giving"}},{"type":"Label","props":{"y":24,"x":420,"width":70,"visible":false,"valign":"middle","text":"碎片不足","name":"enable_tips","height":30,"fontSize":16,"font":"SimHei","align":"center"}}]}]}]};}
		]);
		return FragmentGivingUI;
	})(Dialog);
var FragmentNewerUI=(function(_super){
		function FragmentNewerUI(){
			
		    this.item_list=null;

			FragmentNewerUI.__super.call(this);
		}

		CLASS$(FragmentNewerUI,'ui.FragmentNewerUI',_super);
		var __proto__=FragmentNewerUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(FragmentNewerUI.uiView);
		}

		STATICATTR$(FragmentNewerUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"fragment/newer_bg.png"}},{"type":"List","props":{"y":68,"x":36,"width":490,"var":"item_list","spaceX":2,"repeatY":1,"height":115,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":1,"width":78,"skin":"bakeroom/kuang.png","height":78}},{"type":"Image","props":{"y":7,"x":9,"width":64,"name":"item_icon","height":64}},{"type":"Label","props":{"y":82,"x":0,"wordWrap":true,"width":80,"valign":"middle","text":"五星津巴布韦烟叶·醇","name":"item_name","height":30,"fontSize":14,"font":"SimHei","align":"center"}}]}]},{"type":"Button","props":{"y":190,"x":237,"stateNum":"1","skin":"ui/btn_get_item.png","name":"close"}}]};}
		]);
		return FragmentNewerUI;
	})(Dialog);
var FriendUI=(function(_super){
		function FriendUI(){
			
		    this.tab_friend=null;
		    this.view_stack=null;
		    this.add_friend_btn=null;
		    this.list_friend=null;
		    this.latest_friend=null;
		    this.tuijian_btn=null;
		    this.has_apply=null;

			FriendUI.__super.call(this);
		}

		CLASS$(FriendUI,'ui.FriendUI',_super);
		var __proto__=FriendUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(FriendUI.uiView);
		}

		STATICATTR$(FriendUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":-10,"skin":"friend/diban_3.png"},"child":[{"type":"Tab","props":{"y":21,"x":29,"var":"tab_friend","stateNum":2,"space":5,"skin":"friend/tab.png","selectedIndex":0,"labels":"我的好友,最近访问","labelSize":20,"labelColors":"#3e1600,#3e1600","labelBold":true}},{"type":"ViewStack","props":{"y":78,"x":33,"width":840,"var":"view_stack","selectedIndex":0,"height":197},"child":[{"type":"Box","props":{"y":0,"x":3,"width":836,"name":"item0","height":199},"child":[{"type":"Image","props":{"y":14,"x":9,"skin":"friend/diban_1.png","sizeGrid":"10,10,10,10"},"child":[{"type":"Image","props":{"y":32,"x":29,"skin":"friend/bg_add_2.png"},"child":[{"type":"Label","props":{"y":85,"x":16,"text":"邀请好友","fontSize":18,"font":"SimHei","color":"#601410"}},{"type":"Button","props":{"y":10,"x":15,"var":"add_friend_btn","stateNum":"2","skin":"friend/button_add.png"}}]}]},{"type":"Image","props":{"y":13,"x":188,"width":636,"skin":"friend/diban_1.png","sizeGrid":"10,10,10,10","height":168},"child":[{"type":"List","props":{"y":-4,"x":19,"width":611,"visible":false,"var":"list_friend","spaceX":10,"repeatY":1,"repeatX":4,"height":163,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Box","props":{"y":13,"x":4,"width":137,"name":"render","height":155},"child":[{"type":"Image","props":{"y":6,"skin":"friend/haoyou_2.png","name":"bg"}},{"type":"Label","props":{"y":114,"x":18,"width":100,"valign":"middle","overflow":"hidden","name":"nickname","height":26,"fontSize":20,"font":"SimHei","color":"#601410","align":"center"}},{"type":"Image","props":{"y":70,"x":67,"width":78,"name":"thumb","height":75,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":70,"x":67,"width":85,"skin":"friend/touxiang_2_2.png","height":85,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":-7,"x":-4,"width":45.2,"skin":"ui/xing1.png","height":40}},{"type":"Label","props":{"y":6,"x":2,"width":34,"valign":"middle","text":"1","strokeColor":"#000000","stroke":2,"name":"level","height":20,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"center"}}]}]}]}]},{"type":"Box","props":{"y":27,"x":182,"width":645,"name":"item1","height":150},"child":[{"type":"Image","props":{"y":-14,"x":-170,"width":813,"skin":"friend/diban_1.png","sizeGrid":"10,10,10,10","height":170},"child":[{"type":"List","props":{"y":3,"x":12,"width":797,"visible":false,"var":"latest_friend","spaceX":3,"repeatY":1,"repeatX":6,"height":158,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Box","props":{"y":14,"x":5,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":136,"skin":"friend/haoyou_2.png"}},{"type":"Label","props":{"y":113,"x":18,"width":100,"valign":"middle","overflow":"hidden","name":"nickname","height":26,"fontSize":20,"font":"SimHei","color":"#601410","align":"center"}},{"type":"Image","props":{"y":30,"x":23,"width":80,"name":"thumb","height":80}},{"type":"Image","props":{"y":26,"x":22,"width":85,"skin":"friend/touxiang_2_2.png","height":85}},{"type":"Image","props":{"y":-14,"x":-4,"width":45.2,"skin":"ui/xing1.png","height":40}},{"type":"Label","props":{"y":-1,"x":2,"width":34,"valign":"middle","text":"1","strokeColor":"#000000","stroke":2,"name":"level","height":20,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"center"}}]}]}]}]}]},{"type":"Button","props":{"y":21,"x":334,"var":"tuijian_btn","stateNum":"2","skin":"friend/tab.png","labelSize":20,"labelColors":"#3e1600,#3e1600","labelBold":true,"label":"结识好友"},"child":[{"type":"Image","props":{"y":2,"x":112,"visible":false,"var":"has_apply","skin":"friend/tishidian.png"}}]}]},{"type":"Button","props":{"y":7,"x":829,"stateNum":"2","skin":"friend/button_xiangxia.png","name":"close"}}]};}
		]);
		return FriendUI;
	})(Dialog);
var FriendInfoUI=(function(_super){
		function FriendInfoUI(){
			
		    this.delete_btn=null;
		    this.visit_btn=null;
		    this.nickname=null;
		    this.thumb=null;
		    this.achievement=null;
		    this.PlantAchievement=null;
		    this.PlantIcon=null;
		    this.JiaoyiAchievement=null;
		    this.JiaoyiIcon=null;
		    this.PinjianAchievement=null;
		    this.PinjianIcon=null;
		    this.ZhiyanAchievement=null;
		    this.ZhiyanIcon=null;

			FriendInfoUI.__super.call(this);
		}

		CLASS$(FriendInfoUI,'ui.FriendInfoUI',_super);
		var __proto__=FriendInfoUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(FriendInfoUI.uiView);
		}

		STATICATTR$(FriendInfoUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"friend/datiban_haoyou.png","name":"bg"}},{"type":"Button","props":{"y":6,"x":749,"stateNum":"2","skin":"ui/button_guanbi.png","name":"close"}},{"type":"Button","props":{"y":337,"x":171,"var":"delete_btn","stateNum":"2","skin":"friend/tab.png","labelSize":20,"labelPadding":"0,0,2,0","labelFont":"SimHei","labelColors":"#672416,#672416","label":"删除好友"}},{"type":"Button","props":{"y":337,"x":497,"var":"visit_btn","stateNum":"2","skin":"friend/tab.png","labelSize":20,"labelPadding":"0,0,2,0","labelFont":"SimHei","labelColors":"#672416,#672416","label":"访问Ta家"}},{"type":"Label","props":{"y":85,"x":255,"width":434,"var":"nickname","valign":"middle","strokeColor":"#000000","stroke":3,"overflow":"hidden","height":36,"fontSize":26,"font":"SimHei","color":"#ffffff"}},{"type":"Box","props":{"y":73,"x":93},"child":[{"type":"Image","props":{"y":4,"x":8,"width":90,"var":"thumb","height":90}},{"type":"Image","props":{"skin":"ui/header_bg.png"}},{"type":"Image","props":{"y":-32,"x":-34,"skin":"ui/xing1.png"},"child":[{"type":"Label","props":{"y":22,"x":26,"width":30,"var":"achievement","valign":"middle","text":"0","height":36,"fontSize":22,"font":"SimHei","color":"#ffffff","align":"center"}}]}]},{"type":"Box","props":{"y":170,"x":250,"width":188,"height":40},"child":[{"type":"Label","props":{"y":7,"x":44,"width":141,"var":"PlantAchievement","valign":"middle","text":"0","height":28,"fontSize":20,"color":"#ffffff","align":"center"}},{"type":"Image","props":{"width":40,"var":"PlantIcon","skin":"userinfo/Plant1.png","height":40}}]},{"type":"Box","props":{"y":170,"x":508,"width":184,"height":40},"child":[{"type":"Label","props":{"y":8,"x":44,"width":141,"var":"JiaoyiAchievement","valign":"middle","text":"0","height":28,"fontSize":20,"color":"#ffffff","align":"center"}},{"type":"Image","props":{"width":40,"var":"JiaoyiIcon","skin":"userinfo/Jiaoyi1.png","height":40}}]},{"type":"Box","props":{"y":238,"x":250,"width":188,"height":40},"child":[{"type":"Label","props":{"y":7,"x":48,"width":141,"var":"PinjianAchievement","valign":"middle","text":"0","height":28,"fontSize":20,"color":"#ffffff","align":"center"}},{"type":"Image","props":{"width":40,"var":"PinjianIcon","skin":"userinfo/Pinjian1.png","height":40}}]},{"type":"Box","props":{"y":238,"x":508,"width":183,"height":40},"child":[{"type":"Label","props":{"y":7,"x":44,"width":140,"var":"ZhiyanAchievement","valign":"middle","text":"0","height":28,"fontSize":20,"color":"#ffffff","align":"center"}},{"type":"Image","props":{"width":40,"var":"ZhiyanIcon","skin":"userinfo/Zhiyan1.png","height":40}}]}]};}
		]);
		return FriendInfoUI;
	})(Dialog);
var gongluenewUI=(function(_super){
		function gongluenewUI(){
			
		    this.tujian_btn=null;
		    this.shengchan_btn=null;
		    this.other_btn=null;
		    this.viewstack_1=null;
		    this.jingdianshu_btn=null;
		    this.yuanshengshu_btn=null;
		    this.gailiangshu_btn=null;
		    this.kaxiangshu_btn=null;
		    this.lvsongshu_btn=null;
		    this.jiuxiangshu_btn=null;
		    this.jichushu_btn=null;
		    this.tujian_viewstack=null;
		    this.jingdianshu_faguang_01=null;
		    this.jingdianshu_5=null;
		    this.jingdianshu_faguang_02=null;
		    this.jingdianshu_4=null;
		    this.jingdianshu_faguang_03=null;
		    this.jingdianshu_3=null;
		    this.jingdianshu_faguang_04=null;
		    this.jingdianshu_2=null;
		    this.jingdianshu_faguang_05=null;
		    this.jingdianshu_1=null;
		    this.jingdianshu_content=null;
		    this.yuanshengshu_faguang_01=null;
		    this.yuanshengshu_5=null;
		    this.yuanshengshu_faguang_02=null;
		    this.yuanshengshu_4=null;
		    this.yuanshengshu_faguang_03=null;
		    this.yuanshengshu_3=null;
		    this.yuanshengshu_faguang_04=null;
		    this.yuanshengshu_2=null;
		    this.yuanshengshu_faguang_05=null;
		    this.yuanshengshu_1=null;
		    this.yuanshengshu_content=null;
		    this.gailiangshu_faguang_01=null;
		    this.gailiangshu_5=null;
		    this.gailiangshu_faguang_02=null;
		    this.gailiangshu_4=null;
		    this.gailiangshu_faguang_03=null;
		    this.gailiangshu_3=null;
		    this.gailiangshu_faguang_04=null;
		    this.gailiangshu_2=null;
		    this.gailiangshu_faguang_05=null;
		    this.gailiangshu_1=null;
		    this.gailiangshu_content=null;
		    this.kaxiangshu_faguang_01=null;
		    this.kaxiangshu_5=null;
		    this.kaxiangshu_faguang_02=null;
		    this.kaxiangshu_4=null;
		    this.kaxiangshu_faguang_03=null;
		    this.kaxiangshu_3=null;
		    this.kaxiangshu_faguang_04=null;
		    this.kaxiangshu_2=null;
		    this.kaxiangshu_faguang_05=null;
		    this.kaxiangshu_1=null;
		    this.kaxiangshu_content=null;
		    this.lvsongshu_faguang_01=null;
		    this.lvsongshu_5=null;
		    this.lvsongshu_faguang_02=null;
		    this.lvsongshu_4=null;
		    this.lvsongshu_faguang_03=null;
		    this.lvsongshu_3=null;
		    this.lvsongshu_faguang_04=null;
		    this.lvsongshu_2=null;
		    this.lvsongshu_faguang_05=null;
		    this.lvsongshu_1=null;
		    this.lvsongshu_content=null;
		    this.jiuxiangshu_faguang_01=null;
		    this.jiuxiangshu_5=null;
		    this.jiuxiangshu_faguang_02=null;
		    this.jiuxiangshu_4=null;
		    this.jiuxiangshu_faguang_03=null;
		    this.jiuxiangshu_3=null;
		    this.jiuxiangshu_faguang_04=null;
		    this.jiuxiangshu_2=null;
		    this.jiuxiangshu_faguang_05=null;
		    this.jiuxiangshu_1=null;
		    this.jiuxiangshu_content=null;
		    this.jichushu_faguang_01=null;
		    this.jichushu_5=null;
		    this.jichushu_faguang_02=null;
		    this.jichushu_4=null;
		    this.jichushu_faguang_03=null;
		    this.jichushu_3=null;
		    this.jichushu_faguang_04=null;
		    this.jichushu_2=null;
		    this.jichushu_faguang_05=null;
		    this.jichushu_1=null;
		    this.jichushu_content=null;
		    this.jichu_btn=null;
		    this.jinjie_btn=null;
		    this.shengchan_viewstack=null;
		    this.faguang_07=null;
		    this.choujiang_btn=null;
		    this.faguang_06=null;
		    this.pinjian_btn=null;
		    this.faguang_05=null;
		    this.zhiyan_btn=null;
		    this.faguang_04=null;
		    this.chunhua_btn=null;
		    this.faguang_03=null;
		    this.hongkao_btn=null;
		    this.faguang_02=null;
		    this.zhongzhi_btn=null;
		    this.faguang_01=null;
		    this.shanghang_btn=null;
		    this.jichu_detail_viewstack=null;
		    this.goto_btn_1=null;
		    this.goto_btn_2=null;
		    this.goto_btn_3=null;
		    this.goto_btn_4=null;
		    this.goto_btn_5=null;
		    this.goto_btn_6=null;
		    this.goto_btn_7=null;
		    this.jinjie_panel=null;
		    this.jinjie_faguang_01=null;
		    this.get_tiaoxiangshu=null;
		    this.jinjie_faguang_02=null;
		    this.chakanpeifang=null;
		    this.jinjie_faguang_03=null;
		    this.get_zhongzi=null;
		    this.jinjie_faguang_04=null;
		    this.zhongzhi=null;
		    this.jinjie_faguang_05=null;
		    this.hongkao=null;
		    this.jinjie_faguang_06=null;
		    this.chunhua=null;
		    this.jinjie_faguang_07=null;
		    this.zhiyan=null;
		    this.jinjie_faguang_08=null;
		    this.pinjian=null;
		    this.jinjie_faguang_09=null;
		    this.choujiang=null;
		    this.jinjie_detail_viewstack=null;
		    this.yanjiusuo=null;
		    this.youleyuan=null;
		    this.qiandao=null;
		    this.dati=null;
		    this.yanjiusuo_text=null;
		    this.youleyuan_text=null;
		    this.qiandao_text=null;
		    this.dati_text=null;
		    this.go_back_btn=null;
		    this.shu_goto_btn_1=null;
		    this.shu_goto_btn_2=null;
		    this.shu_goto_btn_3=null;
		    this.shu_goto_btn_4=null;
		    this.peiyuzhongxin=null;
		    this.shenmi=null;
		    this.youleyuan_2=null;
		    this.qiandao_2=null;
		    this.dati_2=null;
		    this.peiyuzhongxin_text=null;
		    this.shenmi_text=null;
		    this.youleyuan_text_2=null;
		    this.qiandao_text_2=null;
		    this.dati_text_2=null;
		    this.go_back_btn_2=null;
		    this.zz_goto_btn_1=null;
		    this.zz_goto_btn_2=null;
		    this.zz_goto_btn_3=null;
		    this.zz_goto_btn_4=null;
		    this.zz_goto_btn_5=null;
		    this.zhongzhi_goto=null;
		    this.hongkao_goto=null;
		    this.chunhua_goto=null;
		    this.zhiyan_goto=null;
		    this.pinjian_goto=null;
		    this.choujiang_goto=null;
		    this.other_panel=null;
		    this.other_shanghang_btn=null;
		    this.other_zhongzhi_btn=null;
		    this.other_dingdan_btn=null;
		    this.other_xiaotan_btn=null;
		    this.other_chengjiu_btn=null;
		    this.other_haoyou_btn=null;
		    this.other_jiandie_btn=null;
		    this.other_tufa_btn=null;
		    this.other_viewstack=null;
		    this.other_shanghang=null;
		    this.shanghang_pre_btn=null;
		    this.shanghang_next_btn=null;
		    this.other_zhongzhi=null;
		    this.zhongzhi_pre_btn=null;
		    this.zhongzhi_next_btn=null;
		    this.other_dingdan=null;
		    this.dingdan_pre_btn=null;
		    this.dingdan_next_btn=null;
		    this.other_xiaotan=null;
		    this.xiaotan_pre_btn=null;
		    this.xiaotan_next_btn=null;
		    this.other_jiandie=null;
		    this.jiandie_pre_btn=null;
		    this.jiandie_next_btn=null;
		    this.other_chengjiu=null;
		    this.chengjiu_pre_btn=null;
		    this.chengjiu_next_btn=null;
		    this.other_haoyou=null;
		    this.haoyou_pre_btn=null;
		    this.haoyou_next_btn=null;
		    this.other_tufa=null;
		    this.tufa_pre_btn=null;
		    this.tufa_next_btn=null;

			gongluenewUI.__super.call(this);
		}

		CLASS$(gongluenewUI,'ui.gongluenewUI',_super);
		var __proto__=gongluenewUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(gongluenewUI.uiView);
		}

		STATICATTR$(gongluenewUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"gongluenew/bg.png"}},{"type":"Box","props":{"y":0,"x":0},"child":[{"type":"Button","props":{"y":17,"x":11,"var":"tujian_btn","stateNum":"2","skin":"gongluenew/tujian.png"}},{"type":"Button","props":{"y":15,"x":292,"var":"shengchan_btn","stateNum":"2","skin":"gongluenew/shengchan.png"}},{"type":"Button","props":{"y":15,"x":573,"var":"other_btn","stateNum":"2","skin":"gongluenew/other.png"}},{"type":"Button","props":{"y":-20,"x":827,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}}]},{"type":"ViewStack","props":{"y":101,"x":26,"width":815,"var":"viewstack_1","selectedIndex":1,"height":464},"child":[{"type":"Box","props":{"name":"item0"},"child":[{"type":"Button","props":{"y":11,"x":9,"var":"jingdianshu_btn","stateNum":"2","skin":"gongluenew/jingdianshu.png"}},{"type":"Button","props":{"y":11,"x":123,"var":"yuanshengshu_btn","stateNum":"2","skin":"gongluenew/yuanshengshu.png"}},{"type":"Button","props":{"y":11,"x":237,"var":"gailiangshu_btn","stateNum":"2","skin":"gongluenew/gailiangshu.png"}},{"type":"Button","props":{"y":11,"x":352,"var":"kaxiangshu_btn","stateNum":"2","skin":"gongluenew/kaxiangshu.png","label":"l"}},{"type":"Button","props":{"y":11,"x":466,"var":"lvsongshu_btn","stateNum":"2","skin":"gongluenew/lvsongshu.png"}},{"type":"Button","props":{"y":11,"x":580,"var":"jiuxiangshu_btn","stateNum":"2","skin":"gongluenew/jiuxiangshu.png"}},{"type":"Button","props":{"y":11,"x":694,"var":"jichushu_btn","stateNum":"2","skin":"gongluenew/jichushu.png"}},{"type":"ViewStack","props":{"y":418,"x":804,"width":793,"var":"tujian_viewstack","selectedIndex":0,"pivotY":348.68421052631584,"pivotX":793.421052631579,"height":354},"child":[{"type":"Box","props":{"y":353,"x":793,"width":793,"pivotY":352.63157894736844,"pivotX":792.1052631578948,"name":"item0","height":353},"child":[{"type":"Image","props":{"y":64,"x":76,"visible":true,"var":"jingdianshu_faguang_01","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":43,"var":"jingdianshu_5","skin":"icon/peifang_6_5.png"}},{"type":"Image","props":{"y":64,"x":237,"visible":false,"var":"jingdianshu_faguang_02","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":206,"var":"jingdianshu_4","skin":"icon/peifang_6_4.png"}},{"type":"Image","props":{"y":64,"x":397,"visible":false,"var":"jingdianshu_faguang_03","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":368,"visible":true,"var":"jingdianshu_3","skin":"icon/peifang_6_3.png"}},{"type":"Image","props":{"y":64,"x":557,"visible":false,"var":"jingdianshu_faguang_04","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":531,"var":"jingdianshu_2","skin":"icon/peifang_6_2.png"}},{"type":"Image","props":{"y":64,"x":716,"visible":false,"var":"jingdianshu_faguang_05","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":693,"var":"jingdianshu_1","skin":"icon/peifang_6_1.png"}},{"type":"Image","props":{"y":142,"x":150,"skin":"gongluenew/xiaohao.png"}},{"type":"Label","props":{"y":199,"x":4,"wordWrap":true,"width":790,"var":"jingdianshu_content","text":"五星云贵烟叶·醇14份\\n四星巴西烟叶·醇8份\\n三星吕宋烟叶·醇5份\\n经典嘴棒10份","pivotY":1.3698630136985912,"pivotX":1.3698630136985912,"padding":"10","leading":5,"height":157,"fontSize":24,"font":"SimSun","color":"#0ef4eb"}}]},{"type":"Box","props":{"y":353,"x":793,"width":793,"pivotY":352.63157894736844,"pivotX":792.1052631578948,"name":"item1","height":353},"child":[{"type":"Image","props":{"y":64,"x":76,"var":"yuanshengshu_faguang_01","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":43,"var":"yuanshengshu_5","skin":"icon/peifang_1_5.png"}},{"type":"Image","props":{"y":64,"x":237,"visible":false,"var":"yuanshengshu_faguang_02","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":206,"var":"yuanshengshu_4","skin":"icon/peifang_1_4.png"}},{"type":"Image","props":{"y":64,"x":397,"visible":false,"var":"yuanshengshu_faguang_03","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":368,"var":"yuanshengshu_3","skin":"icon/peifang_1_3.png"}},{"type":"Image","props":{"y":64,"x":557,"visible":false,"var":"yuanshengshu_faguang_04","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":531,"var":"yuanshengshu_2","skin":"icon/peifang_1_2.png"}},{"type":"Image","props":{"y":64,"x":716,"visible":false,"var":"yuanshengshu_faguang_05","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":693,"var":"yuanshengshu_1","skin":"icon/peifang_1_1.png"}},{"type":"Image","props":{"y":142,"x":150,"skin":"gongluenew/xiaohao.png"}},{"type":"Label","props":{"y":199,"x":4,"wordWrap":true,"width":790,"var":"yuanshengshu_content","text":"五星巴马烟叶·醇14份\\n四星津巴布韦烟叶·醇8份\\n三星云贵烟叶·醇5份\\n玉米颗粒嘴棒10份","padding":"10","leading":5,"height":157,"fontSize":24,"font":"SimSun","color":"#0ef4eb"}}]},{"type":"Box","props":{"y":353,"x":793,"width":793,"pivotY":352.63157894736844,"pivotX":792.1052631578948,"name":"item2","height":353},"child":[{"type":"Image","props":{"y":64,"x":76,"var":"gailiangshu_faguang_01","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":43,"var":"gailiangshu_5","skin":"icon/peifang_2_5.png"}},{"type":"Image","props":{"y":64,"x":237,"visible":false,"var":"gailiangshu_faguang_02","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":29,"x":206,"var":"gailiangshu_4","skin":"icon/peifang_2_4.png"}},{"type":"Image","props":{"y":64,"x":397,"visible":false,"var":"gailiangshu_faguang_03","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":29,"x":368,"var":"gailiangshu_3","skin":"icon/peifang_2_3.png"}},{"type":"Image","props":{"y":64,"x":557,"visible":false,"var":"gailiangshu_faguang_04","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":29,"x":531,"var":"gailiangshu_2","skin":"icon/peifang_2_2.png"}},{"type":"Image","props":{"y":64,"x":716,"visible":false,"var":"gailiangshu_faguang_05","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":693,"var":"gailiangshu_1","skin":"icon/peifang_2_1.png"}},{"type":"Image","props":{"y":143,"x":150,"skin":"gongluenew/xiaohao.png"}},{"type":"Label","props":{"y":199,"x":4,"wordWrap":true,"width":790,"var":"gailiangshu_content","text":"五星津巴布韦烟叶·醇14份\\n四星云贵烟叶·醇8份\\n三星巴西烟叶·醇5份\\n活性炭嘴棒10份","padding":"10","leading":5,"height":157,"fontSize":24,"font":"SimSun","color":"#0ef4eb"}}]},{"type":"Box","props":{"y":353,"x":793,"width":793,"pivotY":352.63157894736844,"pivotX":792.1052631578948,"name":"item3","height":353},"child":[{"type":"Image","props":{"y":64,"x":76,"var":"kaxiangshu_faguang_01","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":43,"var":"kaxiangshu_5","skin":"icon/peifang_3_5.png"}},{"type":"Image","props":{"y":64,"x":237,"visible":false,"var":"kaxiangshu_faguang_02","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":34,"x":206,"var":"kaxiangshu_4","skin":"icon/peifang_3_4.png"}},{"type":"Image","props":{"y":64,"x":397,"visible":false,"var":"kaxiangshu_faguang_03","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":34,"x":368,"var":"kaxiangshu_3","skin":"icon/peifang_3_3.png"}},{"type":"Image","props":{"y":64,"x":557,"visible":false,"var":"kaxiangshu_faguang_04","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":34,"x":531,"var":"kaxiangshu_2","skin":"icon/peifang_3_2.png"}},{"type":"Image","props":{"y":64,"x":716,"visible":false,"var":"kaxiangshu_faguang_05","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":693,"var":"kaxiangshu_1","skin":"icon/peifang_3_1.png"}},{"type":"Image","props":{"y":143,"x":150,"skin":"gongluenew/xiaohao.png"}},{"type":"Label","props":{"y":199,"x":4,"wordWrap":true,"width":790,"var":"kaxiangshu_content","text":"五星吕宋烟叶·醇14份\\n四星津巴布韦烟叶·醇8份\\n三星巴西烟叶·醇5份\\n咖啡颗粒嘴棒10份","padding":"10","leading":5,"height":157,"fontSize":24,"font":"SimSun","color":"#0ef4eb"}}]},{"type":"Box","props":{"y":353,"x":793,"width":793,"pivotY":352.63157894736844,"pivotX":792.1052631578948,"name":"item4","height":353},"child":[{"type":"Image","props":{"y":64,"x":76,"var":"lvsongshu_faguang_01","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":43,"var":"lvsongshu_5","skin":"icon/peifang_4_5.png"}},{"type":"Image","props":{"y":64,"x":237,"visible":false,"var":"lvsongshu_faguang_02","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":37,"x":206,"var":"lvsongshu_4","skin":"icon/peifang_4_4.png"}},{"type":"Image","props":{"y":64,"x":397,"visible":false,"var":"lvsongshu_faguang_03","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":37,"x":368,"var":"lvsongshu_3","skin":"icon/peifang_4_3.png"}},{"type":"Image","props":{"y":64,"x":557,"visible":false,"var":"lvsongshu_faguang_04","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":37,"x":531,"var":"lvsongshu_2","skin":"icon/peifang_4_2.png"}},{"type":"Image","props":{"y":64,"x":716,"visible":false,"var":"lvsongshu_faguang_05","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":693,"var":"lvsongshu_1","skin":"icon/peifang_4_1.png"}},{"type":"Image","props":{"y":143,"x":150,"skin":"gongluenew/xiaohao.png"}},{"type":"Label","props":{"y":199,"x":4,"wordWrap":true,"width":790,"var":"lvsongshu_content","text":"五星吕宋烟叶·醇14份\\n四星巴西烟叶·醇8份\\n三星云贵烟叶·醇5份\\n金花茶提取液嘴棒10份","padding":"10","leading":5,"height":157,"fontSize":24,"font":"SimSun","color":"#0ef4eb"}}]},{"type":"Box","props":{"y":353,"x":793,"width":793,"pivotY":352.63157894736844,"pivotX":792.1052631578948,"name":"item5","height":353},"child":[{"type":"Image","props":{"y":64,"x":76,"var":"jiuxiangshu_faguang_01","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":43,"var":"jiuxiangshu_5","skin":"icon/peifang_5_5.png"}},{"type":"Image","props":{"y":64,"x":237,"visible":false,"var":"jiuxiangshu_faguang_02","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":35,"x":206,"var":"jiuxiangshu_4","skin":"icon/peifang_5_4.png"}},{"type":"Image","props":{"y":64,"x":397,"visible":false,"var":"jiuxiangshu_faguang_03","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":35,"x":368,"var":"jiuxiangshu_3","skin":"icon/peifang_5_3.png"}},{"type":"Image","props":{"y":64,"x":557,"visible":false,"var":"jiuxiangshu_faguang_04","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":35,"x":531,"var":"jiuxiangshu_2","skin":"icon/peifang_5_2.png"}},{"type":"Image","props":{"y":64,"x":716,"visible":false,"var":"jiuxiangshu_faguang_05","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":693,"var":"jiuxiangshu_1","skin":"icon/peifang_5_1.png"}},{"type":"Image","props":{"y":143,"x":150,"skin":"gongluenew/xiaohao.png"}},{"type":"Label","props":{"y":199,"x":4,"wordWrap":true,"width":790,"var":"jiuxiangshu_content","text":"五星云贵烟叶·醇14份\\n四星津巴布韦烟叶·醇8份\\n三星巴西烟叶·醇5份\\n香槟爆珠嘴棒10份","padding":"10","leading":5,"height":157,"fontSize":24,"font":"SimSun","color":"#0ef4eb"}}]},{"type":"Box","props":{"y":353,"x":793,"width":793,"pivotY":352.63157894736844,"pivotX":792.1052631578948,"name":"item6","height":353},"child":[{"type":"Image","props":{"y":64,"x":76,"var":"jichushu_faguang_01","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":43,"var":"jichushu_5","skin":"icon/peifang_7_5.png"}},{"type":"Image","props":{"y":64,"x":237,"visible":false,"var":"jichushu_faguang_02","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":27,"x":206,"var":"jichushu_4","skin":"icon/peifang_7_4.png"}},{"type":"Image","props":{"y":64,"x":397,"visible":false,"var":"jichushu_faguang_03","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":27,"x":368,"var":"jichushu_3","skin":"icon/peifang_7_3.png"}},{"type":"Image","props":{"y":64,"x":557,"visible":false,"var":"jichushu_faguang_04","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":27,"x":531,"var":"jichushu_2","skin":"icon/peifang_7_2.png"}},{"type":"Image","props":{"y":64,"x":716,"visible":false,"var":"jichushu_faguang_05","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":693,"var":"jichushu_1","skin":"icon/peifang_7_1.png"}},{"type":"Image","props":{"y":143,"x":150,"skin":"gongluenew/xiaohao.png"}},{"type":"Label","props":{"y":199,"x":4,"wordWrap":true,"width":790,"var":"jichushu_content","text":"五星巴西烟叶·醇14份\\n四星吕宋烟叶·醇8份\\n三星云贵烟叶·醇5份\\n一点红嘴棒10份","padding":"10","leading":5,"height":157,"fontSize":24,"font":"SimSun","color":"#0ef4eb"}}]}]}]},{"type":"Box","props":{"name":"item1"},"child":[{"type":"Button","props":{"y":4,"x":11,"var":"jichu_btn","stateNum":"2","skin":"gongluenew/jichu.png"}},{"type":"Button","props":{"y":4,"x":209,"var":"jinjie_btn","stateNum":"2","skin":"gongluenew/jinjie.png"}},{"type":"ViewStack","props":{"y":63,"x":12,"width":803,"var":"shengchan_viewstack","selectedIndex":0,"height":394},"child":[{"type":"Box","props":{"y":0,"x":0,"width":801,"name":"item0","height":362},"child":[{"type":"Image","props":{"y":65,"x":743,"visible":false,"var":"faguang_07","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":30,"x":699,"var":"choujiang_btn","skin":"gongluenew/choujiang.png"}},{"type":"Image","props":{"y":65,"x":630,"visible":false,"var":"faguang_06","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":29,"x":582,"var":"pinjian_btn","skin":"gongluenew/pinjian.png"}},{"type":"Image","props":{"y":65,"x":508,"visible":false,"var":"faguang_05","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":29,"x":466,"var":"zhiyan_btn","skin":"gongluenew/zhiyan.png"}},{"type":"Image","props":{"y":65,"x":393,"visible":false,"var":"faguang_04","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":29,"x":353,"var":"chunhua_btn","skin":"gongluenew/chunhua.png"}},{"type":"Image","props":{"y":65,"x":287,"visible":false,"var":"faguang_03","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":29,"x":235,"var":"hongkao_btn","skin":"gongluenew/hongkao.png"}},{"type":"Image","props":{"y":65,"x":168,"visible":false,"var":"faguang_02","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":31,"x":120,"var":"zhongzhi_btn","skin":"gongluenew/zhongzhi.png"}},{"type":"Image","props":{"y":65,"x":52,"var":"faguang_01","skin":"gongluenew/faguang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":77,"x":52,"var":"shanghang_btn","skin":"gongluenew/shanghang.png","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":51,"x":94,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":51,"x":202,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":51,"x":328,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":51,"x":427,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":51,"x":550,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":51,"x":675,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":148,"x":148,"skin":"gongluenew/step.png"}},{"type":"ViewStack","props":{"y":202,"x":-10,"width":820,"var":"jichu_detail_viewstack","selectedIndex":0,"height":193},"child":[{"type":"Box","props":{"y":114,"x":799,"width":789,"pivotY":113.11475409836063,"pivotX":785.2459016393442,"name":"item0","height":157},"child":[{"type":"Image","props":{"y":27,"x":140,"skin":"gongluenew/zlshanghang_text.png"}},{"type":"Button","props":{"y":116,"x":649,"width":137,"var":"goto_btn_1","stateNum":"1","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":111,"x":798,"width":791,"pivotY":109.83606557377047,"pivotX":783.6065573770493,"name":"item1","height":157},"child":[{"type":"Image","props":{"y":41,"x":305,"skin":"gongluenew/zhongzhi_text.png"}},{"type":"Button","props":{"y":116,"x":649,"width":137,"var":"goto_btn_2","stateNum":"1","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":109,"x":787,"width":794,"pivotY":108.19672131147539,"pivotX":777.0491803278688,"name":"item2","height":157},"child":[{"type":"Image","props":{"y":34,"x":282,"skin":"gongluenew/hongkao_text.png"}},{"type":"Button","props":{"y":117,"x":653,"width":137,"var":"goto_btn_3","stateNum":"1","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":117,"x":790,"width":787,"pivotY":118.03278688524588,"pivotX":777.049180327869,"name":"item3","height":159},"child":[{"type":"Image","props":{"y":40,"x":218,"skin":"gongluenew/chunhua_text.png"}},{"type":"Button","props":{"y":118,"x":651,"width":137,"var":"goto_btn_4","stateNum":"1","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":110,"x":794,"width":789,"pivotY":111.47540983606555,"pivotX":778.6885245901641,"name":"item4","height":158},"child":[{"type":"Image","props":{"y":40,"x":186,"skin":"gongluenew/zhiyan_text.png"}},{"type":"Button","props":{"y":119,"x":648,"width":137,"var":"goto_btn_5","stateNum":"1","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":116,"x":788,"width":789,"pivotY":113.11475409836066,"pivotX":773.7704918032788,"name":"item5","height":156},"child":[{"type":"Image","props":{"y":38,"x":176,"skin":"gongluenew/pinjian_text.png"}},{"type":"Button","props":{"y":115,"x":649,"width":137,"var":"goto_btn_6","stateNum":"1","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":116,"x":792,"width":790,"pivotY":113.11475409836063,"pivotX":777.049180327869,"name":"item6","height":158},"child":[{"type":"Image","props":{"y":29,"x":208,"skin":"gongluenew/choujiang_text.png"}},{"type":"Button","props":{"y":115,"x":648,"width":137,"var":"goto_btn_7","stateNum":"1","skin":"gongluenew/goto.png","height":40}}]}]}]},{"type":"Box","props":{"y":0,"x":0,"width":798,"name":"item1","height":358},"child":[{"type":"Panel","props":{"y":10,"x":-3,"width":797,"var":"jinjie_panel","height":118,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Image","props":{"y":3,"x":-25,"var":"jinjie_faguang_01","skin":"gongluenew/faguang.png"}},{"type":"Image","props":{"y":10,"x":0,"var":"get_tiaoxiangshu","skin":"gongluenew/get_tiaoxiangshu.png"}},{"type":"Image","props":{"y":-1,"x":110,"visible":false,"var":"jinjie_faguang_02","skin":"gongluenew/faguang.png"}},{"type":"Image","props":{"y":10,"x":143,"var":"chakanpeifang","skin":"gongluenew/look_peifang.png"}},{"type":"Image","props":{"y":1,"x":257,"visible":false,"var":"jinjie_faguang_03","skin":"gongluenew/faguang.png"}},{"type":"Image","props":{"y":10,"x":285,"var":"get_zhongzi","skin":"gongluenew/get_zhongzi.png"}},{"type":"Image","props":{"y":-4,"x":399,"visible":false,"var":"jinjie_faguang_04","skin":"gongluenew/faguang.png"}},{"type":"Image","props":{"y":19,"x":428,"var":"zhongzhi","skin":"gongluenew/zhongzhi.png"}},{"type":"Image","props":{"y":-3,"x":530,"visible":false,"var":"jinjie_faguang_05","skin":"gongluenew/faguang.png"}},{"type":"Image","props":{"y":17,"x":560,"var":"hongkao","skin":"gongluenew/hongkao.png"}},{"type":"Image","props":{"y":-5,"x":660,"visible":false,"var":"jinjie_faguang_06","skin":"gongluenew/faguang.png"}},{"type":"Image","props":{"y":17,"x":696,"var":"chunhua","skin":"gongluenew/chunhua.png"}},{"type":"Image","props":{"y":-6,"x":811,"visible":false,"var":"jinjie_faguang_07","skin":"gongluenew/faguang.png"}},{"type":"Image","props":{"y":17,"x":827,"var":"zhiyan","skin":"gongluenew/zhiyan.png"}},{"type":"Image","props":{"y":-8,"x":948,"visible":false,"var":"jinjie_faguang_08","skin":"gongluenew/faguang.png"}},{"type":"Image","props":{"y":17,"x":959,"var":"pinjian","skin":"gongluenew/pinjian.png"}},{"type":"Image","props":{"y":-8,"x":1082,"visible":false,"var":"jinjie_faguang_09","skin":"gongluenew/faguang.png"}},{"type":"Image","props":{"y":18,"x":1094,"var":"choujiang","skin":"gongluenew/choujiang.png"}},{"type":"Image","props":{"y":50,"x":98,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":50,"x":240,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":50,"x":393,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":50,"x":519,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":50,"x":662,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":50,"x":800,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":50,"x":924,"skin":"gongluenew/jiantou.png"}},{"type":"Image","props":{"y":50,"x":1059,"skin":"gongluenew/jiantou.png"}}]},{"type":"ViewStack","props":{"y":308,"x":776,"width":789,"var":"jinjie_detail_viewstack","selectedIndex":0,"pivotY":104.66365178066687,"pivotX":772.1028829847371,"height":159},"child":[{"type":"Box","props":{"y":13,"x":33,"width":790,"pivotY":13.114754098360663,"pivotX":32.78688524590164,"name":"item0","height":156},"child":[{"type":"Image","props":{"y":32,"x":36,"var":"yanjiusuo","skin":"gongluenew/yanjiusuo.png"}},{"type":"Image","props":{"y":43,"x":242,"var":"youleyuan","skin":"gongluenew/youleyuan.png"}},{"type":"Image","props":{"y":46,"x":457,"var":"qiandao","skin":"gongluenew/qiandao.png"}},{"type":"Image","props":{"y":44,"x":673,"var":"dati","skin":"gongluenew/dati.png"}},{"type":"Image","props":{"y":14,"x":69,"visible":false,"var":"yanjiusuo_text","skin":"gongluenew/yanjiusuo_text.png"}},{"type":"Image","props":{"y":13,"x":52,"visible":false,"var":"youleyuan_text","skin":"gongluenew/youleyuan_text.png"}},{"type":"Image","props":{"y":13,"x":70,"visible":false,"var":"qiandao_text","skin":"gongluenew/qiandao_text.png"}},{"type":"Image","props":{"y":4,"x":58,"visible":false,"var":"dati_text","skin":"gongluenew/dati_text.png"}},{"type":"Image","props":{"y":117,"x":2,"visible":false,"var":"go_back_btn","skin":"gongluenew/go_back.png"}},{"type":"Image","props":{"y":117,"x":650,"width":137,"visible":false,"var":"shu_goto_btn_1","skin":"gongluenew/goto.png","height":40}},{"type":"Image","props":{"y":117,"x":650,"width":137,"visible":false,"var":"shu_goto_btn_2","skin":"gongluenew/goto.png","height":40}},{"type":"Image","props":{"y":117,"x":650,"width":137,"visible":false,"var":"shu_goto_btn_3","skin":"gongluenew/goto.png","height":40}},{"type":"Image","props":{"y":117,"x":650,"width":137,"visible":false,"var":"shu_goto_btn_4","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":0,"x":0,"width":790,"name":"item1","height":154},"child":[{"type":"Image","props":{"y":34,"x":244,"skin":"gongluenew/chakan_peifang.png"}}]},{"type":"Box","props":{"y":12,"x":16,"width":790,"pivotY":12.068965517241395,"pivotX":15.517241379310349,"name":"item2","height":156},"child":[{"type":"Image","props":{"y":25,"x":42,"var":"peiyuzhongxin","skin":"gongluenew/peiyuzhongxin.png"}},{"type":"Image","props":{"y":26,"x":196,"var":"shenmi","skin":"gongluenew/shenmi.png"}},{"type":"Image","props":{"y":35,"x":347,"var":"youleyuan_2","skin":"gongluenew/youleyuan.png"}},{"type":"Image","props":{"y":38,"x":501,"var":"qiandao_2","skin":"gongluenew/qiandao.png"}},{"type":"Image","props":{"y":36,"x":654,"visible":true,"var":"dati_2","skin":"gongluenew/dati.png"}},{"type":"Image","props":{"y":7,"x":68,"visible":false,"var":"peiyuzhongxin_text","skin":"gongluenew/peiyuzhongxin_text.png"}},{"type":"Image","props":{"y":3,"x":53,"visible":false,"var":"shenmi_text","skin":"gongluenew/shenmi_text.png"}},{"type":"Image","props":{"y":18,"x":46,"visible":false,"var":"youleyuan_text_2","skin":"gongluenew/youleyuan_text.png"}},{"type":"Image","props":{"y":27,"x":66,"visible":false,"var":"qiandao_text_2","skin":"gongluenew/qiandao_text.png"}},{"type":"Image","props":{"y":4,"x":59,"visible":false,"var":"dati_text_2","skin":"gongluenew/dati_text.png"}},{"type":"Image","props":{"y":117,"x":-2,"visible":false,"var":"go_back_btn_2","skin":"gongluenew/go_back.png"}},{"type":"Image","props":{"y":117,"x":650,"width":137,"visible":false,"var":"zz_goto_btn_1","skin":"gongluenew/goto.png","height":40}},{"type":"Image","props":{"y":117,"x":650,"width":137,"visible":false,"var":"zz_goto_btn_2","skin":"gongluenew/goto.png","height":40}},{"type":"Image","props":{"y":117,"x":650,"width":137,"visible":false,"var":"zz_goto_btn_3","skin":"gongluenew/goto.png","height":40}},{"type":"Image","props":{"y":117,"x":650,"width":137,"visible":false,"var":"zz_goto_btn_4","skin":"gongluenew/goto.png","height":40}},{"type":"Image","props":{"y":117,"x":650,"width":137,"visible":false,"var":"zz_goto_btn_5","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":149,"x":782,"width":788,"pivotY":149.1803278688525,"pivotX":781.9672131147541,"name":"item3","height":156},"child":[{"type":"Image","props":{"y":49,"x":298,"skin":"gongluenew/zhongzhi_text.png"}},{"type":"Image","props":{"y":117,"x":650,"width":137,"var":"zhongzhi_goto","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":150,"x":775,"width":787,"pivotY":150.81967213114757,"pivotX":775.4098360655739,"name":"item4","height":158},"child":[{"type":"Image","props":{"y":45,"x":286,"skin":"gongluenew/hongkao_text.png"}},{"type":"Image","props":{"y":117,"x":650,"width":137,"var":"hongkao_goto","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":0,"x":0,"width":784,"name":"item5","height":154},"child":[{"type":"Image","props":{"y":49,"x":210,"skin":"gongluenew/chunhua_text.png"}},{"type":"Image","props":{"y":117,"x":650,"width":137,"var":"chunhua_goto","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":0,"x":0,"width":786,"name":"item6","height":156},"child":[{"type":"Image","props":{"y":47,"x":204,"skin":"gongluenew/zhiyan_text.png"}},{"type":"Image","props":{"y":117,"x":650,"width":137,"var":"zhiyan_goto","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":0,"x":0,"width":786,"name":"item7","height":156},"child":[{"type":"Image","props":{"y":44,"x":187,"skin":"gongluenew/pinjian_text.png"}},{"type":"Image","props":{"y":117,"x":650,"width":137,"var":"pinjian_goto","skin":"gongluenew/goto.png","height":40}}]},{"type":"Box","props":{"y":146,"x":763,"width":785,"pivotY":145.90163934426226,"pivotX":763.9344262295082,"name":"item8","height":156},"child":[{"type":"Image","props":{"y":42,"x":215,"skin":"gongluenew/choujiang_text.png"}},{"type":"Image","props":{"y":117,"x":650,"width":137,"var":"choujiang_goto","skin":"gongluenew/goto.png","height":40}}]}]},{"type":"Image","props":{"y":148,"x":136,"skin":"gongluenew/step.png"}}]}]}]},{"type":"Box","props":{"y":0,"x":0,"width":819,"name":"item2","height":465},"child":[{"type":"Panel","props":{"y":0,"x":0,"width":810,"var":"other_panel","height":61,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Button","props":{"y":11,"x":9,"var":"other_shanghang_btn","stateNum":"2","skin":"gongluenew/zlshanghang.png"}},{"type":"Button","props":{"y":11,"x":124,"var":"other_zhongzhi_btn","stateNum":"2","skin":"gongluenew/zhongzhitext.png"}},{"type":"Button","props":{"y":11,"x":238,"var":"other_dingdan_btn","stateNum":"2","skin":"gongluenew/dingdan.png"}},{"type":"Button","props":{"y":11,"x":353,"var":"other_xiaotan_btn","stateNum":"2","skin":"gongluenew/lubiantan.png"}},{"type":"Button","props":{"y":11,"x":582,"var":"other_chengjiu_btn","stateNum":"2","skin":"gongluenew/chengjiu.png"}},{"type":"Button","props":{"y":11,"x":696,"var":"other_haoyou_btn","stateNum":"2","skin":"gongluenew/haoyou.png"}},{"type":"Button","props":{"y":11,"x":467,"var":"other_jiandie_btn","stateNum":"2","skin":"gongluenew/jiandie.png"}},{"type":"Button","props":{"y":10,"x":811,"var":"other_tufa_btn","stateNum":"2","skin":"gongluenew/tufa.png"}}]},{"type":"ViewStack","props":{"y":481,"x":753,"width":843,"var":"other_viewstack","selectedIndex":0,"pivotY":409.83606557377055,"pivotX":763.9344262295082,"height":408},"child":[{"type":"Box","props":{"y":18,"x":26,"width":842,"pivotY":16.39344262295083,"pivotX":26.229508196721326,"name":"item0","height":404},"child":[{"type":"Image","props":{"var":"other_shanghang","skin":"gongluenew/other_shanghang_1.png","name":"0"}},{"type":"Image","props":{"y":120,"x":32,"width":52,"visible":false,"var":"shanghang_pre_btn","skin":"gongluenew/pre_btn.png","height":103}},{"type":"Image","props":{"y":120,"x":760,"width":52,"var":"shanghang_next_btn","skin":"gongluenew/next_btn.png","height":103}}]},{"type":"Box","props":{"y":18,"x":26,"width":847,"pivotY":16.39344262295083,"pivotX":26.229508196721326,"name":"item1","height":404},"child":[{"type":"Image","props":{"var":"other_zhongzhi","skin":"gongluenew/other_zhongzhi_1.png","name":"0"}},{"type":"Image","props":{"y":120,"x":32,"width":52,"visible":false,"var":"zhongzhi_pre_btn","skin":"gongluenew/pre_btn.png","height":103}},{"type":"Image","props":{"y":120,"x":760,"width":52,"var":"zhongzhi_next_btn","skin":"gongluenew/next_btn.png","height":103}}]},{"type":"Box","props":{"y":18,"x":26,"width":847,"pivotY":16.39344262295083,"pivotX":26.229508196721326,"name":"item2","height":404},"child":[{"type":"Image","props":{"var":"other_dingdan","skin":"gongluenew/other_dingdan_1.png","name":"0"}},{"type":"Image","props":{"y":120,"x":32,"width":52,"visible":false,"var":"dingdan_pre_btn","skin":"gongluenew/pre_btn.png","height":103}},{"type":"Image","props":{"y":120,"x":760,"width":52,"var":"dingdan_next_btn","skin":"gongluenew/next_btn.png","height":103}}]},{"type":"Box","props":{"y":18,"x":26,"width":847,"pivotY":16.39344262295083,"pivotX":26.229508196721326,"name":"item3","height":404},"child":[{"type":"Image","props":{"var":"other_xiaotan","skin":"gongluenew/other_xiaotan_1.png","name":"0"}},{"type":"Image","props":{"y":120,"x":32,"width":52,"visible":false,"var":"xiaotan_pre_btn","skin":"gongluenew/pre_btn.png","height":103}},{"type":"Image","props":{"y":120,"x":760,"width":52,"var":"xiaotan_next_btn","skin":"gongluenew/next_btn.png","height":103}}]},{"type":"Box","props":{"y":18,"x":26,"width":847,"pivotY":16.39344262295083,"pivotX":26.229508196721326,"name":"item4","height":404},"child":[{"type":"Image","props":{"var":"other_jiandie","skin":"gongluenew/other_jiandie_1.png","name":"0"}},{"type":"Image","props":{"y":120,"x":32,"width":52,"visible":false,"var":"jiandie_pre_btn","skin":"gongluenew/pre_btn.png","height":103}},{"type":"Image","props":{"y":120,"x":760,"width":52,"var":"jiandie_next_btn","skin":"gongluenew/next_btn.png","height":103}}]},{"type":"Box","props":{"y":18,"x":26,"width":847,"pivotY":16.39344262295083,"pivotX":26.229508196721326,"name":"item5","height":404},"child":[{"type":"Image","props":{"var":"other_chengjiu","skin":"gongluenew/other_chengjiu_1.png","name":"0"}},{"type":"Image","props":{"y":120,"x":32,"width":52,"visible":false,"var":"chengjiu_pre_btn","skin":"gongluenew/pre_btn.png","height":103}},{"type":"Image","props":{"y":120,"x":760,"width":52,"var":"chengjiu_next_btn","skin":"gongluenew/next_btn.png","height":103}}]},{"type":"Box","props":{"y":18,"x":26,"width":847,"pivotY":16.39344262295083,"pivotX":26.229508196721326,"name":"item6","height":404},"child":[{"type":"Image","props":{"var":"other_haoyou","skin":"gongluenew/other_haoyou_1.png","name":"0"}},{"type":"Image","props":{"y":120,"x":32,"width":52,"visible":false,"var":"haoyou_pre_btn","skin":"gongluenew/pre_btn.png","height":103}},{"type":"Image","props":{"y":120,"x":760,"width":52,"var":"haoyou_next_btn","skin":"gongluenew/next_btn.png","height":103}}]},{"type":"Box","props":{"name":"item7"},"child":[{"type":"Image","props":{"var":"other_tufa","skin":"gongluenew/other_tufa_1.png","name":"0"}},{"type":"Image","props":{"y":120,"x":32,"width":52,"visible":false,"var":"tufa_pre_btn","skin":"gongluenew/pre_btn.png","height":103}},{"type":"Image","props":{"y":120,"x":760,"width":52,"var":"tufa_next_btn","skin":"gongluenew/next_btn.png","height":103}}]}]}]}]}]};}
		]);
		return gongluenewUI;
	})(Dialog);
var GuideBookUI=(function(_super){
		function GuideBookUI(){
			
		    this.panel=null;
		    this.guide_tab=null;
		    this.pre_page=null;
		    this.next_page=null;
		    this.curr_page=null;
		    this.pages_count=null;
		    this.guide_img=null;
		    this.ren=null;

			GuideBookUI.__super.call(this);
		}

		CLASS$(GuideBookUI,'ui.GuideBookUI',_super);
		var __proto__=GuideBookUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(GuideBookUI.uiView);
		}

		STATICATTR$(GuideBookUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":960,"height":640},"child":[{"type":"Image","props":{"y":20,"x":0,"skin":"guidebook/bg.png","height":600}},{"type":"Panel","props":{"y":80,"x":52,"width":854,"var":"panel","height":45,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Tab","props":{"y":1,"x":2,"var":"guide_tab","stateNum":2,"space":0,"skin":"guidebook/tab_bg.png","rotation":0,"labels":"商城,种植,烘烤,培育,醇化,制烟,研究所,品鉴,抽奖,订单,小摊,虫子","labelSize":20,"labelFont":"SimHei","labelColors":"#2E1306,#2E1306","height":44}}]},{"type":"Image","props":{"y":555,"x":469,"skin":"guidebook/fuhao.png"}},{"type":"Button","props":{"y":545,"x":301,"var":"pre_page","stateNum":"2","skin":"guidebook/shangyiye.png"}},{"type":"Button","props":{"y":544,"x":548,"var":"next_page","stateNum":"2","skin":"guidebook/xiayiye.png"}},{"type":"Label","props":{"y":551,"x":433,"width":33,"var":"curr_page","text":"1","fontSize":30,"color":"#fce4b0","align":"right"}},{"type":"Label","props":{"y":552,"x":489,"var":"pages_count","text":"10","fontSize":30,"color":"#fce4b0"}},{"type":"Button","props":{"y":12,"x":883,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Image","props":{"y":333,"x":476,"width":832,"var":"guide_img","height":410,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":103,"x":695,"var":"ren"}}]};}
		]);
		return GuideBookUI;
	})(Dialog);
var GuideStep01UI=(function(_super){
		function GuideStep01UI(){
			

			GuideStep01UI.__super.call(this);
		}

		CLASS$(GuideStep01UI,'ui.GuideStep01UI',_super);
		var __proto__=GuideStep01UI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(GuideStep01UI.uiView);
		}

		STATICATTR$(GuideStep01UI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":960,"height":640},"child":[{"type":"Image","props":{"y":44,"x":277,"skin":"zhiyin/dakailibao.png"}},{"type":"Label","props":{"y":455,"x":219,"wordWrap":true,"width":522,"valign":"middle","text":"银元100000、加速闪电*100、土地*6、一星巴西烟叶·醇*14、一星吕宋烟叶·醇*5、一星云贵烟叶·醇*4、碎片A*1","height":88,"fontSize":26,"font":"SimHei","color":"#f9e500","align":"center"}},{"type":"Label","props":{"y":561,"x":345,"width":270,"valign":"middle","text":"获取物品请到仓库查看","height":34,"fontSize":26,"font":"SimHei","color":"#fb8200","align":"center"}}]};}
		]);
		return GuideStep01UI;
	})(Dialog);
var guide_npc_dialogUI=(function(_super){
		function guide_npc_dialogUI(){
			
		    this.Content=null;
		    this.Content_text=null;
		    this.nav=null;
		    this.BuySeed=null;
		    this.Plant=null;
		    this.Baking=null;
		    this.Aging=null;
		    this.ZhiYan=null;
		    this.PinJian=null;
		    this.ChouJiang=null;
		    this.Order=null;
		    this.yanjiusuo=null;
		    this.peiyu=null;
		    this.youleyuan=null;
		    this.btn_next=null;

			guide_npc_dialogUI.__super.call(this);
		}

		CLASS$(guide_npc_dialogUI,'ui.guide_npc_dialogUI',_super);
		var __proto__=guide_npc_dialogUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(guide_npc_dialogUI.uiView);
		}

		STATICATTR$(guide_npc_dialogUI,
		['uiView',function(){return this.uiView={"type":"View","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"width":960,"skin":"guide/diwen.png","height":221}},{"type":"Image","props":{"y":92,"x":101,"width":835,"skin":"guide/diwen_1.png","sizeGrid":"20,20,20,20","height":119},"child":[{"type":"Image","props":{"y":10,"x":14,"var":"Content"}},{"type":"Label","props":{"y":10,"x":14,"wordWrap":true,"width":810,"var":"Content_text","leading":5,"height":100,"fontSize":24,"font":"SimHei"}}]},{"type":"Panel","props":{"y":40,"x":92,"width":848,"var":"nav","height":53,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Box","props":{"y":5,"x":1},"child":[{"type":"Image","props":{"y":0,"x":31,"var":"BuySeed","skin":"guide/diwen_2.png","gray":true},"child":[{"type":"Image","props":{"y":6,"x":-33,"skin":"guide/jiantou.png"}},{"type":"Label","props":{"y":8,"x":10,"width":55,"valign":"middle","text":"购买","strokeColor":"#ffffff","stroke":2,"height":26,"fontSize":22,"font":"SimHei","color":"#128d25","align":"center"}}]},{"type":"Image","props":{"y":0,"x":136,"var":"Plant","skin":"guide/diwen_2.png","gray":true},"child":[{"type":"Image","props":{"y":6,"x":-34,"skin":"guide/jiantou.png"}},{"type":"Label","props":{"y":8,"x":13,"width":50,"valign":"middle","text":"种植","strokeColor":"#ffffff","stroke":2,"height":26,"fontSize":22,"font":"SimHei","color":"#128d25","align":"center"}}]},{"type":"Image","props":{"y":0,"x":241,"var":"Baking","skin":"guide/diwen_2.png","gray":true},"child":[{"type":"Image","props":{"y":6,"x":-34,"skin":"guide/jiantou.png"}},{"type":"Label","props":{"y":8,"x":13,"width":50,"valign":"middle","text":"烘烤","strokeColor":"#ffffff","stroke":2,"height":26,"fontSize":22,"font":"SimHei","color":"#128d25","align":"center"}}]},{"type":"Image","props":{"y":0,"x":346,"var":"Aging","skin":"guide/diwen_2.png","gray":true},"child":[{"type":"Image","props":{"y":6,"x":-34,"skin":"guide/jiantou.png"}},{"type":"Label","props":{"y":8,"x":13,"width":50,"valign":"middle","text":"醇化","strokeColor":"#ffffff","stroke":2,"height":26,"fontSize":22,"font":"SimHei","color":"#128d25","align":"center"}}]},{"type":"Image","props":{"y":0,"x":452,"var":"ZhiYan","skin":"guide/diwen_2.png","gray":true},"child":[{"type":"Image","props":{"y":6,"x":-34,"skin":"guide/jiantou.png"}},{"type":"Label","props":{"y":8,"x":13,"width":50,"valign":"middle","text":"制烟","strokeColor":"#ffffff","stroke":2,"height":26,"fontSize":22,"font":"SimHei","color":"#128d25","align":"center"}}]},{"type":"Image","props":{"y":0,"x":558,"var":"PinJian","skin":"guide/diwen_2.png","gray":true},"child":[{"type":"Image","props":{"y":6,"x":-34,"skin":"guide/jiantou.png"}},{"type":"Label","props":{"y":8,"x":13,"width":50,"valign":"middle","text":"品鉴","strokeColor":"#ffffff","stroke":2,"height":26,"fontSize":22,"font":"SimHei","color":"#128d25","align":"center"}}]},{"type":"Image","props":{"y":0,"x":663,"var":"ChouJiang","skin":"guide/diwen_2.png","gray":true},"child":[{"type":"Image","props":{"y":6,"x":-34,"skin":"guide/jiantou.png"}},{"type":"Label","props":{"y":8,"x":13,"width":50,"valign":"middle","text":"抽奖","strokeColor":"#ffffff","stroke":2,"height":26,"fontSize":22,"font":"SimHei","color":"#128d25","align":"center"}}]},{"type":"Image","props":{"y":0,"x":768,"var":"Order","skin":"guide/diwen_2.png","gray":true},"child":[{"type":"Image","props":{"y":6,"x":-34,"skin":"guide/jiantou.png"}},{"type":"Label","props":{"y":8,"x":13,"width":50,"valign":"middle","text":"订单","strokeColor":"#ffffff","stroke":2,"height":26,"fontSize":22,"font":"SimHei","color":"#128d25","align":"center"}}]},{"type":"Image","props":{"y":0,"x":873,"var":"yanjiusuo","skin":"guide/diwen_2.png","gray":true},"child":[{"type":"Image","props":{"y":6,"x":-34,"skin":"guide/jiantou.png"}},{"type":"Label","props":{"y":8,"x":5,"width":66,"valign":"middle","text":"研究所","strokeColor":"#ffffff","stroke":2,"height":26,"fontSize":22,"font":"SimHei","color":"#128d25","align":"center"}}]},{"type":"Image","props":{"y":0,"x":978,"var":"peiyu","skin":"guide/diwen_2.png","gray":true},"child":[{"type":"Image","props":{"y":6,"x":-34,"skin":"guide/jiantou.png"}},{"type":"Label","props":{"y":8,"x":5,"width":66,"valign":"middle","text":"培育室","strokeColor":"#ffffff","stroke":2,"height":26,"fontSize":22,"font":"SimHei","color":"#128d25","align":"center"}}]},{"type":"Image","props":{"y":0,"x":1082,"var":"youleyuan","skin":"guide/diwen_2.png","gray":true},"child":[{"type":"Image","props":{"y":6,"x":-34,"skin":"guide/jiantou.png"}},{"type":"Label","props":{"y":8,"x":5,"width":66,"valign":"middle","text":"游乐园","strokeColor":"#ffffff","stroke":2,"height":26,"fontSize":22,"font":"SimHei","color":"#128d25","align":"center"}}]}]}]},{"type":"Button","props":{"y":173,"x":825,"width":100,"var":"btn_next","stateNum":"2","skin":"guide/xiayibukuang.png","labelSize":24,"labelFont":"SimHei","label":"下一步","height":35}}]};}
		]);
		return guide_npc_dialogUI;
	})(View);
var HeChengSuccessUI=(function(_super){
		function HeChengSuccessUI(){
			
		    this.icon=null;

			HeChengSuccessUI.__super.call(this);
		}

		CLASS$(HeChengSuccessUI,'ui.HeChengSuccessUI',_super);
		var __proto__=HeChengSuccessUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(HeChengSuccessUI.uiView);
		}

		STATICATTR$(HeChengSuccessUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"pinjian/hecheng_bg.png"},"child":[{"type":"Button","props":{"y":352,"x":136,"stateNum":"1","skin":"pinjian/button_ok.png","name":"close"}}]},{"type":"Image","props":{"y":173,"x":163,"width":70,"var":"icon","height":70}}]};}
		]);
		return HeChengSuccessUI;
	})(Dialog);
var HolidaysUI=(function(_super){
		function HolidaysUI(){
			
		    this.lingqu_btn=null;
		    this.item0=null;
		    this.item1=null;
		    this.item2=null;

			HolidaysUI.__super.call(this);
		}

		CLASS$(HolidaysUI,'ui.HolidaysUI',_super);
		var __proto__=HolidaysUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(HolidaysUI.uiView);
		}

		STATICATTR$(HolidaysUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"width":793,"skin":"Holidays/yuandan_bg.png","height":529}},{"type":"Button","props":{"y":475,"x":354,"width":128,"var":"lingqu_btn","stateNum":"1","skin":"Holidays/zhongqiu_btn.png","height":39}},{"type":"Box","props":{"y":270,"x":287},"child":[{"type":"Image","props":{"y":0,"x":15,"width":72,"var":"item0","skin":"shop/wupindiban.png","height":76},"child":[{"type":"Image","props":{"y":8,"x":6,"width":60,"name":"icon","height":60}},{"type":"Label","props":{"y":84,"x":37,"width":163,"valign":"middle","name":"item_name","height":19,"fontSize":14,"font":"SimHei","anchorY":0.5,"anchorX":0.5,"align":"center"}}]},{"type":"Image","props":{"y":0,"x":183,"width":72,"var":"item1","skin":"shop/wupindiban.png","height":76},"child":[{"type":"Image","props":{"y":8,"x":6,"width":60,"name":"icon","height":60}},{"type":"Label","props":{"y":84,"x":37,"width":163,"valign":"middle","name":"item_name","height":19,"fontSize":14,"font":"SimHei","anchorY":0.5,"anchorX":0.5,"align":"center"}}]},{"type":"Image","props":{"y":86,"x":98,"width":72,"var":"item2","skin":"shop/wupindiban.png","height":76},"child":[{"type":"Image","props":{"y":8,"x":6,"width":60,"name":"icon","height":60}},{"type":"Label","props":{"y":84,"x":37,"width":163,"valign":"middle","name":"item_name","height":19,"fontSize":14,"font":"SimHei","anchorY":0.5,"anchorX":0.5,"align":"center"}}]}]}]};}
		]);
		return HolidaysUI;
	})(Dialog);
var HuoDongUI=(function(_super){
		function HuoDongUI(){
			
		    this.panel=null;
		    this.tab=null;
		    this.img=null;
		    this.intro=null;
		    this.goto_btn=null;

			HuoDongUI.__super.call(this);
		}

		CLASS$(HuoDongUI,'ui.HuoDongUI',_super);
		var __proto__=HuoDongUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(HuoDongUI.uiView);
		}

		STATICATTR$(HuoDongUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"huodong/youxihuodongdatingdiban.png"}},{"type":"Panel","props":{"y":85,"x":19,"width":282,"var":"panel","vScrollBarSkin":"ui/vscroll.png","height":425},"child":[{"type":"Tab","props":{"y":6,"x":2,"var":"tab","stateNum":2,"space":5,"skin":"huodong/youxihuodong_btn.png","selectedIndex":0,"labelSize":24,"labelColors":"#4a251e,#b55a0a","labelBold":true,"direction":"vertical"}}]},{"type":"Box","props":{"y":87,"x":302,"width":627,"height":424},"child":[{"type":"Image","props":{"y":143,"x":313,"var":"img","anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":328,"x":28,"wordWrap":true,"width":391,"var":"intro","overflow":"scroll","leading":2,"height":97,"fontSize":20,"font":"SimHei","color":"#4a251e"}},{"type":"Button","props":{"y":360,"x":420,"var":"goto_btn","stateNum":"2","skin":"huodong/goto_btn.png"}}]},{"type":"Button","props":{"y":-24,"x":887,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}}]};}
		]);
		return HuoDongUI;
	})(Dialog);
var ItemInfoUI=(function(_super){
		function ItemInfoUI(){
			
		    this.item_name=null;
		    this.panel=null;
		    this.select_details=null;

			ItemInfoUI.__super.call(this);
		}

		CLASS$(ItemInfoUI,'ui.ItemInfoUI',_super);
		var __proto__=ItemInfoUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ItemInfoUI.uiView);
		}

		STATICATTR$(ItemInfoUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"y":180,"x":100,"pivotY":180,"pivotX":100,"mouseThrough":true},"child":[{"type":"Image","props":{"y":0,"x":0,"width":200,"skin":"bozhong/kuang.png","sizeGrid":"10,5,10,5","height":164}},{"type":"Label","props":{"y":10,"x":5,"width":35,"valign":"middle","text":"名称：","height":20,"fontSize":16}},{"type":"Label","props":{"y":10,"x":46,"width":147,"var":"item_name","valign":"middle","height":20,"fontSize":16}},{"type":"Label","props":{"y":47,"x":5,"width":35,"valign":"middle","text":"简介：","height":20,"fontSize":16}},{"type":"Panel","props":{"y":68,"x":7,"width":187,"var":"panel","vScrollBarSkin":"ui/vscroll.png","height":92},"child":[{"type":"HTMLDivElement","props":{"y":-1,"x":0,"width":187,"var":"select_details","height":92}}]}]};}
		]);
		return ItemInfoUI;
	})(Dialog);
var JGCDialogUI=(function(_super){
		function JGCDialogUI(){
			
		    this.tab=null;
		    this.view_stack=null;
		    this.zulin_box0=null;
		    this.zulin_box1=null;
		    this.zulin_box2=null;
		    this.tips1=null;
		    this.tips2=null;
		    this.zulin_ok_btn=null;
		    this.jiqi_3=null;
		    this.jiqi_2=null;
		    this.jiqi_1=null;
		    this.jiqi_name=null;
		    this.cao0=null;
		    this.cao1=null;
		    this.cao2=null;
		    this.time_progress=null;
		    this.time_countdown=null;
		    this.yan_name=null;
		    this.yan_icon=null;
		    this.btn_srart=null;
		    this.btn_lingqu=null;
		    this.btn_speedup=null;
		    this.need_ledou=null;
		    this.help_btn=null;
		    this.ck_btn=null;

			JGCDialogUI.__super.call(this);
		}

		CLASS$(JGCDialogUI,'ui.JGCDialogUI',_super);
		var __proto__=JGCDialogUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(JGCDialogUI.uiView);
		}

		STATICATTR$(JGCDialogUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Tab","props":{"y":131,"x":882,"var":"tab","strokeColors":"#cea36f,#cea36f","stateNum":2,"space":15,"skin":"factory/tab_ban.png","selectedIndex":1,"labels":"租赁,生产","labelStrokeColor":"#cea36f","labelStroke":2,"labelSize":28,"labelColors":"#000000,#000000","labelBold":true,"labelAlign":"center","direction":"vertical"}},{"type":"ViewStack","props":{"y":83,"x":129,"width":738,"var":"view_stack","selectedIndex":1,"height":426},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"item0"},"child":[{"type":"Image","props":{"y":-7,"x":-7,"width":750,"skin":"factory/zhuling_dishe.png","height":435}},{"type":"Box","props":{"y":18,"x":9,"var":"zulin_box0"},"child":[{"type":"Image","props":{"skin":"factory/zhuling_wupinban.png","name":"bg","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Image","props":{"y":47,"x":20,"skin":"factory/jiqishuoming_1.png"}},{"type":"Label","props":{"y":14,"x":17,"width":193,"valign":"middle","text":"初级制烟机器","height":30,"fontSize":24,"font":"SimHei","color":"#6a3806","bold":true,"align":"center"}}]},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"factory/xianzhekuang.png","name":"select"}},{"type":"Label","props":{"y":228,"x":107,"width":73,"valign":"middle","text":"20000","name":"price","height":30,"fontSize":24,"font":"SimHei","color":"#6a3806","bold":true,"align":"left"}},{"type":"Image","props":{"y":70,"x":54,"skin":"factory/zhuling_jiqi_2_1_chuji.png"}},{"type":"Label","props":{"y":259,"x":14,"width":200,"valign":"middle","name":"countdown","height":30,"fontSize":18,"font":"SimHei","color":"#09ad16","align":"center"}}]},{"type":"Box","props":{"y":18,"x":253,"var":"zulin_box1","disabled":true},"child":[{"type":"Image","props":{"skin":"factory/zhuling_wupinban.png","name":"bg","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Label","props":{"y":14,"x":17,"width":193,"valign":"middle","text":"中级制烟机器","height":30,"fontSize":24,"font":"SimHei","color":"#6a3806","bold":true,"align":"center"}},{"type":"Image","props":{"y":47,"x":20,"skin":"factory/jiqishuoming_2.png"}}]},{"type":"Image","props":{"visible":false,"skin":"factory/xianzhekuang.png","name":"select"}},{"type":"Label","props":{"y":228,"x":109,"width":70,"valign":"middle","text":"30000","name":"price","height":30,"fontSize":24,"font":"SimHei","color":"#6a3806","bold":true,"align":"left"}},{"type":"Image","props":{"y":69,"x":21,"skin":"factory/zhuling_jiqi_2_2_zhongji_1.png"}},{"type":"Image","props":{"y":142,"x":48,"skin":"factory/zhuling_shuo.png","name":"suo"}},{"type":"Label","props":{"y":259,"x":14,"width":200,"valign":"middle","name":"countdown","height":30,"fontSize":18,"font":"SimHei","color":"#09ad16","align":"center"}}]},{"type":"Box","props":{"y":18,"x":498,"var":"zulin_box2","disabled":true},"child":[{"type":"Image","props":{"skin":"factory/zhuling_wupinban.png","name":"bg","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Image","props":{"y":47,"x":20,"skin":"factory/jiqishuoming_3.png"}},{"type":"Label","props":{"y":14,"x":17,"width":193,"valign":"middle","text":"高级制烟机器","height":30,"fontSize":24,"font":"SimHei","color":"#6a3806","bold":true,"align":"center"}}]},{"type":"Image","props":{"visible":false,"skin":"factory/xianzhekuang.png","name":"select"}},{"type":"Label","props":{"y":228,"x":109,"width":70,"valign":"middle","text":"40000","name":"price","height":30,"fontSize":24,"font":"SimHei","color":"#6a3806","bold":true,"align":"left"}},{"type":"Image","props":{"y":60,"x":41,"skin":"factory/zhuling_jiqi_2_3_gaoji_1.png"}},{"type":"Image","props":{"y":142,"x":48,"skin":"factory/zhuling_shuo.png","name":"suo"}},{"type":"Label","props":{"y":259,"x":14,"width":200,"valign":"middle","name":"countdown","height":30,"fontSize":18,"font":"SimHei","color":"#09ad16","align":"center"}}]},{"type":"Label","props":{"y":328,"x":139,"width":464,"valign":"middle","text":"*温馨提示：租赁期为7天，从租赁当天算起","height":34,"fontSize":24,"font":"SimHei","color":"#672416","align":"center"}},{"type":"Image","props":{"y":121,"x":273,"var":"tips1","skin":"factory/niudaoxiaoshi.png"}},{"type":"Image","props":{"y":121,"x":526,"var":"tips2","skin":"factory/zhiyanzhuanjia.png"}},{"type":"Button","props":{"y":360,"x":302,"var":"zulin_ok_btn","stateNum":"2","skin":"dati/button_queding.png","labelStrokeColor":"#795501","labelStroke":3,"labelSize":24,"labelPadding":"0,0,2,0","labelFont":"SimHei","labelColors":"#fffef4,#fffef4,#fffef4,#fffef4"}}]},{"type":"Box","props":{"y":0,"x":0,"name":"item1"},"child":[{"type":"Image","props":{"y":-7,"x":-7,"width":750,"skin":"factory/zhiyan_dishe.png","height":435}},{"type":"Image","props":{"y":-2,"x":-2,"width":379,"skin":"factory/zhiyan_beijin.png","name":"room_bg","height":424},"child":[{"type":"Image","props":{"y":401,"x":59,"width":263,"visible":false,"var":"jiqi_3","skin":"factory/shengchan_jiqi_1_3_gaoji.png","pivotY":263,"pivotX":0,"height":263}},{"type":"Image","props":{"y":121,"x":6,"visible":false,"var":"jiqi_2","skin":"factory/shengchan_jiqi_1_2_zhongji.png"}},{"type":"Image","props":{"y":169,"x":74,"var":"jiqi_1","skin":"factory/shengchan_jiqi_1_1_chuji.png"}},{"type":"Label","props":{"y":389,"x":114,"width":151,"var":"jiqi_name","valign":"middle","height":32,"fontSize":26,"font":"SimHei","color":"#6a3806","align":"center"}}]},{"type":"Box","props":{"y":-5,"x":378,"width":362,"height":429},"child":[{"type":"Image","props":{"y":5,"x":-4,"width":363,"skin":"factory/shengchandiban_1219.png","sizeGrid":"35,25,35,25","name":"bg","height":419}},{"type":"Box","props":{"y":45,"x":9,"var":"cao0"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png","name":"bg"}},{"type":"Image","props":{"y":7,"x":7,"skin":"factory/shengchan_+.png","name":"jia"}},{"type":"Image","props":{"y":7,"x":7,"width":90,"name":"icon","height":90}},{"type":"Image","props":{"y":0,"x":2,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected"}}]},{"type":"Box","props":{"y":45,"x":126,"visible":false,"var":"cao1"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png","name":"bg"}},{"type":"Image","props":{"y":7,"x":7,"skin":"factory/shengchan_+.png","name":"jia"}},{"type":"Image","props":{"y":8,"x":8,"width":90,"name":"icon","height":90}},{"type":"Image","props":{"y":0,"x":2,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected"}}]},{"type":"Box","props":{"y":45,"x":242,"visible":false,"var":"cao2"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png","name":"bg"}},{"type":"Image","props":{"y":7,"x":7,"skin":"factory/shengchan_+.png","name":"jia"}},{"type":"Image","props":{"y":8,"x":8,"width":90,"name":"icon","height":90}},{"type":"Image","props":{"y":0,"x":2,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected"}}]},{"type":"Box","props":{"y":150,"x":6,"name":"time"},"child":[{"type":"Image","props":{"y":12,"x":63,"width":172,"skin":"agingroom/shijian_5.png","sizeGrid":"10,15,10,15","height":26}},{"type":"ProgressBar","props":{"y":18,"x":73,"width":153,"var":"time_progress","value":0,"skin":"agingroom/progress.png","sizeGrid":"0,5,0,3","height":12}},{"type":"Image","props":{"y":7,"x":43,"skin":"bakeroom/zhong.png"}},{"type":"Label","props":{"y":14,"x":0,"text":"时间","fontSize":22,"color":"#411b0a","bold":true}},{"type":"Label","props":{"y":12,"x":233,"width":114,"visible":false,"var":"time_countdown","valign":"middle","height":26,"fontSize":16,"color":"#411b0a","bold":true,"align":"center"}}]},{"type":"Box","props":{"y":201,"x":88,"name":"yan"},"child":[{"type":"Image","props":{"skin":"factory/shengchandiban_19.png"}},{"type":"Label","props":{"y":10,"x":8,"width":170,"var":"yan_name","valign":"middle","height":30,"fontSize":22,"font":"SimHei","color":"#411b0a","align":"center"}},{"type":"Image","props":{"y":51,"x":48,"width":90,"var":"yan_icon","height":90}}]},{"type":"Box","props":{"y":367,"x":42,"name":"btn"},"child":[{"type":"Button","props":{"y":-1,"x":141,"width":137,"var":"btn_srart","stateNum":"2","skin":"factory/jian.png","labelStrokeColor":"#795501","labelStroke":3,"labelSize":24,"labelPadding":"0,0,2,0","labelFont":"SimHei","labelColors":"#fffef4,#fffef4,#fffef4,#fffef4","label":"开始生产","height":57}},{"type":"Button","props":{"y":3,"x":144,"width":132,"visible":false,"var":"btn_lingqu","stateNum":"2","skin":"bakeroom/button_lingqu.png","height":46}},{"type":"Button","props":{"y":0,"x":2,"width":107,"var":"btn_speedup","stateNum":"2","skin":"bakeroom/button_jiashu.png","height":47},"child":[{"type":"Label","props":{"y":10,"x":57,"width":28,"var":"need_ledou","valign":"middle","text":"0","height":29,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"center"}}]}]}]}]}]},{"type":"Image","props":{"y":0,"x":0,"skin":"factory/bg.png","name":"bg","mouseThrough":true,"cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Image","props":{"y":505,"x":178,"skin":"factory/shuoming_jiagong.png"}}]},{"type":"Button","props":{"y":10,"x":880,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Button","props":{"y":42,"x":798,"width":68,"var":"help_btn","stateNum":"2","skin":"ui/button_help.png","height":36}},{"type":"Button","props":{"y":43,"x":688,"var":"ck_btn","stateNum":"2","skin":"factory/ck_btn.png"}}]};}
		]);
		return JGCDialogUI;
	})(Dialog);
var JGCPeifangUI=(function(_super){
		function JGCPeifangUI(){
			
		    this.tab=null;
		    this.PF_List=null;
		    this.explain=null;
		    this.CL_List=null;
		    this.ok_btn=null;

			JGCPeifangUI.__super.call(this);
		}

		CLASS$(JGCPeifangUI,'ui.JGCPeifangUI',_super);
		var __proto__=JGCPeifangUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(JGCPeifangUI.uiView);
		}

		STATICATTR$(JGCPeifangUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Box","props":{"name":"bg"},"child":[{"type":"Image","props":{"y":59,"x":2,"width":912,"skin":"factory/peifang_dishe.png","sizeGrid":"5,5,5,5","height":400}},{"type":"Image","props":{"y":0,"x":0,"skin":"factory/peifang_1.png"}},{"type":"Image","props":{"y":446,"x":0,"skin":"factory/peifang_1.png"}},{"type":"Image","props":{"y":235,"x":25,"skin":"factory/tiaoxiangdiban_1219.png"}}]},{"type":"Button","props":{"y":-16,"x":875,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Box","props":{"y":24,"x":32,"width":833,"height":429},"child":[{"type":"Tab","props":{"y":-17,"x":0,"var":"tab","stateNum":2,"skin":"factory/tab.png","labels":"一星,二星,三星,四星,五星","labelSize":26,"labelPadding":"0,0,0,2","labelColors":"#74260a,#74260a","labelBold":true}},{"type":"List","props":{"y":38,"x":-5,"width":876,"visible":false,"var":"PF_List","spaceX":10,"repeatY":1,"repeatX":7,"height":158},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"factory/peifang_zhi.png"}},{"type":"Image","props":{"y":13,"x":12,"width":90,"name":"icon","height":90}},{"type":"Label","props":{"y":109,"x":-2,"wordWrap":true,"width":118,"valign":"middle","name":"name","height":45,"fontSize":18,"font":"SimHei","color":"#000000","align":"center"}},{"type":"Label","props":{"y":10,"x":7,"width":100,"valign":"middle","text":"0","name":"num","height":26,"fontSize":20,"align":"right"}},{"type":"Image","props":{"y":28,"x":24,"visible":false,"skin":"dati/dui.png","name":"gou"}},{"type":"Image","props":{"y":75,"x":66,"skin":"factory/xiaoshou.png"}}]},{"type":"Label","props":{"y":26,"x":281,"wordWrap":true,"width":313,"visible":false,"valign":"middle","text":"还未获得一星调香书，可通过商行或调香研究所获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"Label","props":{"y":183,"x":0,"width":551,"var":"explain","valign":"middle","text":"说明：此书能制作成品烟","height":40,"fontSize":24,"font":"SimHei","color":"#602c14"}},{"type":"List","props":{"y":221,"x":75,"width":769,"var":"CL_List","spaceX":20,"repeatY":1,"repeatX":4,"height":204},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"factory/tiaoxiang_wupin_1219.png"}},{"type":"Image","props":{"y":44,"x":36,"width":102,"name":"icon","height":102}},{"type":"Label","props":{"y":156,"x":6,"width":75,"valign":"middle","text":"0","name":"hasNum","height":30,"fontSize":30,"font":"SimHei","align":"right"}},{"type":"Label","props":{"y":156,"x":79,"width":5,"valign":"middle","text":"/","height":30,"fontSize":30,"font":"SimHei","align":"center"}},{"type":"Label","props":{"y":156,"x":92,"width":75,"valign":"middle","text":"0","name":"needNum","height":30,"fontSize":30,"font":"SimHei","align":"left"}},{"type":"Label","props":{"y":8,"x":8,"wordWrap":true,"width":161,"valign":"middle","name":"name","height":32,"fontSize":16,"color":"#915832","align":"center"}},{"type":"Image","props":{"y":71,"x":55,"visible":false,"skin":"dati/dui.png","name":"gou"}}]}]},{"type":"Button","props":{"y":434,"x":347,"var":"ok_btn","stateNum":"2","skin":"dati/button_queding.png"}}]}]};}
		]);
		return JGCPeifangUI;
	})(Dialog);
var jiandieUI=(function(_super){
		function jiandieUI(){
			
		    this.btn_guyong=null;

			jiandieUI.__super.call(this);
		}

		CLASS$(jiandieUI,'ui.jiandieUI',_super);
		var __proto__=jiandieUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(jiandieUI.uiView);
		}

		STATICATTR$(jiandieUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"jiandie/jiandie_xuan.png"}},{"type":"Button","props":{"y":365,"x":241,"var":"btn_guyong","stateNum":"2","skin":"jiandie/guyong.png"}},{"type":"Button","props":{"y":-25,"x":592,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}}]};}
		]);
		return jiandieUI;
	})(Dialog);
var jiandie_infoUI=(function(_super){
		function jiandie_infoUI(){
			
		    this.tab_paiqian=null;
		    this.tab_chakan=null;
		    this.ViewStack=null;
		    this.FriendList=null;
		    this.btn_paiqian=null;
		    this.ShouHuoList=null;
		    this.btn_shouqu=null;
		    this.CountDown=null;

			jiandie_infoUI.__super.call(this);
		}

		CLASS$(jiandie_infoUI,'ui.jiandie_infoUI',_super);
		var __proto__=jiandie_infoUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(jiandie_infoUI.uiView);
		}

		STATICATTR$(jiandie_infoUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Button","props":{"y":73,"x":788,"var":"tab_paiqian","toggle":true,"stateNum":"2","skin":"jiandie/paiqian_tab.png"}},{"type":"Button","props":{"y":245,"x":789,"var":"tab_chakan","toggle":true,"stateNum":"2","skin":"jiandie/cakan_tab.png"}},{"type":"Image","props":{"skin":"jiandie/jiandie_diban.png"}},{"type":"ViewStack","props":{"y":104,"x":239,"var":"ViewStack","selectedIndex":0},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"item0"},"child":[{"type":"List","props":{"y":0,"x":0,"width":490,"var":"FriendList","repeatY":3,"repeatX":1,"height":383},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"jiandie/jiandie_dikuang.png"}},{"type":"Image","props":{"y":24,"x":17,"skin":"jiandie/touxiangkuang_2.png"}},{"type":"Image","props":{"y":33,"x":23,"width":60,"name":"img","height":60}},{"type":"Image","props":{"y":24,"x":17,"skin":"jiandie/touxiangkuang_1.png"}},{"type":"Label","props":{"y":33,"x":102,"wordWrap":true,"width":285,"valign":"middle","overflow":"hidden","name":"name","height":60,"fontSize":26,"font":"SimHei","color":"#6a3906"}},{"type":"CheckBox","props":{"y":26,"x":386,"skin":"lubiantan/gou_1_01-02.png","name":"Check"}}]}]},{"type":"Button","props":{"y":441,"x":167,"var":"btn_paiqian","stateNum":"2","skin":"jiandie/jiandie_paiqian.png"}},{"type":"Image","props":{"y":-44,"x":-3,"skin":"jiandie/jiandie_biaoti_1.png"}}]},{"type":"Box","props":{"name":"item1"},"child":[{"type":"List","props":{"width":490,"var":"ShouHuoList","repeatY":3,"repeatX":1,"height":383},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"jiandie/jiandie_dikuang.png"}},{"type":"Image","props":{"y":24,"x":17,"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":27,"x":21,"width":71,"name":"img","height":71}},{"type":"Label","props":{"y":15,"x":102,"wordWrap":true,"width":376,"valign":"middle","overflow":"hidden","name":"name","leading":5,"height":60,"fontSize":22,"font":"SimHei","color":"#6a3906"}},{"type":"Label","props":{"y":70,"x":108,"width":371,"valign":"middle","name":"time","height":38,"fontSize":22,"font":"SimHei","color":"#6a3906"}}]}]},{"type":"Button","props":{"y":440,"x":168,"var":"btn_shouqu","stateNum":"2","skin":"jiandie/jiandie_shouqu.png"}}]}]},{"type":"Label","props":{"y":499,"x":389,"width":261,"var":"CountDown","valign":"middle","height":30,"fontSize":24,"font":"SimHei","color":"#c89f6a","align":"center"}},{"type":"Button","props":{"y":-16,"x":758,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}}]};}
		]);
		return jiandie_infoUI;
	})(Dialog);
var JiangChiUI=(function(_super){
		function JiangChiUI(){
			
		    this.jifen_num=null;
		    this.chuji_btn=null;
		    this.zhongji_btn=null;
		    this.gaoji_btn=null;
		    this.duihuan=null;
		    this.help_btn=null;
		    this.goto_btn=null;
		    this.tips=null;
		    this.mask_1=null;
		    this.mask_2=null;
		    this.mask_3=null;
		    this.mask_4=null;
		    this.tips_1=null;
		    this.tips_2=null;
		    this.tips_3=null;
		    this.tips_4=null;
		    this.tips_4_ele=null;

			JiangChiUI.__super.call(this);
		}

		CLASS$(JiangChiUI,'ui.JiangChiUI',_super);
		var __proto__=JiangChiUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(JiangChiUI.uiView);
		}

		STATICATTR$(JiangChiUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"luckdraw/choujiang.png"}},{"type":"Label","props":{"y":569,"x":496,"width":115,"var":"jifen_num","valign":"middle","text":"0","height":30,"fontSize":26,"font":"SimHei","color":"#ffffff","align":"left"}},{"type":"Button","props":{"y":481,"x":200,"var":"chuji_btn","stateNum":"2","skin":"luckdraw/qianwang.png"}},{"type":"Button","props":{"y":480,"x":440,"var":"zhongji_btn","stateNum":"2","skin":"luckdraw/qianwang.png"}},{"type":"Button","props":{"y":480,"x":681,"var":"gaoji_btn","stateNum":"2","skin":"luckdraw/qianwang.png"}},{"type":"Button","props":{"y":123,"x":901,"width":52,"stateNum":"2","skin":"ui/guanbi.png","name":"close","height":58}},{"type":"Button","props":{"y":535,"x":398,"var":"duihuan","stateNum":"2","skin":"luckdraw/jifen.png"}},{"type":"Button","props":{"y":558,"x":779,"var":"help_btn","stateNum":"2","skin":"luckdraw/btn_help.png"}},{"type":"Button","props":{"y":558,"x":89,"var":"goto_btn","stateNum":"2","skin":"luckdraw/btn_goto_dingdan.png"}},{"type":"Sprite","props":{"y":0,"x":0,"width":960,"visible":false,"var":"tips","height":606,"cacheAs":"bitmap"},"child":[{"type":"Sprite","props":{"y":0,"x":0,"visible":false,"alpha":0.5},"child":[{"type":"Rect","props":{"y":0,"x":0,"width":960,"lineWidth":1,"height":606,"fillColor":"#000000"}}]},{"type":"Sprite","props":{"y":183,"x":141,"visible":false,"var":"mask_1","alpha":0.1},"child":[{"type":"Rect","props":{"y":0,"x":0,"width":211,"lineWidth":1,"height":341,"fillColor":"#ff0000"}}]},{"type":"Sprite","props":{"y":519,"x":333,"visible":false,"var":"mask_2","alpha":0.1},"child":[{"type":"Rect","props":{"y":0,"x":0,"width":279,"lineWidth":1,"height":87,"fillColor":"#ff0000"}}]},{"type":"Sprite","props":{"y":183,"x":379,"visible":false,"var":"mask_3","alpha":0.1},"child":[{"type":"Rect","props":{"width":211,"lineWidth":1,"height":341,"fillColor":"#ff0000"}}]},{"type":"Sprite","props":{"y":183,"x":620,"visible":false,"var":"mask_4","alpha":0.1},"child":[{"type":"Rect","props":{"width":211,"lineWidth":1,"height":341,"fillColor":"#ff0000"}}]},{"type":"Image","props":{"y":119,"x":301,"visible":false,"var":"tips_1","skin":"zhiyin/zhiying_qipao_1-17.png","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Label","props":{"y":32,"x":89,"text":"初级奖池","fontSize":20,"font":"SimHei","color":"#ff0400"}},{"type":"Label","props":{"y":32,"x":168,"wordWrap":true,"valign":"middle","text":"需要使用","fontSize":20,"font":"SimHei","color":"#ffffff","align":"center"}},{"type":"Label","props":{"y":32,"x":248,"text":"三星及以下","fontSize":20,"font":"SimHei","color":"#ff0400"}},{"type":"Label","props":{"y":53,"x":97,"text":"香烟","fontSize":20,"font":"SimHei","color":"#ffffff"}},{"type":"Label","props":{"y":53,"x":137,"text":"兑换成积分","fontSize":20,"font":"SimHei","color":"#ff0400"}},{"type":"Label","props":{"y":53,"x":235,"text":"后参与抽奖！","fontSize":20,"font":"SimHei","color":"#ffffff"}}]},{"type":"Image","props":{"y":405,"x":480,"visible":false,"var":"tips_2","skin":"zhiyin/zhiying_qipao_1-17.png","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Label","props":{"y":31,"x":140,"valign":"middle","text":"点击这里可以进入","fontSize":20,"font":"SimHei","color":"#ffffff"}},{"type":"Label","props":{"y":55,"x":158,"text":"积分兑换","fontSize":20,"font":"SimHei","color":"#ff0400"}},{"type":"Label","props":{"y":55,"x":240,"text":"界面","fontSize":20,"font":"SimHei","color":"#ffffff"}}]},{"type":"Image","props":{"y":119,"x":524,"visible":false,"var":"tips_3","skin":"zhiyin/zhiying_qipao_1-17.png","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"HTMLDivElement","props":{"y":22,"x":84,"width":280,"innerHTML":"<div style=\"fontSize:20;color:#ffffff;width:280;height:auto;align:center;\"><span color=\"#FF0000\">中级奖池</span>需要使用<br/><span color=\"#FF0000\">四星香烟</span>换取抽奖机会，<br/>有机会获得<span color=\"#FF0000\">品吸机会代金券</span>！</div>","height":61}}]},{"type":"Image","props":{"y":120,"x":688,"visible":false,"var":"tips_4","skin":"zhiyin/zhiying_qipao_1-17.png","skewY":180,"cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Label","props":{"y":57,"x":219,"wordWrap":true,"width":231,"visible":false,"valign":"middle","text":"高级奖池需要使用五星香烟换取抽奖机会，有更大机会获得香烟抵价券！","skewY":180,"height":77,"fontSize":20,"font":"SimHei","color":"#ffffff","anchorY":0.5,"anchorX":0.5,"align":"center"}},{"type":"HTMLDivElement","props":{"y":22,"x":357,"width":277,"var":"tips_4_ele","innerHTML":"<div style=\"fontSize:20;color:#ffffff;width:280;height:auto;align:center;\"><span color=\"#FF0000\">高级奖池</span>需要使用<br/><span color=\"#FF0000\">五星香烟</span>换取抽奖机会，有更<br/>大机会获得<span color=\"#FF0000\">品吸机会代金券</span>！</div>","height":68}}]}]}]};}
		]);
		return JiangChiUI;
	})(Dialog);
var JiFenDuiHuanUI=(function(_super){
		function JiFenDuiHuanUI(){
			
		    this.tab=null;
		    this.ViewStack=null;
		    this.sanxing_list=null;
		    this.erxing_list=null;
		    this.yixing_list=null;
		    this.duihuan_btn=null;
		    this.qianwang_btn=null;
		    this.jifen_all=null;
		    this.jifen_curr=null;

			JiFenDuiHuanUI.__super.call(this);
		}

		CLASS$(JiFenDuiHuanUI,'ui.JiFenDuiHuanUI',_super);
		var __proto__=JiFenDuiHuanUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(JiFenDuiHuanUI.uiView);
		}

		STATICATTR$(JiFenDuiHuanUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"luckdraw/duihuan_bg.png","cacheAsBitmap":true,"cacheAs":"bitmap"}},{"type":"Tab","props":{"y":115,"x":174,"var":"tab","stateNum":2,"space":10,"skin":"luckdraw/tab_commom.png","selectedIndex":0,"labels":"一星,二星,三星","labelSize":24,"labelColors":"#672416,#672416","labelBold":true}},{"type":"ViewStack","props":{"y":154,"x":173,"width":610,"var":"ViewStack","selectedIndex":0,"height":133},"child":[{"type":"List","props":{"y":0,"x":0,"width":609,"var":"sanxing_list","spaceX":7,"repeatY":1,"repeatX":7,"name":"item2","height":133,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Box","props":{"y":3,"x":7,"name":"render"},"child":[{"type":"Image","props":{"y":-2,"x":9,"skin":"luckdraw/item_bg.png"}},{"type":"Image","props":{"y":4,"x":14,"width":82,"name":"icon","height":82}},{"type":"Image","props":{"y":-1,"x":9,"visible":false,"skin":"luckdraw/item_selected.png","name":"selected"}},{"type":"Label","props":{"y":88,"x":0,"wordWrap":true,"width":110,"valign":"middle","name":"name","height":33,"fontSize":16,"font":"SimHei","color":"#f9f9f9","align":"center"}},{"type":"Label","props":{"y":62,"x":50,"width":44,"valign":"middle","strokeColor":"#000000","stroke":2,"name":"num","height":20,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}}]}]},{"type":"List","props":{"width":609,"var":"erxing_list","spaceX":7,"repeatY":1,"repeatX":7,"name":"item1","height":133,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Box","props":{"y":3,"x":7,"name":"render"},"child":[{"type":"Image","props":{"y":-2,"x":9,"skin":"luckdraw/item_bg.png"}},{"type":"Image","props":{"y":4,"x":14,"width":82,"name":"icon","height":82}},{"type":"Image","props":{"y":-1,"x":9,"visible":false,"skin":"luckdraw/item_selected.png","name":"selected"}},{"type":"Label","props":{"y":90,"x":0,"wordWrap":true,"width":110,"valign":"middle","name":"name","height":33,"fontSize":16,"font":"SimHei","color":"#f9f9f9","align":"center"}},{"type":"Label","props":{"y":62,"x":50,"width":44,"valign":"middle","strokeColor":"#000000","stroke":2,"name":"num","height":20,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}}]}]},{"type":"List","props":{"width":609,"var":"yixing_list","spaceX":7,"repeatY":1,"repeatX":7,"name":"item0","height":133,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Box","props":{"y":3,"x":7,"name":"render"},"child":[{"type":"Image","props":{"y":-2,"x":9,"skin":"luckdraw/item_bg.png"}},{"type":"Image","props":{"y":4,"x":14,"width":82,"name":"icon","height":82}},{"type":"Image","props":{"y":-1,"x":9,"visible":false,"skin":"luckdraw/item_selected.png","name":"selected"}},{"type":"Label","props":{"y":90,"x":0,"wordWrap":true,"width":110,"valign":"middle","name":"name","height":33,"fontSize":16,"font":"SimHei","color":"#f9f9f9","align":"center"}},{"type":"Label","props":{"y":62,"x":50,"width":44,"valign":"middle","strokeColor":"#000000","stroke":2,"name":"num","height":20,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}}]}]}]},{"type":"Button","props":{"y":514,"x":684,"var":"duihuan_btn","stateNum":"2","skin":"luckdraw/btn_duihuan.png"}},{"type":"Button","props":{"y":514,"x":176,"var":"qianwang_btn","stateNum":"2","skin":"luckdraw/btn_back.png"}},{"type":"Label","props":{"y":284,"x":610,"width":122,"var":"jifen_all","valign":"middle","text":"0","height":30,"fontSize":26,"font":"SimHei","color":"#ff0300","align":"center"}},{"type":"Label","props":{"y":284,"x":280,"width":122,"var":"jifen_curr","valign":"middle","text":"0","height":30,"fontSize":26,"font":"SimHei","color":"#ff0300","align":"center"}},{"type":"Button","props":{"y":39,"x":884,"width":52,"stateNum":"2","skin":"ui/guanbi.png","name":"close","height":58}}]};}
		]);
		return JiFenDuiHuanUI;
	})(Dialog);
var kumaUI=(function(_super){
		function kumaUI(){
			
		    this.list=null;
		    this.lingqu_btn=null;
		    this.tips_right=null;
		    this.tips_left=null;

			kumaUI.__super.call(this);
		}

		CLASS$(kumaUI,'ui.kumaUI',_super);
		var __proto__=kumaUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(kumaUI.uiView);
		}

		STATICATTR$(kumaUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"width":542,"skin":"ui/confirm_bg.png","sizeGrid":"35,50,20,50","height":297}},{"type":"Label","props":{"y":18,"x":93,"width":356,"valign":"middle","text":"道具礼包领取","strokeColor":"#000000","height":40,"fontSize":24,"font":"SimHei","color":"#58280e","align":"center"}},{"type":"Label","props":{"y":73,"x":191,"text":"乐豆中心道具兑换","fontSize":20,"font":"SimHei","color":"#c84614"}},{"type":"List","props":{"y":115,"x":126,"width":290,"var":"list","repeatY":1,"repeatX":6,"height":130,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":91,"skin":"bakeroom/kuang.png","height":91}},{"type":"Image","props":{"y":7,"x":7,"width":76,"name":"icon","height":76}},{"type":"Label","props":{"y":91,"x":4,"wordWrap":true,"width":82,"valign":"middle","text":"五星津巴布韦种子","name":"item_name","height":39,"fontSize":16,"font":"SimHei","color":"#823836","align":"center"}}]}]},{"type":"Button","props":{"y":256,"x":201,"var":"lingqu_btn","stateNum":"2","skin":"ui/button_queding.png"}},{"type":"Image","props":{"y":143,"x":420,"visible":false,"var":"tips_right","skin":"ui/3jiaojiantou.png"}},{"type":"Image","props":{"y":143,"x":125,"visible":false,"var":"tips_left","skin":"ui/3jiaojiantou.png","skewY":180}}]};}
		]);
		return kumaUI;
	})(Dialog);
var kuorongUI=(function(_super){
		function kuorongUI(){
			

			kuorongUI.__super.call(this);
		}

		CLASS$(kuorongUI,'ui.kuorongUI',_super);
		var __proto__=kuorongUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(kuorongUI.uiView);
		}

		STATICATTR$(kuorongUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":960,"height":640},"child":[{"type":"Image","props":{"y":179,"x":292,"skin":"depot/kuorong.png"}}]};}
		]);
		return kuorongUI;
	})(Dialog);
var landUI=(function(_super){
		function landUI(){
			
		    this.land1=null;

			landUI.__super.call(this);
		}

		CLASS$(landUI,'ui.landUI',_super);
		var __proto__=landUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(landUI.uiView);
		}

		STATICATTR$(landUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":125,"height":64},"child":[{"type":"Image","props":{"y":0,"x":0,"var":"land1","skin":"tex/land_lv_0.png"},"child":[{"type":"Poly","props":{"y":37,"x":-41,"renderType":"hit","points":"41,-6,107,-40,172,-8,105,28","lineWidth":1,"lineColor":"#ff0000","fillColor":"#00ffff"}}]}]};}
		]);
		return landUI;
	})(View);
var LaxinUI=(function(_super){
		function LaxinUI(){
			
		    this.btn_bg_0=null;
		    this.btn_bg_1=null;
		    this.btn_bg_2=null;
		    this.yq_num=null;
		    this.quan_num=null;
		    this.has_num=null;
		    this.inreo_btn=null;
		    this.zhaoji_btn=null;
		    this.left_side_menu=null;
		    this.ViewStack=null;
		    this.friend_list=null;
		    this.zhaoji_list=null;
		    this.my_list_up=null;
		    this.my_list_down=null;
		    this.newer_list=null;
		    this.newer_up=null;
		    this.newer_down=null;
		    this.exchange_btn=null;
		    this.exchange_list=null;

			LaxinUI.__super.call(this);
		}

		CLASS$(LaxinUI,'ui.LaxinUI',_super);
		var __proto__=LaxinUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(LaxinUI.uiView);
		}

		STATICATTR$(LaxinUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"laxin/laxin_diban.png"}},{"type":"Image","props":{"y":176,"x":73,"var":"btn_bg_0","skin":"laxin/biantiao_2.png"}},{"type":"Image","props":{"y":290,"x":73,"var":"btn_bg_1","skin":"laxin/biantiao_2.png"}},{"type":"Image","props":{"y":404,"x":73,"var":"btn_bg_2","skin":"laxin/biantiao_2.png"}},{"type":"Box","props":{"y":131,"x":122,"name":"dataview"},"child":[{"type":"Box","props":{"y":0,"x":0},"child":[{"type":"Image","props":{"skin":"laxin/laxin_shujudiban.png"}},{"type":"Label","props":{"y":2,"x":2,"width":112,"valign":"middle","text":"已邀请好友:","height":30,"fontSize":20,"font":"SimHei","color":"#bf9971"}},{"type":"Label","props":{"y":2,"x":116,"width":80,"var":"yq_num","valign":"middle","text":"0人","height":30,"fontSize":20,"font":"SimHei","color":"#f7e674"}}]},{"type":"Box","props":{"y":0,"x":214},"child":[{"type":"Image","props":{"skin":"laxin/laxin_shujudiban.png"}},{"type":"Label","props":{"y":2,"x":3,"width":112,"valign":"middle","text":"已获得奖券:","height":30,"fontSize":20,"font":"SimHei","color":"#bf9971"}},{"type":"Label","props":{"y":2,"x":117,"width":80,"var":"quan_num","valign":"middle","text":"0张","height":30,"fontSize":20,"font":"SimHei","color":"#f7e674"}}]},{"type":"Box","props":{"y":0,"x":428},"child":[{"type":"Image","props":{"skin":"laxin/laxin_shujudiban.png"}},{"type":"Label","props":{"y":2,"x":96,"width":101,"var":"has_num","valign":"middle","text":"0张","height":30,"fontSize":20,"font":"SimHei","color":"#f7e674"}},{"type":"Label","props":{"y":2,"x":3,"width":93,"valign":"middle","text":"当前奖券:","height":30,"fontSize":20,"font":"SimHei","color":"#bf9971"}}]}]},{"type":"Box","props":{"y":32,"x":613,"name":"intro"},"child":[{"type":"Image","props":{"y":44,"x":25,"skin":"laxin/biantiao_1.png"}},{"type":"Button","props":{"var":"inreo_btn","stateNum":"2","skin":"laxin/tab_huodongshuoming.png"}}]},{"type":"Box","props":{"y":33,"x":742,"name":"zhaoji"},"child":[{"type":"Image","props":{"y":43,"x":26,"skin":"laxin/biantiao_1.png"}},{"type":"Button","props":{"var":"zhaoji_btn","stateNum":"2","skin":"laxin/tab_zhaoji.png"}}]},{"type":"Tab","props":{"y":152,"x":34,"var":"left_side_menu","selectedIndex":0,"direction":"vertical"},"child":[{"type":"Button","props":{"y":0,"x":0,"width":44,"stateNum":"2","skin":"laxin/tab_wodezhaoji.png","name":"item0","height":114}},{"type":"Button","props":{"y":114,"x":0,"width":44,"stateNum":"2","skin":"laxin/tab_wodejindu.png","name":"item1","height":114}},{"type":"Button","props":{"y":228,"x":0,"width":44,"stateNum":"2","skin":"laxin/tab_jiangliduihuan.png","name":"item2","height":114}}]},{"type":"Button","props":{"y":69,"x":865,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"ViewStack","props":{"y":166,"x":106,"width":790,"var":"ViewStack","selectedIndex":0,"height":360},"child":[{"type":"Box","props":{"width":790,"name":"item0","height":360},"child":[{"type":"Image","props":{"y":4,"x":132,"width":651,"skin":"laxin/laxin_kuang_3.png","sizeGrid":"15,15,15,15","height":348}},{"type":"Image","props":{"y":-4,"x":9,"skin":"laxin/laxin_kuang_4.png"}},{"type":"Label","props":{"y":97,"x":105,"wordWrap":true,"width":26,"text":"选择好友查看奖励","height":165,"fontSize":20,"font":"SimHei","color":"#9c2708","align":"center"}},{"type":"List","props":{"y":25,"x":14,"width":90,"var":"friend_list","vScrollBarSkin":"ui/vscroll.png","spaceY":5,"repeatY":3,"repeatX":1,"height":240},"child":[{"type":"Box","props":{"y":0,"x":9,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":72,"skin":"friend/haoyou_2.png","height":78}},{"type":"Image","props":{"y":9,"x":13,"width":47,"name":"header","height":44}},{"type":"Image","props":{"y":6,"x":9,"width":55,"skin":"friend/touxiang_2_2.png","height":50}},{"type":"Label","props":{"y":56,"x":1,"width":70,"valign":"middle","overflow":"hidden","name":"nickname","height":20,"fontSize":14,"font":"SimHei","align":"center"}},{"type":"Image","props":{"y":-6,"x":-2,"width":36,"skin":"ui/xing1.png","height":32}},{"type":"Label","props":{"y":5,"x":10,"width":14,"valign":"middle","text":"0","strokeColor":"#000000","stroke":2,"name":"lv","height":14,"fontSize":14,"font":"SimHei","color":"#ffffff","align":"center"}}]}]},{"type":"List","props":{"y":23,"x":138,"width":640,"var":"zhaoji_list","vScrollBarSkin":"ui/vscroll.png","spaceY":20,"repeatY":3,"repeatX":1,"height":314},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"skin":"laxin/laxin_renwulian_1.png"}},{"type":"Button","props":{"y":22,"x":539,"width":104,"stateNum":"1","skin":"laxin/yilingqu_2.png","name":"btn_lingqu","height":45}},{"type":"Image","props":{"y":20,"x":462,"skin":"laxin/laxin_jiangquan.png","name":"icon"}},{"type":"Label","props":{"y":55,"x":493,"width":30,"valign":"middle","text":"0","name":"num","height":20,"fontSize":16,"font":"SimHei","color":"#ffdc94","align":"right"}},{"type":"Label","props":{"y":17,"x":23,"width":77,"valign":"middle","text":"领取条件1：","name":"title","height":20,"fontSize":14,"font":"SimHei","color":"#d87c33"}},{"type":"Label","props":{"y":17,"x":100,"width":354,"valign":"middle","name":"content","height":20,"fontSize":14,"font":"SimHei","color":"#ffec93"}},{"type":"Image","props":{"y":43,"x":81,"width":316,"skin":"laxin/bar_progress.png","sizeGrid":"15,20,15,20","name":"jindu","height":30}},{"type":"Image","props":{"y":43,"x":80,"skin":"laxin/bg_progress.png"}}]}]},{"type":"Button","props":{"y":267,"x":23,"width":71,"stateNum":"2","skin":"laxin/+.png","height":73}},{"type":"Image","props":{"y":3,"x":431,"var":"my_list_up","skin":"laxin/laxin_jiantou_shang.png"}},{"type":"Image","props":{"y":332,"x":431,"var":"my_list_down","skin":"laxin/laxin_jiantou_xia.png"}}]},{"type":"Box","props":{"y":0,"x":0,"width":790,"name":"item1","height":360},"child":[{"type":"Image","props":{"y":0,"x":17,"skin":"laxin/laxin_kuang_3.png","sizeGrid":"15,15,15,15"}},{"type":"List","props":{"y":21,"x":30,"width":730,"var":"newer_list","vScrollBarSkin":"ui/vscroll.png","repeatY":3,"height":306},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"skin":"laxin/laxin_renwulian_2.png"}},{"type":"Button","props":{"y":23,"x":603,"stateNum":"1","skin":"laxin/yilingqu_2.png","name":"btn_lingqu"}},{"type":"Label","props":{"y":18,"x":23,"width":95,"valign":"middle","text":"达成条件1:","name":"title","height":24,"fontSize":18,"font":"SimHei","color":"#d87c33"}},{"type":"Label","props":{"y":18,"x":120,"width":158,"valign":"middle","text":"游戏等级达到1级","name":"content","height":24,"fontSize":18,"font":"SimHei","color":"#ffec93"}},{"type":"Image","props":{"y":47,"x":39,"width":220,"skin":"laxin/bar_progress.png","sizeGrid":"15,20,15,20","name":"jindu","height":30}},{"type":"Image","props":{"y":47,"x":38,"width":222,"skin":"laxin/bg_progress.png","height":30}},{"type":"Image","props":{"y":22,"x":312,"width":50,"name":"icon1","height":50}},{"type":"Image","props":{"y":22,"x":406,"width":50,"name":"icon2","height":50}},{"type":"Image","props":{"y":22,"x":500,"width":50,"name":"icon3","height":50}},{"type":"Label","props":{"y":59,"x":333,"width":38,"valign":"middle","strokeColor":"#000000","stroke":2,"name":"num1","height":23,"fontSize":18,"font":"SimHei","color":"#ffec93","align":"right"}},{"type":"Label","props":{"y":59,"x":427,"width":38,"valign":"middle","strokeColor":"#000000","stroke":2,"name":"num2","height":23,"fontSize":18,"font":"SimHei","color":"#ffec93","align":"right"}},{"type":"Label","props":{"y":59,"x":521,"width":38,"valign":"middle","strokeColor":"#000000","stroke":2,"name":"num3","height":23,"fontSize":18,"font":"SimHei","color":"#ffec93","align":"right"}}]}]},{"type":"Image","props":{"y":3,"x":368,"var":"newer_up","skin":"laxin/laxin_jiantou_shang.png"}},{"type":"Image","props":{"y":327,"x":368,"var":"newer_down","skin":"laxin/laxin_jiantou_xia.png"}}]},{"type":"Box","props":{"width":790,"name":"item2","height":360},"child":[{"type":"Image","props":{"y":18,"x":1,"width":787,"skin":"laxin/laxin_kuang_3.png","sizeGrid":"15,15,15,15","height":224}},{"type":"Button","props":{"y":273,"x":323,"var":"exchange_btn","stateNum":"2","skin":"laxin/duihuan.png"}},{"type":"List","props":{"y":24,"x":6,"width":779,"var":"exchange_list","spaceX":2,"selectEnable":true,"repeatY":1,"repeatX":21,"height":214,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Box","props":{"y":11,"x":0,"name":"render"},"child":[{"type":"Image","props":{"width":154,"skin":"laxin/wupinkuang.png","height":191}},{"type":"Image","props":{"y":0,"x":0,"width":154,"visible":false,"skin":"laxin/wupinkuang_s.png","name":"bg_selected","height":191}},{"type":"Image","props":{"y":21,"x":29,"width":96,"name":"icon","height":96}},{"type":"Label","props":{"y":87,"x":74,"width":59,"valign":"middle","text":"x0","strokeColor":"#ff0400","stroke":3,"name":"num","height":24,"fontSize":24,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":119,"x":25,"width":50,"valign":"middle","text":"0","name":"need_quan","height":23,"fontSize":16,"font":"SimHei","color":"#eaff00","align":"center"}},{"type":"Image","props":{"y":113,"x":78,"width":32,"skin":"laxin/laxin_jiangquan.png","height":36}},{"type":"Label","props":{"y":146,"x":22,"width":110,"valign":"middle","text":"剩余数量:0","name":"sy_num","height":30,"fontSize":16,"font":"SimHei","color":"#463512"}}]}]}]}]}]};}
		]);
		return LaxinUI;
	})(Dialog);
var LaxinDialogUI=(function(_super){
		function LaxinDialogUI(){
			

			LaxinDialogUI.__super.call(this);
		}

		CLASS$(LaxinDialogUI,'ui.LaxinDialogUI',_super);
		var __proto__=LaxinDialogUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(LaxinDialogUI.uiView);
		}

		STATICATTR$(LaxinDialogUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"width":693,"skin":"laxin/tianchuang.png","sizeGrid":"50,50,50,50","height":374},"child":[{"type":"Label","props":{"y":19,"x":258,"width":176,"valign":"middle","text":"活动说明","height":39,"fontSize":26,"font":"SimHei","color":"#d87c33","align":"center"}},{"type":"Label","props":{"y":61,"x":24,"wordWrap":true,"width":645,"text":"1、活动时间：2019年9月11日0:00至2019年11月30日23:59。\\n2、仅有未注册过本游戏的用户能被成功邀请；\\n3、老玩家（完成新手指引/达到3级）可在活动界面发起邀请，邀请后选择微信好友发送邀请链接，新玩家通过链接首次进入游戏，双方自动绑定为培训关系；\\n4、每位新玩家最多可与1位老玩家绑定，绑定后不可更改；\\n5、新玩家可通过等级提升获得奖券，老玩家可通过邀请新玩家进入游戏并完成指引、徒弟等级提升获得奖券；\\n6、消耗对应奖券可以兑换奖品，每种实物奖品每名用户最多兑换1次，所有品吸机会代金券每名用户最多兑换1次，具体奖品及剩余数量见兑换界面。\\n7、奖品数量有限，先兑先得，兑完即止。\\n8、奖券将在活动结束后统一回收，请各位玩家及时兑换奖励。","leading":2,"height":292,"fontSize":20,"font":"SimHei"}}]},{"type":"Button","props":{"y":-9,"x":651,"stateNum":"2","skin":"ui/button_guanbi.png","name":"close"}}]};}
		]);
		return LaxinDialogUI;
	})(Dialog);
var LaxinZhaojiUI=(function(_super){
		function LaxinZhaojiUI(){
			

			LaxinZhaojiUI.__super.call(this);
		}

		CLASS$(LaxinZhaojiUI,'ui.LaxinZhaojiUI',_super);
		var __proto__=LaxinZhaojiUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(LaxinZhaojiUI.uiView);
		}

		STATICATTR$(LaxinZhaojiUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"laxin/laxin_zhiying.png"}},{"type":"Button","props":{"y":347,"x":345,"stateNum":"2","skin":"laxin/daqueding.png","name":"close"}}]};}
		]);
		return LaxinZhaojiUI;
	})(Dialog);
var LBT_ALLUI=(function(_super){
		function LBT_ALLUI(){
			
		    this.MarketList=null;
		    this.pre_page=null;
		    this.next_page=null;

			LBT_ALLUI.__super.call(this);
		}

		CLASS$(LBT_ALLUI,'ui.LBT_ALLUI',_super);
		var __proto__=LBT_ALLUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(LBT_ALLUI.uiView);
		}

		STATICATTR$(LBT_ALLUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"width":809,"skin":"lubiantan/zuixin_5.png","sizeGrid":"30,20,30,10","height":479}},{"type":"List","props":{"y":54,"x":89,"width":617,"visible":false,"var":"MarketList","spaceY":30,"spaceX":50,"repeatY":2,"repeatX":2,"height":347},"child":[{"type":"Box","props":{"y":14,"x":63,"name":"render"},"child":[{"type":"Image","props":{"skin":"lubiantan/zuixin_-4.png"}},{"type":"Image","props":{"y":-11,"x":35,"skin":"lubiantan/zuixin_3.png"}},{"type":"Label","props":{"y":-8,"x":40,"width":140,"valign":"middle","overflow":"hidden","name":"nickname","height":30,"fontSize":16,"color":"#f3cea4","align":"center"}},{"type":"Image","props":{"y":34,"x":27,"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":36,"x":29,"width":74,"name":"icon","height":74}},{"type":"Image","props":{"y":97,"x":109,"skin":"lubiantan/zuixin_1.png"}},{"type":"Image","props":{"y":98,"x":109,"width":30,"skin":"userinfo/lebi_big.png","height":30}},{"type":"Label","props":{"y":102,"x":140,"width":48,"valign":"middle","text":"0","strokeColor":"#3e1300","stroke":3,"name":"price","height":22,"fontSize":20,"font":"SimHei","color":"#f6d2b2","align":"left"}},{"type":"Label","props":{"y":40,"x":108,"wordWrap":true,"width":87,"valign":"middle","strokeColor":"#683404","stroke":2,"name":"name","leading":5,"height":56,"fontSize":16,"color":"#f3cea4","align":"center"}},{"type":"Label","props":{"y":115,"x":13,"text":"数量","strokeColor":"#4b1f00","stroke":1,"fontSize":20,"font":"SimHei","color":"#efa96e"}},{"type":"Label","props":{"y":113,"x":54,"width":54,"valign":"middle","text":"0","strokeColor":"#3e1300","stroke":3,"name":"num","height":24,"fontSize":20,"font":"SimHei","color":"#f6d2b2","align":"left"}}]}]},{"type":"Button","props":{"y":409,"x":254,"var":"pre_page","stateNum":"2","skin":"depot/tab_xiaofenlei.png","labelSize":16,"labelFont":"SimHei","labelColors":"#671e11,#671e11","label":"上一页"}},{"type":"Button","props":{"y":-13,"x":733,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Button","props":{"y":409,"x":439,"var":"next_page","stateNum":"2","skin":"depot/tab_xiaofenlei.png","labelSize":16,"labelFont":"SimHei","labelColors":"#671e11,#671e11","label":"下一页"}}]};}
		]);
		return LBT_ALLUI;
	})(Dialog);
var LBT_SJUI=(function(_super){
		function LBT_SJUI(){
			
		    this.tab_1=null;
		    this.view_stack=null;
		    this.List0=null;
		    this.tab_yanye3=null;
		    this.tab_yanye2=null;
		    this.tab_yanye1=null;
		    this.view_yanye=null;
		    this.List1_0=null;
		    this.List1_1=null;
		    this.List1_2=null;
		    this.List2=null;
		    this.List3=null;
		    this.List4=null;
		    this.num_sub_btn=null;
		    this.num_add_btn=null;
		    this.num=null;
		    this.price_sub_btn=null;
		    this.price_add_btn=null;
		    this.price=null;
		    this.hundred_sub_btn=null;
		    this.hundred_add_btn=null;
		    this.CheckBox1=null;
		    this.sale_btn=null;

			LBT_SJUI.__super.call(this);
		}

		CLASS$(LBT_SJUI,'ui.LBT_SJUI',_super);
		var __proto__=LBT_SJUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(LBT_SJUI.uiView);
		}

		STATICATTR$(LBT_SJUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"lubiantan/diban2.png","name":"bg"}},{"type":"Button","props":{"y":-25,"x":835,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Tab","props":{"y":11,"x":56,"var":"tab_1","stateNum":2,"space":10,"skin":"depot/tab_xiaofenlei.png","selectedIndex":0,"labels":"种子,烟叶,调香书,嘴棒","labelSize":16,"labelColors":"#671e11,#671e11","labelBold":true}},{"type":"ViewStack","props":{"y":67,"x":64,"width":425,"visible":false,"var":"view_stack","selectedIndex":0,"height":332},"child":[{"type":"List","props":{"y":0,"x":0,"width":425,"var":"List0","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":3,"repeatY":3,"repeatX":4,"name":"item0","height":332},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Label","props":{"y":76,"x":32,"width":64,"valign":"middle","text":"0","strokeColor":"#ffffff","stroke":2,"name":"num","height":20,"fontSize":20,"font":"SimHei","color":"#671e11","align":"right"}},{"type":"Image","props":{"y":-2,"x":0,"width":105,"visible":false,"skin":"lubiantan/wupingkuang_1.png","sizeGrid":"10,10,10,10","name":"selected","height":105}},{"type":"Label","props":{"y":101,"x":0,"wordWrap":true,"width":104,"valign":"middle","name":"name","height":36,"fontSize":16,"font":"SimHei","align":"center"}}]},{"type":"Label","props":{"y":129,"x":75,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得种子，可通过商行或种子培育中心获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"Box","props":{"y":-10,"x":-46,"width":477,"name":"item1","height":354},"child":[{"type":"Button","props":{"y":254,"x":-12,"var":"tab_yanye3","stateNum":"2","skin":"lubiantan/button_yanye2.png"}},{"type":"Button","props":{"y":122,"x":-13,"var":"tab_yanye2","stateNum":"2","skin":"lubiantan/button_yanye1.png"}},{"type":"Button","props":{"y":-11,"x":-12,"var":"tab_yanye1","stateNum":"2","skin":"lubiantan/button_yanye.png"}},{"type":"ViewStack","props":{"y":10,"x":46,"var":"view_yanye"},"child":[{"type":"List","props":{"width":425,"var":"List1_0","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":3,"repeatY":3,"repeatX":4,"name":"item0","height":332},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Image","props":{"y":-2,"x":0,"width":105,"visible":false,"skin":"lubiantan/wupingkuang_1.png","sizeGrid":"10,10,10,10","name":"selected","height":105}},{"type":"Label","props":{"y":76,"x":32,"width":64,"valign":"middle","text":"0","strokeColor":"#ffffff","stroke":2,"name":"num","height":20,"fontSize":20,"font":"SimHei","color":"#671e11","align":"right"}},{"type":"Label","props":{"y":101,"x":0,"wordWrap":true,"width":104,"valign":"middle","name":"name","height":36,"fontSize":16,"font":"SimHei","align":"center"}}]},{"type":"Label","props":{"y":129,"x":75,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得普通烟叶，可通过种植获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":425,"var":"List1_1","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":3,"repeatY":3,"repeatX":4,"name":"item1","height":332},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Image","props":{"y":-2,"x":0,"width":105,"visible":false,"skin":"lubiantan/wupingkuang_1.png","sizeGrid":"10,10,10,10","name":"selected","height":105}},{"type":"Label","props":{"y":76,"x":32,"width":64,"valign":"middle","text":"0","strokeColor":"#ffffff","stroke":2,"name":"num","height":20,"fontSize":20,"font":"SimHei","color":"#671e11","align":"right"}},{"type":"Label","props":{"y":101,"x":0,"wordWrap":true,"width":104,"valign":"middle","name":"name","height":36,"fontSize":16,"font":"SimHei","align":"center"}}]},{"type":"Label","props":{"y":129,"x":75,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得烘烤烟叶，可通过烘烤室获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":425,"var":"List1_2","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":3,"repeatY":3,"repeatX":4,"name":"item2","height":332},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Image","props":{"y":-2,"x":0,"width":105,"visible":false,"skin":"lubiantan/wupingkuang_1.png","sizeGrid":"10,10,10,10","name":"selected","height":105}},{"type":"Label","props":{"y":76,"x":32,"width":64,"valign":"middle","text":"0","strokeColor":"#ffffff","stroke":2,"name":"num","height":20,"fontSize":20,"font":"SimHei","color":"#671e11","align":"right"}},{"type":"Label","props":{"y":101,"x":0,"wordWrap":true,"width":104,"valign":"middle","name":"name","height":36,"fontSize":16,"font":"SimHei","align":"center"}}]},{"type":"Label","props":{"y":129,"x":75,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得醇化烟叶，可通过醇化室获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]}]}]},{"type":"List","props":{"width":425,"var":"List2","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":3,"repeatY":3,"repeatX":4,"name":"item2","height":332},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Image","props":{"y":-2,"x":0,"width":105,"visible":false,"skin":"lubiantan/wupingkuang_1.png","sizeGrid":"10,10,10,10","name":"selected","height":105}},{"type":"Label","props":{"y":76,"x":32,"width":64,"valign":"middle","text":"0","strokeColor":"#ffffff","stroke":2,"name":"num","height":20,"fontSize":20,"font":"SimHei","color":"#671e11","align":"right"}},{"type":"Label","props":{"y":101,"x":0,"wordWrap":true,"width":104,"valign":"middle","name":"name","height":36,"fontSize":16,"font":"SimHei","align":"center"}}]},{"type":"Label","props":{"y":129,"x":75,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得调香书，可通过商行或调香研究所获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":425,"var":"List3","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":3,"repeatY":3,"repeatX":4,"name":"item3","height":332},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Image","props":{"y":-2,"x":0,"width":105,"visible":false,"skin":"lubiantan/wupingkuang_1.png","sizeGrid":"10,10,10,10","name":"selected","height":105}},{"type":"Label","props":{"y":76,"x":32,"width":64,"valign":"middle","text":"0","strokeColor":"#ffffff","stroke":2,"name":"num","height":20,"fontSize":20,"font":"SimHei","color":"#671e11","align":"right"}},{"type":"Label","props":{"y":101,"x":0,"wordWrap":true,"width":104,"valign":"middle","name":"name","height":36,"fontSize":16,"font":"SimHei","align":"center"}}]},{"type":"Label","props":{"y":129,"x":75,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得嘴棒，可通过商行获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":425,"var":"List4","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":3,"repeatY":3,"repeatX":4,"name":"item4","height":332},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Image","props":{"y":-2,"x":0,"width":105,"visible":false,"skin":"lubiantan/wupingkuang_1.png","sizeGrid":"10,10,10,10","name":"selected","height":105}},{"type":"Label","props":{"y":76,"x":32,"width":64,"valign":"middle","text":"0","strokeColor":"#ffffff","stroke":2,"name":"num","height":20,"fontSize":20,"font":"SimHei","color":"#671e11","align":"right"}},{"type":"Label","props":{"y":101,"x":0,"wordWrap":true,"width":104,"valign":"middle","name":"name","height":36,"fontSize":16,"font":"SimHei","align":"center"}}]},{"type":"Label","props":{"y":129,"x":75,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得滤嘴，可通过商行获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]}]},{"type":"Box","props":{"y":102,"x":519,"width":276,"height":60},"child":[{"type":"Image","props":{"y":9,"x":134,"skin":"lubiantan/shuzhidiban_1.png"}},{"type":"Button","props":{"y":0,"x":93,"var":"num_sub_btn","stateNum":"2","skin":"depot/jian.png"}},{"type":"Button","props":{"y":0,"x":210,"var":"num_add_btn","stateNum":"2","skin":"depot/jia.png"}},{"type":"Label","props":{"y":14,"x":146,"width":75,"var":"num","valign":"middle","text":"0","strokeColor":"#ffffff","stroke":2,"height":30,"fontSize":26,"color":"#671e11","bold":true,"align":"center"}}]},{"type":"Box","props":{"y":176,"x":519,"width":276,"height":60},"child":[{"type":"Image","props":{"y":9,"x":136,"skin":"lubiantan/shuzhidiban_1.png"}},{"type":"Button","props":{"y":4,"x":100,"var":"price_sub_btn","stateNum":"2","skin":"depot/jian.png","scaleY":0.8,"scaleX":0.8}},{"type":"Button","props":{"y":4,"x":213,"var":"price_add_btn","stateNum":"2","skin":"depot/jia.png","scaleY":0.8,"scaleX":0.8}},{"type":"Label","props":{"y":14,"x":148,"width":75,"var":"price","valign":"middle","text":"0","strokeColor":"#ffffff","stroke":2,"height":30,"fontSize":26,"color":"#671e11","bold":true,"align":"center"}},{"type":"Image","props":{"y":8,"x":2,"width":44,"skin":"userinfo/lebi_big.png","height":44}},{"type":"Button","props":{"y":0,"x":46,"var":"hundred_sub_btn","stateNum":"2","skin":"depot/jian_1.png","scaleY":0.9,"scaleX":0.9}},{"type":"Button","props":{"y":0,"x":255,"var":"hundred_add_btn","stateNum":"2","skin":"depot/jia_1.png","scaleY":0.9,"scaleX":0.9}}]},{"type":"CheckBox","props":{"y":273,"x":746,"var":"CheckBox1","toggle":false,"skin":"lubiantan/gou_1_01-02.png"}},{"type":"Button","props":{"y":349,"x":580,"var":"sale_btn","stateNum":"2","skin":"lubiantan/button_chushou.png"}}]};}
		]);
		return LBT_SJUI;
	})(Dialog);
var LBT_XJUI=(function(_super){
		function LBT_XJUI(){
			
		    this.delete_btn=null;
		    this.ad_btn=null;
		    this.icon=null;
		    this.num=null;
		    this.price=null;

			LBT_XJUI.__super.call(this);
		}

		CLASS$(LBT_XJUI,'ui.LBT_XJUI',_super);
		var __proto__=LBT_XJUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(LBT_XJUI.uiView);
		}

		STATICATTR$(LBT_XJUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"lubiantan/diban.png"}},{"type":"Image","props":{"y":139,"x":268,"width":44,"skin":"userinfo/lebi_big.png","height":44}},{"type":"Button","props":{"y":225,"x":76,"var":"delete_btn","stateNum":"2","skin":"lubiantan/button_deelete.png"}},{"type":"Button","props":{"y":217,"x":244,"var":"ad_btn","stateNum":"2","skin":"lubiantan/button_guanggao.png"}},{"type":"Image","props":{"y":87,"x":157,"width":94,"var":"icon","height":94}},{"type":"Label","props":{"y":151,"x":163,"width":82,"var":"num","valign":"middle","strokeColor":"#ffffff","stroke":2,"height":26,"fontSize":20,"font":"SimHei","color":"#000000","align":"right"}},{"type":"Label","props":{"y":149,"x":313,"width":64,"var":"price","valign":"middle","height":26,"fontSize":22,"font":"SimHei","color":"#671e11","bold":true}},{"type":"Button","props":{"y":-32,"x":522,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}}]};}
		]);
		return LBT_XJUI;
	})(Dialog);
var LdChangeGiftUI=(function(_super){
		function LdChangeGiftUI(){
			
		    this.choujiang_title=null;
		    this.daoju_title=null;
		    this.btn_lingqu=null;
		    this.list=null;
		    this.tips=null;

			LdChangeGiftUI.__super.call(this);
		}

		CLASS$(LdChangeGiftUI,'ui.LdChangeGiftUI',_super);
		var __proto__=LdChangeGiftUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(LdChangeGiftUI.uiView);
		}

		STATICATTR$(LdChangeGiftUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"double11/bg.png"}},{"type":"Box","props":{"y":135,"x":188,"width":595,"height":450},"child":[{"type":"Image","props":{"y":77,"x":94,"visible":false,"var":"choujiang_title","skin":"double11/choujiangwenzi.png"}},{"type":"Image","props":{"y":77,"x":94,"visible":false,"var":"daoju_title","skin":"double11/daojuwenzi.png"}},{"type":"Button","props":{"y":351,"x":177,"var":"btn_lingqu","stateNum":"1","skin":"double11/lingqu.png"}},{"type":"List","props":{"y":150,"x":22,"width":550,"visible":false,"var":"list","spaceX":110,"repeatX":3,"height":120},"child":[{"type":"Box","props":{"y":0,"x":0,"width":110,"name":"render","height":120},"child":[{"type":"Image","props":{"y":0,"x":18,"skin":"shop/shenmikuan_1.png"}},{"type":"Label","props":{"y":76,"x":0,"wordWrap":true,"width":110,"valign":"middle","name":"item_name","leading":2,"height":40,"fontSize":16,"color":"#3a1a03","align":"center"}},{"type":"Image","props":{"y":5,"x":23,"width":64,"name":"item_icon","height":64}}]}]},{"type":"Label","props":{"y":274,"x":0,"width":595,"var":"tips","valign":"middle","text":"温馨提示：领取礼包后可进入【仓库】建筑查看。","height":28,"fontSize":16,"font":"SimHei","color":"#3a1a03","align":"center"}}]}]};}
		]);
		return LdChangeGiftUI;
	})(Dialog);
var ledouTipsUI=(function(_super){
		function ledouTipsUI(){
			
		    this.bg=null;
		    this.content=null;

			ledouTipsUI.__super.call(this);
		}

		CLASS$(ledouTipsUI,'ui.ledouTipsUI',_super);
		var __proto__=ledouTipsUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ledouTipsUI.uiView);
		}

		STATICATTR$(ledouTipsUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":95,"x":147,"var":"bg","skin":"ui/shuomingkuang_1.png","anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":40,"x":11,"wordWrap":true,"width":272,"var":"content","valign":"middle","text":"用于缩短种植、烘烤、醇化、制烟等环节的等待时间","strokeColor":"#000000","stroke":3,"leading":5,"height":137,"fontSize":26,"color":"#ffffff","align":"center"}}]};}
		]);
		return ledouTipsUI;
	})(Dialog);
var LevelUpUI=(function(_super){
		function LevelUpUI(){
			
		    this.level=null;
		    this.content=null;

			LevelUpUI.__super.call(this);
		}

		CLASS$(LevelUpUI,'ui.LevelUpUI',_super);
		var __proto__=LevelUpUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(LevelUpUI.uiView);
		}

		STATICATTR$(LevelUpUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"ui/level_up_bg.png"}},{"type":"Button","props":{"y":472,"x":190,"stateNum":"2","skin":"ui/ok_btn.png","name":"ok"}},{"type":"Label","props":{"y":360,"x":355,"width":84,"var":"level","valign":"middle","text":"150","height":44,"fontSize":44,"font":"SimHei","color":"#f1cb04","align":"center"}},{"type":"Label","props":{"y":405,"x":132,"wordWrap":true,"width":369,"var":"content","valign":"middle","text":"升级奖励物品已送达仓库,请注意查收!","height":66,"fontSize":20,"font":"SimHei","color":"#f1cb04","align":"center"}}]};}
		]);
		return LevelUpUI;
	})(Dialog);
var loadingUI=(function(_super){
		function loadingUI(){
			
		    this.leaf=null;
		    this.curr=null;
		    this.tips=null;

			loadingUI.__super.call(this);
		}

		CLASS$(loadingUI,'ui.loadingUI',_super);
		var __proto__=loadingUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(loadingUI.uiView);
		}

		STATICATTR$(loadingUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":960,"height":640},"child":[{"type":"Image","props":{"y":640,"x":0,"skin":"loading/loading.png","skewY":270,"skewX":90}},{"type":"Image","props":{"y":393,"x":479,"var":"leaf","skin":"loading/ani.png","anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":393,"x":479,"width":56,"var":"curr","height":28,"fontSize":28,"font":"SimHei","color":"#0fffcf","anchorY":0.5,"anchorX":0.5,"align":"center"}},{"type":"Image","props":{"y":575,"x":0,"skin":"loading/dikuang.png"},"child":[{"type":"Label","props":{"y":9,"x":0,"width":960,"var":"tips","valign":"middle","text":"攻略里有高星级香烟生产攻略，想要获得更多礼品记得好好研究一下喔！","height":52,"fontSize":22,"font":"SimHei","color":"#ffffff","align":"center"}}]}]};}
		]);
		return loadingUI;
	})(View);
var loadingTipsUI=(function(_super){
		function loadingTipsUI(){
			
		    this.continue_btn=null;
		    this.cancel_btn=null;
		    this.BZTS_btn=null;

			loadingTipsUI.__super.call(this);
		}

		CLASS$(loadingTipsUI,'ui.loadingTipsUI',_super);
		var __proto__=loadingTipsUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(loadingTipsUI.uiView);
		}

		STATICATTR$(loadingTipsUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"zhiyin/zhiying_diban2.png"}},{"type":"HTMLDivElement","props":{"y":63,"x":38,"width":480,"innerHTML":"<div style=\"fontSize:26;color:#672416;width:480;height:auto;align:center;line-height:50;\">本游戏为大型模拟经营类游戏,<span color=\"#ae0626\">内容较多</span>,建议在<span color=\"#ae0626\">wifi模式</span>下打开参与。</div>","height":100}},{"type":"Button","props":{"y":175,"x":327,"var":"continue_btn","stateNum":"2","skin":"loading/jixuqianwang.png"}},{"type":"Button","props":{"y":178,"x":70,"var":"cancel_btn","stateNum":"2","skin":"loading/xiacidakai.png"}},{"type":"CheckBox","props":{"y":257,"x":258,"var":"BZTS_btn","skin":"loading/zhiying_buzaitixing_2.png","scaleY":1.2,"scaleX":1.2},"child":[{"type":"Image","props":{"y":8,"x":35,"skin":"loading/zhiying_buzaitixing_1.png"}}]}]};}
		]);
		return loadingTipsUI;
	})(Dialog);
var LuBianTanUI=(function(_super){
		function LuBianTanUI(){
			
		    this.MyList=null;
		    this.news_btn=null;
		    this.help_btn=null;
		    this.tips=null;
		    this.tips_1=null;
		    this.tips_2=null;

			LuBianTanUI.__super.call(this);
		}

		CLASS$(LuBianTanUI,'ui.LuBianTanUI',_super);
		var __proto__=LuBianTanUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(LuBianTanUI.uiView);
		}

		STATICATTR$(LuBianTanUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"lubiantan/xiaotan.png","name":"bg","cacheAsBitmap":true,"cacheAs":"bitmap"}},{"type":"Button","props":{"y":-14,"x":822,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"List","props":{"y":121,"x":208,"width":499,"var":"MyList","spaceY":5,"spaceX":15,"repeatY":2,"repeatX":4,"height":284},"child":[{"type":"Box","props":{"y":12,"x":19,"name":"render"},"child":[{"type":"Image","props":{"skin":"bakeroom/kuang.png"}},{"type":"Image","props":{"y":100,"x":29,"width":28,"visible":false,"skin":"userinfo/lebi_big.png","name":"jinbi","height":28}},{"type":"Image","props":{"y":5,"x":6,"width":94,"name":"icon","height":94}},{"type":"Label","props":{"y":104,"x":57,"width":44,"valign":"middle","name":"price","height":20,"fontSize":20,"font":"SimHei","align":"left"}},{"type":"Label","props":{"y":75,"x":11,"width":84,"valign":"middle","strokeColor":"#ffffff","stroke":2,"name":"num","height":22,"fontSize":16,"font":"SimHei","align":"right"}},{"type":"Image","props":{"y":19,"x":19,"visible":false,"skin":"lubiantan/guanggao.png","name":"gg","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":20,"x":12,"visible":false,"skin":"lubiantan/yishou.png","name":"sold"}},{"type":"Label","props":{"y":104,"x":-2,"width":27,"visible":false,"valign":"middle","text":"售价","name":"shoujia","height":20,"fontSize":16,"font":"SimHei","align":"center"}}]}]},{"type":"Button","props":{"y":420,"x":337,"var":"news_btn","stateNum":"2","skin":"lubiantan/button_zuixinhuowu.png"}},{"type":"Button","props":{"y":12,"x":742,"width":68,"var":"help_btn","stateNum":"2","skin":"ui/button_help.png","height":36}},{"type":"Sprite","props":{"y":-8,"x":0,"width":926,"visible":false,"var":"tips","height":518},"child":[{"type":"Image","props":{"y":61,"x":297,"visible":false,"var":"tips_1","skin":"zhiyin/zhiying_qipao_1-17.png","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"HTMLDivElement","props":{"y":26,"x":79,"width":280,"innerHTML":"<div style=\"fontSize:20;color:#ffffff;width:280;height:auto;align:center;\">点击<span color=\"#FF0000\">空闲的摊位</span>后就可以<br/>出售多余的物品了，还可以选择售卖物品与数量</div>","height":63}}]},{"type":"Image","props":{"y":328,"x":522,"visible":false,"var":"tips_2","skin":"zhiyin/zhiying_qipao_1-17.png","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"HTMLDivElement","props":{"y":34,"x":78,"width":280,"innerHTML":"<div style=\"fontSize:20;color:#ffffff;width:280;height:auto;align:center;\">也可以进入“<span color=\"#FF0000\">他人售卖</span>”中购买别人售出的物品哦~</div>","height":45}}]}]}]};}
		]);
		return LuBianTanUI;
	})(Dialog);
var LuckDrawUI=(function(_super){
		function LuckDrawUI(){
			
		    this.start_btn=null;

			LuckDrawUI.__super.call(this);
		}

		CLASS$(LuckDrawUI,'ui.LuckDrawUI',_super);
		var __proto__=LuckDrawUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(LuckDrawUI.uiView);
		}

		STATICATTR$(LuckDrawUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":567,"height":418},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/diban.png"}},{"type":"Button","props":{"y":53,"x":508,"width":60,"stateNum":"2","skin":"luckdraw/wuanbi.png","name":"close","height":55}},{"type":"Button","props":{"y":194,"x":202,"var":"start_btn","stateNum":"2","skin":"luckdraw/kaishichoujiang_2.png"}},{"type":"Box","props":{"y":100,"x":62,"width":139,"name":"box_1","height":78},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan.png","name":"goods_bg"}},{"type":"Image","props":{"y":39,"x":69,"name":"goods","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan-15.png","name":"mask_bg"}}]},{"type":"Box","props":{"y":100,"x":202,"width":139,"name":"box_2","height":78},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan.png","name":"goods_bg"}},{"type":"Image","props":{"y":39,"x":69,"name":"goods","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan-15.png","name":"mask_bg"}}]},{"type":"Box","props":{"y":100,"x":342,"width":139,"name":"box_3","height":78},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan.png","name":"goods_bg"}},{"type":"Image","props":{"y":39,"x":69,"name":"goods","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan-15.png","name":"mask_bg"}}]},{"type":"Box","props":{"y":193,"x":342,"width":139,"name":"box_4","height":78},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan.png","name":"goods_bg"}},{"type":"Image","props":{"y":39,"x":69,"name":"goods","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan-15.png","name":"mask_bg"}}]},{"type":"Box","props":{"y":287,"x":342,"width":139,"name":"box_5","height":78},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan.png","name":"goods_bg"}},{"type":"Image","props":{"y":39,"x":69,"name":"goods","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan-15.png","name":"mask_bg"}}]},{"type":"Box","props":{"y":287,"x":202,"width":139,"name":"box_6","height":78},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan.png","name":"goods_bg"}},{"type":"Image","props":{"y":39,"x":69,"name":"goods","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan-15.png","name":"mask_bg"}}]},{"type":"Box","props":{"y":287,"x":62,"width":139,"name":"box_7","height":78},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan.png","name":"goods_bg"}},{"type":"Image","props":{"y":39,"x":69,"name":"goods","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan-15.png","name":"mask_bg"}}]},{"type":"Box","props":{"y":193,"x":62,"width":139,"name":"box_8","height":78},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan.png","name":"goods_bg"}},{"type":"Image","props":{"y":39,"x":69,"name":"goods","anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/wupinlan-15.png","name":"mask_bg"}}]}]};}
		]);
		return LuckDrawUI;
	})(Dialog);
var LuckDrawResultUI=(function(_super){
		function LuckDrawResultUI(){
			
		    this.goods_icon=null;
		    this.show_text=null;

			LuckDrawResultUI.__super.call(this);
		}

		CLASS$(LuckDrawResultUI,'ui.LuckDrawResultUI',_super);
		var __proto__=LuckDrawResultUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(LuckDrawResultUI.uiView);
		}

		STATICATTR$(LuckDrawResultUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":960,"height":640},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/gongxizhongjiang.png"}},{"type":"Image","props":{"y":279,"x":471,"width":84,"var":"goods_icon","height":84,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":358,"x":365,"width":212,"var":"show_text","valign":"middle","name":"show_text","height":26,"fontSize":22,"color":"#a9252b","bold":true,"align":"center"}},{"type":"Button","props":{"y":498,"x":427,"stateNum":"2","skin":"luckdraw/lingqu.png","name":"close"}}]};}
		]);
		return LuckDrawResultUI;
	})(Dialog);
var NewYearLoginUI=(function(_super){
		function NewYearLoginUI(){
			
		    this.lingqu_btn_1=null;
		    this.lingqu_btn_2=null;
		    this.lingqu_btn_3=null;
		    this.lingqu_btn_4=null;
		    this.lingqu_btn_5=null;
		    this.lingqu_btn_6=null;
		    this.lingqu_btn_7=null;

			NewYearLoginUI.__super.call(this);
		}

		CLASS$(NewYearLoginUI,'ui.NewYearLoginUI',_super);
		var __proto__=NewYearLoginUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(NewYearLoginUI.uiView);
		}

		STATICATTR$(NewYearLoginUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":960,"height":640},"child":[{"type":"Sprite","props":{"y":0,"x":0,"alpha":0.5},"child":[{"type":"Rect","props":{"y":0,"x":0,"width":960,"lineWidth":1,"height":640,"fillColor":"#000000"}}]},{"type":"Image","props":{"skin":"2019newyearlogin/denglu_bg.png"}},{"type":"Image","props":{"y":108,"x":112,"skin":"2019newyearlogin/denglu_1.png"},"child":[{"type":"Button","props":{"y":181,"x":16,"var":"lingqu_btn_1","stateNum":"1","skin":"2019newyearlogin/denglu_lingqu.png"}}]},{"type":"Image","props":{"y":117,"x":328,"skin":"2019newyearlogin/denglu_2.png"},"child":[{"type":"Button","props":{"y":181,"x":16,"var":"lingqu_btn_2","stateNum":"1","skin":"2019newyearlogin/denglu_lingqu.png"}}]},{"type":"Image","props":{"y":117,"x":545,"skin":"2019newyearlogin/denglu_3.png"},"child":[{"type":"Button","props":{"y":181,"x":16,"var":"lingqu_btn_3","stateNum":"1","skin":"2019newyearlogin/denglu_lingqu.png"}}]},{"type":"Image","props":{"y":103,"x":761,"skin":"2019newyearlogin/denglu_4.png"},"child":[{"type":"Button","props":{"y":181,"x":16,"var":"lingqu_btn_4","stateNum":"1","skin":"2019newyearlogin/denglu_lingqu.png"}}]},{"type":"Image","props":{"y":358,"x":305,"skin":"2019newyearlogin/denglu_5.png"},"child":[{"type":"Button","props":{"y":181,"x":16,"var":"lingqu_btn_5","stateNum":"1","skin":"2019newyearlogin/denglu_lingqu.png"}}]},{"type":"Image","props":{"y":361,"x":523,"skin":"2019newyearlogin/denglu_6.png"},"child":[{"type":"Button","props":{"y":181,"x":16,"var":"lingqu_btn_6","stateNum":"1","skin":"2019newyearlogin/denglu_lingqu.png"}}]},{"type":"Image","props":{"y":343,"x":741,"skin":"2019newyearlogin/denglu_7.png"},"child":[{"type":"Button","props":{"y":220,"x":31,"var":"lingqu_btn_7","stateNum":"1","skin":"2019newyearlogin/denglu_lingqu.png"}}]},{"type":"Button","props":{"y":20,"x":872,"stateNum":"1","skin":"ui/close_btn.png","name":"close"}}]};}
		]);
		return NewYearLoginUI;
	})(Dialog);
var NewYearsDayUI=(function(_super){
		function NewYearsDayUI(){
			
		    this.choujiang_title=null;
		    this.daoju_title=null;
		    this.btn_lingqu=null;
		    this.list=null;
		    this.tips=null;

			NewYearsDayUI.__super.call(this);
		}

		CLASS$(NewYearsDayUI,'ui.NewYearsDayUI',_super);
		var __proto__=NewYearsDayUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(NewYearsDayUI.uiView);
		}

		STATICATTR$(NewYearsDayUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":21,"x":32,"skin":"double11/newyearsday_bg.png"}},{"type":"Box","props":{"y":135,"x":188,"width":595,"height":450},"child":[{"type":"Image","props":{"y":77,"x":94,"visible":false,"var":"choujiang_title","skin":"double11/choujiangwenzi.png"}},{"type":"Image","props":{"y":77,"x":94,"visible":false,"var":"daoju_title","skin":"double11/daojuwenzi.png"}},{"type":"Button","props":{"y":351,"x":177,"var":"btn_lingqu","stateNum":"1","skin":"double11/lingqu.png"}},{"type":"List","props":{"y":150,"x":22,"width":550,"visible":false,"var":"list","spaceX":110,"repeatX":3,"height":120},"child":[{"type":"Box","props":{"y":0,"x":0,"width":110,"name":"render","height":120},"child":[{"type":"Image","props":{"y":0,"x":18,"skin":"shop/shenmikuan_1.png"}},{"type":"Label","props":{"y":76,"x":0,"wordWrap":true,"width":110,"valign":"middle","name":"item_name","leading":2,"height":40,"fontSize":16,"color":"#3a1a03","align":"center"}},{"type":"Image","props":{"y":5,"x":23,"width":64,"name":"item_icon","height":64}}]}]},{"type":"Label","props":{"y":274,"x":0,"width":595,"var":"tips","valign":"middle","text":"温馨提示：领取礼包后可进入【仓库】建筑查看。","height":28,"fontSize":16,"font":"SimHei","color":"#3a1a03","align":"center"}}]}]};}
		]);
		return NewYearsDayUI;
	})(Dialog);
var OrderInfoUI=(function(_super){
		function OrderInfoUI(){
			
		    this.panel=null;
		    this.content=null;

			OrderInfoUI.__super.call(this);
		}

		CLASS$(OrderInfoUI,'ui.OrderInfoUI',_super);
		var __proto__=OrderInfoUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(OrderInfoUI.uiView);
		}

		STATICATTR$(OrderInfoUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"orderlist/dingdanxiangqing.png"}},{"type":"Panel","props":{"y":78,"x":84,"width":313,"var":"panel","vScrollBarSkin":"ui/vscroll.png","height":161},"child":[{"type":"Label","props":{"y":0,"x":0,"wordWrap":true,"width":313,"var":"content","valign":"middle","leading":5,"fontSize":20,"font":"SimHei","align":"left"}}]},{"type":"Button","props":{"y":-5,"x":422,"width":57,"stateNum":"2","skin":"ui/guanbi.png","name":"close","height":63}}]};}
		]);
		return OrderInfoUI;
	})(Dialog);
var OrderListUI=(function(_super){
		function OrderListUI(){
			
		    this.help_btn=null;
		    this.order_title=null;
		    this.order_content=null;
		    this.order_award=null;
		    this.complete=null;
		    this.order_list=null;

			OrderListUI.__super.call(this);
		}

		CLASS$(OrderListUI,'ui.OrderListUI',_super);
		var __proto__=OrderListUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(OrderListUI.uiView);
		}

		STATICATTR$(OrderListUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":1,"width":839,"skin":"orderlist/orderlist_bg.png","height":569,"cacheAsBitmap":true,"cacheAs":"bitmap"}},{"type":"Button","props":{"y":-5,"x":785,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Button","props":{"y":39,"x":706,"width":68,"var":"help_btn","stateNum":"2","skin":"ui/button_help.png","height":36}},{"type":"Box","props":{"y":68,"x":50},"child":[{"type":"Label","props":{"x":12,"width":220,"var":"order_title","valign":"middle","strokeColor":"#562917","stroke":3,"height":50,"fontSize":26,"font":"SimHei","color":"#F2DA98","align":"center"}},{"type":"List","props":{"y":105,"x":6,"width":232,"var":"order_content","spaceX":10,"repeatY":1,"repeatX":2,"height":174},"child":[{"type":"Box","props":{"y":21,"x":0,"width":110,"name":"render","height":122},"child":[{"type":"Image","props":{"y":0,"x":16,"width":78,"skin":"orderlist/wupindiban.png","height":78}},{"type":"Image","props":{"y":5,"x":23,"width":66,"name":"icon","height":66}},{"type":"Label","props":{"y":80,"x":0,"wordWrap":true,"width":110,"valign":"middle","name":"name","height":30,"fontSize":20,"font":"SimHei","align":"center"}},{"type":"Label","props":{"y":54,"x":18,"width":73,"valign":"middle","strokeColor":"#000000","stroke":1,"name":"num","height":17,"fontSize":18,"font":"SimHei","color":"#1eff00","bold":true,"align":"center"}}]}]},{"type":"List","props":{"y":311,"width":245,"var":"order_award","spaceX":-2,"repeatY":1,"repeatX":3,"height":83},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"width":82,"skin":"orderlist/wupinkuang.png","height":77}},{"type":"Image","props":{"y":5,"x":8,"width":68,"name":"icon","height":68}},{"type":"Label","props":{"y":52,"x":9,"width":64,"valign":"middle","strokeColor":"#000000","stroke":2,"name":"num","height":21,"fontSize":20,"font":"SimHei","color":"#ffffff","align":"center"}}]}]},{"type":"Button","props":{"y":393,"x":44,"var":"complete","stateNum":"1","skin":"orderlist/complete_btn_2.png"}}]},{"type":"List","props":{"y":97,"x":325,"width":481,"var":"order_list","spaceY":40,"spaceX":25,"repeatY":2,"repeatX":3,"height":430},"child":[{"type":"Box","props":{"y":12,"x":5,"name":"render"},"child":[{"type":"Image","props":{"skin":"orderlist/order_empty_row_bg.png","name":"no_content"}},{"type":"Image","props":{"visible":false,"skin":"orderlist/order_row_bg.png","name":"has_content"}},{"type":"Label","props":{"y":45,"x":7,"wordWrap":true,"width":112,"visible":false,"valign":"middle","text":"真龙商行","overflow":"hidden","name":"title","height":36,"fontSize":18,"font":"SimHei","color":"#562917","align":"center"}},{"type":"Label","props":{"y":80,"x":46,"width":64,"visible":false,"valign":"middle","name":"money","height":24,"fontSize":20,"font":"SimHei","color":"#F2DA98"}},{"type":"Label","props":{"y":116,"x":50,"width":60,"visible":false,"valign":"middle","name":"exp","height":24,"fontSize":20,"font":"SimHei","color":"#F2DA98"}},{"type":"Button","props":{"y":-14,"x":35,"visible":false,"stateNum":"2","skin":"orderlist/shuaxin.png","name":"del"}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"orderlist/del_row_bg.png","name":"waiting"}},{"type":"Button","props":{"y":128,"x":19,"visible":false,"stateNum":"1","skin":"orderlist/shuzhidikuang.png","name":"refresh","labelStrokeColor":"#000000","labelStroke":2,"labelSize":18,"labelPadding":"0,0,0,10","labelFont":"SimHei","labelColors":"#00ff00,#00ff00","labelAlign":"left","label":"刷新 1"},"child":[{"type":"Image","props":{"y":12,"x":58,"skin":"orderlist/ledou.png"}}]},{"type":"Label","props":{"y":47,"x":7,"width":112,"visible":false,"valign":"middle","overflow":"hidden","name":"time","height":27,"fontSize":20,"font":"SimHei","color":"#562917","align":"center"}},{"type":"Button","props":{"y":148,"x":20,"visible":false,"stateNum":"2","skin":"orderlist/dingdanxiangqing-06.png","name":"info_btn"}}]}]}]};}
		]);
		return OrderListUI;
	})(Dialog);
var peiyushiUI=(function(_super){
		function peiyushiUI(){
			
		    this.item1=null;
		    this.item2=null;
		    this.item3=null;
		    this.item4=null;
		    this.item5=null;
		    this.item6=null;
		    this.Upgrade1=null;
		    this.Upgrade2=null;
		    this.Upgrade3=null;
		    this.peiyu_btn=null;
		    this.progress1=null;
		    this.countdown1=null;
		    this.progress2=null;
		    this.countdown2=null;
		    this.progress3=null;
		    this.countdown3=null;
		    this.progress4=null;
		    this.countdown4=null;
		    this.progress5=null;
		    this.countdown5=null;
		    this.progress6=null;
		    this.countdown6=null;
		    this.tab_yanye=null;
		    this.view_stack=null;
		    this.list3=null;
		    this.list0=null;
		    this.list1=null;
		    this.list2=null;
		    this.list4=null;
		    this.tab_peiyu=null;
		    this.help_btn=null;
		    this.tips=null;
		    this.tips_1=null;
		    this.tips_2=null;
		    this.tips_2_ele=null;
		    this.tips_3=null;

			peiyushiUI.__super.call(this);
		}

		CLASS$(peiyushiUI,'ui.peiyushiUI',_super);
		var __proto__=peiyushiUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(peiyushiUI.uiView);
		}

		STATICATTR$(peiyushiUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"peiyu/peiyu_bg.png","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"Image","props":{"y":461,"x":264,"skin":"peiyu/shuoming_peiyu.png"}}]},{"type":"Button","props":{"y":41,"x":702,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Box","props":{"y":179,"x":125,"width":381,"name":"left","height":288},"child":[{"type":"Box","props":{"y":-19,"x":17,"var":"item1"},"child":[{"type":"Image","props":{"y":27,"skin":"peiyu/peiyangmin_2.png"}},{"type":"Image","props":{"y":2,"x":0,"width":60,"name":"leaf1","height":60}},{"type":"Image","props":{"y":2,"x":50,"width":60,"name":"leaf2","height":60}},{"type":"Image","props":{"y":0,"x":26,"width":60,"name":"seed","height":60}}]},{"type":"Image","props":{"y":9,"x":17,"skin":"peiyu/peiyangmin_1.png"}},{"type":"Box","props":{"y":-17,"x":142,"var":"item2"},"child":[{"type":"Image","props":{"y":27,"skin":"peiyu/peiyangmin_2.png"}},{"type":"Image","props":{"y":0,"x":0,"width":60,"name":"leaf1","height":60}},{"type":"Image","props":{"y":0,"x":54,"width":60,"name":"leaf2","height":60}},{"type":"Image","props":{"y":0,"x":26,"width":60,"name":"seed","height":60}}]},{"type":"Image","props":{"y":11,"x":142,"skin":"peiyu/peiyangmin_1.png"}},{"type":"Image","props":{"y":9,"x":270,"var":"item3","skin":"peiyu/peiyangmin_2.png"},"child":[{"type":"Image","props":{"y":-21,"x":0,"width":60,"name":"leaf1","height":60}},{"type":"Image","props":{"y":-21,"x":52,"width":60,"name":"leaf2","height":60}},{"type":"Image","props":{"y":-21,"x":32,"width":60,"name":"seed","height":60}}]},{"type":"Image","props":{"y":9,"x":270,"skin":"peiyu/peiyangmin_1.png"}},{"type":"Image","props":{"y":131,"x":17,"var":"item4","skin":"peiyu/peiyangmin_2.png"},"child":[{"type":"Image","props":{"y":-21,"x":0,"width":60,"name":"leaf1","height":60}},{"type":"Image","props":{"y":-19,"x":52,"width":60,"name":"leaf2","height":60}},{"type":"Image","props":{"y":-19,"x":30,"width":60,"name":"seed","height":60}}]},{"type":"Image","props":{"y":130,"x":17,"skin":"peiyu/peiyangmin_1.png"}},{"type":"Image","props":{"y":131,"x":144,"var":"item5","skin":"peiyu/peiyangmin_2.png"},"child":[{"type":"Image","props":{"y":-21,"x":0,"width":60,"name":"leaf1","height":60}},{"type":"Image","props":{"y":-23,"x":52,"width":60,"name":"leaf2","height":60}},{"type":"Image","props":{"y":-21,"x":26,"width":60,"name":"seed","height":60}}]},{"type":"Image","props":{"y":130,"x":144,"skin":"peiyu/peiyangmin_1.png"}},{"type":"Image","props":{"y":131,"x":270,"var":"item6","skin":"peiyu/peiyangmin_2.png"},"child":[{"type":"Image","props":{"y":-21,"x":0,"width":60,"name":"leaf1","height":60}},{"type":"Image","props":{"y":-21,"x":54,"width":60,"name":"leaf2","height":60}},{"type":"Image","props":{"y":-21,"x":34,"width":60,"name":"seed","height":60}}]},{"type":"Image","props":{"y":130,"x":270,"skin":"peiyu/peiyangmin_1.png"}},{"type":"Image","props":{"y":148,"x":52,"var":"Upgrade1","skin":"peiyu/jia-11.png"}},{"type":"Image","props":{"y":148,"x":182,"var":"Upgrade2","skin":"peiyu/jia-11.png"}},{"type":"Image","props":{"y":149,"x":309,"var":"Upgrade3","skin":"peiyu/jia-11.png"}},{"type":"Button","props":{"y":230,"x":113,"var":"peiyu_btn","stateNum":"2","skin":"peiyu/button_peiyu.png"}},{"type":"Image","props":{"y":83,"x":25,"width":22,"skin":"bakeroom/zhong.png","height":22}},{"type":"Image","props":{"y":83,"x":149,"width":22,"skin":"bakeroom/zhong.png","height":22}},{"type":"Image","props":{"y":83,"x":274,"width":22,"skin":"bakeroom/zhong.png","height":22}},{"type":"ProgressBar","props":{"y":87,"x":44,"width":80,"var":"progress1","value":1,"skin":"peiyu/progress_time.png","sizeGrid":"0,5,0,5","height":14},"child":[{"type":"Label","props":{"y":-46,"x":-7,"width":69,"var":"countdown1","valign":"middle","strokeColor":"#000000","stroke":2,"height":15,"fontSize":16,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"ProgressBar","props":{"y":87,"x":168,"width":80,"var":"progress2","value":1,"skin":"peiyu/progress_time.png","sizeGrid":"0,5,0,5","height":14},"child":[{"type":"Label","props":{"y":-46,"x":-6,"width":69,"var":"countdown2","valign":"middle","strokeColor":"#000000","stroke":2,"height":15,"fontSize":16,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"ProgressBar","props":{"y":87,"x":292,"width":80,"var":"progress3","value":1,"skin":"peiyu/progress_time.png","sizeGrid":"0,5,0,5","height":14},"child":[{"type":"Label","props":{"y":-47,"x":0,"width":69,"var":"countdown3","valign":"middle","strokeColor":"#000000","stroke":2,"height":15,"fontSize":16,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"ProgressBar","props":{"y":210,"x":44,"width":80,"var":"progress4","value":1,"skin":"peiyu/progress_time.png","sizeGrid":"0,5,0,5","height":14},"child":[{"type":"Label","props":{"y":-49,"x":-7,"width":69,"var":"countdown4","valign":"middle","strokeColor":"#000000","stroke":2,"height":15,"fontSize":16,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"ProgressBar","props":{"y":210,"x":168,"width":80,"var":"progress5","value":1,"skin":"peiyu/progress_time.png","sizeGrid":"0,5,0,5","height":14},"child":[{"type":"Label","props":{"y":-50,"x":-4,"width":69,"var":"countdown5","valign":"middle","strokeColor":"#000000","stroke":2,"height":15,"fontSize":16,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"ProgressBar","props":{"y":210,"x":292,"width":80,"var":"progress6","value":1,"skin":"peiyu/progress_time.png","sizeGrid":"0,5,0,5","height":14},"child":[{"type":"Label","props":{"y":-50,"x":0,"width":69,"var":"countdown6","valign":"middle","strokeColor":"#000000","stroke":2,"height":15,"fontSize":16,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Image","props":{"y":207,"x":25,"width":22,"skin":"bakeroom/zhong.png","height":22}},{"type":"Image","props":{"y":207,"x":149,"width":22,"skin":"bakeroom/zhong.png","height":22}},{"type":"Image","props":{"y":207,"x":274,"width":22,"skin":"bakeroom/zhong.png","height":22}}]},{"type":"Box","props":{"y":212,"x":620,"width":206,"name":"right","height":196},"child":[{"type":"Tab","props":{"y":-25,"x":-48,"var":"tab_yanye","stateNum":2,"skin":"peiyu/tab_pen.png","selectedIndex":0,"labels":"一星,二星,三星,四星,五星","labelSize":22,"labelPadding":"3,0,2,0","labelFont":"SimHei","labelColors":"#fff4a7,#fff4a7"}},{"type":"ViewStack","props":{"y":27,"x":1,"width":205,"visible":false,"var":"view_stack","height":170},"child":[{"type":"List","props":{"y":8,"x":-42,"width":326,"var":"list3","vScrollBarSkin":"ui/vscroll.png","spaceY":4,"spaceX":22,"repeatY":3,"repeatX":3,"name":"item3","height":219},"child":[{"type":"Box","props":{"y":0,"x":15,"width":85,"name":"render","height":125},"child":[{"type":"Image","props":{"width":85,"skin":"bakeroom/wupindiwen.png","height":85}},{"type":"Image","props":{"y":42,"x":42,"width":85,"name":"icon","height":85,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":60,"x":23,"width":50,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":18,"fontSize":16,"color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":87,"x":6,"wordWrap":true,"width":78,"valign":"middle","name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":10,"x":10,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":73,"x":25,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得四星烟叶，可通过种植获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"y":8,"x":-42,"width":326,"var":"list0","vScrollBarSkin":"ui/vscroll.png","spaceY":4,"spaceX":22,"repeatY":3,"repeatX":3,"name":"item0","height":219},"child":[{"type":"Box","props":{"y":0,"x":15,"width":85,"name":"render","height":125},"child":[{"type":"Image","props":{"width":85,"skin":"bakeroom/wupindiwen.png","height":85}},{"type":"Image","props":{"y":42,"x":42,"width":85,"name":"icon","height":85,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":60,"x":23,"width":50,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":18,"fontSize":16,"color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":87,"x":6,"wordWrap":true,"width":78,"valign":"middle","name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":10,"x":10,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":73,"x":25,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得一星烟叶，可通过种植获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"y":8,"x":-42,"width":326,"var":"list1","vScrollBarSkin":"ui/vscroll.png","spaceY":4,"spaceX":22,"repeatY":3,"repeatX":3,"name":"item1","height":219},"child":[{"type":"Box","props":{"y":0,"x":15,"width":85,"name":"render","height":125},"child":[{"type":"Image","props":{"width":85,"skin":"bakeroom/wupindiwen.png","height":85}},{"type":"Image","props":{"y":42,"x":42,"width":85,"name":"icon","height":85,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":60,"x":23,"width":50,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":18,"fontSize":16,"color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":87,"x":6,"wordWrap":true,"width":78,"valign":"middle","name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":10,"x":10,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":73,"x":25,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得二星烟叶，可通过种植获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"y":8,"x":-42,"width":326,"var":"list2","vScrollBarSkin":"ui/vscroll.png","spaceY":4,"spaceX":22,"repeatY":3,"repeatX":3,"name":"item2","height":219},"child":[{"type":"Box","props":{"y":0,"x":15,"width":85,"name":"render","height":125},"child":[{"type":"Image","props":{"width":85,"skin":"bakeroom/wupindiwen.png","height":85}},{"type":"Image","props":{"y":42,"x":42,"width":85,"name":"icon","height":85,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":60,"x":23,"width":50,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":18,"fontSize":16,"color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":87,"x":6,"wordWrap":true,"width":78,"valign":"middle","name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":10,"x":10,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":73,"x":25,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得三星烟叶，可通过种植获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"y":8,"x":-42,"width":326,"var":"list4","vScrollBarSkin":"ui/vscroll.png","spaceY":4,"spaceX":22,"repeatY":3,"repeatX":3,"name":"item4","height":219},"child":[{"type":"Box","props":{"y":0,"x":15,"width":85,"name":"render","height":125},"child":[{"type":"Image","props":{"width":85,"skin":"bakeroom/wupindiwen.png","height":85}},{"type":"Image","props":{"y":42,"x":42,"width":85,"name":"icon","height":85,"anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":60,"x":23,"width":50,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":18,"fontSize":16,"color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":87,"x":6,"wordWrap":true,"width":78,"valign":"middle","name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}},{"type":"Image","props":{"y":10,"x":10,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":73,"x":25,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得五星烟叶，可通过种植获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]}]}]},{"type":"Tab","props":{"y":126,"x":31,"var":"tab_peiyu","stateNum":2,"skin":"peiyu/tab_1.png","selectedIndex":0,"labels":"培育","labelSize":18,"labelColors":"#6a3906,#261504","labelBold":true,"direction":"vertical"}},{"type":"Button","props":{"y":130,"x":581,"width":68,"var":"help_btn","stateNum":"2","skin":"ui/button_help.png","height":36}},{"type":"Sprite","props":{"y":0,"x":0,"width":952,"visible":false,"var":"tips","height":497},"child":[{"type":"Image","props":{"y":79,"x":210,"visible":false,"var":"tips_1","skin":"zhiyin/zhiying_qipao_1-17.png","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"HTMLDivElement","props":{"y":42,"x":78,"width":280,"innerHTML":"<div style=\"fontSize:20;color:#ffffff;width:280;height:auto;align:center;\">选择一个<span color=\"#FF0000\">培养皿</span></div>","height":24}}]},{"type":"Image","props":{"y":162,"x":643,"visible":false,"var":"tips_2","skin":"zhiyin/zhiying_qipao_1-17.png","skewY":180,"cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"HTMLDivElement","props":{"y":36,"x":359,"width":280,"var":"tips_2_ele","innerHTML":"<div style=\"fontSize:20;color:#ffffff;width:280;height:auto;align:center;\">选择<span color=\"#FF0000\">两份烟叶</span>置入，培育一段时间可以得到一份新的种子</div>","height":70}}]},{"type":"Image","props":{"y":299,"x":353,"visible":false,"var":"tips_3","skin":"zhiyin/zhiying_qipao_1-17.png","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"HTMLDivElement","props":{"y":41,"x":82,"width":280,"innerHTML":"<div style=\"fontSize:20;color:#ffffff;width:280;height:auto;align:center;\">点击<span color=\"#FF0000\">培育按钮</span>即可开始培育</div>","height":24}}]}]}]};}
		]);
		return peiyushiUI;
	})(Dialog);
var pinjianUI=(function(_super){
		function pinjianUI(){
			
		    this.viewstack=null;
		    this.wei_pinjian_list=null;
		    this.pinjian_btn=null;
		    this.yi_pinjian_list=null;
		    this.shengji_btn=null;
		    this.quan=null;
		    this.need_quan=null;
		    this.has_quan=null;
		    this.use=null;
		    this.tab_shengji=null;
		    this.tab_pinjian=null;
		    this.select_icon=null;
		    this.select_name=null;
		    this.panel=null;
		    this.result=null;
		    this.help_btn=null;

			pinjianUI.__super.call(this);
		}

		CLASS$(pinjianUI,'ui.pinjianUI',_super);
		var __proto__=pinjianUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("Text",laya.display.Text);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(pinjianUI.uiView);
		}

		STATICATTR$(pinjianUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"width":959,"skin":"pinjian/bg.png","height":560},"child":[{"type":"Image","props":{"y":502,"x":219,"skin":"pinjian/shuoming_pinjian.png"}}]},{"type":"Button","props":{"y":90,"x":892,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"ViewStack","props":{"y":186,"x":160,"width":638,"var":"viewstack","selectedIndex":0,"height":351},"child":[{"type":"Box","props":{"y":0,"x":0,"width":633,"name":"item0","height":334},"child":[{"type":"List","props":{"y":47,"x":321,"width":293,"visible":false,"var":"wei_pinjian_list","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":20,"repeatY":2,"repeatX":3,"name":"item0","height":217},"child":[{"type":"Box","props":{"y":3,"x":3,"name":"render"},"child":[{"type":"Image","props":{"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":3,"width":73,"name":"icon","height":73}},{"type":"Label","props":{"y":79,"x":4,"width":69,"valign":"middle","name":"num","height":20,"fontSize":20,"font":"SimHei","align":"center"}},{"type":"Image","props":{"y":9,"x":6,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":72,"x":9,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得香烟，可通过制烟坊生产获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"Button","props":{"y":250,"x":75,"var":"pinjian_btn","stateNum":"2","skin":"pinjian/button_pinjian.png"}}]},{"type":"Box","props":{"y":0,"x":0,"width":644,"name":"item1","height":347},"child":[{"type":"List","props":{"y":47,"x":321,"width":293,"visible":false,"var":"yi_pinjian_list","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":20,"repeatY":2,"repeatX":3,"name":"item1","height":217},"child":[{"type":"Box","props":{"y":3,"x":3,"name":"render"},"child":[{"type":"Image","props":{"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":3,"width":73,"name":"icon","height":73}},{"type":"Label","props":{"y":79,"x":4,"width":69,"valign":"middle","name":"num","height":20,"fontSize":20,"font":"SimHei","align":"center"}},{"type":"Image","props":{"y":9,"x":6,"visible":false,"skin":"dati/dui.png","name":"gou"}}]},{"type":"Label","props":{"y":72,"x":9,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得品鉴香烟，可通过香烟品鉴获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"Button","props":{"y":250,"x":75,"var":"shengji_btn","stateNum":"2","skin":"pinjian/button_shengji.png"}},{"type":"Image","props":{"y":133,"x":206,"width":64,"skin":"pinjian/quan_bg.png","height":64},"child":[{"type":"Image","props":{"y":2,"x":2,"width":60,"var":"quan","height":60}},{"type":"Label","props":{"y":39,"x":28,"width":18,"var":"need_quan","valign":"middle","text":"/1","height":16,"fontSize":16,"font":"SimHei","bold":true,"align":"left"}},{"type":"Label","props":{"y":39,"x":6,"width":21,"var":"has_quan","valign":"middle","text":"0","height":16,"fontSize":16,"font":"SimHei","bold":true,"align":"right"}}]},{"type":"CheckBox","props":{"y":166,"x":270,"var":"use","skin":"lubiantan/gou_1_01-02.png","scaleY":0.4,"scaleX":0.4}}]}]},{"type":"Box","props":{"y":191,"x":161,"width":306,"mouseThrough":true,"height":298},"child":[{"type":"Button","props":{"y":24,"x":-82,"width":100,"visible":false,"var":"tab_shengji","stateNum":"2","skin":"pinjian/tab_shengji.png","height":46}},{"type":"Button","props":{"y":-37,"x":-82,"width":100,"visible":false,"var":"tab_pinjian","stateNum":"2","skin":"pinjian/tab_pinjian.png","height":46}},{"type":"Image","props":{"y":61,"x":72,"width":114,"var":"select_icon","height":114}},{"type":"Label","props":{"y":196,"x":9,"width":240,"var":"select_name","valign":"middle","overflow":"hidden","height":34,"fontSize":18,"color":"#fff0c5","align":"center"}},{"type":"Panel","props":{"y":177,"x":4,"width":284,"visible":false,"var":"panel","height":111},"child":[{"type":"Text","props":{"y":1,"x":3,"wordWrap":true,"width":276,"var":"result","height":300,"fontSize":16,"font":"SimHei","color":"#fff0c5"}}]}]},{"type":"Button","props":{"y":149,"x":733,"width":68,"var":"help_btn","stateNum":"2","skin":"ui/button_help.png","height":36}}]};}
		]);
		return pinjianUI;
	})(Dialog);
var PinjianSuccessUI=(function(_super){
		function PinjianSuccessUI(){
			
		    this.goto_choujiang=null;
		    this.goto_dingdan=null;
		    this.icon=null;
		    this.yan_name=null;
		    this.panel=null;
		    this.result=null;

			PinjianSuccessUI.__super.call(this);
		}

		CLASS$(PinjianSuccessUI,'ui.PinjianSuccessUI',_super);
		var __proto__=PinjianSuccessUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("Text",laya.display.Text);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(PinjianSuccessUI.uiView);
		}

		STATICATTR$(PinjianSuccessUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"pinjian/pinjian_bg.png"},"child":[{"type":"Button","props":{"y":450,"x":186,"width":118,"var":"goto_choujiang","stateNum":"2","skin":"pinjian/anjian_quchoujiang.png","height":43}},{"type":"Button","props":{"y":450,"x":375,"width":118,"var":"goto_dingdan","stateNum":"2","skin":"pinjian/anjian_qudingdan.png","height":43}}]},{"type":"Image","props":{"y":76,"x":298,"width":89,"var":"icon","height":89}},{"type":"Label","props":{"y":183,"x":243,"width":193,"var":"yan_name","valign":"middle","height":32,"fontSize":18,"font":"SimHei","color":"#fff0c5","align":"center"}},{"type":"Panel","props":{"y":325,"x":181,"width":326,"var":"panel","height":130},"child":[{"type":"Text","props":{"y":0,"x":0,"wordWrap":true,"width":326,"var":"result","fontSize":20,"font":"SimHei","color":"#fff0c5"}}]},{"type":"Button","props":{"y":-19,"x":520,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}}]};}
		]);
		return PinjianSuccessUI;
	})(Dialog);
var PlantUI=(function(_super){
		function PlantUI(){
			
		    this.plant_name=null;
		    this.plant_progress=null;
		    this.countdown=null;
		    this.SpeedUp_btn=null;
		    this.clear_btn=null;

			PlantUI.__super.call(this);
		}

		CLASS$(PlantUI,'ui.PlantUI',_super);
		var __proto__=PlantUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(PlantUI.uiView);
		}

		STATICATTR$(PlantUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"y":280,"x":220,"pivotY":280,"pivotX":220},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"bozhong/diban_720.png"}},{"type":"Label","props":{"y":16,"x":123,"width":229,"var":"plant_name","valign":"middle","height":36,"fontSize":20,"font":"SimHei","color":"#000000","align":"center"}},{"type":"ProgressBar","props":{"y":57,"x":88,"width":308,"var":"plant_progress","value":1,"skin":"bozhong/progress.png","sizeGrid":"0,10,0,7","height":28},"child":[{"type":"Label","props":{"y":1,"x":68,"width":171,"var":"countdown","valign":"middle","height":24,"fontSize":20,"font":"SimHei","align":"center"}}]},{"type":"Button","props":{"y":109,"x":66,"width":126,"var":"SpeedUp_btn","stateNum":"2","skin":"bozhong/button_jiashu.png","height":57}},{"type":"Button","props":{"y":109,"x":250,"width":126,"var":"clear_btn","stateNum":"2","skin":"bozhong/button_chanchu.png","height":57}},{"type":"Label","props":{"y":58,"x":40,"text":"时间","fontSize":24,"font":"SimHei","color":"#542927"}}]};}
		]);
		return PlantUI;
	})(Dialog);
var QuanDialogUI=(function(_super){
		function QuanDialogUI(){
			
		    this.list=null;
		    this.intro=null;

			QuanDialogUI.__super.call(this);
		}

		CLASS$(QuanDialogUI,'ui.QuanDialogUI',_super);
		var __proto__=QuanDialogUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(QuanDialogUI.uiView);
		}

		STATICATTR$(QuanDialogUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"pinjian/quan_dialog_bg.png"},"child":[{"type":"List","props":{"y":89,"x":53,"width":768,"var":"list","repeatY":1,"repeatX":7,"height":100},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"width":110,"skin":"pinjian/quan_bg.png","height":110}},{"type":"Image","props":{"y":11,"x":13,"width":85,"name":"icon","height":85}},{"type":"Label","props":{"y":70,"x":12,"width":86,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":26,"fontSize":20,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Image","props":{"y":5,"x":6,"width":99,"visible":false,"skin":"lubiantan/wupingkuang_1.png","name":"selected","height":96}}]}]},{"type":"Label","props":{"y":270,"x":162,"wordWrap":true,"width":559,"var":"intro","valign":"middle","height":110,"fontSize":26,"font":"SimHei","color":"#ffffff","align":"center"}}]},{"type":"Button","props":{"y":381,"x":372,"stateNum":"1","skin":"pinjian/button_ok.png","name":"close"}}]};}
		]);
		return QuanDialogUI;
	})(Dialog);
var RankingUI=(function(_super){
		function RankingUI(){
			
		    this.tab=null;
		    this.tab_zhongzhi=null;
		    this.tab_zhiyan=null;
		    this.ruleBtn=null;
		    this.list0=null;
		    this.list1=null;
		    this.current_ranking=null;
		    this.last_ranking=null;
		    this.msg=null;
		    this.stop_tips=null;

			RankingUI.__super.call(this);
		}

		CLASS$(RankingUI,'ui.RankingUI',_super);
		var __proto__=RankingUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(RankingUI.uiView);
		}

		STATICATTR$(RankingUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"ranking/bg.png"}},{"type":"Tab","props":{"y":51,"x":47,"width":543,"visible":false,"var":"tab","height":58},"child":[{"type":"Button","props":{"y":0,"x":18,"visible":false,"stateNum":"2","skin":"ranking/kejindaren.png","name":"item0"}},{"type":"Button","props":{"y":0,"x":190,"visible":false,"stateNum":"2","skin":"ranking/haojiuyigezhi.png","name":"item1"}},{"type":"Button","props":{"y":0,"x":194,"var":"tab_zhongzhi","stateNum":"2","skin":"ranking/zhongzhixiaonengshou.png","name":"item2"}},{"type":"Button","props":{"y":0,"x":194,"var":"tab_zhiyan","stateNum":"2","skin":"ranking/ziyanxiaonengshou.png","name":"item3"}}]},{"type":"Button","props":{"y":-25,"x":884,"stateNum":"2","skin":"ranking/guanbi.png","name":"close"}},{"type":"Button","props":{"y":9,"x":800,"var":"ruleBtn","stateNum":"2","skin":"ranking/paihang_shuoming_jian.png"}},{"type":"List","props":{"y":152,"x":78,"width":481,"var":"list0","vScrollBarSkin":"ranking/vscroll.png","spaceY":7,"repeatY":100,"repeatX":1,"height":312},"child":[{"type":"Box","props":{"y":0,"x":0,"width":472,"name":"render","height":32},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"ranking/paihangdiwen_1.png"}},{"type":"Label","props":{"width":58,"valign":"middle","name":"ranking","height":32,"fontSize":24,"font":"SimHei","color":"#ffd3b0","align":"center"}},{"type":"Label","props":{"x":98,"width":238,"valign":"middle","name":"username","height":32,"fontSize":24,"font":"SimHei","color":"#ffd3b0","align":"center"}},{"type":"Label","props":{"x":338,"width":120,"valign":"middle","name":"num","height":32,"fontSize":24,"font":"SimHei","color":"#ffd3b0","align":"center"}},{"type":"Image","props":{"y":0,"x":15,"width":26,"name":"img","height":38}}]}]},{"type":"List","props":{"y":68,"x":577,"width":344,"var":"list1","vScrollBarSkin":"ranking/vscroll.png","spaceY":5,"repeatY":100,"repeatX":1,"height":427},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"ranking/diwen_320.png"}},{"type":"Image","props":{"y":39,"x":0,"width":63,"skin":"ranking/jiangpinkuang_320.png","name":"bg1","height":59}},{"type":"Image","props":{"y":39,"x":70,"width":63,"skin":"ranking/jiangpinkuang_320.png","name":"bg2","height":59}},{"type":"Image","props":{"y":39,"x":140,"width":63,"skin":"ranking/jiangpinkuang_320.png","name":"bg3","height":59}},{"type":"Image","props":{"y":39,"x":210,"width":63,"skin":"ranking/jiangpinkuang_320.png","name":"bg4","height":59}},{"type":"Image","props":{"y":39,"x":280,"width":63,"skin":"ranking/jiangpinkuang_320.png","name":"bg5","height":59}},{"type":"Image","props":{"y":43,"x":6,"width":51,"name":"icon1","height":51}},{"type":"Image","props":{"y":43,"x":76,"width":51,"name":"icon2","height":51}},{"type":"Image","props":{"y":43,"x":146,"width":51,"name":"icon3","height":51}},{"type":"Image","props":{"y":43,"x":216,"width":51,"name":"icon4","height":51}},{"type":"Image","props":{"y":43,"x":286,"width":51,"name":"icon5","height":51}},{"type":"Label","props":{"y":6,"x":7,"width":149,"valign":"middle","name":"num","height":27,"fontSize":22,"font":"SimHei","color":"#5d1b14"}},{"type":"Button","props":{"y":-6,"x":213,"visible":false,"stateNum":"2","skin":"ranking/zhiying_lingqu.png","scaleY":0.7,"scaleX":0.7,"name":"receive_btn"}},{"type":"Label","props":{"y":114,"x":32,"width":54,"valign":"middle","name":"shop1_total","height":16,"fontSize":22,"font":"SimHei","color":"#5d1b14","anchorY":0.5,"anchorX":0.5,"align":"center"}},{"type":"Label","props":{"y":114,"x":102,"width":54,"valign":"middle","name":"shop2_total","height":16,"fontSize":22,"font":"SimHei","color":"#5d1b14","anchorY":0.5,"anchorX":0.5,"align":"center"}},{"type":"Label","props":{"y":114,"x":172,"width":54,"valign":"middle","name":"shop3_total","height":16,"fontSize":22,"font":"SimHei","color":"#5d1b14","anchorY":0.5,"anchorX":0.5,"align":"center"}},{"type":"Label","props":{"y":114,"x":242,"width":54,"valign":"middle","name":"shop4_total","height":16,"fontSize":22,"font":"SimHei","color":"#5d1b14","anchorY":0.5,"anchorX":0.5,"align":"center"}},{"type":"Label","props":{"y":114,"x":312,"width":54,"valign":"middle","name":"shop5_total","height":16,"fontSize":22,"font":"SimHei","color":"#5d1b14","anchorY":0.5,"anchorX":0.5,"align":"center"}}]}]},{"type":"Label","props":{"y":479,"x":148,"width":290,"var":"current_ranking","valign":"middle","text":"我的当前排行：","height":26,"fontSize":20,"font":"SimHei","color":"#f9c259"}},{"type":"Label","props":{"y":504,"x":148,"width":290,"var":"last_ranking","valign":"middle","text":"我的上周排行：","height":26,"fontSize":20,"font":"SimHei","color":"#ffd3b0"}},{"type":"Label","props":{"y":17,"x":26,"width":363,"var":"msg","valign":"middle","height":35,"fontSize":22,"font":"SimHei","color":"#fed2af"}},{"type":"Label","props":{"y":508,"x":572,"text":"请在结算后，按照排行名次进行领奖","fontSize":22,"font":"SimHei","color":"#fed2af"}},{"type":"Label","props":{"y":172,"x":186,"width":264,"var":"stop_tips","valign":"middle","text":"排行榜活动暂停","height":37,"fontSize":30,"font":"SimHei","color":"#fed2af","align":"center"}}]};}
		]);
		return RankingUI;
	})(Dialog);
var RankingRuleUI=(function(_super){
		function RankingRuleUI(){
			
		    this.panel=null;
		    this.introduce_txt=null;

			RankingRuleUI.__super.call(this);
		}

		CLASS$(RankingRuleUI,'ui.RankingRuleUI',_super);
		var __proto__=RankingRuleUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(RankingRuleUI.uiView);
		}

		STATICATTR$(RankingRuleUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":13,"x":75,"skin":"ranking/paihangbang.png"}},{"type":"Button","props":{"y":-5,"x":357,"stateNum":"2","skin":"ranking/guanbi.png","name":"close"}},{"type":"Panel","props":{"y":108,"x":122,"width":263,"var":"panel","height":341},"child":[{"type":"HTMLDivElement","props":{"y":4,"x":3,"width":253,"var":"introduce_txt","height":341}}]}]};}
		]);
		return RankingRuleUI;
	})(Dialog);
var RechargeUI=(function(_super){
		function RechargeUI(){
			
		    this.title=null;
		    this.tips=null;
		    this.List=null;

			RechargeUI.__super.call(this);
		}

		CLASS$(RechargeUI,'ui.RechargeUI',_super);
		var __proto__=RechargeUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(RechargeUI.uiView);
		}

		STATICATTR$(RechargeUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"recharge/ledouduihuandiban.png"},"child":[{"type":"Image","props":{"y":1,"x":353,"var":"title","skin":"recharge/duiguanjinbi.png"}},{"type":"Image","props":{"y":72,"x":438,"var":"tips","skin":"recharge/duihuangshuoming_lebi.png","anchorY":0.5,"anchorX":0.5}}]},{"type":"Button","props":{"y":-27,"x":832,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"List","props":{"y":93,"x":51,"width":775,"var":"List","spaceX":12,"repeatY":1,"repeatX":5,"height":314},"child":[{"type":"Box","props":{"y":0,"x":5,"width":144,"name":"render","height":313},"child":[{"type":"Image","props":{"y":6,"x":4,"width":136,"name":"icon","height":136}},{"type":"Image","props":{"y":146,"x":2,"width":44,"skin":"userinfo/lebi_big.png","name":"item","height":44}},{"type":"Label","props":{"y":149,"x":46,"width":96,"valign":"middle","name":"num","height":38,"fontSize":26,"font":"SimHei","color":"#ffffa1"}},{"type":"Label","props":{"y":193,"x":24,"width":96,"valign":"middle","name":"song","height":30,"fontSize":24,"font":"SimHei","color":"#ffffa1"}},{"type":"Button","props":{"y":226,"x":4,"stateNum":"2","skin":"recharge/button_anjian.png","name":"btn","labelStrokeColor":"#560f0f","labelStroke":3,"labelSize":30,"labelPadding":"0,55,0,0","labelFont":"SimHei","labelColors":"#ffffa1,#ffffa1","labelAlign":"right"}},{"type":"Image","props":{"y":214,"x":72,"width":60,"visible":false,"skin":"shop/sale.png","name":"dazhe","height":47}}]}]}]};}
		]);
		return RechargeUI;
	})(Dialog);
var RoleTipsUI=(function(_super){
		function RoleTipsUI(){
			

			RoleTipsUI.__super.call(this);
		}

		CLASS$(RoleTipsUI,'ui.RoleTipsUI',_super);
		var __proto__=RoleTipsUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(RoleTipsUI.uiView);
		}

		STATICATTR$(RoleTipsUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"zhiyin/tis.png"}},{"type":"Button","props":{"y":171,"x":145,"stateNum":"2","skin":"zhiyin/zhiying_zhanbukaiqi-38.png","name":"close"}}]};}
		]);
		return RoleTipsUI;
	})(Dialog);
var SelectRoleUI=(function(_super){
		function SelectRoleUI(){
			
		    this.list=null;
		    this.role1=null;
		    this.role2=null;
		    this.role3=null;
		    this.role4=null;
		    this.left_btn=null;
		    this.right_btn=null;
		    this.ok_btn=null;

			SelectRoleUI.__super.call(this);
		}

		CLASS$(SelectRoleUI,'ui.SelectRoleUI',_super);
		var __proto__=SelectRoleUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(SelectRoleUI.uiView);
		}

		STATICATTR$(SelectRoleUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"userinfo/select_role_bg.png"}},{"type":"Panel","props":{"y":135,"x":117,"width":680,"var":"list","height":424,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Box","props":{"y":0,"x":0,"width":340,"var":"role1","height":420},"child":[{"type":"Image","props":{"y":16,"x":27,"skin":"userinfo/role_bg.png"}},{"type":"Image","props":{"y":30,"x":51,"skin":"userinfo/role_1.png","name":"role"}},{"type":"Image","props":{"y":0,"x":11,"visible":false,"skin":"userinfo/selected.png","name":"selected"}}]},{"type":"Box","props":{"y":0,"x":340,"width":340,"var":"role2","height":420},"child":[{"type":"Image","props":{"y":16,"x":27,"skin":"userinfo/role_bg.png"}},{"type":"Image","props":{"y":30,"x":51,"skin":"userinfo/role_2.png","name":"role"}},{"type":"Image","props":{"y":0,"x":11,"visible":false,"skin":"userinfo/selected.png","name":"selected"}}]},{"type":"Box","props":{"y":0,"x":680,"width":340,"var":"role3","height":420},"child":[{"type":"Image","props":{"y":16,"x":27,"skin":"userinfo/role_bg.png"}},{"type":"Image","props":{"y":30,"x":51,"skin":"userinfo/role_3.png","name":"role"}},{"type":"Image","props":{"y":0,"x":11,"visible":false,"skin":"userinfo/selected.png","name":"selected"}}]},{"type":"Box","props":{"y":0,"x":1020,"width":340,"var":"role4","height":420},"child":[{"type":"Image","props":{"y":16,"x":27,"skin":"userinfo/role_bg.png"}},{"type":"Image","props":{"y":30,"x":51,"skin":"userinfo/role_4.png","name":"role"}},{"type":"Image","props":{"y":0,"x":11,"visible":false,"skin":"userinfo/selected.png","name":"selected"}}]}]},{"type":"Button","props":{"y":323,"x":72,"var":"left_btn","stateNum":"1","skin":"userinfo/left_btn.png"}},{"type":"Button","props":{"y":323,"x":797,"var":"right_btn","stateNum":"1","skin":"userinfo/right_btn.png"}},{"type":"Button","props":{"y":543,"x":363,"var":"ok_btn","stateNum":"1","skin":"userinfo/ok_btn.png"}}]};}
		]);
		return SelectRoleUI;
	})(Dialog);
var SelectYanUI=(function(_super){
		function SelectYanUI(){
			
		    this.yan_list=null;
		    this.ok_btn=null;

			SelectYanUI.__super.call(this);
		}

		CLASS$(SelectYanUI,'ui.SelectYanUI',_super);
		var __proto__=SelectYanUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(SelectYanUI.uiView);
		}

		STATICATTR$(SelectYanUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"luckdraw/xuanzhe.png","sizeGrid":"42,25,73,23"}},{"type":"List","props":{"y":81,"x":156,"width":526,"visible":false,"var":"yan_list","repeatY":1,"repeatX":7,"height":128,"hScrollBarSkin":"ui/hscroll.png"},"child":[{"type":"Box","props":{"y":5,"x":0,"name":"render"},"child":[{"type":"Image","props":{"skin":"luckdraw/item_bg.png"}},{"type":"Image","props":{"y":3,"x":4,"width":84,"name":"icon","height":84}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"luckdraw/item_selected.png","name":"selected"}},{"type":"Label","props":{"y":88,"x":1,"wordWrap":true,"width":90,"valign":"middle","name":"name","height":34,"fontSize":16,"font":"SimHei","color":"#ffffff","align":"center"}},{"type":"Label","props":{"y":61,"x":38,"width":44,"valign":"middle","strokeColor":"#000000","stroke":2,"name":"num","height":20,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"right"}}]}]},{"type":"Button","props":{"y":217,"x":508,"var":"ok_btn","stateNum":"2","skin":"luckdraw/queding.png"}},{"type":"Button","props":{"y":217,"x":226,"stateNum":"2","skin":"luckdraw/quxiao.png","name":"close"}}]};}
		]);
		return SelectYanUI;
	})(Dialog);
var shareUI=(function(_super){
		function shareUI(){
			

			shareUI.__super.call(this);
		}

		CLASS$(shareUI,'ui.shareUI',_super);
		var __proto__=shareUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(shareUI.uiView);
		}

		STATICATTR$(shareUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"loading/share.png"}}]};}
		]);
		return shareUI;
	})(Dialog);
var ShengjiSuccessUI=(function(_super){
		function ShengjiSuccessUI(){
			
		    this.bg=null;
		    this.icon=null;
		    this.text=null;

			ShengjiSuccessUI.__super.call(this);
		}

		CLASS$(ShengjiSuccessUI,'ui.ShengjiSuccessUI',_super);
		var __proto__=ShengjiSuccessUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ShengjiSuccessUI.uiView);
		}

		STATICATTR$(ShengjiSuccessUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"var":"bg","skin":"pinjian/shengji_ok.png"}},{"type":"Button","props":{"y":235,"x":116,"stateNum":"1","skin":"pinjian/button_ok.png","name":"close"}},{"type":"Image","props":{"y":97,"x":145,"width":64,"var":"icon","height":64}},{"type":"Label","props":{"y":167,"x":43,"wordWrap":true,"width":273,"var":"text","valign":"middle","leading":5,"height":74,"fontSize":20,"font":"SimHei","color":"#6c301c","align":"center"}}]};}
		]);
		return ShengjiSuccessUI;
	})(Dialog);
var ShiJiangUI=(function(_super){
		function ShiJiangUI(){
			
		    this.list=null;

			ShiJiangUI.__super.call(this);
		}

		CLASS$(ShiJiangUI,'ui.ShiJiangUI',_super);
		var __proto__=ShiJiangUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ShiJiangUI.uiView);
		}

		STATICATTR$(ShiJiangUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"width":735,"skin":"shijiang/tufashijian_1.png","sizeGrid":"54,27,17,27","height":388}},{"type":"List","props":{"y":53,"x":12,"width":708,"var":"list","vScrollBarSkin":"ui/vscroll.png","repeatY":3,"repeatX":1,"height":324},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"shijiang/shijiange.png"}},{"type":"Button","props":{"y":30,"x":484,"stateNum":"2","skin":"shijiang/huolue.png","name":"hulue"}},{"type":"Button","props":{"y":30,"x":569,"stateNum":"2","skin":"shijiang/qianquchuli.png","name":"chuli"}},{"type":"Label","props":{"y":5,"x":30,"width":100,"valign":"middle","name":"name","height":30,"fontSize":24,"font":"SimHei","color":"#442502"}},{"type":"Label","props":{"y":5,"x":138,"width":319,"valign":"middle","name":"time","height":30,"fontSize":24,"font":"SimHei","color":"#442502"}},{"type":"Label","props":{"y":42,"x":14,"wordWrap":true,"width":457,"valign":"middle","name":"content","leading":5,"height":54,"fontSize":24,"font":"SimHei","color":"#442502"}}]}]},{"type":"Button","props":{"y":-25,"x":682,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}}]};}
		]);
		return ShiJiangUI;
	})(Dialog);
var ShouGeUI=(function(_super){
		function ShouGeUI(){
			
		    this.shouge_btn=null;
		    this.yijian_btn=null;

			ShouGeUI.__super.call(this);
		}

		CLASS$(ShouGeUI,'ui.ShouGeUI',_super);
		var __proto__=ShouGeUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ShouGeUI.uiView);
		}

		STATICATTR$(ShouGeUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"y":300,"x":120,"pivotY":300,"pivotX":120},"child":[{"type":"Image","props":{"y":115,"x":0,"skin":"bozhong/shouhuo_1.png"}},{"type":"Button","props":{"y":125,"x":22,"var":"shouge_btn","stateNum":"2","skin":"bozhong/button_shouhuo.png"}},{"type":"Button","props":{"y":125,"x":175,"var":"yijian_btn","stateNum":"2","skin":"bozhong/button_yijian.png"}},{"type":"Image","props":{"y":0,"x":44,"skin":"bozhong/ren.png"}}]};}
		]);
		return ShouGeUI;
	})(Dialog);
var SignInUI=(function(_super){
		function SignInUI(){
			
		    this.sign=null;
		    this.task=null;
		    this.view_stack=null;
		    this.day01=null;
		    this.day02=null;
		    this.day03=null;
		    this.day04=null;
		    this.day05=null;
		    this.day06=null;
		    this.day07=null;
		    this.signin_btn=null;
		    this.day_task=null;
		    this.new_day_task=null;
		    this.scan_day_task=null;

			SignInUI.__super.call(this);
		}

		CLASS$(SignInUI,'ui.SignInUI',_super);
		var __proto__=SignInUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(SignInUI.uiView);
		}

		STATICATTR$(SignInUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":2,"x":136,"skin":"sign/sign_dialog_bg.png","sizeGrid":"0,0,0,0"}},{"type":"Button","props":{"y":-6,"x":852,"stateNum":"2","skin":"ui/guanbi.png","sizeGrid":"5,5,5,5","name":"close"}},{"type":"Button","props":{"y":4,"x":225,"var":"sign","toggle":true,"stateNum":"2","skin":"sign/7tian.png"}},{"type":"Button","props":{"y":10,"x":473,"var":"task","toggle":true,"stateNum":"2","skin":"sign/meiri.png"}},{"type":"ViewStack","props":{"y":90,"x":170,"width":700,"var":"view_stack","height":370},"child":[{"type":"Box","props":{"width":700,"name":"item0","height":370},"child":[{"type":"Box","props":{"y":7,"x":96,"width":122,"var":"day01","height":150},"child":[{"type":"Image","props":{"skin":"sign/sign_day_bg.png"}},{"type":"Label","props":{"y":102,"x":15,"width":87,"name":"good_num","height":26,"fontSize":20,"font":"Arial","color":"#773a1f","align":"center"}},{"type":"Label","props":{"y":20,"x":47,"width":27,"text":"第一天","height":12,"font":"Microsoft YaHei","color":"#773a1f","bold":true}},{"type":"Image","props":{"y":73,"x":61,"width":66,"name":"day01_good","height":66,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"sign/signed_day_bg.png","name":"signed_bg"},"child":[{"type":"Image","props":{"y":37,"x":19,"skin":"sign/gou.png"}}]}]},{"type":"Box","props":{"y":7,"x":222,"width":122,"var":"day02","height":150},"child":[{"type":"Image","props":{"skin":"sign/sign_day_bg.png"}},{"type":"Label","props":{"y":102,"x":15,"width":87,"name":"good_num","height":26,"fontSize":20,"font":"Arial","color":"#773a1f","align":"center"}},{"type":"Label","props":{"y":20,"x":47,"width":27,"text":"第二天","height":12,"font":"Microsoft YaHei","color":"#773a1f","bold":true}},{"type":"Image","props":{"y":73,"x":61,"width":66,"name":"day01_good","height":66,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"sign/signed_day_bg.png","name":"signed_bg"},"child":[{"type":"Image","props":{"y":37,"x":19,"skin":"sign/gou.png"}}]}]},{"type":"Box","props":{"y":7,"x":352,"width":122,"var":"day03","height":150},"child":[{"type":"Image","props":{"skin":"sign/sign_day_bg.png"}},{"type":"Label","props":{"y":102,"x":15,"width":87,"name":"good_num","height":26,"fontSize":20,"font":"Arial","color":"#773a1f","align":"center"}},{"type":"Label","props":{"y":20,"x":47,"width":27,"text":"第三天","height":12,"font":"Microsoft YaHei","color":"#773a1f","bold":true}},{"type":"Image","props":{"y":73,"x":61,"width":66,"name":"day01_good","height":66,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"sign/signed_day_bg.png","name":"signed_bg"},"child":[{"type":"Image","props":{"y":37,"x":19,"skin":"sign/gou.png"}}]}]},{"type":"Box","props":{"y":7,"x":481,"width":122,"var":"day04","height":150},"child":[{"type":"Image","props":{"skin":"sign/sign_day_bg.png"}},{"type":"Label","props":{"y":102,"x":15,"width":87,"name":"good_num","height":26,"fontSize":20,"font":"Arial","color":"#773a1f","align":"center"}},{"type":"Label","props":{"y":20,"x":47,"width":27,"text":"第四天","height":12,"font":"Microsoft YaHei","color":"#773a1f","bold":true}},{"type":"Image","props":{"y":73,"x":61,"width":66,"name":"day01_good","height":66,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"sign/signed_day_bg.png","name":"signed_bg"},"child":[{"type":"Image","props":{"y":37,"x":19,"skin":"sign/gou.png"}}]}]},{"type":"Box","props":{"y":162,"x":96,"width":122,"var":"day05","height":150},"child":[{"type":"Image","props":{"skin":"sign/sign_day_bg.png"}},{"type":"Label","props":{"y":102,"x":15,"width":87,"name":"good_num","height":26,"fontSize":20,"font":"Arial","color":"#773a1f","align":"center"}},{"type":"Label","props":{"y":20,"x":47,"width":27,"text":"第五天","height":12,"font":"Microsoft YaHei","color":"#773a1f","bold":true}},{"type":"Image","props":{"y":73,"x":61,"width":66,"name":"day01_good","height":66,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"sign/signed_day_bg.png","name":"signed_bg"},"child":[{"type":"Image","props":{"y":37,"x":19,"skin":"sign/gou.png"}}]}]},{"type":"Box","props":{"y":162,"x":222,"width":122,"var":"day06","height":150},"child":[{"type":"Image","props":{"skin":"sign/sign_day_bg.png"}},{"type":"Label","props":{"y":102,"x":15,"width":87,"name":"good_num","height":26,"fontSize":20,"font":"Arial","color":"#773a1f","align":"center"}},{"type":"Label","props":{"y":20,"x":47,"width":27,"text":"第六天","height":12,"font":"Microsoft YaHei","color":"#773a1f","bold":true}},{"type":"Image","props":{"y":73,"x":61,"width":66,"name":"day01_good","height":66,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":0,"x":0,"visible":false,"skin":"sign/signed_day_bg.png","name":"signed_bg"},"child":[{"type":"Image","props":{"y":37,"x":19,"skin":"sign/gou.png"}}]}]},{"type":"Box","props":{"y":162,"x":352,"width":238,"var":"day07","height":150},"child":[{"type":"Image","props":{"skin":"sign/sign_day_bg02.png"}},{"type":"Label","props":{"y":109,"x":61,"width":125,"name":"good_num","height":20,"fontSize":20,"font":"Arial","color":"#773a1f","align":"center"}},{"type":"Label","props":{"y":20,"x":105,"width":27,"text":"第七天","height":12,"font":"Microsoft YaHei","color":"#773a1f","bold":true}},{"type":"Image","props":{"y":73,"x":119,"width":66,"name":"day01_good","height":66,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"visible":false,"skin":"sign/signed_day_bg02.png","name":"signed_bg"},"child":[{"type":"Image","props":{"y":40,"x":81,"skin":"sign/gou.png"}}]}]},{"type":"Button","props":{"y":309,"x":245,"var":"signin_btn","stateNum":"2","skin":"sign/sign_btn.png"}}]},{"type":"Box","props":{"width":700,"name":"item1","height":370},"child":[{"type":"List","props":{"y":0,"x":0,"width":700,"visible":false,"var":"day_task","spaceY":5,"repeatY":3,"repeatX":1,"height":370},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"sign/renwulan.png"}},{"type":"Image","props":{"y":21,"x":388,"skin":"dati/diwen_2.png"}},{"type":"Image","props":{"y":25,"x":393,"width":68,"name":"icon","height":68}},{"type":"Label","props":{"y":50,"x":44,"wordWrap":true,"width":345,"name":"task_title","height":55,"fontSize":26,"font":"SimHei","color":"#6e302f","align":"left"}},{"type":"Label","props":{"y":8,"x":16,"width":82,"valign":"middle","text":"任务","name":"task_index","height":34,"fontSize":26,"font":"SimHei","color":"#46311d"}},{"type":"Label","props":{"y":70,"x":392,"width":70,"valign":"middle","text":"奖励物品","strokeColor":"#000000","stroke":3,"height":24,"fontSize":18,"font":"SimHei","color":"#ffffff","align":"center"}},{"type":"Label","props":{"y":47,"x":552,"text":"进行中","fontSize":24,"font":"SimHei","color":"#000000"}},{"type":"Button","props":{"y":33,"x":517,"stateNum":"2","skin":"sign/lingqu.png","name":"lingqu"}},{"type":"Label","props":{"y":11,"x":335,"width":51,"valign":"middle","text":"0","name":"need_num","height":32,"fontSize":32,"font":"SimHei","color":"#46311d","align":"left"}},{"type":"Label","props":{"y":11,"x":326,"width":61,"valign":"middle","text":"0","name":"curr_num","height":32,"fontSize":32,"font":"SimHei","color":"#ff1c00","anchorX":1,"align":"right"}},{"type":"Image","props":{"y":9,"x":317,"skin":"sign/xiegang.png"}},{"type":"Label","props":{"y":37,"x":463,"width":33,"strokeColor":"#000000","stroke":2,"pivotY":10.447761194029852,"pivotX":35.82089552238807,"name":"num","height":20,"fontSize":20,"color":"#f3ebea","align":"right"}}]}]},{"type":"List","props":{"y":2,"x":10,"width":680,"var":"new_day_task","spaceX":30,"height":163},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"sign/small_bg.png"}},{"type":"Image","props":{"y":5,"x":68,"width":70,"skin":"bakeroom/kuang.png","height":70}},{"type":"Label","props":{"y":78,"x":9,"width":187,"valign":"middle","overflow":"hidden","name":"item_name","height":20,"fontSize":14,"font":"SimHei","align":"center"}},{"type":"Image","props":{"y":10,"x":74,"width":60,"name":"icon","height":60}},{"type":"Label","props":{"y":12,"x":6,"wordWrap":true,"width":20,"name":"task_index","height":65,"fontSize":20,"font":"SimHei"}},{"type":"Label","props":{"y":104,"x":6,"width":194,"valign":"middle","name":"task_title","height":24,"fontSize":16,"font":"SimHei"}},{"type":"Label","props":{"y":149,"x":79,"text":"进行中","name":"tips","fontSize":16,"font":"SimHei"}},{"type":"Button","props":{"y":126,"x":53,"width":100,"stateNum":"2","skin":"sign/lingqu.png","name":"lingqu","height":37}},{"type":"Label","props":{"y":6,"x":173,"width":29,"valign":"middle","text":"0","name":"need_num","height":32,"fontSize":20,"font":"SimHei","color":"#46311d","align":"left"}},{"type":"Label","props":{"y":6,"x":166,"width":36,"valign":"middle","text":"0","name":"curr_num","height":32,"fontSize":20,"font":"SimHei","color":"#ff1c00","anchorX":1,"align":"right"}},{"type":"Label","props":{"y":6,"x":160,"width":20,"valign":"middle","text":"/","height":32,"fontSize":20,"font":"SimHei","align":"center"}}]}]},{"type":"List","props":{"y":183,"x":10,"width":680,"var":"scan_day_task","spaceX":42,"repeatY":1,"repeatX":2,"height":183},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"skin":"sign/big_bg.png"}},{"type":"Label","props":{"y":12,"x":6,"wordWrap":true,"width":20,"name":"task_index","height":65,"fontSize":20,"font":"SimHei"}},{"type":"Label","props":{"y":107,"x":6,"width":194,"valign":"middle","name":"task_title","height":24,"fontSize":16,"font":"SimHei"}},{"type":"Button","props":{"y":129,"x":109,"width":100,"stateNum":"2","skin":"sign/lingqu.png","name":"lingqu","height":37}},{"type":"Label","props":{"y":109,"x":282,"width":29,"valign":"middle","text":"0","name":"need_num","height":32,"fontSize":20,"font":"SimHei","color":"#46311d","align":"left"}},{"type":"Label","props":{"y":109,"x":275,"width":36,"valign":"middle","text":"0","name":"curr_num","height":32,"fontSize":20,"font":"SimHei","color":"#ff1c00","anchorX":1,"align":"right"}},{"type":"Label","props":{"y":109,"x":269,"width":20,"valign":"middle","text":"/","height":32,"fontSize":20,"font":"SimHei","align":"center"}},{"type":"List","props":{"y":4,"x":36,"width":266,"spaceX":8,"name":"item_list","height":98},"child":[{"type":"Box","props":{"y":0,"x":-1,"width":83,"name":"render","height":98},"child":[{"type":"Image","props":{"y":0,"x":6,"width":70,"skin":"bakeroom/kuang.png","height":70}},{"type":"Image","props":{"y":5,"x":12,"width":60,"name":"icon","height":60}},{"type":"Label","props":{"y":68,"x":-4,"wordWrap":true,"width":90,"valign":"middle","name":"item_name","height":28,"fontSize":14,"font":"SimHei","align":"center"}}]}]},{"type":"Label","props":{"y":150,"x":135,"text":"进行中","name":"tips","fontSize":16,"font":"SimHei"}}]}]}]}]},{"type":"Image","props":{"y":55,"x":-47,"skin":"sign/sign_person_bg.png"}}]};}
		]);
		return SignInUI;
	})(Dialog);
var SignInResultUI=(function(_super){
		function SignInResultUI(){
			
		    this.goods_icon=null;
		    this.show_text=null;

			SignInResultUI.__super.call(this);
		}

		CLASS$(SignInResultUI,'ui.SignInResultUI',_super);
		var __proto__=SignInResultUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(SignInResultUI.uiView);
		}

		STATICATTR$(SignInResultUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":510,"height":394},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"sign/lingqu_bg.png"}},{"type":"Button","props":{"y":295,"x":168,"stateNum":"2","skin":"sign/lingqu_btn.png","name":"close"}},{"type":"Image","props":{"y":180,"x":255,"width":66,"var":"goods_icon","pivotY":35,"pivotX":31,"height":66}},{"type":"Label","props":{"y":253,"x":75,"width":380,"var":"show_text","name":"show_text","height":26,"fontSize":18,"color":"#712f1f","bold":true,"align":"center"}}]};}
		]);
		return SignInResultUI;
	})(Dialog);
var StoryUI=(function(_super){
		function StoryUI(){
			
		    this.item0=null;
		    this.chuan=null;
		    this.item1=null;
		    this.item2=null;
		    this.item3=null;
		    this.chuanyue_btn=null;
		    this.jump_btn=null;
		    this.chuanyue_ani=null;
		    this.jiantou=null;
		    this.finger=null;
		    this.jiantou1=null;

			StoryUI.__super.call(this);
		}

		CLASS$(StoryUI,'ui.StoryUI',_super);
		var __proto__=StoryUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(StoryUI.uiView);
		}

		STATICATTR$(StoryUI,
		['uiView',function(){return this.uiView={"type":"View","props":{},"child":[{"type":"Box","props":{"var":"item0"},"child":[{"type":"Image","props":{"skin":"story/1.png"}},{"type":"Animation","props":{"y":373,"x":283,"var":"chuan","source":"story/ani_chuan_1.png,story/ani_chuan_2.png,story/ani_chuan_3.png,story/ani_chuan_4.png,story/ani_chuan_5.png,story/ani_chuan_6.png","interval":300,"autoPlay":false}},{"type":"Image","props":{"y":572,"x":0,"skin":"story/tips_1.png"}}]},{"type":"Box","props":{"visible":false,"var":"item1"},"child":[{"type":"Image","props":{"skin":"story/2.png"}},{"type":"Animation","props":{"y":395,"x":170,"source":"story/hongkaodongzhuo1.png,story/hongkaodongzhuo2.png,story/hongkaodongzhuo3.png","interval":500,"autoPlay":true}},{"type":"Animation","props":{"y":327,"x":779,"source":"story/saiyan1.png,story/saiyan2.png","interval":500,"autoPlay":true}},{"type":"Image","props":{"y":572,"x":0,"skin":"story/tips_2.png"}},{"type":"Image","props":{"y":22,"x":167,"skin":"story/tuihuo_1.png"}}]},{"type":"Box","props":{"visible":false,"var":"item2"},"child":[{"type":"Image","props":{"skin":"story/3.png"}},{"type":"Animation","props":{"y":226,"x":47,"width":0,"source":"story/ani_3_1.png,story/ani_3_2.png,story/ani_3_3.png,story/ani_3_4.png","interval":500,"height":0,"autoPlay":true}},{"type":"Image","props":{"y":572,"skin":"story/tips_3.png"}}]},{"type":"Box","props":{"visible":false,"var":"item3"},"child":[{"type":"Image","props":{"skin":"story/3.png"}},{"type":"Image","props":{"y":227,"x":47,"skin":"story/ani_3_1.png"}},{"type":"Image","props":{"y":77,"x":34,"skin":"story/4.png"}},{"type":"Button","props":{"y":577,"x":479,"var":"chuanyue_btn","stateNum":"1","skin":"story/chuanyue.png","anchorY":0.5,"anchorX":0.5}}]},{"type":"Button","props":{"y":34,"x":907,"var":"jump_btn","stateNum":"1","skin":"story/jump_btn.png","anchorY":0.5,"anchorX":0.5}},{"type":"Animation","props":{"visible":false,"var":"chuanyue_ani","source":"story/ani_chuanyue_1.png,story/ani_chuanyue_2.png,story/ani_chuanyue_3.png,story/ani_chuanyue_4.png,story/ani_chuanyue_5.png,story/ani_chuanyue_6.png,story/ani_chuanyue_7.png,story/ani_chuanyue_8.png","interval":200,"index":0,"autoPlay":false}},{"type":"Box","props":{"y":28,"x":299},"child":[{"type":"Animation","props":{"y":43,"x":295.9999999999999,"width":66,"var":"jiantou","source":"story/baijiantou_1.png,story/baijiantou_2.png","pivotY":43,"interval":300,"height":30,"autoPlay":true}},{"type":"Image","props":{"y":11,"x":193,"var":"finger","skin":"story/finger.png"}},{"type":"Animation","props":{"y":43,"x":65.99999999999989,"width":66,"var":"jiantou1","source":"story/baijiantou_1.png,story/baijiantou_2.png","skewY":180,"pivotY":43,"interval":300,"height":30,"autoPlay":true}}]}]};}
		]);
		return StoryUI;
	})(View);
var TestEndUI=(function(_super){
		function TestEndUI(){
			
		    this.Jiangpin=null;

			TestEndUI.__super.call(this);
		}

		CLASS$(TestEndUI,'ui.TestEndUI',_super);
		var __proto__=TestEndUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(TestEndUI.uiView);
		}

		STATICATTR$(TestEndUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"test_end/test_end.png"}},{"type":"Button","props":{"y":511,"x":4,"width":120,"var":"Jiangpin","stateNum":"2","skin":"ui/jiangpingtubiao.png","height":113}}]};}
		]);
		return TestEndUI;
	})(Dialog);
var ThiefUI=(function(_super){
		function ThiefUI(){
			
		    this.say=null;
		    this.btn_ok=null;

			ThiefUI.__super.call(this);
		}

		CLASS$(ThiefUI,'ui.ThiefUI',_super);
		var __proto__=ThiefUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ThiefUI.uiView);
		}

		STATICATTR$(ThiefUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":150,"x":0,"skin":"jiandie/jiandie_beizhua.png"}},{"type":"Image","props":{"y":171,"x":198,"skin":"jiandie/duihuakuang.png"}},{"type":"Image","props":{"y":221,"x":267,"var":"say","skin":"jiandie/duihua_1_1.png"}},{"type":"Image","props":{"y":317,"x":278,"skin":"jiandie/duihuakuang_1.png"}},{"type":"Button","props":{"y":328,"x":351,"var":"btn_ok","stateNum":"2","skin":"jiandie/duihua_1_2.png","name":"yes"}}]};}
		]);
		return ThiefUI;
	})(Dialog);
var tipsUI=(function(_super){
		function tipsUI(){
			
		    this.girl=null;
		    this.content=null;
		    this.BZTS=null;
		    this.ok_btn=null;
		    this.use_lebi_btn=null;
		    this.use_ledou_btn=null;
		    this.cancel_btn=null;
		    this.yes_btn=null;
		    this.bye=null;

			tipsUI.__super.call(this);
		}

		CLASS$(tipsUI,'ui.tipsUI',_super);
		var __proto__=tipsUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(tipsUI.uiView);
		}

		STATICATTR$(tipsUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":1,"skin":"zhiyin/zhiying_diban2.png"}},{"type":"Image","props":{"y":57,"x":-138,"var":"girl","skin":"zhiyin/zhiying_renwu_banshen.png"}},{"type":"HTMLDivElement","props":{"y":41,"x":39,"width":480,"var":"content","height":215}},{"type":"CheckBox","props":{"y":265,"x":377,"var":"BZTS","skin":"zhiyin/zhiying_buzaitixing_2.png","scaleY":1.2,"scaleX":1.2},"child":[{"type":"Image","props":{"y":8,"x":34,"skin":"zhiyin/zhiying_buzaitixing_1.png"}}]},{"type":"Button","props":{"y":254,"x":200,"var":"ok_btn","stateNum":"2","skin":"zhiyin/zhiying_buzaitixing_3.png","name":"ok"}},{"type":"Button","props":{"y":179,"x":305,"visible":false,"var":"use_lebi_btn","stateNum":"2","skin":"zhiyin/zhiying_lebi.png"}},{"type":"Button","props":{"y":179,"x":79,"visible":false,"var":"use_ledou_btn","stateNum":"2","skin":"zhiyin/zhiying_ledou.png"}},{"type":"Button","props":{"y":252,"x":199,"visible":false,"var":"cancel_btn","stateNum":"2","skin":"zhiyin/zhiying_zhanbukaiqi.png","name":"cancel"}},{"type":"Button","props":{"y":180,"x":309,"visible":false,"var":"yes_btn","stateNum":"2","skin":"zhiyin/zhiying_zhanbukaiqi-38.png","name":"yes"}},{"type":"Button","props":{"y":250,"x":203,"visible":false,"var":"bye","stateNum":"2","skin":"zhiyin/zhiying_zaijian.png","name":"ok"}}]};}
		]);
		return tipsUI;
	})(Dialog);
var TuiJianUI=(function(_super){
		function TuiJianUI(){
			
		    this.tab=null;
		    this.has_apply=null;
		    this.view_stack=null;
		    this.tuijian_list=null;
		    this.add_all_btn=null;
		    this.change_btn=null;
		    this.apply_list=null;
		    this.agree_all_btn=null;

			TuiJianUI.__super.call(this);
		}

		CLASS$(TuiJianUI,'ui.TuiJianUI',_super);
		var __proto__=TuiJianUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(TuiJianUI.uiView);
		}

		STATICATTR$(TuiJianUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"skin":"friend/haoyou_diban3.png"}},{"type":"Tab","props":{"y":18,"x":279,"var":"tab","stateNum":2,"skin":"friend/tab.png","selectedIndex":0,"labels":"推荐,申请","labelSize":20,"labelColors":"#3e1600,#3e1600","labelBold":true}},{"type":"Image","props":{"y":22,"x":545,"visible":false,"var":"has_apply","skin":"friend/tishidian.png"}},{"type":"ViewStack","props":{"y":107,"x":52,"var":"view_stack","selectedIndex":0},"child":[{"type":"Box","props":{"name":"item0"},"child":[{"type":"List","props":{"y":0,"x":0,"width":758,"var":"tuijian_list","vScrollBarSkin":"ui/vscroll.png","repeatY":3,"height":291},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"friend/haoyou_diban3_2.png"}},{"type":"Image","props":{"y":9,"x":11,"width":80,"name":"thumb","height":80}},{"type":"Image","props":{"y":7,"x":5,"width":91,"skin":"friend/touxiang_2_2.png","height":83}},{"type":"Label","props":{"y":6,"x":105,"width":300,"valign":"middle","underline":true,"name":"nickname","height":24,"fontSize":20,"font":"SimHei","color":"#e7e7e7"}},{"type":"Label","props":{"y":38,"x":105,"width":99,"valign":"middle","underline":true,"name":"level","height":24,"fontSize":20,"font":"SimHei","color":"#f6dbb5"}},{"type":"Label","props":{"y":69,"x":105,"width":300,"valign":"middle","underline":true,"name":"login_time","height":24,"fontSize":20,"font":"SimHei","color":"#f6dbb5"}},{"type":"Button","props":{"y":24,"x":645,"width":105,"stateNum":"2","skin":"friend/tongyi-07.png","name":"add_btn","height":48}}]}]},{"type":"Box","props":{"y":315,"x":206},"child":[{"type":"Button","props":{"var":"add_all_btn","stateNum":"2","skin":"friend/tongyi-11.png"}},{"type":"Button","props":{"x":201,"var":"change_btn","stateNum":"2","skin":"friend/tongyi-09.png"}}]}]},{"type":"Box","props":{"name":"item1"},"child":[{"type":"List","props":{"width":758,"var":"apply_list","vScrollBarSkin":"ui/vscroll.png","repeatY":3,"height":291},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"skin":"friend/haoyou_diban3_2.png"}},{"type":"Image","props":{"y":9,"x":11,"width":80,"name":"thumb","height":80}},{"type":"Image","props":{"y":7,"x":5,"width":91,"skin":"friend/touxiang_2_2.png","height":83}},{"type":"Label","props":{"y":6,"x":105,"width":300,"valign":"middle","underline":true,"name":"nickname","height":24,"fontSize":20,"font":"SimHei","color":"#e7e7e7"}},{"type":"Label","props":{"y":38,"x":105,"width":99,"valign":"middle","underline":true,"name":"level","height":24,"fontSize":20,"font":"SimHei","color":"#f6dbb5"}},{"type":"Label","props":{"y":69,"x":105,"width":300,"valign":"middle","underline":true,"name":"login_time","height":24,"fontSize":20,"font":"SimHei","color":"#f6dbb5"}},{"type":"Button","props":{"y":24,"x":645,"width":105,"stateNum":"2","skin":"friend/tongyi.png","name":"agree_btn","height":48}},{"type":"Button","props":{"y":24,"x":535,"width":105,"stateNum":"2","skin":"friend/jujue.png","name":"jujue_btn","height":48}}]}]},{"type":"Box","props":{"y":315,"x":307},"child":[{"type":"Button","props":{"y":0,"x":0,"var":"agree_all_btn","stateNum":"2","skin":"friend/tongyi-05.png"}}]}]}]},{"type":"Button","props":{"y":-19,"x":821,"stateNum":"2","skin":"ui/button_guanbi.png","name":"close"}}]};}
		]);
		return TuiJianUI;
	})(Dialog);
var UILayerUI=(function(_super){
		function UILayerUI(){
			
		    this.ProgressExp=null;
		    this.ExpText=null;
		    this.header_img=null;
		    this.header_frame=null;
		    this.NickName=null;
		    this.level=null;
		    this.music_btn=null;
		    this.bg_shandian=null;
		    this.Shandian=null;
		    this.add_shandian_btn=null;
		    this.sale=null;
		    this.bg_bean=null;
		    this.Bean=null;
		    this.bg_money=null;
		    this.Gold=null;
		    this.add_money_btn=null;
		    this.sale_1=null;
		    this.Activity_bg=null;
		    this.Signin=null;
		    this.Activity=null;
		    this.friend_btn=null;
		    this.Dati=null;
		    this.Activity_btn=null;
		    this.ranking_btn=null;
		    this.zhaoji_btn=null;
		    this.Gonglue=null;
		    this.chaxun=null;
		    this.Shijian=null;
		    this.ShiJian_tips=null;
		    this.ShiJian_num=null;
		    this.Jiangpin=null;
		    this.dazhuanpan_btn=null;
		    this.friend_panel=null;
		    this.BackHome=null;
		    this.fire_btn=null;

			UILayerUI.__super.call(this);
		}

		CLASS$(UILayerUI,'ui.UILayerUI',_super);
		var __proto__=UILayerUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(UILayerUI.uiView);
		}

		STATICATTR$(UILayerUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":960,"mouseThrough":true,"height":640},"child":[{"type":"Image","props":{"y":13,"x":101,"width":283,"skin":"ui/zhanghao_diban.png","scaleY":0.8,"scaleX":0.8,"name":"info_bg","height":79},"child":[{"type":"ProgressBar","props":{"y":4,"x":0,"width":197,"var":"ProgressExp","value":1,"skin":"ui/progress.png","sizeGrid":"10,8,10,8","height":35},"child":[{"type":"Label","props":{"y":7,"x":7,"width":184,"var":"ExpText","valign":"middle","strokeColor":"#000000","stroke":3,"height":20,"fontSize":20,"color":"#ffffff","align":"center"}}]},{"type":"Image","props":{"y":49,"x":-58,"width":98,"var":"header_img","skin":"ui/header.jpg","height":98,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":49,"x":-58,"var":"header_frame","skin":"ui/touxiangkuang_0.png","anchorY":0.5,"anchorX":0.5}},{"type":"Label","props":{"y":43,"x":10,"width":237,"var":"NickName","valign":"middle","strokeColor":"#000000","stroke":3,"overflow":"hidden","height":30,"fontSize":24,"color":"#ffffff","align":"left"}},{"type":"Image","props":{"y":-19,"x":201,"skin":"ui/xing1.png","name":"level_bg"},"child":[{"type":"Label","props":{"y":24,"x":25,"width":30,"var":"level","valign":"middle","text":"1","strokeColor":"#000000","stroke":3,"height":27,"fontSize":26,"color":"#ffffff","align":"center"}}]},{"type":"Button","props":{"y":78,"x":14,"var":"music_btn","toggle":true,"stateNum":"2","skin":"ui/yinfu.png","selected":false}}]},{"type":"Box","props":{"y":13,"x":407,"name":"gold_info","cacheAs":"normal"},"child":[{"type":"Box","props":{"y":-4,"x":-20},"child":[{"type":"Image","props":{"y":4,"var":"bg_shandian","skin":"ui/ditu_diban2.png"}},{"type":"Image","props":{"y":2,"x":12,"skin":"userinfo/sandian.png"}},{"type":"Label","props":{"y":10,"x":60,"width":105,"var":"Shandian","valign":"middle","strokeColor":"#000000","stroke":3,"height":30,"fontSize":22,"color":"#ffffff","align":"center"}},{"type":"Button","props":{"y":0,"x":155,"var":"add_shandian_btn","stateNum":"2","skin":"ui/button_plus.png"}},{"type":"Image","props":{"y":45,"x":5,"width":59,"visible":false,"var":"sale","skin":"shop/9zhe.png","height":40}}]},{"type":"Box","props":{"y":0,"x":371},"child":[{"type":"Image","props":{"var":"bg_bean","skin":"ui/ditu_diban2.png"}},{"type":"Image","props":{"y":0,"x":8,"skin":"userinfo/ledou.png"}},{"type":"Label","props":{"y":6,"x":60,"width":121,"var":"Bean","valign":"middle","strokeColor":"#000000","stroke":3,"height":30,"fontSize":22,"color":"#ffffff","align":"center"}}]},{"type":"Box","props":{"y":-4,"x":176},"child":[{"type":"Image","props":{"y":4,"var":"bg_money","skin":"ui/ditu_diban2.png"}},{"type":"Image","props":{"y":5,"x":11,"width":44,"skin":"userinfo/lebi_big.png","height":44}},{"type":"Label","props":{"y":10,"x":60,"width":105,"var":"Gold","valign":"middle","strokeColor":"#000000","stroke":3,"height":30,"fontSize":22,"color":"#ffffff","align":"center"}},{"type":"Button","props":{"y":0,"x":155,"var":"add_money_btn","stateNum":"2","skin":"ui/button_plus.png"}},{"type":"Image","props":{"y":45,"x":5,"width":59,"visible":false,"var":"sale_1","skin":"shop/9zhe.png","height":40}}]}]},{"type":"Image","props":{"y":65,"x":821,"width":142,"var":"Activity_bg","skin":"ui/tuozhan_kuang.png","sizeGrid":"7,7,7,7","height":531,"cacheAs":"normal"},"child":[{"type":"Button","props":{"y":113,"x":24,"width":132,"var":"Signin","stateNum":"2","skin":"ui/button_qiandao.png","scaleY":0.7,"scaleX":0.7,"height":135}},{"type":"Button","props":{"y":16,"x":29,"var":"Activity","stateNum":"2","skin":"ui/huodong.png","scaleY":0.8,"scaleX":0.8}},{"type":"Button","props":{"y":219,"x":34,"var":"friend_btn","stateNum":"2","skin":"ui/button_haoyou.png","scaleY":0.6,"scaleX":0.6}},{"type":"Button","props":{"y":325,"x":31,"var":"Dati","stateNum":"2","skin":"ui/button_tiaozhan.png","scaleY":0.7,"scaleX":0.7}},{"type":"Button","props":{"y":233,"x":-59,"var":"Activity_btn","toggle":true,"stateNum":"2","skin":"ui/tuozhan_shou.png"}},{"type":"Button","props":{"y":433,"x":31,"width":83,"visible":false,"var":"ranking_btn","stateNum":"2","skin":"ui/ranking.png","height":69}},{"type":"Button","props":{"y":420,"x":31,"width":89,"var":"zhaoji_btn","stateNum":"2","skin":"ui/button_zhaoji.png","height":85}}]},{"type":"Box","props":{"y":141,"x":0,"width":139,"mouseThrough":true,"height":357,"cacheAs":"normal"},"child":[{"type":"Button","props":{"y":-22,"x":12,"width":87,"var":"Gonglue","stateNum":"2","skin":"ui/button_gonglue.png","height":110}},{"type":"Button","props":{"y":103,"x":-2,"width":111,"var":"chaxun","stateNum":"2","skin":"ui/tiaoxiangshuchaxun.png","height":124}},{"type":"Button","props":{"y":224,"x":-19,"visible":false,"var":"Shijian","stateNum":"2","skin":"ui/button_shijian.png"}},{"type":"Button","props":{"y":230,"x":-19,"visible":false,"var":"ShiJian_tips","stateNum":"2","skin":"ui/button_shijian.png"},"child":[{"type":"Label","props":{"y":31,"x":72,"width":50,"var":"ShiJian_num","valign":"middle","text":"0","strokeColor":"#ffffff","stroke":2,"height":21,"fontSize":20,"font":"SimHei","color":"#f60013","bold":true,"align":"right"}}]},{"type":"Button","props":{"y":370,"x":4,"width":120,"var":"Jiangpin","stateNum":"2","skin":"ui/jiangpingtubiao.png","height":113}},{"type":"Button","props":{"y":380,"x":130,"visible":false,"var":"dazhuanpan_btn","stateNum":"2","skin":"ui/dazhuanpan_btn.png"}}]},{"type":"Box","props":{"y":333,"x":841,"visible":false,"var":"friend_panel"},"child":[{"type":"Button","props":{"y":106,"x":6,"var":"BackHome","stateNum":"2","skin":"ui/button_fanhui.png"}},{"type":"Button","props":{"y":0,"x":4,"var":"fire_btn","stateNum":"2","skin":"ui/tiancai_tiancai.png"}}]}]};}
		]);
		return UILayerUI;
	})(View);
var UserInfoUI=(function(_super){
		function UserInfoUI(){
			
		    this.nickname=null;
		    this.level=null;
		    this.bean=null;
		    this.money=null;
		    this.medal=null;
		    this.progress_exp=null;
		    this.progress_text=null;
		    this.Plant=null;
		    this.PlantName=null;
		    this.Zhiyan=null;
		    this.ZhiyanName=null;
		    this.Sale=null;
		    this.SaleName=null;
		    this.Pinjian=null;
		    this.PinjianName=null;
		    this.info_btn=null;
		    this.yijian_btn=null;
		    this.header_btn=null;
		    this.role=null;

			UserInfoUI.__super.call(this);
		}

		CLASS$(UserInfoUI,'ui.UserInfoUI',_super);
		var __proto__=UserInfoUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(UserInfoUI.uiView);
		}

		STATICATTR$(UserInfoUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"width":862,"skin":"userinfo/gerenxinxidiban.png","height":575},"child":[{"type":"Box","props":{"y":61,"x":414,"width":423,"name":"right","height":474},"child":[{"type":"Label","props":{"y":31,"x":160,"width":220,"var":"nickname","valign":"middle","overflow":"hidden","height":32,"fontSize":20,"font":"SimHei","bold":true,"align":"center"}},{"type":"Label","props":{"y":81,"x":160,"width":220,"var":"level","valign":"middle","text":"0级","height":32,"fontSize":20,"font":"SimHei","bold":true,"align":"center"}},{"type":"Label","props":{"y":180,"x":161,"width":216,"var":"bean","valign":"middle","text":"0","height":32,"fontSize":20,"font":"SimHei","bold":true,"align":"center"}},{"type":"Label","props":{"y":230,"x":160,"width":218,"var":"money","valign":"middle","text":"0","height":32,"fontSize":20,"font":"SimHei","bold":true,"align":"center"}},{"type":"Label","props":{"y":279,"x":161,"width":216,"var":"medal","valign":"middle","text":"0","height":32,"fontSize":20,"font":"SimHei","bold":true,"align":"center"}},{"type":"ProgressBar","props":{"y":135,"x":154,"width":233,"var":"progress_exp","skin":"userinfo/progress_exp.png","sizeGrid":"8,5,8,5","height":20},"child":[{"type":"Label","props":{"y":1,"x":11,"width":214,"var":"progress_text","valign":"middle","height":18,"fontSize":20,"font":"SimHei","align":"center"}}]},{"type":"Box","props":{"y":349,"x":46,"width":358,"height":98},"child":[{"type":"Image","props":{"y":64,"x":29,"width":60,"var":"Plant","skin":"userinfo/shuo.png","height":60,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Label","props":{"y":-29,"x":-10,"width":80,"visible":false,"var":"PlantName","valign":"middle","text":"种植成就","strokeColor":"#4b2f07","stroke":3,"height":24,"fontSize":16,"font":"SimHei","color":"#fbefc4","align":"center"}}]},{"type":"Image","props":{"y":64,"x":129,"width":60,"var":"Zhiyan","skin":"userinfo/shuo.png","height":60,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Label","props":{"y":-29,"x":-10,"width":80,"visible":false,"var":"ZhiyanName","valign":"middle","text":"制烟成就","strokeColor":"#4b2f07","stroke":3,"height":24,"fontSize":16,"font":"SimHei","color":"#fbefc4","align":"center"}}]},{"type":"Image","props":{"y":64,"x":228,"width":60,"var":"Sale","skin":"userinfo/shuo.png","height":60,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Label","props":{"y":-29,"x":-10,"width":80,"visible":false,"var":"SaleName","valign":"middle","text":"销售成就","strokeColor":"#4b2f07","stroke":3,"height":24,"fontSize":16,"font":"SimHei","color":"#fbefc4","align":"center"}}]},{"type":"Image","props":{"y":64,"x":328,"width":60,"var":"Pinjian","skin":"userinfo/shuo.png","height":60,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Label","props":{"y":-29,"x":-10,"width":80,"visible":false,"var":"PinjianName","valign":"middle","text":"品鉴成就","strokeColor":"#4b2f07","stroke":3,"height":24,"fontSize":16,"font":"SimHei","color":"#fbefc4","align":"center"}}]}]}]},{"type":"Button","props":{"y":380,"x":3,"width":60,"var":"info_btn","stateNum":"2","skin":"userinfo/button_shiwuduihuan.png","height":139}},{"type":"Button","props":{"y":245,"x":6,"width":58,"var":"yijian_btn","stateNum":"2","skin":"userinfo/yijianxiang.png","height":139}},{"type":"Button","props":{"y":114,"x":6,"width":55,"visible":true,"var":"header_btn","stateNum":"2","skin":"userinfo/touxiangkuang_tab.png","height":126}},{"type":"Image","props":{"y":301,"x":217,"var":"role","anchorY":0.5,"anchorX":0.5}},{"type":"Button","props":{"y":5,"x":801,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}}]}]};}
		]);
		return UserInfoUI;
	})(Dialog);
var welcomeUI=(function(_super){
		function welcomeUI(){
			
		    this.ani=null;
		    this.start_btn=null;
		    this.content=null;
		    this.gift_btn=null;
		    this.lingqu_btn=null;
		    this.xiayibu_btn=null;

			welcomeUI.__super.call(this);
		}

		CLASS$(welcomeUI,'ui.welcomeUI',_super);
		var __proto__=welcomeUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(welcomeUI.uiView);
		}

		STATICATTR$(welcomeUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":78,"x":156,"skin":"zhiyin/zhiying_diban2.png"},"child":[{"type":"Image","props":{"y":-68,"x":209,"skin":"zhiyin/huanyingbiaoti_2.png"}},{"type":"Image","props":{"y":-21,"x":44,"skin":"zhiyin/welcome.png"}}]},{"type":"Animation","props":{"width":232,"var":"ani","source":"zhiyin/zhiying_renwu_donghua_1_1.png,zhiyin/zhiying_renwu_donghua_1_2.png,zhiyin/zhiying_renwu_donghua_1_3.png,zhiyin/zhiying_renwu_donghua_1_4.png,zhiyin/zhiying_renwu_donghua_2_1.png,zhiyin/zhiying_renwu_donghua_2_2.png,zhiyin/zhiying_renwu_donghua_2_3.png,zhiyin/zhiying_renwu_donghua_2_4.png,zhiyin/zhiying_renwu_donghua_2_3.png","interval":300,"index":0,"height":506}},{"type":"Button","props":{"y":328,"x":353,"visible":false,"var":"start_btn","stateNum":"2","skin":"zhiyin/zhiying_kaishilvcheng.png"}},{"type":"HTMLDivElement","props":{"y":120,"x":196,"width":476,"var":"content","height":208}},{"type":"Button","props":{"y":257,"x":381,"visible":false,"var":"gift_btn","stateNum":"1","skin":"zhiyin/libao_zhiying.png"},"child":[{"type":"Label","props":{"y":105,"x":-9,"width":122,"text":"点击礼包领取","height":22,"fontSize":20,"font":"SimHei","color":"#4d2202"}}]},{"type":"Button","props":{"y":327,"x":349,"visible":false,"var":"lingqu_btn","stateNum":"2","skin":"zhiyin/zhiying_lingqu.png"}},{"type":"Button","props":{"y":327,"x":349,"visible":false,"var":"xiayibu_btn","stateNum":"2","skin":"zhiyin/zhiying_xiayibu.png"}}]};}
		]);
		return welcomeUI;
	})(Dialog);
var YJSDialogUI=(function(_super){
		function YJSDialogUI(){
			
		    this.left_item0=null;
		    this.left_item1=null;
		    this.left_item2=null;
		    this.compound_btn=null;
		    this.left_result=null;
		    this.help_btn=null;
		    this.tab=null;
		    this.view_stack=null;
		    this.List0=null;
		    this.List1=null;
		    this.List2=null;
		    this.List3=null;
		    this.List4=null;
		    this.tips=null;
		    this.tips_1=null;
		    this.tips_1_ele=null;
		    this.tips_2=null;

			YJSDialogUI.__super.call(this);
		}

		CLASS$(YJSDialogUI,'ui.YJSDialogUI',_super);
		var __proto__=YJSDialogUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(YJSDialogUI.uiView);
		}

		STATICATTR$(YJSDialogUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"width":929,"skin":"pinjian/yajiusuo_bg.png","height":586}},{"type":"Box","props":{"y":179,"x":102,"width":312,"name":"left","height":371},"child":[{"type":"Image","props":{"y":10,"x":131,"width":62,"var":"left_item0","height":62}},{"type":"Image","props":{"y":231,"x":18,"width":62,"var":"left_item1","height":62}},{"type":"Image","props":{"y":231,"x":250,"width":62,"var":"left_item2","height":62}},{"type":"Button","props":{"y":291,"x":118,"var":"compound_btn","stateNum":"2","skin":"pinjian/button_hecheng.png"}},{"type":"Image","props":{"y":142,"x":128,"width":70,"var":"left_result","height":70}},{"type":"Button","props":{"y":4,"x":236,"width":68,"var":"help_btn","stateNum":"2","skin":"ui/button_help.png","height":36}}]},{"type":"Box","props":{"y":166,"x":421,"width":379,"name":"right","height":357},"child":[{"type":"Tab","props":{"y":3,"x":7,"var":"tab","stateNum":2,"space":3,"skin":"bakeroom/tab.png","selectedIndex":0,"labels":"一星,二星,三星,四星,五星","labelSize":22,"labelPadding":"0,0,1,0","labelColors":"#672416,#672416","labelBold":true}},{"type":"ViewStack","props":{"y":62,"x":13,"width":370,"visible":false,"var":"view_stack","height":290},"child":[{"type":"List","props":{"y":0,"x":0,"width":370,"var":"List0","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":15,"repeatY":3,"repeatX":4,"name":"item0","height":290},"child":[{"type":"Box","props":{"y":3,"x":1,"name":"render"},"child":[{"type":"Image","props":{"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":3,"width":73,"name":"icon","height":73}},{"type":"Label","props":{"y":54,"x":3,"width":69,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":20,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Image","props":{"y":8,"x":6,"visible":false,"skin":"dati/dui.png","name":"gou"}},{"type":"Label","props":{"y":78,"x":0,"wordWrap":true,"width":80,"valign":"middle","name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}}]},{"type":"Label","props":{"y":108,"x":47,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得一星调香书，可通过商行或关卡游戏获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":370,"var":"List1","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":15,"repeatY":3,"repeatX":4,"name":"item1","height":290},"child":[{"type":"Box","props":{"y":3,"x":1,"name":"render"},"child":[{"type":"Image","props":{"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":3,"width":73,"name":"icon","height":73}},{"type":"Label","props":{"y":54,"x":3,"width":69,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":20,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Image","props":{"y":8,"x":6,"visible":false,"skin":"dati/dui.png","name":"gou"}},{"type":"Label","props":{"y":78,"x":0,"wordWrap":true,"width":80,"valign":"middle","name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}}]},{"type":"Label","props":{"y":108,"x":47,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得二星调香书，可通过商行或关卡游戏获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":370,"var":"List2","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":15,"repeatY":3,"repeatX":4,"name":"item2","height":290},"child":[{"type":"Box","props":{"y":3,"x":1,"name":"render"},"child":[{"type":"Image","props":{"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":3,"width":73,"name":"icon","height":73}},{"type":"Label","props":{"y":54,"x":3,"width":69,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":20,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Image","props":{"y":8,"x":6,"visible":false,"skin":"dati/dui.png","name":"gou"}},{"type":"Label","props":{"y":78,"x":0,"wordWrap":true,"width":80,"valign":"middle","name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}}]},{"type":"Label","props":{"y":108,"x":47,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得三星调香书，可通过商行或关卡游戏获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":370,"var":"List3","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":15,"repeatY":3,"repeatX":4,"name":"item3","height":290},"child":[{"type":"Box","props":{"y":3,"x":1,"name":"render"},"child":[{"type":"Image","props":{"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":3,"width":73,"name":"icon","height":73}},{"type":"Label","props":{"y":54,"x":3,"width":69,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":20,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Image","props":{"y":8,"x":6,"visible":false,"skin":"dati/dui.png","name":"gou"}},{"type":"Label","props":{"y":78,"x":0,"wordWrap":true,"width":80,"valign":"middle","name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}}]},{"type":"Label","props":{"y":108,"x":47,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得四星调香书，可通过商行或关卡游戏获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]},{"type":"List","props":{"width":370,"var":"List4","vScrollBarSkin":"ui/vscroll.png","spaceY":10,"spaceX":15,"repeatY":3,"repeatX":4,"name":"item4","height":290},"child":[{"type":"Box","props":{"y":3,"x":1,"name":"render"},"child":[{"type":"Image","props":{"skin":"depot/wupindiban.png"}},{"type":"Image","props":{"y":3,"x":3,"width":73,"name":"icon","height":73}},{"type":"Label","props":{"y":54,"x":3,"width":69,"valign":"middle","text":"0","strokeColor":"#000000","stroke":3,"name":"num","height":20,"fontSize":20,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Image","props":{"y":8,"x":6,"visible":false,"skin":"dati/dui.png","name":"gou"}},{"type":"Label","props":{"y":78,"x":0,"wordWrap":true,"width":80,"valign":"middle","name":"name","height":36,"fontSize":18,"font":"SimHei","color":"#290e03","align":"center"}}]},{"type":"Label","props":{"y":108,"x":47,"wordWrap":true,"width":275,"visible":false,"valign":"middle","text":"还未获得五星调香书，可通过商行或关卡游戏获得","name":"tips","leading":5,"height":73,"fontSize":22,"font":"SimHei","color":"#000000","align":"center"}}]}]}]},{"type":"Button","props":{"y":71,"x":866,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Button","props":{"y":152,"x":845,"width":74,"stateNum":"2","skin":"pinjian/button_tab_hecheng.png","height":141}},{"type":"Button","props":{"y":152,"x":17,"width":74,"stateNum":"2","skin":"pinjian/button_tab_hecheng.png","height":141}},{"type":"Sprite","props":{"y":0,"x":0,"width":944,"visible":false,"var":"tips","height":579},"child":[{"type":"Image","props":{"y":142,"x":474,"visible":false,"var":"tips_1","skin":"zhiyin/zhiying_qipao_1-17.png","skewY":180,"cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"HTMLDivElement","props":{"y":45,"x":358,"width":280,"var":"tips_1_ele","innerHTML":"<div style=\"fontSize:20;color:#ffffff;width:280;height:auto;align:center;\">选择<span color=\"#FF0000\">三本</span>调香书放入框中</div>","height":30}}]},{"type":"Image","props":{"y":366,"x":292,"visible":false,"var":"tips_2","skin":"zhiyin/zhiying_qipao_1-17.png","cacheAsBitmap":true,"cacheAs":"bitmap"},"child":[{"type":"HTMLDivElement","props":{"y":35,"x":78,"width":280,"innerHTML":"<div style=\"fontSize:20;color:#ffffff;width:280;height:auto;align:center;\">点击<span color=\"#FF0000\">合成</span>即可得到<br/>一本新的调香书</div>","height":41}}]}]}]};}
		]);
		return YJSDialogUI;
	})(Dialog);
var YouLeChangUI=(function(_super){
		function YouLeChangUI(){
			
		    this.wabao=null;
		    this.xxl=null;
		    this.dati=null;
		    this.intro=null;
		    this.enty_game_btn=null;
		    this.icon=null;
		    this.dati_jiangli=null;

			YouLeChangUI.__super.call(this);
		}

		CLASS$(YouLeChangUI,'ui.YouLeChangUI',_super);
		var __proto__=YouLeChangUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(YouLeChangUI.uiView);
		}

		STATICATTR$(YouLeChangUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"width":960,"skin":"youlechang/youxiguanka.png","height":604},"child":[{"type":"Button","props":{"y":193,"x":179,"width":360,"var":"wabao","toggle":true,"stateNum":"2","skin":"youlechang/huanlewabao.png","scaleY":0.7,"scaleX":0.7,"height":172,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":239,"x":156,"skin":"youlechang/one.png"}},{"type":"Button","props":{"y":193,"x":482,"width":350,"var":"xxl","toggle":true,"stateNum":"2","skin":"youlechang/zhongzixiaoxiaole.png","scaleY":0.7,"scaleX":0.7,"height":171,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":239,"x":457,"skin":"youlechang/two.png"}},{"type":"Button","props":{"y":193,"x":774,"width":269,"var":"dati","stateNum":"2","skin":"youlechang/meiridati_3.png","height":120,"anchorY":0.5,"anchorX":0.5}},{"type":"Image","props":{"y":239,"x":758,"skin":"youlechang/three.png"}},{"type":"Label","props":{"y":332,"x":74,"wordWrap":true,"width":482,"var":"intro","leading":5,"height":164,"fontSize":20,"font":"SimHei","color":"#e2d888"}},{"type":"Button","props":{"y":546,"x":364,"var":"enty_game_btn","stateNum":"3","skin":"youlechang/jingruyouxi.png"}},{"type":"Image","props":{"y":351,"x":698,"width":122,"var":"icon","height":122}},{"type":"Button","props":{"y":-5,"x":852,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Label","props":{"y":353,"x":662,"width":221,"var":"dati_jiangli","text":"调香书、种子、滤嘴","height":29,"fontSize":24,"font":"SimHei","color":"#e2d888"}}]}]};}
		]);
		return YouLeChangUI;
	})(Dialog);
var zhangguiUI=(function(_super){
		function zhangguiUI(){
			

			zhangguiUI.__super.call(this);
		}

		CLASS$(zhangguiUI,'ui.zhangguiUI',_super);
		var __proto__=zhangguiUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(zhangguiUI.uiView);
		}

		STATICATTR$(zhangguiUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"jiandie/zhanggui.png"}},{"type":"Button","props":{"y":307,"x":492,"stateNum":"2","skin":"jiandie/jiandie_burong.png","name":"no"}},{"type":"Button","props":{"y":308,"x":210,"stateNum":"2","skin":"jiandie/jiandie_haode.png","name":"yes"}}]};}
		]);
		return zhangguiUI;
	})(Dialog);
var zhiyin_npcUI=(function(_super){
		function zhiyin_npcUI(){
			
		    this.getTXS=null;
		    this.plant=null;
		    this.bake=null;
		    this.aging=null;
		    this.zhiyan=null;
		    this.pinjian=null;
		    this.shop_building=null;
		    this.zhiyan_building=null;
		    this.pinjian_building=null;
		    this.land_building=null;
		    this.bake_building=null;
		    this.aging_building=null;
		    this.content=null;
		    this.goto_btn=null;

			zhiyin_npcUI.__super.call(this);
		}

		CLASS$(zhiyin_npcUI,'ui.zhiyin_npcUI',_super);
		var __proto__=zhiyin_npcUI.prototype;
		__proto__.createChildren=function(){
		    			View.regComponent("HTMLDivElement",laya.html.dom.HTMLDivElement);

			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(zhiyin_npcUI.uiView);
		}

		STATICATTR$(zhiyin_npcUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"y":7},"child":[{"type":"Image","props":{"y":0,"x":-3,"skin":"zhiyin/zhiying_diban1.png"}},{"type":"Box","props":{"y":2,"x":29,"var":"getTXS","gray":true},"child":[{"type":"Image","props":{"skin":"zhiyin/zhiying_liucheng_tab.png"}},{"type":"Image","props":{"y":10,"x":91,"skin":"zhiyin/zhiying_liucheng_jiantou.png","scaleY":0.9,"scaleX":0.9}},{"type":"Image","props":{"y":-1,"x":7,"skin":"zhiyin/zhiying_liucheng_huoqutiaoxiangshu.png"}}]},{"type":"Box","props":{"y":2,"x":172,"var":"plant","gray":true},"child":[{"type":"Image","props":{"skin":"zhiyin/zhiying_liucheng_tab.png"}},{"type":"Image","props":{"y":10,"x":91,"skin":"zhiyin/zhiying_liucheng_jiantou.png","scaleY":0.9,"scaleX":0.9}},{"type":"Image","props":{"y":10,"x":21,"skin":"zhiyin/zhiying_liucheng_zhongzhi.png"}}]},{"type":"Box","props":{"y":2,"x":315,"var":"bake","gray":true},"child":[{"type":"Image","props":{"skin":"zhiyin/zhiying_liucheng_tab.png"}},{"type":"Image","props":{"y":10,"x":91,"skin":"zhiyin/zhiying_liucheng_jiantou.png","scaleY":0.9,"scaleX":0.9}},{"type":"Image","props":{"y":10,"x":21,"skin":"zhiyin/zhiying_liucheng_hongkao.png"}}]},{"type":"Box","props":{"y":2,"x":458,"var":"aging","gray":true},"child":[{"type":"Image","props":{"skin":"zhiyin/zhiying_liucheng_tab.png"}},{"type":"Image","props":{"y":10,"x":91,"skin":"zhiyin/zhiying_liucheng_jiantou.png","scaleY":0.9,"scaleX":0.9}},{"type":"Image","props":{"y":9,"x":20,"skin":"zhiyin/zhiying_chunhua.png"}}]},{"type":"Box","props":{"y":2,"x":600,"var":"zhiyan","gray":true},"child":[{"type":"Image","props":{"skin":"zhiyin/zhiying_liucheng_tab.png"}},{"type":"Image","props":{"y":10,"x":90,"skin":"zhiyin/zhiying_liucheng_jiantou.png","scaleY":0.9,"scaleX":0.9}},{"type":"Image","props":{"y":10,"x":20,"skin":"zhiyin/zhiying_liucheng_zhiyan.png"}}]},{"type":"Box","props":{"y":2,"x":742,"var":"pinjian","gray":true},"child":[{"type":"Image","props":{"skin":"zhiyin/zhiying_liucheng_tab.png"}},{"type":"Image","props":{"y":10,"x":20,"skin":"zhiyin/zhiying_liucheng_pinjian.png"}}]},{"type":"Box","props":{"y":47,"x":28,"width":146,"height":152},"child":[{"type":"Image","props":{"y":2,"x":2,"width":133,"visible":false,"var":"shop_building","skin":"tex/zlshop.png","height":150},"child":[{"type":"Image","props":{"y":102,"x":4,"width":124,"skin":"tex/zhenlongshanghang_text.png","height":36}}]},{"type":"Image","props":{"y":80,"x":72,"width":146,"visible":false,"var":"zhiyan_building","skin":"tex/zhiyanfang.png","height":114,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Image","props":{"y":81,"x":10,"width":125,"skin":"tex/zhiyanfang_text.png","height":44}}]},{"type":"Image","props":{"y":79,"x":74,"width":150,"visible":false,"var":"pinjian_building","skin":"tex/pinjian.png","height":127,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Image","props":{"y":92,"x":0,"width":149,"skin":"tex/pingjian_text.png","height":43}}]},{"type":"Image","props":{"y":82,"x":0,"visible":false,"var":"land_building","skin":"tex/land_lv_0.png"},"child":[{"type":"Image","props":{"y":-71,"x":56,"skin":"tex/zhongzhiqu.png"}}]},{"type":"Image","props":{"y":77,"x":70,"width":144,"visible":false,"var":"bake_building","skin":"tex/hongkaofang.png","height":118,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Image","props":{"y":79,"x":19,"width":105,"skin":"tex/hongkaoshi_text.png","height":37}}]},{"type":"Image","props":{"y":78,"x":71,"width":140,"visible":false,"var":"aging_building","skin":"tex/agingroom.png","height":140,"anchorY":0.5,"anchorX":0.5},"child":[{"type":"Image","props":{"y":99,"x":-6,"width":151,"skin":"tex/chuhua_text.png","height":44}}]}]},{"type":"HTMLDivElement","props":{"y":50,"x":171,"width":657,"var":"content","height":147}},{"type":"Button","props":{"y":145,"x":407,"var":"goto_btn","stateNum":"2","skin":"zhiyin/zhiying_lijiqianwang.png","name":"close"}}]};}
		]);
		return zhiyin_npcUI;
	})(Dialog);
var ZLShopUI=(function(_super){
		function ZLShopUI(){
			
		    this.table=null;
		    this.view_stack=null;
		    this.tab_zl=null;
		    this.view_stack_zl=null;
		    this.zl_seed=null;
		    this.zl_recipe=null;
		    this.zl_filter_tip=null;
		    this.zl_other=null;
		    this.sm_seed=null;
		    this.refresh_btn=null;
		    this.refresh_countdown=null;
		    this.help_btn=null;
		    this.my_money=null;

			ZLShopUI.__super.call(this);
		}

		CLASS$(ZLShopUI,'ui.ZLShopUI',_super);
		var __proto__=ZLShopUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(ZLShopUI.uiView);
		}

		STATICATTR$(ZLShopUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"shop/datiban.png","sizeGrid":"30,5,5,5"}},{"type":"Image","props":{"y":98,"x":313,"skin":"shop/guangshi.png"}},{"type":"Tab","props":{"y":75,"x":100,"var":"table","staticCache":true,"stateNum":2,"space":70,"skin":"shop/tab_dafenlei.png","labels":"真龙商行,神秘商店","labelSize":24,"labelPadding":"7,0,12,0","labelColors":"#1c0608,#1c0608","labelBold":true,"labelAlign":"center"}},{"type":"ViewStack","props":{"y":149,"x":111,"width":650,"var":"view_stack","height":367},"child":[{"type":"Box","props":{"y":0,"x":0,"width":650,"name":"item0","height":367},"child":[{"type":"Tab","props":{"y":0,"x":0,"var":"tab_zl","stateNum":2,"skin":"depot/tab_xiaofenlei.png","labels":"调香书,种子,嘴棒,其他","labelSize":20,"labelColors":"#672416,#672416","labelBold":true}},{"type":"ViewStack","props":{"y":36,"x":0,"width":650,"visible":false,"var":"view_stack_zl","height":330},"child":[{"type":"List","props":{"y":0,"x":0,"width":646,"var":"zl_seed","vScrollBarSkin":"ui/vscroll.png","spaceY":5,"spaceX":-2,"repeatY":2,"repeatX":4,"name":"item1","height":332},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"shop/kuang.png"}},{"type":"Image","props":{"y":42,"x":46,"skin":"shop/wupindiban.png"}},{"type":"Image","props":{"y":51,"x":53,"width":62,"name":"icon","height":62}},{"type":"Label","props":{"y":11,"x":12,"wordWrap":true,"width":142,"valign":"middle","name":"name","height":30,"fontSize":18,"color":"#7c341a","align":"center"}},{"type":"Image","props":{"y":119,"x":43,"skin":"shop/wupinshuzhi.png"}},{"type":"Label","props":{"y":124,"x":62,"width":63,"valign":"middle","name":"price","height":25,"fontSize":18,"color":"#fed6c0","bold":true,"align":"center"}},{"type":"Image","props":{"y":121,"x":28,"width":36,"skin":"userinfo/lebi.png","name":"currency","height":31}},{"type":"Image","props":{"y":110,"x":115,"width":45,"visible":false,"skin":"shop/sale.png","name":"sale","height":35}}]}]},{"type":"List","props":{"y":0,"x":0,"width":646,"var":"zl_recipe","vScrollBarSkin":"ui/vscroll.png","spaceY":5,"spaceX":-2,"repeatY":2,"repeatX":4,"name":"item0","height":332},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"shop/kuang.png"}},{"type":"Image","props":{"y":42,"x":46,"skin":"shop/wupindiban.png"}},{"type":"Image","props":{"y":51,"x":53,"width":62,"name":"icon","height":62}},{"type":"Label","props":{"y":11,"x":12,"wordWrap":true,"width":142,"valign":"middle","name":"name","height":30,"fontSize":18,"color":"#7c341a","align":"center"}},{"type":"Image","props":{"y":119,"x":43,"skin":"shop/wupinshuzhi.png"}},{"type":"Label","props":{"y":124,"x":62,"width":63,"valign":"middle","name":"price","height":25,"fontSize":18,"color":"#fed6c0","bold":true,"align":"center"}},{"type":"Image","props":{"y":121,"x":28,"width":36,"skin":"userinfo/lebi.png","name":"currency","height":31}},{"type":"Image","props":{"y":110,"x":115,"width":45,"visible":false,"skin":"shop/sale.png","name":"sale","height":35}}]}]},{"type":"List","props":{"y":0,"x":0,"width":646,"var":"zl_filter_tip","vScrollBarSkin":"ui/vscroll.png","spaceY":5,"spaceX":-2,"repeatY":2,"repeatX":4,"name":"item2","height":330},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"shop/kuang.png"}},{"type":"Image","props":{"y":42,"x":46,"skin":"shop/wupindiban.png"}},{"type":"Image","props":{"y":51,"x":53,"width":62,"name":"icon","height":62}},{"type":"Label","props":{"y":11,"x":12,"wordWrap":true,"width":142,"valign":"middle","name":"name","height":30,"fontSize":18,"color":"#7c341a","align":"center"}},{"type":"Image","props":{"y":119,"x":43,"skin":"shop/wupinshuzhi.png"}},{"type":"Label","props":{"y":124,"x":62,"width":63,"valign":"middle","name":"price","height":25,"fontSize":18,"color":"#fed6c0","bold":true,"align":"center"}},{"type":"Image","props":{"y":121,"x":28,"width":36,"skin":"userinfo/lebi.png","name":"currency","height":31}},{"type":"Image","props":{"y":110,"x":115,"width":45,"visible":false,"skin":"shop/sale.png","name":"sale","height":35}}]}]},{"type":"List","props":{"y":0,"x":0,"width":646,"var":"zl_other","vScrollBarSkin":"ui/vscroll.png","spaceY":5,"spaceX":-2,"repeatY":2,"repeatX":4,"name":"item3","height":332},"child":[{"type":"Box","props":{"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"shop/kuang.png"}},{"type":"Image","props":{"y":42,"x":46,"skin":"shop/wupindiban.png"}},{"type":"Image","props":{"y":51,"x":53,"width":62,"name":"icon","height":62}},{"type":"Label","props":{"y":11,"x":12,"wordWrap":true,"width":142,"valign":"middle","name":"name","height":30,"fontSize":18,"color":"#7c341a","align":"center"}},{"type":"Image","props":{"y":119,"x":43,"skin":"shop/wupinshuzhi.png"}},{"type":"Label","props":{"y":124,"x":62,"width":63,"valign":"middle","name":"price","height":25,"fontSize":18,"color":"#fed6c0","bold":true,"align":"center"}},{"type":"Image","props":{"y":121,"x":28,"width":36,"skin":"userinfo/lebi.png","name":"currency","height":31}},{"type":"Image","props":{"y":110,"x":115,"width":45,"visible":false,"skin":"shop/sale.png","name":"sale","height":35}}]}]}]}]},{"type":"Box","props":{"y":0,"x":0,"width":650,"name":"item1","height":367},"child":[{"type":"List","props":{"y":36,"x":0,"width":646,"visible":false,"var":"sm_seed","vScrollBarSkin":"ui/vscroll.png","spaceY":3,"spaceX":0,"repeatY":2,"repeatX":4,"name":"item0","height":332},"child":[{"type":"Box","props":{"y":0,"x":0,"name":"render"},"child":[{"type":"Image","props":{"y":0,"x":0,"skin":"shop/shenmikuan_3.png"}},{"type":"Image","props":{"y":42,"x":42,"skin":"shop/shenmikuan_1.png"}},{"type":"Image","props":{"y":48,"x":48,"width":62,"name":"icon","height":62}},{"type":"Label","props":{"y":11,"x":9,"wordWrap":true,"width":146,"valign":"middle","name":"name","height":30,"fontSize":18,"color":"#7c341a","align":"center"}},{"type":"Image","props":{"y":119,"x":43,"skin":"shop/shenmikuan_2.png"}},{"type":"Label","props":{"y":121,"x":62,"width":63,"valign":"middle","name":"price","height":25,"fontSize":18,"color":"#fed6c0","bold":true,"align":"center"}},{"type":"Image","props":{"y":119,"x":28,"width":36,"skin":"userinfo/lebi.png","name":"currency","height":31}}]}]},{"type":"Box","props":{"y":-33,"x":549},"child":[{"type":"Image","props":{"y":12,"x":43,"skin":"orderlist/shuzhidikuang.png"}},{"type":"Button","props":{"y":4,"x":0,"var":"refresh_btn","stateNum":"2","skin":"orderlist/shuaxin.png"}},{"type":"Image","props":{"y":25,"x":96,"skin":"orderlist/ledou.png"}},{"type":"Label","props":{"y":23,"x":67,"width":31,"valign":"middle","text":"2","strokeColor":"#4a612d","stroke":3,"height":27,"fontSize":26,"font":"SimHei","color":"#ffffff","align":"right"}},{"type":"Label","props":{"y":19,"x":-212,"width":214,"var":"refresh_countdown","valign":"middle","text":"自动刷新：","height":36,"fontSize":22,"font":"SimHei","color":"#301607","align":"center"}}]},{"type":"Label","props":{"y":-2,"x":10,"width":311,"valign":"middle","text":"神秘商行每次售卖的种子种类随机","height":36,"fontSize":22,"font":"SimHei","color":"#301607","align":"center"}}]}]},{"type":"Button","props":{"y":-12,"x":809,"stateNum":"2","skin":"ui/guanbi.png","name":"close"}},{"type":"Button","props":{"y":30,"x":714,"width":68,"var":"help_btn","stateNum":"2","skin":"ui/button_help.png","height":36}},{"type":"Image","props":{"y":75,"x":547,"skin":"shop/diban.png"},"child":[{"type":"Label","props":{"y":9,"x":32,"width":96,"var":"my_money","valign":"middle","text":"0","strokeColor":"#000000","stroke":2,"height":24,"fontSize":20,"font":"SimHei","color":"#fbf6f6","align":"left"}},{"type":"Image","props":{"y":3,"x":-8,"width":40,"skin":"userinfo/lebi_big.png","height":40}}]},{"type":"Image","props":{"y":97,"x":98,"skin":"shop/guangshi.png"}}]};}
		]);
		return ZLShopUI;
	})(Dialog);