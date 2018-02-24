<?
if (!$_POST[add])
{
boxtop("Submit a  Download");
echo("<form method=\"post\" name=\"Download\" style=\"margin: 0xp;\">
<dl style=\"margin:0px;\">

<dt>Download Title</dt>
<dd><input type=\"text\" name=\"title\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Short Description</dt>
<dd><input type=\"text\" name=\"desc\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Author Homepage</dt>
<dd><input type=\"text\" name=\"authorpage\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>
<dt>Download Url</dt>
<dd><input type=\"text\" name=\"url\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px\" size=\"30\" /></dd>

<dt>Download Category</dt>
<dd>
<select size=\"1\" style=\"background-color: #efefef; font-family:Verdana; font-size:10px\" name=\"cat\">
");
$getcats = mysql_query("select * from download_cats order by id asc");
while ($cats = mysql_fetch_array($getcats))
{
echo("
<option value=\"$cats[id]\">$cats[title]</option>");
}
echo("
</select></dd>
<dt>Download Description</dt>
<dd>
<textarea cols=\"53\" rows=\"12\" name=\"desc2\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #efefef;\">
Description of the download here...
</textarea></dd>
<dt>&nbsp;</dt>
<dd>
<input type=\"submit\" value=\"Publish Download\" name=\"add\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px; width:96; height:18\" /></dd>
</dl>
</form>");
endbox();
}
else
{
$title = htmlspecialchars(addslashes("$_POST[title]"));
$desc = htmlspecialchars(addslashes("$_POST[desc]"));
$insert = mysql_query("INSERT INTO `downloads` ( `id` , `title` , `cat` , `text` , `author` , `views` , `desc` , `active` , `authorpage` )
VALUES (
'', '$title', '$_POST[cat]', '$body', '$logged[username]', '2', '$desc', 'no', 'authorpage'
)");
boxtop("Success");
echo("Thank you!  The Download has been submitted.  An administrator must approve it before it shows up on the site.");
endbox();
}
?>