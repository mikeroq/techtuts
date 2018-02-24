<?
include("config.php");
	switch($_GET[mode])
	{
	default:
	$get = mysql_query("SELECT * from affiliates where id = '$_GET[id]'");
	$aff = mysql_fetch_array($get);
	$update = mysql_query("update affiliates set clicks = clicks +1 where id = '$aff[id]'");
	echo ("<meta http-equiv=\"Refresh\" content=\"0; URL=$aff[url]\"/>Thank You! You will be redirected");
	break;
	case 'topsites':
	if ($_GET[id])
	{
	$update = mysql_query("update topsites set out = out+1 where id = '$_GET[id]'");
	$site = mysql_query("SELECT * from topsites where id = '$_GET[id]'");
	$site = mysql_fetch_array($site);
	echo ("<meta http-equiv=\"Refresh\" content=\"0; URL=$site[url]\"/>Thank You! You will be redirected");

	}
	break;
	}
?>