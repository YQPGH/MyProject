<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-06-24 16:31:27 --> Severity: Parsing Error --> syntax error, unexpected ')' D:\WWW\yccq\application\controllers\admin\Activity_count.php 717
ERROR - 2021-06-24 16:34:50 --> Query error: Unknown column 'datetime' in 'field list' - Invalid query: select id,DATE_FORMAT(datetime, "%H:%i:%s") as `time` from zy_national_day 
ERROR - 2021-06-24 16:37:59 --> Severity: Parsing Error --> syntax error, unexpected '$day2' (T_VARIABLE) D:\WWW\yccq\application\controllers\admin\Activity_count.php 714
ERROR - 2021-06-24 16:39:57 --> Query error: Unknown column 'updatetime' in 'field list' - Invalid query: select id,add_time,updatetime,DATE_FORMAT(add_time, "%H:%i:%s") as `time`,DATE_FORMAT(update_time, "%H:%i:%s") as `uptime` from zy_national_day 
