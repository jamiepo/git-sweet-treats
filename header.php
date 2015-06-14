<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sweet Treats - <?php $title; ?>/title>
	
	<script src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/loopedslider.js" type="text/javascript" charset="utf-8"></script>
	
	<style type="text/css" media="screen">	
		/*
		 * Required 
		*/
		.container { width:500px; height:500px; overflow:hidden; position:relative; cursor:pointer; }
		div.slides { position:absolute; top:0; left:0; }
		ul.slides { position:absolute; top:0; left:0; list-style:none; padding:0; margin:0; }
		div.slides div,ul.slides li { position:absolute; top:0; width:600px; display:none; padding:0; margin:0; }
		/*
		 * Optional
		*/
		#loopedSlider,#newsSlider { margin:0 auto; width:500px; position:relative; clear:both; }
		ul.pagination { list-style:none; padding:0; margin:0; }
		ul.pagination li  { float:left; }
		ul.pagination li a { padding:2px 4px; }
		ul.pagination li.active a { background:blue; color:white; }
	</style>
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
    
    <li id="register"><a href="shoppingcart.php"><img src="img/bagicon.png" /> &nbsp;Cart</a></li> <!--this link is SWAPPED WITH CART-->
    <li id="cart"><a href="#">Register</a></li><!--this link is SWAPPED WITH REGISTER-->
 	</ul>
</div>