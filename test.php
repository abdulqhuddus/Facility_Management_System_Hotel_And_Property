<?php
date_default_timezone_set('Asia/Dubai');
$next_pay_date = "2023-07-18";
$next = $next_pay_date." 00:00:01";
 $now = date("Y-m-d H:i:s");
 $starttime1 = strtotime($now);
 $starttime2 = strtotime($next);
 $result_secs = $starttime2 - $starttime1;
 $result_days = $result_secs / 86400;
 if($result_days < 10){echo "TRUE";}else{echo "FALSE";}
?>