<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>物流查询</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="<?= base_url('static/layui/css/layui.css') ?>" media="all">
</head>
<body>
<form action="<?= $this->baseurl.'orderNumberQuery' ?>" method="post" >
    <div class="layui-form-item">
          <label class="layui-form-label">用户id</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input w70" name='uid' value="<?= $value['uid'] ?>" placeholder="请输入用户id">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">订单编号</label>
        <div class="layui-input-block">
             <input type="text" class="layui-input w70" name='ordernum' value="<?= $value['ordernum'] ?>" placeholder="请输入订单编号">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label> <input type="submit" name="submit" class="layui-btn" value=" 查询 ">
    </div>
<form>
<div  class="layui-input-inline">
    <table class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th width="120">更新时间</th>
            <th width="100">接收地址</th>
            <th width="350">当前物流</th>
            <th width="120">投递状态</th>
        </tr>
        </thead>
        <tbody>
        <?php  foreach($data as $key=> $value):?>
        <tr>
            <td><?= $value['acceptTime'] ?></td>
            <td><?= $value['acceptAddress'] ?></td>
            <td><?= $value['remark'] ?></td>
            <td><?= $value['statusP'] ?></td>
        </tr>
        <?php  endforeach;?>
        </tbody>
    </table>
</div>

</body>
</html>