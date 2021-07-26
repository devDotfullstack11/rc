<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-06-24 19:33:16 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT `id`, `title`, `description`, `image`, `is_favourite`
FROM `rc_groups`
JOIN `rc_favourite_groups` `fg` ON `fg`.`group_id`=`rc_groups`.`id`
WHERE `status` = 1
 LIMIT 10
ERROR - 2021-06-24 19:34:07 --> Query error: Unknown column 'rc_groups.id' in 'on clause' - Invalid query: SELECT `g`.`id`, `g`.`title`, `g`.`description`, `g`.`image`, `is_favourite`
FROM `rc_groups` `g`
JOIN `rc_favourite_groups` `fg` ON `fg`.`group_id`=`rc_groups`.`id`
WHERE `status` = 1
 LIMIT 10
ERROR - 2021-06-24 19:35:06 --> Query error: Unknown column 'g.title' in 'field list' - Invalid query: SELECT `g`.`id`, `g`.`title`, `g`.`description`, `g`.`image`, `is_favourite`
FROM `rc_favourite_groups` `g`
RIGHT JOIN `rc_favourite_groups` `fg` ON `fg`.`group_id`=`g`.`id`
WHERE `status` = 1
 LIMIT 10
ERROR - 2021-06-24 19:53:45 --> Severity: Notice --> Undefined variable: data /var/www/html/ms/rc/rc_backend/__private/api/1.0/controllers/Groups.php 118
ERROR - 2021-06-24 20:08:10 --> 404 Page Not Found: My_groups/index
