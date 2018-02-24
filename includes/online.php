<?
include("config.php"); 
	$timeoutseconds = 300; //How long is considered online? 
$timestamp = time(); 
$timeout = ($timestamp-$timeoutseconds); 
if ($logged[username])
{
$page1 = $PHP_SELF;
$update = mysql_query("UPDATE members set online = '$timestamp', ip = '$REMOTE_ADDR' where username = '$logged[username]'");
}
if (!$logged[username])
{
$ch1 = mysql_query("SELECT * from guests where username = '$REMOTE_ADDR'");
$ch2 = mysql_num_rows($ch1);
if ($ch2 == 0)
{
$gnewpg = mysql_query("INSERT INTO `guests` ( `date` , `username` , `page` )VALUES ('$timestamp', '$REMOTE_ADDR', '$_GET[id]')");
}
else
{
$gupdate = mysql_query("UPDATE guests set date = '$timestamp', page = '$_GET[id]' where username = '$ip'");
}
}
$guests = mysql_query("SELECT * from guests where date >= '$timeout'");
$guests1 = mysql_num_rows($guests);
$guests1 = $guests1+1;
//
$getusers = mysql_query("SELECT * from members where online >= '$timeout'");
$numusers = mysql_num_rows($getusers);
$nummembers = mysql_num_rows($getusers);
$numusers = $numusers + $guests1;
//^^end not 0
$numusers = mysql_num_rows($getusers);
$i = 1;
while ($users = mysql_fetch_array($getusers))
{
echo ("$users[username]");
if($i != $numusers)
{
echo(", ");
}
$i++;
}
if($guests1 != 0)
{
if($guests == 1)
{
if($nummembers != 0)
{
echo("<br />1 Guest");
}
//^^ members not 0
else
{
echo("1 Guest");
}
//^^ end no members
}
//^^end not 1
else
{
if($nummembers == 0)
{
echo("$guests1 Guests");
}
else
{
echo("<br />$guests1 Guests");
}
}
}

?>