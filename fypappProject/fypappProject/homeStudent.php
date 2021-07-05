<?php
session_start();
require_once "pdo.php";

if (isset($_POST['booking'])) {
  header("Location: Booking_App.php?username=".urlencode($_GET['username']));
  return;
}
if (isset($_POST['list'])) {
  header("Location: Student_Booking_List.php?username=".urlencode($_GET['username']));
  return;
}
?>
<!Doctype html>
<html>
   <head>
      <title>FYP Appointment Booking System</title>
   </head>
   <body>
     <?php
     // Check user login or not
     if(!isset($_SESSION['userid'])){
       header('Location: index.php');
     }

     // logout
     if(isset($_POST['but_logout'])){
       session_destroy();

       // Remove cookie variables
       $days = 30;
       setcookie ("rememberme","", time() - ($days * 24 * 60 * 60 * 1000) );

       header('Location: index.php');
     }
     ?>
     <h1>Homepage</h1>


     <form method='post' >
     <input type="submit" value="booking" name="booking">
<input type="submit" value="list" name="list">
       <input type="submit" value="Logout" name="but_logout">
     </form>
   </body>
</html>
