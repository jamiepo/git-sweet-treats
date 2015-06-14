<html><head><title>clear password table </title></head>
<body>
<?php
require('config.php');
$query = "delete from ptable";
if (mysql_db_query($DBname,$query,$link)) {
 print ("Entries deleted.");}
else {
 print ("problem deleting entries.");
 }
?>

</body>
</html>
