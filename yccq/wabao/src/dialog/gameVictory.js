var gameVictory=(function(_super){

     function gameVictory(){

         gameVictory.super(this);
   
     }


      //注册类
    Laya.class(gameVictory,"gameVictory",_super);
    var _proto=gameVictory.prototype;



 return gameVictory;
})(ui.gameVictoryUI);