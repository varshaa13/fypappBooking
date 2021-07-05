<?php
require_once "pdo.php";

if (isset($_POST['Back'])) {
  header("Location: homeLecturer.php?username=".urlencode($_GET['username']));
  return;
}
?>
<?php
$Lecturer_ID = $_GET['username'];

$stmt = $pdo->query("SELECT id FROM lecturer WHERE lecturer.username = '$Lecturer_ID'");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$lecturerAuto = $row['id'];
?>

  <?php
//display lecturer name
$stmt = $pdo->query("SELECT username, name FROM lecturer WHERE lecturer.username='$Lecturer_ID'");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<?php
//$Date= htmlentities($_POST['date']);
//if(isset($_POST['submit'])){
  //select statement for display table
  //When student_id and Date is not chose
  if ( !isset($_POST['username']) && !isset($_POST['date']) ) {

    $stmt = $pdo->query("SELECT appointment.id, appointment.apppurpose, student.username, student.name, timetable.date, timetable.startingtime, timetable.endingtime from appointment join student join timetable on (student.id=appointment.studentid) AND (timetable.id=appointment.timetableid) WHERE timetable.lecturerid='$lecturerAuto'");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } else if( isset($_POST['username']) && !isset($_POST['date']) ) {

    //When Student_ID is chosen
    $Student_ID= htmlentities($_POST['username']);
    //$Date= htmlentities($_POST['Date']);

    $stmt = $pdo->query("SELECT appointment.id,appointment.apppurpose,student.username,
                        student.name, timetable.date, timetable.startingtime, timetable.endingtime
                        from appointment join student join timetable on
                        student.id=appointment.studentid AND
                        timetable.id=appointment.timetableid
                        WHERE timetable.lecturerid='$lecturerAuto' AND appointment.studentid='$Student_ID'");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  }else if( !isset($_POST['username']) && isset($_POST['date']) ) {
    //When Date is chosen
    //$Student_ID= htmlentities($_POST['Student_ID']);
    $Date= htmlentities($_POST['date']);

    $stmt = $pdo->query("SELECT appointment.id,appointment.apppurpose,student.username,
                        student.name, timetable.date, timetable.startingtime, timetable.endingtime
                        from appointment join student join timetable on
                        student.id=appointment.studentid AND
                        timetable.id=appointment.timetableid
                        WHERE timetable.lecturerid='$lecturerAuto' AND timetable.id='$Date'");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } else { //When both are chosen
    $Student_ID= htmlentities($_POST['username']);
    $Date= htmlentities($_POST['date']);

    $stmt = $pdo->query("SELECT appointment.id,appointment.apppurpose,student.username,
                        student.name, timetable.date, timetable.startingtime, timetable.endingtime
                        from appointment join student join timetable on
                        student.id=appointment.studentid AND
                        timetable.id=appointment.timetableid
                        WHERE timetable.lecturerid='$lecturerAuto' AND timetable.id='$Date' AND appointment.studentid='$Student_ID'");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  }


?>


<!DOCTYPE html>
<head>
  <title>FYP Appointment Booking System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>
<body>
  <div class="container-fluid ">
  <div class="page-header">
<h1>Time table </h1>
</div>


<form method="post">

<?php
//display lecturer_ID and Lecturer_Name
echo "Lecturer ID: " . $_GET['username'];
echo "<br/>";
echo "Lecturer Name: " .  ($row['name']);

?>
<br/><br/>


<!-- dropdown for Student_ID -->
<select name="username">
         <option disabled selected>--Select Student_ID--</option>
<?php

      $stmt = $pdo->query("SELECT id, username FROM student WHERE lecturerid='$lecturerAuto'");
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              echo "<option value='". $row['id'] . "'>". $row['username']. "</option>";

      }
?>
</select><br/>

<!-- dropdown for Date -->
<select name="date">

         <option disabled selected>--Select date--</option>
<?php

      $stmt = $pdo->query("SELECT DISTINCT id, date FROM timetable WHERE lecturerid='$lecturerAuto'");
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              echo "<option value='". $row['id'] . "'>". $row['date']. "</option>";
  }
?>
</select><br/><br/>

<!-- Submit Button -->
<input type="submit" class="btn btn-info" value="Submit" name="submit">
<input type="submit" value = "Back" name= "Back"><br/><br/>
</form>
<body><table border="1">

<?php
//table to display appointment infos
echo("<tr><th>Student ID</th>");
echo("<th>Student Name</th>");
echo("<th>Date</th>");
echo("<th>Starting Time</th>");
echo("<th>Ending Time</th>");
echo("<th>Appointment Purpose</th></tr>");
foreach($rows as $row){
  echo("<tr><td>");
  echo($row['username']);
  echo("</td><td>");
  echo($row['name']);
  echo("</td><td>");
  echo($row['date']);
  echo("</td><td>");
  echo($row['startingtime']);
  echo("</td><td>");
  echo($row['endingtime']);
  echo("</td><td>");
  echo($row['apppurpose']);
  echo("</td></tr>\n");
}
?>

</table>
</div>
</body>
</html>
