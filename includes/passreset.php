<?
if(!$_POST[submit])
{
boxtop("Request a new password");
echo <<<EOF
<form name="message" method="POST" style="margin: 0px">
<dl style="margin: 0px">
<dt>Email Address</dt>
<dd><input type="text" style="border: 1px solid #808080; background: #efefef; font-family: verdana; font-size: 10px" name="email" /></dd>
<dt>&nbsp;</dt>
<dd><input type="submit" style="border: 1px solid #808080; background-color: #e6e6e6; margin-top: 2px;" value="Submit" name="submit"></dd>
</dl></form>
EOF;
endbox();
}
else
{
$email = htmlspecialchars(addslashes("$_POST[email]"));
$userinfo = mysql_query("select * from members where email = '$email'");
$numuser = mysql_num_rows($userinfo);
if($numuser = 0)
{
boxtop("Error");
echo("No users have signed up with this email address!");
endbox();
}
else
{
$newpass = passreset("12");
$subject = "Your new TechTuts.com Password";
$userinfo = mysql_fetch_array($userinfo);
$header = "From: TechTuts Admin". "<noreply@techtuts.com>";
$mail = "Hello, $userinfo[username].  \n
This email is from the staff at TechTuts.com.  Your new password is: $newpass.\n\n
You can now log in and change your password.\n

Thanks for being a TechTuts Member.";
$md5 = md5($newpass);
if (mail($email, $subject, $mail, $header))
{
$updateusers = mysql_query("update members set password = '$md5' where username = '$userinfo[username]'");
boxtop("Thank You");
echo("We have sent a new password to the email address you gave us.  Once you get it, log in and update your password.");
endbox();
}}
}
?>