<?php
session_start();
$user= $_SESSION['user'];
?>
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
<div id="header">
<img src="img/logo.png" id="logo"/>
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

<h2 id="welcome">Registration</h2>
<div id="content">
<?php
print("Good-bye, $user.");
session_destroy();
?>
</div>
<footer>
<p> Sweet Treats Bakery LLC. All Rights Resevered. 2013</p>
</footer>
</body> 
</html>