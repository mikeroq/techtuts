<?
	$get = mysql_query("SELECT * from affiliates where active = 'yes' order by id desc");
	boxtop("Our Affiliates");
	while($affiliates = mysql_fetch_array($get))
	{
		echo("<a target=\"_BLANK\" href=\"out.php?mode=affiliate&amp;id=$affiliates[id]\"><img alt=\"affiliate\" style=\"padding: 1px; margin: 1px;\" width=\"88\" height=\"31\" src=\"$affiliates[button]\" border=\"0\" /></a> ");
	}
	endbox();
if ($logged[username])
{
if (!$_POST[apply])
{
boxtop("Our Button");
echo("<a href=\"button.gif\"><img src=\"button.gif\" width=\"88\" height=\"31\" border=\"0\" alt=\"button\" /></a>");
endbox();
boxtop("Apply for Affiliation");
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
$email = htmlspecialchars($_POST[email]);
$button = htmlspecialchars($_POST[button]);
$url = htmlspecialchars($_POST[url]);
if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
boxtop("Error");
echo "Invalid email";
endbox();
}else{
boxtop("Thank You");
echo("Your affiliate request has been submitted.  Please wait for an administrator to approve it");
endbox();
$insert = mysql_query("INSERT INTO `affiliates` ( `id` , `url` , `clicks` , `owner` , `button` , `active` , `email` )
VALUES (
'', '$url', '0', '$logged[username]', '$button', 'no', '$email'
)");
}
}
}
else
{
boxtop("Error");
echo("You must login to apply for affiliation");
endbox();
}
?>