<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-06-13 17:28:14 --> 404 Page Not Found: Api/user
ERROR - 2021-06-13 17:28:32 --> 404 Page Not Found: Api/user
ERROR - 2021-06-13 17:28:55 --> 404 Page Not Found: Api/user
ERROR - 2021-06-13 18:36:06 --> Severity: Error --> Call to undefined method Register::sendResponse() /var/www/html/ms/rc/rc_backend/__private/api/controllers/Register.php 97
ERROR - 2021-06-13 18:41:41 --> Severity: Notice --> Undefined variable: data /var/www/html/ms/rc/rc_backend/__private/api/controllers/Register.php 97
ERROR - 2021-06-13 19:36:26 --> Severity: Parsing Error --> syntax error, unexpected end of file, expecting function (T_FUNCTION) /var/www/html/ms/rc/rc_backend/__private/api/controllers/Register.php 126
ERROR - 2021-06-13 19:42:22 --> Severity: Error --> Call to undefined function pr() /var/www/html/ms/rc/rc_backend/__private/api/controllers/Register.php 54
ERROR - 2021-06-13 20:14:21 --> Query error: Unknown column 'c.status' in 'where clause' - Invalid query: SELECT *
FROM `rc_users`
WHERE `email` = 'admin5@maninder.xyz'
AND `c`.`status` != 2
ERROR - 2021-06-13 20:14:41 --> Severity: Notice --> Undefined index: customer_id /var/www/html/ms/rc/rc_backend/__private/api/controllers/Login.php 633
ERROR - 2021-06-13 20:14:41 --> Severity: Notice --> Undefined index: customer_id /var/www/html/ms/rc/rc_backend/__private/api/controllers/Login.php 52
ERROR - 2021-06-13 20:14:41 --> Query error: Unknown column 'customer_id' in 'where clause' - Invalid query: UPDATE `rc_users` SET `auth_token` = '8799b739bbb17e2cf060161c53d48c83'
WHERE `customer_id` IS NULL
ERROR - 2021-06-13 20:15:19 --> Severity: Notice --> Undefined index: customer_id /var/www/html/ms/rc/rc_backend/__private/api/controllers/Login.php 52
ERROR - 2021-06-13 20:15:19 --> Query error: Unknown column 'customer_id' in 'where clause' - Invalid query: UPDATE `rc_users` SET `auth_token` = '8a481977cff0b64636098329dc4b9ea6'
WHERE `customer_id` IS NULL
ERROR - 2021-06-13 20:36:21 --> Severity: Notice --> Undefined index: profile_pic /var/www/html/ms/rc/rc_backend/__private/api/controllers/Login.php 129
ERROR - 2021-06-13 20:36:23 --> Severity: Notice --> Undefined index: customer_id /var/www/html/ms/rc/rc_backend/__private/api/controllers/Login.php 138
ERROR - 2021-06-13 20:36:23 --> Query error: Unknown column 'customer_id' in 'where clause' - Invalid query: UPDATE `rc_users` SET `facebook_id` = '123456', `email_verify` = 1, `auth_token` = '869e707a6b0b450ed28b858299ef3ce2'
WHERE `customer_id` IS NULL
ERROR - 2021-06-13 20:37:12 --> Severity: Notice --> Undefined index: customer_id /var/www/html/ms/rc/rc_backend/__private/api/controllers/Login.php 138
ERROR - 2021-06-13 20:37:12 --> Query error: Unknown column 'customer_id' in 'where clause' - Invalid query: UPDATE `rc_users` SET `facebook_id` = '123456', `email_verify` = 1, `auth_token` = 'e742d4970b022069be4bdb2126cf9886'
WHERE `customer_id` IS NULL
ERROR - 2021-06-13 20:37:26 --> Query error: Unknown column 'auth_token' in 'field list' - Invalid query: UPDATE `rc_users` SET `facebook_id` = '123456', `email_verify` = 1, `auth_token` = '1977e11da080f39dae481f0305b7fa3c'
WHERE `id` = '1'
ERROR - 2021-06-13 20:41:05 --> Severity: Notice --> Undefined index: customer_id /var/www/html/ms/rc/rc_backend/__private/api/controllers/Login.php 97
ERROR - 2021-06-13 20:41:05 --> Query error: Unknown column 'customer_id' in 'where clause' - Invalid query: UPDATE `rc_users` SET `auth_token` = 'ec8067ae6dd3b9835a5e24bf9874c2a3'
WHERE `customer_id` IS NULL
ERROR - 2021-06-13 20:41:37 --> Query error: Unknown column 'auth_token' in 'field list' - Invalid query: UPDATE `rc_users` SET `auth_token` = '1c1904f42240411a68144bf4317f1da3'
WHERE `id` = '1'
