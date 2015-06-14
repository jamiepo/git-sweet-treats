<?php
//initiates the variable to connect to database
$host = 'BakedGoods.db.7003830.hostedresource.com';
$user = 'BakedGoods';
$pwd = 'B@kedgoods1';
$dbname = 'BakedGoods';
//sets up connect to the database & prints error if can't connect
$link =@ mysql_connect($host,$user,$pwd) or die("Could not connect : ".mysql_error());

//selects the database to use & prints error if can't connect
@mysql_select_db($dbname, $link) or die("Could not select DB : ".mysql_error()); ;

?>