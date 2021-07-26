<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-10-04 13:03:15 --> Severity: 4096 --> Object of class DateTime could not be converted to string /var/www/html/pcdata/dawo/__private/system/database/DB_driver.php 1525
ERROR - 2018-10-04 13:03:15 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'WHERE `id` = '126'' at line 2 - Invalid query: UPDATE `cattles` SET `ai_date` = 
WHERE `id` = '126'
ERROR - 2018-10-04 13:03:54 --> Severity: error --> Exception: DateTime::__construct() expects parameter 1 to be string, object given /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 2176
ERROR - 2018-10-04 13:04:48 --> Severity: error --> Exception: DateTime::__construct() expects parameter 1 to be string, object given /var/www/html/pcdata/dawo/__private/api/core/MY_Controller.php 293
ERROR - 2018-10-04 13:06:52 --> Severity: 4096 --> Object of class DateTime could not be converted to string /var/www/html/pcdata/dawo/__private/system/database/DB_driver.php 1477
ERROR - 2018-10-04 13:06:52 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ' 'AI was Done on 04/10/2018')' at line 1 - Invalid query: INSERT INTO `cattle_history` (`cattle_id`, `event_type`, `event_date`, `ai_date`, `message`) VALUES ('126', 'ai', '2018-10-04 00:00:00', , 'AI was Done on 04/10/2018')
ERROR - 2018-10-04 13:08:14 --> Severity: error --> Exception: Call to a member function modify() on string /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 2177
ERROR - 2018-10-04 13:08:52 --> Severity: error --> Exception: Call to a member function format() on string /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 2180
ERROR - 2018-10-04 15:08:43 --> Severity: Compile Error --> Can't use method return value in write context /var/www/html/pcdata/dawo/__private/api/controllers/Service.php 846
