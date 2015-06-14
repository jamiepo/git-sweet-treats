<?php
error_reporting(0);
//assumes that opendbo called, and session started when call is made.
function displaycart() {
 global $cart, $dbname, $link, $totalprice;
 print ("<table border=1>");
 print ("<tr> <th> Product Name </th><th>ID</th><th> Quantity </th> <th> 
       Product Total </th> </tr>");
 $items = 0; //note session variable items not used
 $totalprice = 0.00; 
 $cart = $_SESSION['cart'];
 //print("the count of the cart is ".count($cart));
 
 foreach (@$cart as $pid => $qty) {
   $items += $qty;
  // print(" the pid is ".$pid . " and the qty is ". $qty);
   $query="Select * from catalog where id='$pid'";
   //print("query is $query");
   $result = mysql_db_query($dbname, $query, $link);// or die(mysql_error());
   $item_price = mysql_result($result,0,"cost");// or die(mysql_error());///// mysql_result($result,0,"cost");
   $item_name = mysql_result($result,0,"p_name");// or die(mysql_error());
   $item_total_price = $item_price * $qty;
   $totalprice += $item_total_price;
   $item_total_price = number_format($item_total_price,2);
   print ("<tr><td> $pid </td><td> $item_name </td> <td> $qty </td><td>&nbsp;$item_price  $item_total_price </td> </td> ");
  
  }
 $totalprice = "$" . number_format($totalprice,2);
 print("<tr> <td> TOTAL </td> <td> </td> <td> $items Items</td><td> $totalpricef </td></tr>  </table>");
 $_SESSION['items']=$items;
 $_SESSION['totalprice']=$totalprice;
 mysql_close($link);
}
?>