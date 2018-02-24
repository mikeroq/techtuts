<?
if (!$_GET[user])
{
if($_GET[page])
{
$page = $_GET[page];
}
else
{
$page = "1";
}
boxtop("Select a member");
$max_results = 100;
$from = (($page * $max_results) - $max_results); 
$getmember = mysql_query("SELECT * from members order by username asc LIMIT $from, $max_results");
$n = 0;
while($member = mysql_fetch_array($getmember))
{
if($n != 0)
{
echo("<br />");
}
$n++;
echo("&middot; <a href=\"?view=members&user=$member[username]\">$member[username]</a>");
}
endbox();
boxtop("Pages");
$max_results = 100; 
$total_results1 = mysql_query("SELECT * from members");
$total_results = mysql_num_rows($total_results1);
$total_pages = ceil($total_results / $max_results); 
for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "<u>$i</u> &nbsp;";
        } else {
            echo "<a href=\"?view=members&page=$i\">$i</a> &nbsp;";
    }
}

echo("&nbsp;&nbsp;($total_pages)");
endbox();
}
else
{
$getmember = mysql_query("SELECT * from members where username = '$_GET[user]'");
$num = mysql_num_rows($getmember);
if ($num == 0)
{
boxtop("User not found");
echo("The user you specified cannot be found!");
endbox();
}
else
{
$shouts = mysql_query("Select * from shouts where user = '$_GET[user]'");
$shouts = mysql_num_rows($shouts);
$comments = mysql_query("Select * from tutcomments where author = '$_GET[user]'");
$comments = mysql_num_rows($comments);
$tutorials = mysql_query("Select * from tutorials where active = 'yes' AND author = '$_GET[user]'");
$tutorials = mysql_num_rows($tutorials);
$member = mysql_fetch_array($getmember);
boxtop2("$member[username]'s Profile");
bar("General Information");
$posts = mysql_query("SELECT * from posts where user = '$member[username]'");
$posts = mysql_num_rows($posts);
if ($posts <= 10)
{
$status = "Newbie";
}
if ($posts >= 11 && $posts < 50)
{
$status = "Junior Member";
}
if ($posts >= 50 && $posts < 100)
{
$status = "Active Member";
}
if ($posts >= 100 && $posts < 200)
{
$status = "Senior Member";
}
if ($posts >= 200 && $posts < 250)
{
$status = "1337 Member";
}
if ($posts >= 250 && $posts < 500)
{
$status = "Uber 1337 Member";
}
echo("<b>&middot; Position:</b> $member[leveltext]<br />
<b>&middot; Status:</b> $status");
bar2("Contact Information");
echo("<b>&middot; MSN Messenger:</b> $member[msn]<br />
<b>&middot; AOL Messenger:</b> $member[aim]<br />
<b>&middot; Email:</b> $member[email]");
bar2("Optional Information");
echo("<b>&middot; Intrests:</b> $member[intrests]<br />
<b>&middot; Age:</b> $member[age]");
bar2("Member Stats");
$topics = mysql_query("SELECT * from posts where iftopic = 'yes' and user = '$member[username]'");
$topics = mysql_num_rows($topics);
$replies = mysql_query("SELECT * from posts where iftopic = 'no' and user = '$member[username]'");
$replies = mysql_num_rows($replies);
echo("
<b>&middot; Forum Topics Posted:</b> $topics<br />
<b>&middot; Forum Replies Posted:</b> $replies<br />
<b>&middot; Comments Posted:</b> $comments<br />
<b>&middot; Tutorials Submitted:</b> $tutorials<br />
<b>&middot; Shouts Posted:</b> $shouts<br />
<b>&middot; Pages Viewed:</b> $member[pages]
");
endbox();
}
}
?>