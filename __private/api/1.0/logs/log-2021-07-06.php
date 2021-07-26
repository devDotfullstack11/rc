<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-07-06 22:35:31 --> Query error: Unknown table 'a' - Invalid query: SELECT `a`.*, `g`.`id`, `g`.`title` as `group_title`, `td`.`short_code` as `tree_short_code`, `g`.`image` as `group_image`, `td`.`main_image` as `tree_drive_image`, `a`.`description` as `a_description`, `a`.`title` as `a_title`, `type`
FROM `rc_announcements`
JOIN `rc_groups` `g` ON `g`.`id`=`a`.`main_id`
JOIN `rc_tree_drives` `td` ON `td`.`id`=`a`.`main_id`
ORDER BY `created_at` DESC
 LIMIT 10
ERROR - 2021-07-06 22:48:22 --> Severity: Parsing Error --> syntax error, unexpected '$this' (T_VARIABLE) /var/www/html/ms/rc/rc_backend/__private/api/1.0/controllers/Treedrive.php 149
ERROR - 2021-07-06 22:48:44 --> Query error: Unknown column 'td.created_at' in 'field list' - Invalid query: SELECT `td`.`id`, `td`.`short_code`, `td`.`title`, `td`.`main_image`, `td`.`created_at`, `g`.`title` as `group`
FROM `rc_tree_drives` `td`
JOIN `rc_groups` `g` ON `g`.`id`=`td`.`group_id`
WHERE `td`.`status` = 1
AND `is_draw_declared` =0
ORDER BY `created_at` ASC
 LIMIT 10
