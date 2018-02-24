<?
if($logged[username] && $logged[level] >=1)
{
if (!$_POST[add])
{
boxtop("Submit a  Tutorial");
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
$getcats = mysql_query("select * from tutorial_cats order by id asc");
while ($cats = mysql_fetch_array($getcats))
{
echo("
<option value=\"$cats[id]\">$cats[title]</option>");
}
echo("
</select></dd>
<dt>Tutorial Text</dt>
<dd>
<textarea cols=\"53\" rows=\"12\" name=\"body\" style=\"font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #efefef;\">
BBcode is enabled:
[code]Code Here[/code]
[b]Bold text[/b]
</textarea></dd>
<dt>&nbsp;</dt>
<dd>
<input type=\"submit\" value=\"Publish Tutorial\" name=\"add\" style=\"border: 1px solid #808080; background-color: #efefef; font-family:Verdana; font-size:10px; width:96; height:18\" /></dd>
</dl>
</form>");
endbox();
}
else
{
$title = htmlspecialchars(addslashes("$_POST[title]"));
$desc = htmlspecialchars(addslashes("$_POST[desc]"));
$body= htmlspecialchars(addslashes("$_POST[body]"));
$insert = mysql_query("INSERT INTO `tutorials` ( `id` , `title` , `cat` , `text` , `author` , `views` , `desc` , `active` )
VALUES (
'', '$title', '$_POST[cat]', '$body', '$logged[username]', '2', '$desc', 'no'
)");
boxtop("Success");
echo("Thank you!  The tutorial has been submitted.  An administrator must approve it before it shows up on the site.");
endbox();
}
}
?>