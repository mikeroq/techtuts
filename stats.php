<html>
<!-- Created on: 7/4/2006 -->
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>stats</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="Unregistered User">
  <meta name="generator" content="AceHTML 6 Pro">	
  <style type='text/css'>
  BODY {
  background: #303030;
  margin: 10px;
  }	  
  .head {
  background: #EEB422;
  color: #000000;
  font-size: 12px;
  font-family: Verdana,Tahoma; 
  font-weight: bold;	
  text-align: center;
  }	 
  table {
  font-size: 12px;
  font-family: Verdana,Tahoma;
  background: #626262; 
  }
  </style>
</head>
<body>
<table width='100%' cellspacing='0' cellpadding='5' border='1' bordercolor='00000' style='border-collapse: collapse;'>
	<tr class='head'>
	<td width='20%'>Name</td>
	<td width='10%'>Kills</td>
	<td width='10%'>Deaths</td>
	<td width='10%'>K/D Ratio</td>
	<td width='10%'>Teamkills</td>
	<td width='10%'>Headshots</td>
	<td width='10%'>Shots</td>
	<td width='10%'>Hits</td>
	<td width='10%'>Accuracy</td>
	</tr>	
<?php
@mysql_connect("localhost","root","");
@mysql_select_db("amx");
$get = mysql_query("SELECT * FROM `sqlstatstable` ORDER BY `score` DESC LIMIT 15");			
$i == '1';
while ($r=mysql_fetch_assoc($get)
	{
		$name = $r['name'];
		$kills = number_format($r['kills']);
		$deaths = number_format($r['deaths']);
		$tk = number_format($r['teamkills']);
		$kd = $kills/$deaths;
		$hits = number_format($r['hits']);
		$shots = number_format($r['shots']);
		$hs = number_format($r['headshots']);
		$a = $r['accuracy'],"%";		
		
	    echo "<tr><td width='2%'>$i</td><td width='18%'>$name</td>
	<td width='10%'>$kills</td>
	<td width='10%'>$deaths</td>
	<td width='10%'>$kd</td>
	<td width='10%'>$tk</td>
	<td width='10%'>$hs</td>
	<td width='10%'>$shots</td>
	<td width='10%'>$hits</td>
	<td width='10%'>$a</td></tr>"; 
	$i++;
?>
</table>
</body>
</html>
