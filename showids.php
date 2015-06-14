<html><head><title>Show ids and passwords</title> </head>
<body>
<h1>Id and encrypted passwords</h1> <p>
<?php
require ("config.php");
?>
<table border=1>
<?php
$query="Select * from ptable";
$result=mysql_db_query($DBname, $query, $link);
while ($row=mysql_fetch_array($result)) {
  print ("<tr><td>");
  print($row['uid']);
  print("</td>");
  print("<td>");
  print($row['pass']);
  print("</td></tr>");
}
print ("</table>");
mysql_close($link);
?>
</body> </html>