<?php

array (
  0 => 'queued_entry_hours',
);
$sql = 'select `value` from `system_setting` where `name` = ? limit 1';
$time = '23.98ms';
array (
  0 => 'queued_entry_people_percent',
);
$sql = 'select `value` from `system_setting` where `name` = ? limit 1';
$time = '1.92ms';
array (
  0 => 2,
);
$sql = 'select count(*) as aggregate from `queue` where `status` = ?';
$time = '1.93ms';
array (
  0 => 2,
);
$sql = 'select * from `queue` where `status` = ? order by `created_at` asc limit 1';
$time = '2.49ms';