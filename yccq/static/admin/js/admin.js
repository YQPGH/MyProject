layui.use(['layer'], function () {
    var layer = layui.layer;

    var $ = layui.jquery; //由于layer弹层依赖jQuery，所以可以直接得到

    $("#edit_admin").click(function () {
        layer.open({
            type: 2,
            title: '编辑账户：',
            area: ['500px','500px'],
            content: base_url + '/admin/admin/edit_dialog?id=' + admin_id
        });
    });

});