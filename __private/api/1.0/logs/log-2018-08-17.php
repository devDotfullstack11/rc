<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-08-17 04:24:30 --> Severity: Notice --> Undefined variable: conditons C:\Wamp\www\html\verdant\dawo\__private\api\controllers\Service.php 2173
ERROR - 2018-08-17 04:24:30 --> Severity: Notice --> Undefined variable: userdata C:\Wamp\www\html\verdant\dawo\__private\api\controllers\Service.php 2173
ERROR - 2018-08-17 04:24:36 --> Severity: Notice --> Undefined variable: conditons C:\Wamp\www\html\verdant\dawo\__private\api\controllers\Service.php 2173
ERROR - 2018-08-17 04:24:36 --> Severity: Notice --> Undefined variable: userdata C:\Wamp\www\html\verdant\dawo\__private\api\controllers\Service.php 2173
ERROR - 2018-08-17 04:24:57 --> Severity: Notice --> Undefined variable: userdata C:\Wamp\www\html\verdant\dawo\__private\api\controllers\Service.php 2173
ERROR - 2018-08-17 05:09:39 --> Query error: Table 'dawo.events' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows` FROM `events`
ERROR - 2018-08-17 05:10:08 --> Query error: Table 'dawo.events' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows` FROM `events`
ERROR - 2018-08-17 05:54:02 --> Severity: error --> Exception: Too few arguments to function Group_model::get_all(), 0 passed in C:\Wamp\www\html\verdant\dawo\__private\api\controllers\Service.php on line 3336 and exactly 1 expected C:\Wamp\www\html\verdant\dawo\__private\api\models\Group_model.php 11
ERROR - 2018-08-17 05:54:48 --> Severity: error --> Exception: Too few arguments to function Group_model::get_all(), 0 passed in C:\Wamp\www\html\verdant\dawo\__private\api\controllers\Service.php on line 3336 and exactly 1 expected C:\Wamp\www\html\verdant\dawo\__private\api\models\Group_model.php 11
ERROR - 2018-08-17 05:55:12 --> Severity: Notice --> Undefined index: userdata C:\Wamp\www\html\verdant\dawo\__private\api\controllers\Service.php 3336
ERROR - 2018-08-17 06:07:42 --> Severity: Notice --> Undefined index: userdata C:\Wamp\www\html\verdant\dawo\__private\api\controllers\Service.php 3336
ERROR - 2018-08-17 06:13:39 --> Query error: Column 'group_id' in where clause is ambiguous - Invalid query: SELECT `cattles`.`id`, `cattles`.`tag_id`, `cattles`.`dam_id`, `cattles`.`dob`, `cattles`.`dop`, `purchase_price`, `weight`, `cattles`.`ai_date`, `is_pregnant`, `calving_date`, `sale_date`, `death_date`, `sale_price`, `sire_id`, `per_day_milk`, `breed`, `type`, `lactation`, `breeding_process`.`is_ai_done` as `ai_status`, `cattle_group`, `pgd`.`group_id` as `parent_group`, `pgd`.`group_title` as `parent_group_title`, `sgd`.`group_id` as `sub_group`, `sgd`.`group_title` as `sub_group_title`
FROM `cattles`
LEFT JOIN `breeding_process` ON `breeding_process`.`cattle_id`=`cattles`.`id`
LEFT JOIN `group_data` `pgd` ON `pgd`.`group_id`=`cattles`.`parent_group`
LEFT JOIN `group_data` `sgd` ON `sgd`.`group_id`=`cattles`.`sub_group`
WHERE `group_id` = '10'
AND `owner_id` = '6'
AND `is_deleted` = 'No'
ORDER BY `id` DESC
ERROR - 2018-08-17 10:38:30 --> Severity: error --> Exception: syntax error, unexpected '$this' (T_VARIABLE), expecting ')' C:\Wamp\www\html\verdant\dawo\__private\api\controllers\Service.php 274
ERROR - 2018-08-17 11:57:00 --> Severity: Notice --> Undefined variable: update_lactation C:\Wamp\www\html\verdant\dawo\__private\api\controllers\Service.php 2078
