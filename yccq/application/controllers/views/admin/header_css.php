<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>香草传奇-后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="<?= base_url('static/layui/css/layui.css?t=0510') ?>" media="all">
    <link rel="stylesheet" href="<?= base_url('static/admin/css/global.css?t=070') ?>" media="all">
    <script src="<?= base_url('static/admin/js/overtime.js') ?>"></script>
    <script>
        var base_url = '<?=site_url()?>';
        var admin_id = <?=$_SESSION['admin']['id']?>;
    </script>
</head>
