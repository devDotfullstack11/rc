<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-07-10 13:32:54 --> Query error: Column 'created_at' in where clause is ambiguous - Invalid query: SELECT `r`.*, `td`.`short_code` as `tree_short_code`, `td`.`main_image` as `tree_drive_image`
FROM `rc_rewards` `r`
JOIN `rc_tree_drives` `td` ON `td`.`id`=`r`.`tree_drive_id`
WHERE `created_at` >= '2021-07-05 00:00:00'
AND `created_at` <= '2021-07-11 23:59:59'
ERROR - 2021-07-10 13:39:21 --> Severity: Parsing Error --> syntax error, unexpected '' (T_ENCAPSED_AND_WHITESPACE), expecting identifier (T_STRING) or variable (T_VARIABLE) or number (T_NUM_STRING) /var/www/html/ms/rc/rc_backend/__private/api/1.0/controllers/User.php 428
