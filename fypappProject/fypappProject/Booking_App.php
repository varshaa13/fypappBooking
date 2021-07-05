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
$stmt = $pdo->query("SELECT id FROM student WHERE student.username = '$Student_ID'");

$row = $stmt->fetch(PDO::FETCH_ASSOC);
  $studentAuto = $row['id'];
?>
<?php
if(isset($_POST['submit']))
{

  $times = $_POST['check'];
  foreach($times as $time)
  {

    $stmt = $pdo->prepare('INSERT INTO appointment
       (apppurpose, studentid, timetableid) VALUES (:apppurpose, :studentid , :timetableid)');
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

</head>
<style>
a {
  color:white;
  text-decoration: none;
}
button{
  background-color: #4CAF50;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  float: right;
  margin-left: 6px;
  margin-top: 5px;
}
body{
  background-color: #ffd280;
}
* {
  box-sizing: border-box;
}

input[type=text], input[type=number], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: vertical;
}

label {
  padding: 12px 12px 12px 0;
  display: inline-block;
}

input[type=submit] {
  background-color: #4CAF50;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  float: right;
  margin-left: 6px;
  margin-top: 5px;
}

input[type=submit]:hover {
  background-color: #45a049;
}

.container {
  border-radius: 5px;
  background-color: #fffab5;
  padding: 20px;
}

.col-25 {
  float: left;
  width: 25%;
  margin-top: 6px;
}

.col-75 {
  float: left;
  width: 75%;
  margin-top: 6px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

</style>

<body>
  <script type="text/javascript">
     function emptyValidation() {
       if(document.getElementById('date').selectedIndex==0){
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
       if (document.getElementById('apppurpose').value==""){
          alert("Appointment purpose is required");
          return false;
        }
        return true;
     }
  </script>
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
  <table id="table">
<?php
$Student_ID = $_GET['username'];
$stmt = $pdo->query("SELECT username, name FROM student WHERE student.username = '$Student_ID'");

echo("<th>Student ID:</th>");
echo("<th>Student Name:</th>");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
  echo("<tr><td>");
  echo($row['username']);
  echo("</td><td>");
    echo($row['name']);
echo("</td></tr>\n");

?>
</table>

<div class="container">
<form method ="POST" name="form1" onsubmit="return emptyValidation()" required>
  <div class="row">
    <div class="col-25">
      <label for="date">Date:</label></div>
        <div class="col-75">
  <select name="date" id="date">
         <option disabled selected>--Select date--</option>
<?php
$Student_ID = $_GET['username'];
      $stmt = $pdo->query("SELECT DISTINCT date FROM timetable WHERE lecturerid in (SELECT lecturer.id FROM lecturer JOIN student ON (lecturer.id = student.lecturerid) where student.username = '$Student_ID')");
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                        echo "<option value='" . $row['date'] . "'>" . $row['date']. "</option>";
                      }
                        ?>

</select>

<input type="submit" value="Check For Available time" name="CheckTime">
</div>
</div>
</form>

<div class="row">
  <div class="col-25">
    <label for="time"></label></div>
      <div class="col-75">
<table border="1">
<?php
if(isset($_POST['CheckTime'])){
    $Student_ID = $_GET['username'];
      $Date = $_POST['date'];

                     $stmt = $pdo->query("SELECT DISTINCT id, (concat(startingtime, ' - ', endingtime)) AS Time FROM timetable WHERE lecturerid in (SELECT lecturer.id FROM lecturer JOIN student ON (lecturer.id = student.lecturerid) where student.username = '$Student_ID') AND timetable.date = '$Date'");

echo("<tr><strong>Selected Date:</strong> " .$Date. "</tr>");

                      echo("<p></p>");
                echo("<th>Time:</th>");
                      echo("<th>Availability:</th>");
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

</table>
</div>
</div>
<p></p>

<div class="row">
  <div class="col-25">
    <label for="date">Appointment Purpose:</label></div>
      <div class="col-75">
<input type="text" name="apppurpose" id="apppurpose">
</div>
</div>
<div class="row">
  <div class="col-25">
    <label for="date"></label></div>
      <div class="col-75">

<input type="submit" value="Book" name ="submit">

</form>
<form method="post">
<input type="submit" value = "Back" name= "Back">

</form>
</div>
</div>
</div>
</body>
</html>
