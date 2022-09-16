var BackGround=(function(_super){
   function BackGround (){
       BackGround.super(this);
       
       //创建精灵
       this.bg=new Laya.Sprite();
       //加载并显示背景图
       this.bg.loadImage("hunt/background.png");
       //添加至容器中
       this.addChild(this.bg);

   }
   //注册类
   Laya.class(BackGround,"BackGround",_super);
   var _proto=BackGround.prototype;

   //返回函数
   return BackGround;
})(Laya.Sprite);


