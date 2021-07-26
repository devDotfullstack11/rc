<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-07-07 22:20:57 --> Severity: Notice --> Undefined variable: extra_flag /var/www/html/ms/rc/rc_backend/__private/api/1.0/controllers/User.php 364
ERROR - 2021-07-07 23:26:21 --> Query error: Table 'reforestration_canada.groups' doesn't exist - Invalid query: SELECT `g`.`id`, `g`.`title`, `g`.`description`, `g`.`image`, SUM(trees_planted) as trees_planted
FROM `rc_tree_planted` `tp`
JOIN `groups` `g` ON `g`.`id`=`tp`.`group_id`
GROUP BY `tp`.`group_id`
