<?php
session_start();
require_once "pdo.php";

if (isset($_POST['booking'])) {
  header("Location: Booking_App.php?username=".urlencode($_GET['username']));
  return;
}
if (isset($_POST['appstatus'])) {
  header("Location: appointmentStatus.php?username=".urlencode($_GET['username']));
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
      <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="style.css">
   </head>

   <style>


     body {
       text-align: center;
     }
     div.transbox {
       height: 100%;
       width: 600px;
     }
     </style>
   <body>
     <nav class="navbar navbar-inverse navbar-fixed-top">
     <div class="container-fluid">
       <div class="navbar-header">
         <a class="navbar-brand" href="#"><img src="grad.png"> </a>
       </div>
         <ul class="nav navbar-nav navbar-right">
       <ul class="nav navbar-nav">
         <li class="active"><a href="homeStudent.php">Home</a></li>
       </ul>
     </ul>
   </div>
</nav>
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
     <div class="container position-relative mt-4">
           <div class="position-absolute top-0 start-50 translate-middle-x text-dark p-4 transbox" style="background:rgba(255,255,255,.8)">
     <h1 style="text-align: center">Student Homepage</h1>

     <form method='post' >
     <input class="btn btn-info btn-lg" type="submit" value="Appointment Booking" name="booking"><br/><br/>
     <input class="btn btn-info btn-lg" type="submit" value="Appointment Status" name="appstatus"><br/><br/>
     <input class="btn btn-info btn-lg" type="submit" value="Appointment list" name="list"><br/><br/>
     <input class="btn btn-info btn-lg" type="submit" value="Logout" name="but_logout"><br/><br/>
     </form>
   </body>
</html>
