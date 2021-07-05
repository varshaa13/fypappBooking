<?php
require_once "pdo.php";
session_start();

if (isset($_POST['Back'])) {
  header("Location: homeStudent.php?username=".urlencode($_GET['username']));
  return;
}
?>

<?php
$Student_ID = $_GET['username'];
$stmt = $pdo->query("SELECT id, name FROM student WHERE student.username = '$Student_ID'");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$studentAuto = $row['id'];
?>

<?php
if(isset($_POST['submit'])) {

  $times = $_POST['check'];
  foreach($times as $time) {
    $stmt = $pdo->prepare('INSERT INTO appointment (apppurpose, studentid, timetableid) VALUES (:apppurpose, :studentid , :timetableid)');
    $stmt->execute(array(
      ':apppurpose'  => $_POST['apppurpose'],
      ':studentid'  =>  $studentAuto,
      ':timetableid'  => $time)
    );
  }

  $_SESSION['success'] = "Result inserted";
  header("Location: Booking_App.php?username=".urlencode($_GET['username']));
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

    <script type="text/javascript">
      function emptyValidation() {
        if(document.getElementById('date').selectedIndex==0) {
          alert("Date is required");
          return false;
        }
        return true;
      }

      function myValidation() {
        var checkbox =document.querySelector('input[name="check[]"]:checked');
        if (!checkbox){
          alert("Time is required");
          return false;
        }
        if (document.getElementById('apppurpose').value=="") {
          alert("Appointment purpose is required");
          return false;
        }
        return true;
     }
    </script>

    <div class="container">
      <div class="w-50 position-absolute mb-5 top-50 start-50 translate-middle text-dark p-5" style="background:rgba(255,255,255,.8)">

        <h1 class="text-center mb-2">Book Appointment</h1>

        <?php
        if(isset($_SESSION['success'])){
          echo ('<p class="text-center" style = "color:green;">'.($_SESSION['success'])."</p>\n");
          unset($_SESSION['success']);
        }
        if(isset($_SESSION['error'])){
          echo ('<p p class="text-center" style = "color:red;">'.($_SESSION['failure'])."</p>\n");
          unset($_SESSION['error']);
        }
        ?>

        <?php
        //display lecturer_ID and Lecturer_Name
        echo("<strong>Student ID: </strong>".$_GET['username']);
        echo "<br/>";
        echo("<strong>Student Name: </strong>".$row['name']);

        ?>

          <form method ="POST" name="form1" onsubmit="return emptyValidation()" required>
              <div class="mb-3 row">
                <label for="date" class="col-sm-2 col-form-label mt-4">Date:</label>
              <div class="col-sm-6 mt-4">
                <select class="form-control" name="date" id="date">
                  <option disabled selected>--Select date--</option>
                  <?php
                    $Student_ID = $_GET['username'];
                    $stmt = $pdo->query("SELECT DISTINCT date FROM timetable WHERE lecturerid in (SELECT lecturer.id FROM lecturer JOIN student ON (lecturer.id = student.lecturerid)
                                        WHERE timetable.status='Available' AND student.username = '$Student_ID')");
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                      echo "<option value='" . $row['date'] . "'>" . $row['date']. "</option>";
                    }
                  ?>
                </select>
              </div>
              <div class="col-sm-2 mt-4">
                <input class="btn btn-info text-light" type="submit" value="Check For Available time" name="CheckTime">
              </div>
              </div>
          </form>

          <div class="mb-2 mt-2 row text-center">
            <table class="table table-striped">
                <thead class="thead-light" >
                  <?php
                  if(isset($_POST['CheckTime'])){
                    $Student_ID = $_GET['username'];
                    $Date = $_POST['date'];
                    $stmt = $pdo->query("SELECT DISTINCT id, (concat(startingtime, ' - ', endingtime)) AS Time FROM timetable WHERE lecturerid IN
                                        (SELECT lecturer.id FROM lecturer JOIN student ON (lecturer.id = student.lecturerid) where student.username = '$Student_ID')
                                        AND  timetable.status='Available' AND timetable.date = '$Date'");

                    echo("<p><u><strong class='text-center'>Selected Date: ".$Date."</strong></u></p>");
                    echo("<tr><th scope='col'>Time</th>");
                    echo("<th scope='col'>Availability</th></tr></thead><tbody>");
                    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
                      echo("<tr><td>");
                      echo($row['Time']);
                      echo("</td><td>");
                      echo('<form method="POST" onsubmit="return myValidation()" required>');
                      echo('<input type="checkbox" value='.$row['id'].' name="check[]" id="check">');
                      echo("</td></tr>\n");
                    }
                  }
                  ?>
                  </tbody>
            </table>
          </div>

          <div class="mb-3 row">
            <label for="date" class="col-sm-4 col-form-label">Appointment Purpose:</label>
            <div class="col-sm-6">
              <input type="text" name="apppurpose" id="apppurpose" class="form-control">
            </div>
          </div>

          <div class="row mb-2 mt-2 text-center">
            <input class="btn btn-success" type="submit" value="Book" name ="submit">
          </div>
          </form>

          <div class="row mt-4 text-end">
            <form method="post">
              <input class="btn btn-info text-light" type="submit" value = "Back" name= "Back">
            </form>
          </div>
        </div>
      </div>
    </body>
</html>
