<?
if(!$_POST[name])
{
boxtop2("Contact Us");

bar("Your Name");

echo ("<form style=\"margin: 0px;\" method=\"POST\"><input style=\"border: 1px solid #808080;
 font-family:Verdana;
 font-size:10px;
 background: #e6e6e6\" type=\"text\" name=\"name\" size=\"30\" />");

bar2("Your Email Address");

echo("<input style=\"border: 1px solid #808080;
 font-family:Verdana;
 font-size:10px;
 background: #e6e6e6\" type=\"text\" name=\"email\" size=\"30\" />");

bar2("Email Subject");

echo("<input style=\"border: 1px solid #808080;
 font-family:Verdana;
 font-size:10px;
 background: #e6e6e6\" type=\"text\" name=\"subject\" size=\"30\" />");

bar2("Message");

echo("<textarea style=\"border: 1px solid #808080;
 font-family:Verdana;
 font-size:10px;
 background: #e6e6e6\" rows=\"7\" name=\"message\" cols=\"31\"></textarea>
<br />
<br /><input style=\"border: 1px solid #808080;
 font-family:Verdana;
 font-size:10px;
 background: #e6e6e6\" type=\"submit\" value=\"Submit\" name=\"B1\"></form>");

endbox();
}else{$subject = htmlspecialchars($_POST[subject]);
$message = htmlspecialchars($_POST[message]);
$name = htmlspecialchars($_POST[name]);
$subject = htmlspecialchars($_POST[subject]);
$email = htmlspecialchars($_POST[email]);
$mailto = "Greg Grillo <$settings[email]>";
$subject = "$subject";
$mail = "$message \n From: $name \n Email: $email";
$header = "From: Website Visitor". "<$email>";
if (mail($mailto, $subject, $mail, $header)){boxtop("Thank You");
echo ("Your email has been sent! You should get a reply soon");
 endbox();
}else{boxtop("Error");
echo ("An error occured with the email form. Please try again");
endbox();
}}?>