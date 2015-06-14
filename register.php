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
            <li><a href="brownie.php">Brownies</a></li>
            <li><a href="cakes.php" class="messages">Cakes</a></li>
            <li><a href="cookies.php" class="signout">Cookies</a></li>
            <li><a href="cupcake.php" class="signout">Cupcakes</a></li>
            <li><a href="pie.php" class="signout">Pies</a></li>
            <li><a href="tart.php" class="signout">Tarts</a></li>
            <li><a href="torte.php" class="signout">Tortes</a></li>
	</ul>
    
    
    <li><a href="contact.php">Contact Us</a>  </li>
    
    <li id="cart"><a href="shoppingcart.php"><img src="img/bagicon.png" /> &nbsp; Cart</a></li> <!--this link is SWAPPED WITH CART-->
    <li id="register"><a href="signin.php">Login</a></li><!--this link is SWAPPED WITH REGISTER-->
 	</ul>
</div>

<h2 id="welcome">Registration</h2>
<div id="content">
<?php
// set variables
require("config.php");
$tname = "ptable";

if (isset($_POST['submitted'])) {
  $pword1 = $_POST['pword1'];
  $pword2 = $_POST['pword2'];
  $uid = $_POST['uid'];
  $pword1 = trim($pword1);
  $pword2 = trim($pword2);
  $uid = trim($uid);
$oksofar = true;
    $pattern="^[a-z0-9]{4,10}$";
    if (!eregi($pattern,$uid)){
      print ("Please make your id a combination ");
      print ("of at least 4 and not more than 10 alphanumeric characters. ");
      print ("Use the BACK function on your browser to return to the form."); 
      $oksofar = false;
    }
    if (!eregi($pattern,$pword1)){
      print ("Please make your password a combination ");
      print ("of at least 4 and not more than 10 alphanumeric characters.  ");
      print ("Use the BACK function on your browser to return to the form."); 
      $oksofar = false;
    }
    if (StrCmp($pword1,$pword2)!=0) {
      print ("The two passwords did not match. Please try again. ");
      print ("pword1 was $pword1 and pword2 was $pword2. ");
      print ("Use the BACK function on your browser to return to the form."); 
      $oksofar = false;
    }
    $query = "Select * from ptable where uid='$uid'";
    $result=mysql_db_query($dbname, $query, $link);
    if (mysql_num_rows($result)!=0) {
      print ("The id is already taken.  Please try again. ");
      print ("Use the BACK function on your browser to return to the form."); 
      $oksofar = false;
    }
    if ($oksofar) {
      $pw1 = md5($pword1);
      $query = "INSERT INTO $tname values ('$uid','$pw1')";
      $result = mysql_db_query($dbname,$query, $link);
      if ($result) {
	  print("Registration successful.<br>\n");
       }
       else {
	  print ("Registration not successful. <br>\n");
        }
      }
       $submitted = FALSE;
       mysql_close($link);
   }  //ends if submitted
else {
     print ("Create a user id and a password. Make each a combination ");
     print ("of at least 4 and not more than 10 alphanumeric characters. <br>");
     print ("<form action=\"register.php\" method=post>\n");
     print ("<label class=\"description\">User name:</label> <input type=text name=\"uid\" size=10><br>\n");
     print ("<label class=\"description\">Password:</label>  <input type=password name=\"pword1\" size=10><br>\n");
     print ("<label class=\"description\">Re-Enter Password:</label>  <input type=password name=\"pword2\" size=10><br>\n");
     print ("<input type=hidden name=\"submitted\" value=\"True\"><br>\n");
     print ("<input type=submit class=\"btn\" name=\"submit\" value=\"Sign up!\"><br>\n");
     print ("</form><br>\n");
}
?>
</div>
<footer>
<p> Sweet Treats Bakery LLC. All Rights Resevered. 2013</p>
</footer>
</body> 
</html>