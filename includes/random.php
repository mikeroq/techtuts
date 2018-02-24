<center><?
$random = mysql_query("select * from affiliates WHERE active='yes' ORDER BY RAND() LIMIT 0, 4"); //makes query to db selecting affiliates
while($aff = mysql_fetch_array($random))
{
echo ("<a target=\"_BLANK\" href=\"out.php?mode=affiliate&amp;id=$aff[id]\"><img alt=\"$aff[title]\" width=\"88\" height=\"31\" style=\"margin: 1px; padding: 1px;\"border=\"0\" src=\"$aff[button]\" /></a>");
}
?>
</center>