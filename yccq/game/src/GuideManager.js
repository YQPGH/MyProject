/**
 * Created by 41496 on 2017/9/21.
 */
var GuideManager = (function(){
    function GuideManager()
    {
        this.guideContainer = null;
        this.npcDialogIndex = null;
        this.Dialog = null;
        this.DialogArr = [];
        this.map = Laya.stage.getChildByName('MyGame').map;
        //this.createGuide();
    }
    Laya.class(GuideManager,'GuideManager');
    var proto = GuideManager.prototype;

    proto.createGuide = function()
    {
        this.guideContainer = new Laya.Sprite();
        // 设置容器为画布缓存
        this.guideContainer.cacheAs = "bitmap";
        Laya.stage.addChild(this.guideContainer);
        //this.guideContainer.on(Laya.Event.MOUSE_DOWN,this,this.onGuideContainerClick);

        //绘制遮罩区，含透明度，可见游戏背景
        var maskArea = new Laya.Sprite();
        maskArea.alpha = 0.6;
        maskArea.graphics.drawRect(0, 0, Laya.stage.width, Laya.stage.height, "#000000");
        this.guideContainer.addChild(maskArea);

        //NPC对话框
        this.NPC = new guide_npc_dialogUI();
        this.NPC.nav.hScrollBar.hide = true;
        this.NPC.pos(0,Laya.stage.height-221);
        this.guideContainer.addChild(this.NPC);
        this.NPC.btn_next.clickHandler = new Laya.Handler(this,this.nextStep);
        this.npcDialogIndex = [this.NPC.BuySeed,this.NPC.Plant,this.NPC.Baking,this.NPC.Aging,this.NPC.ZhiYan,this.NPC.PinJian,this.NPC.ChouJiang,this.NPC.Order,this.NPC.yanjiusuo,this.NPC.peiyu,this.NPC.youleyuan];

        this.hitArea = new Laya.HitArea();
        this.hitArea.hit.drawRect(0, 0, Laya.stage.width, Laya.stage.height, "#000000");

        this.guideContainer.hitArea = this.hitArea;
        this.guideContainer.mouseEnabled = true;

        this.JianTou = new Laya.Animation();
        this.JianTou.loadAnimation("GuideArrows.ani");

        this.map.addBuilding(this.JianTou,0,0);
        this.JianTou.zOrder = 10000;
        this.JianTou.play();

        //this.nextStep();
    };

    //跳转到商店购买种子
    proto.goToBuySeed = function()
    {
        this.moveScreen(18,20);
        this.JianTouShow(16,18);
        this.closeNpcDialog();
    };
    //购买调香书指引
    proto.buyTXS = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 220,
                y: 145,
                width: 100,
                height: 40,
                text:'guide/zhiyingwenzhi1_3.png',
                textx:170,
                texty:100,
                fingerx:250,
                fingery:180
            },
            {
                type:'rectangle',
                x: 445,
                y: 360,
                width: 150,
                height: 150,
                text:'guide/zhiyingwenzhi1_4.png',
                textx:170,
                texty:100,
                fingerx:500,
                fingery:400
            },
            {
                type:'circle',
                x: 845,
                y: 30,
                radius:30,
                text:'guide/55.png',
                textx:600,
                texty:30,
                fingerx:844,
                fingery:30
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.tab_zl.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[1]);
        });
    };
    //确认购买调香书
    proto.confirmBuyTXS = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 150,
                y: 110,
                width: 270,
                height: 120,
                text:'guide/zhiyingwenzhi1_5.png',
                textx:120,
                texty:80,
                fingerx:220,
                fingery:200
            },
            {
                type:'rectangle',
                x: 170,
                y: 372,
                width: 145,
                height: 50,
                text:'guide/zhiyingwenzhi1_13.png',
                textx:130,
                texty:200,
                fingerx:220,
                fingery:380
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.item_details.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[1]);
        });
        dialog.buy_btn.on('click',this,function(){
            this.buyTXS(this.DialogArr[0],2);
            this.sendGuideStep(1,0);
        })
    };

    proto.buyLvZui = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 330,
                y: 145,
                width: 100,
                height: 40,
                text:'guidetext/33.png',
                textx:230,
                texty:100,
                fingerx:360,
                fingery:180
            },
            {
                type:'rectangle',
                x: 445,
                y: 360,
                width: 150,
                height: 150,
                text:'guidetext/34.png',
                textx:100,
                texty:100,
                fingerx:500,
                fingery:400
            },
            {
                type:'circle',
                x: 854,
                y: 40,
                radius:30,
                text:'guidetext/55.png',
                textx:600,
                texty:30,
                fingerx:844,
                fingery:30
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.tab_zl.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[1]);
        });
    };

    proto.confirmBuyLvZui = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'circle',
                x: 314,
                y: 253,
                radius:25,
                text:'guide/zhiyingwenzhi1_12.png',
                textx:120,
                texty:150,
                fingerx:300,
                fingery:230
            },
            {
                type:'rectangle',
                x: 170,
                y: 372,
                width: 145,
                height: 50,
                text:'guide/zhiyingwenzhi1_13.png',
                textx:150,
                texty:200,
                fingerx:230,
                fingery:380
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.add_btn.on('click',this,function(){
            if(dialog.buy_num.text == 10){
                this.dialogGuideNextStep(dialog,step[1]);
            }
        });
        dialog.buy_btn.on('click',this,function(){
            this.buySeed(this.DialogArr[0],3);
            this.sendGuideStep(2,0);
        })
    };

    //跳转到加工厂
    proto.goToFactory = function()
    {
        this.moveScreen(30,29);
        this.JianTouShow(29,28);
        this.closeNpcDialog();
    };
    proto.factory = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 870,
                y: 135,
                width:100,
                height:60,
                text:'guidetext/56.png',
                textx:630,
                texty:150,
                fingerx:880,
                fingery:150

            },
            {
                type:'rectangle',
                x: 140,
                y: 110,
                width:220,
                height:280,
                text:'guidetext/57.png',
                textx:350,
                texty:200,
                fingerx:230,
                fingery:200
            },
            {
                type:'rectangle',
                x: 430,
                y: 450,
                width:140,
                height:60,
                text:'guidetext/58.png',
                textx:30,
                texty:200,
                fingerx:480,
                fingery:460
            },
            {
                type:'rectangle',
                x: 870,
                y: 212,
                width:100,
                height:60,
                text:'guidetext/6.png',
                textx:580,
                texty:210,
                fingerx:880,
                fingery:230
            },
            {
                type:'rectangle',
                x: 520,
                y: 90,
                width:100,
                height:100,
                text:'guidetext/7.png',
                textx:150,
                texty:230,
                fingerx:560,
                fingery:140
            },
            {
                type:'circle',
                x: 900,
                y: 60,
                radius:30,
                text:'guidetext/55.png',
                textx:600,
                texty:30,
                fingerx:870,
                fingery:50
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);

        dialog.tab.on('change',this,function(){
            if(dialog.tab.selectedIndex == 0){
                console.log('租赁指引');
                this.dialogGuideNextStep(dialog,step[1]);
            }else {
                console.log('生产指引');
                this.dialogGuideNextStep(dialog,step[4]);
            }
        });

        dialog.zulin_box0.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[2]);
            dialog.zulin_ok_btn.disabled = false;
        });

        dialog.zulin_ok_btn.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[3]);
        });
    };
    //查看配方原料指引
    proto.peifang = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 30,
                y: 70,
                width:110,
                height:130,
                text:'',
                textx:630,
                texty:150,
                fingerx:60,
                fingery:100
            },
            {
                type:'rectangle',
                x: 130,
                y: 270,
                width:660,
                height:170,
                text:'guidetext/8.png',
                textx:50,
                texty:70,
                fingerx:520,
                fingery:270
            },
            {
                type:'circle',
                x: 890,
                y: 35,
                radius:30,
                text:'guidetext/55.png',
                textx:600,
                texty:30,
                fingerx:870,
                fingery:50
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.PF_List.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[1]);
        });
        dialog.CL_List.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[2]);
        });
        dialog.getChildByName('close').on('click',this,function(){
            this.factory(this.DialogArr[0],5);
            this.sendGuideStep(2,0);
        });
    };
    //购买种子指引
    proto.buySeed = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 600,
                y: 190,
                width: 150,
                height: 150,
                text:'guide/zhiyingwenzhi1_8.png',
                textx:350,
                texty:200,
                fingerx:650,
                fingery:230
            },
            {
                type:'rectangle',
                x: 330,
                y: 145,
                width: 100,
                height: 40,
                text:'guide/zhiyingwenzhi1_10.png',
                textx:280,
                texty:100,
                fingerx:360,
                fingery:180
            },
            {
                type:'rectangle',
                x: 445,
                y: 360,
                width: 150,
                height: 150,
                text:'guide/zhiyingwenzhi1_11.png',
                textx:100,
                texty:100,
                fingerx:500,
                fingery:400
            },
            {
                type:'circle',
                x: 850,
                y: 30,
                radius:30,
                text:'guide/55.png',
                textx:600,
                texty:30,
                fingerx:840,
                fingery:40
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.tab_zl.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[2]);
        });
    };
    //购买确认指引
    proto.confirmBuy = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 170,
                y: 372,
                width: 145,
                height: 50,
                text:'guide/zhiyingwenzhi1_9.png',
                textx:150,
                texty:200,
                fingerx:230,
                fingery:380
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.buy_btn.on('click',this,function(){
            this.buySeed(this.DialogArr[0],1);
            GuideManager.actionIndex = 1;
            this.sendGuideStep(1,1);
        })
    };

    proto.goToPlant = function(num)
    {
        num = num?num:0;
        var land_pos = [[14,32],[14,33],[15,33],[16,33],[17,33],[15,32]];
        this.moveScreen(16,30);
        this.JianTouShow(land_pos[num][0],land_pos[num][1]);
        this.closeNpcDialog();
    };
    //种植指引
    proto.Plant = function(dialog)
    {
        var step = [
            {
                type:'rectangle',
                x: 423,
                y: 15,
                width: 100,
                height: 45,
                text:'guide/zhiyingwenzhi2_4.png',
                textx:170,
                texty:70,
                fingerx:480,
                fingery:30
            },
            {
                type:'rectangle',
                x: 25,
                y: 70,
                width: 90,
                height: 90,
                text:'guide/zhiyingwenzhi2_5.png',
                textx:130,
                texty:70,
                fingerx:50,
                fingery:80
            },
            {
                type:'rectangle',
                x: 530,
                y: 92,
                width: 165,
                height: 70,
                text:'guide/zhiyingwenzhi2_6.png',
                textx:200,
                texty:70,
                fingerx:590,
                fingery:100
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[0]);
        dialog.table.once('click',this,function(){
            if(GuideManager.guideStep != 3 && GuideManager.guideStep != 4) return;
            this.dialogGuideNextStep(dialog,step[1]);
        });
        dialog.view_stack.once('click',this,function(){
            this.dialogGuideNextStep(dialog,step[2]);
        });
        dialog.Plant_btn.once('click',this,function(){
            console.log('PlantNum='+GuideManager.PlantNum+',guideStep='+GuideManager.guideStep);
            if(GuideManager.guideStep != 3 && GuideManager.guideStep != 4) return;
            if(GuideManager.PlantNum >= 5){
                dialog.guideContainer.destroy();
                this.setStep(4,0);
                this.sendGuideStep(4,0);
                this.openNpcDialog();
                dialog.close();
                GuideManager.PlantNum = 0;
                return;
            }
            if(GuideManager.PlantNum == 0){
                this.nextStep();
                this.openNpcDialog();
            }

            GuideManager.PlantNum ++;
            this.sendGuideStep(2,GuideManager.PlantNum);
            this.goToPlant(GuideManager.PlantNum);
            dialog.close();

            //

        });
    };
    //跳转到加速
    proto.goToSpeedUp = function()
    {
        this.moveScreen(16,30);
        this.JianTouShow(14,32);
        this.closeNpcDialog();
    };

    proto.speedUp = function(dialog)
    {
        var step = [
            {
                type:'rectangle',
                x: 70,
                y: 112,
                width: 115,
                height: 50,
                text:'guide/zhiyingwenzhi2_10.png',
                textx:70,
                texty:30,
                fingerx:100,
                fingery:120
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[0]);

    };

    //种植加速确认
    proto.confirmSpeed = function(dialog)
    {
        var step = [
            {
                type:'rectangle',
                x: 60,
                y: 140,
                width: 130,
                height: 50,
                text:'guide/zhiyingwenzhi2_11.png',
                textx:30,
                texty:50,
                fingerx:100,
                fingery:150
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[0]);
        dialog.yes.on('click',this,function(){
            GuideManager.PlantNum ++;
            this.sendGuideStep(5,GuideManager.PlantNum);
            this.openNpcDialog();
            if(GuideManager.PlantNum == 6){
                this.nextStep();
            }else {
                this.goToPlant(GuideManager.PlantNum)
            }
            dialog.close();
        })
    };

    //跳转到收获烟叶
    proto.goToShouHuo = function()
    {
        this.moveScreen(16,30);
        this.JianTouShow(14,32);
        this.closeNpcDialog();
    };

    proto.shouHuo = function(dialog)
    {
        var step = [
            {
                type:'rectangle',
                x: 180,
                y: 130,
                width: 180,
                height: 50,
                text:'guide/13.png',
                textx:0,
                texty:0,
                fingerx:250,
                fingery:140
            }

        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[0]);
        dialog.yijian_btn.on('click',this,function(){
            this.sendGuideStep(6,0);
            this.openNpcDialog();
            this.nextStep();
        });
    };

    proto.goToBaking = function()
    {
        this.moveScreen(22,30);
        this.JianTouShow(21,29);
        this.closeNpcDialog();
    };

    proto.baking = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 815,
                y: 60,
                width: 70,
                height: 50,
                text:'guide/zhiyingwenzhi3_2.png',
                textx:500,
                texty:120,
                fingerx:830,
                fingery:70
            },
            {
                type:'rectangle',
                x: 510,
                y: 120,
                width: 95,
                height: 95,
                text:'guide/zhiyingwenzhi3_3.png',
                textx:150,
                texty:300,
                fingerx:530,
                fingery:140
            },
            {
                type:'rectangle',
                x: 520,
                y: 430,
                width: 130,
                height: 50,
                text:'guide/zhiyingwenzhi3_4.png',
                textx:150,
                texty:300,
                fingerx:560,
                fingery:440
            },
            {
                type:'rectangle',
                x: 670,
                y: 430,
                width: 130,
                height: 50,
                text:'guide/zhiyingwenzhi3_6.png',
                textx:670,
                texty:370,
                fingerx:720,
                fingery:440
            },
            {
                type:'circle',
                x: 923,
                y: 38,
                radius:30,
                text:'guide/55.png',
                textx:650,
                texty:30,
                fingerx:900,
                fingery:40
            }

        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.tab.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[1]);
        });
        dialog.view_stack.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[2]);
        });
        dialog.auto_btn.on('click',this,function(){
            //this.dialogGuideNextStep(dialog,step[2]);
        });
        dialog.lingqu_btn.on('click',this,function(){
            this.sendGuideStep(7,0);
            this.dialogGuideNextStep(dialog,step[4]);
        });
    };

    proto.confirmAutoBaking = function(dialog)
    {
        var step = [
            {
                type:'rectangle',
                x: 60,
                y: 140,
                width: 130,
                height: 50,
                text:'guide/23.png',
                textx:-70,
                texty:50,
                fingerx:100,
                fingery:150
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[0]);
        dialog.yes.on('click',this,function(){

            this.baking(this.DialogArr[0],3);
            dialog.close();
        })
    };

    proto.goToAging = function()
    {
        this.moveScreen(22,24);
        this.JianTouShow(22,24);
        this.closeNpcDialog();
    };

    proto.aging = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 822,
                y: 68,
                width: 70,
                height: 50,
                text:'guide/zhiyingwenzhi4_2.png',
                textx:500,
                texty:120,
                fingerx:840,
                fingery:80
            },
            {
                type:'rectangle',
                x: 535,
                y: 135,
                width: 95,
                height: 95,
                text:'guide/zhiyingwenzhi4_3.png',
                textx:150,
                texty:300,
                fingerx:550,
                fingery:160
            },
            {
                type:'rectangle',
                x: 230,
                y: 305,
                width: 110,
                height: 50,
                text:'guide/zhiyingwenzhi4_4.png',
                textx:150,
                texty:220,
                fingerx:260,
                fingery:320
            },
            {
                type:'rectangle',
                x: 230,
                y: 373,
                width: 110,
                height: 50,
                text:'guide/zhiyingwenzhi4_5.png',
                textx:100,
                texty:220,
                fingerx:260,
                fingery:390
            },
            {
                type:'rectangle',
                x: 230,
                y: 305,
                width: 110,
                height: 50,
                text:'guide/zhiyingwenzhi4_7.png',
                textx:150,
                texty:220,
                fingerx:260,
                fingery:320
            },
            {
                type:'circle',
                x: 935,
                y: 53,
                radius:30,
                text:'guide/55.png',
                textx:670,
                texty:30,
                fingerx:920,
                fingery:40
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.tab.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[1]);
        });
        dialog.view_stack.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[2]);
        });
        dialog.Aging_btn.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[3]);
        });
        dialog.lingqu_btn.on('click',this,function(){
            this.sendGuideStep(8,0);
            this.dialogGuideNextStep(dialog,step[5]);
        });
    };

    proto.confirmAgingSpeed = function(dialog)
    {
        var step = [
            {
                type:'rectangle',
                x: 60,
                y: 140,
                width: 130,
                height: 50,
                text:'',
                textx:-70,
                texty:50,
                fingerx:100,
                fingery:150
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[0]);
        dialog.yes.on('click',this,function(){
            this.aging(this.DialogArr[0],4);
            dialog.close();
        })
    };

    proto.jiagong = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 870,
                y: 135,
                width:100,
                height:60,
                text:'guide/zhiyingwenzhi5_3.png',
                textx:200,
                texty:150,
                fingerx:880,
                fingery:150

            },
            {
                type:'rectangle',
                x: 140,
                y: 110,
                width:220,
                height:280,
                text:'guide/zhiyingwenzhi5_5.png',
                textx:370,
                texty:200,
                fingerx:230,
                fingery:200
            },
            {
                type:'rectangle',
                x: 430,
                y: 450,
                width:140,
                height:60,
                text:'guide/zhiyingwenzhi5_6.png',
                textx:370,
                texty:200,
                fingerx:480,
                fingery:460
            },
            {
                type:'rectangle',
                x: 870,
                y: 212,
                width:100,
                height:60,
                text:'guide/zhiyingwenzhi5_7.png',
                textx:580,
                texty:210,
                fingerx:880,
                fingery:230
            },
            {
                type:'rectangle',
                x: 520,
                y: 90,
                width:100,
                height:100,
                text:'guide/zhiyingwenzhi5_9.png',
                textx:300,
                texty:130,
                fingerx:560,
                fingery:140
            },
            {
                type:'rectangle',
                x: 618,
                y: 240,
                width:140,
                height:50,
                text:'guide/zhiyingwenzhi5_12.png',
                textx:400,
                texty:230,
                fingerx:658,
                fingery:242
            },
            {
                type:'rectangle',
                x: 732,
                y: 140,
                width:120,
                height:40,
                text:'guide/zhiyingwenzhi5_13.png',
                textx:300,
                texty:230,
                fingerx:762,
                fingery:140
            },
            {
                type:'rectangle',
                x: 618,
                y: 240,
                width:140,
                height:50,
                text:'guide/zhiyingwenzhi5_16.png',
                textx:300,
                texty:230,
                fingerx:658,
                fingery:242
            },
            {
                type:'circle',
                x: 918,
                y: 50,
                radius:30,
                text:'guide/55.png',
                textx:600,
                texty:30,
                fingerx:900,
                fingery:50
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.tab.on('change',this,function(){
            if(dialog.tab.selectedIndex == 0){
                console.log('租赁指引');
                this.dialogGuideNextStep(dialog,step[1]);
            }else {
                console.log('生产指引');
                this.dialogGuideNextStep(dialog,step[4]);
            }
        });

        dialog.zulin_box0.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[2]);
            dialog.zulin_ok_btn.disabled = false;
        });

        dialog.zulin_ok_btn.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[3]);
        });
        dialog.start1_btn.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[6]);
        });
        dialog.lingqu1_btn.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[8]);
            this.sendGuideStep(9,0);
        });
    };

    proto.jiagongPF = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 30,
                y: 70,
                width:110,
                height:130,
                text:'guide/zhiyingwenzhi5_10.png',
                textx:180,
                texty:120,
                fingerx:60,
                fingery:100
            },
            {
                type:'rectangle',
                x: 380,
                y: 460,
                width:130,
                height:50,
                text:'guide/zhiyingwenzhi5_11.png',
                textx:380,
                texty:400,
                fingerx:440,
                fingery:480
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.PF_List.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[1]);
        });
        dialog.ok_btn.on('click',this,function(){
            this.jiagong(this.DialogArr[0],5);
        });
    };

    proto.confirmJiaGongSpeed = function(dialog)
    {
        var step = [
            {
                type:'rectangle',
                x: 60,
                y: 140,
                width: 130,
                height: 50,
                text:'guide/zhiyingwenzhi5_15.png',
                textx:50,
                texty:50,
                fingerx:100,
                fingery:150
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[0]);
        dialog.yes.on('click',this,function(){
            this.jiagong(this.DialogArr[0],7);
            dialog.close();
        })
    };

    proto.goToPinJian = function()
    {
        this.moveScreen(18,15);
        this.JianTouShow(16,13);
        this.closeNpcDialog();
    };

    proto.PinJian = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 490,
                y: 240,
                width: 70,
                height: 70,
                text:'guide/zhiyingwenzhi6_4.png',
                textx:400,
                texty:180,
                fingerx:500,
                fingery:250
            },
            {
                type:'rectangle',
                x: 240,
                y: 440,
                width: 90,
                height: 50,
                text:'guide/zhiyingwenzhi6_5.png',
                textx:400,
                texty:400,
                fingerx:260,
                fingery:450
            },
            {
                type:'circle',
                x: 930,
                y: 132,
                radius:30,
                text:'guide/55.png',
                textx:700,
                texty:120,
                fingerx:910,
                fingery:140
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.wei_pinjian_list.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[1]);
        });

    };

    proto.pinJianConform = function(dialog)
    {
        var step = [
            {
                type:'rectangle',
                x: 60,
                y: 140,
                width: 130,
                height: 50,
                text:'guide/zhiyingwenzhi6_6.png',
                textx:80,
                texty:100,
                fingerx:100,
                fingery:150
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[0]);
        dialog.yes.on('click',this,function(){
            this.PinJian(this.DialogArr[0],2);
            this.sendGuideStep(10,0);
        })
    };

    proto.pinJianResult = function(dialog)
    {
        var step = [
            {
                type:'rectangle',
                x: 190,
                y: 452,
                width: 110,
                height: 50,
                text:'guide/zhiyingwenzhi6_7.png',
                textx:-50,
                texty:150,
                fingerx:230,
                fingery:470
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[0]);
        dialog.goto_choujiang.on('click',this,function(){
            this.openNpcDialog();
            this.nextStep();
        });
    };

    proto.goToChouJian = function()
    {
        this.moveScreen(24,17);
        this.JianTouShow(21,14);
    };

    proto.ChouJiang = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 0,
                y: 0,
                width: 0,
                height: 0,
                text:'guide/zhiyingwenzhi7_2.png',
                textx:100,
                texty:180,
                fingerx:0,
                fingery:0
            },
            {
                type:'rectangle',
                x: 150,
                y: 380,
                width: 120,
                height: 50,
                text:'guide/zhiyingwenzhi7_3.png',
                textx:60,
                texty:330,
                fingerx:190,
                fingery:390
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);

        if(num == 0){
            var next_btn = new Laya.Button('guide/xiayibukuang.png','下一步');
            next_btn.pos(700,300);
            next_btn.scale(0.5,0.5);
            next_btn.labelSize = 26;
            next_btn.stateNum = 2;
            dialog.guideContainer.addChild(next_btn);
            next_btn.on('click',this,function(){
                next_btn.removeSelf();
                this.dialogGuideNextStep(dialog,step[1]);
            });
        }

    };

    proto.JiFenDuiHuan = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 0,
                y: 0,
                width: 0,
                height: 0,
                text:'guide/zhiyingwenzhi7_4.png',
                textx:100,
                texty:180,
                fingerx:0,
                fingery:0
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);

        if(num == 0){
            var next_btn = new Laya.Button('guide/xiayibukuang.png','下一步');
            next_btn.pos(700,300);
            next_btn.scale(0.5,0.5);
            next_btn.labelSize = 26;
            next_btn.stateNum = 2;
            dialog.guideContainer.addChild(next_btn);
            next_btn.on('click',this,function(){
                next_btn.removeSelf();
                dialog.close();
                this.sendGuideStep(11,0);
            });
        }
    };

    proto.goToOrder = function()
    {
        this.moveScreen(28,23);
        this.JianTouShow(27,22);

    };

    proto.Order = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 330,
                y: 140,
                width: 140,
                height: 180,
                text:'guide/zhiyingwenzhi8_4.png',
                textx:100,
                texty:350,
                fingerx:370,
                fingery:230
            },
            {
                type:'rectangle',
                x: 50,
                y: 465,
                width: 150,
                height: 50,
                text:'guide/zhiyingwenzhi8_5.png',
                textx:100,
                texty:350,
                fingerx:100,
                fingery:470
            },
            {
                type:'rectangle',
                x: 210,
                y: 460,
                width: 70,
                height: 55,
                text:'guide/zhiyingwenzhi8_2.png',
                textx:100,
                texty:350,
                fingerx:220,
                fingery:470
            },
            {
                type:'rectangle',
                x: 130,
                y: 435,
                width: 80,
                height: 95,
                text:'guide/zhiyingwenzhi8_3.png',
                textx:100,
                texty:350,
                fingerx:160,
                fingery:470
            },
            {
                type:'circle',
                x: 805,
                y: 57,
                radius:30,
                text:'guidetext/55.png',
                textx:550,
                texty:30,
                fingerx:775,
                fingery:50
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        var click_box = new Laya.Image();
        click_box.size(step[0].width,step[0].height);
        click_box.pos(step[0].x,step[0].y);
        click_box.zOrder = 1000;
        dialog.addChild(click_box);
        click_box.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[1]);
            click_box.removeSelf();
        });
        dialog.complete_btn.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[2]);
            this.sendGuideStep(12,0);
        });
        dialog.del_btn.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[3]);
        });
        dialog.refresh_btn.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[4]);
        });

    };

    proto.goToYanJiuSuo = function()
    {
        this.moveScreen(29,20);
        this.JianTouShow(27,18);
        this.closeNpcDialog();
    };

    proto.YanJiuSuo = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 735,
                y: 170,
                width: 75,
                height: 50,
                text:'guide/zhiyingwenzhi9_4.png',
                textx:540,
                texty:250,
                fingerx:750,
                fingery:180
            },
            {
                type:'rectangle',
                x: 430,
                y: 230,
                width: 90,
                height: 115,
                text:'guide/zhiyingwenzhi9_5.png',
                textx:170,
                texty:300,
                fingerx:460,
                fingery:260
            },
            {
                type:'rectangle',
                x: 215,
                y: 465,
                width: 110,
                height: 55,
                text:'guide/zhiyingwenzhi9_6.png',
                textx:220,
                texty:410,
                fingerx:250,
                fingery:480
            },
            {
                type:'circle',
                x: 905,
                y: 112,
                radius:30,
                text:'guide/55.png',
                textx:600,
                texty:100,
                fingerx:895,
                fingery:112
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.tab.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[1]);
        });
        dialog.view_stack.clickNum = 0;
        dialog.view_stack.on('click',this,function(){
            dialog.view_stack.clickNum ++;
            if(dialog.view_stack.clickNum == 3){
                this.dialogGuideNextStep(dialog,step[2]);
            }
        });
        dialog.compound_btn.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[3]);
            this.sendGuideStep(13,0);
        });
    };

    proto.goToPeiYuShi = function()
    {
        this.moveScreen(23,20);
        this.JianTouShow(22,19);
        this.closeNpcDialog();
    };

    proto.PeiYuShi = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 135,
                y: 190,
                width: 120,
                height: 50,
                text:'guide/zhiyingwenzhi10_3.png',
                textx:50,
                texty:120,
                fingerx:180,
                fingery:200
            },
            {
                type:'rectangle',
                x: 852,
                y: 190,
                width: 70,
                height: 50,
                text:'guide/zhiyingwenzhi10_4.png',
                textx:580,
                texty:300,
                fingerx:870,
                fingery:200
            },
            {
                type:'rectangle',
                x: 595,
                y: 245,
                width: 85,
                height: 120,
                text:'guide/zhiyingwenzhi10_5_1.png',
                textx:550,
                texty:200,
                fingerx:620,
                fingery:270
            },
            {
                type:'rectangle',
                x: 240,
                y: 410,
                width: 140,
                height: 55,
                text:'guide/zhiyingwenzhi10_5_2.png',
                textx:260,
                texty:350,
                fingerx:300,
                fingery:430
            },
            {
                type:'circle',
                x: 740,
                y: 83,
                radius:30,
                text:'guide/55.png',
                textx:500,
                texty:100,
                fingerx:730,
                fingery:83
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        dialog.item1.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[1]);
        });
        dialog.tab_yanye.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[2]);
        });
        dialog.view_stack.clickNum = 0;
        dialog.view_stack.on('click',this,function(){
            dialog.view_stack.clickNum ++;
            if(dialog.view_stack.clickNum == 2){
                this.dialogGuideNextStep(dialog,step[3]);
            }
        });
        dialog.peiyu_btn.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[4]);
            this.sendGuideStep(14,0);
        });
    };

    proto.goToYouLeChang = function()
    {
        this.moveScreen(32,19);
        this.JianTouShow(32,17);
        this.closeNpcDialog();
    };

    proto.YouLeChang = function(dialog,num)
    {
        var num = num?num:0;
        var step = [
            {
                type:'rectangle',
                x: 135,
                y: 190,
                width: 0,
                height: 0,
                text:'guide/zhiyingwenzhi13_4.png',
                textx:250,
                texty:120,
                fingerx:0,
                fingery:0
            },
            {
                type:'rectangle',
                x: 50,
                y: 140,
                width: 250,
                height: 100,
                text:'guide/zhiyingwenzhi13_5.png',
                textx:290,
                texty:280,
                fingerx:150,
                fingery:180
            },
            {
                type:'rectangle',
                x: 350,
                y: 140,
                width: 250,
                height: 100,
                text:'guide/zhiyingwenzhi13_6.png',
                textx:290,
                texty:280,
                fingerx:450,
                fingery:180
            },
            {
                type:'rectangle',
                x: 640,
                y: 140,
                width: 250,
                height: 100,
                text:'guide/zhiyingwenzhi13_7.png',
                textx:180,
                texty:280,
                fingerx:750,
                fingery:180
            },
            {
                type:'circle',
                x: 890,
                y: 40,
                radius:30,
                text:'guide/55.png',
                textx:600,
                texty:80,
                fingerx:880,
                fingery:30
            }
        ];
        this.createDialogMask(dialog);
        this.dialogGuideNextStep(dialog,step[num]);
        if(num == 0){
            var next_btn = new Laya.Button('guide/xiayibukuang.png','下一步');
            next_btn.pos(700,200);
            next_btn.scale(0.5,0.5);
            next_btn.labelSize = 26;
            next_btn.stateNum = 2;
            dialog.guideContainer.addChild(next_btn);
            next_btn.on('click',this,function(){
                next_btn.removeSelf();
                this.dialogGuideNextStep(dialog,step[1]);
            });
        }

        dialog.wabao.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[2]);
        });
        dialog.xxl.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[3]);
        });
        dialog.dati.on('click',this,function(){
            this.dialogGuideNextStep(dialog,step[4]);
            this.sendGuideStep(15,0);
        });
    };

    proto.dialogGuideNextStep = function(dialog,step)
    {
        dialog.hitArea.unHit.clear();
        dialog.interactionArea.graphics.clear();

        if(step.type == 'rectangle'){
            dialog.hitArea.unHit.drawRect(step.x, step.y, step.width, step.height, "#000000");
            dialog.interactionArea.graphics.drawRect(step.x, step.y, step.width, step.height, "#000000");
        }else if(step.type == 'circle'){
            dialog.hitArea.unHit.drawCircle(step.x, step.y, step.radius, "#000000");
            dialog.interactionArea.graphics.drawCircle(step.x, step.y, step.radius, "#000000");
        }
        if(step.text){
            dialog.tipContainer.graphics.clear();
            dialog.tipContainer.loadImage(step.text);
            dialog.tipContainer.pos(step.textx, step.texty);
        }else {
            dialog.tipContainer.graphics.clear();
        }
        if(step.fingerx && step.fingery){
            dialog.finger.pos(step.fingerx,step.fingery);
            dialog.finger.visible = true;
        }else {
            dialog.finger.visible = false;
        }

    };

    proto.JianTouShow = function(col,row)
    {
        var point = this.map.getPosByindex(col,row);
        this.JianTou.visible = true;
        this.JianTou.pos(point.x,point.y);
    };

    proto.JianTouHide = function()
    {
        this.JianTou.visible = false;
    };

    proto.createDialogMask = function(dialog,fullScreen)
    {
        // 引导所在容器
        if(!dialog.guideContainer){
            dialog.guideContainer = new Laya.Sprite();
            // 设置容器为画布缓存
            dialog.guideContainer.cacheAs = "bitmap";
            dialog.guideContainer.zOrder = 100;
            dialog.addChild(dialog.guideContainer);
            //gameContainer.on("click", this, nextStep);

            //绘制遮罩区，含透明度，可见游戏背景
            dialog.maskArea = new Laya.Sprite();
            dialog.maskArea.alpha = 0.6;
            dialog.maskArea.graphics.drawRect(0, 0, dialog.width, dialog.height, "#000000");

            dialog.guideContainer.addChild(dialog.maskArea);

            //绘制一个圆形区域，利用叠加模式，从遮罩区域抠出可交互区
            dialog.interactionArea = new Laya.Sprite();
             //设置叠加模式
            dialog.interactionArea.blendMode = "destination-out";
            dialog.guideContainer.addChild(dialog.interactionArea);

            dialog.hitArea = new Laya.HitArea();
            dialog.hitArea.hit.drawRect(0, 0, dialog.width, dialog.height, "#000000");

            dialog.guideContainer.hitArea = dialog.hitArea;
            dialog.guideContainer.mouseEnabled = true;

            dialog.finger = new Laya.Image('guidetext/finger.png');
            dialog.finger.visible = false;
            dialog.finger.pos(0,0);
            dialog.guideContainer.addChild(dialog.finger);

            dialog.tipContainer = new Laya.Sprite();
            dialog.guideContainer.addChild(dialog.tipContainer);
        }
    };

    proto.setStep = function(step,index)
    {
        console.log(step);
        /*if(step == 0 && index ==0){
            this.NPC.visible = false;
            this.showLebiGuide();
        }*/
        GuideManager.guideStep = step;
        GuideManager.guideIndex = index+1;
        for(var i = 0; i < this.npcDialogIndex.length; i++)
        {
            if(i <= GuideStep[step].index){
                this.npcDialogIndex[i].gray = false;
            }
        }
        if(GuideStep[step].index >= 7){
            Laya.timer.once(100,this,function(){
                this.NPC.nav.hScrollBar.value += 311;
            });
        }
        this.NPC.Content.skin = GuideStep[step].text[index];
    };

    proto.onGuideContainerClick = function(e)
    {
        //this.nextStep();
        //e.stopPropagation();
    };

    proto.nextStep = function()
    {
        console.log(GuideManager.PlantNum);
        if(GuideManager.guideStep == GuideStep.length)return;
        console.log(GuideManager.guideStep,GuideManager.guideIndex);

        if(GuideManager.guideIndex == GuideStep[GuideManager.guideStep].text.length)
        {

            if(GuideManager.guideStep == 0){
                this.goToBuySeed();
            }else if(GuideManager.guideStep == 1)
            {
                this.goToBuySeed();
                //this.goToFactory();
            }else if(GuideManager.guideStep == 2)
            {
                this.goToPlant(GuideManager.PlantNum);
            }else if(GuideManager.guideStep == 4)
            {
                this.goToSpeedUp();
            }else if(GuideManager.guideStep == 5)
            {
                this.goToShouHuo();
            }else  if(GuideManager.guideStep == 6)
            {
                this.goToBaking();
            }else if(GuideManager.guideStep == 7){
                this.goToAging();
            } else  if(GuideManager.guideStep == 8)
            {
                this.goToFactory();
            }else if(GuideManager.guideStep == 9){
                this.goToPinJian();
            }else if(GuideManager.guideStep == 10){
                this.closeNpcDialog();
                this.goToChouJian();
            }else if(GuideManager.guideStep == 11){
                this.closeNpcDialog();
                this.goToOrder();
            }else if(GuideManager.guideStep == 12){
                this.closeNpcDialog();
                this.goToYanJiuSuo();
            }else if(GuideManager.guideStep == 13){
                this.closeNpcDialog();
                this.goToPeiYuShi();
            }else if(GuideManager.guideStep == 14){
                this.closeNpcDialog();
                this.goToYouLeChang();
            }else  if(GuideManager.guideStep == 15){
                this.closeNpcDialog();
                this.sendGuideStep(100,0);
                this.JianTouHide();
                AllowGuide = false;
            }
            GuideManager.guideStep++;
            GuideManager.guideIndex = 0;
            return;
        }else if(GuideManager.guideStep == 15 && GuideManager.guideIndex == 1){
            this.showGongLue();
        }else if(GuideManager.guideStep == 15 && GuideManager.guideIndex == 2){
            this.hideGongLue();
        }
        this.setStep(GuideManager.guideStep,GuideManager.guideIndex++);
    };

    proto.showGongLue = function()
    {
        //绘制一个圆形区域，利用叠加模式，从遮罩区域抠出可交互区
        var x = 55;
        var y = 185;
        var radius = 50;
        this.interactionArea = new Laya.Sprite();
        //设置叠加模式
        this.interactionArea.blendMode = "destination-out";
        this.guideContainer.addChild(this.interactionArea);

        this.hitArea.unHit.drawCircle(x, y, radius, "#000000");
        this.interactionArea.graphics.drawCircle(x, y, radius, "#000000");
        this.jiantou = new Laya.Image('guide/jiantou_1.png');
        this.jiantou.rotation = 90;
        this.jiantou.pos(220,140);
        this.guideContainer.addChild(this.jiantou);
    };

    proto.hideGongLue = function()
    {
        this.hitArea.unHit.clear();
        this.interactionArea.graphics.clear();
        this.jiantou.removeSelf();
    };

    //proto.
    proto.closeNpcDialog = function()
    {
        this.guideContainer.visible = false;
    };

    proto.openNpcDialog = function()
    {
        this.guideContainer.visible = true;
    };

    proto.moveScreen = function(col,row)
    {
        this.map.mapMoveTo(col,row);
    };

    proto.dialogOpen = function(dialog)
    {
        //console.log(dialog.name,GuideManager.guideStep);
        if(AllowGuide){
            if(dialog.name == 'zldialog' && GuideManager.guideStep == 1){
                this.JianTouHide();
                this.DialogArr.push(dialog);
                this.buyTXS(dialog);
            }else if(dialog.name == 'zlbuy' && GuideManager.guideStep == 1){
                this.DialogArr.push(dialog);
                this.confirmBuyTXS(dialog);
            }else if(dialog.name == 'zlbuy' && GuideManager.guideStep == 2 && GuideManager.actionIndex ==0){
                this.DialogArr.push(dialog);
                this.confirmBuy(dialog);
            }else if(dialog.name == 'zlbuy' && GuideManager.guideStep == 2 && GuideManager.actionIndex ==1){
                this.DialogArr.push(dialog);
                this.confirmBuyLvZui(dialog);
            }else if(dialog.name == 'zldialog' && GuideManager.guideStep == 2){
                this.JianTouHide();
                this.DialogArr.push(dialog);
                this.buySeed(dialog,GuideManager.actionIndex);
            }else if(dialog.name == 'bozhong' && GuideManager.guideStep == 3 && GuideManager.PlantNum <= 5){
                this.JianTouHide();
                this.Plant(dialog);
            }else if(dialog.name == 'plant' && GuideManager.guideStep == 5){
                this.JianTouHide();
                this.speedUp(dialog);
            }else if(dialog.name == 'shouge' && GuideManager.guideStep == 6){
                this.JianTouHide();
                this.shouHuo(dialog);
            }else if(dialog.name == 'hkdialog' && GuideManager.guideStep == 7){
                this.JianTouHide();
                this.DialogArr.push(dialog);
                this.baking(dialog);
            }else if(dialog.name == 'confirm1' && GuideManager.guideStep == 5){
                this.confirmSpeed(dialog);
            }else if(dialog.name == 'confirm1' && GuideManager.guideStep == 7 && this.DialogArr[0]){
                this.DialogArr.push(dialog);
                this.confirmAutoBaking(dialog);
            }else if(dialog.name == 'chdialog' && GuideManager.guideStep == 8){
                this.DialogArr.push(dialog);
                this.aging(dialog);
            }else if(dialog.name == 'confirm1' && GuideManager.guideStep == 8 && this.DialogArr[0]){
                this.DialogArr.push(dialog);
                this.confirmAgingSpeed(dialog);
            }else if(dialog.name == 'jgcdialog' && GuideManager.guideStep == 9){
                this.JianTouHide();
                this.DialogArr.push(dialog);
                this.jiagong(dialog);
            }else if(dialog.name == 'jgcpeifang' && GuideManager.guideStep == 9){
                this.JianTouHide();
                this.DialogArr.push(dialog);
                this.jiagongPF(dialog);
            }else if(dialog.name == 'confirm1' && GuideManager.guideStep == 9 && this.DialogArr[0]){
                this.DialogArr.push(dialog);
                this.confirmJiaGongSpeed(dialog);
            }else if(dialog.name == 'pjdialog' && GuideManager.guideStep == 10){
                this.JianTouHide();
                this.DialogArr.push(dialog);
                this.PinJian(dialog);
            }else if(dialog.name == 'confirm1' && GuideManager.guideStep == 10 && this.DialogArr[0]){
                this.DialogArr.push(dialog);
                this.pinJianConform(dialog);
            }else if(dialog.name == 'pinjian_result' && GuideManager.guideStep == 10){
                this.pinJianResult(dialog);
            }else if(dialog.name == 'choujiang' && GuideManager.guideStep == 11){
                this.JianTouHide();
                this.ChouJiang(dialog);
            }else if(dialog.name == 'jifenduihuan' && GuideManager.guideStep == 11){
                this.JiFenDuiHuan(dialog);
            }else if(dialog.name == 'ggldialog' && GuideManager.guideStep == 12){
                this.JianTouHide();
                this.Order(dialog);
            }else if(dialog.name == 'yanjiusuo' && GuideManager.guideStep == 13){
                this.JianTouHide();
                this.YanJiuSuo(dialog);
            }else if(dialog.name == 'peiyushi' && GuideManager.guideStep == 14){
                this.JianTouHide();
                this.PeiYuShi(dialog);
            }else if(dialog.name == 'youlechang' && GuideManager.guideStep == 15){
                this.JianTouHide();
                this.YouLeChang(dialog);
            }
        }

    };

    proto.dialogClose = function(dialog)
    {
        //console.log(dialog.name,GuideManager.guideStep);
        if(AllowGuide){
            if(dialog.name == 'zldialog' && (GuideManager.guideStep == 1 || GuideManager.guideStep == 2)){
                console.log('lll');
                this.openNpcDialog();
                this.nextStep();
            }else if(dialog.name == 'hkdialog' && GuideManager.guideStep == 7){
                this.openNpcDialog();
                this.nextStep();
            }else if(dialog.name == 'chdialog' && GuideManager.guideStep == 8){
                this.openNpcDialog();
                this.nextStep();
            }else if(dialog.name == 'jgcdialog' && GuideManager.guideStep == 9){
                this.openNpcDialog();
                this.nextStep();
            }else if(dialog.name == 'pjdialog' && GuideManager.guideStep == 10){
                this.openNpcDialog();
                this.nextStep();
            }else if(dialog.name == 'jifenduihuan' && GuideManager.guideStep == 11){
                this.openNpcDialog();
                this.nextStep();
            }else if(dialog.name == 'ggldialog' && GuideManager.guideStep == 12){
                this.openNpcDialog();
                this.nextStep();
            }else if(dialog.name == 'yanjiusuo' && GuideManager.guideStep == 13){
                this.openNpcDialog();
                this.nextStep();
            }else if(dialog.name == 'peiyushi' && GuideManager.guideStep == 14){
                this.openNpcDialog();
                this.nextStep();
            }else if(dialog.name == 'youlechang' && GuideManager.guideStep == 15){
                this.openNpcDialog();
                this.nextStep();
            }
            this.DialogArr.removeObject(dialog);
        }

    };
    //step 大步骤，index小步骤
    proto.sendGuideStep = function(step,index)
    {
        Utils.post('guide/update',{uid:localStorage.GUID,step1:step,step2:index},this.onDataReturn,onHttpErr);
    };

    proto.onDataReturn = function(res)
    {
        console.log(res);
        if(res.code == 0)
        {

        }
    };

    proto.getGuideStep = function()
    {
        Utils.post('guide/status',{uid:localStorage.GUID},this.onGuideStepReturn,onHttpErr);
    };

    proto.onGuideStepReturn = function(res)
    {
        if(res.code == 0)
        {
            GuideManager.isGetStep = true;
            if(res.data.step1 != 100)
            {
                AllowGuide = true;
                GuideManager.instance().createGuide();
                GuideManager.instance().setStep(Number(res.data.step1),0);
                GuideManager.actionIndex = Number(res.data.step2);
                if(res.data.step1 == 2){
                    GuideManager.PlantNum = Number(res.data.step2);
                }
                //GuideManager.instance().setStep(0,0);
            }
        }
    };


    GuideManager.instance=function(){
        if (!GuideManager._instance){
            GuideManager._instance=new GuideManager();
        }
        return GuideManager._instance;
    };

    GuideManager._instance=null;
    GuideManager.isGetStep = false;
    var GuideStep = [
        {
            index: 0,
            text: [
                'guide/zhiyingwenzhi1_1.png'
            ]
        },
        {
            index:0,
            text:[
                'guide/zhiyingwenzhi1_6.png'
            ]
        },
        {
            index:1,
            text:[
                'guide/zhiyingwenzhi2_1.png'
            ]
        },
        {
            index:1,
            text:[
                'guide/zhiyingwenzhi2_7.png'
            ]
        },
        {
            index:1,
            text:[
                'guide/zhiyingwenzhi2_8.png'
            ]
        },
        {
            index:1,
            text:[
                'guide/zhiyingwenzhi2_13.png'
            ]
        },
        {
            index:2,
            text:[
                'guide/zhiyingwenzhi3_1.png',
                'guide/zhiyingwenzhi3_7.png',
                'guide/zhiyingwenzhi3_8.png'
            ]
        },
        {
            index:3,
            text:[
                'guide/zhiyingwenzhi3_9.png'
            ]
        },
        {
            index:4,
            text:[
                'guide/zhiyingwenzhi5_1.png'
            ]
        },
        {
            index:5,
            text:[
                'guide/zhiyingwenzhi6_1.png',
                'guide/zhiyingwenzhi6_2.png'
            ]
        },
        {
            index:6,
            text:[
                'guide/zhiyingwenzhi7_1.png'
            ]
        },
        {
            index:7,
            text:[
                'guide/zhiyingwenzhi8_1.png'
            ]
        },

        {
            index:8,
            text:[
                'guide/zhiyingwenzhi8_6.png',
                'guide/zhiyingwenzhi9_1.png',
                'guide/zhiyingwenzhi9_2.png'
            ]
        },
        {
            index:9,
            text:[
                'guide/zhiyingwenzhi9_7.png',
                'guide/zhiyingwenzhi9_8.png',
                'guide/zhiyingwenzhi10_1.png'
            ]
        },
        {
            index:10,
            text:[
                'guide/zhiyingwenzhi10_6.png',
                'guide/zhiyingwenzhi11_1.png'
            ]
        },
        {
            index:10,
            text:[
                'guide/zhiyingwenzhi11_3.png',
                'guide/zhiyingwenzhi12_1.png',
                'guide/zhiyingwenzhi13_1.png',
                'guide/zhiyingwenzhi13_2.png',
                'guide/zhiyingwenzhi13_3.png'
            ]
        }

    ];
    var hitArea;
    //var interactionArea;
    GuideManager.GuideContent = GuideStep;
    GuideManager.guideStep = 0;
    GuideManager.guideIndex = 0;
    GuideManager.actionIndex = 0;
    GuideManager.PlantNum = 0;
    return GuideManager;
})();