<?
if(!$_GET[page]){
    $page = 1;
} else {
    $page = $_GET[page];
}
switch($_GET[act])
{
default:
$getcat = mysql_query("SELECT * from forum_cats order by 'order' asc");
while($cat = mysql_fetch_array($getcat))
{
boxtop2("$cat[title]");
bar("<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">
<tr>
<td height=\"8\">Forum Name</td><td height=\"8\" width=\"75\" align=\"center\">Topics</td><td height=\"8\" width=\"75\" align=\"center\">Replies</td>
<td width=\"125\" align=\"center\" height=\"8\">Last Post</td>
</tr>
</table>");
$getforum = mysql_query("SELECT * from forum_forums where parent = '$cat[id]' order by 'order' asc");
echo("<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width=\"100%\">");
while($forums = mysql_fetch_array($getforum))
{
$topics  = mysql_query("SELECT * from posts where forum = '$forums[id]' and iftopic = 'yes'");
$topics = mysql_num_rows($topics);
$replies  = mysql_query("SELECT * from posts where forum = '$forums[id]' and iftopic = 'no'");
$replies = mysql_num_rows($replies);
$latest = mysql_query("SELECT * from posts where forum = '$forums[id]' order by id desc limit 1");
echo("<tr>
<td><b><a href=\"?view=forum&act=topics&id=$forums[id]\">$forums[title]</a></b><br />
&nbsp;&nbsp;&nbsp;$forums[desc]</td><td align=\"center\" width=\"75\">$topics</td><td align=\"center\" width=\"75\">$replies</td>
<td align=\"center\" width=\"125\">");
$lnum = mysql_num_rows($latest);
if($lnum == 0)
{
echo("<i>No Posts Yet</i>");
}
else
{
$latest = mysql_fetch_array($latest);
echo("
$latest[user]<br /><a href=\"?view=forum&act=posts&fid=$latest[forum]&amp;id=$latest[topic]\">View Topic</a></td>
</tr>
");
}}
echo("</table>");
endbox();
}
break;
case 'topics':
if (!$_GET[id])
{
boxtop("Error");
echo("Invalid forum id! Please go back and try again");
endbox();
}
else
{
$getforum = mysql_query("SELECT * from forum_forums where id = '$_GET[id]'");
$testnum = mysql_num_rows($getforum);
if ($testnum == 0)
{
boxtop("Error");
echo("Invalid forum id! Please go back and try again");
endbox();
}
else
{
$forum = mysql_fetch_array($getforum);
boxtop2("Forum Navigation");
echo("<div class=\"bar\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">
<tr>
<td height=\"8\"><a href=\"?view=forum\">Forum Index</a> » <b>$forum[title]</b></td></tr>
</table></div>");
echo("$settings[ad2]");
endbox();
echo("<p style=\"margin: 0px\" align=\"right\"><a href=\"?view=forum&act=newtopic&id=$_GET[id]\"><img src=\"images/newtopic.gif\" border=\"0\"></a>&nbsp;&nbsp;&nbsp;</p>");
boxtop2("Topics in $forum[title]");
$stickyget = mysql_query("SELECT * from topics where forum = '$forum[id]' AND sticky = 'yes' ORDER BY 'lastpost' desc");
$stickynum = mysql_num_rows($stickyget);
if($stickynum != 0)
{
bar1a("<table cellpadding=\"1\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>
<td>Topic Title and Description</td><td  width=\"75\"># Replies</td><td  width=\"75\"># Views</td>
<td width=\"100\">Last Post by</td>
</tr></table>");
}
else
{
bar("<table cellpadding=\"1\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>
<td>Topic Title and Description</td><td width=\"100\">Started By</td><td  width=\"75\"># Replies</td><td  width=\"75\"># Views</td>
<td width=\"100\">Last Post by</td>
</tr></table>");
}
$max_results = 10;
$from = (($page * $max_results) - $max_results); 
$max_results = 10;
$stickynum = mysql_num_rows($stickyget);
if ($stickynum != 0)
{
$postnum = 0;
bar3("Important Topics");
echo("<table cellpadding=\"1\" cellspacing=\"0\" border=\"0\" width=\"100%\">");
while($sticky = mysql_fetch_array($stickyget))
{
$started = mysql_query("select * from posts where topic = '$sticky[id]'  AND iftopic = 'yes'");
$started = mysql_fetch_array($started);
$replies  = mysql_query("SELECT * from posts where iftopic = 'no' and topic = '$sticky[id]'");
$replies = mysql_num_rows($replies);
$lastpost = mysql_query("select * from posts where topic = '$sticky[id]' order by 'id' desc limit 1");
$lastpost = mysql_fetch_array($lastpost);
echo("<tr>
<td");
if ($postnum != 0)
{
echo(" style=\"border-top: 1px dotted #808080;\"");
}
echo("><b><a href=\"?view=forum&act=posts&fid=$sticky[forum]&id=$sticky[id]&page=$pages\">$sticky[title]</a></b><br />
&nbsp;&nbsp;&nbsp;$sticky[desc]</td><td width=\"100\"");
if($postnum != 0)
{
echo(" style=\"border-top: 1px dotted #808080;\"");
}
echo(">$started[user]<td width=\"75\"");
if ($postnum != 0)
{
echo(" style=\"border-top: 1px dotted #808080;\"");
}
echo(">$replies</td><td width=\"75\"");
if ($postnum != 0)
{
echo(" style=\"border-top: 1px dotted #808080;\"");
}
echo(">$sticky[views]</td>
<td width=\"100\"");
if ($postnum != 0)
{
echo(" style=\"border-top: 1px dotted #808080;\"");
}
echo(">$lastpost[user]</td>
</tr>
");
$postnum = $postnum+1;
}
echo("</table>");
}
if($stickynum != 0)
{
bar4("Normal Topics");
}
//end sticky
$gettopics = mysql_query("SELECT * from topics where forum = '$forum[id]' AND sticky = 'no' ORDER BY 'lastpost' desc LIMIT $from, $max_results");
$topicnum =  mysql_num_rows($gettopics);
if ($topicnum == 0)
{
echo("There are currently no topics in this forum!");
}
else
{
$postnum = 0;
echo("<table cellpadding=\"1\" cellspacing=\"0\" border=\"0\" width=\"100%\">");
while($topics = mysql_fetch_array($gettopics))
{
$started = mysql_query("select * from posts where topic = '$topics[id]' AND iftopic = 'yes'");
$started = mysql_fetch_array($started);
$max_results = 10; 
$page_results = mysql_query("SELECT * from topics where forum = '$forum[id]'");
$page_results = mysql_num_rows($page_results);
$pages = ceil($page_results / $page_results); 
$replies  = mysql_query("SELECT * from posts where iftopic = 'no' and topic = '$topics[id]'");
$replies = mysql_num_rows($replies);
$lastpost = mysql_query("select * from posts where topic = '$topics[id]' order by 'id' desc limit 1");
$lastpost = mysql_fetch_array($lastpost);
echo("<tr>
<td");
if ($postnum != 0)
{
echo(" style=\"border-top: 1px dotted #808080;\"");
}
echo("><b><a href=\"?view=forum&act=posts&fid=$topics[forum]&id=$topics[id]&page=$pages\">$topics[title]</a></b><br />
&nbsp;&nbsp;&nbsp;$topics[desc]</td><td width=\"100\"");
if($postnum != 0)
{
echo(" style=\"border-top: 1px dotted #808080;\"");
}
echo(">$started[user]<td width=\"75\"");
if ($postnum != 0)
{
echo(" style=\"border-top: 1px dotted #808080;\"");
}
echo(">$replies</td><td width=\"75\"");
if ($postnum != 0)
{
echo(" style=\"border-top: 1px dotted #808080;\"");
}
echo(">$topics[views]</td>
<td width=\"100\"");
if ($postnum != 0)
{
echo(" style=\"border-top: 1px dotted #808080;\"");
}
echo(">$lastpost[user]</td>
</tr>
");
$postnum = $postnum+1;
}}
echo("</table>");
endbox();
boxtop("Pages");
$max_results = 10; 
$total_results1 = mysql_query("SELECT * from topics where forum = '$forum[id]'");
$total_results = mysql_num_rows($total_results1);
$total_pages = ceil($total_results / $max_results); 
for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "<u>$i</u> &nbsp;";
        } else {
            echo "<a href=\"?view=forum&amp;act=topics&amp;id=$forum[id]&amp;page=$i\">$i</a> &nbsp;";
    }
}

echo("&nbsp;&nbsp;($total_pages)");
endbox();
}
}
break;
case 'posts':
$gettopic = mysql_query("select * from topics where id = '$_GET[id]' and forum = '$_GET[fid]'");
$topicnum = mysql_nuM_rows($gettopic);
if ($topicnum == 0)
{
boxtop("Error");
echo("The topic you are trying to view has been deleted.");
endbox();
}
else
{
if (!$_POST[post])
{
$topic = mysql_fetch_array($gettopic);
$update = mysql_query("update topics set views = views+1 where id ='$_GET[id]'");
$getforum = mysql_query("SELECT * from forum_forums where id = '$topic[forum]'");
$forum = mysql_fetch_array($getforum);
$max_results = 12; 
$from = (($page * $max_results) - $max_results); 

$posts = mysql_query("SELECT * from posts where topic = '$topic[id]' ORDER BY 'id' asc LIMIT $from, $max_results");
boxtop2("Forum Navigation");
echo("<div class=\"bar\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">
<tr>
<td height=\"8\"><a href=\"?view=forum\">Forum Index</a> » <a href=\"?view=forum&act=topics&id=$topic[forum]\">$forum[title]</a> » <b>$topic[title]</b></td></tr></table></div>");
echo("$settings[ad2]");

endbox();
boxtop2("Posts in $topic[title]");
$postnum = 1;
while($post = mysql_fetch_array($posts))
{
$posts2 = mysql_query("SELECT * from posts where user = '$post[user]'");
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
$status = "TechTuts Addict";
}
if ($posts2 >= 250 && $posts2 < 1000)
{
$status = "Extreme Member";
}
$query = mysql_query("SELECT DATE_FORMAT(posts.date, '%W, %M %d, %Y at %l:%i %p EST') as vtime FROM posts where id = '$post[id]'");
$row = mysql_fetch_array($query);
if ($postnum == 1)
{
bar("<img src=\"images/down.gif\" style=\"margin-top: 2px;\" border=\"0\" alt=\"Down arrow\" /> &nbsp;Posted on $row[vtime]");
}
else
{
bar2("<img src=\"images/down.gif\" style=\"margin-top: 2px;\" border=\"0\" alt=\"Down arrow\" /> &nbsp;Posted on $row[vtime]");
}
$post[message] = $bbcode->parse($post[message]);
$post[message] = str_replace("&amp;", "&", $post[message]);
$post[message] = str_replace("[img]", "<img border='0' src='", $post[message]);
$post[message] = str_replace("[/img]", "' />", $post[message]);
$post[message] = str_replace("<code>", "", $post[message]);
$post[message] = str_replace("</code>", "", $post[message]);
$user = mysql_query("SELECT * from members where username = '$post[user]'");
$user = mysql_fetch_array($user);
$sig = $user[sig];
$user[sig] = $bbcode->parse($user[sig]);
$user[sig] = str_replace("&amp;", "&", $user[sig]);
$user[sig] = str_replace("[img]", "<img border='0' src='", $user[sig]);
$user[sig] = str_replace("[/img]", "' />", $user[sig]);
$userposts = mysql_query("SELECT * from posts where user = '$post[user]'");
$userposts = mysql_num_rows($userposts);
echo <<<EOF
<table border="0" width="100%" cellspacing="0" cellpadding="2" id="table1" bgcolor="#FFFFFF">
	<tr>
		<td valign="top" bgcolor="#EFEFEF" width="125" align="center" style="border-right: 1px dotted #808080;"><b><a href="?view=members&user=$post[user]">$post[user]</a></b><br /><i>$status</i><br />

EOF;

avatar("$post[user]");

echo <<<EOF
<br />Group: $user[leveltext]<br />Posts: $userposts<br />
EOF;
if($logged[username] == $post[user] || $logged[username] && $logged[level] >= 7)
{
if ($post[iftopic] == yes)
{
echo("<a href=\"?view=forum&act=edittopic&id=$post[topic]\">Edit Topic</a>");
}
else
{
echo("<a href=\"?view=forum&act=edit&id=$post[id]\">Edit Post</a>");
}
}
if($logged[username] && $logged[level] >= 8)
{
echo(" &nbsp;<a href=\"?view=forum&act=admin&func=dpost&id=$post[id]&confirm=no\">Delete</a>");
}
echo <<<EOF
</td>
		<td bgcolor="#EFEFEF" valign="top">$post[message]
EOF;
if($sig != "")
{
echo("<br />
		<br /><br /><b><font color=\"#808080\">--Signature--</font></b><br />
		$user[sig]");
		}
echo<<<EOF
</td>
	</tr>
</table>
EOF;
$postnum = $postnum +1;
}
endbox();
boxtop("Pages");
$total_results1 = mysql_query("SELECT * from posts where topic = '$topic[id]'");
$total_results = mysql_num_rows($total_results1);
$total_pages = ceil($total_results / $max_results); 
for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "<u>$i</u> &nbsp;";
        } else {
            echo "<a href=\"?view=forum&amp;act=posts&amp;fid=$topic[forum]&amp;id=$topic[id]&amp;page=$i\">$i</a> &nbsp;";
    }
}
endbox();
if ($logged[username])
{
$getlastpost = mysql_query("select * from posts where topic = '$_GET[id]' order by id desc limit 1");
$lastpost = mysql_fetch_array($getlastpost);
if($lastpost[user] == $logged[username])
{
boxtop("Sorry");
echo("You were the last person to reply!  Please update your last post instead of making a new one.");
endbox();
}
else
{
boxtop("Post a reply");
if($topic[locked] == yes)
{
echo("This topic is locked!  If you would like it unlocked, please contact an administrator");
}
else
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
	<input type="submit" style="border: 1px solid #808080; background-color: #e6e6e6; margin-top: 2px;" value="Submit" name="post">
</dd></dl>
</form>
EOF;
}}
endbox();
}}
if($_POST[post])
{

$message = htmlspecialchars($_POST[message]);
$insert = mysql_query("INSERT INTO `posts` ( `id` , `title` , `topic` , `message` , `user` , `date` , `forum` , `cat` , `iftopic` )
VALUES (
'', 'Reply to $topic[title]', '$_GET[id]', '$message', '$logged[username]', NOW( ) , '$_GET[fid]', '$topic[cat]', 'no'
)");
$max_results = 12;
$total_results1 = mysql_query("SELECT * from posts where topic = '$_GET[id]'");
$total_results = mysql_num_rows($total_results1);
$total_pages = ceil($total_results / $max_results);
$update = mysql_query("update topics set lastpost = NOW() where id = '$_GET[id]'");
boxtop("Thank You!");
echo("<meta http-equiv=\"Refresh\" content=\"0; URL=?view=forum&act=posts&fid=$_GET[fid]&id=$_GET[id]&page=$total_pages\"/>I am now redirecting you!");
endbox();
}

if (!$logged[username])
{
boxtop("Please Login");
include("includes/login.php");
endbox();
}

if($logged[username] && $logged[level] >= 8)
{
boxtop("Staff Options");
if($topic[locked] == yes)
{
echo("<a href=\"?view=forum&act=admin&func=unlock&id=$_GET[id]\">Unlock Topic</a>");
}
else
{
echo("<a href=\"?view=forum&act=admin&func=lock&id=$_GET[id]\">Lock Topic</a>");
}
if($topic[sticky] == yes)
{
echo(" &middot; <a href=\"?view=forum&act=admin&func=unsticky&id=$_GET[id]\">UnPin Topic</a>");
}
else
{
echo(" &middot; <a href=\"?view=forum&act=admin&func=sticky&id=$_GET[id]\">Pin Topic</a>");
}
echo(" &middot; <a href=\"?view=forum&act=admin&func=delete&id=$_GET[id]&confirm=no\">Delete Thread</a>");
endbox();
}
}
break;
case 'newtopic':
if($logged[username])
{ //1
if ($_GET[id])
{ //2
$getforum = mysql_query("select * from forum_forums where id = '$_GET[id]'");
$forum = mysql_fetch_array($getforum);
if($logged[level] >= $forum[minlevel])
{ //3
$getforum = mysql_query("select * from forum_forums where id = '$_GET[id]'");
$forum1 = mysql_num_rows($getforum);
if ($forum1 == 0)
{ //4
boxtop("Error");
echo("Invalid Forum!");
endbox();
} //3
else
{ //4
$forum = mysql_fetch_array($getforum);
if (!$_POST[post])
{ //5
boxtop("New Topic");
echo <<<EOF
	<form name="message" method="POST" style="margin: 0px">
<dl style="margin: 0px">
<dt>Topic Title</dt>
<dd><input type="text" name="title" size="30" style="margin: 1px; font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #efefef" /></dd> 
<dt>Topic Description</dt>
<dd><input type="text" name="desc" size="30" style="margin: 1px; font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #efefef" /></dd> 
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
	<input type="submit" style="border: 1px solid #808080; background-color: #e6e6e6; margin-top: 2px;" value="Submit" name="post">
</dd></dl>
</form>
EOF;
endbox();
} //4
else
{ //5
$message = htmlspecialchars($_POST[message]);
$title = htmlspecialchars($_POST[title]);
$desc = htmlspecialchars($_POST[desc]);
$insert1 = mysql_query("INSERT INTO `topics` ( `id` , `cat` , `forum` , `title` , `lastpost` , `views` , `desc` )
VALUES (
'', '$forum[parent]', '$forum[id]', '$title', NOW( ) , '0', '$desc'
)");
$get = mysql_query("select * from topics order by 'id' desc limit 1");
$topic = mysql_fetch_array($get);
$insert = mysql_query("INSERT INTO `posts` ( `id` , `title` , `topic` , `message` , `user` , `date` , `forum` , `cat` , `iftopic` )
VALUES (
'', '$title', '$topic[id]', '$message', '$logged[username]', NOW( ) , '$_GET[id]', '$forum[parent]', 'yes'
)");
boxtop("Thank You");
echo("Your topic has been posted.  <a href=\"?view=forum&amp;act=topics&id=$_GET[id]\">Click here to view your message</a>");
endbox();
}//4
}//3
}
else
{
boxtop("Incorrect Permissions");
echo("Sorry, but you need to be a staff member to start a new topic here!");
endbox();
}
}
}
else
{
boxtop2("Please Login");
bar("You must login to create a new topic");
include("includes/login.php");
endbox(); 
}
break;
case 'edittopic':
if (!$_GET[id])
{
boxtop("Error");
echo("Invalid topic!  Please go back and try again");
endbox();
}
else
{
$gettopic = mysql_query("SELECT * from topics where id = '$_GET[id]'");
$tnum = mysql_num_rows($gettopic);
if ($tnum == 0)
{
boxtop("Error");
echo("Invalid topic!  Please go back and try again");
endbox();
}
else
{
$topic = mysql_fetch_array($gettopic);
$getpost = mysql_query("SELECT * from posts where topic = '$topic[id]' and iftopic = 'yes'");
$post = mysql_fetch_array($getpost);
if($logged[username] && $logged[username] == $post[user] || $logged[username] && $logged[level] >= 7)
{
//if not posted
if(!$_POST[edit])
{
boxtop("Editing Topic");
echo <<<EOF
	<form name="message" method="POST" style="margin: 0px">
<dl style="margin: 0px">
<dt>Topic Title</dt>
<dd><input type="text" value="$topic[title]" name="title" size="30" style="margin: 1px; font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #efefef" /></dd> 
<dt>Topic Description</dt>
<dd><input type="text" value="$topic[desc]" name="desc" size="30" style="margin: 1px; font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #efefef" /></dd> 
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
<textarea rows="7" name="message" cols="93" style="border: 1px solid #808080; background-color: #EFEFEF; font-family:Verdana; color:#000000; font-size:10px">$post[message]</textarea><br>
	<input type="submit" style="border: 1px solid #808080; background-color: #e6e6e6; margin-top: 2px;" value="Submit" name="edit">
</dd></dl>
</form>
EOF;
endbox();
}
else
{
boxtop("Thank You");
echo("Your topic has been updated.");
endbox();
$title = htmlspecialchars($_POST[title]);
$title = addslashes($title);
$message = htmlspecialchars($_POST[message]);
$desc = htmlspecialchars($_POST[desc]);
$desc = addslashes($desc);
$query = mysql_query("update topics set title = '$title' where id= '$topic[id]'") or die(mysql_error());
$query = mysql_query("update topics set `desc` = '$desc' where id = '$topic[id]'") or die(mysql_error());

$updatepost = mysql_query("update posts set message = '$message' where id = '$post[id]'");
}
}
else
{
boxtop("Error");
echo("You did not start this topic!  so..you cannot edit it");
endbox();
}
}
}
break;
case 'edit':
if (!$_GET[id])
{
boxtop("Error");
echo("Invalid post!  Please go back and try again");
endbox();
}
else
{
$gettopic = mysql_query("SELECT * from posts where id = '$_GET[id]'");
$tnum = mysql_num_rows($gettopic);
if ($tnum == 0)
{
boxtop("Error");
echo("Invalid post!  Please go back and try again");
endbox();
}
else
{
$topic = mysql_fetch_array($gettopic);
$getpost = mysql_query("SELECT * from posts where id = '$_GET[id]'");
$post = mysql_fetch_array($getpost);
if($logged[username] && $logged[username] == $post[user] || $logged[username] && $logged[level] >= 7)
{
//if not posted
if(!$_POST[edit])
{
boxtop("Post a reply");
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
<textarea rows="7" name="message" cols="93" style="border: 1px solid #808080; background-color: #EFEFEF; font-family:Verdana; color:#000000; font-size:10px">$post[message]</textarea><br>
	<input type="submit" style="border: 1px solid #808080; background-color: #e6e6e6; margin-top: 2px;" value="Submit" name="edit">
</dd></dl>
</form>
EOF;
endbox();
}
else
{
$message = htmlspecialchars($_POST[message]);
$update = mysql_query("update posts set message = '$message' where id = '$post[id]'");
boxtop("Thank You!");
echo("Your message has been updated.  <a href=\"?view=forum&act=posts&fid=$post[forum]&id=$post[topic]\">Click Here to continue</a>");
endbox();
}
}
else
{
boxtop("Error");
echo("You did not write this post!  so..you cannot edit it");
endbox();
}
}
}
break;
case 'admin':
if($logged[username] && $logged[level] >= 8)
{
switch($_GET[func])
{
default:
boxtop("Admin Controls");
echo("Please go to the main forum and select one of the options when viewing a topic");
endbox();
break;
case 'lock':
if($_GET[id])
{
$topicinfo = mysql_query("Select * from topics where id = '$_GET[id]'");
$topicinfo = mysql_fetch_array($topicinfo);
boxtop("Topic Locked");
echo("\"$topicinfo[title]\" has been locked.  <a href=\"?view=forum&act=posts&fid=$topicinfo[forum]&id=$topicinfo[id]&page=1\">Click here to continue</a>");
endbox();
$lock = mysql_query("update topics set locked  = 'yes' where id = '$_GET[id]'");
}
else
{
boxtop("Invalid Topic!");
echo("The topic you are trying to lock...is not real!");
endbox();
}
break;
case 'unlock':
if($_GET[id])
{
$topicinfo = mysql_query("Select * from topics where id = '$_GET[id]'");
$topicinfo = mysql_fetch_array($topicinfo);
boxtop("Topic Locked");
echo("\"$topicinfo[title]\" has been unlocked.  <a href=\"?view=forum&act=posts&fid=$topicinfo[forum]&id=$topicinfo[id]&page=1\">Click here to continue</a>");
endbox();
$lock = mysql_query("update topics set locked  = 'no' where id = '$_GET[id]'");
}
else
{
boxtop("Invalid Topic!");
echo("The topic you are trying to unlock...is not real!");
endbox();
}
break;
case 'sticky':
if($_GET[id])
{
$topicinfo = mysql_query("Select * from topics where id = '$_GET[id]'");
$topicinfo = mysql_fetch_array($topicinfo);
boxtop("Topic Locked");
echo("\"$topicinfo[title]\" has been pinned.  <a href=\"?view=forum&act=posts&fid=$topicinfo[forum]&id=$topicinfo[id]&page=1\">Click here to continue</a>");
endbox();
$lock = mysql_query("update topics set sticky  = 'yes' where id = '$_GET[id]'");
}
else
{
boxtop("Invalid Topic!");
echo("The topic you are trying to pin...is not real!");
endbox();
}
break;
case 'unsticky':
if($_GET[id])
{
$topicinfo = mysql_query("Select * from topics where id = '$_GET[id]'");
$topicinfo = mysql_fetch_array($topicinfo);
boxtop("Topic Locked");
echo("\"$topicinfo[title]\" has been unpinned.  <a href=\"?view=forum&act=posts&fid=$topicinfo[forum]&id=$topicinfo[id]&page=1\">Click here to continue</a>");
endbox();
$lock = mysql_query("update topics set sticky  = 'no' where id = '$_GET[id]'");
}
else
{
boxtop("Invalid Topic!");
echo("The topic you are trying to unpin...is not real!");
endbox();
}
break;
case 'delete':
if($_GET[id])
{
if($_GET[confirm] == no)
{
$delete = mysql_query("Select * from topics where id = '$_GET[id]'");
$delete = mysql_fetch_array($delete);
boxtop2("Are you sure?");
bar("Are you sure you want to delete $delete[title]");
echo("<a href=\"?view=forum&act=admin&func=delete&id=$_GET[id]&confirm=yes\">Yes</a> &nbsp; &nbsp; &nbsp; <a href=\"?view=forum&act=posts&fid=$delete[forum]&id=$delete[topic]\">No</a>");
endbox();
}
if($_GET[confirm] == yes)
{
$deletetopic = mysql_query("delete from topics where id = '$_GET[id]'");
$deletetopic = mysql_query("delete from posts where topic = '$_GET[id]'");
boxtop("Topic Deleted");
echo("The topic has been deleted.  <a href=\"?view=forum\">Click here to continue</a>");
endbox();
}
}
break;
case 'dpost':
if($_GET[id])
{
if($_GET[confirm] == no)
{
boxtop2("Are you sure?");
bar("Are you sure you want to delete this post?");
echo("<a href=\"?view=forum&act=admin&func=dpost&id=$_GET[id]&confirm=yes\">Yes</a> &nbsp; &nbsp; &nbsp; <a href=\"?view=forum\">No</a>");
endbox();
}
if($_GET[confirm] == yes)
{
$deletetopic = mysql_query("delete from posts where id = '$_GET[id]'");
boxtop("Topic Deleted");
echo("The post has been deleted.  <a href=\"?view=forum\">Click here to continue</a>");
endbox();
}
}
break;
}
}
else
{
boxtop("Error");
echo("You are not an admin!");
endbox();
}
break;
}
?>