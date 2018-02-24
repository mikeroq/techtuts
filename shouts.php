<?
ob_start();
include("config.php");
include ("includes/ubb.php");
include("includes/funcs.php");
$bbcode = new ubbParser();
?>
<html>
<head>
<style type="text/css">
a, div, td, body
{
font-family: verdana;
font-size: 10;
text-decoration: none;
}
a
{
color: #808080;
}
</style></head><body style="background: #f1f1f1; margin: 0px;">
<?
if($_GET[admin] ==yes)
{
if($logged[username] && $logged[level] >= 7)
{
if($_GET[id])
{
$getshout = mysql_query("select * from ashouts where id = '$_GET[id]' limit 1");
$shout = mysql_fetch_array($getshout);
if($logged[username] && $logged[username] == $shout[user]|| $logged[username] && $logged[level] >= 8)
{
$delete = mysql_query("delete from ashouts where id = '$_GET[id]'");
echo("<div style=\"background: #d7d7d7\">Shout Deleted</div>");
}
}
if($_POST[shout])
{
if($logged[username] && $logged[level] >=1)
{
if(isset($_POST[message]))
{
$last = mysql_query("Select * from ashouts order by id desc limit 1");
$last = mysql_fetch_array($last);
$message = htmlspecialchars("$_POST[message]");
if($last[shout] != $message)
{
if($_POST[url] == URL||$_POST[url]==NULL)
{
$insert = mysql_query("insert into `ashouts` (`url`, `shout`, `time`, `ip`, `user`) VALUES ('na', '$message', NOW(), '$REMOTE_ADDR', '$logged[username]')");
echo("<meta http-equiv=\"Refresh\" content=\"0; URL=shouts.php?admin=yes\"/>");
}
else
{
$url = htmlspecialchars(addslashes("$_POST[url]"));
setcookie("url", $url,time()+(60*60*24*19), "/", ""); 
$insert = mysql_query("insert into `ashouts` (`url`, `shout`, `time`, `ip`, `user`) VALUES ('$url', '$message', NOW(), '$REMOTE_ADDR', '$logged[username]')");
echo("<meta http-equiv=\"Refresh\" content=\"0; URL=shouts.php?admin=yes\"/>");
}}
else
{
echo("<div style=\"background: #d7d7d7;\"><b>No Spam</b></div>");
}
}
}
else
{
echo("You are not logged in!");
}
}
$color2 = "#e6e6e6";
$color1 = "#f1f1f1";
$row_count = 0;
$getshouts = mysql_query("select * from ashouts order by id desc limit 10");
while($shouts = mysql_fetch_array($getshouts))
{
$shouts[shout] = $bbcode->parse($shouts[shout]);
$shouts[shout] = str_replace("&amp;", "&", $shouts[shout]);

$row_color = ($row_count % 2) ? $color1 : $color2; 
$row_count++;
echo("<div style=\"wordwrap: break-word; background: $row_color; padding: 2px;\">");
if($shouts[url] != na)
{
echo("<a href=\"$shouts[url]\" target=\"_BLANK\">");
}
echo("<b style=\"color: #808080; margin: 1px;\">$shouts[user]</b>");

if($shouts[url] != na)
{
echo("</a>");
}

if($logged[username] && $logged[username] == $shouts[user]|| $logged[username] && $logged[level] >= 8)
{
echo("&nbsp; <a href=\"?admin=yes&id=$shouts[id]\">(Delete)</a>");
}
echo("<br />
$shouts[shout]</div>");
}
}}
else
{
if($_GET[id])
{
$getshout = mysql_query("select * from shouts where id = '$_GET[id]' limit 1");
$shout = mysql_fetch_array($getshout);
if($logged[username] && $logged[username] == $shout[user]|| $logged[username] && $logged[level] >= 8)
{
$delete = mysql_query("delete from shouts where id = '$_GET[id]'");
echo("<div style=\"background: #d7d7d7\">Shout Deleted</div>");
}
}
if($_POST[shout])
{
if($logged[username] && $logged[level] >=1)
{
if(isset($_POST[message]))
{
$last = mysql_query("Select * from shouts order by id desc limit 1");
$last = mysql_fetch_array($last);
$message = htmlspecialchars("$_POST[message]");
if($last[shout] != $message)
{
if($_POST[url] == URL||$_POST[url]==NULL)
{
$insert = mysql_query("insert into `shouts` (`url`, `shout`, `time`, `ip`, `user`) VALUES ('na', '$message', NOW(), '$REMOTE_ADDR', '$logged[username]')");
echo("<meta http-equiv=\"Refresh\" content=\"0; URL=shouts.php\"/>");
}
else
{
$url = htmlspecialchars(addslashes("$_POST[url]"));
setcookie("url", $url,time()+(60*60*24*19), "/", ""); 
$insert = mysql_query("insert into `shouts` (`url`, `shout`, `time`, `ip`, `user`) VALUES ('$url', '$message', NOW(), '$REMOTE_ADDR', '$logged[username]')");
echo("<meta http-equiv=\"Refresh\" content=\"0; URL=shouts.php\"/>");
}}
else
{
echo("<div style=\"background: #d7d7d7;\"><b>No Spam</b></div>");
}
}
}
else
{
echo("You are not logged in!");
}
}
if($_GET[archive] == yes)
{
$color2 = "#e6e6e6";
$color1 = "#f1f1f1";
$row_count = 0;
$getshouts = mysql_query("select * from shouts order by id desc limit 150");
echo("<div style=\"background: #e6e6e6\" align=\"center\"><b>Last 150 Shouts</b></div>");
while($shouts = mysql_fetch_array($getshouts))
{
$shouts[shout] = $bbcode->parse($shouts[shout]);
$shouts[shout] = str_replace("&amp;", "&", $shouts[shout]);
$row_color = ($row_count % 2) ? $color1 : $color2; 
$row_count++;
echo("<div style=\"width: 100%; wordwrap: break-word; background: $row_color; padding: 2px;\">");
if($shouts[url] != na)
{
echo("<a href=\"$shouts[url]\" target=\"_BLANK\">");
}
echo("<b style=\"color: #808080; margin: 1px;\">$shouts[user]</b>");

if($shouts[url] != na)
{
echo("</a>");
}

if($logged[username] && $logged[username] == $shouts[user]|| $logged[username] && $logged[level] >= 8)
{
echo("&nbsp; <a href=\"?id=$shouts[id]\">(Delete)</a>");
}
echo("<br />
$shouts[shout]</div>");
}
}
else
{
$color2 = "#e6e6e6";
$color1 = "#f1f1f1";
$row_count = 0;
$getshouts = mysql_query("select * from shouts order by id desc limit 10");
while($shouts = mysql_fetch_array($getshouts))
{
$shouts[shout] = $bbcode->parse($shouts[shout]);
$shouts[shout] = str_replace("&amp;", "&", $shouts[shout]);
$row_color = ($row_count % 2) ? $color1 : $color2; 
$row_count++;
echo("<div style=\"width: 100%; wordwrap: break-word; background: $row_color; padding: 2px;\">");
if($shouts[url] != na)
{
echo("<a href=\"$shouts[url]\" target=\"_BLANK\">");
}
echo("<b style=\"color: #808080; margin: 1px;\">$shouts[user]</b>");

if($shouts[url] != na)
{
echo("</a>");
}

if($logged[username] && $logged[username] == $shouts[user]|| $logged[username] && $logged[level] >= 8)
{
echo("&nbsp; <a href=\"?id=$shouts[id]\">(Delete)</a>");
}
echo("<br />
$shouts[shout]</div>");
}}
$row_color = ($row_count % 2) ? $color1 : $color2; 
echo("<div style=\"background: $row_color\" align=\"center\" align=\"center\"><a href=\"?archive=yes\">Archive</a></div>");
}
?>
</body></html>