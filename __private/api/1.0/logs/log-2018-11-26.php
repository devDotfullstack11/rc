<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-11-26 10:31:04 --> Query error: Table 'dawo.events' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows` FROM `events`
ERROR - 2018-11-26 10:31:53 --> Query error: Table 'dawo.events' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows` FROM `events`
ERROR - 2018-11-26 15:28:50 --> Severity: Notice --> Array to string conversion /var/www/html/pcdata/dawo/__private/system/database/DB_query_builder.php 683
ERROR - 2018-11-26 15:28:50 --> Query error: Unknown column 'Array' in 'where clause' - Invalid query: DELETE FROM `upcoming_events`
WHERE `cattle_id` = `Array`
AND `event_type` = 'heat'
