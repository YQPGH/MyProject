<?php $this->load->view('admin/header_css'); ?>

<body>
<div class="layui-layout layui-layout-admin">
    <!--头部布局-->
    <div class="layui-header header">
        <div class="layui-main">
            <a class="logo" href="/">
                <img src="<?= base_url('static/admin/images/logo.png') ?>" alt="香草传奇">
            </a>
            <ul class="layui-nav nav">
               <?= topMenus()?>
            </ul>

            <ul class="layui-nav admin">
                <li class="layui-nav-item">
                    <a href="javascript:;"><?= $_SESSION['admin']['username'] ?></a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" id="edit_admin">编辑账户</a></dd>
                        <dd><a href="<?= site_url('admin/common/login_out') ?>">退出登录</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>

    <!--左侧布局-->
    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
          <?= leftMenus() ?>
        </div>
    </div>