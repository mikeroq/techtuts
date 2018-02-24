<?
ob_start();
$conn = mysql_connect("localhost","syke_syke","4a360192");
mysql_select_db(syke_cms) or die(mysql_error());
$max_items = "5";
$logged = MYSQL_QUERY("SELECT * from members WHERE id='$_COOKIE[id]' AND password = '$_COOKIE[pass]'");
$logged = mysql_fetch_array($logged); 
?>