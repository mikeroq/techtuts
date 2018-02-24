<?php
include ("config.php");
$ip = $REMOTE_ADDR;
$getid = mysql_query("SELECT * from stats order by 'id' desc limit 1");
$idinfo = mysql_fetch_array($getid);
$test1 = mysql_query("SELECT * from ip where ip = '$ip'");
$datecheck = mysql_query("SELECT * from stats where date = NOW()");
$updatehits = mysql_query ("UPDATE stats SET hits = hits + 1 WHERE id = '$idinfo[id]'");

$datenum = mysql_num_rows($datecheck);
if ($datenum == 0)
{
$dateinsert = mysql_query("INSERT INTO `stats` ( `date`, `total`, `hits`)VALUES ( NOW(), '1', '1')");
}
$test = mysql_num_rows($test1);
	if ($test == 0)
	{
		$statinsert = mysql_query("INSERT INTO `ip` ( `ip` , `date`)VALUES ('$ip', NOW())");
		$updateuni = mysql_query ("UPDATE stats SET total = total + 1 WHERE id = '$idinfo[id]'"); 
		}
if ($test >= 1)
{
$tdaytest = mysql_query("SELECT * from ip where ip = '$ip' AND date = NOW()");
$day = mysql_num_rows($tdaytest);
if ($day == 0)
{
$update = mysql_query("UPDATE ip set date = NOW() where ip = '$ip'");
$updateuni = mysql_query ("UPDATE stats SET total = total + 1 WHERE id = '$idinfo[id]'");
}
}
?> 