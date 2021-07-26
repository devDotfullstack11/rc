<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-07-11 23:47:52 --> 404 Page Not Found: User/award_detail
ERROR - 2021-07-11 23:48:19 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT `id`, `title`, `short_code`, `price`, `description`, `slotes_available`, `main_image`, `info_image`, `info_title`, `info_description`, `winner_1`, `winner_2`, `winner_3`
FROM `rc_tree_drives`
JOIN `rc_tree_drive_draws` `tdd` ON `tdd`.`tree_drive_id`=`rc_tree_drives`.`id`
WHERE `id` = '1'
ERROR - 2021-07-11 23:48:40 --> Query error: Column 'id' in where clause is ambiguous - Invalid query: SELECT `rc_tree_drives`.`id`, `title`, `short_code`, `price`, `description`, `slotes_available`, `main_image`, `info_image`, `info_title`, `info_description`, `winner_1`, `winner_2`, `winner_3`
FROM `rc_tree_drives`
JOIN `rc_tree_drive_draws` `tdd` ON `tdd`.`tree_drive_id`=`rc_tree_drives`.`id`
WHERE `id` = '1'
ERROR - 2021-07-11 23:49:31 --> Severity: Notice --> Undefined index: id /var/www/html/ms/rc/rc_backend/__private/api/1.0/controllers/Treedrive.php 306
ERROR - 2021-07-11 23:49:31 --> Severity: Notice --> Undefined index: id /var/www/html/ms/rc/rc_backend/__private/api/1.0/controllers/Treedrive.php 308
ERROR - 2021-07-11 23:50:15 --> Severity: Notice --> Undefined index: id /var/www/html/ms/rc/rc_backend/__private/api/1.0/controllers/Treedrive.php 306
ERROR - 2021-07-11 23:59:06 --> Query error: Unknown column 'position' in 'field list' - Invalid query: SELECT `prize_text`, `u`.`name`, `position`
FROM `rc_tree_drive_prizes` `tdp`
LEFT JOIN `rc_users` `u` ON `u`.`id`=`tdp`.`winner_user_id`
WHERE `tree_drive_id` = '2'
ORDER BY `prize_position` ASC
