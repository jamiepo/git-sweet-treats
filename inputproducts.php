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
          <li><a href="brownie.php" class="documents">Brownies</a></li>
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

<h2 id="welcome">Add a new Product to the Catalog</h2>
<div id="content">
<?php

// set variables
require("config.php");
$tname = "catalog";
// need sign in procedure
if (isset($_POST['submitted'] )) {
$p_name=$_POST['p_name'];
$type=$_POST['type'];
$picture=$_POST['picture'];
$cost=$_POST['cost'];
    $p_name = trim($p_name);
	$type = trim($type);
    $picture= trim($picture);
    $pattern="(http://)?([[:alnum:]\.,-_?/&=])\.((gif)|(jpg))$";
    if (!eregi($pattern,$picture)){
      print ("Please submit a valid address for a picture.<br>");
      print ("Use the BACK function on your browser to return to the form."); 
    }
    else {
       $picture = AddSlashes($picture);  // should check for valid address 
       // should check $cost to be valid number
       $query = "INSERT INTO $tname values ('0', '".$p_name."', '".$type."', '".$picture."', ".$cost.")";
	   print ("the query is $query.");
       $result = mysql_db_query($DBname,$query, $link);
       if ($result) {
	  print("The product was successfully added.<br>\n");
       }
       else {
	  print ("The product was NOT successfully added. <br>\n");
        }
       $submitted = FALSE;
       mysql_close($link);
       print ("<a href=\"inputproducts.php\">Submit Another Product. </a><br>");
     } //ends if good URL
}  //ends if submitted
else {
     print ("<form action=\"inputproducts.php\" method=post>");
     print ("<ul><li><label class=\"description\">Name of Product</label> <input type=text name=\"p_name\" size=30></li>");
	 print ("<ul><li><label class=\"description\">Type of Product</label> <input type=text name=\"type\" size=30></li>");
     print ("<li><label class=\"description\">File name of Picture</label> <input type=text name=\"picture\" size=50><br>\n");
     print ("<li><label class=\"description\">Cost of Product</label> <input type=text name=\"cost\" size=6><br>\n");
     print ("<input type=hidden name=\"submitted\" value=\"True\"><br>\n");
     print ("<input type=submit name=\"submit\" class=\"btn\" value=\"Submit Product!\"><br>\n");
     print ("</form><br>\n");
}
?>
</div>
<footer>
<p> Sweet Treats Bakery LLC. All Rights Resevered. 2013</p>
</footer>
</body> 
</html>