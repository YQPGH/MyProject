<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-04-22 10:33:02 --> Severity: Warning --> Missing argument 1 for Main::trees_share() D:\WWW\yccq\application\controllers\api\Main.php 171
ERROR - 2021-04-22 10:50:18 --> Query error: Unknown column 'p.type2' in 'field list' - Invalid query: SELECT p.type2,p.id,p.pid,p.add_time log_time,c.shop1 shopid FROM zy_prize_record p,zy_prize c
                                  WHERE p.`pid` = c.`id` AND c.type1='trees' AND p.uid='abcc'
                                  ORDER BY p.id DESC LIMIT 30
ERROR - 2021-04-22 14:55:57 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ' `updatetime` = 1619074557
WHERE `uid` = 'abcc'
AND `id` = '5'' at line 1 - Invalid query: UPDATE `zy_trees_ball` SET total = total+, `updatetime` = 1619074557
WHERE `uid` = 'abcc'
AND `id` = '5'
