<?php
	session_start();
	error_reporting(0);
?>
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
<div id="header">
<img src="img/logo.png" id="logo"/>
<img id="right" src="img/muffins-2.jpg"/>
</div>
<div id="nav">
<ul class="menu">
 
    <li><a href="index.php">Home</a></li>
    <li><a href="#">Shop</a>
    <ul>
            <li><a href="brownie.php">Brownies</a></li>
            <li><a href="cakes.php">Cakes</a></li>
            <li><a href="cookies.php">Cookies</a></li>
            <li><a href="cupcake">Cupcakes</a></li>
            <li><a href="pie.php">Pies</a></li>
            <li><a href="tart.php">Tartes</a></li>
            <li><a href="torte.php">Tortes</a></li>
	</ul>
    
    <li><a href="contact.php">Contact Us</a>  </li>
    
    <li id="cart"><a href="shoppingcart.php"><img src="img/bagicon.png" /> &nbsp;Cart</a></li> <!--this link is SWAPPED WITH CART-->
    <li id="register"><a href="endsession.php">Logout</a></li><!--this link is SWAPPED WITH REGISTER-->
 	</ul>
</div>
<div id="content">
<h2 id="welcome">Sign In</h2> 
<?php
 require("config.php");
if (isset($_POST['submitted'] )) {
   $pword1=$_POST['pword1'];
   $uid = $_POST['uid'];
    $pword1 = trim($pword1);
    $uid = trim($uid);
    $oksofar = true;
    $pw1= md5($pword1);
   
    $query = "Select * from ptable where uid='$uid' and pass='$pw1'";
    $result=mysql_db_query($DBname, $query, $link);
    if (mysql_num_rows($result == 0)) {
      print ("No match for id and password. ");
      print ("Use the BACK function on your browser to return to the form."); 
      $oksofar = false;
    }
    if ($oksofar) { 
	 header("Location:index.php");
     $user=$uid;
	 $_SESSION['user'] = $user;
     print("You are now sign in as $user.");
	
	// print("OR <a href='endsession.php'>End session </a>");
      }
       
       mysql_close($link);
   }  //ends if submitted
else {
?>
<form action="signin.php" method=post>
<ul>
	<li><label class="description">Username:</label> <input type=text name="uid" size=10></li>
	<li><label class=" description">Password:</label>  <input type=password name="pword1" size=10></li>
    <li><input type=hidden name="submitted" value="True"></li>
    <li><input type=submit class="btn" name="submit" value="Sign up!"></li>
</ul>  
</form>
<?
}
?>
</div>
<footer>
<p> Sweet Treats Bakery LLC. All Rights Resevered. 2013</p>
</footer>
</body> </html>