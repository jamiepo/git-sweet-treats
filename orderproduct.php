<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sweet Treats - Order</title>

</head>

<body>

<div id="main">
<div id="header"> <a href="index.php"><img src="img/logo.png" id="logo"/></a>
<img id="right" src="img/muffins-2.jpg"/>
</div>
<div id="nav">
<ul class="menu">
 
    <li><a href="index.php">Home</a></li>
    <li><a href="#">Shop</a>
    <ul>
            <li><a href="#" class="documents">Brownies</a></li>
            <li><a href="#" class="messages">Cakes</a></li>
            <li><a href="#" class="signout">Cookies</a></li>
            <li><a href="#" class="signout">Cupcakes</a></li>
            <li><a href="#" class="signout">Pies</a></li>
            <li><a href="#" class="signout">Tartes</a></li>
            <li><a href="#" class="signout">Tortes</a></li>
	</ul>
    
    <li><a href="contact.php">Contact Us</a>  </li>
    
    <li id="cart"><a href="shoppingcart.php"><img src="img/bagicon.png" /> &nbsp; Cart</a></li> <!--this link is SWAPPED WITH CART-->
    <li id="register"><a href="signin.php">Login</a></li><!--this link is SWAPPED WITH REGISTER-->
 	</ul>
</div>

<h2 id="welcome">Shop Baked Goods </h2>
<div id="content">
<?php
require ("config.php");
if (isset($_COOKIE['currentcustomer'])) {
 $currentcustomer = $_COOKIE['currentcustomer'];
 //print("currentcustomer id is: $currentcustomer<br>");
 $query="SELECT fname FROM customers where id=$currentcustomer";
  $result=mysql_db_query($DBname,$query,$link);
 $Num_past = mysql_num_rows($result);
 if ($Num_past!=0) {
   $fname=mysql_result($result,0,'fname');
   print("Welcome back, $fname!<br>");
 }
}
?>
Select product:
<table>
<?php
// make connection to database
// Improvements could be to have product categories and/or to have paging
$query="Select * from catalog";
$result=mysql_db_query($DBname, $query, $link);
while ($row=mysql_fetch_array($result)) {
  print ("<tr><td><a href=makeorder.php");
  print ("?p_id=");
  print($row['id']);
  print(">");
  print($row['p_name']);
  print("</a></td>");
  print("<td><img src=\"");
  $picture=$row['picture'];  
  print("$picture");  //
  // print($row['picture']);
  print("\" width='200'></td></tr>");
}
print ("</table>");
mysql_close($link);
?>
</div>
<footer>
<p> Sweet Treats Bakery LLC. All Rights Resevered. 2013</p>
</footer>
</body> 
</html>