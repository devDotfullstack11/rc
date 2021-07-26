<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-07-18 17:36:35 --> Query error: Column 'created_at' in field list is ambiguous - Invalid query: SELECT `g`.`id`, `g`.`title`, `g`.`description`, `g`.`image`, SUM(trees_planted) as trees_planted, `tp`.`group_id`, `created_at`
FROM `rc_tree_planted` `tp`
JOIN `rc_groups` `g` ON `g`.`id`=`tp`.`group_id`
GROUP BY `tp`.`group_id`
ERROR - 2021-07-18 17:37:51 --> Query error: Expression #7 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'reforestration_canada.tp.created_at' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT `g`.`id`, `g`.`title`, `g`.`description`, `g`.`image`, SUM(trees_planted) as trees_planted, `tp`.`group_id`, `tp`.`created_at`
FROM `rc_tree_planted` `tp`
JOIN `rc_groups` `g` ON `g`.`id`=`tp`.`group_id`
GROUP BY `tp`.`group_id`
