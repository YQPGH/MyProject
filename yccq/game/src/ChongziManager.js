/**
 * Created by 41496 on 2018/9/27.
 */
(function(){
    function ChongziManager(type){
        this.mapType = !type?'MyGame':type;

        this.cacheAni();
    }
    Laya.class(ChongziManager,'ChongziManager');
    var proto = ChongziManager.prototype;

    proto.cacheAni = function() {
        Laya.Animation.createFrames(['ani_chongzi/ani_big_1.png','ani_chongzi/ani_big_2.png','ani_chongzi/ani_big_3.png'],"chongzi_big");//缓存动作
        Laya.Animation.createFrames(['ani_chongzi/ani_middle_1.png','ani_chongzi/ani_middle_2.png','ani_chongzi/ani_middle_3.png'],"chongzi_middle");
        Laya.Animation.createFrames(['ani_chongzi/ani_small_1.png','ani_chongzi/ani_small_2.png','ani_chongzi/ani_small_3.png'],"chongzi_small");
    };

    proto.createChongzi = function(type,pos,data){
        this.map = Laya.stage.getChildByName(this.mapType).map;
        if(!this.chongzi){
            this.chongzi = new ChongziAni(type,data.nickname+'的虫子',this.mapType);
            this.chongzi.setLiftTime(data.start_time,data.stop_time,data.now_time);
            this.chongzi.number = data.number;
            this.map.addBuilding(this.chongzi,pos[0],pos[1]);
        }
        //ChongziManager.chongziArr.push(chongzi);
        //console.log(ChongziManager.chongziArr);
    };

    proto.removeChongzi = function(chongzi){
        //ChongziManager.chongziArr.removeObject(chongzi);
        this.chongzi = null;
    };



    /*type 'MyGame','FriendFarm'*/
    ChongziManager.instance=function(type){
        if (!ChongziManager._instance){
            ChongziManager._instance=new ChongziManager(type);
        }
        return ChongziManager._instance;
    };

    ChongziManager.destroy = function(){
        //ChongziManager._instance.chongzi.clearAllTimer();
        ChongziManager._instance = null;
    };
    ChongziManager._instance=null;
    ChongziManager.chongziArr = [];
})();