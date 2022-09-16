/**
 * Created by 41496 on 2017/12/20.
 */
(function(){
    function SelectRole()
    {
        SelectRole.__super.call(this);
        this.selected_role = null;
        this.roleData = [
            {'role_id':1,'name':'烟农','role':'userinfo/role_1.png'},
            {'role_id':2,'name':'烟女','role':'userinfo/role_2.png'},
            {'role_id':3,'name':'女孩','role':'userinfo/role_3.png'},
            {'role_id':4,'name':'商人','role':'userinfo/role_4.png'}
        ];
        this.RoleList = [this.role1,this.role2,this.role3,this.role4];
        for(var i = 0; i < this.RoleList.length; i++)
        {
            this.RoleList[i].role_id = i+1;
            this.RoleList[i].getChildByName('role').skin = this.roleData[i].role;
            //this.RoleList[i].getChildByName('name').changeText(this.roleData[i].name);
            this.RoleList[i].on(Laya.Event.CLICK,this,this.onRoleClick,[i]);
        }
        this.list.hScrollBar.hide = true;
        this.left_btn.clickHandler = new Laya.Handler(this,function(){this.list.scrollTo(0,0);});
        this.left_btn.gray = true;
        this.right_btn.clickHandler = new Laya.Handler(this,function(){this.list.scrollTo(this.list.hScrollBar.max,0);});

        this.list.hScrollBar.on('change',this,this.onScrollBarChange);

        this.ok_btn.clickHandler = new Laya.Handler(this,this.onOkBtnClick);

    }
    Laya.class(SelectRole,"SelectRole",SelectRoleUI);
    var proto = SelectRole.prototype;

    proto.onScrollBarChange = function()
    {
        console.log(this.list.hScrollBar.value);
        if(this.list.hScrollBar.value == this.list.hScrollBar.min){
            this.left_btn.gray = true;
        }else{
            this.left_btn.gray = false;
        }
        if(this.list.hScrollBar.value == this.list.hScrollBar.max){
            this.right_btn.gray = true;
        }else{
            this.right_btn.gray = false;
        }
    };

    proto.onRoleClick = function(index)
    {
        if(this.selected_role){
            this.selected_role.getChildByName('selected').visible = false;
        }
        var role = this.RoleList[index];
        role.getChildByName('selected').visible = true;
        this.selected_role = role;
    };

    proto.onOkBtnClick = function()
    {
        if(this.selected_role !== null){
            this.setRole(this.selected_role.role_id);
        }else {
            var dialog = new CommomConfirm('请选择一个角色');
            dialog.popup();
        }
    };

    proto.setRole = function(role_id)
    {
        var self = this;
        Utils.post('user/update_role',{uid:localStorage.GUID,role:role_id},function(res){
            if(res.code == 0){
                self.close();
                self.showTips();
                ZhiYinManager.instance().setGuideStep(1,0,true);

            }else{
                var dialog = new CommomConfirm(res.msg);
                dialog.popup();
            }
        },onHttpErr);
    };

    proto.showTips = function()
    {
        var dialog = new RoleTipsUI();
        dialog.closeHandler = new Laya.Handler(this,function(){
            ZhiYinManager.instance().showZhiYin();
        });
        dialog.popup();

    }
})();