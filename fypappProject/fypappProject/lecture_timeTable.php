<?php
session_start();
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

if ( isset($_POST['submit']) ) {

if(isset($_POST['date']) && isset($_POST['startingtime']) && isset($_POST['endingtime'])){

  $date          = htmlentities($_POST['date']);
  $startingtime = htmlentities($_POST['startingtime']);
  $endingtime   = htmlentities($_POST['endingtime']);

  $stmt = $pdo->prepare("INSERT INTO timetable (date, startingtime, endingtime, lecturerid)
  VALUES (:date, :startingtime, :endingtime, :lecturerid)");
    $stmt->execute(array(


      ':date'          => $date,
      ':startingtime' => $startingtime,
      ':endingtime'   => $endingtime,
    ':lecturerid'   => $lecturerAuto)
    );
}
    $_SESSION['success'] = "Record inserted successfully";
    header("Location: lecture_timeTable.php?username=".urlencode($_GET['username']));
    return;

  }
$Lecturer_ID = $_GET['username'];
//delete
  if (isset($_POST['delete']) && isset($_POST['timetableid']) ) {

  $stmt = $pdo->prepare("DELETE FROM timetable WHERE id = :timetableid");
  $stmt->execute(array(
    ':timetableid' => $_POST['timetableid']
  ));

  $_SESSION['success'] = "Record deleted successfully";
  header("Location: lecture_timeTable.php?username=".urlencode($_GET['username']));
  return;

}





?>


<!DOCTYPE html>
<head>
  <title>VARSHAA A/P MUNIANDY 202378</title>
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

<?php

if(isset($_SESSION['success'])){
  echo ('<p style = "color:green;">'. htmlentities($_SESSION['success'])."</p>\n");
  unset($_SESSION['success']);

}

 ?>

<form method="post">

  <label for="Date" class="control-label">Date</label>
  <input type="date"  name="date" class="form-control">

  <label for="Starting_Time" class="control-label">Starting Time</label>
  <input type="time"  name="startingtime" class="form-control">

  <label for="Ending_Time" class="control-label">Ending Time</label>
  <input type="time"  name="endingtime" class="form-control" ><br/>

  <input type="submit" class="btn btn-info" value="Submit" name="submit">
  <input type="submit" value = "Back" name= "Back">

<table border="1">

  <?php
  $lecturerAuto = $row['id'];
  $stmt = $pdo->query("SELECT timetable.id, timetable.date, timetable.startingtime, timetable.endingtime from timetable JOIN lecturer ON(timetable.lecturerid = lecturer.id) where lecturerid='$lecturerAuto'");


    echo("<tr><th>Date</th>");
    echo("<th>Starting Time</th>");
    echo("<th>Ending Time</th></tr>");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      echo("<tr><td>");
      echo($row['date']);
      echo("</td><td>");
      echo($row['startingtime']);
      echo("</td><td>");
      echo($row['endingtime']);
      echo("</td><td>");
            //A new form to delete

            echo('<a href="editTimetable.php?id='.$row['id'].'">Edit</a> ');
            echo('<form method="post"><input type="hidden"');
            echo('name="timetableid" value="'.$row['id'].'">'."\n");
            echo('<input class="btn btn-danger" type="submit" value="Delete" name="delete"> ');
            echo("\n</form>\n");
      echo("</td></tr>\n");
}
   ?>
</table>

</form>
</div>
</body>
</html>
