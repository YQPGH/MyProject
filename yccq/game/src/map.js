/**
 * Created by lkl on 2017/2/10.
 */
(function()
{
    var TiledMap = Laya.TiledMap;
    var Browser = Laya.Browser;
    var Handler = Laya.Handler;
    var Rectangle = Laya.Rectangle;
    var Point = Laya.Point;
    var Event = Laya.Event;
    var Tween = Laya.Tween;
    //上次记录的两个触模点之间距离
    var lastDistance = 0;
    var lastPivotX = lastPivotY = 0;
    var dragRegion;
    function Maps(caller)
    {
        Maps.__super.call(this);
        lastDistance = 0;
        lastPivotX = lastPivotY = 0;
        this.caller = caller;
        this.hasScale = false;
        this.name = 'map';
        this.initMaps();
    }
    Laya.class(Maps,'ZY.Maps',Laya.Box);
    var proto = Maps.prototype;

    proto.initMaps = function ()
    {
        this.tiledMap = new TiledMap();
        this.tiledMap.createMap("map/map1.json", new Rectangle(0, 0, Browser.clientWidth, Browser.clientHeight), Handler.create(this,this.mapCompleteHandler));
        this.tiledMap.autoCacheType = 'normal';
    };

    proto.mapCompleteHandler = function()
    {
        this.mapSprite = this.tiledMap.mapSprite();
        this.mapSprite.name = 'mapSprite';
        this.mapSprite.mouseThrough = true;
        this.mapSprite.zOrder = -1;
        this.mapLayer = this.tiledMap.getLayerByName("map");
        this.mapSprite.autoSize = true;
        this.resize();
        this.caller.mapCompleteHandler();
        Laya.stage.on(Event.MOUSE_DOWN,this,this.mouseDown);
        Laya.stage.on(Event.MOUSE_UP,this,this.mouseUp);


        this.mapSprite.on(Event.DRAG_END,this,this.onDragEnd);
        this.mapSprite.on(Event.DRAG_START,this,this.onDragStart);

        this.addBackGround();
        this.initTree();
        this.addAni();


    };

    // 窗口大小改变，把地图的视口区域重设下
    proto.resize = function () {
        //改变地图视口大小
        //this.tiledMap.scale = 1;
        var pos = this.getPosByindex(25,25);
        this.mX = pos.x - parseInt(GameSize.width/2);
        this.mY = pos.y - parseInt(GameSize.height/2);
        this.tiledMap.changeViewPort(0,0,this.tiledMap.width, this.tiledMap.height);
        this.mapSprite.pos(-this.mX,-this.mY);

    };

    //拖动地图
    //鼠标按下拖动地图
    proto.mouseDown = function (e) {
        hasMove = false;
        //
        //Dialog.manager.closeAll();
        if(mapMove) return;
        if(NPCShow) return;

        if (hasDialog) return;

        var touches = e.touches;

        /*if (touches && touches.length == 2)
        {
            this.mapSprite.stopDrag();
            lastDistance = Utils.getDistance(touches);

            // 把地图容器轴心移到当前手指位置
            var pivotX = this.mapSprite.mouseX;
            var pivotY = this.mapSprite.mouseY;
            this.mapSprite.pivot(pivotX, pivotY);
            // 把地图容器位置恢复到原来的位置
            var tempX = this.mapSprite.x;
            var tempY = this.mapSprite.y;
            var distanceX = pivotX - lastPivotX;
            var distanceY = pivotY - lastPivotY;
            this.mapSprite.pos(tempX + distanceX * this.mapSprite.scaleX, tempY + distanceY * this.mapSprite.scaleX);
            lastPivotX = pivotX;
            lastPivotY = pivotY;
            console.log('xy:', tempX, tempY);
            console.log('pivot:', pivotX, pivotY);
            hasScale = true;
        }else {*/
            //鼠标按下开始拖拽(设置了拖动区域和超界弹回的滑动效果)
            //var dragRegion = new Rectangle((-this.tiledMap.width+this.mapSprite.pivotX)*this.mapSprite.scaleX+Laya.stage.width, (-this.tiledMap.height+this.mapSprite.pivotY)*this.mapSprite.scaleY+Laya.stage.height, this.tiledMap.width*this.mapSprite.scaleX-Laya.stage.width, this.tiledMap.height*this.mapSprite.scaleY-Laya.stage.height);

            var dragRegion = new Rectangle((-4500+this.mapSprite.pivotX)*this.mapSprite.scaleX+Laya.stage.width, (-2350+this.mapSprite.pivotY)*this.mapSprite.scaleY+Laya.stage.height, 2650*this.mapSprite.scaleX-Laya.stage.width, 1550*this.mapSprite.scaleY-Laya.stage.height);
            this.mapSprite.startDrag(dragRegion, true, 50);
            //this.mapSprite.startDrag(null, true, 100);
        //}

        Laya.stage.on(Event.MOUSE_MOVE,this,this.mouseMove);

    };

    proto.mouseMove = function (e) {

        //Dialog.manager.closeNotFull();
        Dialog.manager.closeAll();
        var touches = e.touches;
        /*if (touches && touches.length == 2) {
            if (this.mapSprite.scaleX > 1.5) {
                this.mapSprite.scaleX = this.mapSprite.scaleY = 1.5;
                return;
            }
            if (this.mapSprite.scaleX < 0.7) {
                this.mapSprite.scaleX = this.mapSprite.scaleY = 0.7;
                return;
            }

            var distance = Utils.getDistance(touches);

            //判断当前距离与上次距离变化，确定是放大还是缩小
            const factor = 0.005;
            this.mapSprite.scaleX += (distance - lastDistance) * factor;
            this.mapSprite.scaleY += (distance - lastDistance) * factor;
            lastDistance = distance;

        } else {
            //移动地图视口1024
            //this.tiledMap.moveViewPort(mX - (Laya.stage.mouseX - mLastMouseX), mY - (Laya.stage.mouseY - mLastMouseY));
            //this.mapSprite.pos(mX + (Laya.stage.mouseX - mLastMouseX), mY + (Laya.stage.mouseY - mLastMouseY));
        }*/

    };

    proto.mouseUp = function (e) {
        Laya.timer.once(200,this,function(){hasScale = false;});
        var p = new Laya.Point(Laya.stage.mouseX,Laya.stage.mouseY);
        this.mapSprite.globalToLocal(p);
        console.log(this.getIndexByPos(p.x,p.y));
        Laya.stage.off(Event.MOUSE_MOVE,this,this.mouseMove);

    };

    proto.onDragStart = function()
    {
        hasMove = true;
    };

    proto.onDragEnd = function()
    {
        hasMove = false;
        this.mapSprite.stopDrag();
    };


    //添加建筑
    proto.addBuilding = function(obj,col,row)
    {
        if(!obj)
        {
            return;
        }
        var p = this.getPosByindex(col,row);
        obj.pos(p.x,p.y);
        obj.zOrder = col*100+row;
        this.mapSprite.addChild(obj);
    };

    //移动到指定位置
    proto.mapMoveTo = function(col,row,caller,handler)
    {
        mapMove = true;
        var complete = null;
        if(caller && handler){
            complete = new Laya.Handler(caller, handler);
        }
        var p = this.getPosByindex(col,row);
        //this.mapSprite.pivot(0,0);
        var x = parseInt((p.x-this.mapSprite.pivotX)*this.mapSprite.scaleX) - parseInt(GameSize.width/2);
        var y = parseInt((p.y-this.mapSprite.pivotY)*this.mapSprite.scaleY) - parseInt(GameSize.height/2);
        console.log(x+','+y);
        Tween.to(this.mapSprite,
            {
                x: -x,
                y: -y
            }, 500,null,new Laya.Handler(this,function(){
                mapMove = false;
                if(complete){
                    complete.run()
                }

            }));
    };

    //移动一段距离
    proto.mapMoveBy = function(x,y)
    {
        var x = this.mapSprite.x - x;
        var y = this.mapSprite.y - y;
        Tween.to(this.mapSprite,
            {
                x: x,
                y: y
            }, 500);
    };

    proto.getPosByindex = function (col, row) {
        var p = new Point(0, 0);
        this.mapLayer.getScreenPositionByTilePos(col, row, p);


        p.x = Math.floor(p.x);
        p.y = Math.floor(p.y) + Math.floor(this.tiledMap.tileHeight / 2);
        return p;
    };

    proto.getIndexByPos = function (x, y) {
        var p = new Point(x, y);
        //this.mapSprite.globalToLocal(p);
        this.mapLayer.getTilePositionByScreenPos(p.x, p.y, p);
        p.x = Math.floor(p.x);
        p.y = Math.floor(p.y);
        return p;
    };

    proto.addBackGround = function()
    {
        var shan = new Laya.Sprite();
        shan.loadImage('mapbg/shan.png');
        shan.pos(1720,680);
        this.mapSprite.addChild(shan);

        var hai1 = new Laya.Sprite();
        hai1.loadImage('mapbg/haibianjiaoshi.png');
        hai1.pos(1720,1850);
        this.mapSprite.addChild(hai1);
    };

    proto.initTree = function()
    {
        //大篱笆
        var Liba_da = [[20,35,0],[20,34,0],[20,33,0],[20,32,0],[20,31,0],[15,35,1],[15,34,1],[15,33,1],[15,32,1],[15,31,1],[16,36,2],[17,36,2],[18,36,2],[19,36,2]];
        for(var i = 0,len = Liba_da.length; i < len; i++)
        {
            var liba = new Liba(Liba_da[i][2]);
            this.addBuilding(liba,Liba_da[i][0],Liba_da[i][1]);
        }

        //小篱笆
        var Liba_xiao = [[25,25,2],[26,25,2],[27,25,2],[28,23,3],[29,23,3],[30,23,3],[31,25,2],[32,25,2],[33,25,2],[11,25,2],[12,25,2],[13,25,2],[14,25,2],[15,25,2],[16,25,2],[17,25,2],[18,25,2],[19,25,2],[20,25,2],[21,29,1],[23,36,0],[23,27,0],[23,22,0],[23,21,0],[23,17,0],[20,20,2],[19,20,2],[20,15,2],[19,15,2],[26,31,2],[25,31,2],[27,30,0],[27,30,2],[28,31,0],[28,32,0],[28,35,0],[28,36,0],[28,37,0],[28,38,0],[28,39,0],[28,35,2],[29,35,2],[30,35,2],[31,35,2],[30,29,0],[30,28,0],[30,27,0],[38,30,1],[38,29,1]];
        for(var i = 0,len = Liba_xiao.length; i < len; i++)
        {
            var liba = new LibaSmall(Liba_xiao[i][2]);
            this.addBuilding(liba,Liba_xiao[i][0],Liba_xiao[i][1]);
        }

        //石阶
        var shjie = [[28,25,0],[28,26,1],[28,27,1],[28,28,0],[27,28,1],[27,29,0],[28,30,0],[28,31,0],[28,32,0],[28,33,1],[27,33,0],[27,34,1],[34,36,0],[37,33,1],[37,32,0],[38,32,0],[38,31,1],[38,30,1],[38,29,1],[38,28,0],[37,27,1],[37,26,1],[36,26,0],[35,26,0],[34,26,1],[34,25,0],[31,23,0],[24,23,0],[25,23,0],[20,23,0],[20,28,1],[20,29,1],[20,30,1],[21,30,0],[19,30,0]];
        for(var i = 0,len = shjie.length; i < len; i++)
        {
            var sj = new Shijie(shjie[i][2]);
            this.addBuilding(sj,shjie[i][0],shjie[i][1]);
            sj.zOrder = 0;
        }

        //小石阶
        var shjie_small = [[21,27,0],[21,23,1],[23,23,1],[26,23,1],[32,23,1],[25,30,0],[10,24,2],[10,24,3]];
        for(var i = 0,len = shjie_small.length; i < len; i++)
        {
            var sj_small = new ShijieSmall(shjie_small[i][2]);
            this.addBuilding(sj_small,shjie_small[i][0],shjie_small[i][1]);
            sj_small.zOrder = 0;
        }

        //松树
        var Songshu = [[22,41],[31,40],[33,40],[35,40],[36,38],[38,38],[40,35],[31,14],[25,13],[24,7],[23,7],[25,6],[12,14],[14,14],[13,12],[15,11],[17,11],[20,7],[20,5],[8,29],[20,41],[19,40],[15,36],[9,22],[8,20]];
        for(var i = 0,len = Songshu.length; i < len; i++)
        {
            this.addBuilding(new TreeSongshu(),Songshu[i][0],Songshu[i][1]);
        }

        //榕树
        //config.Rongshu = [[28,40],[34,40],[35,39],[37,29],[24,29],[35,20],[8,23],[8,19],[11,21],[14,23],[14,17],[8,27],[10,31],[13,32]];

        for(var i = 0,len = config.Rongshu.length; i < len; i++)
        {
            this.addBuilding(new TreeRongshu(),config.Rongshu[i][0],config.Rongshu[i][1]);
        }
        //柳树
        var Liushu = [[13,26],[12,29],[26,15],[28,14],[31,17],[10,16],[12,19]];

        for(var i = 0,len = Liushu.length; i < len; i++)
        {
            this.addBuilding(new TreeLiushu(),Liushu[i][0],Liushu[i][1]);
        }

        //柏树
        var Baishu = [[18,9],[16,13],[20,8],[20,10],[23,5],[29,23],[34,23],[39,23]];
        for(var i = 0,len = Baishu.length; i < len; i++)
        {
            this.addBuilding(new TreeBaishu(),Baishu[i][0],Baishu[i][1]);
        }

        //植物1
        var zhiwu1 = [[9,21,0],[11,15,0],[15,30,0],[23,16,0],[28,35,0],[31,37,0],[32,25,0],[37,25,0],[36,27,0],[27,14,0]];
        for(var i = 0,len = zhiwu1.length; i < len; i++)
        {
            var zhiwu = new TreeZhiwu1(zhiwu1[i][2]);
            this.addBuilding(zhiwu,zhiwu1[i][0],zhiwu1[i][1]);
        }

        //植物2
        var zhiwu2 = [[23,35,1],[27,40,1],[33,36,0],[39,29,2],[27,20,2],[31,18,2],[30,14,2],[21,38,1],[22,39,1],[29,31,1],[33,25,2],[25,38,1],[29,37,0],[26,16,1]];
        for(var i = 0,len = zhiwu2.length; i < len; i++)
        {
            var zhiwu = new TreeZhiwu2(zhiwu2[i][2]);
            this.addBuilding(zhiwu,zhiwu2[i][0],zhiwu2[i][1]);
        }

        //植物3
        var zhiwu3 = [[13,25,0],[17,25,0],[19,38,0],[21,37,2],[21,36,2],[21,31,2],[21,25,2],[23,41,0],[23,36,3],[29,30,3],[27,25,1],[30,27,0],[37,34,0],[33,25,1],[38,25,0],[23,19,0],[23,13,0],[26,9,0],[21,10,2],[16,15,0],[30,40,0],[27,38,1],[38,27,3],[26,30,0]];
        for(var i = 0,len = zhiwu3.length; i < len; i++)
        {
            var zhiwu = new TreeZhiwu3(zhiwu3[i][2]);
            this.addBuilding(zhiwu,zhiwu3[i][0],zhiwu3[i][1]);
        }

        //植物4
        var zhiwu4= [[29,38,1],[23,38,2],[38,35,2],[23,30,2],[23,22,1],[21,20,2],[24,14],[21,15,2]];
        for(var i = 0,len = zhiwu4.length; i < len; i++)
        {
            var zhiwu = new TreeZhiwu4(zhiwu4[i][2]);
            this.addBuilding(zhiwu,zhiwu4[i][0],zhiwu4[i][1]);
        }

        //草
        var grass= [[21,38,0],[19,27,0],[10,26,1],[26,21,2],[26,20,3],[30,23,0],[31,23,4],[21,8,0],[13,26,0],[31,17,3],[28,14,5],[26,15,0],[10,25,2],[38,23,0]];
        for(var i = 0,len = grass.length; i < len; i++)
        {
            var zhiwu = new TreeGrass(grass[i][2]);
            this.addBuilding(zhiwu,grass[i][0],grass[i][1]);
        }

        //石头
        var shitou = [[23,37,1],[28,36,1],[29,39,0],[39,32,0],[33,27,0],[32,19,1],[25,19,0],[29,10,0],[13,18,1],[12,20,0],[13,28,0],[6,22,0],[21,33,1]];
        for(var i = 0,len = shitou.length; i < len; i++)
        {
            var st = new Shitou(shitou[i][2]);
            this.addBuilding(st,shitou[i][0],shitou[i][1]);
        }

        //小石头
        var shitou_small = [[22,38,0],[22,38,1],[22,35,0],[21,32,0],[22,29,0],[21,27,1],[21,26,0],[24,23,1],[21,19,0],[21,12,0],[22,11,0],[21,9,0],[22,8,2],[22,8,3],[26,23,1],[33,23,1],[36,25,2],[21,17,0]];
        for(var i = 0,len = shitou_small.length; i < len; i++)
        {
            var st_small = new ShitouSmall(shitou_small[i][2]);
            this.addBuilding(st_small,shitou_small[i][0],shitou_small[i][1]);
            st_small.zOrder = 0;
        }

        //鹅
        var e = new Laya.Image('tex/e.png');
        e.pivot(e.width/2,e.height-20);
        this.addBuilding(e,10,28);

        var e1 = new Laya.Image('tex/e.png');
        e1.pivot(e1.width,e1.height-20);
        this.addBuilding(e1,12,17);

        //木头
        var mutou = new Laya.Image('tex/gongdi.png');
        this.addBuilding(mutou,15,16);
        var mutou = new Laya.Image('tex/gongdi.png');
        this.addBuilding(mutou,33,16);

        //路灯
        var ludeng = new Laya.Image('tex/ludeng.png');
        ludeng.pivot(ludeng.width/2,ludeng.height-20);
        this.addBuilding(ludeng,27,23);
      

        var paizi = new Laya.Image('tex/zhongzhiqu.png');
        paizi.pivot(45,86);
        this.addBuilding(paizi,20,31);

        //温室
        var wenshi = new Laya.Image('tex/wenshi.png');
        wenshi.pivot(wenshi.width/2,wenshi.height/2);
        this.addBuilding(wenshi,13,31);

        //井
        var jing = new Laya.Image('tex/jing.png');
        jing.pivot(jing.width/2-20,58);
        this.addBuilding(jing,26,37);

        //水桶
        var tong = new Laya.Image('tex/tong.png');
        tong.pivot(tong.width-20,65);
        this.addBuilding(tong,27,37);

        //桌子
        var zhuoziArr = [[18,23],[25,36]];
        for(var i = 0; i < zhuoziArr.length; i++)
        {
            var zhuozi = new Laya.Image('tex/zuo1.png');
            zhuozi.pivot(zhuozi.width/2,55);
            this.addBuilding(zhuozi,zhuoziArr[i][0],zhuoziArr[i][1]);
        }

        //桃树
        var taoshuArr = [[11,23],[24,39],[30,36],[38,26],[24,15],[27,8],[16,18]];
        for(var i = 0; i < taoshuArr.length; i++)
        {
            var taoshu = new Laya.Image('tex/taoshu.png');
            taoshu.pivot(taoshu.width/2,130);
            this.addBuilding(taoshu,taoshuArr[i][0],taoshuArr[i][1]);
        }

        //瓦罐
        var waguanArr = [[19,27],[24,36],[27,21]];
        for(var i = 0; i < waguanArr.length; i++)
        {
            var waguan = new Laya.Image('tex/waguan.png');
            waguan.pivot(waguan.width/2,45);
            this.addBuilding(waguan,waguanArr[i][0],waguanArr[i][1]);
        }

        //钓鱼翁
        var yuweng = new DiaoYuWeng();
        this.addBuilding(yuweng,26,17);


    };

    proto.addAni = function()
    {
        //右边的还海岸动画
        var haian = new HaianAni();
        haian.pos(3700,1780);
        this.mapSprite.addChild(haian);

        //船
        this.ship = new ShipAni(this);
        this.addBuilding(this.ship,36,22);

        //生产5只海鸥、小鸟随机飞动
        for(var i = 0; i < 5; i++){
            var haio1 = new HaioAni();
            this.mapSprite.addChild(haio1);

            var Bird = new BirdAni();
            this.mapSprite.addChild(Bird);
        }
        //有鱼的水池
        var pond = new PondAni();
        pond.pos(3970,1450);
        this.mapSprite.addChild(pond);

        //左边海浪
        var hailang = new HailangAni();
        hailang.pos(1720,1850);
        this.mapSprite.addChild(hailang);

        //马车
        this.gharry = new GharryAni();
        this.addBuilding(this.gharry,22,0);
        this.resetGharry();

        //工人
        var gongren = new Gongren();
        this.addBuilding(gongren,37,33);
        var point1 = this.getPosByindex(37,33),
            point2 = this.getPosByindex(37,30),
            point3 = this.getPosByindex(30,29),
            point4 = this.getPosByindex(30,33);

        this.timeLine = new Laya.TimeLine();
        this.timeLine.addLabel("action01",0).to(gongren,{x:point2.x, y:point2.y},2000,null,0)
            .addLabel("action02",0).to(gongren,{x:point3.x, y:point3.y},4000,null,0)
            .addLabel("action03",0).to(gongren,{x:point4.x, y:point4.y},2000,null,0)
            .addLabel("action04",0).to(gongren,{x:point3.x, y:point3.y},2000,null,0)
            .addLabel("action05",0).to(gongren,{x:point2.x, y:point2.y},4000,null,0)
            .addLabel("action06",0).to(gongren,{x:point1.x, y:point1.y},2000,null,0);
        this.timeLine.play(0,true);
        this.timeLine.on(Event.COMPLETE,this,this.onTimeLineComplete,[gongren]);
        this.timeLine.on(Event.LABEL, this, this.onTimeLineLabel,[gongren]);

        //girl
        this.girl = new GirlAni(this);
        //boy
        this.boy = new BoyAni(this);


    };

    proto.resetGharry = function()
    {
        this.gharry.skewY = 0;
        this.gharry.PlayAni(0);
        this.setGharryDest(22,0,22,25,this.onRoad1Complete);
    };

    proto.onTweenUpdate = function()
    {
        var p = this.getIndexByPos(this.gharry.x,this.gharry.y);
        this.gharry.zOrder = (p.x-1)*100+p.y;
    };

    proto.onRoad1Complete = function()
    {
        this.gharry_tween.recover();
        this.setGharryDest(24,24,39,24,this.onRoad2Complete);
        this.gharry.skewY = 180;
    };

    proto.onRoad2Complete = function(){
        this.gharry_tween.recover();
        this.gharry.body.gotoAndStop(3);
        Laya.timer.once(3000,this,this.shipStart);
    };

    proto.shipStart = function()
    {
        this.ship.startWork()
    };

    proto.BackRoad1 = function()
    {
        this.gharry.PlayAni(1);
        this.setGharryDest(39,24,24,24,this.onBackRoad1Complete);
        this.gharry.skewY = 0;
    };

    proto.onBackRoad1Complete = function()
    {
        this.gharry_tween.recover();
        this.setGharryDest(22,25,22,0,this.resetGharry);
        this.gharry.skewY = 180;
    };

    /*
    * gharry_col :马车列坐标
    * gharry_row :马车行坐标
    * dest_col :目标列坐标
    * dest_row :目标行坐标
    * complete :完成动作后回调函数
    */
    proto.setGharryDest = function(gharry_col, gharry_row,dest_col,dest_row,complete)
    {
        var p = this.getPosByindex(gharry_col,gharry_row);
        this.gharry.pos(p.x,p.y);

        var Dest_1 = this.getPosByindex(dest_col,dest_row);

        var s = Utils.ABDistance([p.x,p.y],[Dest_1.x,Dest_1.y]);
        var t = s/0.1;
        var completeHandler = complete?new Laya.Handler(this,complete):null;
        this.gharry_tween = Laya.Tween.to(this.gharry,{x:Dest_1.x,y:Dest_1.y},t,null,completeHandler);
        this.gharry_tween.update = new Laya.Handler(this,this.onTweenUpdate);
    };

    proto.onTimeLineComplete = function(obj)
    {
        obj.PlayAni('up');
    };

    proto.onTimeLineLabel = function(obj,Label)
    {
        switch(Label)
        {
            case 'action01':
                obj.PlayAni('up');

                break;
            case 'action02':
                obj.PlayAni('left');
                obj.zOrder = 30*100+29;
                break;
            case 'action03':
                obj.PlayAni('down');
                break;
            case 'action04':
                obj.PlayAni('up');
                break;
            case 'action05':
                obj.PlayAni('right');
                break;
            case 'action06':
                obj.PlayAni('down');
                obj.zOrder = 37*100+31;
                break;
        }
    };

    proto.beforeMapDestroy = function()
    {
        this.timeLine.destroy();
        this.girl.destroyAni();
        this.boy.destroyAni();
    }
})();