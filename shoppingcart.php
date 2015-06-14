
<?php
	error_reporting(0);
	session_start();
if (!isset($_SESSION["cart"])) {
  $_SESSION['cart']=array();
  $_SESSION['items'] = 0;
  $_SESSION['totalprice']=0.00;
  $cart = array();
 }
 else {
 //print ("cart already started ");
  $cart = $_SESSION['cart'];
 }
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sweet Treats - Shopping Cart</title>
<?
	require("displaycartfunction.php");
?>
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
              <li><a href="brownie.php" class="documents">Brownies</a></li>
              <li><a href="cakes.php" class="messages">Cakes</a></li>
              <li><a href="cookies.php" class="signout">Cookies</a></li>
              <li><a href="cupcake.php" class="signout">Cupcakes</a></li>
              <li><a href="pie.php" class="signout">Pies</a></li>
              <li><a href="tart.php" class="signout">Tarts</a></li>
              <li><a href="torte.php" class="signout">Tortes</a></li>
      </ul>
    
    
    <li><a href="contact.php">Contact Us</a>  </li>
    
    <li id="cart"><a href="shoppingcart.php"><img src="img/bagicon.png" /> &nbsp;Cart</a></li> <!--this link is SWAPPED WITH CART-->
    <li id="register"><a href="register.php">Login</a></li><!--this link is SWAPPED WITH REGISTER-->
 	</ul>
</div>

<?php
// make connection to database
require("config.php");
?>
<div id="content">
<h2 id="welcome">Shopping Cart</h2>

<?
if (isset($_GET['productid'])) {
 $p_id = $_GET['productid'];
 $p_cost = $_GET['productcost'];
 $quantity=$_GET['quantity'];
 $cart[$p_id] = $p_cost;/////////////////////////////
 $cart[$p_id] = $quantity;
 $_SESSION['cart'] = $cart;  
 //print("In shoppingcart cart count  is ". count($_SESSION['cart']));
 //print(" just added was p_id is ".$p_id . " and quantity is $quantity");
}
displaycart();
?>
<a href="submitorder.php"> Checkout (submit order)! </a> &nbsp; &nbsp; 
<a href="orderproduct.php"> More shopping! </a>
</div>
<footer>
<p> Sweet Treats Bakery LLC. All Rights Resevered. 2013</p>
</footer>
</body> 
</html> 