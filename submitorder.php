
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/style.css" />
<script type="text/javascript" src="js/script.js"></script>
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
    <li id="register"><a href="endsession.php">Logout</a></li>
    <li id="register"><a href="signin.php">Login</a></li><!--this link is SWAPPED WITH REGISTER-->
 	</ul>
</div>

<div id="content">
<?php

require("config.php");
require("displaycartfunction.php");
$today = Date("Y-m-d");
$states = array('AL'=>"Alabama",'AK'=>"Alaska",'AZ'=>"Arizona",'AR'=>"Arkansas",'CA'=>"California",'CO'=>"Colorado",'CT'=>"Connecticut",'DE'=>"Delaware",'DC'=>"District Of Columbia",'FL'=>"Florida",'GA'=>"Georgia",'HI'=>"Hawaii",'ID'=>"Idaho",'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa",  'KS'=>"Kansas",'KY'=>"Kentucky",'LA'=>"Louisiana",'ME'=>"Maine",'MD'=>"Maryland", 'MA'=>"Massachusetts",'MI'=>"Michigan",'MN'=>"Minnesota",'MS'=>"Mississippi",'MO'=>"Missouri",'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma", 'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
	
	
if (!isset($_POST['submitconfirm'])) {
  print ("Please give information for ordering or confirm information present.<br>");
  print ("<form action=\"$PHP_SELF\" method=post><br>");
  $ofname=""; $olname=""; $obilling=""; $oemail="";
  if (isset($_COOKIE['currentcustomer']))
  $currentcustomer = $_COOKIE['currentcustomer'];
 // print("in submitorder, currentcustomer is $currentcustomer");
   {$query="SELECT * from customers where id='$currentcustomer'";
  // print ("in submitorder, query is $query");
    $result=mysql_db_query($dbname,$query,$link);
//	print("result is $result");
   $Num_past = mysql_num_rows($result);
   if ($Num_past>0) {
   // $obilling=mysql_result($result,0,"billing");
    $ofname=mysql_result($result,0,"fname");
    $olname=mysql_result($result,0,"lname");
	$oadd1=mysql_result($result,0,"add1");
	$oadd2=mysql_result($result,0,"add2");
	$ocity=mysql_result($result,0,"city");
	$ostate=mysql_result($result,0,"state");
	$ozip=mysql_result($result,0,"zip");
	$ophone=mysql_result($result,0,"phone");
    $oemail=mysql_result($result,0,"emailaddress");
    print ("<input type=hidden name=oldcustomer value=TRUE>");
    print("<br>INFO OKAY <input type=\"radio\" name=\"choices\" value=\"OKAY\" CHECKED >");
    print ("<br>CHANGE MY INFO <input type=\"radio\" name=\"choices\" value=\"CHANGE\" >");
    print ("<br>NEW CUSTOMER <input type=\"radio\" name=\"choices\" value=\"NC\"><br>");
   }
   }
  print ("<ul><li><label class=\"description\">First Name</label> <input type=text name='fname' value='".$ofname."'></li>");
  print ("<li><label class=\"description\">Last Name</label> <input type=text name='lname' value='".$olname."'></li>");
  print ("<li><label class=\"description\">Address Line 1</label> <input type=text name='add1' value='".$oadd1."'></li>");
  print ("<li><label class=\"description\">Address Line 2</label> <input type= text name='add2' value='".$oadd2."'></li>");
  print ("<li><label class=\"description\">City</label> <input type=text name='city' value='".$ocity."'><br>");
  print ("<li><label class=\"description\">State</label> ");
  echo '<select name="state" id="state"><option value="'.$ostate.'">Select one…</option>';
	foreach ($states as $key => $value) {
	echo '<option value="'.$value.'">'.$value.'</option>';
	}
  echo '</select></li>'; 
  print ("<li><label class=\"description\">Zipcode</label> <input type=text name='zip' value='".$ozip."'></li>");
  print ("<li><label class=\"description\">Phone</label> <input type=text name='phone' value='".$ophone."'></li>");
  print ("<li><label class=\"description\">E-mail</label><input type=text name='email' value='".$oemail."'></li></ul>");
  print ("<input type=hidden name='submitconfirm' value=TRUE>");
  print ("<input type=submit name='submit' class=\"btn\" value='SUBMIT/CONFIRM INFORMATION'>");
  print ("</form>");
 }
else {
if (!isset($_POST['oldcustomer']) ) {
   $oldcustomer=FALSE;
   }
    $choices = $_POST['choices'];
   $ofname = $_POST['fname'];
   $olname = $_POST['lname'];
   $oadd1 = $POST['add1'];
   $oadd2 = $POST['add2'];
   $ocity = $POST['city'];
   $ostate = $POST['state'];
   $ozip = $POST['zip'];
   $ophone = $POST['phone'];
   $oemail = $_POST['email'];
if (!@$oldcustomer) {
    $query="INSERT INTO customers VALUES ('0','".$fname;
    $query=$query."','".$lname."','".$billing."','".$email."','X')" ;  // X for pass now
    $result=mysql_db_query($dbname, $query,$link); //need error handling. 
    $currentcustomer=mysql_insert_id();
    setcookie("currentcustomer",$currentcustomer); //sets cookie
    } //end if not old customer--need to insert into db and create cookie
else {  // old customer.  Update db just in case changes were made
  
    if (@$choices=='CHANGE') {    
       $query="UPDATE customers set fname='".$fname ;
       $query = $query . "', lname='".$lname."', billing='".$billing;
       $query = $query . "', emailaddress='".$email ."' where id=$currentcustomer";
       mysql_db_query($DBname,$query,$link);
     }
  else if (@$choices=='NC') {
    $query="INSERT INTO customers VALUES ('0','".$fname;
    $query=$query."','".$lname."','".$billing."','".$email."','X')" ;  // X for pass now
    $result=mysql_db_query($DBname, $query,$link); //need error handling. 
    $currentcustomer=mysql_insert_id();
    $duration = 90 * 24 * 60* 60;  //90 days
    setcookie("currentcustomer",$currentcustomer, time()+$duration); //sets long term 
    } //end if changed to new customer
  }
 print("Welcome, $fname <br>");
 print ("Today is $today <br>\n");
 print ("Here is your order.<hr>");
 displaycart();
 print ("<hr> We are billing it using the following information: <br> $billing<br>");
 $query = "INSERT INTO orderlist VALUES ('0', '";
 $query = $query . $currentcustomer."', '".$today."',  'set',".$totalprice.")";
 mysql_db_query($DBname, $query, $link);
 $orderid=mysql_insert_id();
 foreach ($cart as $pid=>$qty) {
    $query="INSERT INTO ordereditems values ('".$orderid."','".$pid."',".$qty.")";
    mysql_db_query($DBname,$query,$link);
  }  //ends the foreach
  $_SESSION['cart'] = array();    //remove all items from shopping card
  $_SESSION['items'] = 0;
  $_SESSION['totalprice']=0.00;
}  //ends handling of form -- the else clause on if submitconfirm
?>
</div>



<footer>
<p> Sweet Treats Bakery LLC. All Rights Resevered. 2013</p>
</footer>


</div>
</body>
</html>

