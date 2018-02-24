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
<td width=\"100\" align=\"center\" height=\"8\">Last Post</td>
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
<td align=\"center\" width=\"100\">");
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
<td height=\"8\" width=\"58%\"><a href=\"?view=forum\">Forum Index</a> » <b>$forum[title]</b></td><td width=\"42%\" height=\"8\"><b>Pages:</b>");
$max_results = 7; 
$total_results1 = mysql_query("SELECT * from topics where forum = '$forum[id]'");
$total_results = mysql_num_rows($total_results1);
$total_pages = ceil($total_results / $max_results); 
for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "&nbsp; <u>$i</u>";
        } else {
            echo "&nbsp; <a href=\"?view=forum&amp;act=topics&amp;id=$forum[id]&amp;page=$i\">$i</a>";
    }
}

echo("&nbsp;&nbsp;($total_pages)</td></tr>
</table></div>");
echo<<<EOF
<center>
<script type="text/javascript"><!--
google_ad_client = "pub-0950362717359680";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text_image";
google_ad_channel ="";
google_color_border = "efefef";
google_color_bg = "f1f1f1";
google_color_link = "000000";
google_color_url = "336600";
google_color_text = "333333";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></center>
EOF;
endbox();
echo("<p style=\"margin: 0px\" align=\"right\"><a href=\"?view=forum&act=newtopic&id=$_GET[id]\"><img src=\"images/newtopic.gif\" border=\"0\"></a>&nbsp;&nbsp;&nbsp;</p>");
boxtop2("Topics in $forum[title]");
bar("<table cellpadding=\"1\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>
<td>Topic Title and Description</td><td  width=\"75\"># Replies</td><td  width=\"75\"># Views</td>
<td width=\"100\">Last Post by</td>
</tr></table>");
$max_results = 10;
$from = (($page * $max_results) - $max_results); 
$gettopics = mysql_query("SELECT * from topics where forum = '$forum[id]' ORDER BY 'lastpost' desc LIMIT $from, $max_results");
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
&nbsp;&nbsp;&nbsp;$topics[desc]</td><td width=\"75\"");
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
}
}
break;
case 'posts':
if (!$_POST[post])
{
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
<td height=\"8\" width=\"58%\"><a href=\"?view=forum\">Forum Index</a> » <a href=\"?view=forum&act=topics&id=$topic[forum]\">$forum[title]</a> » <b>$topic[title]</b></td><td width=\"42%\" height=\"8\"><b>Pages:</b>");
$total_results1 = mysql_query("SELECT * from posts where topic = '$topic[id]'");
$total_results = mysql_num_rows($total_results1);
$total_pages = ceil($total_results / $max_results); 
for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "&nbsp; <u>$i</u>";
        } else {
            echo "&nbsp; <a href=\"?view=forum&amp;act=posts&amp;fid=$topic[forum]&amp;id=$topic[id]&amp;page=$i\">$i</a>";
    }
}
echo("&nbsp;&nbsp;($total_pages)</td></tr>
</table></div>");
echo<<<EOF
<center>
<script type="text/javascript"><!--
google_ad_client = "pub-0950362717359680";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text_image";
google_ad_channel ="";
google_color_border = "efefef";
google_color_bg = "f1f1f1";
google_color_link = "000000";
google_color_url = "336600";
google_color_text = "333333";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></center>
EOF;
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
$status = "Active Memeber";
}
if ($posts2 >= 100 && $posts2 < 200)
{
$status = "Senior Memeber";
}
$query = mysql_query("SELECT DATE_FORMAT(posts.date, '%W, %M %d, %Y at %l:%i %p EST') as vtime FROM posts where id = '$post[id]'");
$row = mysql_fetch_array($query);
if ($postnum == 1)
{
bar("&middot; Posted on $row[vtime]");
}
else
{
bar2("&middot; Posted on $row[vtime]");
}
$post[message] = $bbcode->parse($post[message]);
$post[message] = str_replace("&amp;", "&", $post[message]);
$post[message] = str_replace("[img]", "<img border='0' src='", $post[message]);
$post[message] = str_replace("[/img]", "' />", $post[message]);

$user = mysql_query("SELECT * from members where username = '$post[user]'");
$user = mysql_fetch_array($user);
$userposts = mysql_query("SELECT * from posts where user = '$post[user]'");
$userposts = mysql_num_rows($userposts);
echo <<<EOF
<table border="0" width="100%" cellspacing="0" cellpadding="2" id="table1" bgcolor="#FFFFFF">
	<tr>
		<td bgcolor="#EFEFEF" width="125" align="center" style="border-right: 1px dotted #808080;"><b><a href="?view=members&user=$post[user]">$post[user]</a></b><br /><i>$status</i><br />

EOF;

avatar("$post[user]");

echo <<<EOF
<br />Group: $user[leveltext]<br />Posts: $userposts<br />
EOF;
if($logged[username] == $post[user])
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
echo <<<EOF
</td>
		<td bgcolor="#EFEFEF" valign="top">$post[message]</td>
	</tr>
</table>
EOF;
$postnum = $postnum +1;
}
}
endbox();
boxtop("Pages");
$total_results1 = mysql_query("SELECT * from posts where topic = '$topic[id]'");
$total_results = mysql_num_rows($total_results1);
$total_pages = ceil($total_results / $max_results); 
for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "&nbsp; <u>$i</u>";
        } else {
            echo "&nbsp; <a href=\"?view=forum&amp;act=posts&amp;fid=$topic[forum]&amp;id=$topic[id]&amp;page=$i\">$i</a>";
    }
}
endbox();
if ($logged[username])
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
<textarea rows="7" name="message" cols="93" style="border: 1px solid #808080; background-color: #EFEFEF; font-family:Verdana; color:#000000; font-size:10px"></textarea><br>
	<input type="submit" style="border: 1px solid #808080; background-color: #e6e6e6; margin-top: 2px;" value="Submit" name="post">
</dd></dl>
</form>
EOF;
endbox();
}
}
else
{
$message = htmlspecialchars($_POST[message]);
$insert = mysql_query("INSERT INTO `posts` ( `id` , `title` , `topic` , `message` , `user` , `date` , `forum` , `cat` , `iftopic` )
VALUES (
'', 'Reply to $topic[title]', '$_GET[id]', '$message', '$logged[username]', NOW( ) , '$_GET[fid]', '$topic[cat]', 'no'
)");
$update = mysql_query("update topics set lastpost = NOW() where id = '$_GET[id]'");
boxtop("Thank You!");
echo("Your post has been posted.  <a href=\"?view=forum&act=posts&fid=$_GET[fid]&id=$_GET[id]\">Click Here to continue</a>");
endbox();
}
if (!$logged[username])
{
boxtop("Please Login");
include("includes/login.php");
endbox();
}
break;
case 'newtopic':
if($logged[username])
{
if ($_GET[id])
{
$getforum = mysql_query("select * from forum_forums where id = '$_GET[id]'");
$forum1 = mysql_num_rows($getforum);
if ($forum1 == 0)
{
boxtop("Error");
echo("Invalid Forum!");
endbox();
}
else
{
$forum = mysql_fetch_array($getforum);
if (!$_POST[post])
{
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
}
else
{
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
}
}}
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
if($logged[username] && $logged[username] == $post[user])
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
$message = addslashes($message);
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
if($logged[username] && $logged[username] == $post[user])
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
echo("Your message has been uodated.  <a href=\"?view=forum&act=posts&fid=$post[forum]&id=$_GET[id]\">Click Here to continue</a>");
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
}
?>