<? ob_start();
$ip = $REMOTE_ADDR;
include ("config.php");
$result1 = MYSQL_QUERY("SELECT * from members WHERE id='$_COOKIE[id2]' AND password = '$_COOKIE[pass]'");
$worked1 = mysql_fetch_array($result1);
if ($_POST[username] & $_POST[password]) {
$username = $_POST["username"];
$username = htmlspecialchars("$username");
$password1 = $_POST["password"];
$password1 = htmlspecialchars("$password1");
$username = $_POST["username"];
$realpass = md5($password1);
$sQuery = MYSQL_QUERY("SELECT * from members WHERE username='$username' AND password='$realpass'");
$Cookie = MySQL_Fetch_Array($sQuery);
if ($Cookie[username]){
setcookie("id", $Cookie['id'],time()+(60*60*24*5), "/", ""); 
setcookie("pass", $Cookie['password'],time()+(60*60*24*5), "/", ""); 
echo ("<meta http-equiv=\"Refresh\" content=\"0; URL=?view=home\"/>Thank You! You will be redirected");

}
else{
echo ("<font face=\"Verdana\" size=\"1\">Incorrect Username or Password!</font>
");
}
}
include ("config.php");
if ($logged[username]){
$num = mysql_query("select * from pmessages where touser = '$logged[username]' and unread = 'unread'");
$num = mysql_num_rows($num);
echo ("<div width=\"100%\">Welcome $logged[username]! &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>User Controls:</b>&nbsp;&nbsp; <a href=\"?view=cpanel\">Member Control Panel</a> &middot; <a href=\"?view=cpanel&mode=profile\">Edit Profile</a> &middot;");
if ($num == 0) 
{
echo "No New Messages &middot; <a href=\"?view=logout\">Logout</a></div>";
}
else
{
echo ("<a href='?view=cpanel&mode=messenger\'>Messenger ($num new)</a> &middot; <a href=\'?view=logout\">Logout</a></div>");
echo ("<script>window.alert('You Have A New Message')</script>");
}
if (!$_POST[username]){
if (!$logged[username]){
echo ("
<form method=\"post\" action=\"$php_self\" style=\"margin: 0px;\">
<input type=\"text\" name=\"username\" size=\"14\" style=\"margin: 1px; font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #efefef\" onfocus=\"this.value=''\" value=\"Username\" />&nbsp&nbsp;
<input type=\"password\" name=\"password\" size=\"14\" style=\"margin: 1px; font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #efefef\" onfocus=\"this.value=''\"value=\"username\" />&nbsp;&nbsp;
<input type=\"submit\" value=\"Submit\" name=\"B1\" style=\"margin: 1px; font-family: Verdana; font-size: 10px; border: 1px solid #808080; background-color: #efefef\" />
&nbsp;&nbsp;&nbsp;<a href=\"?view=register\">Register</a> &middot; <a href=\"?view=resetpass\">Forgot Password</a></form>
");
}}}
?>