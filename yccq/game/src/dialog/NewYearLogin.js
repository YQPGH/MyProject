/**
 * Created by 41496 on 2019/1/10.
 */
(function(){
    function NewYearLogin(data) {
        NewYearLogin.__super.call(this);
        this.LoginData = data;
        this.btn_list = [this.lingqu_btn_1,this.lingqu_btn_2,this.lingqu_btn_3,this.lingqu_btn_4,this.lingqu_btn_5,this.lingqu_btn_6,this.lingqu_btn_7];
        for(var i = 0; i < this.btn_list.length; i++) {
            this.btn_list[i].clickHandler = new Laya.Handler(this,this.onLingquBtnClick,[i]);
        }
        this.init();
    }
    Laya.class(NewYearLogin,'NewYearLogin',NewYearLoginUI);
    var proto = NewYearLogin.prototype;

    proto.init = function() {
        for(var i = 0; i < this.btn_list.length; i++) {
            if(i < this.LoginData.login_total) {
                this.setLingqu(this.btn_list[i]);
            }else if(i == this.LoginData.login_total) {
                this.btn_list[i].visible = true;
            }else {
                this.btn_list[i].visible = false;
            }
        }
    };

    proto.onLingquBtnClick = function(index) {
        var self = this;
        Utils.post("Loginprize/login",{uid:localStorage.GUID},function(res){
            if(res.code == '0') {
                self.setLingqu(self.btn_list[index]);
                if(res.data.money) getMoney(res.data.money);
                if(res.data.shandian) getShandian(res.data.shandian);
                if(res.data.shopid) getItem(res.data.shopid, res.data.shop_num);
            }else {
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);

    };

    proto.setLingqu = function(btn) {
        btn.skin = '2019newyearlogin/denglu_yilingqu.png';
        btn.disabled = true;
    };
})();