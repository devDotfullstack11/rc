<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-07-24 12:54:54 --> Query error: Unknown column 'g.image' in 'field list' - Invalid query: SELECT `g`.`id`, `g`.`title`, `g`.`description`, `g`.`image`, SUM(trees_planted) as trees_planted, `tp`.`group_id`
FROM `rc_tree_planted` `tp`
JOIN `rc_groups` `g` ON `g`.`id`=`tp`.`group_id`
GROUP BY `tp`.`group_id`
ERROR - 2021-07-24 12:57:15 --> Query error: Unknown column 'g.image' in 'field list' - Invalid query: SELECT `g`.`id`, `g`.`title`, `g`.`description`, `g`.`image`, SUM(trees_planted) as trees_planted, `tp`.`group_id`
FROM `rc_tree_planted` `tp`
JOIN `rc_groups` `g` ON `g`.`id`=`tp`.`group_id`
GROUP BY `tp`.`group_id`
ERROR - 2021-07-24 13:01:04 --> Query error: Column 'status' in where clause is ambiguous - Invalid query: SELECT `td`.`id`, `td`.`short_code`, `td`.`title`, `td`.`main_image`, `td`.`created_at`, `g`.`title` as `group`, `td`.`price`
FROM `rc_tree_drives` `td`
JOIN `rc_groups` `g` ON `g`.`id`=`td`.`group_id`
WHERE `group_id` = '3'
AND `status` = 1
AND `is_draw_declared` =0
ERROR - 2021-07-24 13:16:07 --> 404 Page Not Found: Tree_drive/add_review
ERROR - 2021-07-24 13:16:36 --> 404 Page Not Found: Tree_drive/add_review
ERROR - 2021-07-24 13:17:23 --> 404 Page Not Found: Tree_drive/add_review
ERROR - 2021-07-24 13:18:46 --> Query error: Unknown column 'user_id' in 'where clause' - Invalid query: SELECT *
FROM `rc_tree_drive_draws`
WHERE `user_id` = '1'
AND `tree_drive_id` = '1'
