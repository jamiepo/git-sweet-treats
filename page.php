<?php
session_start();
if (!isset($_SESSION["user"])) {
?>
You need to <a href="signin.php">SIGN IN.</a>
<?
 }
else {
print("<html><head><title>First page </title></head><body>");
 print ("You are signed in. User id is $user");
 print ("<br><a href=\"endsession.php\">Sign out and end session </a>");
}
?>
</body>
</html>