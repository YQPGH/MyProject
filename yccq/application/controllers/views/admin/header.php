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
                <li class="layui-nav-item <?= $_SESSION['nav'] == 1 ? 'layui-this' : '';
                echo ' show'; ?>">
                    <a href="<?= site_url('admin/stat/index') ?>">游戏统计</a>
                </li>
                <li class="layui-nav-item <?= $_SESSION['nav'] == 2 ? 'layui-this' : '';
                echo ' show'; ?>">
                    <a href="<?= site_url('admin/user') ?>">游戏信息</a>
                </li>
                <li class="layui-nav-item <?= $_SESSION['nav'] == 5 ? 'layui-this' : '';
                echo ' show'; ?>">
                    <a href="<?= site_url('admin/news') ?>">互动及其他</a>
                </li>
                <li class="layui-nav-item <?= $_SESSION['nav'] == 3 ? 'layui-this' : '';
                echo ' show' ?>">
                    <a href="<?= site_url('admin/shop') ?>">游戏设置</a>
                </li>
                <li class="layui-nav-item <?= $_SESSION['nav'] == 4 ? 'layui-this' : '';
                echo ' show' ?>">
                    <a href="<?= site_url('admin/admin') ?>">系统设置</a>
                </li>
                <li class="layui-nav-item <?= $_SESSION['nav'] == 6 ? 'layui-this' : '';
                echo ' show' ?>">
                    <a href="<?= site_url('admin/activity/mid_autumn') ?>">游戏记录</a>
                </li>
                <li class="layui-nav-item <?= $_SESSION['nav'] == 7 ? 'layui-this' : '';
                echo ' show' ?>">
                    <a href="<?= site_url('admin/advert/config_list') ?>">游戏配置</a>
                </li>
                <li class="layui-nav-item <?= $_SESSION['nav'] == 8 ? 'layui-this' : '';
                echo ' show' ?>">
                    <a href="<?= site_url('admin/fragment/name_list') ?>">奖品名单</a>
                </li>
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
            <?php if ($_SESSION['nav'] == 1): ?>
                <ul class="layui-nav layui-nav-tree site-demo-nav">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">游戏统计</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('stat', 'index') ?><?= permission('SYS_Stat','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/stat/index') ?>">统计概况</a>
                            </dd>
                            <dd class="<?= side_show('stat', 'day') ?><?= permission('SYS_Stat_day','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/stat/day') ?>">每日统计</a>
                            </dd>
                        </dl>
                    </li>
                </ul>
            <?php endif; ?>

            <?php if ($_SESSION['nav'] == 2): ?>
                <ul class="layui-nav layui-nav-tree site-demo-nav">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="layui-this" href="javascript:;">玩家管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('user','index','status=0')?><?= permission('SYS_User','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/user/index?status=0') ?>">玩家信息</a>
                            </dd>
                            <dd class="<?= side_show('user','top') ?><?= permission('SYS_User_Top','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/user/top') ?>">玩家排行管理</a>
                            </dd>
                            <dd class="<?= side_show('user','index','status=2') ?><?= permission('SYS_User_Warning','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/user/index?status=2')?>">玩家黑名单管理</a>
                            </dd>
                            <dd class="<?= side_show('unusual') ?><?= permission('SYS_Unusual_Record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/unusual/index')?>">游戏异常数据监控</a>
                            </dd>

                                    <dd class="<?= side_show('rank') ?><?= permission('SYS_Ranking_zy','read')?' show':' layui-hide'; ?>">
                                        <a href="<?= site_url('admin/rank')?>">周排行管理</a>
                                    </dd>
                            </li>
                        </dl>
                    </li>

                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="layui-this" href="javascript:;">交易记录</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('log_shop','money') ?><?= permission('SYS_Ranking_money','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/log_shop/money')?>">玩家乐币消耗排行</a>
                            </dd>
                            <dd class="<?= side_show('log_shop','ledou') ?><?= permission('SYS_Ranking_ledou','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/log_shop/ledou')?>">玩家乐豆消耗排行</a>
                            </dd>
                            <dd class="<?= side_show('log_shop','index') ?><?= permission('SYS_Shop_log','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/log_shop/index')?>">交易流水记录</a>
                            </dd>
                        </dl>
                    </li>

                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">游戏记录</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('peiyu') ?><?= permission('SYS_Peiyu','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/peiyu') ?>">种子培育记录管理</a>
                            </dd>
                            <dd class="<?= side_show('status','process_record') ?><?= permission('SYS_Status_Process_Record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/status/process_record') ?>">玩家制烟记录管理</a>
                            </dd>

                            <dd class="<?= side_show('land','seed_record') ?><?= permission('SYS_Seed_record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/land/seed_record') ?>">种植记录</a>
                            </dd>
                            <dd class="<?= side_show('land','gather_record') ?><?= permission('SYS_Seed_gather','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/land/gather_record') ?>">采摘记录</a>
                            </dd>
                            <dd class="<?= side_show('land','land_upgrade_record') ?><?= permission('SYS_Land_upgrade','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/land/land_upgrade_record') ?>">土地升级记录</a>
                            </dd>
                            <dd class="<?= side_show('status','aging_record') ?><?= permission('SYS_Status_Aging_Record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/status/aging_record') ?>">贮藏记录</a>
                            </dd>
                            <dd class="<?= side_show('status','bake_record') ?><?= permission('SYS_Status_Bake_Record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/status/bake_record') ?>">烘烤记录</a>
                            </dd>
                            <dd class="<?= side_show('status','pinjian_record') ?><?= permission('SYS_Status_Pinjian_Record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/status/pinjian_record') ?>">品鉴记录</a>
                            </dd>
                            <dd class="<?= side_show('chengjiu') ?><?= permission('SYS_Chengjiu','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/chengjiu') ?>">成就获得记录</a>
                            </dd>
                            <dd class="<?= side_show('log_task') ?><?= permission('SYS_Task_log','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/log_task') ?>">每日任务记录</a>
                            </dd>
                            <dd class="<?= side_show('market') ?><?= permission('SYS_Market','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/market') ?>">路边小摊管理</a>
                            </dd>

                        </dl>
                    </li>

                </ul>
            <?php endif; ?>

            <?php if ($_SESSION['nav'] == 5): ?>
                <ul class="layui-nav layui-nav-tree site-demo-nav">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">好友交互管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('friend','index') ?><?= permission('SYS_Friend','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/friend/index') ?>">好友关系管理</a>
                            </dd>
                            <dd class="<?= side_show('friend','connect_list') ?><?= permission('SYS_Friend_connect','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/friend/connect_list') ?>">好友互访记录管理</a>
                            </dd>
                            <dd class="<?= side_show('friend','plant_index') ?>">
                                <a href="<?= site_url('admin/friend/plant_index') ?>">种植好友互动管理</a>
                            </dd>
                            <dd class="<?= side_show('friend','bake_index') ?>">
                                <a href="<?= site_url('admin/friend/bake_index') ?>">烘烤好友互动管理</a>
                            </dd>
                        </dl>
                    </li>
                </ul>

                <ul class="layui-nav layui-nav-tree site-demo-nav">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">报警事件中心</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('event') ?><?= permission('SYS_Event','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/event/index') ?>">报警事件记录</a>
                            </dd>

                        </dl>
                    </li>
                </ul>
                <ul class="layui-nav layui-nav-tree site-demo-nav">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">意见反馈</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('suggestion') ?><?= permission('SYS_Suggestion_Record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/suggestion/index') ?>">意见反馈</a>
                            </dd>

                        </dl>
                    </li>
                </ul>
            <?php endif; ?>

            <?php if ($_SESSION['nav'] == 3): ?>
                <ul class="layui-nav layui-nav-tree site-demo-nav">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">商店管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('shop','index') ?><?= permission('SYS_Shop','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/shop/index') ?>">真龙商店</a>
                            </dd>
                            <dd class="<?= side_show('shop','shen_mi') ?><?= permission('SYS_Shop_shen_mi','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/shop/shen_mi') ?>">神秘商店</a>
                            </dd>
                            <dd class="<?= side_show('spend_record','index') ?><?= permission('SYS_Spend_record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/spend_record/index') ?>">商店购买记录</a>
                            </dd>
                            <dd class="<?= side_show('spend_record','sale_index') ?><?= permission('SYS_Spend__sale_record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/spend_record/sale_index') ?>">商店出售记录</a>
                            </dd>
                            <dd class="<?= side_show('setting','index') ?><?= permission('SYS_Setting','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/setting/index') ?>">通用设置</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item ">
                        <a class="javascript:;" href="javascript:;">库存管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('store','index') ?><?= permission('SYS_Ru_Record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/store/index') ?>">入库记录</a>
                            </dd>
                            <dd class="<?= side_show('store','chu_list') ?><?= permission('SYS_Chu_Record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/store/chu_list') ?>">出库记录</a>
                            </dd>
                            <dd class="<?= side_show('store','store_upgrade_record') ?><?= permission('SYS_Store_Upgrade_Record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/store/store_upgrade_record') ?>">仓库升级记录</a>
                            </dd>
                        </dl>
                    </li>

                    <li class="layui-nav-item ">
                        <a class="javascript:;" href="javascript:;">麻将比大小管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('pkgame','index')?>"?>
                                <a href="<?= site_url('admin/pkgame/index') ?>">参与用户</a>
                            </dd>
                            <dd class="<?= side_show('pkgame','game_record') ?>"?>
                                <a href="<?= site_url('admin/pkgame/game_record') ?>">游戏记录</a>
                            </dd>
                            <dd class="<?= side_show('pkgame','trade_log') ?>"?>
                                <a href="<?= site_url('admin/pkgame/trade_log')?>">结算记录</a>
                            </dd>
                            <dd class="<?= side_show('pkgame','game_unusual') ?>"?>
                                <a href="<?= site_url('admin/pkgame/game_unusual')?>">游戏异常信息</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item ">
                        <a class="javascript:;" href="javascript:;">碎片管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('fragment','fragment_manage') ?><?= permission('SYS_Fragment_Manage','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/fragment/fragment_manage') ?>">库存管理</a>
                            </dd>
                            <!--                            <dd class="--><?//= side_show('fragment','scan') ?><!----><?//= permission('SYS_Fragment_ScanRecord','read')?' show':' layui-hide'; ?><!--">-->
                            <!--                                <a href="--><?//= site_url('admin/fragment/scan') ?><!--">扫码记录</a>-->
                            <!--                            </dd>-->
                            <dd class="<?= side_show('fragment','index') ?><?= permission('SYS_Fragment_IntRecord','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/fragment/index') ?>">入库记录</a>
                            </dd>
                            <dd class="<?= side_show('fragment','chu_list') ?><?= permission('SYS_Fragment_Out_Record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/fragment/chu_list') ?>">出库记录</a>
                            </dd>
                        </dl>
                    </li>

                    <li class="layui-nav-item ">
                        <a class="javascript:;" href="javascript:;">订单管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('orders_config') ?><?= permission('SYS_Orders_config','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/orders_config') ?>">订单任务发布</a>
                            </dd>
                            <dd class="<?= side_show('orders') ?><?= permission('SYS_Orders','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/orders') ?>">订单记录</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item ">
                        <a class="javascript:;" href="javascript:;">签到管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('prize','index','status=0') ?><?= permission('SYS_Prize_config','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/prize/index?status=0') ?>">奖励设置</a>
                            </dd>
                            <dd class="<?= side_show('sign') ?><?= permission('SYS_Sign','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/sign') ?>">签到记录</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item ">
                        <a class="javascript:;" href="javascript:;">抽奖管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('prize','index','status=1') ?><?= permission('SYS_Prize_config','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/prize/index?status=1') ?>">奖品设置</a>
                            </dd>
                            <dd class="<?= side_show('log_prize') ?><?= permission('SYS_Log_prize','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/log_prize') ?>">抽奖记录</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item ">
                        <a class="javascript:;" href="javascript:;">答题管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('question_config') ?><?= permission('SYS_Question_config','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/question_config') ?>">题目管理</a>
                            </dd>
                            <dd class="<?= side_show('question') ?><?= permission('SYS_Question','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/question') ?>">问答记录</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item ">
                        <a class="javascript:;" href="javascript:;">小游戏管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('hunt_prize_config') ?><?= permission('SYS_Hunt_config','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/hunt_prize_config') ?>">挖宝奖品配置</a>
                            </dd>
                            <dd class="<?= side_show('hunt_record') ?><?= permission('SYS_Hunt_record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/hunt_record') ?>">挖宝记录</a>
                            </dd>
                            <dd class="<?= side_show('xxl_config') ?><?= permission('SYS_Xxl_config','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/xxl_config') ?>">消消乐奖品配置</a>
                            </dd>
                            <dd class="<?= side_show('xxl_record') ?><?= permission('SYS_Xxl_record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/xxl_record') ?>">消消乐记录</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item ">
                        <a class="javascript:;" href="javascript:;">活动管理</a>
                        <dl class="layui-nav-child">

                            <dd class="<?= side_show('activity') ?><?= permission('SYS_Activity','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/activity') ?>">9号打折活动管理</a>
                            </dd>
                            <dd class="<?= side_show('other_activity') ?><?= permission('SYS_Other_Activity','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/other_activity') ?>">乐豆兑换活动管理</a>
                            </dd>
                        </dl>
                    </li>

                </ul>
            <?php endif; ?>

            <?php if ($_SESSION['nav'] == 4): ?>
                <ul class="layui-nav layui-nav-tree site-demo-nav">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">系统管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('admin') ?><?= permission('SYS_Account','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/admin') ?>">账号管理</a>
                            </dd>
                            <dd class="<?= side_show('admin_group') ?><?= permission('SYS_Role','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/admin_group') ?>">角色管理</a>
                            </dd>
                            <dd class="<?= side_show('admin_priv');?><?= permission('SYS_Priv','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/admin_priv') ?>">权限管理</a>
                            </dd>
                            <dd class="<?= side_show('logadmin') ?><?= permission('SYS_Log','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/logadmin') ?>">后台操作日志</a>
                            </dd>
                        </dl>
                    </li>
                </ul>
            <?php endif; ?>

            <?php if ($_SESSION['nav'] == 6): ?>
                <ul class="layui-nav layui-nav-tree site-demo-nav">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">金叶1+1-快乐双11</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('leaf','mid_autumn') ?><?= permission('SYS_Leaf_Nov11_Prize','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/leaf/nov11_prize') ?>">奖品统计</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">月圆情99-与你共婵娟</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('activity','mid_autumn') ?><?= permission('SYS_Midautumn','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/activity/mid_autumn') ?>">奖品统计</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">乘风破浪来见你</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('activity','qixi') ?><?= permission('SYS_Qixi','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/activity/qixi') ?>">奖品统计</a>
                            </dd>
<!--                            <dd class="--><?//= side_show('activity','mayday_name_list') ?><!----><?//= permission('SYS_Leaf_Name_List','read')?' show':' layui-hide'; ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/activity/mayday_name_list') ?><!--">奖品记录</a>-->
<!--                            </dd>-->
                        </dl>
                    </li>
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">劳动光荣 勤劳兴“叶”</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('leaf','mayday_prize') ?><?= permission('SYS_Leaf_Mayday_Prize','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/leaf/mayday_prize') ?>">奖品统计</a>
                            </dd>
                            <dd class="<?= side_show('leaf','mayday_name_list') ?><?= permission('SYS_Leaf_Name_List','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/leaf/mayday_name_list') ?>">奖品记录</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">年年有金叶，好礼过大年</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('leaf','leaf_prize') ?><?= permission('SYS_Leaf_Name_List','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/leaf/leaf_prize') ?>">奖品统计</a>
                            </dd>
                            <dd class="<?= side_show('leaf','name_list') ?><?= permission('SYS_Leaf_Name_List','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/leaf/name_list') ?>">奖品记录</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">召集制烟师</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('laxin','index') ?><?= permission('SYS_Laxin','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/laxin/index') ?>">拉新记录</a>
                            </dd>
                            <dd class="<?= side_show('laxin','name_list') ?><?= permission('SYS_Laxin_Name_List','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/laxin/name_list') ?>">拉新奖品记录</a>
                            </dd>
                            <dd class="<?= side_show('laxin','invite_list') ?><?= permission('SYS_Laxin','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/laxin/invite_list') ?>">拉新邀请记录</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">游戏记录</a>
                        <dl class="layui-nav-child">
<!--                            <dd class="--><?//= side_show('fragment','scan') ?><!----><?//= permission('SYS_Fragment_ScanRecord','read')?' show':' layui-hide'; ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/fragment/scan') ?><!--">扫码获取原料记录</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('Fragment_scan') ?><!----><?//= permission('SYS_Fragment_IntRecord','read')?' show':' layui-hide'; ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/Fragment_scan') ?><!--">集碎片记录</a>-->
<!--                            </dd>-->
                            <dd class="<?= side_show('task') ?><?= permission('SYS_Task_record','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/task/index') ?>">任务记录</a>
                            </dd>

<!--                            <dd class="--><?//= side_show('order_rank') ?><!----><?//= permission('SYS_Order_Rank','read')?' show':' layui-hide'; ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/order_rank')?><!--">订单排行奖励记录</a>-->
<!--                            </dd>-->
                            <dd class="<?= side_show('building') ?><?= permission('SYS_Building','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/building/index') ?>">建筑升级</a>
                            </dd>
                            <dd class="<?= side_show('headframe')?>">
                                <a href="<?= site_url('admin/headframe/index') ?>">头像框记录</a>
                            </dd>

<!--                            <dd class="--><?//= side_show('advert') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/advert') ?><!--">广告记录</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('holiday_activities','boss_list') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/boss_list') ?><!--">挑战BOSS记录</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('holiday_activities','mid_autumn_list') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/mid_autumn_list') ?><!--">中秋活动记录</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('holiday_activities','national_list') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/national_list') ?><!--">国庆活动记录</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('holiday_activities','christmas_list') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/christmas_list') ?><!--">圣诞活动记录</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('holiday_activities','newyear_list') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/newyear_list') ?><!--">元旦活动记录</a>-->
<!--                            </dd>-->
                        </dl>
                    </li>

                </ul>

            <?php endif; ?>

            <?php if ($_SESSION['nav'] == 7): ?>
                <ul class="layui-nav layui-nav-tree site-demo-nav">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">广告配置管理</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('advert','config_list') ?><?= permission('SYS_advert','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/advert/config_list') ?>">广告/公告发布</a>
                            </dd>
                        </dl>
                    </li>
                </ul>
                <ul class="layui-nav layui-nav-tree site-demo-nav">
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">配置管理-三期</a>
                        <dl class="layui-nav-child">
<!--                            <dd class="--><?//= side_show('holiday_activities','fragment_config') ?><!----><?//= permission('SYS_Fragment_Manage','read')?' show':' layui-hide'; ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/fragment_config') ?><!--">扫码原料配置</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('holiday_activities','week_task_config') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/week_task_config') ?><!--">任务升级配置</a>-->
<!--                            </dd>-->
                            <dd class="<?= side_show('laxin','laxin_config') ?><?= permission('SYS_laxin','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/laxin/laxin_config') ?>">拉新引流配置</a>
                            </dd>
                            <dd class="<?= side_show('building','config_list') ?><?= permission('SYS_Building','read')?' show':' layui-hide'; ?>">
                                <a href="<?= site_url('admin/building/config_list') ?>">建筑升级配置</a>
                            </dd>

<!--                            <dd class="--><?//= side_show('holiday_activities','boss_config') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/boss_config') ?><!--">挑战BOSS配置</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('holiday_activities','mid_autumn_config') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/mid_autumn_config') ?><!--">中秋活动配置</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('holiday_activities','national_config') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/national_config') ?><!--">国庆活动配置</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('holiday_activities','christmas_config') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/christmas_config') ?><!--">圣诞活动配置</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('holiday_activities','newyear_config') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/newyear_config') ?><!--">元旦活动配置</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('holiday_activities','spring_config') ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/holiday_activities/spring_config') ?><!--">春节活动配置</a>-->
<!--                            </dd>-->
                        </dl>
                    </li>


                </ul>
            <?php endif; ?>
            <?php if ($_SESSION['nav'] == 8): ?>
                <ul class="layui-nav layui-nav-tree site-demo-nav">
<!--                    <li class="layui-nav-item layui-nav-itemed">-->
<!--                        <a class="javascript:;" href="javascript:;">活动奖品管理</a>-->
<!--                        <dl class="layui-nav-child">-->
<!--                            <dd class="--><?//= side_show('fragment','name_list') ?><!----><?//= permission('SYS_Fargment_Name_List','read')?' show':' layui-hide'; ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/fragment/name_list') ?><!--">集碎片赢京东好礼</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('scrape','name_list')?><!----><?//= permission('SYS_Scrape_Name_list','read')?' show':' layui-hide'; ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/scrape/name_list') ?><!--">您有一份新年礼物待查收</a>-->
<!--                            </dd>-->
<!--                            <dd class="--><?//= side_show('rank','name_list') ?><!----><?//= permission('SYS_Ranking_Name_List','read')?' show':' layui-hide'; ?><!--">-->
<!--                                <a href="--><?//= site_url('admin/rank/name_list') ?><!--">种植能手大比拼</a>-->
<!--                            </dd>-->
<!---->
<!--                        </dl>-->
<!--                    </li>-->
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">品吸机会</a>
                        <dl class="layui-nav-child">
                            <dd class="<?= side_show('wldetail','theme_list_1') ?><?= permission('SYS_Theme_List_1','read')?' show':' layui-hide'; ?>">
                                <a style=""   href="<?= site_url('admin/wldetail/theme_list_1') ?>">“烟草传奇”游戏公测方案</a>
                            </dd>
                            <dd class="<?= side_show('wldetail','theme_list_2') ?><?= permission('SYS_Theme_List_2','read')?' show':' layui-hide'; ?>">
                                <a style="height: 60px; line-height: 22px;" href="<?= site_url('admin/wldetail/theme_list_2') ?>">《烟草传奇》主题游戏<br>活动方案</a>
                            </dd>
                            <dd class="<?= side_show('wldetail','theme_list_3');?><?= permission('SYS_Theme_List_3','read')?' show':' layui-hide'; ?>">
                                <a style="height: 60px; line-height: 22px;" href="<?= site_url('admin/wldetail/theme_list_3') ?>">《烟草传奇》游戏公测<br>活动方案</a>
                            </dd>
                            <dd class="<?= side_show('wldetail','theme_list_4');?><?= permission('SYS_Theme_List_4','read')?' show':' layui-hide'; ?>">
                                <a style="height: 60px; line-height: 22px;" href="<?= site_url('admin/wldetail/theme_list_4') ?>">《香草传奇》游戏<br>新春主题营销活动</a>
                            </dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="javascript:;" href="javascript:;">2019-2020年物料明细</a>
                        <dl class="layui-nav-child">
                            <?= menus() ?>
                        </dl>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>