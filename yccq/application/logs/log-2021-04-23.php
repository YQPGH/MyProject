<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-04-23 09:17:59 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'p.id,p.pid,p.add_time log_time,c.shop1 shopid,c.shandian,c.money,c.shop1_total s' at line 1 - Invalid query: SELECT c.type2,p.ticket_id,,p.id,p.pid,p.add_time log_time,c.shop1 shopid,c.shandian,c.money,c.shop1_total shop_num FROM zy_prize_record p,zy_prize c
                                  WHERE p.`pid` = c.`id` AND c.type1='trees' AND p.uid='abcc'
                                  ORDER BY p.id DESC LIMIT 30
