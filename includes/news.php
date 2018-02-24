<?
$getnews = mysql_query("SELECT * from news order by id desc limit 3");
while ($news = mysql_fetch_array($getnews))
{
if ($logged[username])
{
$news[news] = str_replace('[viewer]', $logged[username], $news[news]);
}
else
{
$news[news] = str_replace('[viewer]', $REMOTE_ADDR, $news[news]);
}

$news[news] = $bbcode->parse($news[news]);
$news[news] = str_replace("[img]", "<img border='0' src='", $news[news]);
$news[news] = str_replace("[/img]", "' />", $news[news]);
boxtop2("$news[title]");
bar("Posted by $news[author] on $news[date]");
echo ("$news[news]");
endbox();
}
boxtop2("Latest Forum Action");
$getid = mysql_query("SELECT DISTINCT topic from posts order by id desc limit 8");
echo("<table stylecellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>
<td align=\"center\" style=\"padding: 3px 20px 3px 3px; color:#808080;\" bgcolor=\"#dddddd\">Parent Topic</td><td align=\"center\" style=\"padding: 3px 20px 3px 3px; color:#808080;\" bgcolor=\"#dddddd\" width=\"100\">Post By</td><td align=\"center\"  style=\"padding: 3px 20px 3px 3px; color:#808080;\" bgcolor=\"#dddddd\">Parent Forum</td><td align=\"center\" style=\"padding: 3px 20px 3px 3px; color:#808080;\" bgcolor=\"#dddddd\" width=\"75\"># Replies</td><td align=\"center\" style=\"padding: 3px 20px 3px 3px; color:#808080;\" bgcolor=\"#dddddd\" align=\"center\" width=\"75\"># Views</td>
</tr>");
while($id = mysql_fetch_array($getid))
{
$getpost = mysql_query("select * from posts where topic = '$id[topic]' order by id desc limit 1");
$post = mysql_fetch_array($getpost);
$topic = mysql_query("select * from topics where id = '$post[topic]'");
$topic = mysql_fetch_array($topic);
$forum = mysql_query("Select * from forum_forums where id = '$post[forum]'");
$forum = mysql_fetch_array($forum);
$numreplies = mysql_query("select * from posts where iftopic = 'no' and topic = '$post[topic]'");
$numreplies = mysql_num_rows($numreplies);
echo("<tr>
<td class=\"content\"><a href=\"?view=forum&act=posts&fid=$post[forum]&id=$topic[id]\">$topic[title]</a></td>
<td class=\"content\" width=\"100\"><a href=\"?view=members&user=$post[user]\">$post[user]</a></td>
<td class=\"content\"><a href=\"?view=forum&act=topics&id=$forum[id]\">$forum[title]</a></td>
<td class=\"content\" align=\"center\" width=\"75\">$numreplies</td>
<td class=\"content\" align=\"center\" width=\"75\">$topic[views]</td>
</tr>");
}
echo("</table>");
endbox();
?>