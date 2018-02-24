<?
	if (!$logged[username])
	{
		boxtop("Error");
		echo("You must be logged in to view this page!");
		endbox();
	}
	else
	{
	switch($_GET[mode])
	{
		default:
			boxtop("Please select an option from the list below");
			echo("<a href=\"?view=cpanel&mode=profile\">Edit Profile</a><br />
			<a href=\"?view=cpanel&mode=messenger\">Personal Messenger</a><br />
			<a href=\"?view=cpanel&mode=avatar\">Avatar Settings</a><br />
			<a href=\"?view=cpanel&mode=password\">Change Password</a><br />

			<a href=\"?view=logout\">Logout</a>");
			endbox();
		break;
		case 'profile':
			if (!$_POST[update])
			{
			boxtop("Edit your profile");
				$getuser = mysql_query("SELECT * from members where username = '$logged[username]'");
				$user=  mysql_fetch_array($getuser);
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

<DT>&nbsp;
<DD><input name=\"update\" type=\"submit\" value=\"Update Profile\">
</DL>
</form>
");
endbox();
			}
			else
			{
$msn = htmlspecialchars($_POST[msn]);
$aim = htmlspecialchars($_POST[aim]);
$age = htmlspecialchars($_POST[age]);
$sig = htmlspecialchars($_POST[sig]);
$intrests = htmlspecialchars($_POST[intrests]);
$query = mysql_query("update members set msn = '$msn', aim = '$aim', age ='$age', intrests = '$intrests', sig = '$sig' where username = '$logged[username]'");
boxtop("Profile updated");
echo("Your profile has been updated!");
endbox();
	}
		break;
		case 'avatar':
	
		$username = $logged[username];
$maxwidth = "100"; // Max width allowed for avatars
$maxheight = "100"; // Max height allowed for avatars

if (isset($_POST['upload'])) {
$filetype = $_FILES['file']['type'];
$filetypex = substr($filetype,0,5);

if ($filetypex == image) {
$newid = "images/avatars/";
$newid .= "$username";
$newid .= ".gif";


$mysock = getimagesize($_FILES['file']['tmp_name']);
$imagewidth = $mysock[0];
$imageheight = $mysock[1];
if ($imagewidth <= "$maxwidth" && $imageheight <= "$maxheight") {
if(!(copy($_FILES['file']['tmp_name'], $newid))) die("Cannot upload files.");
boxtop("Thank You!");
echo "Your avatar has been uploaded";
endbox();
}
else {
boxtop("Error");
echo "The avatar you selected is too big.  Please select an image that is less than (or equal to) 100x100 pixels";
endbox();
}

}
else {
boxtop("Error");
echo "The file you selected is not an image!  Please select a new avatar";
endbox();
}
}
else {
	if (!isset($_GET[delete]))
		{
		$filename = 'images/avatars/'.$username.'.gif';
if (file_exists($filename))
		{
boxtop("Your Avatar");
$filename = 'images/avatars/'.$username.'.gif';
if (file_exists($filename)) {
echo '<img src="'.$filename.'" border="0">';
echo("<br /><a href=\"?view=cpanel&mode=avatar&delete\">Click here to delete your avatar</a>");
endbox();
}
}
else {
boxtop("Upload an avatar");
echo "You Do Not Have An Avatar";
?>
<form action="<?=$_SERVER['REQUEST_URI'] ?>" method="post" enctype="multipart/form-data">
<dl style="margin: 0px;">
<dt>Select a file</dt>
<dd><input type="file" name="file" style="border: 1px solid #808080; background-color: #efefef;" size="18"></dd>
<dt>&nbsp;</dt>
<dd><input type="submit" style="border: 1px solid #808080; background-color: #e6e6e6;" value="Upload Avatar" name="upload" size="18"></dd>
</dl>
<?
endbox();
}
}}
 if (isset($_GET[delete])) {
$filename = 'images/avatars/'.$username.'.gif';
if (file_exists($filename)) {
unlink($filename);
boxtop("Avatar Deleted");
echo("Your avatar has been deleted.  Please <a href=\"?view=cpanel&mode=avatar\">Click here</a> to upload a new avatar");
endbox();
}
else
{
boxtop("OOPS!");
echo("You do not have an avatar to delete!  Please go back and upload one");
endbox();
}
}
		break;
case 'messenger':

//checks to see if they are logged in
switch($_GET[page])
{
//this allows us to use one page for the entire thing
default:
boxtop("Private Messenger");
echo ("- <a href=\"?view=cpanel&mode=messenger&page=inbox\">Inbox</a><br />
- <a href=\"?view=cpanel&mode=messenger&page=write\">New Message</a>");
endbox();
break;
case 'write':
if (!$_POST[send])
{
boxtop("Compose New Message");
//the form hasnt been submitted yet....
echo ("<form method=\"POST\" style=\"margin: 0px;\">
    <dl style=\"margin: 0px;\">
            <dt>recipient</dt>
            <dd>
            <select style=\"background-color: #efefef;\" name=\"to\">
");
$getusers = mysql_query("SELECT * FROM members ORDER BY 'username' ASC");
            while ($users = MySQL_Fetch_Array($getusers)) {
    echo ("<option value=\"$users[username]\">$users[username]</option>");
}
//the above line gets all the members names and puts them in a drop down box
echo ("
</select>
</dd>
<dt>Message Subject</dt>
<dd><input type=\"text\" style=\"border: 1px solid #808080; background-color: #efefef;\" name=\"subject\" size=\"20\"></dd>
<dt>Message</dt>
<dd><textarea rows=\"7\" style=\"border: 1px solid #808080; background-color: #efefef;\" name=\"message\" cols=\"35\"></textarea>
</dd><dt>&nbsp;</dt>
<dd><input type=\"submit\" value=\"Submit\" style=\"border: 1px solid #808080; background-color: #e6e6e6;\" name=\"send\"></dd>
</dl>
</form>
");
endbox();
}
if ($_POST[to])
{
//the form has been submitted.  Now we have to make it secure and insert it into the database
$subject = htmlspecialchars("$_POST[subject]");
$message = htmlspecialchars("$_POST[message]");
$to = htmlspecialchars("$_POST[to]");
//the above lines remove html and add \ before all "
$send = mysql_query("INSERT INTO `pmessages` ( `title` , `message` , 
`touser` , `from` , `unread` , 
`date` ) VALUES ('$subject', '$message', '$to', 
'$logged[username]', 'unread', NOW())");
boxtop("Thank You");
echo ("Your message has been sent.");
endbox();
}
break;
case 'delete':
if (!$_GET[msgid])
{
boxtop("Error");
echo ("Sorry, but this is an invalid message!");
endbox();
}
else
{
$getmsg = mysql_query("SELECT * from pmessages where id = '$_GET[msgid]'");
$msg = mysql_fetch_array($getmsg);
//hmm..someones trying to delete someone elses messages!  This keeps them from doing it
if ($msg[touser] != $logged[username])
{
boxtop("Error");
echo ("This message was not sent to you!");
endbox();
}
else
{
boxtop("Thank You");
$delete  = mysql_query("delete from pmessages where id = '$_GET[msgid]'");
echo ("Message Deleted");
endbox();
}
}
break;
case 'inbox':
boxtop2("Inbox");
$get = mysql_query("SELECT * from pmessages where touser = '$logged[username]' order by id desc");
echo("
<table border=\"0\" width=\"100%\" cellspacing=\"0\">
<tr>
<td class=\"bar\" align=\"center\">Subject</td>
<td class=\"bar\" align=\"center\" width=\"125\">From</td>
<td class=\"bar\" align=\"center\" width=\"97\">Date</td>
<td class=\"bar\" width=\"25\">Delete</td>
</tr>
</table><div class=\"content\">
");
$nummessages = mysql_num_rows($get);
if ($nummessages == 0)
{
echo ("You have 0 messages!");
}
else
{
echo("<table border=\"0\" width=\"100%\" cellspacing=\"1\">");
while ($messages = mysql_fetch_array($get))
{
//the above lines gets all the messages sent to you, and displays them with the newest ones on top
echo ("
<tr>
<td><a href=\"?view=cpanel&mode=messenger&page=view&msgid=$messages[id]\">");
if ($messages[reply] == yes)
{
echo ("Reply to: ");
}
echo ("$messages[title]</a></td>
<td width=\"125\">$messages[from]</td>
<td width=\"97\">$messages[date]</td>
<td width=\"25\"><a href=\"?view=cpanel&mode=messenger&page=delete&msgid=$messages[id]\">Delete</a></td>
</tr>");
}
echo ("</table>");
}
endbox();
break;
case 'view':
//the url now should look like ?page=view&msgid=#
if (!$_GET[msgid])
{
//there isnt a &msgid=# in the url
echo ("Invalid message!");
}
else
{
//the url is fine..so we continue...
$getmsg= mysql_query("SELECT * from pmessages where id = '$_GET[msgid]'");
$msg = mysql_fetch_array($getmsg);
//the above lines get the message, and put the details into an array.
if ($msg[touser] == $logged[username])
{
//makes sure that this message was sent to the logged in member
if (!$_POST[message])
{
$msg[title] = stripslashes("$msg[title]");
boxtop("$msg[title]");
//the form has not been submitted, so we display the message and the form
$markread = mysql_query("Update pmessages set unread = 'read' where id = '$_GET[msgid]'");
//this line marks the message as read.
$msg[message] = $bbcode->parse($msg[message]);
$msg[message] = str_replace("&amp;", "&", $msg[message]);
$msg[message] = str_replace("[img]", "<img border='0' src='", $msg[message]);
$msg[message] = str_replace("[/img]", "' />", $msg[message]);
echo("$msg[message]");
//removes slashes and converts new lines into line breaks.
bar2("Send a Reply");
echo ("
<form method=\"POST\" style=\"margin: 0px;\">
<textarea rows=\"6\" style=\"background: #efefef; border: 1px solid #808080;\" name=\"message\" cols=\"45\"></textarea>
<br /><br /><input style=\"background: #e6e6e6; border: 1px solid #808080;\" type=\"submit\" value=\"Submit\" name=\"send\"></dd>
</form>");
endbox();
}
if ($_POST[message])
{
boxtop("Thank You");
//the form HAS been submitted, now we insert it into the database
$message = htmlspecialchars("$_POST[message]");
$do = mysql_query("INSERT INTO `pmessages` ( `title` , `message` , `touser` , `from` , `unread` , 
`date`, `reply`) VALUES
('$msg[title]', '$message', '$msg[from]', '$logged[username]',
 'unread', NOW(), 'yes')");
echo ("Your message has been sent");
endbox();
}
}
else
{
//hmm..this message was NOT sent to the logged in user...so we won't display it.
boxtop("Error");
echo ("This message was not sent to you!");
endbox();
}}
break;
}
break;
case 'password':
if(!$_POST[update])
{
boxtop2("Change Password");
echo("<form method=\"POST\" style=\"margin: 0px;\">");
bar("Password - 25 characters maximum");
echo("<input type=\"password\" style=\"border: 1px solid #808080; font-family:Verdana; font-size:10px; background: #e6e6e6\" size=\"15\" maxlength=\"25\" name=\"pass\">");
bar2("Confirm Password - must match above password");
echo("<input type=\"password\" size=\"15\" style=\"border: 1px solid #808080; font-family:Verdana; font-size:10px; background: #e6e6e6\" maxlength=\"25\" name=\"cpass\">");
echo("<br /><br /><input style=\"border: 1px solid #808080; background: #e6e6e6\" name=\"update\" type=\"submit\" value=\"Change Password\">");
endbox();
}
else
{
$password = $_POST[pass];
$cpassword = $_POST[cpass];
if($password != $cpassword) {
boxtop("Error");
echo("The passwords you submitted are not the same!  Please go back and fix your mistake");
endbox();
}
else
{
$pass = htmlspecialchars(addslashes("$password"));
$md5 = md5("$pass");
$update = mysql_query("update members set password = '$md5' where username = '$logged[username]'");
boxtop("Thank You");
echo("Your password has been changed to \"$pass\".  Once you view a new page, you will have to log in again.");
endbox();
}
}
break;

}
	}
?>