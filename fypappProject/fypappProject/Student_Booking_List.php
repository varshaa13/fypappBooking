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
  <?php

$Student_ID = $_GET['username'];
$stmt = $pdo->query("SELECT id FROM student WHERE student.username = '$Student_ID'");

$row = $stmt->fetch(PDO::FETCH_ASSOC);
  $studentAuto = $row['id'];

  $stmt = $pdo -> query("SELECT appointment.id, timetable.date, timetable.startingtime, timetable.endingtime, appointment.apppurpose FROM timetable JOIN appointment ON (timetable.id = appointment.timetableid) where studentid = '$studentAuto'");
echo '<table border="1">'."\n";
echo "<th>Appointment Date</th>";
echo "<th>Appointment Time</th>";
echo "<th>Appointment Purpose</th>";
echo "<th>Delete</th>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  echo "<tr><td>";
  echo($row['date']);
  echo("</td><td>");
  echo($row['startingtime'] .'-'. $row['endingtime']);
  echo("</td><td>");
  echo($row['apppurpose']);
  echo("</td><td>");
  echo('<form method="post"><input type="hidden"');
  echo('name="appid" value="'.$row['id'].'">'."\n");
  echo('<input type="submit" value="DEL" name="delete">');
  echo("\n</form>\n");
  echo("</td></tr>\n");
}
?>
</table>
<form method="post">
<input type="submit" value = "Back" name= "Back">

</form>
</body>
</html>
