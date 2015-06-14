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
// make connection to database
require("config.php");
?>
<h1>Indicate quantity and confirm order </h1>
<p>
<?
$p_id = $_GET['p_id'];
$query="SELECT * FROM catalog WHERE id=$p_id";
//print("in makeorder, p_id is $p_id");
$result=mysql_db_query($dbname,$query,$link);
$p_name=mysql_result($result,0,"p_name");
$picture=mysql_result($result,0,"picture");
$cost=mysql_result($result,0,"cost");
print ("<center>" .$picture . "</center>");
print("<br>");
print("$p_name");
?>
<form action="shoppingcart.php" method="get">
<label class="description">Quantity</label> <input type=text class="qty" size=3 name="quantity">
<input type=submit class="btn" value="Submit quantity" >
<input type=hidden name='productid' value='
<? 
print($p_id);
?>
'>
</form> 
</div>
<footer>
<p> Sweet Treats Bakery LLC. All Rights Resevered. 2013</p>
</footer>
</body> 
</html>