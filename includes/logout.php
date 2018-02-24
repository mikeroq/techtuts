<?
ob_start();
setcookie("id", asdfasdfafdsadsf,time()+(60*60*24*5), "/", "");
setcookie("pass", asdfgasdfgadsgagds,time()+(60*60*24*5), "/", "");
echo ("You are now logged out!");
?> 