<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-01-11 16:32:33 --> Severity: Warning --> Invalid argument supplied for foreach() D:\WWW\yccq\application\helpers\common_helper.php 1075
ERROR - 2022-01-11 17:17:39 --> Query error: Unknown column 'a.pid' in 'where clause' - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `zy_leaf_peiyang_record` `a`
WHERE 1 = 1  and `a`.`pid` = `p`.`id` and UNIX_TIMESTAMP(a.add_time) > 1609430400
ERROR - 2022-01-11 17:23:53 --> Query error: Unknown table 'a' - Invalid query: SELECT `a`.*
FROM `zy_leaf`
WHERE 1 = 1 
ORDER BY `a`.`id` DESC
 LIMIT 20
