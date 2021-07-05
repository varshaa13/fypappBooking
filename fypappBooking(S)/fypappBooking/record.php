<?php
require_once "pdo.php";
session_start();

if (isset($_POST['Back'])) {
  header("Location: homeLecturer.php?username=".urlencode($_GET['username']));
  return;
}
?>

<?php
if (isset($_POST['reset'])) {
  $_SESSION['attendance'] = Array();
  session_destroy();
  header("Location: record.php?username=".urlencode($_GET['username']));
  return;
}

$studentName = isset($_POST['studentName']) ? $_POST['studentName'] : '';
$matricNum = isset($_POST['matricNum']) ? $_POST['matricNum'] : '';
$dateTime = isset($_POST['dateTime']) ? $_POST['dateTime'] : '';

if (isset($_POST['studentName']) && isset($_POST['matricNum']) && isset($_POST['dateTime'])) {
  $studentName = htmlentities($_POST['studentName']);
  $matricNo = htmlentities($_POST['matricNum']);
  $dateTime = htmlentities($_POST['dateTime']);

  if (!isset($_SESSION['attendance'])) $_SESSION['attendance'] = Array();
  $_SESSION['attendance'] [] = array($studentName, $matricNum, $dateTime);
  header("Location: record.php?username=".urlencode($_GET['username']));
  return;

}
?>
<!DOCTYPE html>
<html>
<head>
  <title>FYP Appointment Booking System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="style.css">
  <script type="text/javascript" src="jquery.min.js"></script>
</head>
<style>

body {
display: flex;
justify-content: center;
align-items: center;
text-align: center;
height: 500px;
width: auto;
}

input[type=text], select, textarea {
width: 100%;
padding: 12px;
border: 1px solid #ccc;
border-radius: 4px;
resize: vertical;
}

h1{
color: white;
}

a{
text-decoration: underline;
}

.textbox{
align-items: center;
}

form{
  text-align: left;
}


</style>

<body>
  <div class="container">
    <form method="post"  action="record.php">
          <div class="transbox">
        <h1><center>Attendance Record</center></h1>

          <div>
            <label for="matricNumber"><strong>Student Name:</strong></label>
          <input class="form-control" type="text" name="studentName" placeholder="Enter the student name" value="<?= htmlentities($studentName) ?>"></div>

        <br/>
          <div>
            <label for="matricNumber"><strong>Matric Number:</strong></label>

            <input class="form-control" type="text" name="matricNum" placeholder="Enter the matric number" value="<?= htmlentities($matricNum) ?>"></div>

          <br/>
          <div>
              <label for="date&time"><strong>Date & Time:</strong></label>

              <input class="form-control" type="datetime-local" name="dateTime" placeholder="Enter the date and time" value="<?= htmlentities($dateTime) ?>"></div>

            <br/>
                <input class="btn btn-success" type="submit" name="submit" value="Record">
                <input class="btn btn-danger" type="submit" name="reset" value="Reset" onclick="return confirm('Are you sure you want to delete?');" >
                <input class="btn btn-info" type="submit" value = "Back" name= "Back">
          </form>
          <br/>
          <div id = "currentattendancelist">
            <img src="spinner.gif" alt="Loading..." width="60" height="60" />
          </div>
        </div>
      </div>
      </div>
      </div>
        <br/>
        <script type="text/javascript">
        function updateAttendance(){
          window.console && console.log('Requesting JSON');
          $.getJSON('attendancelist.php', function(rowz) {
            window.console && console.log('JSON Received');
            window.console && console.log(rowz);
            $("#currentattendancelist").empty();
            for (var i = 0; i < rowz.length; i++) {
              entry = rowz[i];
              $("#currentattendancelist").append('<p>' + entry[0] + '&nbsp;&nbsp;'+ '|' + '&nbsp;&nbsp;'+ entry[1] + '&nbsp;&nbsp;' + '|' + '&nbsp;&nbsp;'+ entry[2] + "</p>\n");
            }
            setTimeout('updateAttendance()', 4000);
          });
        }

        //make sure JSON requests are not cached
        $(document).ready(function() {
          $.ajaxSetup ({
            cache: false
          });
          updateAttendance();
        });
  </script>
 </body>
</html>
