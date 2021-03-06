<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sweet Treats - Order</title>
	
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
    
    <li id="register"><a href="shoppingcart.php"><img src="img/bagicon.png" /> &nbsp;Cart</a></li> <!--this link is SWAPPED WITH CART-->
    <li id="cart"><a href="endsession.php">Logout</a></li>
    <li id="cart"><a href="signin.php">Login</a></li><!--this link is SWAPPED WITH REGISTER-->
 	</ul>
</div>
<div id="content">
<h2 id="welcome"> Welcome  to Sweet Treats<span class="welcome"><a href="register.php"> Register Now!</a> It's Quick and Easy!</span></h2>

<div id="loopedSlider">	
	<div class="container">
		<div class="slides">
			<div><img src="img/cupcakes/banana_pb_cupcake.jpg" width="500" height="500" alt="First Image" /></div>
			<div><img src="img/cupcakes/bluberry_cupcakes.jpg" width="500" height="500" alt="Second Image" /></div>
			<div><img src="img/cupcakes/chocchip_dough_cupk.JPG" width="500" height="500" alt="Third Image" /></div>
            <div><img src="img/cookies/blue_cookies.jpg" width="500" height="500" alt="First Image" /></div>
			<div><img src="img/cookies/choc_waff_cookies.jpg" width="500" height="500" alt="Second Image" /></div>
			<div><img src="img/cookies/choc_chip_cookies.jpg" width="500" height="500" alt="Third Image" /></div>
            <div><img src="img/tartes/apple_tart.jpg" width="500" height="500" alt="First Image" /></div>
			<div><img src="img/tartes/clementine_banana_tarte.jpg" width="500" height="500" alt="Second Image" /></div>
			<div><img src="img/tartes/tarts2.jpg" width="500" height="500" alt="Third Image" /></div>
            <div><img src="img/cakes/choclava_cake.jpg" width="500" height="500" alt="First Image" /></div>
			<div><img src="img/cakes/flower_cake.jpg" width="500" height="500" alt="Second Image" /></div>
			<div><img src="img/cakes/strawberry_cake.jpg" width="500" height="500" alt="Third Image" /></div>
            <div><img src="img/tortes/choc_almond_torte.jpg" width="500" height="500" alt="First Image" /></div>
			<div><img src="img/tortes/choc_banana_torte.jpg" width="500" height="500" alt="Second Image" /></div>
			<div><img src="img/tortes/choc_banana_torte2.jpg" width="500" height="500" alt="Third Image" /></div>
            <div><img src="img/brownies/Chocolate-Chip-Cookie-Dough-Brownies-3.jpg" width="500" height="500" alt="First Image" /></div>
			<div><img src="img/brownies/IMG_1850_edited-1.JPG" width="500" height="500" alt="Second Image" /></div>
			<div><img src="img/brownies/mintbrownie.jpg" width="500" height="500" alt="Third Image" /></div>
		</div>
	</div>
	
	
</div>
<br /><br />
<script type="text/javascript" charset="utf-8">
	$(function(){
		$('#loopedSlider').loopedSlider({
			autoStart: 3000
		});
		$('#newsSlider').loopedSlider({
			autoHeight: 400
		});
	});
</script>
<footer>
<hr />
<p> Sweet Treats Bakery LLC. All Rights Resevered. 2013</p>
</footer>
</body> 
</html>