<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sweet Treats - Home</title>

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
    <li id="register"><a href="signin.php">Login</a></li><!--this link is SWAPPED WITH REGISTER-->
 	</ul>
</div>

<div id="content">
<h2 id="welcome">Contact Us</h2>
<p>Have questions you needs answers to, send us a message and we will be sure to get back to you.</p>
<form id="contact" action="contact.php" method="post">
<u>
<li><label class="description">Name:</label>
<input type="text" required="required" name="cname" />
</li>
<li><label class="description">Subject:</label>
<input type="text" name="csubject" />
</li>
<li>
<label class="description">Message:</label>
<textarea required="required" name="cmessage"></textarea>
</li>
<input type="submit" class="btn" value="submit" name="send" />
</form>

</div>



<footer>
<p> Sweet Treats Bakery LLC. All Rights Resevered. 2013</p>
</footer>


</div>
</body>
</html>
