<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-10-03 16:20:16 --> Query error: In aggregated query without GROUP BY, expression #1 of SELECT list contains nonaggregated column 'dawo.group.group_id'; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT `group`.*, SUM(cattles.id) as total_cattles
FROM `group_data` `group`
LEFT JOIN `cattles` `cattles` ON `cattles`.`parent_group` = `group`.`group_id`
WHERE `user_id` = '6'
ORDER BY `sort_order` ASC
ERROR - 2018-10-03 16:21:34 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dawo.group.group_id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT `group`.*, SUM(cattles.id) as total_cattles
FROM `group_data` `group`
LEFT JOIN `cattles` `cattles` ON `cattles`.`parent_group` = `group`.`group_id`
WHERE `user_id` = '6'
GROUP BY `cattles`.`parent_group`
ORDER BY `sort_order` ASC
ERROR - 2018-10-03 16:21:47 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dawo.group.group_id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT `group`.*, SUM(cattles.id) as total_cattles, `parent_group`
FROM `group_data` `group`
LEFT JOIN `cattles` `cattles` ON `cattles`.`parent_group` = `group`.`group_id`
WHERE `user_id` = '6'
GROUP BY `cattles`.`parent_group`
ORDER BY `sort_order` ASC
ERROR - 2018-10-03 16:24:04 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dawo.group.group_id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT `group`.*, SUM(cattles.id) as total_cattles, `cattles`.`parent_group`
FROM `group_data` `group`
LEFT JOIN `cattles` `cattles` ON `cattles`.`parent_group` = `group`.`group_id`
WHERE `user_id` = '6'
GROUP BY `cattles`.`parent_group`
ORDER BY `sort_order` ASC
ERROR - 2018-10-03 16:25:33 --> Query error: Unknown column 'cattles.parent_group' in 'group statement' - Invalid query: SELECT `g`.*, SUM(c.id) as total_cattles, `c`.`parent_group`
FROM `group_data` `g`
LEFT JOIN `cattles` `c` ON `c`.`parent_group` = `g`.`group_id`
WHERE `user_id` = '6'
GROUP BY `cattles`.`parent_group`
ORDER BY `sort_order` ASC
ERROR - 2018-10-03 16:25:45 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dawo.g.group_id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT `g`.*, SUM(c.id) as total_cattles, `c`.`parent_group`
FROM `group_data` `g`
LEFT JOIN `cattles` `c` ON `c`.`parent_group` = `g`.`group_id`
WHERE `user_id` = '6'
GROUP BY `c`.`parent_group`
ORDER BY `sort_order` ASC
ERROR - 2018-10-03 16:39:21 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dawo.g.group_id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT `g`.*, SUM(c.id) as total_cattles, `c`.`parent_group`
FROM `group_data` `g`
LEFT JOIN `cattles` `c` ON `c`.`parent_group` = `g`.`group_id`
WHERE `user_id` = '6'
GROUP BY `c`.`parent_group`
ORDER BY `sort_order` ASC
ERROR - 2018-10-03 17:11:30 --> Severity: Compile Error --> Cannot redeclare Expense_model::update_entry() /var/www/html/pcdata/dawo/__private/api/models/Expense_model.php 112
ERROR - 2018-10-03 17:11:38 --> Severity: Compile Error --> Cannot redeclare Expense_model::update_entry() /var/www/html/pcdata/dawo/__private/api/models/Expense_model.php 112
ERROR - 2018-10-03 17:12:03 --> Severity: Notice --> Undefined variable: id /var/www/html/pcdata/dawo/__private/api/models/Expense_model.php 108
ERROR - 2018-10-03 17:17:34 --> Severity: Warning --> Missing argument 2 for Expense_model::update_entry(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 465 and defined /var/www/html/pcdata/dawo/__private/api/models/Expense_model.php 112
ERROR - 2018-10-03 17:17:34 --> Severity: Notice --> Undefined variable: data /var/www/html/pcdata/dawo/__private/api/models/Expense_model.php 114
ERROR - 2018-10-03 17:58:40 --> Severity: Notice --> Undefined index: machines_maintenance /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 567
ERROR - 2018-10-03 17:58:40 --> Severity: Notice --> Undefined index: machines_maintenance /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 567
ERROR - 2018-10-03 17:58:40 --> Severity: Notice --> Undefined index: farm_milk_consumption /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 568
ERROR - 2018-10-03 17:58:40 --> Severity: Notice --> Undefined index: farm_milk_consumption /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 568
ERROR - 2018-10-03 17:58:40 --> Severity: Notice --> Undefined index: cattle_purchase /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 569
ERROR - 2018-10-03 17:58:40 --> Severity: Notice --> Undefined index: cattle_purchase /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 569
ERROR - 2018-10-03 17:58:40 --> Severity: Notice --> Undefined index: id /var/www/html/pcdata/dawo/__private/api/models/Income_model.php 105
ERROR - 2018-10-03 17:58:40 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '81 = ''
WHERE `id` IS NULL' at line 1 - Invalid query: UPDATE `income` SET 81 = ''
WHERE `id` IS NULL
ERROR - 2018-10-03 17:58:40 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/pcdata/dawo/__private/system/core/Exceptions.php:271) /var/www/html/pcdata/dawo/__private/system/core/Common.php 570
ERROR - 2018-10-03 17:58:45 --> Severity: Notice --> Undefined index: machines_maintenance /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 567
ERROR - 2018-10-03 17:58:45 --> Severity: Notice --> Undefined index: machines_maintenance /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 567
ERROR - 2018-10-03 17:58:45 --> Severity: Notice --> Undefined index: farm_milk_consumption /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 568
ERROR - 2018-10-03 17:58:45 --> Severity: Notice --> Undefined index: farm_milk_consumption /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 568
ERROR - 2018-10-03 17:58:45 --> Severity: Notice --> Undefined index: cattle_purchase /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 569
ERROR - 2018-10-03 17:58:45 --> Severity: Notice --> Undefined index: cattle_purchase /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 569
ERROR - 2018-10-03 17:58:45 --> Severity: Notice --> Undefined index: id /var/www/html/pcdata/dawo/__private/api/models/Income_model.php 105
ERROR - 2018-10-03 17:58:45 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '81 = ''
WHERE `id` IS NULL' at line 1 - Invalid query: UPDATE `income` SET 81 = ''
WHERE `id` IS NULL
ERROR - 2018-10-03 17:58:45 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/pcdata/dawo/__private/system/core/Exceptions.php:271) /var/www/html/pcdata/dawo/__private/system/core/Common.php 570
ERROR - 2018-10-03 18:21:27 --> Severity: Notice --> Undefined variable: date /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 826
ERROR - 2018-10-03 18:22:14 --> Severity: Notice --> Undefined variable: date /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 826
