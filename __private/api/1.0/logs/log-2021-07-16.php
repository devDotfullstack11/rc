<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-07-16 22:14:10 --> Query error: Unknown column 'is_favourite' in 'field list' - Invalid query: SELECT `g`.`id`, `g`.`title`, `g`.`description`, `g`.`image`, `is_favourite`
FROM `rc_groups` `g`
WHERE `is_favourite` = 1
AND `user_id` = '1'
AND `status` = 1
AND `user_id` = '1'
GROUP BY `g`.`id`
ERROR - 2021-07-16 22:14:39 --> Query error: Unknown column 'is_favourite' in 'field list' - Invalid query: SELECT `g`.`id`, `g`.`title`, `g`.`description`, `g`.`image`, `is_favourite`
FROM `rc_groups` `g`
WHERE `user_id` = '1'
AND `status` = 1
GROUP BY `g`.`id`
ERROR - 2021-07-16 23:24:20 --> 404 Page Not Found: Treedirve/manage
ERROR - 2021-07-16 23:25:01 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ']' /var/www/html/ms/rc/rc_backend/__private/api/1.0/controllers/Treedrive.php 367
ERROR - 2021-07-16 23:25:28 --> Severity: Parsing Error --> syntax error, unexpected ',', expecting ']' /var/www/html/ms/rc/rc_backend/__private/api/1.0/controllers/Treedrive.php 372
