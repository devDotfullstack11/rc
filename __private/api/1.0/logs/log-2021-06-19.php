<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-06-19 16:30:50 --> Severity: Error --> Call to undefined function pr() /var/www/html/ms/rc/rc_backend/__private/api/1.0/controllers/Groups.php 56
ERROR - 2021-06-19 16:35:17 --> Severity: Error --> Call to undefined function pr() /var/www/html/ms/rc/rc_backend/__private/api/1.0/controllers/Groups.php 56
ERROR - 2021-06-19 17:01:40 --> Query error: Unknown column 'image' in 'field list' - Invalid query: SELECT `id`, `title`, `description`, `image`
FROM `rc_tree_drives`
WHERE `group_id` = '1'
 LIMIT 10
ERROR - 2021-06-19 17:34:45 --> Query error: Unknown column 'id,short_code,title' in 'where clause' - Invalid query: SELECT *
FROM `rc_tree_drives`
WHERE `id,short_code,title` IS NULL
AND `group_id` = '1'
 LIMIT 10
ERROR - 2021-06-19 17:38:37 --> Severity: Notice --> Undefined index: image /var/www/html/ms/rc/rc_backend/__private/api/1.0/controllers/Groups.php 70
ERROR - 2021-06-19 18:12:15 --> Query error: Unknown column 'is_draw_declared' in 'where clause' - Invalid query: SELECT `id`, `title`, `description`, `image`
FROM `rc_groups`
WHERE `status` = 1
AND `is_draw_declared` =0
 LIMIT 10
