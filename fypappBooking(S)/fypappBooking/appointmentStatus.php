<?php
require_once "pdo.php";
session_start();

if (isset($_POST['Back'])) {
  header("Location: homeStudent.php?username=".urlencode($_GET['username']));
  return;
}

$Student_ID = $_GET['username'];

if(isset($_POST['delete']) && isset($_POST['appid']) ){
  $sql="DELETE FROM appointment WHERE id = :appid";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':appid' => $_POST['appid']));

  $_SESSION['success'] = 'Record deleted';
  header("Location:appointmentStatus.php?username=".urlencode($_GET['username']));
  return;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>FYP Appointment Booking System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/jquery.min.js"></script>
  <script src="jquery.tabledit.js"></script>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#"><img src="grad.png"> </a>
    </div>
      <ul class="nav navbar-nav navbar-right">
    <ul class="nav navbar-nav">
      <li class="active"><a href="homeLecturer.php">Home</a></li>
    </ul>
  </ul>
</div>
</nav>
  <?php

  if(isset($_SESSION['success'])){
    echo ('<p style = "color:green;">'.($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
  }
  if(isset($_SESSION['error'])){
    echo ('<p style = "color:red;">'.($_SESSION['failure'])."</p>\n");
    unset($_SESSION['error']);
  }
  ?>
  <div class="container-fluid ">

    <div class="container position-relative mt-4">
          <div class="position-absolute top-0 start-50 translate-middle-x text-dark p-4 transbox" style="background:rgba(255,255,255,.8)">
  <h1><center>Appointment Status List</center></h1>

  <?php
  $Student_ID = $_GET['username'];
  $stmt = $pdo->query("SELECT id FROM student WHERE student.username = '$Student_ID'");

  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $studentAuto = $row['id'];

  $stmt = $pdo -> query("SELECT appointment.id, appointment.status, timetable.date, timetable.startingtime, timetable.endingtime, appointment.apppurpose
    FROM timetable JOIN appointment ON (timetable.id = appointment.timetableid) where (appointment.status='Pending' OR appointment.status='Rejected') AND studentid = '$studentAuto'");

    echo '<table class="table table-striped">'."\n";
    echo "<tr><th>Appointment Date</th>";
    echo "<th>Appointment Time</th>";
    echo "<th>Appointment Purpose</th>";
    echo "<th>Appointment Status</th>";
    echo "<th>Action</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      echo "<tr><td>";
      echo($row['date']);
      echo("</td><td>");
      echo($row['startingtime'] .'-'. $row['endingtime']);
      echo("</td><td>");
      echo($row['apppurpose']);
      echo("</td><td>");
      echo($row['status']);
      echo("</td><td>");
      echo('<form method="post"><input type="hidden"');
      echo('name="appid" value="'.$row['id'].'">'."\n");
      echo('<input class="btn btn-danger" type="submit" value="Delete" name="delete">');
      echo("\n</form>\n");
      echo("</td></tr>\n");
    }
    echo"</table>";
    ?>
    <br />

<div class="mb-2 mt-2 text-end">
    <form method="post">
      <input class="btn btn-info" type="submit" value = "Back" name= "Back">
    </form>
  </div>

  </div>
  </div>
  </div>
  </body>
  </html>
