<?php
function createtable($tname,$fields) {
global $DBname, $link;
$query="CREATE TABLE ".$tname."(".$fields.")";
if (mysql_query($query,$link)) {
   print ("The table, $tname, was created successfully.<br>\n");
   }
else {
   print ("The table, $tname, was not created. <br>\n");
   }
}
?>
<html><head><title>Creating order project tables </title> </head>
<body>
<?php
require("config.php");
$tname = "customers";
$fields="id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, fname char(30), lname char(30), add1 TEXT, add2 TEXT, city TEXT, state char(20), zip VARCHAR(10), phone VARCHAR(10),  emailaddress char(50), pass char(30)";
createtable($tname, $fields);

$tname="orders";
$fields="id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, customer_id INT UNSIGNED NOT NULL, o_date DATE, status ENUM('open','set','billed','shipped'), total FLOAT(2)";
createtable($tname,$fields);

$tname="catalog";
$fields="id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, p_name CHAR(30), picture CHAR(50), cost FLOAT(2)";
createtable($tname,$fields);

$tname="ordereditems";
$fields="order_id INT UNSIGNED NOT NULL, p_id INT UNSIGNED NOT NULL, quantity INT NOT NULL";
createtable($tname,$fields);

$tname = "ptable";
$fields="uid char(10) NOT NULL PRIMARY KEY,  pass char(32)";
createtable($tname, $fields);

mysql_close($link);
?>
</body>
</html>