<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-06-03 10:36:51 --> Severity: Warning --> Illegal string offset 'title' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 337
ERROR - 2023-06-03 10:36:51 --> Severity: Warning --> Illegal string offset 'message' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 469
ERROR - 2023-06-03 10:36:51 --> Severity: Warning --> Cannot assign an empty string to a string offset /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 469
ERROR - 2023-06-03 10:36:51 --> Severity: Warning --> Illegal string offset 'identity' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 470
ERROR - 2023-06-03 10:36:51 --> Severity: Warning --> Illegal string offset 'password' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 476
ERROR - 2023-06-03 10:36:51 --> Severity: Warning --> Illegal string offset 'parent' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 481
ERROR - 2023-06-03 10:36:51 --> Severity: Warning --> Illegal string offset 'title' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 482
ERROR - 2023-06-03 10:36:52 --> 404 Page Not Found: ../modules/pwfpanel/controllers/Pwfpanel/img
ERROR - 2023-06-03 10:37:06 --> Severity: Warning --> Illegal string offset 'title' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 337
ERROR - 2023-06-03 10:37:08 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 47
ERROR - 2023-06-03 10:37:08 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 51
ERROR - 2023-06-03 10:37:08 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 65
ERROR - 2023-06-03 10:37:08 --> Query error: Expression #1 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'idcare.P.id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT `P`.`id` as `patient_id`, `P`.`patient_id` as `pid`, `P`.`name` as `patient_name`, `P`.`date_of_start_abx`, `P`.`address`, `P`.`total_days_of_patient_stay`, `P`.`room_number`, `P`.`symptom_onset`, `P`.`md_stayward_consult`, `P`.`criteria_met`, `P`.`md_stayward_response`, `P`.`psa`, `P`.`created_date`, `P`.`care_unit_id`, `CI`.`name` as `care_unit_name`, `P`.`doctor_id`, `P`.`culture_source`, `P`.`organism`, `P`.`precautions`, `CS`.`name` as `culture_source_name`, `Org`.`name` as `organism_name`, `Pre`.`name` as `precautions_name`, `DOC`.`name` as `doctor_name`, `P`.`md_steward_id`, `U`.`first_name` as `md_stayward`, `PC`.`initial_rx`, `IRX`.`name` as `initial_rx_name`, `PC`.`initial_dx`, `IDX`.`name` as `initial_dx_name`, `PC`.`initial_dot`, `PC`.`new_initial_rx`, `IRX2`.`name` as `new_initial_rx_name`, `PC`.`new_initial_dx`, `IDX2`.`name` as `new_initial_dx_name`, `PC`.`new_initial_dot`, `PC`.`comment`
FROM `vendor_sale_patient` `P`
INNER JOIN `vendor_sale_care_unit` `CI` ON `CI`.`id`=`P`.`care_unit_id`
INNER JOIN `vendor_sale_doctors` `DOC` ON `DOC`.`id`=`P`.`doctor_id`
LEFT JOIN `vendor_sale_users` `U` ON `U`.`id`=`P`.`md_steward_id`
INNER JOIN `vendor_sale_patient_consult` `PC` ON `PC`.`patient_id`=`P`.`id`
LEFT JOIN `vendor_sale_initial_rx` `IRX` ON `IRX`.`id`=`PC`.`initial_rx`
LEFT JOIN `vendor_sale_initial_dx` `IDX` ON `IDX`.`id`=`PC`.`initial_dx`
LEFT JOIN `vendor_sale_culture_source` `CS` ON `CS`.`name`=`P`.`culture_source`
LEFT JOIN `vendor_sale_organism` `Org` ON `Org`.`name`=`P`.`organism`
LEFT JOIN `vendor_sale_precautions` `Pre` ON `Pre`.`name`=`P`.`precautions`
LEFT JOIN `vendor_sale_initial_rx` `IRX2` ON `IRX2`.`id`=`PC`.`new_initial_rx`
LEFT JOIN `vendor_sale_initial_dx` `IDX2` ON `IDX2`.`id`=`PC`.`new_initial_dx`
GROUP BY `pid`
ORDER BY `P`.`id` DESC
ERROR - 2023-06-03 10:38:19 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 47
ERROR - 2023-06-03 10:38:19 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 51
ERROR - 2023-06-03 10:38:19 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 65
ERROR - 2023-06-03 10:38:26 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 297
ERROR - 2023-06-03 10:39:10 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 47
ERROR - 2023-06-03 10:39:10 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 51
ERROR - 2023-06-03 10:39:10 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 65
ERROR - 2023-06-03 10:44:58 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 47
ERROR - 2023-06-03 10:44:58 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 51
ERROR - 2023-06-03 10:44:58 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 65
ERROR - 2023-06-03 10:59:19 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 979
ERROR - 2023-06-03 10:59:45 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 47
ERROR - 2023-06-03 10:59:45 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 51
ERROR - 2023-06-03 10:59:45 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 65
ERROR - 2023-06-03 11:04:29 --> Severity: Warning --> Illegal string offset 'title' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 337
ERROR - 2023-06-03 11:04:29 --> Severity: Warning --> Illegal string offset 'message' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 469
ERROR - 2023-06-03 11:04:29 --> Severity: Warning --> Cannot assign an empty string to a string offset /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 469
ERROR - 2023-06-03 11:04:29 --> Severity: Warning --> Illegal string offset 'identity' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 470
ERROR - 2023-06-03 11:04:29 --> Severity: Warning --> Illegal string offset 'password' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 476
ERROR - 2023-06-03 11:04:29 --> Severity: Warning --> Illegal string offset 'parent' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 481
ERROR - 2023-06-03 11:04:29 --> Severity: Warning --> Illegal string offset 'title' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 482
ERROR - 2023-06-03 11:04:33 --> Severity: Warning --> Illegal string offset 'title' /var/www/html/IDCARE/BACKEND/application/modules/pwfpanel/controllers/Pwfpanel.php 337
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> A non-numeric value encountered /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:35 --> Severity: Warning --> Division by zero /var/www/html/IDCARE/BACKEND/application/modules/reportsSummary/controllers/ReportsSummary.php 3120
ERROR - 2023-06-03 11:04:37 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 65
ERROR - 2023-06-03 11:05:07 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 65
ERROR - 2023-06-03 11:07:27 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 65
ERROR - 2023-06-03 11:07:47 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 47
ERROR - 2023-06-03 11:07:47 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 51
ERROR - 2023-06-03 11:07:47 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 65
ERROR - 2023-06-03 11:09:07 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 47
ERROR - 2023-06-03 11:09:07 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 51
ERROR - 2023-06-03 11:09:07 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 65
ERROR - 2023-06-03 11:09:43 --> Severity: Warning --> Invalid argument supplied for foreach() /var/www/html/IDCARE/BACKEND/application/modules/patient/controllers/Patient.php 65
