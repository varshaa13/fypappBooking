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

//select statement for display table
//When student_id and Date is not chose
if ( !isset($_POST['username']) && !isset($_POST['date']) ) {

  $stmt = $pdo->query("SELECT appointment.id, appointment.apppurpose, student.username, student.name, timetable.date, timetable.startingtime, timetable.endingtime from appointment join student join timetable on (student.id=appointment.studentid) AND (timetable.id=appointment.timetableid) WHERE timetable.lecturerid='$lecturerAuto'");
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else if( isset($_POST['username']) && !isset($_POST['date']) ) {

  //When Student_ID is chosen
  $Student_ID= htmlentities($_POST['username']);
  $stmt = $pdo->query("SELECT appointment.id,appointment.apppurpose,student.username,
    student.name, timetable.date, timetable.startingtime, timetable.endingtime
    from appointment join student join timetable on
    student.id=appointment.studentid AND
    timetable.id=appointment.timetableid
    WHERE timetable.lecturerid='$lecturerAuto' AND appointment.studentid='$Student_ID'");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  }else if( !isset($_POST['username']) && isset($_POST['date']) ) {

    //When Date is chosen
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

      $Lecturer_ID = $_GET['username'];
      if(isset($_POST['Delete']) && isset($_POST['appid']) ){

        $sql="DELETE FROM appointment WHERE id = :appid";
        echo "<pre>\n$sql\n</pre>\n";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':appid' => $_POST['appid']));
        $_SESSION['success'] = 'Record deleted successfully';
        header("Location: appoinment.php?username=".urlencode($_GET['username']));
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

   <div class="transbox">
   <div class="container-fluid ">
     <div class="container position-relative mt-4">
       <div class="position-absolute top-0 start-50 translate-middle-x text-dark p-4 ">
                <h1><center>Appointment List</center></h1>
                <?php

                if(isset($_SESSION['success'])){
                  echo ('<p style = "color:green;">'.($_SESSION['success'])."</p>\n");
                  unset($_SESSION['success']);
                }

                ?>
        <form method="post">

          <?php
          //display lecturer_ID and Lecturer_Name
          echo "Lecturer ID: " . $_GET['username'];
          echo "<br/>";
          echo "Lecturer Name: " .  ($row['name']);

          ?>
          <br/><br/>

          <!-- dropdown for Student_ID -->
          <select class="form-control input-sm" name="username">
            <option disabled selected>--Select Student_ID--</option>
            <?php

            $stmt = $pdo->query("SELECT id, username FROM student WHERE lecturerid='$lecturerAuto'");
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              echo "<option value='". $row['id'] . "'>". $row['username']. "</option>";

            }
            ?>
          </select><br/>

          <!-- dropdown for Date -->
          <select class="form-control input-sm" name="date">

            <option disabled selected>--Select date--</option>
            <?php

            $stmt = $pdo->query("SELECT DISTINCT date, id FROM timetable WHERE lecturerid='$lecturerAuto'");
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              echo "<option value='". $row['id'] . "'>". $row['date']. "</option>";
            }
            ?>
          </select><br/><br/>

          <!-- Submit Button -->
          <input type="submit" class="btn btn-success" value="Submit" name="submit">
          <input type="submit" class="btn btn-info" value = "Back" name= "Back">
        </br></br>
        </form>
         <table class="table table-striped" >

          <?php
          //table to display appointment infos
          echo("<tr><th>Student ID</th>");
          echo("<th>Student Name</th>");
          echo("<th>Date</th>");
          echo("<th>Starting Time</th>");
          echo("<th>Ending Time</th>");
          echo("<th>Appointment Purpose</th>");
          echo("<th>Action</th></tr>");
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
            echo('name="appid" value="'.$row['id'].'">'."\n");
            echo('<input class="btn btn-danger" type="submit" value="Delete" name="Delete">');
            echo("\n</form>\n");
            echo("</td></tr>\n");
          }
          echo"</table>";
          ?>

      </div>
    </div>
  </div>
</div>
</body>
</html>
