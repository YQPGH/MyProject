(function(){
    function game_desc(){
         game_desc.__super.call(this);
         this.Mydesc();
    }

    Laya.class(game_desc,'game_desc',game_descUI);
    var proto = game_desc.prototype;

     proto.Mydesc = function(){

        this.descLabel_1.text = "选取好适当的角度后将钩子释放，钩子抓到物品即获得该物品对应的积分，通关后会获得对应奖励" 
      
        this.descLabel_2.text = "在60秒内勾取到目标分数的金子就能进入下一关";
    }

})();


