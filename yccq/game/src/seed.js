/**
 * Created by lkl on 2017/1/17.
 */

(function() {
    // 种子类
    var HttpRequest = Laya.HttpRequest;
    var Event = Laya.Event;
    var Text = Laya.Text;
    var hr = null;
    function Seed(seedData) {
        Seed.__super.call(this);
        this.young   = null;//幼苗期
        this.growing = null;//成长期
        this.mature  = null;//成熟期

        this.width = 128;
        this.height = 64;
        this.pivot(64,32);
        this.isMature = false;//是否成熟
        //this.seedNum = seedNum;//成熟后获得的数量
        this.setSeed(seedData);
        this.plant_record_id = 0;

        this.init();

    }

    Laya.class(Seed, "Seed",Laya.Sprite);
    var _proto = Seed.prototype;
    _proto.setSeed = function(seedData)
    {
        this.seedData = seedData;
        this.youngTexture = plant[seedData.shop_id].young.texture;
        this.growingTexture = plant[seedData.shop_id].growing.texture;
        this.matureTexture = plant[seedData.shop_id].mature.texture;
        this.seedName = ItemInfo[seedData.shop_id].name;
        this.lifeTime = Number(seedData.work_time);//成长周期单位秒
        this.growingTime = parseInt(Number(seedData.work_time)/2);
    };

    _proto.init = function()
    {
        this.young = new Laya.Sprite();

        //var Texture = Laya.loader.getRes(this.youngTexture);
        this.young.loadImage(this.youngTexture);
        this.young.pivot(this.young.width/2,this.young.height-50);
        this.young.pos(64,32);
        this.addChild(this.young);

        this.growing = new Laya.Sprite();

        //var Texture = Laya.loader.getRes(this.growingTexture);
        this.growing.loadImage(this.growingTexture);
        this.growing.pivot(this.growing.width/2,this.growing.height-50);
        this.growing.pos(64,32);
        this.growing.visible = false;
        this.addChild(this.growing);

        //this.addChild(this.growing);

        this.mature = new Laya.Sprite();

        //var Texture = Laya.loader.getRes(this.matureTexture);
        this.mature.loadImage(this.matureTexture);
        this.mature.pivot(this.mature.width/2,this.mature.height-50);
        this.mature.pos(64,32);
        this.mature.visible = false;
        this.addChild(this.mature);

        //可收获标识
        /*var xing = new Laya.Image(ItemIcon.Xingxing);
        xing.anchorX = 0.5;
        xing.pos(this.mature.width/2-5,30);
        this.mature.addChild(xing);*/

        this.progress = new Laya.ProgressBar('peiyu/progress_time.png');
        this.progress.anchorX = 0.5;
        this.progress.sizeGrid = '2,5,2,5';
        this.progress.size(60,12);
        this.progress.pos(this.getBounds().width/2,0);
        this.progress.value = 1;
        this.addChild(this.progress);

        this.allTime = this.lifeTime;

        Laya.timer.loop(1000,this,this.mytimer);

    };



    _proto.mytimer = function()
    {
        this.lifeTime -= 1;
        //console.log(this.lifeTime);
        this.progress.value = this.lifeTime/this.allTime;
        switch (this.lifeTime) {
            case this.growingTime:
                this.setGrowing();
                break;
            case 0:
                this.setMature();

                break;
        }
    };

    _proto.setGrowing = function()
    {
        console.log('成长');
        if(this.growing && this.young){
            this.young.removeSelf();
            //this.addChild(this.growing);
            this.growing.visible = true;
        }
    };

    _proto.setMature = function()
    {
        var res = false;

        if(!this.isMature){
            if(this.growing && this.mature){
                console.log('成熟');
                this.progress.visible = false;
                this.young.removeSelf();
                this.growing.removeSelf();
                //this.addChild(this.mature);
                this.mature.visible = true;
                this.isMature = true;
                Laya.timer.clear(this,this.mytimer);
                if(this.parent.parent.dialog)this.parent.parent.dialog.close();

                if(ZhiYinManager.step1 == 4 && ZhiYinManager.step2 == 1)
                {
                    ZhiYinManager.instance().setGuideStep(4,2);
                }
            }
        }else{
            console.log('该作物已成熟');
        }
        return res;

    };

    //用于初始化已种植土地
    _proto.setMatureLocal = function()
    {
        this.young.removeSelf();
        this.growing.removeSelf();
        //this.addChild(this.mature);
        this.mature.visible = true;
        this.isMature = true;
        Laya.timer.clear(this,this.mytimer);
        this.lifeTime = 0;
        this.progress.visible = false;
    };
})();