<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-10-09 10:58:06 --> Severity: error --> Exception: syntax error, unexpected ';', expecting ',' or ')' /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 1292
ERROR - 2018-10-09 11:07:07 --> Query error: Column 'ai_date' cannot be null - Invalid query: INSERT INTO `cattle_history` (`cattle_id`, `event_type`, `event_date`, `ai_date`, `message`) VALUES ('250', 'Second PD', '2017-05-23 00:00:00', NULL, 'Second PD Done on 23/05/2017')
ERROR - 2018-10-09 11:08:16 --> Query error: Column 'ai_date' cannot be null - Invalid query: INSERT INTO `cattle_history` (`cattle_id`, `event_type`, `event_date`, `ai_date`, `message`) VALUES ('250', 'Second PD', '2018-10-09', NULL, 'First PD Done on 09/10/2018')
ERROR - 2018-10-09 11:10:30 --> Query error: Column 'ai_date' cannot be null - Invalid query: INSERT INTO `cattle_history` (`cattle_id`, `event_type`, `event_date`, `ai_date`, `message`) VALUES ('250', 'Second PD', '2017-05-23 00:00:00', NULL, 'Second PD Done on 23/05/2017')
ERROR - 2018-10-09 11:19:02 --> Query error: Column 'ai_date' cannot be null - Invalid query: INSERT INTO `cattle_history` (`cattle_id`, `event_type`, `event_date`, `ai_date`, `message`) VALUES ('250', 'second_pd', '2017-05-23 00:00:00', NULL, 'Second PD Done on 23/05/2017')
ERROR - 2018-10-09 11:20:07 --> Query error: Column 'ai_date' cannot be null - Invalid query: INSERT INTO `cattle_history` (`cattle_id`, `event_type`, `event_date`, `ai_date`, `message`) VALUES ('250', 'first_pd', '2018-10-09', NULL, 'First PD Done on 09/10/2018')
ERROR - 2018-10-09 11:20:18 --> Severity: Notice --> Undefined index: first_pd_on /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 2233
ERROR - 2018-10-09 11:20:18 --> Severity: Warning --> DateTime::modify(): Failed to parse time string (+ day) at position 0 (+): Unexpected character /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 2235
ERROR - 2018-10-09 11:40:38 --> Severity: error --> Exception: DateTime::__construct() expects parameter 1 to be string, object given /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 1292
ERROR - 2018-10-09 11:41:08 --> Severity: error --> Exception: DateTime::__construct() expects parameter 1 to be string, object given /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 1293
ERROR - 2018-10-09 11:42:18 --> Severity: Notice --> Array to string conversion /var/www/html/pcdata/dawo/__private/system/database/DB_query_builder.php 683
ERROR - 2018-10-09 11:42:18 --> Query error: Unknown column 'Array' in 'where clause' - Invalid query: DELETE FROM `upcoming_events`
WHERE `cattle_id` = `Array`
AND `event_type` = 'heat'
ERROR - 2018-10-09 11:44:00 --> Severity: Notice --> Array to string conversion /var/www/html/pcdata/dawo/__private/system/database/DB_query_builder.php 683
ERROR - 2018-10-09 11:44:00 --> Query error: Unknown column 'Array' in 'where clause' - Invalid query: DELETE FROM `upcoming_events`
WHERE `cattle_id` = `Array`
AND `event_type` = 'heat'
ERROR - 2018-10-09 11:44:19 --> Severity: 4096 --> Object of class DateTime could not be converted to string /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 1294
ERROR - 2018-10-09 11:44:51 --> Severity: Notice --> Array to string conversion /var/www/html/pcdata/dawo/__private/system/database/DB_query_builder.php 683
ERROR - 2018-10-09 11:44:51 --> Query error: Unknown column 'Array' in 'where clause' - Invalid query: DELETE FROM `upcoming_events`
WHERE `cattle_id` = `Array`
AND `event_type` = 'heat'
