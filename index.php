<?
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
include("config.php");
include("includes/funcs.php");
include("includes/stats.php");
include ("includes/ubb.php");
$bbcode = new ubbParser();
$settings = mysql_query("select * from settings where id = '1'");
$settings = mysql_fetch_array($settings);
if($logged[username])
{
$query = mysql_query("update members set pages = pages+1 where username = '$logged[username]'");
}
?>
<?php
$ip = $_SERVER['REMOTE_ADDR'];
$getbanned = mysql_query("select * from ipban where ip = '$ip'");
$bannednum = mysql_num_rows($getbanned);
if($bannednum >= 1)
{
$bannedinfo = mysql_fetch_array($getbanned);
echo("<div align=\"center\"><b>You Are Banned!</b><br />
You have been banned for the following reason:<br />
<i>$bannedinfo[reason]</b></div>");
}
else
{
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?
echo("<title>$settings[title]</title>");
?>
<style type="text/css">
<? echo("$settings[css]"); ?>
</style>
<script type="text/javascript">
function sendtext(e, text)
{
e.value += text
}
function showDiv(id)
	{
		var item = null;
		item = document.getElementById(id);
		
		if (item.style.display == "none")
		{
			item.style.display = "";
		}
		else
		{
			item.style.display = "none";
		}
	}
</script>
<!-- Showdiv code borrowed from zulumonkey -->
</head>
<body>
<div id="wrapper">
	
<div class="bwrap" width="100%"><div class="banner"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td><a href="?view=home"><img border="0" src="images/bluebanner.jpg" width="200" height="70"></a>
</td><td width="234"><?
echo("$settings[ad1]");
?></td></tr></table></div><div class="bar"><? include("includes/login2.php"); ?></div></div>
	  <table cellspacing="0" cellpadding="0" border="0" width="100%" height="471">
	<tr>
	  <td width="205" valign="top">

<div class="bwrap">
<div class="title">Navigation</div>
<div class="bar">Main Navigation</div>
<div class="content">
&nbsp;<a href="?view=home">Home</a><br />
&nbsp;<a href="?view=affiliates">Affiliates</a><br />
&nbsp;<a href="?view=contact">Contact</a>
</div>

<div class="bar">Interaction</div>
<div class="content">
&nbsp;<a href="?view=forum">Forum</a><br>
&nbsp;<a href="?view=topsites">Topsites</a></div>
<div class="bar">Tutorials</div>
<div class="content">
<? 
$getcats = mysql_query("SELECT * from tutcat order by 'id' asc");
echo("&nbsp;<a href=\"?view=submittutorial\">Submit Tutorial</a><br />");
while($tutcat = mysql_fetch_array($getcats))
{
echo("&nbsp;<a href=\"?view=tutorials&act=cat&id=$tutcat[id]\">$tutcat[title]</a><br />");
}
?>
</div>

<div class="bar">Downloads</div>
<div class="content">
<? 
$getcats = mysql_query("SELECT * from dlcat order by 'id' asc");
echo("&nbsp;<a href=\"?view=submitdl\">Submit Download</a><br />");
while($dlcat = mysql_fetch_array($getcats))
{
echo("&nbsp;<a href=\"?view=downloads&act=cat&id=$dlcat[id]\">$dlcat[title]</a><br />");
}
?>
</div>
</div>

<div class="bwrap">
<div class="title">Random Affiliates</div>
<div class="content">
<?
include("includes/random.php");
?>
</div>
<div class="bar" align="center"><a href="?view=affiliates">Apply / View All</a></div>
</div>

<div class="bwrap">
<div class="title">Stats</div>
<div class="bar">Member Stats</div>
<div class="content">
Members: <?
$get = mysql_query("SELECT * from members");
$memnum = mysql_num_rows($get);
echo("$memnum");
$latestmem = mysql_query("SELECT * from members order by 'id' desc limit 1");
$latestmem = mysql_fetch_array($latestmem);
?><br />Latest Member: <? echo("$latestmem[username]");
$gettopics = mysql_query("select * from posts where iftopic = 'yes'");
$gettopics = mysql_num_rows($gettopics);
$getreplies = mysql_query("select * from posts where iftopic = 'no'");
$getreplies = mysql_num_rows($getreplies);
$getall = mysql_query("select * from posts");
$getall = mysql_num_rows($getall);
?></div>
<div class="bar">People Online</div>
<div class="content">
<? include("includes/online.php"); ?></div>
	<div class="bar">
	Forum Stats</div><div class="content">
	Posts: <? echo("$getall"); ?><br />
	Topics: <? echo("$gettopics"); ?><br />
	Replies: <? echo("$getreplies"); ?></div>
	<?
	$pendingaff = mysql_query("select * from affiliates where active = 'no'");
	$pendingaff = mysql_num_rows($pendingaff);
	$pendingtut = mysql_query("select * from tutorials where active = 'no'");
	$pendingtut = mysql_num_rows($pendingtut);
	$pendingdl = mysql_query("select * from downloads where active = 'no'");
	$pendingdl = mysql_num_rows($pendingdl);
	if($logged[username] && $logged[level] == 9)
	{
	echo("<div class=\"bar\">Admin Stats</div><div class=\"content\">Pending Affiliates: $pendingaff<br />Pending Tutorials: $pendingtut<br />Pending Downloads: $pendingdl</div>");
	}
	?>
</div>
&nbsp;</td>
	  <td width="5"></td>
	  <td valign="top" align="left"><?
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
include("includes/newforum.php");
break;
case 'admin':
include("includes/admin.php");
break;
case 'contact':
include("includes/contact.php");
break;
case 'cpanel':
include("includes/cpanel.php");
break;
case 'members':
include("includes/members.php");
break;
case 'topsites':
include("includes/topsites.php");
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
case 'tutorials':
include("includes/tutorial.php");
break;
case 'downloads':
include("includes/downloads.php");
break;
case 'admin2':
include("includes/admin2.php");
break;
case 'submittutorial':
include("includes/submittut.php");
break;
case 'submitdl':
include("includes/submitdl.php");
break;
case 'resetpass':
include("includes/passreset.php");
break;
case 'bbcode':
boxtop("BBCode");
echo("[b]Bold Text[/b] -- This makes text bold (Bold Text)<br />
[i]Italic Text[/i] -- This makes text italic (<i>Italic Text</i>)<br />
[u]Underlined Text[/u] -- This makes text underlined(<u>Underlined Text</u>)<br />
[s]Struck Through Text[/s] - This makes text struck through(<s>Stuck 
Through Text</s>)<br />
1[sup]st[/sup] -- This makes text superscript (1<sup>st</sup>)<br />
H[sub]2[/sub]O -- This makes text subscript (H<sub>2</sub>O)<br />
[code]&lt;HTML&gt; Code Here[/code] -- Used for displaying code<br />
[php]&lt;? echo(&quot;This is php code&quot;); ?&gt;[/php] -- Used for displaying php 
code<br />
[quote by=username]I said this![/quote] -- Used to display what other 
people said<br />
[img]http://syk3.com/button.gif[/img] -- Used to display an image<br />
[url=http://syk3.com]Awesome Site[/url] -- Used to display a link (<a href=\"http://syk3.com\">Awesome 
Site</a>)<br />
[url]http://syk3.com[/url] -- Another way to display a link (<a href=\"http://syk3.com\">http://syk3.com</a>)<br />
");
endbox();
break;
}
?><table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td valign="top" width="400">
			<div class="bwrap"><div class="title"><? if($logged[username] && $logged[level] >= 7)
			{
			?><a style="color: #eeeeee;" href="javascript:showDiv('shoutbox'); javascript:showDiv('adminshout')">
			<?
			}
			?>Shoutbox<?
			if($logged[username] && $logged[level] >=7)
			{
			echo("</a>");
			}
			?></div><div id="shoutbox" class="content"><? 
			include("shout.php");
			?></div></div>
			<?
			if($logged[username] && $logged[level] >=7)
			{
			?>
	<div class="bwrap"><div class="title">
			<? if($logged[username] && $logged[level] >= 7)
			{
			?>
			<a style="color: #eeeeee;" href="javascript:showDiv('shoutbox'); javascript:showDiv('adminshout')">
			<?
			}
			?>Staff Shoutbox<?
			if($logged[username] && $logged[level] >=7)
			{
			echo("</a>");
			}
			?> (Click to toggle)</div><div id="adminshout" style="display:none;" class="content"><? 
			include("123adminshout.php");
			?></div></div>
			<?
			}
			?>
					</td>
			<td valign="top">
			<?
			boxtop("Recent Comments");
			$color2 = "#e6e6e6";
			$color1 = "#f1f1f1";
			$row_count = 0;
			$getcomments = mysql_query("Select * from tutcomments order by id desc limit 8");
			while($comments=  mysql_fetch_array($getcomments))
			{
			$row_color = ($row_count % 2) ? $color1 : $color2; 
			$row_count++;
			$gettutorial = mysql_query("Select * from tutorials where id = '$comments[tut]'");
			$tutorial = mysql_fetch_array($gettutorial);
			$numbefore = mysql_query("select * from tutcomments where tut =  '$tutorial[id]' AND id <= '$comments[id]'");
			$numbefore = mysql_num_rows($numbefore);
			$pagenum = $numbefore / 16;
			$pagenum = ceil($pagenum);
			echo("<div style=\"background: $row_color\">Comment by <a href=\"?view=members&user=$comments[author]\">$comments[author]</a><br />
			&nbsp; In <i><a href=\"?view=tutorials&act=tutorial&id=$tutorial[id]&page=$pagenum#comment$comments[id]\">$tutorial[title]</a></i></div>");
			}
			endbox();
			?>
</td>



	<td width="205" valign="top">

<div class="bwrap">
<div class="title">New Tutorials</div>
<div class="content">
<?
$tutnum = 0;
$gettuts = mysql_query("SELECT * from tutorials where active = 'yes' order by 'id' desc limit 7");
while($tuts = mysql_fetch_array($gettuts))
{
if ($tutnum == 0)
{
echo("<a href=\"?view=tutorials&act=tutorial&id=$tuts[id]\">$tuts[title]</a>");
}
else
{
echo("<br /><a href=\"?view=tutorials&act=tutorial&id=$tuts[id]\">$tuts[title]</a>");
}
$tutnum = $tutnum +1;
}
?></div>
</div>
<div class="bwrap">
<div class="title">Top Topsites</div>
<div class="content">
<?
$getsites = mysql_query("SELECT * from topsites order by votes desc limit 5");
while($sites = mysql_fetch_array($getsites))
{
echo("<a href=\"out.php?mode=topsites&id=$sites[id]\">$sites[title]</a><br />");
}
?><a href="?view=topsites">Add Your Site</a></div>
</div>

<div class="bwrap">
<div class="title">Poll</div>
<?
$getq1 = mysql_query("SELECT * from poll ORDER BY id DESC limit 1");
    $getq = mysql_fetch_array($getq1);
    echo("<div class=\"bar\"><center>$getq[question]</center></div>");
    ?>
<div class="content">    <?
    //fill in the color variable with a hex code for a color.
    $color = "#4E7EB0";
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
&nbsp;</td>
					</tr>
		</table>
</td>

	</tr>
</table></div><div align="center">© 2005 Syk3.com</div><br />
	</body>
</html>
<?
}
?>