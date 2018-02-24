<?
if($logged[username] && $logged[level] >= 2)
{
switch($_GET[act])
{
default:
boxtop2("Staff Control Panel");
bar("Welcome to the staff control panel");
$pendingaff = mysql_query("select * from affiliates where active = 'no'");
$pendingaff = mysql_num_rows($pendingaff);
$pendingtut = mysql_query("select * from tutorials where active = 'no'");
$pendingtut = mysql_num_rows($pendingtut);
echo("<b>Pending Affiliates:</b> $pendingaff · <b>Pending Tutorials:</b> $pendingtut");
if($logged[level] == 3)
{
bar2("Main Admin Controls");
echo("<a href=\"?x=admin&act=main&func=css\">CSS Managment</a> · <a href=\"?x=admin&act=main&func=ipban\">IP Bans</a> · <a href=\"?x=admin&act=main&func=settings\">Settings</a> · <a href=\"?x=admin&act=upload\">Upload Files</a>");
}
bar2("News Managment");
echo("<a href=\"?x=admin&act=news&func=add\">Add News</a> · <a href=\"?x=admin&act=news&func=edit\">Edit News</a> · <a href=\"?x=admin&act=news&func=delete\">Delete News</a>");
bar2("Tutorial Managment");
echo("<a href=\"?x=admin&act=tutorials&func=write\">Write Tutorial</a> · <a href=\"?x=admin&act=tutorials&func=approve\">Approve Tutorial($pendingtut)</a> · <a href=\"?x=admin&act=tutorials&func=edit\">Edit Tutorial</a> · <a href=\"?x=admin&act=tutorials&func=delete\">Delete Tutorial</a>");
bar2("Tutorial Category Managment");
echo("<a href=\"?x=admin&act=tutcat&func=new\">New Category</a> · <a href=\"?x=admin&act=tutcat&func=edit\">Edit Category</a> · <a href=\"?x=admin&act=tutcat&func=delete\">Delete Category</a>");
if($logged[level] >= 2)
{
bar2("Affiliate Managment");
echo("<a href=\"?x=admin&act=affiliates&func=new\">New Affiliate</a> · <a href=\"?x=admin&act=affiliates&func=approve\">Approve Affiliate</a>($pendingaff) · <a href=\"?x=admin&act=affiliates&func=edit\">Edit Affiliate</a> · <a href=\"?x=admin&act=affiliates&func=delete\">Delete Affiliate</a>");
bar2("Poll Managment");
echo("<a href=\"?x=admin&act=poll\">New Poll</a> · <a href=\?x=admin&act=poll&func=editvotes\">Edit/Delete Votes</a>");
bar2("Forum Managment");
echo("<a href=\"?x=admin&act=forum&func=new\">New Forum</a> · <a href=\"?x=admin&act=forum&func=edit\">Edit Forum</a> · <a href=\"?x=admin&act=forum&func=delete\">Delete Forum</a>");
bar2("Forum Category Managment");
echo("<a href=\"?x=admin&act=forumcat&func=new\">New Category</a> · <a href=\"?x=admin&act=forumcat&func=edit\">Edit Category</a> · <a href=\"?x=admin&act=forumcat&func=delete\">Delete Category</a>");
}
if($logged[level] >=2)
{
bar2("Other Stuff");
echo("<a href=\"?x=admin&act=users\">Edit Users</a> · <a href=\"?x=admin&act=topsites\">Topsites</a>");
}
endbox();
break;
case 'main':

switch($_GET[func])
{
default:
boxtop("Main Admin Controls");
echo("<a href=\"?x=admin&act=main&func=css\">CSS Managment</a> · <a href=\"?x=admin&act=main&func=i  pban\">IP Bans</a> · <a href=\"?x=admin&act=main&func=settings\">Settings</a> · <a href=\"?x=admin&act=upload\">Upload Files</a>");
break;


case 'css':
if(!$_POST[update])
{
boxtop("Updating CSS");
$getcss = mysql_Query("Select * from settings where id = '1'");
$css  = mysql_fetch_array($getcss);
echo("
<form method=\"POST\" style=\"margin: 0px;\">
	<textarea rows=\"6\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #EFEFEF\" name=\"css\" cols=\"37\">$css[css]</textarea><br>
	<br>
	<input type=\"submit\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #e6e6e6\" value=\"Update\" name=\"update\">
</form>");
endbox();
}
else
{
boxtop("Css Updated");
echo("The css has been updated");
endbox();
$update = mysql_query("update settings set css = '$_POST[css]' where id = '1'");
}
break;

case 'ipban':
switch($_GET[mode])
{
case 'edit':
$getipban = mysql_query("Select * from ipban where id = '$_GET[id]'");
$check = mysql_num_rows($getipban);
if($check == 0)
{
boxtop("Error");
echo("Invalid Ip Ban Id");
endbox();
}
else
{
if(!$_POST[update])
{
$ipban = mysql_fetch_array($getipban);
boxtop("Editing A Ipban");
echo("
<form method=\"POST\" style=\"margin: 0px;\">
	<input type=\"text\" name=\"ip\" size=\"20\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #EFEFEF\" value=\"$ipban[ip]\"><br>
	<br>
	<textarea rows=\"6\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #EFEFEF\" name=\"reason\" cols=\"37\">$ipban[reason]</textarea><br>
	<br>
	<input type=\"submit\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #e6e6e6\" value=\"Update\" name=\"update\">
</form>");
endbox();
}
else
{
boxtop("IP Ban Updated");
echo("The ip address ban has been updated");
endbox();
$update = mysql_query("update ipban set ip = '$_POST[ip]', reason = '$_POST[reason]' WHERE id = '$_GET[id]'");
}}
break;
case 'unban':
if($_GET[id])
{
boxtop("IP Unbanned");
echo("The Ip address has been unbanned");
endbox();
$unban = mysql_query("delete from ipban where id = '$_GET[id]'");
}
else
{
boxtop("Error");
echo("Invalid Ip Ban Id");
endbox();
}
break;
default:
if(!$_POST[Ban])
{
boxtop("Banned Ip Addresses");
$getipbans = mysql_query("select * from ipban");
while($ipbans = mysql_fetch_array($getipbans))
{
echo("<b>IP Address:</b> $ipbans[ip]<br />
<b>Reason:</b> $ipbans[reason]<br /><b><a href=\"?x=admin&act=main&func=ipban&mode=edit&id=$ipbans[id]\">Edit<a/> · <a href=\"?x=admin&act=main&func=ipban&mode=unban&id=$ipbans[id]\">Unban</a></b><hr>");
}
bar2("Ban New Ip");
echo("
<form method=\"POST\" style=\"margin: 0px;\">
	<input type=\"text\" name=\"ip\" size=\"20\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #EFEFEF\"><br>
	<br>
	<textarea rows=\"6\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #EFEFEF\" name=\"reason\" cols=\"37\"></textarea><br>
	<br>
	<input type=\"submit\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #e6e6e6\" value=\"Ban\" name=\"Ban\">
</form>");
endbox();
}
else
{
boxtop("Ip Address Banned");
echo("The ip address has been banned");
endbox();
$ban = mysql_query("INSERT INTO `ipban` ( `id` , `ip` , `reason` )VALUES ('', '$_POST[ip]', '$_POST[reason]')");
}
break;
}
break;

case 'settings':

if($logged[level] == 9)
{
if(!$_POST[update])
{
$settings = mysql_query("Select * from settings");
$settings = mysql_fetch_array($settings);
boxtop("Updating Site Settings");
echo("<form method=\"post\" style=\"margin: 0xp;\">
<dl style=\"margin:0px;\">
<dt>Site Title (appears in title bar)</dt>
<dd><input value=\"$settings[title]\" type=\"text\" name=\"title\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Email Address</dt>
<dd><input value=\"$settings[email]\" type=\"text\" name=\"email\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Top of site ad code</dt>
<dd><textarea rows=\"6\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #EFEFEF\" name=\"ad1\" cols=\"37\">$settings[ad1]</textarea></dd>
<dt>Other ad code</dt>
<dd><textarea rows=\"6\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #EFEFEF\" name=\"ad2\" cols=\"37\">$settings[ad2]</textarea><br></dd>
<dt>&nbsp;</dt>
<dd><input type=\"submit\" value=\"Update Settings\" name=\"update\" style=\"border: 1px solid #808080; background-color: #e6e6e6; font-family:Verdana; font-size:10px; width:96; height:18\" /></dd>
</dl>
</form>
");
endbox();
}
else
{
$update = mysql_query("update settings set title = '$_POST[title]', email = '$_POST[email]', ad1 = '$_POST[ad1]', ad2 = '$_POST[ad2]'");
boxtop("Settings Updated");
echo("The site settings have been updated!");
endbox();
}
}
else
{
boxtop("Error");
echo("Sorry, but this feature is for admins only!");
endbox();
}


break;
}

break;



case 'upload':
if($logged[level] >= 8)
{
boxtop("Upload Files");
$numfiles = 10;
if($_POST[submit])
{
foreach ($_FILES["files"]["error"] as $key => $error) {
   if ($error == UPLOAD_ERR_OK) {
       $tmp_name = $_FILES["files"]["tmp_name"][$key];
       $name = $_FILES["files"]["name"][$key];
       move_uploaded_file($tmp_name, "uploads/$name");
   }
}
echo("The files have been uploaded. <a href=\"http://techtuts.com/uploads\">Click here to continue</a>");
}
else
{
echo("<form action=\"\" method=\"post\" enctype=\"multipart/form-data\">");
for($i = 1; $i <= $numfiles; $i++)
{
echo("
<input type=\"file\" name=\"files[]\" /><br /><br />");
}
echo("<input type=\"submit\" name=\"submit\" value=\"Upload Files\" /></form>");
}
endbox();
}
else
{
boxtop("Error");
echo("You must be a moderator or administrator to use the file uploader");
endbox();
}
break;
case 'news':
switch($_GET[func])
{
default:
boxtop("News Managment");
echo("<a href=\"?x=admin&act=news&func=add\">Add News</a> · <a href=\"?x=admin&act=news&func=edit\">Edit News</a> · <a href=\"?x=admin&act=news&func=delete\">Delete News</a>");
bar2("User Managment");
break;
case 'add':
if (!$_POST[addnews])
{
boxtop("Add News");
echo ("
<form method=\"post\" style=\"margin: 0px;\">
<dl style=\"margin: 0px\">
<dt>News Title</dt>
<dd><input type=\"text\" size=\"15\" name=\"newstitle\" style=\"border: 1px solid #808080; background-color: #EFEFEF\" /></dd>
<dt>News Body</dt>
<dd><textarea cols=\"60\" rows=\"9\" name=\"body\" style=\"border: 1px solid #808080; background-color: #EFEFEF\">News Body</textarea></dd>
<dt>&nbsp;</dt>
<dd><input name=\"addnews\" style=\"border: 1px solid #808080; background-color: #e6e6e6\" type=\"submit\" value=\"Add News\"></dd>
</dl>
</form>
");
endbox();
}
else
{
$date = date("F d, Y"); 
boxtop("Success");
$query = mysql_query("INSERT INTO `news` ( `id` , `title` , `date` , `news` , `author` ) VALUES ('', '$_POST[newstitle]', '$date', '$_POST[body]', '$logged[username]'
)");
echo("News added");
endbox();
}
break;
case 'edit':
if (!$_GET[id])
{
boxtop("Select A News Post");
$get = mysql_query("SELECT * from news order by id asc");
while($news = mysql_fetch_array($get))
{
echo("<a href=\"?x=admin&amp;act=news&func=edit&id=$news[id]\">$news[title]</a><br />\n");
}
endbox();
}
else
{
if (!$_POST[editnews])
{
$news = mysql_query("SELECT * from news where id = '$_GET[id]'");
$news = mysql_fetch_array($news);
boxtop("Editing $news[title]");
echo ("
<form method=\"post\" style=\"margin: 0px;\">
<dl style=\"margin: 0px\">
<dt>News Title</dt>
<dd><input type=\"text\" value=\"$news[title]\" size=\"15\" name=\"newstitle\" style=\"border: 1px solid #808080; background-color: #EFEFEF\" /></dd>
<dt>News Body</dt>
<dd><textarea cols=\"60\" rows=\"9\" name=\"body\" style=\"border: 1px solid #808080; background-color: #EFEFEF\">$news[news]</textarea></dd>
<dt>&nbsp;</dt>
<dd><input name=\"editnews\" style=\"border: 1px solid #808080; background-color: #e6e6e6\" type=\"submit\" value=\"Add News\"></dd>
</dl>
</form>
");
endbox();
}
else
{
$update = mysql_query("update news set title = '$_POST[newstitle]', news = '$_POST[body]' where id = '$_GET[id]'");
boxtop("News Updated");
echo("News Updated");
endbox();
}
}
break;
case 'delete':
if (!$_GET[id])
{
boxtop("Select A News Post");
$get = mysql_query("SELECT * from news order by id asc");
while($news = mysql_fetch_array($get))
{
echo("<a href=\"?x=admin&amp;act=news&func=delete&id=$news[id]\">$news[title]</a><br />\n");
}
endbox();
}
else
{
boxtop("News Deleted");
echo("The news post has been deleted");
endbox();
$delete = mysql_query("delete from news where id ='$_GET[id]'");
}
break;
}
break;
case 'topsites':
if($logged[level] >= 8)
{
switch($_GET[func])
{
default:
boxtop("Topsites");
$gettopsites = mysql_query("select * from topsites");
while($topsites = mysql_fetch_array($gettopsites))
{
echo("<b>$topsites[title]</b> - <a href=\"?x=admin&act=topsites&func=edit&id=$topsites[id]\">Edit Site</a> · <a href=\"?x=admin&act=topsites&func=delete&id=$topsites[id]\">Delete</a><br />");
}
endbox();
break;
case 'edit':
if($_GET[id])
{
$getsite = mysql_query("Select * from topsites where id = '$_GET[id]'");
$sitenum = mysql_num_rows($getsite);
if($sitenum == 0)
{
boxtop("Error");
echo("The site you are trying to edit has been deleted or has never existed.");
endbox();
}
else
{
$site = mysql_fetch_array($getsite);
if(!$_POST[join])
{
boxtop("Edit Topsite");
echo("<form method=\"post\" style=\"margin: 0px;\">
<DL style=\"margin: 0px\">
<DT>Site Name
<DD><input type=\"text\" maxlength=\"30\" value=\"$site[title]\" size=\"30\" name=\"name\" style=\"font-family:Verdana; font-size:10px; border: 1px solid #808080; background-color: #EFEFEF\" />
<DT>Site URL
<DD><input size=\"30\" value=\"$site[url]\" name=\"url\" style=\"font-family:Verdana; font-size:10px; border: 1px solid #808080; background-color: #EFEFEF\">
<DT>88x31 Button</dt>
<DD><input size=\"30\" value=\"$site[button]\" name=\"button\" style=\"font-family:Verdana; font-size:10px; border: 1px solid #808080; background-color: #EFEFEF\">
<DT>Site Description
<DD><textarea rows=\"5\" cols=\"50\" name=\"desc\" style=\"font-family:Verdana; font-size:10px; border: 1px solid #808080; background-color: #EFEFEF\">$site[desc]</textarea>
<DT>&nbsp;
<DD><input name=\"join\" style=\"background: #e6e6e6\" type=\"submit\" value=\"Join Topsites\">
</DL>
</form>");
endbox();
}

else
{
$name = addslashes(htmlspecialchars("$_POST[name]"));
$url = addslashes(htmlspecialchars("$_POST[url]"));
$button = addslashes(htmlspecialchars("$_POST[button]"));
$desc = addslashes(htmlspecialchars("$_POST[desc]"));
$update = mysql_query("UPDATE `topsites` SET `title` = '$name',`desc` = '$desc',`button` = '$button',`url` = '$url' WHERE `id` = '$_GET[id]'");
boxtop("Thank You");
echo("That site has been updated.  <a href=\"?x=admin&act=topsites\">Click here to continue</a>");
endbox();
}
}
}
else
{
boxtop("Error");
echo("Invalid Topsite");
endbox();
}
break;
case 'delete':
$delete = mysql_query("delete from topsites where id ='$_GET[id]'");
boxtop("Thank You");
echo("That topsite has been deleted.  <a href=\"?x=admin\">Click here to continue</a>");
endbox();
break;
}
}
else
{
boxtop("Sorry");
echo("Sorry, but this feature is for adminstrators and moderators only");
endbox();
}
break;
case 'tutorials':
switch($_GET[func])
{
default:
boxtop2("Tutorial Managment");
bar("Select an option");
echo("<a href=\"?x=admin&act=tutorials&func=write\">Write Tutorial</a> · <a href=\"?x=admin&act=tutorials&func=approve\">Approve Tutorial($pendingtut)</a> · <a href=\"?x=admin&act=tutorials&func=edit\">Edit Tutorial</a> · <a href=\"?x=admin&act=tutorials&func=delete\">Delete Tutorial</a>");
endbox();
break;
case 'write':
if (!$_POST[add])
{
boxtop("Write Tutorial");
echo("<form method=\"post\" style=\"margin: 0xp;\">
<dl style=\"margin:0px;\">
<dt>Tutorial Title</dt>
<dd><input type=\"text\" name=\"title\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Tutorial Description</dt>
<dd><input type=\"text\" name=\"desc\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>

<dt>Tutorial Category</dt>
<dd>
<select size=\"1\" style=\"background-color: #efefef; font-family:Verdana; font-size:10px\" name=\"cat\">
");
$getcats = mysql_query("select * from tutcat order by id asc");
while ($cats = mysql_fetch_array($getcats))
{
echo("
<option value=\"$cats[id]\">$cats[title]</option>");
}
echo("
</select></dd>
<dt>Tutorial Text</dt>
<dd>
<textarea cols=\"53\" rows=\"12\" name=\"body\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #efefef;\"></textarea></dd>
<dt>&nbsp;</dt>
<dd>
<input type=\"submit\" value=\"Publish Tutorial\" name=\"add\" style=\"border: 1px solid #808080; background-color: #e6e6e6; font-family:Verdana; font-size:10px; width:96; height:18\" /></dd>
</dl>
</form>");
endbox();
}
else
{
$insert = mysql_query("INSERT INTO `tutorials` ( `id` , `title` , `cat` , `text` , `author` , `views` , `desc` , `active` )
VALUES (
'', '$_POST[title]', '$_POST[cat]', '$_POST[body]', '$logged[username]', '2', '$_POST[desc]', 'yes'
)");
boxtop("Success");
echo("Thank you!  The tutorial has been added");
endbox();
}
break;
case 'edit':
if($_GET[id])
{
$gettutorial = mysql_query("Select * from tutorials where id = '$_GET[id]'");
$ifexists = mysql_num_rows($gettutorial);
if($ifexists == 0)
{
boxtop("Error");
echo("Invalid Tutorial");
endbox();
}
else
{
if (!$_POST[add])
{
$tutorial = mysql_fetch_array($gettutorial);
boxtop("Edit Tutorial");

echo("<form method=\"post\" style=\"margin: 0xp;\">
<dl style=\"margin:0px;\">
<dt>Tutorial Title</dt>
<dd><input value=\"$tutorial[title]\" type=\"text\" name=\"title\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Tutorial Description</dt>
<dd><input value=\"$tutorial[desc]\" type=\"text\" name=\"desc\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>

<dt>Tutorial Category</dt>
<dd>
<select size=\"1\" style=\"background-color: #efefef; font-family:Verdana; font-size:10px\" name=\"cat\">
");
$getcats = mysql_query("select * from tutcat order by id asc");
while ($cats = mysql_fetch_array($getcats))
{
echo("<option value=\"$cats[id]\"");
if($cats[id] == $tutorial[cat])
{
echo(" selected");
}
echo(">$cats[title]</option>");
}
echo("
</select></dd>
<dt>Tutorial Text</dt>
<dd>
<textarea cols=\"53\" rows=\"12\" name=\"body\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #efefef;\">$tutorial[text]</textarea></dd>
<dt>&nbsp;</dt>
<dd>
<input type=\"submit\" value=\"Update Tutorial\" name=\"add\" style=\"border: 1px solid #808080; background-color: #e6e6e6; font-family:Verdana; font-size:10px; width:96; height:18\" /></dd>
</dl>
</form>");
endbox();
}
else
{
$update = mysql_query("update tutorials set `title` = '$_POST[title]', `cat` = '$_POST[cat]', `desc` = '$_POST[desc]', `text` = '$_POST[body]' where id = '$_GET[id]'");
boxtop("Success");
echo("Thank you!  The tutorial has been updated");
endbox();
}
}}
else
{
boxtop("Select a Tutorial to Edit");
$select = mysql_query("Select * from tutcat order by 'id' asc");
$catnum = 0;
while($cats = mysql_fetch_array($select))
{
if($catnum != 0)
{
echo("<br />");
}
echo("<b>$cats[title]</b>");
$gettuts = mysql_query("select * from tutorials where cat = '$cats[id]' order by 'id' asc");
while($tuts = mysql_fetch_array($gettuts))
{
echo("<br />&nbsp; <a href=\"?x=admin&act=tutorials&func=edit&id=$tuts[id]\">$tuts[title]</a>");
}
$catnum = $catnum+1;
}
endbox();
}
break;
case 'approve':
if($_GET[id])
{
$update = mysql_query("update tutorials set active = 'yes' where id = '$_GET[id]'");
boxtop("Thank You!");
echo("The tutorial you selected has been approved");
endbox();
}
else
{
boxtop("Approve Tutorials");
$gettuts = mysql_query("select * from tutorials where active = 'no'");
$n = 0;
while($tuts = mysql_fetch_array($gettuts))
{
if($n != 0)
{
echo("<br />");
}
echo("<a href=\"?x=tutorials&act=tutorial&id=$tuts[id]\">$tuts[title]</a> -- <a href=\"?x=admin&act=tutorials&func=approve&id=$tuts[id]\">Approve</a><br />");
}
endbox();
}
break;
case 'delete':
if($_GET[id])
{
$delete = mysql_query("delete from tutorials where id = '$_GET[id]'");
$delete = mysql_query("delete from tutcomments where tut = '$_GET[id]'");

boxtop("Tutorial Deleted");
echo("The tutorial you selected has been deleted");
endbox();
}
else
{
boxtop("Select a Tutorial to Delete");
$select = mysql_query("Select * from tutcat order by 'id' asc");
$catnum = 0;
while($cats = mysql_fetch_array($select))
{
if($catnum != 0)
{
echo("<br />");
}
echo("<b>$cats[title]</b>");
$gettuts = mysql_query("select * from tutorials where cat = '$cats[id]' order by 'id' asc");
while($tuts = mysql_fetch_array($gettuts))
{
echo("<br />&nbsp; <a href=\"?x=admin&act=tutorials&func=delete&id=$tuts[id]\">$tuts[title]</a>");
}
$catnum = $catnum+1;
}
endbox();
}
break;
}
break;
case 'tutcat':
switch($_GET[func])
{
default:
boxtop2("Tutorial Category Managment");
bar("Select an option");
echo("<a href=\"?x=admin&act=tutcat&func=new\">New Category</a> · <a href=\"?x=admin&act=tutcat&func=edit\">Edit Category</a> · <a href=\"?x=admin&act=tutcat&func=delete\">Delete Category</a>");
endbox();
endbox();
break;
case 'new':
if(!$_POST[add])
{
boxtop("Add a category");
echo("<form method=\"post\" style=\"margin: 0xp;\">
<dl style=\"margin:0px;\">
<dt>Category Title</dt>
<dd><input type=\"text\" name=\"title\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>&nbsp;</dt>
<dd><input type=\"submit\" value=\"Add Categpory\" name=\"add\" style=\"border: 1px solid #808080; background-color: #e6e6e6; font-family:Verdana; font-size:10px; width:96; height:18\" /></dd>
</dl>
</form>");
endbox();
}
else
{
$insert = mysql_query("INSERT INTO `tutcat` ( `title` )
VALUES (
'$_POST[title]')");
boxtop("Thank You");
echo("The tutorial category \"$_POST[title]\" has been added.");
endbox();
}
break;
case 'edit':
if($_GET[id])
{
if(!$_POST[add])
{
$cat = mysql_query("select * from tutcat where id = '$_GET[id]'");
$cat = mysql_fetch_array($cat);
boxtop("Updating $cat[title]");
echo("<form method=\"post\" style=\"margin: 0xp;\">
<dl style=\"margin:0px;\">
<dt>Category Title</dt>
<dd><input value=\"$cat[title]\" type=\"text\" name=\"title\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>&nbsp;</dt>
<dd><input type=\"submit\" value=\"Update Categpory\" name=\"add\" style=\"border: 1px solid #808080; background-color: #e6e6e6; font-family:Verdana; font-size:10px; width:96; height:18\" /></dd>
</dl>
</form>");
endbox();
}
else
{
$insert = mysql_query("update tutcat set `title` = '$_POST[title]' where id = '$_GET[id]'");
boxtop("Thank You");
echo("The tutorial category has been updated.");
endbox();
}
}
else
{
boxtop("Select a category to update");
$getcats = mysql_query("select * from tutcat order by 'id' asc");
$n = 0;
while($cats = mysql_fetch_array($getcats))
{
if($n != 0)
{
echo("<br />");
}
echo("<a href=\"?x=admin&act=tutcat&func=edit&id=$cats[id]\">$cats[title]</a>");
$n = $n+1;
}
endbox();
}
break;
case 'delete':
if($_GET[id])
{
boxtop("Thank You");
echo("The tutorial category you selected has been deleted");
endbox();
$delete = mysql_query("delete from tutcat where id = '$_GET[id]'");
}
else
{
boxtop("Select a category to delete");
$getcats = mysql_query("select * from tutcat order by 'id' asc");
$n = 0;
while($cats = mysql_fetch_array($getcats))
{
if($n != 0)
{
echo("<br />");
}
echo("<a href=\"?x=admin&act=tutcat&func=delete&id=$cats[id]\">$cats[title]</a>");
$n = $n+1;
}
endbox();
}
break;
}
break;
case 'affiliates':
if($logged[level] >= 8)
{
switch($_GET[func])
{
default:
boxtop2("Affiliate Managment");
bar("Select an option");
echo("<a href=\"?x=admin&act=affiliates&func=new\">New Affiliate</a> · <a href=\"?x=admin&act=affiliates&func=approve\">Approve Affiliate</a> · <a href=\"?x=admin&act=affiliates&func=edit\">Edit Affiliate</a> · <a href=\"?x=admin&act=affiliates&func=delete\">Delete Affiliate</a>");
endbox();
break;
case 'new':
if (!$_POST[apply])
{
boxtop("Add New Affiliate");
echo <<<EOF
<DL style="margin: 0px">
<form method="post" style="margin: 0px;">
<DT>Site URL
<DD><input type="text" name="url" size="20" style="border: 1px solid #808080; background-color: #EFEFEF" value="http://" />
<DT>Button URL
<DD><input type="text" name="button" size="20" style="border: 1px solid #808080; background-color: #EFEFEF" value="http://" />
<DT>Email Address
<DD><input type="text" name="email" size="20" style="border: 1px solid #808080; background-color: #EFEFEF" value="" />
<DT>&nbsp;
<DD><input type="submit" value="Submit" name="apply">
</DL>
</form>
EOF;
endbox();
}
else
{
$email = htmlspecialchars(addslashes("$_POST[email]"));
$button = htmlspecialchars(addslashes("$_POST[button]"));
$url = htmlspecialchars($_POST[url]);
boxtop("Thank You");
echo("The affiliate has been added");
endbox();
$insert = mysql_query("INSERT INTO `affiliates` ( `id` , `url` , `clicks` , `owner` , `button` , `active` , `email` )
VALUES (
'', '$_POST[url]', '0', '$logged[username]', '$_POST[button]', 'yes', '$_POST[email]'
)");
}
break;
case 'edit':
if($_GET[id])
{
if (!$_POST[apply])
{
$aff = mysql_query("select * from affiliates where id = '$_GET[id]'");
$affnum = mysql_nuM_rows($aff);
if($affnum == 0)
{
boxtop("Error");
echo("Invalid Affiliate");
endbox();
}
else
{
$aff = mysql_fetch_array($aff);
boxtop("Editing Affiliate");
echo <<<EOF
<DL style="margin: 0px">
<form method="post" style="margin: 0px;">
<DT>Site URL
<DD><input type="text" name="url" size="20" style="border: 1px solid #808080; background-color: #EFEFEF" value="$aff[url]" />
<DT>Button URL
<DD><input type="text" name="button" size="20" style="border: 1px solid #808080; background-color: #EFEFEF" value="$aff[button]" />
<DT>Email Address
<DD><input type="text" name="email" size="20" style="border: 1px solid #808080; background-color: #EFEFEF" value="$aff[email]" />
<DT>&nbsp;
<DD><input type="submit" value="Submit" name="apply">
</DL>
</form>
EOF;
endbox();
}}
else
{
$email = htmlspecialchars(addslashes("$_POST[email]"));
$button = htmlspecialchars(addslashes("$_POST[button]"));
$url = htmlspecialchars($_POST[url]);
boxtop("Thank You");
echo("The affiliate has been Updated");
endbox();
$insert = mysql_query("update affiliates set button = '$_POST[button]', url = '$_POST[url]', email = '$_POST[email]' where id = '$_GET[id]'");
}}
else
{
boxtop("Select an affilaite to edit");
$getaff = mysql_query("select * from affiliates order by 'id' asc");
$n = 0;
while($aff = mysql_fetch_array($getaff))
{
if($n != 0)
{
echo("<br />");
}
echo("<a href=\"?x=admin&act=affiliates&func=edit&id=$aff[id]\">$aff[url]</a>");
$n = $n+1;
}
endbox();
}
break;
case 'approve':
if($_GET[id])
{
boxtop("Affiliate Approved");
echo("The affiliate you selected has been approved!");
endbox();
$update = mysql_query("update affiliates set active = 'yes' where id = '$_GET[id]'");
}
else
{
boxtop("Select an affiliate");
$getaff = mysql_query("select * from affiliates where active = 'no'");
$affnum = mysql_num_rows($getaff);
if($affnum == 0)
{
echo("There are 0 affilaites to approve!");
}
else
{
$n = 0;
while($aff = mysql_fetch_array($getaff))
{
if($n != 0)
{
echo("<br />");
}
echo("<a href=\"$aff[url]\">$aff[url]</a> -- <a href=\"?x=admin&act=affiliates&func=approve&id=$aff[id]\">Approve</a>");
$n = $n+1;
}}
endbox();
}
break;
case 'delete':
if($_GET[id])
{
boxtop("Affiliate Deleted");
echo("The affiliate you selected has been deleted!");
endbox();
$update = mysql_query("delete from affiliates where id = '$_GET[id]'");
}
else
{
boxtop("Select an affiliate");
$getaff = mysql_query("select * from affiliates");
$n = 0;
while($aff = mysql_fetch_array($getaff))
{
if($n != 0)
{
echo("<br />");
}
echo("<a href=\"$aff[url]\">$aff[url]</a> -- <a href=\"?x=admin&act=affiliates&func=delete&id=$aff[id]\">Delete</a>");
$n = $n+1;
}
endbox();
}
break;
}
}
else
{
boxtop("Error");
echo("Sorry, but this feature is for admins and moderators only!");
endbox();
}
break;
case 'users':
if($logged[level] >= 9)
{
if($_GET[user])
{
if (!$_POST[update])
{
$user = mysql_query("SELECT * from members where username = '$_GET[user]'");
$user = mysql_fetch_array($user);
if($user[editable] == no)
{
boxtop("Sorry");
echo("Sorry, but you cannot edit this user!");
endbox();
}
else
{
boxtop("Editing $user[username]'s profile");
echo("
					<form method=\"post\" style=\"margin: 0px;\">
<DL style=\"margin: 0px\">
<DT>MSN Messenger
<DD><input type=\"text\" size=\"30\" name=\"msn\" value=\"$user[msn]\" style=\"border: 1px solid #808080; background-color: #EFEFEF; font-family: Verdana; font-size: 10px;\" />
<DT>AOL Messenger
<DD><input size=\"30\" value=\"$user[aim]\" name=\"aim\" style=\"border: 1px solid #808080; background-color: #EFEFEF; font-family: Verdana; font-size: 10px;\">
<DT>Email Address
<DD><input type=\"text\" value=\"$user[email]\" size=\"30\" name=\"emai1\" style=\"border: 1px solid #808080; background-color: #EFEFEF; font-family: Verdana; font-size: 10px;\">
<DT>Age
<DD><input type=\"text\" value=\"$user[age]\" size=\"30\"  name=\"age\" style=\"border: 1px solid #808080; background-color: #EFEFEF; font-family: Verdana; font-size: 10px;\">
<DT>Intrests
<DD><input type=\"text\" value=\"$user[intrests]\" size=\"30\" name=\"intrests\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #EFEFEF\">
<DT>Forum Signature
<DD><textarea cols=\"65\" rows=\"5\" name=\"sig\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #EFEFEF\">$user[sig]</textarea>
<dt>User Level</dt>
<dd><input type=\"text\" size=\"25\" maxlength=\"25\"  style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #EFEFEF\"  name=\"level\"
value=\"$user[level]\"><br />
1: member, 7: Tutorial Staff, 8: Moderator, 9: Administrator</dd>
<DT>&nbsp;
<DD><input name=\"update\" type=\"submit\" value=\"Update Profile\">
</DL>
</form>
");
endbox();
}}
else
{
$email = htmlspecialchars($_POST[emai1]);
$aim = htmlspecialchars($_POST[aim]);
$msn = htmlspecialchars($_POST[msn]);
$locate = htmlspecialchars($_POST[locate]);
$level = htmlspecialchars($_POST[level]);
boxtop("Thank You");
echo ("$_GET[user]'s profile has been updated.");
$update = mysql_query("Update members set email = '$email', msn = '$msn', aim = '$aim', sig = '$_POST[sig]', age = '$_POST[age]', intrests = '$_POST[intrests]', level = '$level' where username = '$_GET[user]'");
endbox();
}}
else
{
boxtop("Select a user to edit");
$getusers = mysql_query("Select * from members order by username asc");
while($users = mysql_fetch_array($getusers))
{
//makes a list of all the users
echo("<a href=\"?x=admin&act=users&user=$users[username]\">$users[username]</a><br />");
//displays the user's names
}
endbox();
}
}

break;

case 'forum':
if($logged[level] >=8)
{
switch($_GET[func])
{
default:

break;
case 'new':
if(!$_POST[add])
{
boxtop("Add a forum");
echo("<form method=\"post\" style=\"margin: 0xp;\">
<dl style=\"margin:0px;\">
<dt>Forum Title</dt>
<dd><input type=\"text\" name=\"title\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Forum Description</dt>
<dd><input type=\"text\" name=\"desc\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Parent Category</dt>
<dd><select size=\"1\" style=\"background-color: #efefef; font-family:Verdana; font-size:10px\" name=\"cat\">
");
$getcats = mysql_query("select * from forum_cats order by id asc");
while ($cats = mysql_fetch_array($getcats))
{
echo("
<option value=\"$cats[id]\">$cats[title]</option>");
}
echo("
</select></dd><dt>&nbsp;</dt>
<dd><input type=\"submit\" value=\"Add Categpory\" name=\"add\" style=\"border: 1px solid #808080; background-color: #e6e6e6; font-family:Verdana; font-size:10px; width:96; height:18\" /></dd>
</dl>
</form>");
endbox();
}
else
{
$insert = mysql_query("INSERT INTO `forum_forums` ( `title` , `desc`, `parent` )
VALUES (
'$_POST[title]', '$_POST[desc]', '$_POST[cat]')");
boxtop("Thank You");
echo("The forum \"$_POST[title]\" has been added.");
endbox();
}
break;
case 'edit':
if($_GET[id])
{
if(!$_POST[add])
{
$forum = mysql_query("select * from forum_forums where id = '$_GET[id]'");
$forum = mysql_fetch_array($forum);
boxtop("Updating $cat[title]");
echo("<form method=\"post\" style=\"margin: 0xp;\">
<dl style=\"margin:0px;\">
<dt>Forum Title</dt>
<dd><input value=\"$forum[title]\" type=\"text\" name=\"title\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Forum Description</dt>
<dd><input value=\"$forum[desc]\" type=\"text\" name=\"desc\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Minimum User Level Required to Post a New Topic</dt>
<dd><input value=\"$forum[minlevel]\" type=\"text\" name=\"minlevel\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Parent Category</dt>
<dd><select size=\"1\" style=\"background-color: #efefef; font-family:Verdana; font-size:10px\" name=\"cat\">
");
$getcats = mysql_query("select * from forum_cats order by id asc");
while ($cats = mysql_fetch_array($getcats))
{
echo("
<option value=\"$cats[id]\"");
if($cats[id] == $forum[parent])
{
echo(" selected");
}
echo(">$cats[title]</option>");
}
echo("
</select></dd><dt>&nbsp;</dt>
<dd><input type=\"submit\" value=\"Add Categpory\" name=\"add\" style=\"border: 1px solid #808080; background-color: #e6e6e6; font-family:Verdana; font-size:10px; width:96; height:18\" /></dd>
</dl>
</form>");
endbox();
}
else
{
$update = mysql_query("update forum_forums set `title` = '$_POST[title]', `desc` = '$_POST[desc]', parent = '$_POST[cat]', minlevel = '$_POST[minlevel]' where id = '$_GET[id]'");
boxtop("Thank You");
echo("The tutorial category has been updated.");
endbox();
}
}
else
{
boxtop("Select a category to update");
$getcats = mysql_query("select * from forum_cats order by 'order' asc");
$n = 0;
while($cats = mysql_fetch_array($getcats))
{
if($n != 0)
{
echo("<br />");
}
echo("$cats[title]");
$getforums = mysql_query("Select * from forum_forums where parent = '$cats[id]' order by 'id' asc");
while($forums = mysql_fetch_array($getforums))
{
echo("<br /> &nbsp; <a href=\"?x=admin&act=forum&func=edit&id=$forums[id]\">$forums[title]</a>");
}
$n = $n+1;
}
endbox();
}
break;
case 'delete':
if($_GET[id])
{
$delete = mysql_query("delete from forum_forums where id = '$_GET[id]'");
boxtop("Forum Deleted");
echo("The forum you selected has been deleted");
endbox();
}
else
{
boxtop("Select a forum to delete");
$getcats = mysql_query("select * from forum_cats order by 'order' asc");
$n = 0;
while($cats = mysql_fetch_array($getcats))
{
if($n != 0)
{
echo("<br />");
}
echo("$cats[title]");
$getforums = mysql_query("Select * from forum_forums where parent = '$cats[id]' order by 'id' asc");
while($forums = mysql_fetch_array($getforums))
{
echo("<br /> &nbsp; <a href=\"?x=admin&act=forum&func=delete&id=$forums[id]\">$forums[title]</a>");
}
$n = $n+1;
}
endbox();
}
break;
}
}
else
{
boxtop("Error");
echo("Sorry, but this feature is for admins and moderators only!");
endbox();
}
break;
case 'forumcat':
if($logged[level] >= 8)
{
switch($_GET[func])
{
default:
echo("<a href=\"?x=admin&act=forumcat&func=new\">New Category</a> · <a href=\"?x=admin&act=forumcat&func=edit\">Edit Category</a> · <a href=\"?x=admin&act=forumcat&func=deletecat\">Delete Category</a>");
break;
case 'new':
if(!$_POST[add])
{
boxtop("Add a Forum Category");
echo("<form method=\"post\" style=\"margin: 0xp;\">
<dl style=\"margin:0px;\">
<dt>Category Title</dt>
<dd><input type=\"text\" name=\"title\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Position (1 is top)</dt>
<dd><input type=\"text\" name=\"position\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>&nbsp;</dt>
<dd><input type=\"submit\" value=\"Add Categpory\" name=\"add\" style=\"border: 1px solid #808080; background-color: #e6e6e6; font-family:Verdana; font-size:10px; width:96; height:18\" /></dd>
</dl>
</form>
Note: If categories have the same position, they will be ordered depending on the order in which they were created.  Decimals may be used.");
endbox();
}
else
{
$insert = mysql_query("INSERT INTO `forum_cats` ( `title` , `order` )
VALUES (
'$_POST[title]', '$_POST[position]')");
boxtop("Thank You");
echo("The forum category \"$_POST[title]\" has been added.");
endbox();
}
break;
case 'edit':
if($_GET[id])
{
if(!$_POST[add])
{
$cat = mysql_query("Select * from forum_cats where id = '$_GET[id]'");
$cat = mysql_fetch_array($cat);
boxtop("Update a Forum Category");
echo("<form method=\"post\" style=\"margin: 0xp;\">
<dl style=\"margin:0px;\">
<dt>Forum Title</dt>
<dd><input value=\"$cat[title]\" type=\"text\" name=\"title\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Position (1 is top)</dt>
<dd><input value=\"$cat[order]\" type=\"text\" name=\"position\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>&nbsp;</dt>
<dd><input type=\"submit\" value=\"Update Categpory\" name=\"add\" style=\"border: 1px solid #808080; background-color: #e6e6e6; font-family:Verdana; font-size:10px; width:96; height:18\" /></dd>
</dl>
</form>
Note: If categories have the same position, they will be ordered depending on the order in which they were created.  Decimals may be used.");
endbox();
}
else
{
$insert = mysql_query("update forum_cats set `title` = '$_POST[title]', `order` = '$_POST[position]' where id = '$_GET[id]'");
boxtop("Thank You");
echo("The forum category you selected has been updated.");
endbox();
}
}
else
{
boxtop("Select a Category");
$getcats = mysql_query("Select * from forum_cats");
$n = 0;
while($cats = mysql_fetch_array($getcats))
{
if($n != 0)
{
echo("<br />");
}
$n = $n+1;
echo("<a href=\"?x=admin&act=forumcat&func=edit&id=$cats[id]\">$cats[title]</a>");
}
endbox();
}
break;
case 'delete':
if($_GET[id])
{
$delete = mysql_query("delete from forum_cats where id = '$_GET[id]'");
boxtop("Category Deleted");
echo("The category you selected has been deleted");
endbox();
}
else
{
boxtop("Select a Category");
$getcats = mysql_query("Select * from forum_cats");
$n = 0;
while($cats = mysql_fetch_array($getcats))
{
if($n != 0)
{
echo("<br />");
}
$n = $n+1;
echo("<a href=\"?x=admin&act=forumcat&func=delete&id=$cats[id]\">$cats[title]</a>");
}
endbox();
}
break;
}
}
else
{
boxtop("Error");
echo("Sorry, but this feature is for admins and moderators only!");
endbox();
}
break;
case 'poll':
if($logged[level] >= 8)
{
switch($_GET[func])
{
default:
boxtop("Make a selection");
echo ("<a href=\"?x=admin&act=poll&func=new\">New Poll</a><br />
<a href=\"?x=admin&act=poll&func=editvotes\">Edit/Delete Votes</a>");
endbox();
break;
case 'new':
$getpollid = mysql_query("SELECT * from poll order by 'id' desc limit 1");
$pollid = mysql_fetch_array($getpollid);
$pollid[id] = $pollid[id] + 1;
if (!$_POST[pquestion])
{
boxtop("Enter Poll Information");
echo ("
<form method=\"post\" style=\"margin: 0xp;\">
<dl style=\"margin:0px;\">
<dt>Poll Question</dt>
<dd><input type=\"text\" value=\"Poll Question\" name=\"pquestion\" size=\"20\" style=\"font-family:Verdana; font-size:10px; width:96; border: 1px solid #000000; background-color: #E6E6E6\"></dd>
<dt>Poll Choice 1</dt>
<dd><input type=\"text\" name=\"pchoice1\" size=\"20\" style=\"font-family:Verdana; font-size:10px; width:96; border: 1px solid #000000; background-color: #E6E6E6\"></dd>
<dt>Poll Choice 2</dt>
<dd><input type=\"text\" name=\"pchoice2\" size=\"20\" style=\"font-family:Verdana; font-size:10px; width:96; border: 1px solid #000000; background-color: #E6E6E6\"></dd>
<dt>Poll Choice 3</dt>
<dd><input type=\"text\" name=\"pchoice3\" size=\"20\" style=\"font-family:Verdana; font-size:10px; width:96; border: 1px solid #000000; background-color: #E6E6E6\"></dd>
<dt>Poll Choice 4</dt>
<dd><input type=\"text\" name=\"pchoice4\" size=\"20\" style=\"font-family:Verdana; font-size:10px; width:96; border: 1px solid #000000; background-color: #E6E6E6\"></dd>
<dt>Poll Choice 5</dt>
<dd><input type=\"text\" name=\"pchoice5\" size=\"20\" style=\"font-family:Verdana; font-size:10px; width:96; border: 1px solid #000000; background-color: #E6E6E6\"></dd>
<dt>Poll Choice 6</dt>
<dd><input type=\"text\" name=\"pchoice6\" size=\"20\" style=\"font-family:Verdana; font-size:10px; width:96; border: 1px solid #000000; background-color: #E6E6E6\"></dd>
<dt>Poll Choice 7</dt>
<dd><input type=\"text\" name=\"pchoice7\" size=\"20\" style=\"font-family:Verdana; font-size:10px; width:96; border: 1px solid #000000; background-color: #E6E6E6\"></dd>
<dt>Poll Choice 8</dt>
<dd><input type=\"text\" name=\"pchoice8\" size=\"20\" style=\"border: 1px solid #000000; font-family:Verdana; font-size:10px; width:96; background-color: #E6E6E6\"></dd>
<dt>&nbsp;</dt>
<dd><input type=\"submit\" value=\"Submit\" name=\"B1\" style=\"font-family:Verdana; font-size:10px; width:96; border: 1px solid #000000; background-color: #E6E6E6\"></dd>
<dt>&nbsp;</dt>
<dd>
<input type=\"submit\" value=\"Submit\" name=\"B1\" style=\"border: 1px solid #000000; background-color: #E6E6E6\"></dd></form>
");
endbox();
}
if ($_POST[pquestion])
{
$newforumadd = mysql_query("INSERT INTO `poll` ( `question` )VALUES ('$_POST[pquestion]')");
$query = mysql_query("INSERT INTO `poll_choices` ( `id` , `pollid` , `text` , `votes` )
VALUES (
'', '$pollid[id]', '$_POST[pchoice1]', '0'
)");
if (!empty($_POST[pchoice2]))
{
$query = mysql_query("INSERT INTO `poll_choices` ( `id` , `pollid` , `text` , `votes` )
VALUES (
'', '$pollid[id]', '$_POST[pchoice2]', '0'
)");
}
if (!empty($_POST[pchoice3]))
{
$query = mysql_query("INSERT INTO `poll_choices` ( `id` , `pollid` , `text` , `votes` )
VALUES (
'', '$pollid[id]', '$_POST[pchoice3]', '0'
)");
}
if (!empty($_POST[pchoice4]))
{
$query = mysql_query("INSERT INTO `poll_choices` ( `id` , `pollid` , `text` , `votes` )
VALUES (
'', '$pollid[id]', '$_POST[pchoice4]', '0'
)");
}
if (!empty($_POST[pchoice5]))
{
$query = mysql_query("INSERT INTO `poll_choices` ( `id` , `pollid` , `text` , `votes` )
VALUES (
'', '$pollid[id]', '$_POST[pchoice5]', '0'
)");
}
if (!empty($_POST[pchoice6]))
{
$query = mysql_query("INSERT INTO `poll_choices` ( `id` , `pollid` , `text` , `votes` )
VALUES (
'', '$pollid[id]', '$_POST[pchoice6]', '0'
)");
}if (!empty($_POST[pchoice7]))
{
$query = mysql_query("INSERT INTO `poll_choices` ( `id` , `pollid` , `text` , `votes` )
VALUES (
'', '$pollid[id]', '$_POST[pchoice7]', '0'
)");
}
if (!empty($_POST[pchoice8]))
{
$query = mysql_query("INSERT INTO `poll_choices` ( `id` , `pollid` , `text` , `votes` )
VALUES (
'', '$pollid[id]', '$_POST[pchoice8]', '0'
)");
}
boxtop("Success");
echo ("Poll Added");
endbox();
}
break;
case 'editvotes':
$getp = mysql_query("SELECT * from poll ORDER BY id DESC limit 1");
$p = mysql_fetch_array($getp);
$getvotes = mysql_query("SELECT * from poll_votes where poll_id = '$p[id]'");
if (!$_GET[voteid] && !$_GET[deleteid])
{
boxtop2("Votes for current poll");
bar ("
<table border=\"0\" width=\"100%\" id=\"table1\" cellspacing=\"0\" cellpadding=\"2\">
<tr>
<td width=\"25%\"><b>Username</b></td>
<td width=\"25%\"><b>Choice</b></td>
<td width=\"25%\"><b>Edit</b></td>
<td width=\"25%\"><b>Delete</b></td>
</tr></table>");
echo("<table border=\"0\" width=\"100%\" id=\"table1\" cellspacing=\"0\" cellpadding=\"2\">");
while($votes = mysql_fetch_array($getvotes))
{
echo ("
<tr>
<td width=\"25%\">$votes[username]</td>
<td width=\"25%\">$votes[choice]</td>
<td width=\"25%\"><a href=\"?x=admin&act=poll&func=editvotes&voteid=$votes[id]\">Edit</a></td>
<td width=\"25%\"><a href=\"?x=admin&act=poll&func=editvotes&deleteid=$votes[id]\">Delete</a></td>
</tr>");
}
echo ("
</table>");

endbox();
}
if ($_GET[voteid] && !$_GET[deleteid])
{
if (!$_POST[editchoice])
{
$getp = mysql_query("SELECT * from poll ORDER BY id DESC limit 1");
$p = mysql_fetch_array($getp);
$getvotes = mysql_query("SELECT * from poll_votes where poll_id = '$p[id]'");
$votes = mysql_fetch_array($getvotes);
$getchoice1 = mysql_query("SELECT * from poll_choices WHERE pollid = '$p[id]' order by 'id' asc");
boxtop("Editing Vote");
echo ("
<table border=\"0\" align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" id=\"table1\"><form method=\"POST\">
");
while ($choices = mysql_fetch_array($getchoice1)){
echo ("<tr>
<td width=\"10\"><input type=\"radio\" value=\"$choices[id]\" name=\"editchoice\"></td><td align=\"left\">$choices[text]</td></tr>");
}
echo ("
<tr>
<td align=\"center\" colspan=\"2\">");
echo ("<input type=\"submit\" value=\"Submit\" name=\"pollsubmit\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #000000; background-color: #E6E6E6\">");
echo ("</td>
</form>
</tr>
</table>");
endbox();
}
if ($_POST[editchoice])
{
$getp = mysql_query("SELECT * from poll ORDER BY id DESC limit 1");
$p = mysql_fetch_array($getp);
$getvote  = mysql_query("SELECT * from poll_votes where id = '$_GET[voteid]'");
$vote = mysql_fetch_array($getvote);
$updatenum = mysql_query("Update poll_choices set votes = votes - 1 where id = '$vote[choice]' AND pollid = '$p[id]'");
$updatechoice = mysql_query("UPdate poll_votes set choice = '$_POST[editchoice]' where username = '$vote[username]'");
$updatechoice = mysql_query("UPdate poll_choices set votes = votes + 1 where id = '$_POST[editchoice]' AND pollid = '$p[id]'");
boxtop("Success");
echo ("Vote modified");
endbox();
}
}
if ($_GET[deleteid])
{
$getp = mysql_query("SELECT * from poll ORDER BY id DESC limit 1");
$p = mysql_fetch_array($getp);
$getvote  = mysql_query("SELECT * from poll_votes where id = '$_GET[deleteid]'");
$vote = mysql_fetch_array($getvote);
$updatenum = mysql_query("Update poll_choices set votes = votes - 1 where id = '$vote[choice]' AND pollid = '$p[id]'");
$deletevote = mysql_query("Delete FROM poll_votes WHERE id = '$_GET[deleteid]'") or die(mysql_error()); 
boxtop("Success");
echo ("Vote Deleted");
endbox();
}
break;
}
}
else
{
boxtop("Error");
echo("Sorry, but this feature is for admins and moderators only!");
endbox();
}
break;
}
}
else
{
boxtop("Error");
echo("You are either not logged in, or you are not an admin!");
endbox();
}
?>

