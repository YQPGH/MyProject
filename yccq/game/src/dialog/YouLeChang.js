/**
 * Created by 41496 on 2017/10/12.
 */
(function(){
    function YouLeChangDialog()
    {
        YouLeChangDialog.__super.call(this);
        this.name = 'youlechang';
        this.Selected = null;
        this.wabao.clickHandler = new Laya.Handler(this,this.onWaBaoClick);
        this.xxl.clickHandler = new Laya.Handler(this,this.onXXLClick);
        this.dati.clickHandler = new Laya.Handler(this,this.onDaTiClick);
        this.GameIntro = [
            '    玩家根据宝物位置选取适当的角度控制矿工将钩子释放，钩子会沿玩家选取的角度直线伸长，若钩子在伸长过程中触碰物品，物品会随着钩子拉升至地面，玩家获得该物品对应的积分。游戏共6个等级关卡，关卡越高，过关所需分数越高，难度越大。每日前3次挑战免费，之后挑战需要花费2乐豆，挑战机会每日0点刷新。',
            '    随意移动两个相邻的格子，若有三个或以上相同图案的格子直线相邻即可消除，并记为1步。单局游戏内，共可以移动25步，计时2分钟，按最终得分划分奖励，得分越高，奖励越丰厚。每日免费挑战3次，每日0时更新游戏状态，超过3次的每次需要2个乐豆喔！',
            '    商行掌柜每天会在题板出五道考题，参与答题的烟农将会得到掌柜的奖励，答对的题数越多，得到的奖励越丰厚。'
        ];
        this.enty_game_btn.clickHandler = new Laya.Handler(this,this.onEntyGameClick);
        this.onWaBaoClick();
    }
    Laya.class(YouLeChangDialog,'YouLeChangDialog',YouLeChangUI);
    var proto = YouLeChangDialog.prototype;

    proto.onWaBaoClick = function()
    {
        this.Selected = this.wabao;
        this.wabao.selected = true;
        //this.wabao.scale(1,1);
        this.xxl.selected = false;
        this.dati.selected = false;
        //this.xxl.scale(0.7,0.7);
        this.intro.text = this.GameIntro[0];
        this.icon.visible = true;
        this.dati_jiangli.visible = false;
        this.icon.skin = 'icon/peifang_1_1.png';
    };

    proto.onXXLClick = function()
    {
        this.Selected = this.xxl;
        this.xxl.selected = true;
        //this.xxl.scale(1,1);
        this.wabao.selected = false;
        this.dati.selected = false;
        //this.wabao.scale(0.7,0.7);
        this.intro.text = this.GameIntro[1];
        this.icon.visible = true;
        this.dati_jiangli.visible = false;
        this.icon.skin = 'icon/zhongzi_1_1.png';
    };

    proto.onDaTiClick = function()
    {
        this.Selected = this.dati;
        this.dati.selected = true;
        this.xxl.selected = false;
        this.wabao.selected = false;
        this.intro.text = this.GameIntro[2];
        this.icon.visible = false;
        this.dati_jiangli.visible = true;
    };

    proto.onEntyGameClick = function()
    {
        if(this.Selected == this.wabao){
            window.location.href = config.WabaoURL;
        }else if(this.Selected == this.xxl){
            window.location.href = config.XiaoxiaoleURL;
        }else if(this.Selected == this.dati){
            this.close();
            var dialog = new Dati();
        }
    };
})();