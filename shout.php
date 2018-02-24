<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>New Page 1</title>
</head>

<body>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="200">
<iframe style="margin: 2px;" name="shouts" width="181" height="148" src="shouts.php" frameborder="0" marginwidth="1" marginheight="1">
Your browser does not support inline frames or is currently configured not to display inline frames.
</iframe>

		</td>
		<td valign="top">
		<?
		$numshouts = mysql_query("select * from shouts");
		$numshouts = mysql_num_rows($numshouts);
	if($logged[username])
	{
	echo<<<EOF
	<form style="margin:0px;" name="shouts" action="shouts.php" method="POST" target="shouts">
	
<input style="border:1px solid #808080; margin:2px; background-color:#EFEFEF; font-family:Verdana; font-size:10px" type="text" name="url" size="20" value="
EOF;
if($_COOKIE[url])
{
echo("$_COOKIE[url]");
}
else
{
echo("URL");
}
echo <<<EOF
"><br>
	<textarea rows="7" style="border:1px solid #808080; margin:2px; background-color:#EFEFEF; font-family:Verdana; font-size:10px" name="message" maxlength="12" cols="30">Message Here</textarea><br>
	<input type="submit" style="border:1px solid #808080; margin:2px; background-color:#efefef; font-family:Verdana; font-size:10px" value="Shout" name="shout">
</form>
<a href="?view=bbcode">BBcode Guide</a>
EOF;
}
else
{
include("includes/login.php");
}
?>
</td>
	</tr>
	</table>
</body>

</html>