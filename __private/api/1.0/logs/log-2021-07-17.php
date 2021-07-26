<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-07-17 18:44:45 --> Query error: Column 'user_id' in where clause is ambiguous - Invalid query: SELECT `td`.`id`, `td`.`short_code`, `td`.`title`, `td`.`main_image`, `td`.`created_at`, `g`.`title` as `group`, `td`.`price`
FROM `rc_tree_drives` `td`
JOIN `rc_groups` `g` ON `g`.`id`=`td`.`group_id`
WHERE `td`.`status` = 1
AND `is_draw_declared` =0
AND `user_id` = '1'
ORDER BY `created_at` ASC
