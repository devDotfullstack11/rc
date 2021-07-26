<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-10-11 12:29:01 --> Severity: error --> Exception: syntax error, unexpected '' (T_ENCAPSED_AND_WHITESPACE), expecting identifier (T_STRING) or variable (T_VARIABLE) or number (T_NUM_STRING) /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3320
ERROR - 2018-10-11 12:30:37 --> Severity: Notice --> Undefined property: Service::$History_model /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3360
ERROR - 2018-10-11 12:30:37 --> Severity: error --> Exception: Call to a member function get_all_by_cattle_ids() on null /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3360
ERROR - 2018-10-11 12:30:52 --> Query error: Unknown column 'income_date' in 'where clause' - Invalid query: SELECT COUNT(id) as total_cattles, `event_date`
FROM `cattle_history`
WHERE `income_date` >= '2018-10-01 00:00:00'
AND `income_date` <= '2018-10-07 23:59:59'
AND `cattle_id` IN('7', 'Maninder', 'None', 'manyder@gmail.com', '9780337441', '', 'Male', 'testabc', 'Test Address is here', '2', NULL, 'd54ce9de9df77c579775a7b6b1a4bdc0-20171204095707', '$2y$10$ZcGzoDKEAc2KzDK/fN2rDeVmIEasiuJDsL7OFdW1gokPxMmGVEC4O', '2019-01-05 09:57:07', 'Yes', '1', '0', '2018-01-05 09:57:07', '2017-12-04 09:57:07', 'Yes')
GROUP BY `event_date`
ORDER BY `event_date` DESC
ERROR - 2018-10-11 12:44:48 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dawo.cattle_history.id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `cattle_history`
WHERE `event_date` >= '2018-10-01 00:00:00'
AND `event_date` <= '2018-10-07 23:59:59'
AND `cattle_id` IN('7', 'Maninder', 'None', 'manyder@gmail.com', '9780337441', '', 'Male', 'testabc', 'Test Address is here', '2', NULL, 'd54ce9de9df77c579775a7b6b1a4bdc0-20171204095707', '$2y$10$ZcGzoDKEAc2KzDK/fN2rDeVmIEasiuJDsL7OFdW1gokPxMmGVEC4O', '2019-01-05 09:57:07', 'Yes', '1', '0', '2018-01-05 09:57:07', '2017-12-04 09:57:07', 'Yes')
GROUP BY `event_date`
ORDER BY `event_date` DESC
ERROR - 2018-10-11 12:53:47 --> Query error: Expression #3 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'dawo.cattle_history.event_type' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT COUNT(id) as total_cattles, `event_date`, `event_type`
FROM `cattle_history`
WHERE `event_date` >= '2018-10-01 00:00:00'
AND `event_date` <= '2018-10-07 23:59:59'
AND `cattle_id` IN('251', '252', '253', '254')
GROUP BY `event_date`
ORDER BY `event_date` ASC
ERROR - 2018-10-11 12:56:36 --> Severity: Notice --> Undefined variable: done_events_last_week /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3364
ERROR - 2018-10-11 13:24:44 --> Severity: error --> Exception: syntax error, unexpected ')' /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3360
ERROR - 2018-10-11 13:25:08 --> Severity: Compile Error --> Cannot redeclare History_model::get_all_by_cattle_ids() /var/www/html/pcdata/dawo/__private/api/models/History_model.php 106
ERROR - 2018-10-11 13:31:40 --> Query error: Unknown column 'eventy_type' in 'where clause' - Invalid query: SELECT COUNT(id) as total_cattles, `event_date`, `event_type`
FROM `cattle_history`
WHERE `eventy_type` IN('ai')
AND `event_date` IN('2018-10-03 00:00:00')
AND `cattle_id` IN('251', '252', '253', '254')
GROUP BY `event_date`, `event_type`
ORDER BY `event_date` ASC
ERROR - 2018-10-11 13:31:51 --> Severity: Notice --> Undefined variable: event_day /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3366
ERROR - 2018-10-11 14:44:07 --> Severity: Notice --> Undefined variable: last_week_start /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4534
ERROR - 2018-10-11 14:44:07 --> Severity: Notice --> Undefined variable: last_week_end /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4534
ERROR - 2018-10-11 14:44:07 --> Severity: Notice --> Undefined variable: user /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4554
ERROR - 2018-10-11 14:44:07 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')
GROUP BY `event_date`, `event_type`
ORDER BY `event_date` ASC' at line 6 - Invalid query: SELECT COUNT(id) as total_cattles, `event_date`, `event_type`
FROM `cattle_history`
WHERE `event_date` >= '2018-10-01 00:00:00'
AND `event_date` <= '2018-10-07 23:59:59'
AND `event_type` = 'ai'
AND `cattle_id` IN()
GROUP BY `event_date`, `event_type`
ORDER BY `event_date` ASC
ERROR - 2018-10-11 14:44:52 --> Severity: Notice --> Undefined variable: user /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4556
ERROR - 2018-10-11 14:44:52 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')
GROUP BY `event_date`, `event_type`
ORDER BY `event_date` ASC' at line 6 - Invalid query: SELECT COUNT(id) as total_cattles, `event_date`, `event_type`
FROM `cattle_history`
WHERE `event_date` >= '2018-10-01 00:00:00'
AND `event_date` <= '2018-10-07 23:59:59'
AND `event_type` = 'ai'
AND `cattle_id` IN()
GROUP BY `event_date`, `event_type`
ORDER BY `event_date` ASC
ERROR - 2018-10-11 15:19:29 --> Severity: error --> Exception: syntax error, unexpected '}' /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4533
ERROR - 2018-10-11 15:19:38 --> Severity: Notice --> Undefined variable: total /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4531
ERROR - 2018-10-11 15:19:38 --> Severity: error --> Exception: Call to undefined function generate_excel_eventwise() /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4532
ERROR - 2018-10-11 15:19:50 --> Severity: Warning --> Missing argument 6 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 3325 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4477
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> String offset cast occurred /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4530
ERROR - 2018-10-11 15:19:50 --> Severity: Warning --> Missing argument 6 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 3327 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4477
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> String offset cast occurred /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4530
ERROR - 2018-10-11 15:19:50 --> Severity: Warning --> Missing argument 6 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 3329 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4477
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> String offset cast occurred /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4530
ERROR - 2018-10-11 15:19:50 --> Severity: Warning --> Missing argument 6 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 3331 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4477
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> String offset cast occurred /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4530
ERROR - 2018-10-11 15:19:50 --> Severity: Warning --> Missing argument 6 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 3333 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4477
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> String offset cast occurred /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4530
ERROR - 2018-10-11 15:19:50 --> Severity: Warning --> Missing argument 6 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 3335 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4477
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> String offset cast occurred /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:19:50 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4530
ERROR - 2018-10-11 15:19:50 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/pcdata/dawo/__private/system/core/Exceptions.php:271) /var/www/html/pcdata/dawo/__private/system/core/Common.php 570
ERROR - 2018-10-11 15:20:07 --> Severity: Warning --> Missing argument 6 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 3325 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4477
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> String offset cast occurred /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4530
ERROR - 2018-10-11 15:20:07 --> Severity: Warning --> Missing argument 6 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 3327 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4477
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> String offset cast occurred /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4530
ERROR - 2018-10-11 15:20:07 --> Severity: Warning --> Missing argument 6 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 3329 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4477
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> String offset cast occurred /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4530
ERROR - 2018-10-11 15:20:07 --> Severity: Warning --> Missing argument 6 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 3331 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4477
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> String offset cast occurred /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4530
ERROR - 2018-10-11 15:20:07 --> Severity: Warning --> Missing argument 6 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 3333 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4477
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> String offset cast occurred /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4530
ERROR - 2018-10-11 15:20:07 --> Severity: Warning --> Missing argument 6 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 3335 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4477
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> String offset cast occurred /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4478
ERROR - 2018-10-11 15:20:07 --> Severity: Notice --> Undefined variable: index /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4530
ERROR - 2018-10-11 15:20:07 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/pcdata/dawo/__private/system/core/Exceptions.php:271) /var/www/html/pcdata/dawo/__private/system/core/Common.php 570
ERROR - 2018-10-11 15:23:55 --> Severity: error --> Exception: syntax error, unexpected 'echo' (T_ECHO), expecting ',' or ';' /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4533
ERROR - 2018-10-11 15:45:33 --> Severity: Warning --> Missing argument 8 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 4522 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4464
ERROR - 2018-10-11 15:45:33 --> Severity: Notice --> Undefined variable: type /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4519
ERROR - 2018-10-11 15:45:33 --> Severity: Notice --> Undefined variable: type /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4523
ERROR - 2018-10-11 15:54:53 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/pcdata/dawo/__private/api/controllers/Service.php:4519) /var/www/html/pcdata/dawo/__private/system/core/Common.php 570
ERROR - 2018-10-11 15:54:53 --> Severity: Error --> Allowed memory size of 134217728 bytes exhausted (tried to allocate 9437184 bytes) /var/www/html/pcdata/dawo/__private/api/third_party/PHPExcel/CachedObjectStorage/Memory.php 55
ERROR - 2018-10-11 15:59:54 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/pcdata/dawo/__private/api/controllers/Service.php:4519) /var/www/html/pcdata/dawo/__private/system/core/Common.php 570
ERROR - 2018-10-11 15:59:54 --> Severity: Error --> Allowed memory size of 134217728 bytes exhausted (tried to allocate 20480 bytes) /var/www/html/pcdata/dawo/__private/api/third_party/PHPExcel/Style/Supervisor.php 123
ERROR - 2018-10-11 16:01:20 --> Severity: Warning --> Missing argument 9 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 4530 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4464
ERROR - 2018-10-11 16:01:20 --> Severity: Warning --> Missing argument 9 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 4530 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4464
ERROR - 2018-10-11 16:01:20 --> Severity: Warning --> Missing argument 9 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 4530 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4464
ERROR - 2018-10-11 16:01:20 --> Severity: Warning --> Missing argument 9 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 4530 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4464
ERROR - 2018-10-11 16:01:20 --> Severity: Warning --> Missing argument 9 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 4530 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4464
ERROR - 2018-10-11 16:01:20 --> Severity: Warning --> Missing argument 9 for Service::generate_excel_eventwise(), called in /var/www/html/pcdata/dawo/__private/api/controllers/Service.php on line 4530 and defined /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4464
ERROR - 2018-10-11 16:01:20 --> Severity: Notice --> Undefined variable: is_last /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 4536
ERROR - 2018-10-11 16:01:20 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /var/www/html/pcdata/dawo/__private/system/core/Exceptions.php:271) /var/www/html/pcdata/dawo/__private/system/core/Common.php 570
ERROR - 2018-10-11 16:24:36 --> Severity: Notice --> Undefined index: born_cattle_id /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3574
ERROR - 2018-10-11 16:37:52 --> Severity: Notice --> Undefined variable: rowCount /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3332
ERROR - 2018-10-11 16:37:52 --> Severity: Notice --> Undefined variable: rowCount /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3337
ERROR - 2018-10-11 16:38:19 --> Severity: Notice --> Undefined variable: rowCount /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3337
ERROR - 2018-10-11 16:47:01 --> Severity: error --> Exception: Call to undefined method DeliveryData::get_all_cattle_ids() /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3357
ERROR - 2018-10-11 16:47:22 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')
GROUP BY `delivery_date`
ORDER BY `delivery_date` ASC' at line 3 - Invalid query: SELECT SUM(male_count) as male_count, SUM(female_count) as female_count, SUM(dead_count) as dead_count
FROM `delivery_data`
WHERE `cattle_id` IN()
GROUP BY `delivery_date`
ORDER BY `delivery_date` ASC
ERROR - 2018-10-11 16:47:43 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')
GROUP BY `delivery_date`
ORDER BY `delivery_date` ASC' at line 3 - Invalid query: SELECT SUM(male_count) as male_count, SUM(female_count) as female_count, SUM(dead_count) as dead_count, `delivery_date`
FROM `delivery_data`
WHERE `cattle_id` IN()
GROUP BY `delivery_date`
ORDER BY `delivery_date` ASC
ERROR - 2018-10-11 16:55:44 --> Severity: Notice --> Undefined index: event_date /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3370
ERROR - 2018-10-11 16:55:44 --> Severity: Notice --> Undefined variable: event /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3374
ERROR - 2018-10-11 16:55:44 --> Severity: Notice --> Undefined index: event_date /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 3375
ERROR - 2018-10-11 17:14:36 --> Severity: error --> Exception: syntax error, unexpected '=', expecting ')' /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 2818
ERROR - 2018-10-11 17:28:23 --> Severity: error --> Exception: syntax error, unexpected '$thecattlerecord' (T_VARIABLE) /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 1124
ERROR - 2018-10-11 17:28:26 --> Severity: error --> Exception: syntax error, unexpected '$thecattlerecord' (T_VARIABLE) /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 1124
