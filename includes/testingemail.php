<?php
$subject='Testing php Email';
$body='test.';

/* Specify your smtp Server, Port and Valid From Address */
ini_set("smtp","mail.yourdomain.com");
ini_set("smtp_port","25");
ini_set("sendmail_from","cadfan@gmail.com");

if(mail ('ToPersonsName <cadfan@gmail.com>', $subject, $body, $headers )){
echo "<h2>Your Message was sent!</h2>";
}
else{
echo "<font color='red'><h2>Your Message Was Not Sent!</h2></font>";
}
exit;
?> 