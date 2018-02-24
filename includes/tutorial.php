<?
if($_GET[page])
{
$page = $_GET[page];
}
else
{
$page = "1";
}
switch($_GET[act])
{
case 'tutorial':
if (!$_GET[id])
{
boxtop("Error");
echo("You selected an invalid tutorial!  Please go back and try again");
endbox();
}
else
{
$getut = mysql_query("SELECT * from tutorials where id = '$_GET[id]'");
$numtut = mysql_num_rows($getut);
if ($numtut == 0)
{
boxtop("Error");
echo("You selected an invalid tutorial!  Please go back and try again");
endbox();
}
else
{
$update = mysql_query("update tutorials set views = views+1 where id = '$_GET[id]'");
$tut = mysql_fetch_array($getut);
if(!$_POST[comment])
{
boxtop("$tut[title]");
$tut[text] = $bbcode->parse($tut[text]);
$tut[text] = str_replace("&amp;", "&", $tut[text]);
$tut[text] = str_replace("[img]", "<img border='0' src='", $tut[text]);
$tut[text] = str_replace("[/img]", "' />", $tut[text]);
$tut[text] = str_replace("<code>", "", $tut[text]);
$tut[text] = str_replace("</code>", "", $tut[text]);

echo("$tut[text]");
endbox();
boxtop2("Comments");
echo("<a name=\"comments\"></a>");
$max_results = 16;
$from = (($page * $max_results) - $max_results); 
$getcomments = mysql_query("Select * from tutcomments where tut = '$_GET[id]' order by 'id' asc limit $from, $max_results");
$commentsnum = mysql_num_rows($getcomments);
if($commentsnum == 0)
{
bar("No Comments!");
echo("There are currently 0 comments!  Please feel free to add one using the form below.");
}
else
{
$numba = 1;
while($comment = mysql_fetch_array($getcomments))
{
$query = mysql_query("SELECT DATE_FORMAT(tutcomments.date, '%W, %M %d, %Y at %l:%i %p EST') as vtime FROM tutcomments where id = '$comment[id]'");
$row = mysql_fetch_array($query);
if($numba == 1)
{
bar("<img src=\"images/down.gif\" style=\"margin-top: 1px;\" border=\"0\" alt=\"Down arrow..lol\" /> Posted on $row[vtime]");
}
else
{
bar2("<img src=\"images/down.gif\" style=\"margin-top: 1px;\" border=\"0\" alt=\"Down arrow..lol\" /> Posted on $row[vtime]");
}
$posts2 = mysql_query("SELECT * from posts where user = '$comment[author]'");
$posts2 = mysql_num_rows($posts2);
if ($posts2 <= 10)
{
$status = "Newbie";
}
if ($posts2 >= 11 && $posts2 < 50)
{
$status = "Junior Member";
}
if ($posts2 >= 50 && $posts2 < 100)
{
$status = "Active Member";
}
if ($posts2 >= 100 && $posts2 < 200)
{
$status = "Senior Member";
}
if ($posts2 >= 200 && $posts2 < 250)
{
$status = "1337 Member";
}
if ($posts2 >= 250 && $posts2 < 500)
{
$status = "Uber 1337 Member";
}
$comment[comment] = $bbcode->parse($comment[comment]);
$comment[comment] = str_replace("&amp;", "&", $comment[comment]);
$comment[comment] = str_replace("[img]", "<img border='0' src='", $comment[comment]);
$comment[comment] = str_replace("[/img]", "' />", $comment[comment]);
$comment[comment] = str_replace("<code>", "", $comment[comment]);
$comment[comment] = str_replace("</code>", "", $comment[comment]);

$user = mysql_query("SELECT * from members where username = '$comment[author]'");
$user = mysql_fetch_array($user);
$userposts = mysql_query("SELECT * from posts where user = '$comment[author]'");
$userposts = mysql_num_rows($userposts);
echo <<<EOF
<a name="comment$comment[id]"></a><table border="0" width="100%" cellspacing="0" cellpadding="2" id="table1" bgcolor="#FFFFFF">
	<tr>
		<td valign="top" bgcolor="#EFEFEF" width="125" align="center" style="border-right: 1px dotted #808080;"><b><a href="?view=members&user=$comment[author]">$comment[author]</a></b><br /><i>$status</i><br />

EOF;

avatar("$comment[author]");

echo <<<EOF
<br />Group: $user[leveltext]<br />Posts: $userposts<br />
EOF;
if($logged[username] == $comment[author]|| $logged[username] && $logged[level] >= 8)
{
echo("<a href=\"?view=tutorials&act=editcomment&id=$comment[id]\">Edit</a>");
}
if($logged[username] && $logged[level] >= 8)
{
echo(" &nbsp; <a href=\"?view=tutorials&act=deletecomment&id=$comment[id]\">Delete</a>");
}
echo <<<EOF
</td>
		<td bgcolor="#EFEFEF" valign="top">$comment[comment]</td>
	</tr>
</table>
EOF;

$numba++;
}
}
bar2("Comment Pages");
$total_results1 = mysql_query("SELECT * from tutcomments where tut = '$_GET[id]'");
$total_results = mysql_num_rows($total_results1);
$total_pages = ceil($total_results / $max_results); 
for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "<u>$i</u> &nbsp;";
        } else {
            echo "<a href=\"?view=tutorials&act=tutorial&id=$_GET[id]&page=$i#comments\">$i</a> &nbsp;";
    }
}

bar2("Post a comment");
if($logged[username])
{
echo <<<EOF
	<form name="message" method="POST" style="margin: 0px">
<dl style="margin: 0px">
<dt>Smilies</dt>
<dd><img border="0" onClick="sendtext(document.message.message, ':)')" src="images/smilies/smile.gif" width="20" height="20">
<img onClick="sendtext(document.message.message, ':p')" border="0" src="images/smilies/tongue.gif" width="20" height="20">
<img onClick="sendtext(document.message.message, ':o')" border="0" src="images/smilies/ohmy.gif" width="20" height="20">
<img onClick="sendtext(document.message.message, ':huh?:')" border="0" src="images/smilies/huh.gif" width="20" height="20">
<img onClick="sendtext(document.message.message, ':C')" border="0" src="images/smilies/angry.gif" width="20" height="20">
<img border="0" onClick="sendtext(document.message.message, ':D')" src="images/smilies/biggrin.gif" width="20" height="20">
<img onClick="sendtext(document.message.message, ':lol:')" border="0" src="images/smilies/laugh.gif" width="20" height="20">
<img border="0"  onClick="sendtext(document.message.message, ':shades:')" src="images/smilies/cool.gif" width="20" height="20">
<img border="0" onClick="sendtext(document.message.message, ':blink:')" src="images/smilies/blink.gif" width="20" height="20">
<img border="0" onClick="sendtext(document.message.message, ':blush:')" src="images/smilies/blush.gif" width="20" height="20">
<img border="0" onClick="sendtext(document.message.message, '!!')" src="images/smilies/!!.gif" width="20" height="20"></dd>
<dt>Post Box</dt>
<dd>
<textarea rows="7" name="message" cols="93" style="border: 1px solid #808080; background-color: #EFEFEF; font-family:Verdana; color:#000000; font-size:10px"></textarea><br>
	<input type="submit" style="border: 1px solid #808080; background-color: #e6e6e6; margin-top: 2px;" value="Submit" name="comment">
</dd></dl>
</form>
EOF;
endbox();
}
else
{
echo("You cannot post a comment!  You must login first.");
endbox();
}
}
else
{
$message = htmlspecialchars($_POST[message]);
$insert = mysql_query("INSERT INTO `tutcomments` ( `tut` , `author` , `comment`, `date` )
VALUES (
'$_GET[id]', '$logged[username]', '$message', NOW())");
boxtop("Thank You!");
echo("We are now redirecting you.... <meta http-equiv=\"Refresh\" content=\"0; URL=?view=tutorials&act=tutorial&id=$_GET[id]\"/>");
endbox();
}
}
}
break;
default:
$color2 = "#e6e6e6";
$color1 = "#efefef";
$row_count = 0; 
$getcats = mysql_query("SELECT * from tutcat order by 'id' asc");
boxtop2("Select a Category");
bar("<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
		<td>Category Name</td>
		<td width=\"75\"># Tutorials</td>
		<td align=\"center\" width=\"175\">Latest Tutorial</td>
	</tr>
	</table>
");
echo("<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">");
while ($cats = mysql_fetch_array($getcats))
{
$row_color = ($row_count % 2) ? $color1 : $color2; 
$row_count++; 
$latest = mysql_query("SELECT * from tutorials where cat = '$cats[id]' and active = 'yes' order by 'id' desc limit 1");
$latestnum = mysql_num_rows($latest);
$numtuts = mysql_query("Select * from tutorials where active = 'yes' AND cat = '$cats[id]'");
$numtuts = mysql_num_rows($numtuts);
echo("
	<tr>
		<td bgcolor=\"$row_color\"><a href=\"?view=tutorials&act=cat&id=$cats[id]\">$cats[title]</a></td>
		<td bgcolor=\"$row_color\" width=\"75\">&nbsp;&nbsp; $numtuts</td>
		<td bgcolor=\"$row_color\" width=\"175\">");
		if ($latestnum == 0)
		{
		echo("No Tutorials Yet");
		}
		else
		{
		$latest = mysql_fetch_array($latest);
		echo("<a href=\"?view=tutorials&act=tutorial&id=$latest[id]\">$latest[title]</a>");
		}
		echo("</td>
	</tr>");
}
echo("</table");
endbox();
break;
case 'cat':
$color2 = "#e6e6e6";
$color1 = "#efefef";
$row_count = 0; 
if (!$_GET[id])
{
boxtop("Error");
echo("You have selected an invalid tutorial category!  Please go back and try again");
endbox();
}
else
{
$getcat = mysql_query("SELECT * from tutcat where id = '$_GET[id]'");
$catnum = mysql_num_rows($getcat);
if ($catnum == 0)
{
boxtop("Error");
echo("You have selected an invalid tutorial category!  Please go back and try again");
endbox();
}
else
{
$cat = mysql_fetch_array($getcat);
$gettut = mysql_query("SELECT * from tutorials where cat = '$cat[id]' and active = 'yes' order by 'id' desc");
$numtuts = mysql_num_rows($gettut);
if ($numtuts == 0)
{
boxtop("$cat[title] Tutorials");
echo("There are currently 0 tutorials in this category!");
endbox();
}
else
{
$color2 = "#e6e6e6";
$color1 = "#efefef";
$row_count = 0;
boxtop2("$cat[title] Tutorials");
echo("<div class=\"bar\"><table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
		<td>Tutorial Name and Description</td>
		<td width=\"100\"># Views</td>
		<td width=\"125\">Author</td>
	</tr>
	</table></div>
");
echo("<div class=\"content\">
<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">");

while($tuts = mysql_fetch_array($gettut))
{
$row_color = ($row_count % 2) ? $color1 : $color2; 
$row_count++; 
echo ("
<tr>
		<td bgcolor=\"$row_color\"><a href=\"?view=tutorials&act=tutorial&id=$tuts[id]\">$tuts[title]</a></td>
		<td bgcolor=\"$row_color\" rowspan=\"2\" width=\"100\">&nbsp;$tuts[views]</td>
		<td bgcolor=\"$row_color\" rowspan=\"2\" width=\"125\">$tuts[author]</td>
	</tr>
	<tr>
	<td bgcolor=\"$row_color\">&nbsp;&nbsp;$tuts[desc]</td></tr>");
}
echo("</table>");
endbox();
}
}}
break;
case 'editcomment':
if($_GET[id])
{
$getcomment = mysql_query("select * from tutcomments where id = '$_GET[id]'");
$comment = mysql_fetch_array($getcomment);
if($logged[username] && $logged[username] == $comment[author] || $logged[username] && $logged[level] >= 8)
{
if(!$_POST[comment])
{
boxtop("Update Comment");
echo <<<EOF
	<form name="message" method="POST" style="margin: 0px">
<dl style="margin: 0px">
<dt>Smilies</dt>
<dd><img border="0" onClick="sendtext(document.message.message, ':)')" src="images/smilies/smile.gif" width="20" height="20">
<img onClick="sendtext(document.message.message, ':p')" border="0" src="images/smilies/tongue.gif" width="20" height="20">
<img onClick="sendtext(document.message.message, ':o')" border="0" src="images/smilies/ohmy.gif" width="20" height="20">
<img onClick="sendtext(document.message.message, ':huh?:')" border="0" src="images/smilies/huh.gif" width="20" height="20">
<img onClick="sendtext(document.message.message, ':C')" border="0" src="images/smilies/angry.gif" width="20" height="20">
<img border="0" onClick="sendtext(document.message.message, ':D')" src="images/smilies/biggrin.gif" width="20" height="20">
<img onClick="sendtext(document.message.message, ':lol:')" border="0" src="images/smilies/laugh.gif" width="20" height="20">
<img border="0"  onClick="sendtext(document.message.message, ':shades:')" src="images/smilies/cool.gif" width="20" height="20">
<img border="0" onClick="sendtext(document.message.message, ':blink:')" src="images/smilies/blink.gif" width="20" height="20">
<img border="0" onClick="sendtext(document.message.message, ':blush:')" src="images/smilies/blush.gif" width="20" height="20">
<img border="0" onClick="sendtext(document.message.message, '!!')" src="images/smilies/!!.gif" width="20" height="20"></dd>
<dt>Post Box</dt>
<dd>
<textarea rows="7" name="message" cols="93" style="border: 1px solid #808080; background-color: #EFEFEF; font-family:Verdana; color:#000000; font-size:10px">$comment[comment]</textarea><br>
	<input type="submit" style="border: 1px solid #808080; background-color: #e6e6e6; margin-top: 2px;" value="Submit" name="comment">
</dd></dl>
</form>
EOF;
endbox();
}
else
{
$comment = htmlspecialchars("$_POST[message]");
$update = mysql_query("update tutcomments set comment = '$comment' where id = '$_GET[id]'");
$getcomment = mysql_query("Select * from tutcomments where id = '$_GET[id]'");
$getcomment = mysql_fetch_array($getcomment);
boxtop("Comment Updated");
echo("Redirecting....");
echo("<meta http-equiv=\"Refresh\" content=\"0; URL=?view=tutorials&act=tutorial&id=$getcomment[tut]\"/>");

endbox();
}
}
}
else
{
boxtop("Error");
echo("Invalid Comment");
endbox();
}
break;
case 'deletecomment':
if($logged[username] && $logged[level] >= 8)
{
if($_GET[id])
{
boxtop("Comment Deleted");
echo("The comment you selected has been deleted");
endbox();
$delete = mysql_query("delete from tutcomments where id = '$_GET[id]'");
}
else
{
boxtop("Error");
echo("Invalid Comment");
endbox();
}
}
break;
}
?>