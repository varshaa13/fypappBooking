<?php
require_once "pdo.php";

session_start();

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
$stmt = $pdo->query("SELECT appointment.id, appointment.apppurpose, appointment.status, student.username, student.name, timetable.date, timetable.startingtime, timetable.endingtime FROM appointment JOIN student JOIN timetable ON (student.id=appointment.studentid) AND (timetable.id=appointment.timetableid) WHERE appointment.status='Pending' AND timetable.lecturerid='$lecturerAuto'");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
if (isset($_POST['approve'])) {
  $sql="UPDATE appointment SET status='Approved' WHERE id=:id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':id'=> $_POST['appointmentid'] )
  );

  $_SESSION['success']='Appointment Approved';
  header("Location: requests.php?username=".urlencode($_GET['username']));
  return;
}
?>

<?php
if (isset($_POST['reject'])) {
  $sql="UPDATE appointment SET status='Rejected' WHERE id=:id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':id'=> $_POST['appointmentid'] )
  );

  $sql1="UPDATE timetable SET status='Available' WHERE id= (SELECT appointment.timetableid FROM appointment WHERE id=:id)";
  $stmt = $pdo->prepare($sql1);
  $stmt->execute(array(
    ':id'=> $_POST['appointmentid'] )
  );

  $_SESSION['success']='Appointment Rejected';
  header("Location: requests.php?username=".urlencode($_GET['username']));
  return;
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
  <div class="container-fluid ">

    <div class="container position-relative mt-4">
          <div class="position-absolute top-0 start-50 translate-middle-x text-dark p-4 transbox" style="background:rgba(255,255,255,.8)">
<h1><center>Appointment Requests</center></h1>

<form method="post">

<?php
//display lecturer_ID and Lecturer_Name
echo "Lecturer ID: " . $_GET['username'];
echo "<br/>";
echo "Lecturer Name: " .  ($row['name']);

?>
<br/><br/>

<body><table class="table table-striped">

<?php
if(!$rows) {
  echo("<p class='text-center'>No Appointment Requests</p>");
} else {
  //table to display appointment infos
  echo("<tr><th>Student ID</th>");
  echo("<th>Student Name</th>");
  echo("<th>Date</th>");
  echo("<th>Starting Time</th>");
  echo("<th>Ending Time</th>");
  echo("<th>Appointment Purpose</th>");
  echo("<th>Appointment Status</th></tr>");

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
    echo("</td><td>");
    echo('<form method="post"><input type="hidden"');
    echo('name="appointmentid" value="'.$row['id'].'">'."\n");
    echo('<input class="btn btn-success" type="submit" value="Approved" name="approve"> ');
    echo('<input class="btn btn-danger" type="submit" value="Rejected" name="reject"> ');
    echo("\n</form>\n");
    echo("</td></tr>\n");
  }
}
?>

</table>
<br/>
<input class="btn btn-info" type="submit" value = "Back" name= "Back"><br/><br/>
</form>
</div>
</div>
</body>
</html>
