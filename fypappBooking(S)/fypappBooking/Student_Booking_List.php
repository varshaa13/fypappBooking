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
  echo "<pre>\n$sql\n</pre>\n";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':appid' => $_POST['appid']));
  $_SESSION['success'] = 'Record deleted';
header("Location:Student_Booking_List.php?username=".urlencode($_GET['username']));
return;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>FYP Appointment Booking System</title>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
</head>

<body style="background-image: url('uni.jpeg'); height:100%; background-position: center; background-repeat: no-repeat; background-size: cover;">

  <!-- Javascript -->
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

  <div class="container">
    <div class="w-50 position-absolute mb-5 top-50 start-50 translate-middle text-dark p-5" style="background:rgba(255,255,255,.8)">

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

      <h1 class="text-center mb-2">List of Appointments</h1>

      <?php
      $Student_ID = $_GET['username'];
      $stmt = $pdo->query("SELECT id, name FROM student WHERE student.username = '$Student_ID'");

      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $studentAuto = $row['id'];

      $stmt = $pdo -> query("SELECT appointment.id, timetable.date, timetable.startingtime, timetable.endingtime, appointment.apppurpose
                            FROM timetable JOIN appointment ON (timetable.id = appointment.timetableid) where appointment.status='Approved' AND studentid = '$studentAuto'");

      //display lecturer_ID and Lecturer_Name
      echo("<strong>Student ID: </strong>".$_GET['username']);
      echo "<br/>";
      echo("<strong>Student Name: </strong>".$row['name']);

      echo '<div class="mt-4 mb-4"><table class="table table-striped">'."\n";
      echo "<tr><th>Appointment Date</th>";
      echo "<th>Appointment Time</th>";
      echo "<th>Appointment Purpose</th></tr>";
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<tr><td>";
        echo($row['date']);
        echo("</td><td>");
        echo($row['startingtime'] .'-'. $row['endingtime']);
        echo("</td><td>");
        echo($row['apppurpose']);
        echo("</td></tr>\n");
      }
      echo"</table></div>";
      ?>

      <div class="mb-2 mt-2 text-end">
        <form method="post">
        <input class="btn btn-info text-light" type="submit" value = "Back" name= "Back">
        </form>
      </div>

    </div>
  </div>
</body>
</html>
