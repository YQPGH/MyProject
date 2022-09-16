<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-07-06 10:47:19 --> Query error: Table 'zy_yccq1.zy_chat_config' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `zy_chat_config`
WHERE 1 = 1 
ERROR - 2021-07-06 10:56:27 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'and `a`.`uid` = `b`.`uid`
ORDER BY `a`.`id` DESC
 LIMIT 20' at line 3 - Invalid query: SELECT `a`.`update_time`, `b`.`openid`, `b`.`nickname`
FROM `zy_friend` `a`, `zy_user` `b`
WHERE  and `a`.`uid` = `b`.`uid`
ORDER BY `a`.`id` DESC
 LIMIT 20
ERROR - 2021-07-06 11:08:56 --> Severity: Error --> Call to a member function row_array() on string D:\WWW\yccq\application\controllers\admin\Chat.php 113
ERROR - 2021-07-06 11:11:04 --> Severity: Error --> Call to a member function row_array() on string D:\WWW\yccq\application\controllers\admin\Chat.php 113
ERROR - 2021-07-06 15:20:43 --> Severity: Error --> Call to a member function count() on null D:\WWW\yccq\application\controllers\admin\Coolrun.php 49
ERROR - 2021-07-06 16:11:08 --> Query error: Unknown column 'a.title' in 'where clause' - Invalid query: SELECT `a`.*, `b`.`openid`, `b`.`nickname`
FROM `zy_midautumn_prize_record` `a`, `zy_user` `b`
WHERE `a`.`title` = 2 and `a`.`uid` = `b`.`uid`
ORDER BY `a`.`id` DESC
 LIMIT 20
ERROR - 2021-07-06 16:49:02 --> Query error: Table 'zy_yccq1.zy_qixi_player' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `zy_qixi_player` `a`
WHERE 1 = 1 
ERROR - 2021-07-06 17:09:24 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'and `a`.`title` = 9' at line 3 - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `zy_prize_record` `a`
WHERE   and `a`.`title` = 9 
