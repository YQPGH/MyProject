(function(){
    function FragmentConfirm(type){
        FragmentConfirm.__super.call(this);
        if(type == 'ask'){
            this.ask_tips.visible = true;
        }else if(type == 'giving'){
            this.giving_tips.visible = true;
        }
    }
    Laya.class(FragmentConfirm,'FragmentConfirm',FragmentConfirmUI);
    var proto = FragmentConfirm.prototype;
})();