<?php
ob_start();
// allows you to use cookies
include("config.php");
//gets the config page
if ($_POST[register]) {
// the above line checks to see if the html form has been submitted
$username = $_POST[username];
$password = $_POST[pass];
$cpassword = $_POST[cpass];
$email = $_POST[emai1];
//the above lines set variables with the user submitted information
if($username==NULL|$password==NULL|$cpassword==NULL|$email==NULL) {
//checks to make sure no fields were left blank
boxtop("Error");
echo "A field was left blank.";
endbox();
}else{
//none were left blank!  We continue...
if($password != $cpassword) {
// the passwords are not the same!  
boxtop("Error");
echo "Passwords do not match";
endbox();
}else{
// the passwords are the same!  we continue...
$password = md5($password);
// encrypts the password
$username = htmlspecialchars($username);
$checkname = mysql_query("SELECT * FROM members WHERE username='$username'");
$checkname= mysql_num_rows($checkname);
$checkemail = mysql_query("SELECT email FROM members WHERE email='$email'");
$checkemail = mysql_num_rows($checkemail);
if ($checkemail>0|$checkname>0) {
// oops...someone has already registered with that username or email!
boxtop("Error");
echo "The username or email is already in use";
endbox();
}else{
// noone is using that email or username!  We continue...
$username = htmlspecialchars($username);
$username = addslashes($username);
$password = htmlspecialchars($password);
$password = addslashes($password);
$email = htmlspecialchars($email);
$email = addslashes($email);
// the above lines make it so that there is no html in the user submitted information.
//Everything seems good, lets insert.
if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
boxtop("Error");
echo "Invalid email";
endbox();
}else{
$query = mysql_query("INSERT INTO members (username, password, email) VALUES('$username','$password','$email')");
// inserts the information into the database.
boxtop("Thank You");
echo "You have successfully registered!";
endbox();
}
}
}
}
}
else
{
// the form has not been submitted...so now we display it.
boxtop2("Register");
bar("All fields are required");
echo ("
<form method=\"post\" style=\"margin: 0px;\">
<DL style=\"margin: 0px\">
<DT>Username
<DD><input type=\"text\" size=\"15\" maxlength=\"25\" name=\"username\" style=\"font-family:Verdana; font-size:10px; border: 1px solid #808080; background-color: #EFEFEF\" />
<DT>Password
<DD><input type=\"password\" size=\"15\" maxlength=\"25\" name=\"pass\" style=\"font-family:Verdana; font-size:10px; border: 1px solid #808080; background-color: #EFEFEF\">
<DT>Confirm Password
<DD><input type=\"password\" size=\"15\" maxlength=\"25\" name=\"cpass\" style=\"font-family:Verdana; font-size:10px; border: 1px solid #808080; background-color: #EFEFEF\">
<DT>Email Address
<DD><input type=\"text\" size=\"15\" name=\"emai1\" style=\"font-family:Verdana; font-size:10px; border: 1px solid #808080; background-color: #EFEFEF\">
<DT>&nbsp;
<DD><input name=\"register\" type=\"submit\" value=\"Register\">
</DL>
</form>
");
endbox();
}
?> 