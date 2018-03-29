<?php
	// Key Value
	date_default_timezone_set('America/Sao_Paulo');
	$date_format = 'd-m-Y';
	$yesterday = strtotime('-1 day');
	$tomorrow = strtotime('+1 day');
	$key_yesterday = md5(date($date_format, $yesterday));
	$key_today = md5(date($date_format));
	$key_tomorrow = md5(date($date_format, $tomorrow));
	
	echo $key_yesterday;
	echo "<BR>";
	echo $key_today;
	echo "<BR>";
	echo $key_tomorrow;
	echo "<BR>";
?>