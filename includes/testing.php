<?
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
include("config.php");
include("includes/funcs.php");
include("includes/stats.php");
?>
<html>
<head>
<title>TechTuts.com -- Under Construction</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
body 
{
    margin: 0px;
    padding: 0px;
    font-family: "Lucida Grande",verdana, arial, sans-serif;
    font-size: 11px;
    background: #d7d7d7;
}
div, table
{
  font-family: "Lucida Grande",verdana, arial, sans-serif;
    font-size: 11px;
}

#wrapper 
{
    width: 99%;
    background: #d7d7d7;
    margin-left: auto;
    margin-right: auto;
    padding: 10px;
}
#main 
{
    margin: 0px 10px 20px 225px;
    background: #d7d7d7;
    padding: 1px;
    top: 137px;
    border: 0px solid silver;
    width: auto;
}

#left 
{
    float: left;
    width: 200px;
    top: 137px;
    left: 0px;
    padding: 1px;
    margin: 5px;
    margin-top: 0px;
    border: 0px solid silver;
    width: 200px;
    clear: both;
}

.title
{
    color: #EEE;
    background: #54646F;
    padding: 4px;
    margin: 0px;
    
}
.content
{
    background: #f1f1f1;
    padding: 3px 3px 3px 3px;
    margin: 0px;
    color: #6c6c6c;      
}
.bar
{
    color: #808080;
    background: #DDD;
    padding: 3px 20px 3px 3px;
    margin: 1px;
}

.nbar
{
    padding: 4px;
    margin: 0px;
    color: #AAA;
    border: solid 0px silver;
    margin-left: 10px;
    margin-right: 10px;
    margin-bottom: 10px;
}
.ubar
{
    background: #efefef;
    padding: 4px;
    margin: 3px;
    color: #AAA;
    border: solid 1px silver;
    margin-left: 12px;
    margin-right: 10px;
    margin-bottom: 15px;
}
a
{
    text-decoration: none;
    color: #6c6c6c;
}
a:active, 
a:hover
{
    text-decoration: underline;
    color: #A1A1A1;
}
.bwrap
{
    border: solid 1px #808080;
    background: #efefef;
    padding: 1px 1px 1px 1px;
    margin: 5px 5px 5px 5px;
}
   
</style>

</head>
<body>
<!--Layout by KryptosCodes.com-->
<table border="0" width="100%" cellpadding="0" id="table1">
	<tr>
		<td width="10">&nbsp;</td>
		<td colspan="2">
		<div class="bwrap"><div class="title">Banner Here</div><div class="bar">
		<?
		include("includes/login2.php");
		?></div></div>
		</td>
		<td width="10">&nbsp;</td>
	</tr>
	<tr>
		<td width="10">&nbsp;</td>
		<td width="200" valign="top"><div class="bwrap">
<div class="title">Navigation</div>
<div class="bar">Main Navigation</div>
<div class="content"><a href="?view=home">Home</a><br />
	<a href="?view=affiliates">Affiliates</a><br />
	<a href="?view=contact">Contact</a>
	</div>
	<div class="bar">Interaction</div>
<div class="content">
<a href="?view=forum">Forum</a><br />
Topsites<br />
Submit Content</div>
<div class="bar">Tutorials</div>
<div class="content">
PHP<br />
Photoshop<br />
CSS<br />
JavaScript<br />
Fireworks<br />
Other</div>
<div class="bar">Members</div>
<div class="content">
<? if (!$logged[username])
{
echo("<a href=\"?view=register\">Register</a><br />");
}
?>
Member List<br />
Staff List<br />
</div>
</div>
<div class="bwrap">
<div class="title">Random Affiliates</div>
<div class="content"><?PHP
include("includes/random.php");
?></div>
</div>
<div class="bwrap">
<div class="title">New Tutorials</div>
<div class="content">
Latest tutorials go here</div>
</div>
<div class="bwrap">
<div class="title">Poll</div>
<?
$getq1 = mysql_query("SELECT * from poll ORDER BY id DESC limit 1");
    $getq = mysql_fetch_array($getq1);
    echo("<div class=\"bar\">$getq[question]</div>");
    ?>
<div class="content">    <?
    //fill in the color variable with a hex code for a color.
    $color = "#54646F";
    //For the lines below, use the following stuff...
    //if you want to record peoples usernames when they vote..change $remote_addr to a variable with the user's
    //username.  $remote_addr is the users Ip address.
    $ident = "$logged[username]";
    //change the following line to where u want the user to be redirected
    //after they vote
    $redir = "?view=home";
    include ("config.php"); // the page that connects to the database
        $checkvoted = mysql_query("SELECT * from poll_votes where ident = '$ident' AND poll_id = '$getq[id]'");
    $voted = mysql_num_rows($checkvoted);
    if ($voted == 0 && !$_POST[pollsubmit])
    {
    $getchoice1 = mysql_query("SELECT * from poll_choices WHERE pollid = '$getq[id]' order by 'id' asc");
    echo ("
    <table border=\"0\" align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><form method=\"POST\">
    ");
    while ($choices = mysql_fetch_array($getchoice1)){
    echo ("<tr>
    <td width=\"10\"><input type=\"radio\" value=\"$choices[id]\" name=\"choice\"></td><td align=\"left\">$choices[text]</td></tr>");
    }
    echo ("
    <tr>
    <td align=\"center\" colspan=\"2\"><input type=\"submit\" value=\"Submit\" name=\"pollsubmit\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #000000; background-color: #E6E6E6\">
    </td>
    </form>
    </tr>
    </table>");
    }
    if ($_POST[pollsubmit] && $voted == 0)
    {
    $pchoiceq= mysql_query("SELECT * from poll_choices WHERE pollid = '$getq[id]' order by 'id' asc");
    while ($pchoices = mysql_fetch_array($pchoiceq)){
    $choiceid = $pchoices[id];
    if ($_POST[choice] == $choiceid && $voted == 0)
    {
    $update_votes = mysql_query("Update poll_choices set votes = votes + 1 where id = '$choiceid'");
    $fvote = mysql_query("INSERT INTO `poll_votes` ( `poll_id`, `ident`, `choice`, `date` )VALUES ('$getq[id]', '$ident', '$choiceid', NOW())");

    echo ("<meta http-equiv=\"Refresh\" content=\"0; URL=$redir\"/>Thank You for voting!");
    }
    }
    }
    if ($voted == 1)
    {
    $totalvotes = 0;
    $getnumvotes = mysql_query("SELECT * from poll_choices where pollid = '$getq[id]' order by 'id' asc");
    while($votes = mysql_fetch_array($getnumvotes))
    {
    $totalvotes = $totalvotes + $votes[votes];
    }

    $getpoll1  = mysql_query("SELECT * from poll_choices where pollid = '$getq[id]' order by 'id' asc");
    while ($getpoll = mysql_fetch_array($getpoll1))
    {
    $numvotes = $getpoll[votes] / $totalvotes;
    $numvotes2 = $numvotes * 100;
    echo ("$getpoll[text]");
    $round = ceil($numvotes2);
    echo ("  ($getpoll[votes] votes)
    <table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    <tr>
    <td style=\"border: 1px solid #808080\">
    <table width=\"$numvotes2%\" cellspacing=\"1\"  cellpadding=\"0\">
    <tr>
    <td bgcolor=\"$color\" height=\"5\"> </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    ");
    }
    }
    ?>
</div>
</div>
<div class="bwrap">
<div class="title">Stats</div>
<div class="content">Members: <?
$get = mysql_query("SELECT * from members");
$memnum = mysql_num_rows($get);
echo("$memnum");
?><br />
	Unique Hits: <? $get = mysql_query("SELECT * from ip");
	$ipnum = mysql_num_rows($get);
	echo("$ipnum");
	?>
	<br />
	Unique Today: <? $get1 = mysql_query("SELECT * from stats order by id desc limit 1");
	$array = mysql_fetch_array($get1); 
	echo("$array[total]");
	?><br /> 
	Hits: <? $gettotal = mysql_query("select * from stats");
	$i = 0;
	while($num = mysql_fetch_array($gettotal))
	{
	$i = $i + $num[hits];
	}
	echo ("$i");
	?><br />
	Hits Today: <? echo("$array[hits]"); ?></div>
</div>

</td>
		<td valign="top">
<?
ob_start();
switch("$_GET[view]")
{
default:
include("includes/news.php");
break;
case 'register':
include("includes/register.php");
break;
case 'forum':
include("includes/forum.php");
break;
case 'admin':
include("includes/admin.php");
break;
case 'contact':
include("includes/contact.php");
break;
case 'logout':
ob_start();
setcookie("id", asdfasdfafdsadsf,time()+(60*60*24*5), "/", "");
setcookie("pass", asdfgasdfgadsgagds,time()+(60*60*24*5), "/", "");
boxtop("Thank You");
echo ("You are now logged out!");
endbox();
break;
case 'affiliates':
include("includes/affiliates.php");
break;
}
?>&nbsp;</td>
		<td width="10">&nbsp;</td>
	</tr>
</table>

</body>
</html>