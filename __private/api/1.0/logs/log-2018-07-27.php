<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-07-27 12:18:33 --> Severity: Notice --> Undefined index: status C:\Wamp\www\html\verdant\dawo\__private\api\models\User_model.php 42
ERROR - 2018-07-27 12:40:20 --> Severity: Notice --> Undefined index: status C:\Wamp\www\html\verdant\dawo\__private\api\models\User_model.php 42
ERROR - 2018-07-27 12:42:24 --> Query error: Table 'dawo.group_data' doesn't exist - Invalid query: SELECT `cattles`.`id`, `cattles`.`tag_id`, `cattles`.`dam_id`, `cattles`.`dob`, `cattles`.`dop`, `purchase_price`, `weight`, `cattles`.`ai_date`, `is_pregnant`, `calving_date`, `sale_date`, `death_date`, `sale_price`, `sire_id`, `per_day_milk`, `breed`, `type`, `lactation`, `breeding_process`.`is_ai_done` as `ai_status`, `cattle_group`, `pgd`.`group_id` as `parent_group`, `pgd`.`group_title` as `parent_group_title`, `sgd`.`group_id` as `sub_group`, `sgd`.`group_title` as `sub_group_title`
FROM `cattles`
LEFT JOIN `breeding_process` ON `breeding_process`.`cattle_id`=`cattles`.`id`
LEFT JOIN `group_data` `pgd` ON `pgd`.`group_id`=`cattles`.`parent_group`
LEFT JOIN `group_data` `sgd` ON `sgd`.`group_id`=`cattles`.`sub_group`
WHERE `owner_id` = '6'
AND `is_deleted` = 'No'
ORDER BY `id` DESC
ERROR - 2018-07-27 12:46:57 --> Query error: Unknown column 'cattles.parent_group' in 'on clause' - Invalid query: SELECT `cattles`.`id`, `cattles`.`tag_id`, `cattles`.`dam_id`, `cattles`.`dob`, `cattles`.`dop`, `purchase_price`, `weight`, `cattles`.`ai_date`, `is_pregnant`, `calving_date`, `sale_date`, `death_date`, `sale_price`, `sire_id`, `per_day_milk`, `breed`, `type`, `lactation`, `breeding_process`.`is_ai_done` as `ai_status`, `cattle_group`, `pgd`.`group_id` as `parent_group`, `pgd`.`group_title` as `parent_group_title`, `sgd`.`group_id` as `sub_group`, `sgd`.`group_title` as `sub_group_title`
FROM `cattles`
LEFT JOIN `breeding_process` ON `breeding_process`.`cattle_id`=`cattles`.`id`
LEFT JOIN `group_data` `pgd` ON `pgd`.`group_id`=`cattles`.`parent_group`
LEFT JOIN `group_data` `sgd` ON `sgd`.`group_id`=`cattles`.`sub_group`
WHERE `owner_id` = '6'
AND `is_deleted` = 'No'
ORDER BY `id` DESC
ERROR - 2018-07-27 12:48:17 --> Query error: Unknown column 'cattles.sub_group' in 'on clause' - Invalid query: SELECT `cattles`.`id`, `cattles`.`tag_id`, `cattles`.`dam_id`, `cattles`.`dob`, `cattles`.`dop`, `purchase_price`, `weight`, `cattles`.`ai_date`, `is_pregnant`, `calving_date`, `sale_date`, `death_date`, `sale_price`, `sire_id`, `per_day_milk`, `breed`, `type`, `lactation`, `breeding_process`.`is_ai_done` as `ai_status`, `cattle_group`, `pgd`.`group_id` as `parent_group`, `pgd`.`group_title` as `parent_group_title`, `sgd`.`group_id` as `sub_group`, `sgd`.`group_title` as `sub_group_title`
FROM `cattles`
LEFT JOIN `breeding_process` ON `breeding_process`.`cattle_id`=`cattles`.`id`
LEFT JOIN `group_data` `pgd` ON `pgd`.`group_id`=`cattles`.`parent_group`
LEFT JOIN `group_data` `sgd` ON `sgd`.`group_id`=`cattles`.`sub_group`
WHERE `owner_id` = '6'
AND `is_deleted` = 'No'
ORDER BY `id` DESC
