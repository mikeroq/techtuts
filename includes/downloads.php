<?
switch($_GET[act])
{
case 'download':
if (!$_GET[id])
{
boxtop("Error");
echo("You selected an invalid download!  Please go back and try again");
endbox();
}
else
{
$gedl = mysql_query("SELECT * from downloads where id = '$_GET[id]'");
$numdl = mysql_num_rows($gedl);
if ($numdl == 0)
{
boxtop("Error");
echo("You selected an invalid download!  Please go back and try again");
endbox();
}
else
{
$update = mysql_query("update downloads set views = views+1 where id = '$_GET[id]'");
$dl = mysql_fetch_array($gedl);
if(!$_POST[comment])
{
boxtop("$dl[title]");
$dl[text] = $bbcode->parse($dl[text]);
$dl[text] = str_replace("&amp;", "&", $dl[text]);
$dl[text] = str_replace("[img]", "<img border='0' src='", $dl[text]);
$dl[text] = str_replace("[/img]", "' />", $dl[text]);
echo("$dl[text]");
endbox();
boxtop("Choose an option");
echo <<<EOF
<a href="#">View Homepage</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">Veiw Screenshot</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">Download File</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">Rate File</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">Dead Download</a>
EOF;
endbox();
}
}
}
break;
default:
$color2 = "#e6e6e6";
$color1 = "#efefef";
$row_count = 0;
$getcats = mysql_query("SELECT * from download_cats order by 'id' asc");
boxtop2("Select a Category");
bar("<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
		<td>Category Name</td>
		<td width=\"75\"># downloads</td>
		<td align=\"center\" width=\"175\">Latest download</td>
	</tr>
	</table>
");
echo("<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">");
while ($cats = mysql_fetch_array($getcats))
{
$row_color = ($row_count % 2) ? $color1 : $color2;
$row_count++;
$latest = mysql_query("SELECT * from downloads where cat = '$cats[id]' and active = 'yes' order by 'id' desc limit 1");
$latestnum = mysql_num_rows($latest);
$numdls = mysql_query("Select * from downloads where active = 'yes' AND cat = '$cats[id]'");
$numdls = mysql_num_rows($numdls);
echo("
	<tr>
		<td height=\"18\" bgcolor=\"$row_color\"><a href=\"?view=downloads&act=cat&id=$cats[id]\">$cats[title]</a></td>
		<td bgcolor=\"$row_color\" width=\"75\">&nbsp;&nbsp; $numdls</td>
		<td bgcolor=\"$row_color\" width=\"175\">");
		if ($latestnum == 0)
		{
		echo("No Downloads Yet");
		}
		else
		{
		$latest = mysql_fetch_array($latest);
		echo("<a href=\"$latest[id]\">$latest[title]</a>");
		}
		echo("</td>
	</tr>");
}
echo("</table>");
endbox();
break;
case 'cat':
$color2 = "#e6e6e6";
$color1 = "#efefef";
$row_count = 0;
if (!$_GET[id])
{
boxtop("Error");
echo("You have selected an invalid download category!  Please go back and try again");
endbox();
}
else
{
$getcat = mysql_query("SELECT * from download_cats where id = '$_GET[id]'");
$catnum = mysql_num_rows($getcat);
if ($catnum == 0)
{
boxtop("Error");
echo("You have selected an invalid download category!  Please go back and try again");
endbox();
}
else
{
$cat = mysql_fetch_array($getcat);
$getdl = mysql_query("SELECT * from downloads where cat = '$cat[id]' and active = 'yes' order by 'id' desc");
$numdls = mysql_num_rows($getdl);
if ($numdls == 0)
{
boxtop("$cat[title] Downloads");
echo("There are currently 0 downloads in this category!");
endbox();
}
else
{
$color2 = "#e6e6e6";
$color1 = "#efefef";
$row_count = 0;
boxtop2("$cat[title] Downloads");
bar("<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
		<td>Download Name and Description</td>
		<td width=\"100\"># Views</td>
		<td width=\"125\">Author</td>
	</tr>
	</table>
");
echo("<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">");

while($dls = mysql_fetch_array($getdl))
{
$row_color = ($row_count % 2) ? $color1 : $color2;
$row_count++;
echo ("
<tr>
		<td bgcolor=\"$row_color\"><a href=\"?view=downloads&act=download&id=$dls[id]\">$dls[title]</a></td>
		<td bgcolor=\"$row_color\" rowspan=\"2\" width=\"100\">&nbsp;$dls[views]</td>
		<td bgcolor=\"$row_color\" rowspan=\"2\" width=\"125\"><a href=\"$dls[authorpage]\">$dls[author]</a></td>
	</tr>
	<tr>
	<td bgcolor=\"$row_color\">&nbsp;&nbsp;$dls[desc]</td></tr>");
}
echo("</table>");
endbox();
}
}}
break;
}
?>