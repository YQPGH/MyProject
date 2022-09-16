<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-07-07 10:25:24 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '`a`.`uid` = `b`.`uid`
ORDER BY `a`.`id` DESC
 LIMIT 20' at line 3 - Invalid query: SELECT `a`.*, `b`.`nickname`
FROM `zy_trees_gatherrecord` `a`, `zy_user` `b`
WHERE `a`.`type=0AND` `a`.`uid` = `b`.`uid`
ORDER BY `a`.`id` DESC
 LIMIT 20
